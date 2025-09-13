<?php

namespace App\Http\Controllers\Backend\Farhad;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:user_view')->only(['index', 'show']);
        $this->middleware('can:user_create')->only(['create', 'store']);
        $this->middleware('can:user_edit')->only(['edit', 'update']);
        $this->middleware('can:user_delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all users with their roles (optional eager loading)
        $users = User::with('roles')->orderBy('created_at', 'desc')->get();

        return view('backend.layouts.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all(); // Fetch all roles from Spatie
        return view('backend.layouts.user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'role' => 'required|exists:roles,id',
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        // Handle image upload
        $imageUrl = null; // default

        if ($request->file('image')) {
            $image     = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $directory = 'uploads/users-images/';
            // Create directory if it doesn't exist
            if (!file_exists(public_path($directory))) {
                mkdir(public_path($directory), 0755, true);
            }
            $resizedImage = Image::make($image)->resize(150, 150); // Resize to 150x150 used image intervention
            $resizedImage->save(public_path($directory . $imageName));
            $imageUrl = $directory . $imageName;
        }

        // Create User
        $user = User::create([
            'image' => $imageUrl,
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign Role using Spatie
        $role = Role::find($request->role);
        if ($role) {
            $user->assignRole($role->name);
        }

        return redirect()->route('admin.users.index')->with('success', 'User created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all(); // Get all roles from Spatie
        $userRole = $user->roles->pluck('id')->first(); // Get assigned role

        return view('backend.layouts.user.edit', compact('user', 'roles', 'userRole'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|exists:roles,id',
            'password' => 'nullable|confirmed|min:6',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048'
        ]);

        // Handle Image Upload
        $imageUrl = $user->image; // Keep old image by default
        if ($request->file('image')) {
            if ($user->image && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $directory = 'uploads/users-images/';
            // Create directory if it doesn't exist
            if (!file_exists(public_path($directory))) {
                mkdir(public_path($directory), 0755, true);
            }
            $resizedImage = Image::make($image)->resize(150, 150);
            $resizedImage->save(public_path($directory . $imageName));
            $imageUrl = $directory . $imageName;
        }
        // If Dropify removed the image
        elseif ($request->input('image') === null) {
            if ($user->image && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }
            $imageUrl = null;
        }

        // Prepare data for mass assignment
        $updateData = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'image' => $imageUrl,
        ];

        if ($request->password) {
            $updateData['password'] = Hash::make($request->password);
        }

        // Update user using mass assignment
        $user->update($updateData);

        // Sync Role
        $role = Role::find($request->role);
        if ($role) {
            $user->syncRoles([$role->name]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Delete user image if exists
        if ($user->image && file_exists(public_path($user->image))) {
            unlink(public_path($user->image));
        }

        // Delete the user
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Verify authenticated user's password via AJAX
     */
    public function verifyPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = auth()->user();

        if (Hash::check($request->password, $user->password)) {
            return response()->json(['success' => true]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Incorrect password'
        ]);
    }
}

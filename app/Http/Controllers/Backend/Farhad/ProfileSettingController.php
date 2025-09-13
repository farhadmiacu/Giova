<?php

namespace App\Http\Controllers\Backend\Farhad;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use App\Models\User;

class ProfileSettingController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('backend.layouts.profile-setting.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        // Prevent unauthorized updates
        if ($user->id != $id) {
            abort(403, 'Unauthorized action.');
        }

        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // Update basic info
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;

        // -------------------------
        // Image update / keep / remove
        // -------------------------
        $imageUrl = $user->image; // default: keep old image

        if ($request->hasFile('image')) {
            // Delete old if exists
            if ($user->image && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }

            // Upload new
            $image     = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $directory = 'uploads/users/';

            if (!file_exists(public_path($directory))) {
                mkdir(public_path($directory), 0755, true);
            }

            // Resize + save
            $resizedImage = Image::make($image)->resize(300, 300);
            $resizedImage->save(public_path($directory . $imageName));

            $imageUrl = $directory . $imageName;
        }

        // If explicitly removed via Dropify
        if ($request->remove_image == 1) {
            if ($user->image && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }
            $imageUrl = null;
        }

        $user->image = $imageUrl;

        // -------------------------
        // Password update (if entered)
        // -------------------------
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()
            ->route('admin.profile-settings.edit')
            ->with('success', 'Profile updated successfully!');
    }
}

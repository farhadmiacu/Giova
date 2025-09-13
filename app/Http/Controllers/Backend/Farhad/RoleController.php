<?php

namespace App\Http\Controllers\Backend\Farhad;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:role_view')->only(['index', 'show']);
        $this->middleware('can:role_create')->only(['create', 'store']);
        $this->middleware('can:role_edit')->only(['edit', 'update']);
        $this->middleware('can:role_delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        // Get all roles with their permissions
        $roles = Role::with('permissions')->get();

        // Pass roles to the view
        return view('backend.layouts.role.index', compact('roles'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('backend.layouts.role.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return 'ok';
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        // Create Role
        $role = Role::create(['name' => $request->name]);

        // Sync Permissions using names (since your Blade sends names)
        $role->syncPermissions($request->permissions);

        // return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
        return redirect()->back()->with('success', 'Role created successfully.');
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
        // Find the role by ID
        $role = Role::findOrFail($id);

        // Get all permissions
        $permissions = Permission::all();

        // Get the permission IDs assigned to this role
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        // Pass data to the edit view
        return view('backend.layouts.role.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Find the role by ID
        $role = Role::findOrFail($id);

        // Validate input
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        // Update role name
        $role->name = $request->name;
        $role->save();

        // Sync permissions (based on names, because Blade sends names)
        $role->syncPermissions($request->permissions);

        // Redirect back with success message
        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Find the role by ID
        $role = Role::findOrFail($id);

        //  prevent deleting critical roles like 'admin'
        if (strtolower($role->name) === 'admin') {
            return redirect()->route('admin.roles.index')->with('error', 'Cannot delete the admin role.');
        }

        // Delete the role
        $role->delete(); // also Spatie’s package automatically detaches associated permissions.

        // Redirect back with success message
        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }
}

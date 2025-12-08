<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of roles
     */
    public function index()
    {
        if (!auth()->user()->hasPermission('manage_roles')) {
            abort(403, 'You do not have permission to manage roles');
        }
        
        $roles = Role::with('permissions')->get();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show role edit form
     */
    public function edit(Role $role)
    {
        if (!auth()->user()->hasPermission('manage_roles')) {
            abort(403, 'You do not have permission to manage roles');
        }
        
        $permissions = Permission::all();
        $selectedPermissions = $role->permissions()->pluck('id')->toArray();
        
        return view('roles.edit', compact('role', 'permissions', 'selectedPermissions'));
    }

    /**
     * Update role permissions
     */
    public function update(Request $request, Role $role)
    {
        if (!auth()->user()->hasPermission('manage_roles')) {
            abort(403, 'You do not have permission to manage roles');
        }
        
        $request->validate([
            'permissions' => 'array',
        ]);

        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('roles.index')->with('success', 'Role permissions updated successfully');
    }

    /**
     * Display user management
     */
    public function manageUsers()
    {
        if (!auth()->user()->hasPermission('manage_users')) {
            abort(403, 'You do not have permission to manage users');
        }
        
        $users = User::with('roleModel')->get();
        $roles = Role::all();
        
        return view('roles.users', compact('users', 'roles'));
    }

    /**
     * Create a new user
     */
    public function createUser(Request $request)
    {
        if (!auth()->user()->hasPermission('manage_users')) {
            abort(403, 'You do not have permission to manage users');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id,
            'role' => 'user',
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ], 201);
    }

    /**
     * Update user role
     */
    public function updateUserRole(Request $request, User $user)
    {
        if (!auth()->user()->hasPermission('manage_users')) {
            abort(403, 'You do not have permission to manage users');
        }
        
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $role = Role::find($request->role_id);
        $user->update([
            'role_id' => $role->id,
            'role' => 'user',
        ]);

        return response()->json(['message' => 'User role updated successfully']);
    }
}

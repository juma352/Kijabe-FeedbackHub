@extends('layouts.app')

@section('title', 'Manage Roles')

@section('content')
<div class="container-fluid px-6 py-8">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Role Management</h1>
            <p class="text-gray-600 mt-2">Manage roles and their permissions</p>
        </div>
    </div>

    <!-- Roles Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">System Roles</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permissions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($roles as $role)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $role->display_name }}</div>
                            <div class="text-xs text-gray-500">{{ $role->name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-600">{{ $role->description ?? 'No description' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @forelse($role->permissions as $permission)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $permission->display_name }}
                                    </span>
                                @empty
                                    <span class="text-sm text-gray-500">No permissions</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('roles.edit', $role) }}" 
                               class="text-blue-600 hover:text-blue-900 p-2 rounded hover:bg-blue-50">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            No roles found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Edit Role: ' . $role->display_name)

@section('content')
<div class="container-fluid px-6 py-8">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Role: {{ $role->display_name }}</h1>
            <p class="text-gray-600 mt-2">Manage permissions for {{ $role->display_name }}</p>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-8">
        <form action="{{ route('roles.update', $role) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Role Info -->
            <div class="mb-8 pb-8 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Role Information</h2>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input type="text" value="{{ $role->display_name }}" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-50" disabled>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">System Name</label>
                        <input type="text" value="{{ $role->name }}" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-50" disabled>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-50" disabled>{{ $role->description }}</textarea>
                </div>
            </div>

            <!-- Permissions -->
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Permissions</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($permissions as $permission)
                    <div class="flex items-start">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                               id="permission_{{ $permission->id }}"
                               class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                               @if(in_array($permission->id, $selectedPermissions)) checked @endif>
                        <label for="permission_{{ $permission->id }}" class="ml-3 flex-1">
                            <div class="text-sm font-medium text-gray-900">{{ $permission->display_name }}</div>
                            <p class="text-xs text-gray-500">{{ $permission->description ?? '' }}</p>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('roles.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

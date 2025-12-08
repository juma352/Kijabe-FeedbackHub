@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="container-fluid px-6 py-8">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">User Management</h1>
            <p class="text-gray-600 mt-2">Assign roles to users</p>
        </div>
        <button onclick="openCreateUserModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
            <i class="fas fa-plus mr-2"></i>Add New User
        </button>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">System Users</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->isAdmin())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                    Administrator
                                </span>
                            @elseif($user->roleModel)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $user->roleModel->display_name }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    Unassigned
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->email_verified_at)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i> Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i> Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if(!$user->isAdmin())
                            <button class="text-blue-600 hover:text-blue-900 p-2 rounded hover:bg-blue-50"
                                    onclick="editUserRole('{{ $user->id }}', '{{ $user->name }}', '{{ $user->roleModel->id ?? '' }}')">
                                <i class="fas fa-edit"></i> Edit Role
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            No users found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create User Modal -->
<div id="create-user-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Add New User</h3>
                <button onclick="closeCreateUserModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="create-user-form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" name="name" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" name="email" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Role</label>
                    <select name="role_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">-- Select a role --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeCreateUserModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Role Modal -->
<div id="edit-role-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Change User Role</h3>
                <button onclick="closeRoleModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="role-form">
                <input type="hidden" id="user_id">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">User Name</label>
                    <input type="text" id="user_name" class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-50" disabled>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Role</label>
                    <select name="role_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Select a role --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRoleModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Update Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editUserRole(userId, userName, roleId) {
    document.getElementById('user_id').value = userId;
    document.getElementById('user_name').value = userName;
    document.querySelector('select[name="role_id"]').value = roleId;
    document.getElementById('edit-role-modal').classList.remove('hidden');
}

function closeRoleModal() {
    document.getElementById('edit-role-modal').classList.add('hidden');
}

document.getElementById('role-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const userId = document.getElementById('user_id').value;
    const roleId = document.querySelector('select[name="role_id"]').value;
    
    if (!roleId) {
        alert('Please select a role');
        return;
    }
    
    fetch(`/users/${userId}/role`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: JSON.stringify({ role_id: roleId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            alert(data.message);
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to update user role');
    });
});

// Create User Modal Functions
function openCreateUserModal() {
    document.getElementById('create-user-modal').classList.remove('hidden');
}

function closeCreateUserModal() {
    document.getElementById('create-user-modal').classList.add('hidden');
    document.getElementById('create-user-form').reset();
}

// Close modal when clicking outside
document.getElementById('create-user-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCreateUserModal();
    }
});

document.getElementById('create-user-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Validate required fields
    if (!formData.get('name') || !formData.get('email') || !formData.get('password') || !formData.get('password_confirmation') || !formData.get('role_id')) {
        alert('Please fill in all required fields');
        return;
    }
    
    // Validate password confirmation
    if (formData.get('password') !== formData.get('password_confirmation')) {
        alert('Passwords do not match');
        return;
    }
    
    fetch('/users/create', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            alert(data.message);
            closeCreateUserModal();
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to create user');
    });
});
</script>
@endsection

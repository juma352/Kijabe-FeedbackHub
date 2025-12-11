<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Department Heads Management
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        <div class="bg-white shadow-sm rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Department Contacts</h3>
                    <p class="text-sm text-gray-600 mt-1">Manage department heads and notification recipients</p>
                </div>
                <button onclick="openAddDepartmentModal()" 
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm font-medium">
                    <i class="fas fa-plus mr-2"></i> Add Department
                </button>
            </div>

            <div class="p-6">
                <div class="space-y-4">
                    @foreach($departments as $department)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50" id="dept-{{ $department->id }}">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-3">
                                        <h4 class="text-lg font-semibold text-gray-900">{{ $department->department_name }}</h4>
                                        <span class="px-2 py-1 text-xs rounded-full {{ $department->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $department->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <span class="text-gray-600">Department Head:</span>
                                            <span class="ml-2 font-medium text-gray-900" id="name-{{ $department->id }}">{{ $department->head_name }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Email:</span>
                                            <span class="ml-2 text-blue-600" id="email-{{ $department->id }}">{{ $department->head_email }}</span>
                                        </div>
                                        <div class="md:col-span-2">
                                            <span class="text-gray-600">CC Emails:</span>
                                            <span class="ml-2 text-gray-900" id="cc-{{ $department->id }}">{{ $department->cc_emails ?? 'None' }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <button onclick="editDepartment({{ $department->id }})" 
                                        class="ml-4 px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="edit-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Edit Department Contact</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="edit-form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="dept-id">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Department Name</label>
                            <input type="text" id="edit-dept-name" disabled 
                                   class="w-full rounded-md border-gray-300 bg-gray-50">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Head Name *</label>
                            <input type="text" name="head_name" id="edit-head-name" required
                                   class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Head Email *</label>
                            <input type="email" name="head_email" id="edit-head-email" required
                                   class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">CC Emails (comma-separated)</label>
                            <textarea name="cc_emails" id="edit-cc-emails" rows="2"
                                      class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                      placeholder="email1@example.com, email2@example.com"></textarea>
                            <p class="text-xs text-gray-500 mt-1">Additional recipients who will receive notifications</p>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="edit-is-active" value="1"
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label class="ml-2 text-sm text-gray-700">Active (receives notifications)</label>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeModal()" 
                                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Department Modal -->
    <div id="add-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Add New Department</h3>
                    <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="add-form">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="add-dept-name" class="block text-sm font-medium text-gray-700">Department Name</label>
                            <input type="text" id="add-dept-name" name="department_name" required
                                   class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="e.g., Pediatrics">
                        </div>
                        
                        <div>
                            <label for="add-head-name" class="block text-sm font-medium text-gray-700">Department Head Name</label>
                            <input type="text" id="add-head-name" name="head_name" required
                                   class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Head of department">
                        </div>
                        
                        <div>
                            <label for="add-head-email" class="block text-sm font-medium text-gray-700">Head Email</label>
                            <input type="email" id="add-head-email" name="head_email" required
                                   class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="head@example.com">
                        </div>
                        
                        <div>
                            <label for="add-cc-emails" class="block text-sm font-medium text-gray-700">CC Emails (Optional)</label>
                            <textarea id="add-cc-emails" name="cc_emails" rows="2"
                                      class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                      placeholder="email1@example.com, email2@example.com"></textarea>
                            <p class="text-xs text-gray-500 mt-1">Additional recipients who will receive notifications</p>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="add-is-active" value="1" checked
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label class="ml-2 text-sm text-gray-700">Active (receives notifications)</label>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeAddModal()" 
                                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            <i class="fas fa-plus mr-1"></i> Add Department
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
const departments = @json($departments);

function editDepartment(id) {
    const dept = departments.find(d => d.id === id);
    if (!dept) return;
    
    document.getElementById('dept-id').value = dept.id;
    document.getElementById('edit-dept-name').value = dept.department_name;
    document.getElementById('edit-head-name').value = dept.head_name;
    document.getElementById('edit-head-email').value = dept.head_email;
    document.getElementById('edit-cc-emails').value = dept.cc_emails || '';
    document.getElementById('edit-is-active').checked = dept.is_active;
    
    document.getElementById('edit-modal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('edit-modal').classList.add('hidden');
}

function openAddDepartmentModal() {
    document.getElementById('add-form').reset();
    document.getElementById('add-is-active').checked = true;
    document.getElementById('add-modal').classList.remove('hidden');
}

function closeAddModal() {
    document.getElementById('add-modal').classList.add('hidden');
}

document.getElementById('add-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = {
        department_name: document.getElementById('add-dept-name').value,
        head_name: document.getElementById('add-head-name').value,
        head_email: document.getElementById('add-head-email').value,
        cc_emails: document.getElementById('add-cc-emails').value,
        is_active: document.getElementById('add-is-active').checked ? 1 : 0,
    };
    
    fetch('/departments', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message || 'Failed to add department');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to add department');
    });
});

document.getElementById('edit-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const deptId = document.getElementById('dept-id').value;
    const formData = {
        head_name: document.getElementById('edit-head-name').value,
        head_email: document.getElementById('edit-head-email').value,
        cc_emails: document.getElementById('edit-cc-emails').value,
        is_active: document.getElementById('edit-is-active').checked ? 1 : 0,
    };
    
    fetch(`/departments/${deptId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to update department');
    });
});

// Close modal when clicking outside
document.getElementById('edit-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Close add modal when clicking outside
document.getElementById('add-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddModal();
    }
});
</script>

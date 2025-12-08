<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Form') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Builder -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <!-- Form Header -->
                    <div class="px-6 py-4 border-b border-gray-200">
                        <input type="text" id="form-title" placeholder="Untitled Form" 
                               class="text-2xl font-bold border-none outline-none focus:ring-0 bg-transparent w-full text-gray-900 placeholder-gray-400">
                        <textarea id="form-description" placeholder="Form description (optional)" 
                                  class="mt-2 w-full border-none outline-none focus:ring-0 bg-transparent text-gray-600 placeholder-gray-400 resize-none" 
                                  rows="2"></textarea>
                    </div>

                    <!-- Form Fields -->
                    <div id="form-fields" class="p-6 min-h-96">
                        <div id="field-container" class="space-y-4">
                            <!-- Dynamic fields will be added here -->
                        </div>

                        <!-- Empty state -->
                        <div id="empty-state" class="text-center py-12">
                            <i class="fas fa-plus-circle text-gray-300 text-6xl mb-4"></i>
                            <p class="text-gray-500 mb-4">Start building your form by adding fields from the sidebar</p>
                            <p class="text-sm text-gray-400">Drag and drop field types from the right panel</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Field Types Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 sticky top-6">
                    <!-- Field Types -->
                    <div class="px-4 py-3 border-b border-gray-200">
                        <h3 class="font-semibold text-gray-900">Field Types</h3>
                    </div>

                    <div class="p-4 space-y-2" id="field-types">
                        <!-- Text Fields -->
                        <div class="field-type cursor-pointer p-3 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors duration-200" 
                             data-type="text" data-icon="fas fa-font" data-label="Text">
                            <div class="flex items-center">
                                <i class="fas fa-font text-blue-600 w-5 mr-3"></i>
                                <div>
                                    <div class="font-medium text-gray-900">Text</div>
                                    <div class="text-sm text-gray-500">Single line text input</div>
                                </div>
                            </div>
                        </div>

                        <div class="field-type cursor-pointer p-3 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors duration-200" 
                             data-type="textarea" data-icon="fas fa-align-left" data-label="Textarea">
                            <div class="flex items-center">
                                <i class="fas fa-align-left text-blue-600 w-5 mr-3"></i>
                                <div>
                                    <div class="font-medium text-gray-900">Textarea</div>
                                    <div class="text-sm text-gray-500">Multi-line text input</div>
                                </div>
                            </div>
                        </div>

                        <div class="field-type cursor-pointer p-3 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors duration-200" 
                             data-type="email" data-icon="fas fa-envelope" data-label="Email">
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-blue-600 w-5 mr-3"></i>
                                <div>
                                    <div class="font-medium text-gray-900">Email</div>
                                    <div class="text-sm text-gray-500">Email address input</div>
                                </div>
                            </div>
                        </div>

                        <div class="field-type cursor-pointer p-3 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors duration-200" 
                             data-type="number" data-icon="fas fa-hashtag" data-label="Number">
                            <div class="flex items-center">
                                <i class="fas fa-hashtag text-blue-600 w-5 mr-3"></i>
                                <div>
                                    <div class="font-medium text-gray-900">Number</div>
                                    <div class="text-sm text-gray-500">Numeric input</div>
                                </div>
                            </div>
                        </div>

                        <!-- Selection Fields -->
                        <div class="field-type cursor-pointer p-3 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors duration-200" 
                             data-type="select" data-icon="fas fa-list" data-label="Dropdown">
                            <div class="flex items-center">
                                <i class="fas fa-list text-blue-600 w-5 mr-3"></i>
                                <div>
                                    <div class="font-medium text-gray-900">Dropdown</div>
                                    <div class="text-sm text-gray-500">Select one option</div>
                                </div>
                            </div>
                        </div>

                        <div class="field-type cursor-pointer p-3 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors duration-200" 
                             data-type="radio" data-icon="fas fa-dot-circle" data-label="Radio Buttons">
                            <div class="flex items-center">
                                <i class="fas fa-dot-circle text-blue-600 w-5 mr-3"></i>
                                <div>
                                    <div class="font-medium text-gray-900">Radio Buttons</div>
                                    <div class="text-sm text-gray-500">Select one option</div>
                                </div>
                            </div>
                        </div>

                        <div class="field-type cursor-pointer p-3 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors duration-200" 
                             data-type="checkbox" data-icon="fas fa-check-square" data-label="Checkboxes">
                            <div class="flex items-center">
                                <i class="fas fa-check-square text-blue-600 w-5 mr-3"></i>
                                <div>
                                    <div class="font-medium text-gray-900">Checkboxes</div>
                                    <div class="text-sm text-gray-500">Select multiple options</div>
                                </div>
                            </div>
                        </div>

                        <!-- Rating & Feedback -->
                        <div class="field-type cursor-pointer p-3 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors duration-200" 
                             data-type="rating" data-icon="fas fa-star" data-label="Rating">
                            <div class="flex items-center">
                                <i class="fas fa-star text-blue-600 w-5 mr-3"></i>
                                <div>
                                    <div class="font-medium text-gray-900">Rating</div>
                                    <div class="text-sm text-gray-500">Star or numeric rating</div>
                                </div>
                            </div>
                        </div>

                        <div class="field-type cursor-pointer p-3 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors duration-200" 
                             data-type="scale" data-icon="fas fa-sliders-h" data-label="Scale">
                            <div class="flex items-center">
                                <i class="fas fa-sliders-h text-blue-600 w-5 mr-3"></i>
                                <div>
                                    <div class="font-medium text-gray-900">Scale</div>
                                    <div class="text-sm text-gray-500">Linear scale (1-10)</div>
                                </div>
                            </div>
                        </div>

                        <!-- Advanced -->
                        <div class="field-type cursor-pointer p-3 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors duration-200" 
                             data-type="date" data-icon="fas fa-calendar" data-label="Date">
                            <div class="flex items-center">
                                <i class="fas fa-calendar text-blue-600 w-5 mr-3"></i>
                                <div>
                                    <div class="font-medium text-gray-900">Date</div>
                                    <div class="text-sm text-gray-500">Date picker</div>
                                </div>
                            </div>
                        </div>

                        <div class="field-type cursor-pointer p-3 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors duration-200" 
                             data-type="file" data-icon="fas fa-file-upload" data-label="File Upload">
                            <div class="flex items-center">
                                <i class="fas fa-file-upload text-blue-600 w-5 mr-3"></i>
                                <div>
                                    <div class="font-medium text-gray-900">File Upload</div>
                                    <div class="text-sm text-gray-500">File attachment</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Settings -->
                    <div class="border-t border-gray-200 p-4">
                        <h4 class="font-medium text-gray-900 mb-3">Form Settings</h4>
                        
                        <div class="space-y-3">
                            <!-- Department Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Department <span class="text-red-500">*</span></label>
                                <select id="form-department" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">-- Select Department --</option>
                                    @foreach($departments as $key => $name)
                                    <option value="{{ $key }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Sub-Department Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sub-Department <span class="text-red-500">*</span></label>
                                <select id="form-sub-department" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" disabled>
                                    <option value="">-- Select Sub-Department --</option>
                                </select>
                            </div>

                            <label class="flex items-center">
                                <input type="checkbox" id="form-public" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Make form public</span>
                            </label>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Expires At (Optional)</label>
                                <input type="datetime-local" id="form-expires" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="border-t border-gray-200 p-4 space-y-2">
                        <button onclick="previewForm()" class="w-full bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md transition duration-200">
                            <i class="fas fa-eye mr-2"></i>
                            Preview Form
                        </button>
                        <button onclick="saveForm()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-200">
                            <i class="fas fa-save mr-2"></i>
                            Save Form
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Field Configuration Modal -->
    <div id="field-config-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-md w-full max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900" id="modal-title">Configure Field</h3>
                        <button onclick="closeFieldModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="p-6" id="modal-content">
                    <!-- Dynamic content will be loaded here -->
                </div>

                <div class="sticky bottom-0 bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex justify-end space-x-3">
                        <button onclick="closeFieldModal()" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Cancel
                        </button>
                        <button onclick="saveFieldConfig()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Save Field
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
let formFields = [];
let currentFieldId = null;
let fieldCounter = 0;

// Department and Sub-Department mapping
const departmentSubDepartments = {
    'kchs': {
        'basic': 'Basic and Post Basic'
    },
    'research': {
        'research': 'Research'
    },
    'gme': {
        'interns_residents': 'Interns and Residents',
        'visitor': 'Visitor'
    },
    'cpd': {
        'elearning': 'E-Learning',
        'simulation': 'Simulation',
        'short_courses': 'Short Courses'
    }
};

// Initialize drag and drop
document.addEventListener('DOMContentLoaded', function() {
    const fieldTypes = document.querySelectorAll('.field-type');
    
    fieldTypes.forEach(fieldType => {
        fieldType.addEventListener('click', function() {
            const type = this.getAttribute('data-type');
            addField(type);
        });
    });

    // Handle department selection
    const departmentSelect = document.getElementById('form-department');
    const subDepartmentSelect = document.getElementById('form-sub-department');

    departmentSelect.addEventListener('change', function() {
        const department = this.value;
        subDepartmentSelect.innerHTML = '<option value="">-- Select Sub-Department --</option>';
        
        if (department && departmentSubDepartments[department]) {
            const subDepts = departmentSubDepartments[department];
            Object.entries(subDepts).forEach(([key, label]) => {
                const option = document.createElement('option');
                option.value = key;
                option.textContent = label;
                subDepartmentSelect.appendChild(option);
            });
            subDepartmentSelect.disabled = false;
        } else {
            subDepartmentSelect.disabled = true;
        }
    });
});

function addField(type) {
    fieldCounter++;
    const fieldId = `field_${fieldCounter}`;
    
    const field = {
        id: fieldId,
        type: type,
        key: fieldId,
        label: getDefaultLabel(type),
        required: false,
        placeholder: '',
        options: type === 'select' || type === 'radio' || type === 'checkbox' ? [
            {label: 'Option 1', value: 'option1'},
            {label: 'Option 2', value: 'option2'}
        ] : null,
        maxRating: type === 'rating' ? 5 : null,
        maxScale: type === 'scale' ? 10 : null,
        minScale: type === 'scale' ? 1 : null
    };
    
    formFields.push(field);
    renderFields();
    
    // Hide empty state
    document.getElementById('empty-state').style.display = 'none';
}

function getDefaultLabel(type) {
    const labels = {
        'text': 'Text Field',
        'textarea': 'Long Text Field', 
        'email': 'Email Address',
        'number': 'Number Field',
        'select': 'Dropdown Field',
        'radio': 'Radio Button Field',
        'checkbox': 'Checkbox Field',
        'rating': 'Rating Field',
        'scale': 'Scale Field',
        'date': 'Date Field',
        'file': 'File Upload Field'
    };
    return labels[type] || 'Field';
}

function renderFields() {
    const container = document.getElementById('field-container');
    container.innerHTML = '';
    
    formFields.forEach((field, index) => {
        const fieldHtml = createFieldHtml(field, index);
        container.innerHTML += fieldHtml;
    });
}

function createFieldHtml(field, index) {
    const isRequired = field.required ? '<span class="text-red-500">*</span>' : '';
    
    let fieldInput = '';
    
    switch(field.type) {
        case 'text':
        case 'email':
        case 'number':
            fieldInput = `<input type="${field.type}" placeholder="${field.placeholder || 'Enter ' + field.label.toLowerCase()}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" disabled>`;
            break;
            
        case 'textarea':
            fieldInput = `<textarea placeholder="${field.placeholder || 'Enter ' + field.label.toLowerCase()}" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" disabled></textarea>`;
            break;
            
        case 'select':
            const selectOptions = field.options ? field.options.map(opt => `<option value="${opt.value}">${opt.label}</option>`).join('') : '';
            fieldInput = `<select class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" disabled>
                <option>Choose an option</option>
                ${selectOptions}
            </select>`;
            break;
            
        case 'radio':
            const radioOptions = field.options ? field.options.map(opt => `
                <label class="flex items-center">
                    <input type="radio" name="${field.key}" value="${opt.value}" class="text-blue-600 focus:ring-blue-500" disabled>
                    <span class="ml-2 text-gray-700">${opt.label}</span>
                </label>
            `).join('') : '';
            fieldInput = `<div class="space-y-2">${radioOptions}</div>`;
            break;
            
        case 'checkbox':
            const checkboxOptions = field.options ? field.options.map(opt => `
                <label class="flex items-center">
                    <input type="checkbox" value="${opt.value}" class="text-blue-600 focus:ring-blue-500" disabled>
                    <span class="ml-2 text-gray-700">${opt.label}</span>
                </label>
            `).join('') : '';
            fieldInput = `<div class="space-y-2">${checkboxOptions}</div>`;
            break;
            
        case 'rating':
            const stars = Array.from({length: field.maxRating || 5}, (_, i) => `
                <i class="fas fa-star text-gray-300 hover:text-yellow-400 cursor-pointer text-xl"></i>
            `).join('');
            fieldInput = `<div class="flex space-x-1">${stars}</div>`;
            break;
            
        case 'scale':
            fieldInput = `<div class="flex items-center space-x-2">
                <span class="text-sm text-gray-600">${field.minScale || 1}</span>
                <input type="range" min="${field.minScale || 1}" max="${field.maxScale || 10}" class="flex-1" disabled>
                <span class="text-sm text-gray-600">${field.maxScale || 10}</span>
            </div>`;
            break;
            
        case 'date':
            fieldInput = `<input type="date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" disabled>`;
            break;
            
        case 'file':
            fieldInput = `<input type="file" class="w-full text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" disabled>`;
            break;
    }
    
    return `
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors duration-200 field-item" data-index="${index}">
            <div class="flex items-start justify-between mb-3">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        ${field.label} ${isRequired}
                    </label>
                    ${fieldInput}
                </div>
                
                <div class="flex space-x-1 ml-4">
                    <button onclick="editField(${index})" class="p-1 text-gray-400 hover:text-blue-600 transition-colors duration-200" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="duplicateField(${index})" class="p-1 text-gray-400 hover:text-green-600 transition-colors duration-200" title="Duplicate">
                        <i class="fas fa-copy"></i>
                    </button>
                    <button onclick="removeField(${index})" class="p-1 text-gray-400 hover:text-red-600 transition-colors duration-200" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            
            ${field.description ? `<p class="text-sm text-gray-600 mb-2">${field.description}</p>` : ''}
        </div>
    `;
}

function editField(index) {
    currentFieldId = index;
    const field = formFields[index];
    showFieldConfigModal(field);
}

function duplicateField(index) {
    const field = {...formFields[index]};
    fieldCounter++;
    field.id = `field_${fieldCounter}`;
    field.key = `field_${fieldCounter}`;
    field.label = field.label + ' (Copy)';
    
    formFields.splice(index + 1, 0, field);
    renderFields();
}

function removeField(index) {
    if (confirm('Are you sure you want to remove this field?')) {
        formFields.splice(index, 1);
        renderFields();
        
        if (formFields.length === 0) {
            document.getElementById('empty-state').style.display = 'block';
        }
    }
}

function showFieldConfigModal(field) {
    const modal = document.getElementById('field-config-modal');
    const title = document.getElementById('modal-title');
    const content = document.getElementById('modal-content');
    
    title.textContent = `Configure ${getDefaultLabel(field.type)}`;
    
    let optionsHtml = '';
    if (field.type === 'select' || field.type === 'radio' || field.type === 'checkbox') {
        const optionsJson = JSON.stringify(field.options || []);
        optionsHtml = `
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Options</label>
                <div id="options-container">
                    ${(field.options || []).map((option, i) => `
                        <div class="flex items-center space-x-2 mb-2">
                            <input type="text" value="${option.label}" placeholder="Option label" 
                                   class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   onchange="updateOptionLabel(${i}, this.value)">
                            <input type="text" value="${option.value}" placeholder="Option value" 
                                   class="w-24 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   onchange="updateOptionValue(${i}, this.value)">
                            <button onclick="removeOption(${i})" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `).join('')}
                </div>
                <button onclick="addOption()" class="mt-2 text-blue-600 hover:text-blue-800 text-sm">
                    <i class="fas fa-plus mr-1"></i> Add Option
                </button>
            </div>
        `;
    }
    
    let ratingHtml = '';
    if (field.type === 'rating') {
        ratingHtml = `
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Rating</label>
                <select id="max-rating" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    ${[3,4,5,6,7,8,9,10].map(n => `<option value="${n}" ${field.maxRating === n ? 'selected' : ''}>${n}</option>`).join('')}
                </select>
            </div>
        `;
    }
    
    let scaleHtml = '';
    if (field.type === 'scale') {
        scaleHtml = `
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Scale Range</label>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Minimum</label>
                        <input type="number" id="min-scale" value="${field.minScale || 1}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Maximum</label>
                        <input type="number" id="max-scale" value="${field.maxScale || 10}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            </div>
        `;
    }
    
    content.innerHTML = `
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Field Label</label>
            <input type="text" id="field-label" value="${field.label}" 
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
            <textarea id="field-description" placeholder="Help text for this field" rows="2"
                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">${field.description || ''}</textarea>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Placeholder (Optional)</label>
            <input type="text" id="field-placeholder" value="${field.placeholder || ''}" 
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
        
        ${optionsHtml}
        ${ratingHtml}
        ${scaleHtml}
        
        <div class="mb-4">
            <label class="flex items-center">
                <input type="checkbox" id="field-required" ${field.required ? 'checked' : ''} 
                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <span class="ml-2 text-sm text-gray-700">Required field</span>
            </label>
        </div>
    `;
    
    modal.classList.remove('hidden');
}

function closeFieldModal() {
    document.getElementById('field-config-modal').classList.add('hidden');
    currentFieldId = null;
}

function saveFieldConfig() {
    if (currentFieldId === null) return;
    
    const field = formFields[currentFieldId];
    
    field.label = document.getElementById('field-label').value;
    field.description = document.getElementById('field-description').value;
    field.placeholder = document.getElementById('field-placeholder').value;
    field.required = document.getElementById('field-required').checked;
    
    if (field.type === 'rating') {
        field.maxRating = parseInt(document.getElementById('max-rating').value);
    }
    
    if (field.type === 'scale') {
        field.minScale = parseInt(document.getElementById('min-scale').value);
        field.maxScale = parseInt(document.getElementById('max-scale').value);
    }
    
    renderFields();
    closeFieldModal();
}

function addOption() {
    const field = formFields[currentFieldId];
    if (!field.options) field.options = [];
    
    const newOption = {
        label: `Option ${field.options.length + 1}`,
        value: `option${field.options.length + 1}`
    };
    
    field.options.push(newOption);
    showFieldConfigModal(field);
}

function removeOption(index) {
    const field = formFields[currentFieldId];
    field.options.splice(index, 1);
    showFieldConfigModal(field);
}

function updateOptionLabel(index, value) {
    const field = formFields[currentFieldId];
    field.options[index].label = value;
}

function updateOptionValue(index, value) {
    const field = formFields[currentFieldId];
    field.options[index].value = value;
}

function previewForm() {
    if (formFields.length === 0) {
        alert('Please add at least one field to preview the form.');
        return;
    }
    
    // Create a temporary preview form
    const previewData = {
        title: document.getElementById('form-title').value || 'Untitled Form',
        description: document.getElementById('form-description').value,
        fields: formFields,
        user: { name: '{{ auth()->user()->name }}' },
        expires_at: document.getElementById('form-expires').value || null,
        is_active: true
    };
    
    // Store in session storage for preview
    sessionStorage.setItem('formPreviewData', JSON.stringify(previewData));
    
    // Open preview window
    window.open('/forms/preview/builder', '_blank', 'width=1200,height=800,scrollbars=yes');
}

function saveForm() {
    const title = document.getElementById('form-title').value.trim();
    const description = document.getElementById('form-description').value.trim();
    const department = document.getElementById('form-department').value;
    const departmentSubdivision = document.getElementById('form-sub-department').value;
    
    if (!title) {
        alert('Please enter a form title.');
        return;
    }

    if (!department) {
        alert('Please select a department.');
        return;
    }

    if (!departmentSubdivision) {
        alert('Please select a sub-department.');
        return;
    }
    
    if (formFields.length === 0) {
        alert('Please add at least one field to the form.');
        return;
    }
    
    const formData = {
        title: title,
        description: description,
        fields: formFields,
        is_public: document.getElementById('form-public').checked,
        expires_at: document.getElementById('form-expires').value || null,
        department: department,
        department_subdivision: departmentSubdivision,
        settings: {}
    };
    
    // Show loading state
    const saveButton = event.target;
    const originalText = saveButton.innerHTML;
    saveButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
    saveButton.disabled = true;
    
    fetch('/forms', {
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
            showNotification('Form saved successfully!', 'success');
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1000);
        } else {
            showNotification('Failed to save form. Please try again.', 'error');
            saveButton.innerHTML = originalText;
            saveButton.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while saving the form.', 'error');
        saveButton.innerHTML = originalText;
        saveButton.disabled = false;
    });
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation-triangle' : 'info'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);

    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 3000);
}
</script>
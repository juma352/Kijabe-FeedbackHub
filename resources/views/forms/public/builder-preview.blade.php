<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Form Preview</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Preview Header -->
    <div class="bg-blue-600 text-white p-4">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <i class="fas fa-eye text-xl"></i>
                <div>
                    <h1 class="text-xl font-bold">Form Preview</h1>
                    <p class="text-blue-200 text-sm">This is how your form will appear to respondents</p>
                </div>
            </div>
            <button onclick="window.close()" class="bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-times mr-2"></i>Close Preview
            </button>
        </div>
    </div>

    <!-- Form Container -->
    <div class="max-w-4xl mx-auto py-8 px-4">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-8">
                <div id="preview-header">
                    <h1 class="text-3xl font-bold mb-4" id="preview-title">Sample Form Title</h1>
                    <p class="text-blue-100 text-lg" id="preview-description">This is a sample form description. Replace this with your own form description.</p>
                </div>
            </div>

            <!-- Form Body -->
            <div class="p-8">
                <form id="preview-form" class="space-y-6">
                    <div id="preview-fields">
                        <!-- Sample fields will be populated here -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Sample Text Field *
                                </label>
                                <input 
                                    type="text" 
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Enter your response here..."
                                    disabled
                                >
                                <p class="text-gray-500 text-sm mt-1">This is a sample text field</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Sample Multiple Choice
                                </label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="radio" name="sample_choice" class="text-blue-600 mr-3" disabled>
                                        <span class="text-gray-700">Option 1</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="sample_choice" class="text-blue-600 mr-3" disabled>
                                        <span class="text-gray-700">Option 2</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="sample_choice" class="text-blue-600 mr-3" disabled>
                                        <span class="text-gray-700">Option 3</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Sample Textarea
                                </label>
                                <textarea 
                                    rows="4" 
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Enter your detailed response here..."
                                    disabled
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Preview Notice -->
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-yellow-500 mr-3"></i>
                            <div>
                                <p class="text-sm font-medium text-yellow-800">Preview Mode</p>
                                <p class="text-sm text-yellow-700">This is a preview of your form. Fields are disabled and no data will be submitted.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Disabled Submit Button -->
                    <div class="flex justify-end">
                        <button 
                            type="button" 
                            class="bg-gray-400 text-white px-8 py-3 rounded-lg font-semibold cursor-not-allowed"
                            disabled
                        >
                            <i class="fas fa-paper-plane mr-2"></i>Submit (Preview Mode)
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Function to update preview with form data from parent window
        function updatePreview(formData) {
            if (formData.title) {
                document.getElementById('preview-title').textContent = formData.title;
            }
            
            if (formData.description) {
                document.getElementById('preview-description').textContent = formData.description;
            }
            
            if (formData.fields && formData.fields.length > 0) {
                const fieldsContainer = document.getElementById('preview-fields');
                fieldsContainer.innerHTML = '';
                
                formData.fields.forEach((field, index) => {
                    const fieldElement = createPreviewField(field, index);
                    fieldsContainer.appendChild(fieldElement);
                });
            }
        }

        // Function to create preview field element
        function createPreviewField(field, index) {
            const fieldDiv = document.createElement('div');
            fieldDiv.className = 'space-y-2';
            
            const label = document.createElement('label');
            label.className = 'block text-sm font-medium text-gray-700 mb-2';
            label.textContent = field.label + (field.required ? ' *' : '');
            fieldDiv.appendChild(label);
            
            let inputElement;
            
            switch (field.type) {
                case 'text':
                case 'email':
                case 'number':
                    inputElement = document.createElement('input');
                    inputElement.type = field.type;
                    inputElement.className = 'w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent';
                    inputElement.placeholder = field.placeholder || 'Enter your response...';
                    inputElement.disabled = true;
                    break;
                    
                case 'textarea':
                    inputElement = document.createElement('textarea');
                    inputElement.rows = 4;
                    inputElement.className = 'w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent';
                    inputElement.placeholder = field.placeholder || 'Enter your response...';
                    inputElement.disabled = true;
                    break;
                    
                case 'select':
                    inputElement = document.createElement('select');
                    inputElement.className = 'w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent';
                    inputElement.disabled = true;
                    
                    const defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    defaultOption.textContent = 'Select an option...';
                    inputElement.appendChild(defaultOption);
                    
                    if (field.options) {
                        field.options.forEach(option => {
                            const optionElement = document.createElement('option');
                            optionElement.value = option;
                            optionElement.textContent = option;
                            inputElement.appendChild(optionElement);
                        });
                    }
                    break;
                    
                case 'radio':
                case 'checkbox':
                    const optionsDiv = document.createElement('div');
                    optionsDiv.className = 'space-y-2';
                    
                    if (field.options) {
                        field.options.forEach((option, optIndex) => {
                            const optionLabel = document.createElement('label');
                            optionLabel.className = 'flex items-center';
                            
                            const optionInput = document.createElement('input');
                            optionInput.type = field.type;
                            optionInput.name = `field_${index}`;
                            optionInput.value = option;
                            optionInput.className = 'text-blue-600 mr-3';
                            optionInput.disabled = true;
                            
                            const optionText = document.createElement('span');
                            optionText.className = 'text-gray-700';
                            optionText.textContent = option;
                            
                            optionLabel.appendChild(optionInput);
                            optionLabel.appendChild(optionText);
                            optionsDiv.appendChild(optionLabel);
                        });
                    }
                    
                    inputElement = optionsDiv;
                    break;
                    
                default:
                    inputElement = document.createElement('input');
                    inputElement.type = 'text';
                    inputElement.className = 'w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent';
                    inputElement.disabled = true;
            }
            
            fieldDiv.appendChild(inputElement);
            
            // Add help text if provided
            if (field.help_text) {
                const helpText = document.createElement('p');
                helpText.className = 'text-gray-500 text-sm mt-1';
                helpText.textContent = field.help_text;
                fieldDiv.appendChild(helpText);
            }
            
            return fieldDiv;
        }

        // Listen for messages from parent window
        window.addEventListener('message', function(event) {
            if (event.data.type === 'updatePreview') {
                updatePreview(event.data.formData);
            }
        });

        // Let parent know we're ready
        if (window.opener) {
            window.opener.postMessage({ type: 'previewReady' }, '*');
        }
    </script>
</body>
</html>
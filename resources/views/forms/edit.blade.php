<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Edit Form') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">{{ $form->title }}</p>
            </div>
            <a href="{{ route('forms.show', $form) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                <i class="fas fa-times mr-2"></i>
                Cancel
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <form method="POST" action="{{ route('forms.update', $form) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Form Title *</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $form->title) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Enter form title">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Optional description for respondents">{{ old('description', $form->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Settings -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Public/Private -->
                        <div>
                            <label for="is_public" class="block text-sm font-medium text-gray-700">Visibility</label>
                            <select id="is_public" name="is_public" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="0" {{ old('is_public', $form->is_public) == 0 ? 'selected' : '' }}>Private</option>
                                <option value="1" {{ old('is_public', $form->is_public) == 1 ? 'selected' : '' }}>Public</option>
                            </select>
                            @error('is_public')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Expiration Date -->
                        <div>
                            <label for="expires_at" class="block text-sm font-medium text-gray-700">Expires At (Optional)</label>
                            <input type="datetime-local" id="expires_at" name="expires_at" value="{{ old('expires_at', $form->expires_at?->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('expires_at')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Fields -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Form Fields</h3>
                        <button type="button" onclick="addField()" class="inline-flex items-center px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                            <i class="fas fa-plus mr-1"></i>
                            Add Field
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <div id="fields-container" class="space-y-4">
                        @forelse($form->fields ?? [] as $index => $field)
                            <div class="field-item border border-gray-200 rounded-lg p-4 bg-gray-50" data-index="{{ $index }}">
                                <div class="flex justify-between items-start mb-3">
                                    <h4 class="font-medium text-gray-900">{{ $field['label'] ?? "Field $index" }}</h4>
                                    <button type="button" onclick="removeField(this)" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Label *</label>
                                        <input type="text" name="fields[{{ $index }}][label]" value="{{ $field['label'] ?? '' }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Field label">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Type *</label>
                                        <select name="fields[{{ $index }}][type]" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" onchange="updateFieldType(this)">
                                            <option value="text" {{ ($field['type'] ?? '') == 'text' ? 'selected' : '' }}>Text</option>
                                            <option value="email" {{ ($field['type'] ?? '') == 'email' ? 'selected' : '' }}>Email</option>
                                            <option value="number" {{ ($field['type'] ?? '') == 'number' ? 'selected' : '' }}>Number</option>
                                            <option value="textarea" {{ ($field['type'] ?? '') == 'textarea' ? 'selected' : '' }}>Textarea</option>
                                            <option value="select" {{ ($field['type'] ?? '') == 'select' ? 'selected' : '' }}>Select</option>
                                            <option value="radio" {{ ($field['type'] ?? '') == 'radio' ? 'selected' : '' }}>Radio</option>
                                            <option value="checkbox" {{ ($field['type'] ?? '') == 'checkbox' ? 'selected' : '' }}>Checkbox</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Placeholder</label>
                                        <input type="text" name="fields[{{ $index }}][placeholder]" value="{{ $field['placeholder'] ?? '' }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Enter placeholder text">
                                    </div>

                                    <div>
                                        <label class="flex items-center mt-6">
                                            <input type="checkbox" name="fields[{{ $index }}][required]" value="1" {{ ($field['required'] ?? false) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                            <span class="ml-2 text-sm font-medium text-gray-700">Required</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                                    <textarea name="fields[{{ $index }}][description]" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Help text for respondents">{{ $field['description'] ?? '' }}</textarea>
                                </div>

                                @if(in_array($field['type'] ?? '', ['select', 'radio', 'checkbox']))
                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700">Options (one per line)</label>
                                        <textarea name="fields[{{ $index }}][options_text]" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Option 1&#10;Option 2&#10;Option 3">{{ isset($field['options']) && is_array($field['options']) ? implode("\n", array_map(function($o) { return $o['label'] ?? $o; }, $field['options'])) : '' }}</textarea>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-600">
                                <i class="fas fa-inbox text-4xl mb-2 opacity-50"></i>
                                <p>No fields yet. Click "Add Field" to get started.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('forms.show', $form) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-times mr-2"></i>
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    <i class="fas fa-save mr-2"></i>
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <script>
        let fieldIndex = {{ (max(array_keys($form->fields ?? [])) ?? -1) + 1 }};

        function addField() {
            const container = document.getElementById('fields-container');
            const fieldHTML = `
                <div class="field-item border border-gray-200 rounded-lg p-4 bg-gray-50" data-index="${fieldIndex}">
                    <div class="flex justify-between items-start mb-3">
                        <h4 class="font-medium text-gray-900">New Field</h4>
                        <button type="button" onclick="removeField(this)" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Label *</label>
                            <input type="text" name="fields[${fieldIndex}][label]" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Field label">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Type *</label>
                            <select name="fields[${fieldIndex}][type]" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" onchange="updateFieldType(this)">
                                <option value="text">Text</option>
                                <option value="email">Email</option>
                                <option value="number">Number</option>
                                <option value="textarea">Textarea</option>
                                <option value="select">Select</option>
                                <option value="radio">Radio</option>
                                <option value="checkbox">Checkbox</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Placeholder</label>
                            <input type="text" name="fields[${fieldIndex}][placeholder]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Enter placeholder text">
                        </div>

                        <div>
                            <label class="flex items-center mt-6">
                                <input type="checkbox" name="fields[${fieldIndex}][required]" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">Required</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                        <textarea name="fields[${fieldIndex}][description]" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Help text for respondents"></textarea>
                    </div>
                </div>
            `;

            const temp = document.createElement('div');
            temp.innerHTML = fieldHTML;
            container.appendChild(temp.firstElementChild);
            fieldIndex++;
        }

        function removeField(button) {
            button.closest('.field-item').remove();
        }

        function updateFieldType(select) {
            const fieldItem = select.closest('.field-item');
            const type = select.value;
            let optionsDiv = fieldItem.querySelector('[data-options]');

            if (['select', 'radio', 'checkbox'].includes(type)) {
                if (!optionsDiv) {
                    const index = fieldItem.dataset.index;
                    const optionsHTML = `
                        <div class="mt-4" data-options="true">
                            <label class="block text-sm font-medium text-gray-700">Options (one per line)</label>
                            <textarea name="fields[${index}][options_text]" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Option 1&#10;Option 2&#10;Option 3"></textarea>
                        </div>
                    `;
                    const temp = document.createElement('div');
                    temp.innerHTML = optionsHTML;
                    fieldItem.appendChild(temp.firstElementChild);
                }
            } else if (optionsDiv) {
                optionsDiv.remove();
            }
        }
    </script>
</x-app-layout>
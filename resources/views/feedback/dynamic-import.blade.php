<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dynamic CSV Analysis & Import') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Step Indicator -->
            <div class="mb-8">
                <div class="flex items-center justify-center space-x-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-medium">
                            1
                        </div>
                        <span class="ml-2 text-sm font-medium text-green-600">File Uploaded</span>
                    </div>
                    <div class="w-12 h-1 bg-green-300 rounded"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-medium">
                            2
                        </div>
                        <span class="ml-2 text-sm font-medium text-blue-600">Column Analysis</span>
                    </div>
                    <div class="w-12 h-1 bg-gray-300 rounded"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium">
                            3
                        </div>
                        <span class="ml-2 text-sm font-medium text-gray-500">Import Data</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('feedback.dynamic.import.process') }}" method="POST" id="mappingForm">
                @csrf
                <input type="hidden" name="file_path" value="{{ $filePath }}">
                
                <!-- CSV Analysis Summary -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">📊 CSV Analysis Results</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                            <div class="text-center p-4 bg-blue-50 rounded-lg">
                                <div class="text-2xl font-bold text-blue-600">{{ $analysis['total_columns'] }}</div>
                                <div class="text-sm text-blue-700">Columns Detected</div>
                            </div>
                            <div class="text-center p-4 bg-green-50 rounded-lg">
                                <div class="text-2xl font-bold text-green-600">{{ $analysis['estimated_rows'] }}</div>
                                <div class="text-sm text-green-700">Data Rows</div>
                            </div>
                            <div class="text-center p-4 bg-purple-50 rounded-lg">
                                <div class="text-2xl font-bold text-purple-600">
                                    {{ count(array_filter($analysis['suggested_mappings'], fn($m) => $m !== null && !is_array($m))) }}
                                </div>
                                <div class="text-sm text-purple-700">Auto-Detected Fields</div>
                            </div>
                            <div class="text-center p-4 bg-orange-50 rounded-lg">
                                <div class="text-2xl font-bold text-orange-600">
                                    {{ count($analysis['suggested_mappings']['additional_fields']) }}
                                </div>
                                <div class="text-sm text-orange-700">Additional Fields</div>
                            </div>
                        </div>

                        <!-- Data Preview -->
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-900 mb-3">📋 Data Preview</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white border border-gray-300 text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            @foreach($analysis['headers'] as $header)
                                                <th class="px-3 py-2 text-left font-medium text-gray-700 border-b">
                                                    {{ $header }}
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($analysis['sample_rows'] as $row)
                                            <tr class="border-b">
                                                @foreach($row as $cell)
                                                    <td class="px-3 py-2 text-gray-600 max-w-xs truncate">
                                                        {{ Str::limit($cell, 50) }}
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Column Mapping Configuration -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">🎯 Column Mapping Configuration</h3>
                        <p class="text-gray-600 mb-6">Review and adjust the detected column mappings. The system has automatically identified the most likely columns for each field type.</p>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Primary Fields -->
                            <div class="space-y-6">
                                <h4 class="font-medium text-gray-900 border-b pb-2">Primary Fields</h4>
                                
                                <!-- Feedback Content -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        📝 Feedback Content (Required)
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <select name="mappings[feedback_content]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                        <option value="">Select column...</option>
                                        @foreach($analysis['headers'] as $index => $header)
                                            <option value="{{ $index }}" 
                                                @if(isset($analysis['suggested_mappings']['feedback_content']['column_index']) && $analysis['suggested_mappings']['feedback_content']['column_index'] == $index) selected @endif>
                                                {{ $header }}
                                                @if(isset($analysis['suggested_mappings']['feedback_content']['column_index']) && $analysis['suggested_mappings']['feedback_content']['column_index'] == $index)
                                                    ({{ $analysis['suggested_mappings']['feedback_content']['confidence'] }}% confidence)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @if(isset($analysis['suggested_mappings']['feedback_content']))
                                        <p class="text-xs text-green-600 mt-1">✅ Auto-detected with {{ $analysis['suggested_mappings']['feedback_content']['confidence'] }}% confidence</p>
                                    @endif
                                </div>

                                <!-- Rating -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">⭐ Rating/Score</label>
                                    <select name="mappings[rating]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select column (optional)...</option>
                                        @foreach($analysis['headers'] as $index => $header)
                                            <option value="{{ $index }}" 
                                                @if(isset($analysis['suggested_mappings']['rating']['column_index']) && $analysis['suggested_mappings']['rating']['column_index'] == $index) selected @endif>
                                                {{ $header }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if(isset($analysis['suggested_mappings']['rating']))
                                        <p class="text-xs text-green-600 mt-1">✅ Auto-detected: {{ $analysis['suggested_mappings']['rating']['column_name'] }}</p>
                                    @endif
                                </div>

                                <!-- Date -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">📅 Date/Timestamp</label>
                                    <select name="mappings[date]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select column (optional)...</option>
                                        @foreach($analysis['headers'] as $index => $header)
                                            <option value="{{ $index }}" 
                                                @if(isset($analysis['suggested_mappings']['date']['column_index']) && $analysis['suggested_mappings']['date']['column_index'] == $index) selected @endif>
                                                {{ $header }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if(isset($analysis['suggested_mappings']['date']))
                                        <p class="text-xs text-green-600 mt-1">✅ Auto-detected: {{ $analysis['suggested_mappings']['date']['column_name'] }}</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Secondary Fields -->
                            <div class="space-y-6">
                                <h4 class="font-medium text-gray-900 border-b pb-2">Secondary Fields</h4>
                                
                                <!-- Department -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">🏥 Department/Category</label>
                                    <select name="mappings[department]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select column (optional)...</option>
                                        @foreach($analysis['headers'] as $index => $header)
                                            <option value="{{ $index }}" 
                                                @if(isset($analysis['suggested_mappings']['department']['column_index']) && $analysis['suggested_mappings']['department']['column_index'] == $index) selected @endif>
                                                {{ $header }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if(isset($analysis['suggested_mappings']['department']))
                                        <p class="text-xs text-green-600 mt-1">✅ Auto-detected: {{ $analysis['suggested_mappings']['department']['column_name'] }}</p>
                                    @endif
                                </div>

                                <!-- Identifier -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">👤 Identifier/Name</label>
                                    <select name="mappings[identifier]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select column (optional)...</option>
                                        @foreach($analysis['headers'] as $index => $header)
                                            <option value="{{ $index }}" 
                                                @if(isset($analysis['suggested_mappings']['identifier']['column_index']) && $analysis['suggested_mappings']['identifier']['column_index'] == $index) selected @endif>
                                                {{ $header }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Email -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">📧 Email</label>
                                    <select name="mappings[email]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select column (optional)...</option>
                                        @foreach($analysis['headers'] as $index => $header)
                                            <option value="{{ $index }}" 
                                                @if(isset($analysis['suggested_mappings']['email']['column_index']) && $analysis['suggested_mappings']['email']['column_index'] == $index) selected @endif>
                                                {{ $header }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Fields -->
                        @if(!empty($analysis['suggested_mappings']['additional_fields']))
                            <div class="mt-8">
                                <h4 class="font-medium text-gray-900 border-b pb-2 mb-4">📋 Additional Fields</h4>
                                <p class="text-gray-600 mb-4">These fields will be stored as additional data with each feedback entry.</p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($analysis['suggested_mappings']['additional_fields'] as $field)
                                        <div class="border rounded-lg p-4 bg-gray-50">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="font-medium text-gray-900">{{ $field['column_name'] }}</span>
                                                <span class="text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded">
                                                    {{ $field['detected_type'] }}
                                                </span>
                                            </div>
                                            <div class="text-sm text-gray-600">
                                                @if(isset($analysis['column_analysis'][$field['column_index']]['sample_values']))
                                                    <strong>Sample:</strong> 
                                                    {{ implode(', ', array_slice($analysis['column_analysis'][$field['column_index']]['sample_values'], 0, 2)) }}
                                                @endif
                                            </div>
                                            <input type="hidden" name="additional_fields[]" value="{{ $field['column_index'] }}">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Import Options -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">⚙️ Import Options</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="auto_analyze" value="1" checked class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                    <span class="ml-2 text-sm text-gray-700">Automatically analyze sentiment and calculate scores</span>
                                </label>
                            </div>
                            
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="calculate_learner_experience" value="1" checked class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                    <span class="ml-2 text-sm text-gray-700">Calculate learner experience scores</span>
                                </label>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Import Source Name</label>
                                <input type="text" name="source_name" value="Dynamic CSV Import" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="e.g., Hospital Survey 2024">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between">
                    <a href="{{ route('feedback.import') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        ← Back to Upload
                    </a>
                    
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        🚀 Import {{ $analysis['estimated_rows'] }} Records
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Add some interactivity for better UX
        document.addEventListener('DOMContentLoaded', function() {
            // Highlight required fields
            const requiredSelects = document.querySelectorAll('select[required]');
            requiredSelects.forEach(select => {
                select.addEventListener('change', function() {
                    if (this.value) {
                        this.classList.remove('border-red-300');
                        this.classList.add('border-green-300');
                    } else {
                        this.classList.add('border-red-300');
                        this.classList.remove('border-green-300');
                    }
                });
            });

            // Form validation
            document.getElementById('mappingForm').addEventListener('submit', function(e) {
                const feedbackContent = document.querySelector('select[name="mappings[feedback_content]"]').value;
                if (!feedbackContent) {
                    e.preventDefault();
                    alert('Please select a column for Feedback Content - this field is required!');
                    return false;
                }
            });
        });
    </script>
</x-app-layout>
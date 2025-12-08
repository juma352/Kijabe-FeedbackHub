<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Preview: {{ $form->title }} - Feedback Hub</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <!-- Preview Banner -->
        <div class="bg-yellow-500 text-white px-4 py-3 shadow-sm">
            <div class="max-w-4xl mx-auto flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-eye mr-3 text-xl"></i>
                    <div>
                        <h2 class="font-semibold">Preview Mode</h2>
                        <p class="text-sm text-yellow-100">This is how your form will appear to respondents. Form submission is disabled in preview mode.</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('forms.show', $form) }}" class="bg-yellow-600 hover:bg-yellow-700 px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Form
                    </a>
                    <a href="{{ $form->share_url }}" target="_blank" class="bg-white text-yellow-600 hover:bg-yellow-50 px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Open Live Form
                    </a>
                </div>
            </div>
        </div>

        <div class="min-h-screen flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-comments text-2xl text-blue-500 mr-3"></i>
                            <h1 class="text-xl font-semibold text-gray-900">Feedback Hub</h1>
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-eye mr-1"></i>
                            Preview Mode
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 py-8">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Form Container -->
                    <div class="bg-white shadow-sm rounded-lg border border-gray-200 relative">
                        <!-- Preview Overlay -->
                        <div class="absolute inset-0 bg-blue-50 bg-opacity-75 z-10 rounded-lg flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-200 pointer-events-none">
                            <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                                <i class="fas fa-eye text-blue-500 text-3xl mb-3"></i>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Preview Mode</h3>
                                <p class="text-gray-600">Form interactions are disabled</p>
                            </div>
                        </div>

                        <!-- Form Header -->
                        <div class="px-6 py-8 border-b border-gray-200">
                            <div class="text-center">
                                <h1 class="text-3xl font-bold text-gray-900 mb-3">{{ $form->title }}</h1>
                                @if($form->description)
                                    <p class="text-lg text-gray-600 leading-relaxed">{{ $form->description }}</p>
                                @endif
                            </div>

                            <!-- Form Info -->
                            <div class="mt-6 flex items-center justify-center space-x-6 text-sm text-gray-500">
                                <div class="flex items-center">
                                    <i class="fas fa-user mr-2"></i>
                                    <span>By {{ $form->user->name }}</span>
                                </div>
                                @if($form->expires_at)
                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-2"></i>
                                        <span>Expires {{ $form->expires_at->format('M j, Y') }}</span>
                                    </div>
                                @endif
                                <div class="flex items-center">
                                    <i class="fas fa-lock mr-2"></i>
                                    <span>Responses are private</span>
                                </div>
                            </div>
                        </div>

                        <!-- Form Fields -->
                        <form id="preview-form" class="pointer-events-none">
                            @csrf
                            <div class="px-6 py-8 space-y-8">
                                @foreach($form->fields as $index => $field)
                                    <div class="form-field">
                                        <!-- Field Label -->
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ $field['label'] }}
                                            @if($field['required'] ?? false)
                                                <span class="text-red-500">*</span>
                                            @endif
                                        </label>

                                        <!-- Field Description -->
                                        @if(!empty($field['description']))
                                            <p class="text-sm text-gray-600 mb-3">{{ $field['description'] }}</p>
                                        @endif

                                        <!-- Field Input -->
                                        <div class="field-input">
                                            @switch($field['type'])
                                                @case('text')
                                                    <input type="text" 
                                                           placeholder="{{ $field['placeholder'] ?? 'Enter text here...' }}"
                                                           value="Sample text input"
                                                           class="w-full rounded-md border-gray-300 shadow-sm bg-gray-50 text-gray-600"
                                                           disabled>
                                                    @break

                                                @case('email')
                                                    <input type="email" 
                                                           placeholder="{{ $field['placeholder'] ?? 'Enter your email address' }}"
                                                           value="sample@example.com"
                                                           class="w-full rounded-md border-gray-300 shadow-sm bg-gray-50 text-gray-600"
                                                           disabled>
                                                    @break

                                                @case('number')
                                                    <input type="number" 
                                                           placeholder="{{ $field['placeholder'] ?? 'Enter a number' }}"
                                                           value="42"
                                                           min="{{ $field['min'] ?? '' }}"
                                                           max="{{ $field['max'] ?? '' }}"
                                                           class="w-full rounded-md border-gray-300 shadow-sm bg-gray-50 text-gray-600"
                                                           disabled>
                                                    @break

                                                @case('textarea')
                                                    <textarea rows="4"
                                                              placeholder="{{ $field['placeholder'] ?? 'Enter your response here...' }}"
                                                              class="w-full rounded-md border-gray-300 shadow-sm bg-gray-50 text-gray-600"
                                                              disabled>Sample longer text response that would appear in the textarea field.</textarea>
                                                    @break

                                                @case('select')
                                                    <select class="w-full rounded-md border-gray-300 shadow-sm bg-gray-50 text-gray-600" disabled>
                                                        <option>Choose an option</option>
                                                        @foreach($field['options'] ?? [] as $option)
                                                            <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                                                        @endforeach
                                                    </select>
                                                    @break

                                                @case('radio')
                                                    <div class="space-y-3">
                                                        @foreach($field['options'] ?? [] as $index => $option)
                                                            <label class="flex items-center">
                                                                <input type="radio" 
                                                                       name="{{ $field['key'] }}" 
                                                                       value="{{ $option['value'] }}"
                                                                       {{ $index === 0 ? 'checked' : '' }}
                                                                       class="text-blue-600 focus:ring-blue-500"
                                                                       disabled>
                                                                <span class="ml-3 text-gray-700">{{ $option['label'] }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                    @break

                                                @case('checkbox')
                                                    <div class="space-y-3">
                                                        @foreach($field['options'] ?? [] as $index => $option)
                                                            <label class="flex items-center">
                                                                <input type="checkbox" 
                                                                       name="{{ $field['key'] }}[]" 
                                                                       value="{{ $option['value'] }}"
                                                                       {{ $index < 2 ? 'checked' : '' }}
                                                                       class="text-blue-600 focus:ring-blue-500"
                                                                       disabled>
                                                                <span class="ml-3 text-gray-700">{{ $option['label'] }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                    @break

                                                @case('rating')
                                                    <div class="flex items-center space-x-2">
                                                        @for($i = 1; $i <= ($field['maxRating'] ?? 5); $i++)
                                                            <button type="button" 
                                                                    class="text-2xl {{ $i <= 4 ? 'text-yellow-400' : 'text-gray-300' }} cursor-default">
                                                                <i class="fas fa-star"></i>
                                                            </button>
                                                        @endfor
                                                        <span class="ml-3 text-sm text-gray-600">4 stars (sample rating)</span>
                                                    </div>
                                                    @break

                                                @case('scale')
                                                    <div class="space-y-3">
                                                        <div class="flex items-center space-x-4">
                                                            <span class="text-sm font-medium text-gray-700">{{ $field['minScale'] ?? 1 }}</span>
                                                            <input type="range" 
                                                                   min="{{ $field['minScale'] ?? 1 }}" 
                                                                   max="{{ $field['maxScale'] ?? 10 }}"
                                                                   value="7"
                                                                   class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer slider"
                                                                   disabled>
                                                            <span class="text-sm font-medium text-gray-700">{{ $field['maxScale'] ?? 10 }}</span>
                                                        </div>
                                                        <div class="text-center">
                                                            <span class="text-lg font-semibold text-blue-600">7 (sample value)</span>
                                                        </div>
                                                    </div>
                                                    @break

                                                @case('date')
                                                    <input type="date" 
                                                           value="{{ date('Y-m-d') }}"
                                                           class="w-full rounded-md border-gray-300 shadow-sm bg-gray-50 text-gray-600"
                                                           disabled>
                                                    @break

                                                @case('file')
                                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center bg-gray-50">
                                                        <i class="fas fa-file-upload text-gray-400 text-3xl mb-3"></i>
                                                        <p class="text-gray-600">File upload area (disabled in preview)</p>
                                                        @if(!empty($field['allowed_types']))
                                                            <p class="mt-1 text-sm text-gray-500">
                                                                Allowed types: {{ implode(', ', $field['allowed_types']) }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                    @break

                                                @default
                                                    <p class="text-red-500">Unsupported field type: {{ $field['type'] }}</p>
                                            @endswitch
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Optional Contact Information -->
                                <div class="border-t border-gray-200 pt-8">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Information (Optional)</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Your Name</label>
                                            <input type="text" 
                                                   placeholder="Your name"
                                                   value="Sample User Name"
                                                   class="w-full rounded-md border-gray-300 shadow-sm bg-gray-50 text-gray-600"
                                                   disabled>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Your Email</label>
                                            <input type="email" 
                                                   placeholder="your.email@example.com"
                                                   value="sample@example.com"
                                                   class="w-full rounded-md border-gray-300 shadow-sm bg-gray-50 text-gray-600"
                                                   disabled>
                                        </div>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-500">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        This information is optional and will only be used to contact you if needed.
                                    </p>
                                </div>
                            </div>

                            <!-- Submit Button (Disabled) -->
                            <div class="px-6 py-6 bg-gray-50 border-t border-gray-200 rounded-b-lg">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm text-gray-500">
                                        <i class="fas fa-eye mr-1"></i>
                                        Preview mode - form submission is disabled
                                    </p>
                                    <button type="button" 
                                            class="inline-flex items-center px-6 py-3 bg-gray-400 border border-transparent rounded-md font-semibold text-white cursor-not-allowed opacity-75">
                                        <i class="fas fa-ban mr-2"></i>
                                        Submit Response (Disabled)
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Preview Actions -->
                    <div class="mt-6 flex items-center justify-center space-x-4">
                        <a href="{{ route('forms.show', $form) }}" class="inline-flex items-center px-6 py-3 bg-gray-600 border border-transparent rounded-md font-semibold text-white hover:bg-gray-700 transition duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Form Details
                        </a>
                        
                        @if($form->is_active)
                            <a href="{{ $form->share_url }}" target="_blank" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 transition duration-200">
                                <i class="fas fa-external-link-alt mr-2"></i>
                                Open Live Form
                            </a>
                        @endif

                        @if(auth()->user()->isAdmin() || $form->user_id === auth()->id())
                            <a href="{{ route('forms.edit', $form) }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 transition duration-200">
                                <i class="fas fa-edit mr-2"></i>
                                Edit Form
                            </a>
                        @endif
                    </div>

                    <!-- Footer -->
                    <div class="mt-8 text-center">
                        <p class="text-sm text-gray-500">
                            Preview of form created with 
                            <span class="font-semibold text-blue-600">Feedback Hub</span>
                        </p>
                    </div>
                </div>
            </main>
        </div>

        <script>
            // Disable all form interactions in preview mode
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('preview-form');
                const inputs = form.querySelectorAll('input, select, textarea, button');
                
                inputs.forEach(input => {
                    input.disabled = true;
                    input.style.pointerEvents = 'none';
                });

                // Add preview indicators
                const previewIndicators = document.querySelectorAll('[data-preview]');
                previewIndicators.forEach(indicator => {
                    indicator.classList.add('opacity-75', 'cursor-not-allowed');
                });
            });

            // Show notification for disabled interactions
            function showPreviewNotification() {
                showNotification('This is preview mode - form interactions are disabled', 'info');
            }

            function showNotification(message, type = 'info') {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
                    type === 'success' ? 'bg-green-500 text-white' :
                    type === 'error' ? 'bg-red-500 text-white' :
                    type === 'warning' ? 'bg-yellow-500 text-white' :
                    'bg-blue-500 text-white'
                }`;
                notification.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation-triangle' : type === 'warning' ? 'exclamation-triangle' : 'info'} mr-2"></i>
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
    </body>
</html>
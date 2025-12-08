<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $form->title }} - Feedback Hub</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-comments text-2xl text-blue-500 mr-3"></i>
                            <h1 class="text-xl font-semibold text-gray-900">Feedback Hub</h1>
                        </div>
                        <div class="text-sm text-gray-500">
                            <i class="fas fa-shield-alt mr-1"></i>
                            Secure Form
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 py-8">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-400 mr-3"></i>
                                <div class="text-green-800">
                                    <h3 class="font-medium">Success!</h3>
                                    <p class="mt-1">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Form Container -->
                    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
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
                        <form id="public-form" method="POST" action="{{ route('forms.public.submit', $form->share_token) }}" enctype="multipart/form-data">
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
                                                           name="{{ $field['key'] }}" 
                                                           id="{{ $field['key'] }}"
                                                           placeholder="{{ $field['placeholder'] ?? '' }}"
                                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                           {{ ($field['required'] ?? false) ? 'required' : '' }}>
                                                    @break

                                                @case('email')
                                                    <input type="email" 
                                                           name="{{ $field['key'] }}" 
                                                           id="{{ $field['key'] }}"
                                                           placeholder="{{ $field['placeholder'] ?? 'Enter your email address' }}"
                                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                           {{ ($field['required'] ?? false) ? 'required' : '' }}>
                                                    @break

                                                @case('number')
                                                    <input type="number" 
                                                           name="{{ $field['key'] }}" 
                                                           id="{{ $field['key'] }}"
                                                           placeholder="{{ $field['placeholder'] ?? '' }}"
                                                           min="{{ $field['min'] ?? '' }}"
                                                           max="{{ $field['max'] ?? '' }}"
                                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                           {{ ($field['required'] ?? false) ? 'required' : '' }}>
                                                    @break

                                                @case('textarea')
                                                    <textarea name="{{ $field['key'] }}" 
                                                              id="{{ $field['key'] }}"
                                                              rows="4"
                                                              placeholder="{{ $field['placeholder'] ?? '' }}"
                                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                              {{ ($field['required'] ?? false) ? 'required' : '' }}></textarea>
                                                    @break

                                                @case('select')
                                                    <select name="{{ $field['key'] }}" 
                                                            id="{{ $field['key'] }}"
                                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                            {{ ($field['required'] ?? false) ? 'required' : '' }}>
                                                        <option value="">Choose an option</option>
                                                        @foreach($field['options'] ?? [] as $option)
                                                            <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                                                        @endforeach
                                                    </select>
                                                    @break

                                                @case('radio')
                                                    <div class="space-y-3">
                                                        @foreach($field['options'] ?? [] as $option)
                                                            <label class="flex items-center">
                                                                <input type="radio" 
                                                                       name="{{ $field['key'] }}" 
                                                                       value="{{ $option['value'] }}"
                                                                       class="text-blue-600 focus:ring-blue-500"
                                                                       {{ ($field['required'] ?? false) ? 'required' : '' }}>
                                                                <span class="ml-3 text-gray-700">{{ $option['label'] }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                    @break

                                                @case('checkbox')
                                                    <div class="space-y-3">
                                                        @foreach($field['options'] ?? [] as $option)
                                                            <label class="flex items-center">
                                                                <input type="checkbox" 
                                                                       name="{{ $field['key'] }}[]" 
                                                                       value="{{ $option['value'] }}"
                                                                       class="text-blue-600 focus:ring-blue-500">
                                                                <span class="ml-3 text-gray-700">{{ $option['label'] }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                    @break

                                                @case('rating')
                                                    <div class="flex items-center space-x-2" data-rating-field="{{ $field['key'] }}">
                                                        <input type="hidden" name="{{ $field['key'] }}" id="{{ $field['key'] }}_input" {{ ($field['required'] ?? false) ? 'required' : '' }}>
                                                        @for($i = 1; $i <= ($field['maxRating'] ?? 5); $i++)
                                                            <button type="button" 
                                                                    class="rating-star text-2xl text-gray-300 hover:text-yellow-400 focus:outline-none transition-colors duration-200" 
                                                                    data-rating="{{ $i }}"
                                                                    data-field="{{ $field['key'] }}">
                                                                <i class="fas fa-star"></i>
                                                            </button>
                                                        @endfor
                                                        <span class="ml-3 text-sm text-gray-600" id="{{ $field['key'] }}_display">Click to rate</span>
                                                    </div>
                                                    @break

                                                @case('scale')
                                                    <div class="space-y-3">
                                                        <div class="flex items-center space-x-4">
                                                            <span class="text-sm font-medium text-gray-700">{{ $field['minScale'] ?? 1 }}</span>
                                                            <input type="range" 
                                                                   name="{{ $field['key'] }}" 
                                                                   id="{{ $field['key'] }}"
                                                                   min="{{ $field['minScale'] ?? 1 }}" 
                                                                   max="{{ $field['maxScale'] ?? 10 }}"
                                                                   value="{{ floor(($field['minScale'] ?? 1 + $field['maxScale'] ?? 10) / 2) }}"
                                                                   class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer slider"
                                                                   oninput="updateScaleValue('{{ $field['key'] }}', this.value)"
                                                                   {{ ($field['required'] ?? false) ? 'required' : '' }}>
                                                            <span class="text-sm font-medium text-gray-700">{{ $field['maxScale'] ?? 10 }}</span>
                                                        </div>
                                                        <div class="text-center">
                                                            <span class="text-lg font-semibold text-blue-600" id="{{ $field['key'] }}_value">
                                                                {{ floor(($field['minScale'] ?? 1 + $field['maxScale'] ?? 10) / 2) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    @break

                                                @case('date')
                                                    <input type="date" 
                                                           name="{{ $field['key'] }}" 
                                                           id="{{ $field['key'] }}"
                                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                           {{ ($field['required'] ?? false) ? 'required' : '' }}>
                                                    @break

                                                @case('file')
                                                    <input type="file" 
                                                           name="{{ $field['key'] }}" 
                                                           id="{{ $field['key'] }}"
                                                           class="w-full text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                           {{ ($field['required'] ?? false) ? 'required' : '' }}>
                                                    @if(!empty($field['allowed_types']))
                                                        <p class="mt-1 text-sm text-gray-500">
                                                            Allowed types: {{ implode(', ', $field['allowed_types']) }}
                                                        </p>
                                                    @endif
                                                    @break

                                                @default
                                                    <p class="text-red-500">Unsupported field type: {{ $field['type'] }}</p>
                                            @endswitch
                                        </div>

                                        <!-- Validation Error -->
                                        @error($field['key'])
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endforeach

                                <!-- Optional Contact Information -->
                                <div class="border-t border-gray-200 pt-8">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Information (Optional)</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Your Name</label>
                                            <input type="text" 
                                                   name="_respondent_name" 
                                                   id="_respondent_name"
                                                   placeholder="Your name"
                                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Your Email</label>
                                            <input type="email" 
                                                   name="_respondent_email" 
                                                   id="_respondent_email"
                                                   placeholder="your.email@example.com"
                                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-500">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        This information is optional and will only be used to contact you if needed.
                                    </p>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="px-6 py-6 bg-gray-50 border-t border-gray-200 rounded-b-lg">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm text-gray-500">
                                        <i class="fas fa-shield-alt mr-1"></i>
                                        Your responses are secure and private
                                    </p>
                                    <button type="submit" 
                                            id="submit-button"
                                            class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition duration-200">
                                        <i class="fas fa-paper-plane mr-2"></i>
                                        Submit Response
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Footer -->
                    <div class="mt-8 text-center">
                        <p class="text-sm text-gray-500">
                            Powered by 
                            <span class="font-semibold text-blue-600">Feedback Hub</span>
                            - Create your own forms for free
                        </p>
                    </div>
                </div>
            </main>
        </div>

        <!-- Loading Overlay -->
        <div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white rounded-lg p-6 flex items-center">
                    <i class="fas fa-spinner fa-spin text-blue-600 text-2xl mr-4"></i>
                    <span class="text-lg font-medium">Submitting your response...</span>
                </div>
            </div>
        </div>

        <script>
            // Rating functionality
            document.addEventListener('DOMContentLoaded', function() {
                // Handle rating stars
                const ratingStars = document.querySelectorAll('.rating-star');
                
                ratingStars.forEach(star => {
                    star.addEventListener('click', function() {
                        const rating = parseInt(this.getAttribute('data-rating'));
                        const fieldKey = this.getAttribute('data-field');
                        const ratingField = document.querySelector(`[data-rating-field="${fieldKey}"]`);
                        const hiddenInput = document.getElementById(`${fieldKey}_input`);
                        const display = document.getElementById(`${fieldKey}_display`);
                        
                        // Update hidden input
                        hiddenInput.value = rating;
                        
                        // Update display
                        display.textContent = `${rating} star${rating !== 1 ? 's' : ''}`;
                        
                        // Update star colors
                        const stars = ratingField.querySelectorAll('.rating-star');
                        stars.forEach((s, index) => {
                            if (index < rating) {
                                s.classList.remove('text-gray-300');
                                s.classList.add('text-yellow-400');
                            } else {
                                s.classList.remove('text-yellow-400');
                                s.classList.add('text-gray-300');
                            }
                        });
                    });
                    
                    // Hover effect
                    star.addEventListener('mouseenter', function() {
                        const rating = parseInt(this.getAttribute('data-rating'));
                        const fieldKey = this.getAttribute('data-field');
                        const ratingField = document.querySelector(`[data-rating-field="${fieldKey}"]`);
                        const stars = ratingField.querySelectorAll('.rating-star');
                        
                        stars.forEach((s, index) => {
                            if (index < rating) {
                                s.classList.add('text-yellow-400');
                                s.classList.remove('text-gray-300');
                            }
                        });
                    });
                    
                    star.addEventListener('mouseleave', function() {
                        const fieldKey = this.getAttribute('data-field');
                        const ratingField = document.querySelector(`[data-rating-field="${fieldKey}"]`);
                        const hiddenInput = document.getElementById(`${fieldKey}_input`);
                        const currentRating = parseInt(hiddenInput.value) || 0;
                        const stars = ratingField.querySelectorAll('.rating-star');
                        
                        stars.forEach((s, index) => {
                            if (index < currentRating) {
                                s.classList.add('text-yellow-400');
                                s.classList.remove('text-gray-300');
                            } else {
                                s.classList.remove('text-yellow-400');
                                s.classList.add('text-gray-300');
                            }
                        });
                    });
                });

                // Handle form submission
                const form = document.getElementById('public-form');
                const submitButton = document.getElementById('submit-button');
                const loadingOverlay = document.getElementById('loading-overlay');

                form.addEventListener('submit', function(e) {
                    // Show loading state
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Submitting...';
                    loadingOverlay.classList.remove('hidden');
                });
            });

            // Scale value update
            function updateScaleValue(fieldKey, value) {
                document.getElementById(`${fieldKey}_value`).textContent = value;
            }

            // Notification system
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
                }, 5000);
            }

            // Auto-save draft functionality (optional)
            let autoSaveTimeout;
            function autoSave() {
                clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(() => {
                    const formData = new FormData(document.getElementById('public-form'));
                    const data = {};
                    for (let [key, value] of formData.entries()) {
                        if (key.startsWith('_')) continue; // Skip system fields
                        data[key] = value;
                    }
                    
                    // Save to localStorage as draft
                    localStorage.setItem(`form_draft_{{ $form->share_token }}`, JSON.stringify(data));
                }, 2000);
            }

            // Load draft on page load
            document.addEventListener('DOMContentLoaded', function() {
                const draftData = localStorage.getItem(`form_draft_{{ $form->share_token }}`);
                if (draftData) {
                    try {
                        const data = JSON.parse(draftData);
                        Object.keys(data).forEach(key => {
                            const input = document.querySelector(`[name="${key}"]`);
                            if (input) {
                                input.value = data[key];
                                // Trigger change event for any special handling
                                input.dispatchEvent(new Event('change'));
                            }
                        });
                        showNotification('Draft restored', 'info');
                    } catch (e) {
                        console.log('Failed to restore draft');
                    }
                }

                // Auto-save on input changes
                const inputs = document.querySelectorAll('input, select, textarea');
                inputs.forEach(input => {
                    input.addEventListener('input', autoSave);
                    input.addEventListener('change', autoSave);
                });
            });
        </script>
    </body>
</html>
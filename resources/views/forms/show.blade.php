<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $form->title }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Created {{ $form->created_at->diffForHumans() }}</p>
            </div>
            <div class="flex space-x-3">
                @if(auth()->user()->isAdmin() || $form->user_id === auth()->id())
                    <a href="{{ route('forms.analytics', $form) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Analytics
                    </a>
                    <a href="{{ route('forms.edit', $form) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Form
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Form Info -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Form Details</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Title</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $form->title }}</p>
                        </div>
                        
                        @if($form->description)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $form->description }}</p>
                            </div>
                        @endif

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Department</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    @php
                                        $deptLabels = [
                                            'kchs' => 'KCHS',
                                            'research' => 'Research',
                                            'gme' => 'GME',
                                            'cpd' => 'CPD'
                                        ];
                                    @endphp
                                    {{ $deptLabels[$form->department] ?? ucfirst($form->department) }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Sub-Department</label>
                                <p class="mt-1 text-sm text-gray-900">{{ str_replace('_', ' ', ucfirst($form->department_subdivision)) }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <div class="mt-1">
                                    @if($form->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-circle text-xs mr-1"></i>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-pause text-xs mr-1"></i>
                                            Inactive
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Visibility</label>
                                <div class="mt-1">
                                    @if($form->is_public)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-globe text-xs mr-1"></i>
                                            Public
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-lock text-xs mr-1"></i>
                                            Private
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($form->expires_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Expires At</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $form->expires_at->format('M j, Y g:i A') }}
                                    @if($form->isExpired())
                                        <span class="text-red-600">(Expired)</span>
                                    @else
                                        <span class="text-green-600">({{ $form->expires_at->diffForHumans() }})</span>
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Form Preview -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Form Preview</h3>
                </div>
                <div class="p-6 bg-gray-50">
                    <div class="space-y-6">
                        @foreach($form->fields as $field)
                            <div class="bg-white p-4 rounded-lg border border-gray-200">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $field['label'] }}
                                    @if($field['required'] ?? false)
                                        <span class="text-red-500">*</span>
                                    @endif
                                    <span class="text-xs text-gray-500 ml-2">({{ ucfirst($field['type']) }})</span>
                                </label>
                                
                                @if(!empty($field['description']))
                                    <p class="text-sm text-gray-600 mb-3">{{ $field['description'] }}</p>
                                @endif

                                <div class="text-gray-400">
                                    @switch($field['type'])
                                        @case('text')
                                        @case('email')
                                        @case('number')
                                            <input type="{{ $field['type'] }}" placeholder="{{ $field['placeholder'] ?? 'Field preview' }}" class="w-full rounded-md border-gray-300 bg-gray-50" disabled>
                                            @break

                                        @case('textarea')
                                            <textarea placeholder="{{ $field['placeholder'] ?? 'Field preview' }}" rows="3" class="w-full rounded-md border-gray-300 bg-gray-50" disabled></textarea>
                                            @break

                                        @case('select')
                                            <select class="w-full rounded-md border-gray-300 bg-gray-50" disabled>
                                                <option>Choose an option</option>
                                                @foreach($field['options'] ?? [] as $option)
                                                    <option>{{ $option['label'] }}</option>
                                                @endforeach
                                            </select>
                                            @break

                                        @case('radio')
                                            <div class="space-y-2">
                                                @foreach($field['options'] ?? [] as $option)
                                                    <label class="flex items-center">
                                                        <input type="radio" class="text-blue-600" disabled>
                                                        <span class="ml-2 text-gray-600">{{ $option['label'] }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                            @break

                                        @case('checkbox')
                                            <div class="space-y-2">
                                                @foreach($field['options'] ?? [] as $option)
                                                    <label class="flex items-center">
                                                        <input type="checkbox" class="text-blue-600" disabled>
                                                        <span class="ml-2 text-gray-600">{{ $option['label'] }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                            @break

                                        @case('rating')
                                            <div class="flex space-x-1">
                                                @for($i = 1; $i <= ($field['maxRating'] ?? 5); $i++)
                                                    <i class="fas fa-star text-gray-300 text-xl"></i>
                                                @endfor
                                            </div>
                                            @break

                                        @case('scale')
                                            <div class="flex items-center space-x-4">
                                                <span class="text-sm">{{ $field['minScale'] ?? 1 }}</span>
                                                <input type="range" min="{{ $field['minScale'] ?? 1 }}" max="{{ $field['maxScale'] ?? 10 }}" class="flex-1" disabled>
                                                <span class="text-sm">{{ $field['maxScale'] ?? 10 }}</span>
                                            </div>
                                            @break

                                        @case('date')
                                            <input type="date" class="w-full rounded-md border-gray-300 bg-gray-50" disabled>
                                            @break

                                        @case('file')
                                            <input type="file" class="w-full text-gray-600 bg-gray-50 border border-gray-300 rounded-md" disabled>
                                            @break
                                    @endswitch
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Quick Stats</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Total Responses</span>
                            <span class="text-2xl font-bold text-blue-600">{{ $form->responses_count }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Form Fields</span>
                            <span class="text-lg font-semibold text-gray-900">{{ count($form->fields) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Created</span>
                            <span class="text-sm text-gray-600">{{ $form->created_at->format('M j, Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Last Updated</span>
                            <span class="text-sm text-gray-600">{{ $form->updated_at->format('M j, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Share Form -->
            @if($form->is_active)
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Share Form</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Public Link</label>
                            <div class="flex">
                                <input type="text" 
                                       value="{{ $form->share_url }}" 
                                       id="share-url"
                                       class="flex-1 rounded-l-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" 
                                       readonly>
                                <button onclick="copyShareUrl()" 
                                        class="px-4 py-2 bg-blue-600 text-white rounded-r-md hover:bg-blue-700 transition-colors duration-200">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>

                        <div class="flex space-x-2">
                            <a href="{{ $form->share_url }}" 
                               target="_blank"
                               class="flex-1 text-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors duration-200">
                                <i class="fas fa-external-link-alt mr-2"></i>
                                Open Form
                            </a>
                            <button onclick="previewForm()"
                                    class="flex-1 px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors duration-200">
                                <i class="fas fa-eye mr-2"></i>
                                Preview
                            </button>
                        </div>

                        <!-- QR Code (Future Enhancement) -->
                        <div class="text-center pt-4 border-t border-gray-200">
                            <p class="text-sm text-gray-600 mb-2">Share via social media</p>
                            <div class="flex justify-center space-x-2">
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode($form->share_url) }}&text={{ urlencode('Check out this form: ' . $form->title) }}" 
                                   target="_blank"
                                   class="p-2 bg-blue-400 text-white rounded-md hover:bg-blue-500">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($form->share_url) }}" 
                                   target="_blank"
                                   class="p-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="https://api.whatsapp.com/send?text={{ urlencode('Check out this form: ' . $form->title . ' ' . $form->share_url) }}" 
                                   target="_blank"
                                   class="p-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-yellow-400 mr-3"></i>
                        <div>
                            <h4 class="font-medium text-yellow-800">Form Inactive</h4>
                            <p class="text-sm text-yellow-700">Activate the form to share it publicly.</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Recent Responses -->
            @if($form->responses->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Responses</h3>
                        @if(auth()->user()->isAdmin() || $form->user_id === auth()->id())
                            <a href="{{ route('forms.responses', $form) }}" class="text-sm text-blue-600 hover:text-blue-800">
                                View All
                            </a>
                        @endif
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach($form->responses->take(5) as $response)
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center">
                                        @if($response->respondent_name)
                                            <span class="text-sm font-medium text-gray-900">{{ $response->respondent_name }}</span>
                                        @else
                                            <span class="text-sm text-gray-600">Anonymous</span>
                                        @endif
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $response->submitted_at->diffForHumans() }}</span>
                                </div>
                                @if($response->respondent_email)
                                    <p class="text-xs text-gray-600 mb-2">{{ $response->respondent_email }}</p>
                                @endif
                                <p class="text-sm text-gray-700">{{ count($response->responses) }} field(s) completed</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Actions -->
            @if(auth()->user()->isAdmin() || $form->user_id === auth()->id())
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Actions</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <button onclick="toggleFormStatus({{ $form->id }})" 
                                class="w-full px-4 py-2 {{ $form->is_active ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-md transition-colors duration-200">
                            <i class="fas {{ $form->is_active ? 'fa-pause' : 'fa-play' }} mr-2"></i>
                            {{ $form->is_active ? 'Deactivate' : 'Activate' }} Form
                        </button>
                        
                        <a href="{{ route('forms.edit', $form) }}" 
                           class="block w-full text-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors duration-200">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Form
                        </a>

                        <button onclick="duplicateForm({{ $form->id }})" 
                                class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
                            <i class="fas fa-copy mr-2"></i>
                            Duplicate Form
                        </button>

                        <button onclick="deleteForm({{ $form->id }})" 
                                class="w-full px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-200">
                            <i class="fas fa-trash mr-2"></i>
                            Delete Form
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

<script>
function copyShareUrl() {
    const shareUrl = document.getElementById('share-url');
    shareUrl.select();
    shareUrl.setSelectionRange(0, 99999); // For mobile devices

    navigator.clipboard.writeText(shareUrl.value).then(function() {
        showNotification('Share link copied to clipboard!', 'success');
    }, function(err) {
        console.error('Could not copy text: ', err);
        showNotification('Failed to copy link', 'error');
    });
}

function previewForm() {
    window.open('{{ $form->share_url }}', '_blank');
}

function toggleFormStatus(formId) {
    fetch(`/forms/${formId}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('Failed to update form status', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred', 'error');
    });
}

function duplicateForm(formId) {
    fetch(`/forms/${formId}/duplicate`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => {
                window.location.href = data.edit_url || `/forms/${data.form_id}/edit`;
            }, 1000);
        } else {
            showNotification('Failed to duplicate form', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred', 'error');
    });
}

function deleteForm(formId) {
    if (confirm('Are you sure you want to delete this form? This action cannot be undone and will delete all responses.')) {
        fetch(`/forms/${formId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => {
                    window.location.href = '/forms';
                }, 1000);
            } else {
                showNotification('Failed to delete form', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred', 'error');
        });
    }
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
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                @if(request()->get('show') === 'all' && auth()->user()->isAdmin())
                    {{ __('All Forms') }}
                @else
                    {{ __('My Forms') }}
                @endif
            </h2>
            <a href="{{ route('forms.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                <i class="fas fa-plus mr-2"></i>
                Create New Form
            </a>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($forms as $form)
            <div class="bg-white overflow-hidden shadow-lg rounded-lg border border-gray-200 hover:shadow-xl transition-shadow duration-200">
                <!-- Form Header -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $form->title }}</h3>
                            @if($form->description)
                                <p class="text-sm text-gray-600 line-clamp-2">{{ $form->description }}</p>
                            @endif
                        </div>
                        
                        <!-- Status Badge -->
                        <div class="ml-4 flex-shrink-0">
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
                </div>

                <!-- Form Stats -->
                <div class="px-6 py-4 bg-gray-50">
                    <div class="flex items-center justify-between text-sm text-gray-600">
                        <div class="flex items-center">
                            <i class="fas fa-eye mr-1"></i>
                            <span>{{ $form->responses_count }} responses</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-calendar mr-1"></i>
                            <span>{{ $form->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    
                    @if(request()->get('show') === 'all' && auth()->user()->isAdmin())
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <i class="fas fa-user mr-1"></i>
                            <span>Created by: {{ $form->user->name }}</span>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="px-6 py-4 bg-white border-t border-gray-100">
                    <div class="flex items-center justify-between">
                        <div class="flex space-x-2">
                            <a href="{{ route('forms.show', $form) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                <i class="fas fa-eye mr-1"></i>
                                View
                            </a>
                            
                            @if(auth()->user()->isAdmin() || $form->user_id === auth()->id())
                                <a href="{{ route('forms.edit', $form) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                    <i class="fas fa-edit mr-1"></i>
                                    Edit
                                </a>
                                
                                <a href="{{ route('forms.analytics', $form) }}" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                    <i class="fas fa-chart-bar mr-1"></i>
                                    Analytics
                                </a>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-1">
                            @if($form->is_active)
                                <button onclick="copyShareLink('{{ $form->share_url }}')" class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors duration-200" title="Copy Share Link">
                                    <i class="fas fa-share"></i>
                                </button>
                            @endif
                            
                            @if(auth()->user()->isAdmin() || $form->user_id === auth()->id())
                                <button onclick="toggleFormStatus({{ $form->id }})" class="p-2 {{ $form->is_active ? 'text-yellow-600 hover:text-yellow-800 hover:bg-yellow-50' : 'text-green-600 hover:text-green-800 hover:bg-green-50' }} rounded-lg transition-colors duration-200" title="{{ $form->is_active ? 'Deactivate' : 'Activate' }}">
                                    <i class="fas {{ $form->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                </button>
                                
                                <button onclick="deleteForm({{ $form->id }})" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors duration-200" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                @if($form->expires_at)
                    <div class="px-6 py-2 bg-yellow-50 border-t border-yellow-200">
                        <p class="text-xs text-yellow-800">
                            <i class="fas fa-clock mr-1"></i>
                            Expires: {{ $form->expires_at->format('M j, Y g:i A') }}
                        </p>
                    </div>
                @endif
            </div>
        @empty
            <!-- Empty State -->
            <div class="col-span-full flex flex-col items-center justify-center py-12">
                <div class="text-center">
                    <i class="fas fa-clipboard-list text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No forms yet</h3>
                    <p class="text-gray-500 mb-6">Get started by creating your first form to collect feedback and responses.</p>
                    <a href="{{ route('forms.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 transition ease-in-out duration-150">
                        <i class="fas fa-plus mr-2"></i>
                        Create Your First Form
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($forms->hasPages())
        <div class="mt-8">
            {{ $forms->links() }}
        </div>
    @endif
</x-app-layout>

<script>
function copyShareLink(url) {
    navigator.clipboard.writeText(url).then(function() {
        // Show success message
        showNotification('Share link copied to clipboard!', 'success');
    }, function(err) {
        console.error('Could not copy text: ', err);
        showNotification('Failed to copy link', 'error');
    });
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
            location.reload(); // Refresh to update UI
        } else {
            showNotification('Failed to update form status', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred', 'error');
    });
}

function deleteForm(formId) {
    if (confirm('Are you sure you want to delete this form? This action cannot be undone.')) {
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
                location.reload(); // Refresh to update UI
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
    // Create notification element
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

    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);

    // Animate out and remove
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}
</script>
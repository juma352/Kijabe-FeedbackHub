@extends('layouts.app')

@section('title', 'Feedback Management')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid px-6 py-8">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Feedback Management</h1>
            <p class="text-gray-600 mt-2">Manage feedback sentiment and coordinate departmental actions</p>
        </div>
        
        <!-- Bulk Actions Button -->
        <div class="flex space-x-3">
            <button id="bulk-action-btn" 
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium disabled:opacity-50 disabled:cursor-not-allowed" 
                    disabled>
                <i class="fas fa-envelope mr-2"></i>Send Bulk Notifications
            </button>
            <button id="require-action-btn" 
                    class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-medium disabled:opacity-50 disabled:cursor-not-allowed" 
                    disabled>
                <i class="fas fa-exclamation-triangle mr-2"></i>Require Action
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-comments text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    <p class="text-gray-600 text-sm">Total Feedback</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['action_required'] }}</p>
                    <p class="text-gray-600 text-sm">Action Required</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-orange-100 rounded-lg">
                    <i class="fas fa-clock text-orange-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_actions'] }}</p>
                    <p class="text-gray-600 text-sm">Pending Actions</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <i class="fas fa-frown text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['negative_sentiment'] }}</p>
                    <p class="text-gray-600 text-sm">Negative Sentiment</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-edit text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['manually_edited'] }}</p>
                    <p class="text-gray-600 text-sm">Manual Edits</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-900">Feedback Items</h2>
                
                <!-- Select All Checkbox -->
                <div class="flex items-center space-x-4">
                    <label class="flex items-center">
                        <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Select All</span>
                    </label>
                    <span id="selection-count" class="text-sm text-gray-500">0 selected</span>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" id="header-checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Content</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Sentiment
                            <div class="text-xs font-normal text-gray-400 mt-1">(Edit via Edit button)</div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Departments</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($feedbacks as $feedback)
                    <tr class="hover:bg-gray-50" data-feedback-id="{{ $feedback->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" class="feedback-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500" 
                                   value="{{ $feedback->id }}">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ $feedback->id }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-xs">
                                {{ Str::limit($feedback->content, 100) }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ $feedback->source }} • {{ $feedback->created_at->format('M j, Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="sentiment-container">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                             {{ $feedback->sentiment === 'positive' ? 'bg-green-100 text-green-800' : 
                                                ($feedback->sentiment === 'negative' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($feedback->sentiment) }}
                                </span>
                                @if($feedback->sentiment_manually_edited)
                                    <div class="text-xs text-yellow-600 mt-1">
                                        <i class="fas fa-edit"></i> Manually Edited
                                        @if($feedback->original_sentiment)
                                            (was {{ ucfirst($feedback->original_sentiment) }})
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col space-y-1">
                                @if($feedback->action_required)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-exclamation-triangle mr-1"></i> Action Required
                                    </span>
                                    @if($feedback->action_taken_at)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i> Action Taken
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            <i class="fas fa-clock mr-1"></i> Pending
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        No Action Required
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="departments-container" data-feedback-id="{{ $feedback->id }}">
                                @if($feedback->departments)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($feedback->departments as $dept)
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ ucwords(str_replace('_', ' ', $dept)) }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <button class="suggest-departments-btn text-xs text-blue-600 hover:text-blue-800" 
                                            data-feedback-id="{{ $feedback->id }}">
                                        <i class="fas fa-lightbulb mr-1"></i> Suggest Departments
                                    </button>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('feedback.show', $feedback) }}" 
                                   class="text-blue-600 hover:text-blue-900 p-2 rounded hover:bg-blue-50"
                                   title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('feedback.edit', $feedback) }}" 
                                   class="text-green-600 hover:text-green-900 p-2 rounded hover:bg-green-50"
                                   title="Edit Feedback">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $feedbacks->links() }}
        </div>
    </div>
</div>

<!-- Require Action Modal -->
<div id="require-action-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Mark Feedback - Require Action</h3>
                <button id="close-require-action-modal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="require-action-form">
                <input type="hidden" id="require-action-feedback-ids">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Departments to Assign</label>
                    <div class="space-y-2 max-h-48 overflow-y-auto">
                        @foreach($departments as $key => $name)
                        <label class="flex items-center">
                            <input type="checkbox" name="require-action-departments[]" value="{{ $key }}" 
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">{{ $name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                    <textarea name="require-action-notes" rows="3" 
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Add notes about why action is required..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancel-require-action" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700">
                        Mark as Requiring Action
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Action Modal -->
<div id="bulk-action-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Send Bulk Notifications</h3>
                <button id="close-modal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="bulk-notification-form">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Departments</label>
                    <div class="space-y-2 max-h-48 overflow-y-auto">
                        @foreach($departments as $key => $name)
                        <label class="flex items-center">
                            <input type="checkbox" name="departments[]" value="{{ $key }}" 
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">{{ $name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Custom Message (Optional)</label>
                    <textarea name="custom_message" rows="3" 
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Add any additional context or instructions..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancel-bulk-action" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Send Notifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Wait for the entire page to load including images/assets
window.addEventListener('load', function() {
    console.log('Window fully loaded - initializing checkboxes');
    
    const bulkActionBtn = document.getElementById('bulk-action-btn');
    const requireActionBtn = document.getElementById('require-action-btn');
    const selectionCount = document.getElementById('selection-count');
    
    function updateSelectionState() {
        const checkedBoxes = document.querySelectorAll('.feedback-checkbox:checked');
        const count = checkedBoxes.length;
        
        console.log('Checked boxes:', count);
        
        if (selectionCount) {
            selectionCount.textContent = `${count} selected`;
        }
        
        if (bulkActionBtn) {
            bulkActionBtn.disabled = count === 0;
        }
        
        if (requireActionBtn) {
            requireActionBtn.disabled = count === 0;
        }
    }
    
    // Attach change listeners to all checkboxes
    document.querySelectorAll('.feedback-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectionState);
    });
    
    // Bulk action button
    if (bulkActionBtn) {
        bulkActionBtn.addEventListener('click', function() {
            const modal = document.getElementById('bulk-action-modal');
            if (modal) modal.classList.remove('hidden');
        });
    }
    
    // Require Action button
    if (requireActionBtn) {
        requireActionBtn.addEventListener('click', function() {
            const checkedFeedback = Array.from(document.querySelectorAll('.feedback-checkbox:checked'))
                .map(cb => cb.value);
            
            if (checkedFeedback.length === 0) {
                alert('Please select at least one feedback item');
                return;
            }
            
            const modal = document.getElementById('require-action-modal');
            if (modal) {
                document.getElementById('require-action-feedback-ids').value = JSON.stringify(checkedFeedback);
                modal.classList.remove('hidden');
            }
        });
    }
    
    // Modal close handlers
    ['close-require-action-modal', 'cancel-require-action'].forEach(id => {
        const btn = document.getElementById(id);
        if (btn) {
            btn.addEventListener('click', () => {
                document.getElementById('require-action-modal').classList.add('hidden');
            });
        }
    });
    
    ['close-modal', 'cancel-bulk-action'].forEach(id => {
        const btn = document.getElementById(id);
        if (btn) {
            btn.addEventListener('click', () => {
                document.getElementById('bulk-action-modal').classList.add('hidden');
            });
        }
    });
    
    // Require action form
    const requireActionForm = document.getElementById('require-action-form');
    if (requireActionForm) {
        requireActionForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const feedbackIds = JSON.parse(document.getElementById('require-action-feedback-ids').value);
            const selectedDepartments = Array.from(document.querySelectorAll('input[name="require-action-departments[]"]:checked'))
                .map(cb => cb.value);
            
            if (selectedDepartments.length === 0) {
                alert('Please select at least one department');
                return;
            }
            
            fetch('/feedback/require-action', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    feedback_ids: feedbackIds,
                    departments: selectedDepartments,
                    notes: document.querySelector('textarea[name="require-action-notes"]').value
                })
            })
            .then(r => r.json())
            .then(data => {
                alert(data.message);
                location.reload();
            })
            .catch(e => alert('Error: ' + e));
        });
    }
    
    // Bulk notifications form
    const bulkForm = document.getElementById('bulk-notification-form');
    if (bulkForm) {
        bulkForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const checkedFeedback = Array.from(document.querySelectorAll('.feedback-checkbox:checked'))
                .map(cb => cb.value);
            const selectedDepartments = Array.from(document.querySelectorAll('input[name="departments[]"]:checked'))
                .map(cb => cb.value);
            
            if (selectedDepartments.length === 0) {
                alert('Please select at least one department');
                return;
            }
            
            fetch('/feedback/bulk-notifications', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    feedback_ids: checkedFeedback,
                    departments: selectedDepartments,
                    custom_message: document.querySelector('textarea[name="custom_message"]').value
                })
            })
            .then(r => r.json())
            .then(data => {
                alert(data.message);
                location.reload();
            })
            .catch(e => alert('Error: ' + e));
        });
    }
    
    console.log('Setup complete');
    updateSelectionState();
});
</script>
@endsection
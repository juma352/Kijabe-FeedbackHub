<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Feedback Hub Dashboard') }}
        </h2>
    </x-slot>

    <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-comments text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500">Total Feedbacks</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ number_format($totalFeedbacks) }}</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-star text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500">Average Rating</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ $averageRating ? number_format($averageRating, 1) : 'N/A' }}</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-smile text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500">Positive</dt>
                                <dd class="text-2xl font-semibold text-green-600">{{ $sentimentCounts['positive'] }}</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-frown text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500">Negative</dt>
                                <dd class="text-2xl font-semibold text-red-600">{{ $sentimentCounts['negative'] }}</dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                    <div class="flex space-x-4">
                        <a href="{{ route('feedback.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Add New Feedback
                        </a>
                        <a href="{{ route('feedback.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            View All Feedback
                        </a>
                        <a href="{{ route('feedback.analytics') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            View Analytics
                        </a>
                        <a href="{{ route('feedback.import') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Import External Data
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Feedback -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Recent Feedback</h3>
                        <a href="{{ route('feedback.index') }}" class="text-sm text-blue-600 hover:text-blue-900">View all →</a>
                    </div>
                    
                    @if($recentFeedbacks->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentFeedbacks as $feedback)
                                <div class="border-l-4 @if($feedback->sentiment === 'positive') border-green-400 @elseif($feedback->sentiment === 'negative') border-red-400 @else border-gray-400 @endif bg-gray-50 p-4 rounded-r">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900">{{ $feedback->source }}</p>
                                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($feedback->content, 150) }}</p>
                                            <div class="flex items-center space-x-4 mt-2">
                                                @if($feedback->rating)
                                                    <span class="text-xs text-gray-500">Rating: {{ $feedback->rating }}/5</span>
                                                @endif
                                                @if($feedback->sentiment)
                                                    <span class="text-xs px-2 py-1 rounded-full @if($feedback->sentiment === 'positive') bg-green-100 text-green-800 @elseif($feedback->sentiment === 'negative') bg-red-100 text-red-800 @else bg-gray-100 text-gray-800 @endif">
                                                        {{ ucfirst($feedback->sentiment) }}
                                                    </span>
                                                @endif
                                                <span class="text-xs text-gray-500">{{ $feedback->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        <a href="{{ route('feedback.show', $feedback) }}" class="text-blue-600 hover:text-blue-900 text-sm">
                                            View
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-inbox mx-auto h-12 w-12 text-gray-400 text-5xl"></i>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No feedback yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating your first feedback entry.</p>
                            <div class="mt-6">
                                <a href="{{ route('feedback.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Add Feedback
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
</x-app-layout>

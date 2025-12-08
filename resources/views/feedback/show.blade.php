<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Feedback Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Navigation -->
            <div class="mb-6">
                <a href="{{ route('feedback.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-900">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Feedback List
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Feedback Content</h3>
                                <div class="flex space-x-2">
                                    <a href="{{ route('feedback.edit', $feedback) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Edit
                                    </a>
                                    <form action="{{ route('feedback.destroy', $feedback) }}" method="POST" class="inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this feedback?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-2 border border-red-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="prose max-w-none">
                                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                                    <p class="text-gray-900 whitespace-pre-wrap">{{ $feedback->content }}</p>
                                </div>
                            </div>

                            <!-- Metadata -->
                            @if($feedback->metadata && is_array($feedback->metadata) && count($feedback->metadata) > 0)
                                <div class="mt-6">
                                    <h4 class="text-md font-medium text-gray-900 mb-3">Additional Information</h4>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <dl class="space-y-2">
                                            @foreach($feedback->metadata as $key => $value)
                                                <div class="flex">
                                                    <dt class="text-sm font-medium text-gray-500 capitalize min-w-0 flex-shrink-0 w-24">
                                                        {{ str_replace('_', ' ', $key) }}:
                                                    </dt>
                                                    <dd class="text-sm text-gray-900 ml-2">
                                                        @if(is_array($value))
                                                            {{ implode(', ', $value) }}
                                                        @else
                                                            {{ $value }}
                                                        @endif
                                                    </dd>
                                                </div>
                                            @endforeach
                                        </dl>
                                    </div>
                                </div>
                            @endif

                            <!-- Keywords -->
                            @if($feedback->keyword)
                                <div class="mt-6">
                                    <h4 class="text-md font-medium text-gray-900 mb-3">Keywords</h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(explode(',', $feedback->keyword) as $keyword)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ trim($keyword) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Basic Info -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Details</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Source</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $feedback->source }}</dd>
                                </div>

                                @if($feedback->rating)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Rating</dt>
                                    <dd class="mt-1 flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="h-4 w-4 {{ $i <= $feedback->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                        <span class="ml-2 text-sm text-gray-600">{{ $feedback->rating }}/5</span>
                                    </dd>
                                </div>
                                @endif

                                @if($feedback->sentiment)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Sentiment</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            @if($feedback->sentiment === 'positive') bg-green-100 text-green-800
                                            @elseif($feedback->sentiment === 'negative') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($feedback->sentiment) }}
                                        </span>
                                    </dd>
                                </div>
                                @endif

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $feedback->created_at->format('F j, Y g:i A') }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Updated</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $feedback->updated_at->format('F j, Y g:i A') }}</dd>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Score Information -->
                    @if($feedback->score)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Score Analysis</h3>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Total Score</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $feedback->score->total_score }}</span>
                                </div>
                                
                                @if($feedback->score->sentiment_score !== null)
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500">Sentiment</span>
                                    <span class="text-sm text-gray-900">{{ $feedback->score->sentiment_score }}</span>
                                </div>
                                @endif
                                
                                @if($feedback->score->keyword_score !== null)
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500">Keywords</span>
                                    <span class="text-sm text-gray-900">{{ $feedback->score->keyword_score }}</span>
                                </div>
                                @endif
                                
                                @if($feedback->score->urgency_score !== null)
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500">Urgency</span>
                                    <span class="text-sm text-gray-900">{{ $feedback->score->urgency_score }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
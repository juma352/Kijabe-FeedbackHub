<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Feedback') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <!-- Navigation -->
            <div class="mb-6">
                <a href="{{ route('feedback.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-900">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Feedback List
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('feedback.update', $feedback) }}">
                        @csrf
                        @method('PUT')

                        <!-- Source -->
                        <div class="mb-6">
                            <x-input-label for="source" :value="__('Source')" />
                            <x-text-input id="source" class="block mt-1 w-full" type="text" name="source" :value="old('source', $feedback->source)" required autofocus />
                            <x-input-error :messages="$errors->get('source')" class="mt-2" />
                            <p class="text-sm text-gray-600 mt-1">Where did this feedback come from? (e.g., Website, Email, Social Media)</p>
                        </div>

                        <!-- Content -->
                        <div class="mb-6">
                            <x-input-label for="content" :value="__('Feedback Content')" />
                            <textarea id="content" name="content" rows="6" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('content', $feedback->content) }}</textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                            <p class="text-sm text-gray-600 mt-1">The actual feedback text from the customer</p>
                        </div>

                        <!-- Rating -->
                        <div class="mb-6">
                            <x-input-label for="rating" :value="__('Rating (Optional)')" />
                            <select id="rating" name="rating" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Select a rating</option>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ old('rating', $feedback->rating) == $i ? 'selected' : '' }}>
                                        {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                                    </option>
                                @endfor
                            </select>
                            <x-input-error :messages="$errors->get('rating')" class="mt-2" />
                            <p class="text-sm text-gray-600 mt-1">Customer rating if provided (1-5 stars)</p>
                        </div>

                        <!-- Sentiment -->
                        <div class="mb-6">
                            <x-input-label for="sentiment" :value="__('Sentiment')" />
                            <div class="mt-2">
                                <select id="sentiment" name="sentiment" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="positive" {{ old('sentiment', $feedback->sentiment) == 'positive' ? 'selected' : '' }}>
                                        😊 Positive
                                    </option>
                                    <option value="neutral" {{ old('sentiment', $feedback->sentiment) == 'neutral' ? 'selected' : '' }}>
                                        😐 Neutral
                                    </option>
                                    <option value="negative" {{ old('sentiment', $feedback->sentiment) == 'negative' ? 'selected' : '' }}>
                                        😞 Negative
                                    </option>
                                </select>
                                <x-input-error :messages="$errors->get('sentiment')" class="mt-2" />
                                @if($feedback->sentiment_manually_edited)
                                    <div class="mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-info-circle text-yellow-400"></i>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm text-yellow-800">
                                                    This sentiment has been manually edited.
                                                    @if($feedback->original_sentiment)
                                                        Original AI prediction was: <strong>{{ ucfirst($feedback->original_sentiment) }}</strong>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-sm text-gray-600 mt-1">
                                        Current sentiment analysis. Changes will be tracked as manual edits.
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Metadata -->
                        <div class="mb-6">
                            <x-input-label for="metadata" :value="__('Additional Information (JSON)')" />
                            <textarea id="metadata" name="metadata" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder='{"customer_id": 123, "product": "Widget A"}'>{{ old('metadata', is_array($feedback->metadata) ? json_encode($feedback->metadata, JSON_PRETTY_PRINT) : $feedback->metadata) }}</textarea>
                            <x-input-error :messages="$errors->get('metadata')" class="mt-2" />
                            <p class="text-sm text-gray-600 mt-1">Optional JSON data with additional context (customer info, product details, etc.)</p>
                        </div>

                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('feedback.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Update Feedback') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
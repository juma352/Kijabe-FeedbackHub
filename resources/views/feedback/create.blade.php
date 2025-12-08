<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Feedback') }}
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
                    <form method="POST" action="{{ route('feedback.store') }}">
                        @csrf

                        <!-- Source -->
                        <div class="mb-6">
                            <x-input-label for="source" :value="__('Source')" />
                            <select id="source" name="source" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select feedback source</option>
                                <option value="Website" {{ old('source') === 'Website' ? 'selected' : '' }}>Website</option>
                                <option value="WhatsApp" {{ old('source') === 'WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                                <option value="Email" {{ old('source') === 'Email' ? 'selected' : '' }}>Email</option>
                                <option value="Twitter" {{ old('source') === 'Twitter' ? 'selected' : '' }}>Twitter</option>
                                <option value="Facebook" {{ old('source') === 'Facebook' ? 'selected' : '' }}>Facebook</option>
                                <option value="Instagram" {{ old('source') === 'Instagram' ? 'selected' : '' }}>Instagram</option>
                                <option value="Phone Call" {{ old('source') === 'Phone Call' ? 'selected' : '' }}>Phone Call</option>
                                <option value="In-Person" {{ old('source') === 'In-Person' ? 'selected' : '' }}>In-Person</option>
                                <option value="Survey" {{ old('source') === 'Survey' ? 'selected' : '' }}>Survey</option>
                                <option value="Other" {{ old('source') === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <x-input-error :messages="$errors->get('source')" class="mt-2" />
                            <p class="text-sm text-gray-600 mt-1">Where did this feedback come from?</p>
                        </div>

                        <!-- Content -->
                        <div class="mb-6">
                            <x-input-label for="content" :value="__('Feedback Content')" />
                            <textarea id="content" name="content" rows="6" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required placeholder="Enter the customer feedback here...">{{ old('content') }}</textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                            <p class="text-sm text-gray-600 mt-1">The actual feedback text from the customer</p>
                        </div>

                        <!-- Rating -->
                        <div class="mb-6">
                            <x-input-label for="rating" :value="__('Rating (Optional)')" />
                            <select id="rating" name="rating" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Select a rating</option>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
                                        {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                                    </option>
                                @endfor
                            </select>
                            <x-input-error :messages="$errors->get('rating')" class="mt-2" />
                            <p class="text-sm text-gray-600 mt-1">Customer rating if provided (1-5 stars)</p>
                        </div>

                        <!-- Metadata -->
                        <div class="mb-6">
                            <x-input-label for="metadata" :value="__('Additional Information (JSON)')" />
                            <textarea id="metadata" name="metadata" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder='{"customer_id": 123, "product": "Widget A", "order_number": "12345"}'>{{ old('metadata') }}</textarea>
                            <x-input-error :messages="$errors->get('metadata')" class="mt-2" />
                            <p class="text-sm text-gray-600 mt-1">Optional JSON data with additional context (customer info, product details, etc.)</p>
                        </div>

                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('feedback.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Save Feedback') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All Feedback') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Actions -->
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Feedback Management</h3>
                    <p class="text-sm text-gray-600">Manage and analyze customer feedback</p>
                </div>
                <a href="{{ route('feedback.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Add New Feedback
                </a>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Feedback List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if($feedbacks->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Source</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Content</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sentiment</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($feedbacks as $feedback)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $feedback->source }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            <div class="max-w-xs">
                                                {{ Str::limit($feedback->content, 100) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($feedback->rating)
                                                <div class="flex items-center">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="h-4 w-4 {{ $i <= $feedback->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    @endfor
                                                    <span class="ml-1 text-sm">{{ $feedback->rating }}/5</span>
                                                </div>
                                            @else
                                                <span class="text-gray-400">No rating</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($feedback->sentiment)
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                    @if($feedback->sentiment === 'positive') bg-green-100 text-green-800
                                                    @elseif($feedback->sentiment === 'negative') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ ucfirst($feedback->sentiment) }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">Not analyzed</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($feedback->score)
                                                <span class="font-medium">{{ $feedback->score->total_score }}</span>
                                            @else
                                                <span class="text-gray-400">No score</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $feedback->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            <a href="{{ route('feedback.show', $feedback) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                            <a href="{{ route('feedback.edit', $feedback) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            <form action="{{ route('feedback.destroy', $feedback) }}" method="POST" class="inline" 
                                                  onsubmit="return confirm('Are you sure you want to delete this feedback?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
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
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No feedback found</h3>
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
    </div>
</x-app-layout>
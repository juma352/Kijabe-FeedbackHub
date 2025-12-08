<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kijabe Hospital - Education Department Analytics') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Header Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-blue-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <div>
                        <h3 class="text-blue-800 font-medium">Hospital-Specific Analytics</h3>
                        <p class="text-blue-700 text-sm mt-1">
                            Analyzing feedback from Kijabe Hospital Education Department with specialized categorization and medical context understanding.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Key Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500">Hospital Feedback</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ $hospitalFeedbacks->count() }}</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500">Resolved Issues</dt>
                                <dd class="text-2xl font-semibold text-green-600">
                                    {{ $hospitalFeedbacks->filter(function($f) { 
                                        return isset($f->metadata['category']) && strtolower($f->metadata['category']) === 'urgent'; 
                                    })->count() }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500">In Progress</dt>
                                <dd class="text-2xl font-semibold text-yellow-600">
                                {{ $hospitalFeedbacks->filter(function($f) { 
                                        return isset($f->metadata['status']) && strtolower($f->metadata['status']) === 'done'; 
                                    })->count() }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500">Pending</dt>
                                <dd class="text-2xl font-semibold text-red-600">
                                    {{ $hospitalFeedbacks->filter(function($f) { 
                                        return !isset($f->metadata['status']) || (strtolower($f->metadata['status']) !== 'done' && strtolower($f->metadata['status']) !== 'in progress'); 
                                    })->count() }}
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Department Categories -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Feedback by Department Category</h3>
                        <div class="space-y-3">
                            @forelse($categoryStats as $category => $count)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-700">{{ $category }}</span>
                                    <div class="flex items-center">
                                        <span class="text-sm text-gray-600 mr-3">{{ $count }}</span>
                                        <div class="w-20 bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $hospitalFeedbacks->count() > 0 ? ($count / $hospitalFeedbacks->count()) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">No hospital feedback data available. Import your CSV file first.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Common Issues by Source</h3>
                        <div class="space-y-3">
                            @php
                                $sourceStats = $hospitalFeedbacks->groupBy('source')->map->count();
                            @endphp
                            @forelse($sourceStats as $source => $count)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-700">{{ $source }}</span>
                                    <div class="flex items-center">
                                        <span class="text-sm text-gray-600 mr-3">{{ $count }}</span>
                                        <div class="w-20 bg-gray-200 rounded-full h-2">
                                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ $hospitalFeedbacks->count() > 0 ? ($count / $hospitalFeedbacks->count()) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">No source data available.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Priority Issues -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">High Priority Issues Requiring Attention</h3>
                    
                    @php
                        $urgentFeedbacks = $hospitalFeedbacks->filter(function($f) {
                            return $f->score && $f->score->total_score < -3;
                        })->take(10);
                    @endphp
                    
                    @if($urgentFeedbacks->count() > 0)
                        <div class="space-y-4">
                            @foreach($urgentFeedbacks as $feedback)
                                <div class="border-l-4 border-red-400 bg-red-50 p-4 rounded-r">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2 mb-2">
                                                <span class="text-xs px-2 py-1 bg-red-100 text-red-800 rounded-full font-medium">
                                                    {{ $feedback->metadata['category'] ?? 'Unknown' }}
                                                </span>
                                                @if(isset($feedback->metadata['status']))
                                                    <span class="text-xs px-2 py-1 rounded-full {{ 
                                                        strtolower($feedback->metadata['status']) === 'done' ? 'bg-green-100 text-green-800' : 
                                                        (strtolower($feedback->metadata['status']) === 'in progress' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')
                                                    }}">
                                                        {{ $feedback->metadata['status'] }}
                                                    </span>
                                                @endif
                                                <span class="text-xs text-gray-500">
                                                    Score: {{ $feedback->score->total_score }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-900 mb-2">{{ Str::limit($feedback->content, 200) }}</p>
                                            <div class="flex items-center space-x-4 text-xs text-gray-500">
                                                <span>Source: {{ $feedback->source }}</span>
                                                @if(isset($feedback->metadata['responsible_person']) && !empty($feedback->metadata['responsible_person']))
                                                    <span>Assigned: {{ $feedback->metadata['responsible_person'] }}</span>
                                                @endif
                                                <span>{{ $feedback->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        <a href="{{ route('feedback.show', $feedback) }}" class="text-blue-600 hover:text-blue-900 text-sm ml-4">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No High Priority Issues</h3>
                            <p class="mt-1 text-sm text-gray-500">All feedback appears to be handled appropriately or no critical issues detected.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('feedback.import') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Import More Data
                        </a>
                        <a href="{{ route('feedback.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            View All Feedback
                        </a>
                        <a href="{{ route('feedback.analytics') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            General Analytics
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
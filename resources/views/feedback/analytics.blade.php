<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Feedback Analytics') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Key Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500">Total Feedback</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ number_format($insights['total_feedbacks']) }}</dd>
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500">Average Rating</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ $insights['avg_rating'] ? number_format($insights['avg_rating'], 1) : 'N/A' }}</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 {{ $insights['avg_score'] >= 0 ? 'bg-green-500' : 'bg-red-500' }} rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500">Average Score</dt>
                                <dd class="text-2xl font-semibold {{ $insights['avg_score'] >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ number_format($insights['avg_score'], 1) }}</dd>
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
                                <dt class="text-sm font-medium text-gray-500">High Priority</dt>
                                <dd class="text-2xl font-semibold text-red-600">{{ $insights['high_priority_count'] }}</dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trend Analysis -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Weekly Trend</h3>
                        <div class="flex items-center space-x-4">
                            <div class="flex-1">
                                <div class="flex justify-between text-sm text-gray-600 mb-1">
                                    <span>Current Week</span>
                                    <span>{{ $insights['recent_trend']['current_avg'] }}/5</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($insights['recent_trend']['current_avg'] / 5) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 flex items-center">
                            @if($insights['recent_trend']['trend'] === 'improving')
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                <span class="text-green-600 font-medium">Improving</span>
                            @elseif($insights['recent_trend']['trend'] === 'declining')
                                <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                </svg>
                                <span class="text-red-600 font-medium">Declining</span>
                            @else
                                <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h8"></path>
                                </svg>
                                <span class="text-gray-600 font-medium">Stable</span>
                            @endif
                            <span class="ml-2 text-sm text-gray-600">
                                ({{ $insights['recent_trend']['change'] > 0 ? '+' : '' }}{{ $insights['recent_trend']['change'] }})
                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Sentiment Distribution</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                                    <span class="text-sm font-medium text-gray-700">Positive</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-600 mr-3">{{ $insights['sentiment_distribution']['positive'] }}</span>
                                    <div class="w-20 bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $insights['total_feedbacks'] > 0 ? ($insights['sentiment_distribution']['positive'] / $insights['total_feedbacks']) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-gray-500 rounded-full mr-3"></div>
                                    <span class="text-sm font-medium text-gray-700">Neutral</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-600 mr-3">{{ $insights['sentiment_distribution']['neutral'] }}</span>
                                    <div class="w-20 bg-gray-200 rounded-full h-2">
                                        <div class="bg-gray-500 h-2 rounded-full" style="width: {{ $insights['total_feedbacks'] > 0 ? ($insights['sentiment_distribution']['neutral'] / $insights['total_feedbacks']) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                                    <span class="text-sm font-medium text-gray-700">Negative</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-600 mr-3">{{ $insights['sentiment_distribution']['negative'] }}</span>
                                    <div class="w-20 bg-gray-200 rounded-full h-2">
                                        <div class="bg-red-500 h-2 rounded-full" style="width: {{ $insights['total_feedbacks'] > 0 ? ($insights['sentiment_distribution']['negative'] / $insights['total_feedbacks']) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Source Breakdown & Keywords -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Feedback Sources</h3>
                        <div class="space-y-3">
                            @foreach($insights['source_breakdown'] as $source => $count)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-700">{{ $source }}</span>
                                    <div class="flex items-center">
                                        <span class="text-sm text-gray-600 mr-3">{{ $count }}</span>
                                        <div class="w-20 bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $insights['total_feedbacks'] > 0 ? ($count / $insights['total_feedbacks']) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Top Keywords</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($insights['top_keywords'] as $keyword => $count)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $keyword }} ({{ $count }})
                                </span>
                            @endforeach
                            @if(empty($insights['top_keywords']))
                                <p class="text-gray-500 text-sm">No keywords found yet. Keywords are extracted automatically from feedback content.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resolution Metrics -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Feedback Resolution Metrics</h3>
                    
                    <!-- Resolution Overview Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-50 rounded-lg p-4 border-l-4 border-blue-500">
                            <div class="text-sm text-gray-600 mb-1">Total Action Required</div>
                            <div class="text-2xl font-bold text-blue-600">{{ number_format($resolutionMetrics['total_action_required']) }}</div>
                        </div>
                        
                        <div class="bg-green-50 rounded-lg p-4 border-l-4 border-green-500">
                            <div class="text-sm text-gray-600 mb-1">Resolved</div>
                            <div class="text-2xl font-bold text-green-600">{{ number_format($resolutionMetrics['resolved']) }}</div>
                        </div>
                        
                        <div class="bg-orange-50 rounded-lg p-4 border-l-4 border-orange-500">
                            <div class="text-sm text-gray-600 mb-1">Pending</div>
                            <div class="text-2xl font-bold text-orange-600">{{ number_format($resolutionMetrics['pending']) }}</div>
                        </div>
                        
                        <div class="bg-purple-50 rounded-lg p-4 border-l-4 border-purple-500">
                            <div class="text-sm text-gray-600 mb-1">Resolution Rate</div>
                            <div class="text-2xl font-bold text-purple-600">{{ number_format($resolutionMetrics['resolution_rate']) }}%</div>
                        </div>
                    </div>
                    
                    <!-- Average Resolution Time -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-2">Average Resolution Time</h4>
                            <div class="flex items-end space-x-4">
                                <div>
                                    <div class="text-sm text-gray-600">Hours</div>
                                    <div class="text-3xl font-bold text-gray-900">{{ number_format($resolutionMetrics['avg_resolution_time_hours'], 1) }}</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-600">Days</div>
                                    <div class="text-3xl font-bold text-gray-900">{{ number_format($resolutionMetrics['avg_resolution_time_days'], 1) }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-2">Recent Activity</h4>
                            <div class="text-sm text-gray-600">Resolved in last 30 days</div>
                            <div class="text-3xl font-bold text-gray-900">{{ number_format($resolutionMetrics['recent_resolutions']) }}</div>
                        </div>
                    </div>
                    
                    <!-- Resolution by Sentiment -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Resolution by Sentiment</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($resolutionMetrics['resolution_by_sentiment'] as $sentiment => $data)
                                <div class="bg-white border rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-700 capitalize">{{ $sentiment }}</span>
                                        <span class="text-sm font-bold {{ $sentiment === 'negative' ? 'text-red-600' : ($sentiment === 'positive' ? 'text-green-600' : 'text-gray-600') }}">
                                            {{ $data['rate'] }}%
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                        <div class="h-2 rounded-full {{ $sentiment === 'negative' ? 'bg-red-500' : ($sentiment === 'positive' ? 'bg-green-500' : 'bg-gray-500') }}" 
                                             style="width: {{ $data['rate'] }}%"></div>
                                    </div>
                                    <div class="text-xs text-gray-600">
                                        {{ $data['resolved'] }} / {{ $data['total'] }} resolved
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Resolution by Department -->
                    @if(!empty($resolutionMetrics['resolution_by_department']))
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3">Resolution by Department</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Department</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Resolved</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pending</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rate</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($resolutionMetrics['resolution_by_department'] as $department => $data)
                                            <tr>
                                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $department }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-600">{{ $data['total'] }}</td>
                                                <td class="px-4 py-3 text-sm text-green-600">{{ $data['resolved'] }}</td>
                                                <td class="px-4 py-3 text-sm text-orange-600">{{ $data['pending'] }}</td>
                                                <td class="px-4 py-3 text-sm">
                                                    <div class="flex items-center">
                                                        <span class="font-semibold {{ $data['rate'] >= 75 ? 'text-green-600' : ($data['rate'] >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                                            {{ $data['rate'] }}%
                                                        </span>
                                                        <div class="ml-2 w-16 bg-gray-200 rounded-full h-1.5">
                                                            <div class="h-1.5 rounded-full {{ $data['rate'] >= 75 ? 'bg-green-500' : ($data['rate'] >= 50 ? 'bg-yellow-500' : 'bg-red-500') }}" 
                                                                 style="width: {{ $data['rate'] }}%"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('feedback.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            View All Feedback
                        </a>
                        <a href="{{ route('feedback.import') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Import External Data
                        </a>
                        <form action="{{ route('feedback.bulk.analyze') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Re-analyze All
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Learner Experience Analytics') }}
            </h2>
            <div class="flex space-x-2">
                <form method="POST" action="{{ route('feedback.bulk.learner.experience') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Calculate All Scores
                    </button>
                </form>
                <form method="POST" action="{{ route('feedback.bulk.learner.experience') }}" style="display: inline;">
                    @csrf
                    <input type="hidden" name="recalculate" value="1">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm">
                        <i class="fas fa-redo mr-2"></i>
                        Recalculate All
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Header Info -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-blue-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <div>
                        <h3 class="text-blue-800 font-medium">Comprehensive Learner Experience Analysis</h3>
                        <p class="text-blue-700 text-sm mt-1">
                            Four-component analysis: Learning Environment, Content Quality, Learner Engagement, and Support System
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500">Analyzed Feedback</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ number_format($experienceInsights['total_analyzed'] ?? 0) }}</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 {{ ($experienceInsights['average_scores']['total'] ?? 0) >= 75 ? 'bg-green-500' : (($experienceInsights['average_scores']['total'] ?? 0) >= 50 ? 'bg-yellow-500' : 'bg-red-500') }} rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500">Average Experience Score</dt>
                                <dd class="text-2xl font-semibold {{ ($experienceInsights['average_scores']['total'] ?? 0) >= 75 ? 'text-green-600' : (($experienceInsights['average_scores']['total'] ?? 0) >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ number_format($experienceInsights['average_scores']['total'] ?? 0, 1) }}/100
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    $topPriority = collect($experienceInsights['priority_areas'] ?? [])->sortDesc()->keys()->first();
                    $priorityCount = $experienceInsights['priority_areas'][$topPriority] ?? 0;
                @endphp
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
                                <dt class="text-sm font-medium text-gray-500">Top Priority Area</dt>
                                <dd class="text-sm font-semibold text-red-600">{{ $topPriority ?? 'None' }}</dd>
                                <dd class="text-xs text-gray-500">{{ $priorityCount }} issues</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500">Exceptional Experiences</dt>
                                <dd class="text-2xl font-semibold text-purple-600">{{ $experienceInsights['experience_levels']['Exceptional'] ?? 0 }}</dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Component Scores -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Average Component Scores</h3>
                        <div class="space-y-4">
                            @foreach([
                                'Environment' => $experienceInsights['average_scores']['environment'] ?? 0,
                                'Content Quality' => $experienceInsights['average_scores']['content_quality'] ?? 0,
                                'Engagement' => $experienceInsights['average_scores']['engagement'] ?? 0,
                                'Support System' => $experienceInsights['average_scores']['support_system'] ?? 0
                            ] as $component => $score)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-700">{{ $component }}</span>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm text-gray-600">{{ number_format($score, 1) }}/100</span>
                                        <div class="w-24 bg-gray-200 rounded-full h-2">
                                            <div class="h-2 rounded-full {{ $score >= 75 ? 'bg-green-500' : ($score >= 50 ? 'bg-yellow-500' : 'bg-red-500') }}" style="width: {{ $score }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Experience Level Distribution</h3>
                        <div class="space-y-3">
                            @foreach(['Exceptional', 'Excellent', 'Good', 'Satisfactory', 'Needs Improvement', 'Critical'] as $level)
                                @php
                                    $count = $experienceInsights['experience_levels'][$level] ?? 0;
                                    $total = array_sum($experienceInsights['experience_levels'] ?? []);
                                    $percentage = $total > 0 ? ($count / $total) * 100 : 0;
                                @endphp
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-700">{{ $level }}</span>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm text-gray-600">{{ $count }} ({{ number_format($percentage, 1) }}%)</span>
                                        <div class="w-20 bg-gray-200 rounded-full h-2">
                                            <div class="h-2 rounded-full {{ 
                                                $level === 'Exceptional' || $level === 'Excellent' ? 'bg-green-500' :
                                                ($level === 'Good' || $level === 'Satisfactory' ? 'bg-blue-500' : 'bg-red-500')
                                            }}" style="width: {{ min(100, $percentage * 2) }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Priority Areas Analysis -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Areas Requiring Most Attention</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach($experienceInsights['priority_areas'] ?? [] as $area => $count)
                            <div class="text-center p-4 bg-red-50 rounded-lg border border-red-200">
                                <div class="text-2xl font-bold text-red-600">{{ $count }}</div>
                                <div class="text-sm text-red-700 font-medium">{{ $area }}</div>
                                <div class="text-xs text-red-600">issues identified</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Recent Experiences -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Learner Experiences</h3>
                    
                    @if($recentExperiences->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentExperiences as $feedback)
                                <div class="border rounded-lg p-4 hover:bg-gray-50">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2 mb-2">
                                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $feedback->learnerExperience->level_color }}">
                                                    {{ $feedback->learnerExperience->experience_level }}
                                                </span>
                                                <span class="text-sm font-semibold {{ $feedback->learnerExperience->total_score >= 75 ? 'text-green-600' : ($feedback->learnerExperience->total_score >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                                    {{ number_format($feedback->learnerExperience->total_score, 1) }}/100
                                                </span>
                                                @if($feedback->learnerExperience->experience_data['priority_areas'])
                                                    <span class="text-xs text-gray-500">
                                                        Priority: {{ implode(', ', $feedback->learnerExperience->experience_data['priority_areas']) }}
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <p class="text-sm text-gray-900 mb-2">{{ Str::limit($feedback->content, 150) }}</p>
                                            
                                            <div class="grid grid-cols-4 gap-2 mb-2">
                                                <div class="text-center">
                                                    <div class="text-xs text-gray-500">Environment</div>
                                                    <div class="text-sm font-medium">{{ number_format($feedback->learnerExperience->environment_score, 1) }}</div>
                                                </div>
                                                <div class="text-center">
                                                    <div class="text-xs text-gray-500">Content</div>
                                                    <div class="text-sm font-medium">{{ number_format($feedback->learnerExperience->content_quality_score, 1) }}</div>
                                                </div>
                                                <div class="text-center">
                                                    <div class="text-xs text-gray-500">Engagement</div>
                                                    <div class="text-sm font-medium">{{ number_format($feedback->learnerExperience->engagement_score, 1) }}</div>
                                                </div>
                                                <div class="text-center">
                                                    <div class="text-xs text-gray-500">Support</div>
                                                    <div class="text-sm font-medium">{{ number_format($feedback->learnerExperience->support_system_score, 1) }}</div>
                                                </div>
                                            </div>
                                            
                                            <div class="flex items-center space-x-4 text-xs text-gray-500">
                                                <span>Source: {{ $feedback->source }}</span>
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
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No Experience Data Yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Import feedback data to see learner experience analysis.</p>
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
                        <a href="{{ route('feedback.analytics') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Hospital Analytics
                        </a>
                        <a href="{{ route('feedback.analytics') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            General Analytics
                        </a>
                        <form action="{{ route('feedback.bulk.analyze') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Recalculate All Scores
                            </button>
                        </form>
                        <form action="{{ route('feedback.bulk.learner.experience') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Calculate Learner Experiences (50 at a time)
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
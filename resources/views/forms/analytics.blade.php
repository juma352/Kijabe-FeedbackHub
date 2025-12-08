<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $form->title }} - Analytics
                </h2>
                <p class="text-sm text-gray-600 mt-1">Response insights and statistics</p>
            </div>
            <a href="{{ route('forms.show', $form) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Form
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <i class="fas fa-comments text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Total Responses</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $analytics['total_responses'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Completion Rate</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $analytics['response_rate'] ?? 0 }}%</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-lg">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Avg. Completion Time</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $analytics['completion_time'] ?? '~2 min' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-lg">
                            <i class="fas fa-list text-purple-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Total Fields</p>
                            <p class="text-2xl font-bold text-gray-900">{{ count($form->fields) ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Field Analytics -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Field Response Analysis</h3>
            </div>
            <div class="p-6">
                @if(isset($analytics['field_analytics']) && count($analytics['field_analytics']) > 0)
                    <div class="space-y-6">
                        @foreach($analytics['field_analytics'] as $fieldAnalysis)
                            <div class="border-l-4 border-blue-500 pl-4 py-2">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $fieldAnalysis['field']['label'] ?? 'Unknown Field' }}</h4>
                                        <p class="text-xs text-gray-600 mt-1">{{ ucfirst($fieldAnalysis['field']['type'] ?? 'text') }} field</p>
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        {{ $fieldAnalysis['response_count'] ?? 0 }} responses
                                    </span>
                                </div>

                                <!-- Completion Rate Bar -->
                                <div class="mb-4">
                                    <div class="flex justify-between text-xs text-gray-600 mb-1">
                                        <span>Completion Rate</span>
                                        <span>{{ $fieldAnalysis['completion_rate'] ?? 0 }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $fieldAnalysis['completion_rate'] ?? 0 }}%"></div>
                                    </div>
                                </div>

                                <!-- Field Type Specific Stats -->
                                @if(isset($fieldAnalysis['average']))
                                    <div class="grid grid-cols-3 gap-4 text-sm">
                                        <div class="bg-gray-50 p-3 rounded">
                                            <p class="text-gray-600">Average</p>
                                            <p class="text-lg font-bold text-gray-900">{{ $fieldAnalysis['average'] }}</p>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded">
                                            <p class="text-gray-600">Min</p>
                                            <p class="text-lg font-bold text-gray-900">{{ $fieldAnalysis['min'] ?? 'N/A' }}</p>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded">
                                            <p class="text-gray-600">Max</p>
                                            <p class="text-lg font-bold text-gray-900">{{ $fieldAnalysis['max'] ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Multiple Choice Stats -->
                                @if(isset($fieldAnalysis['value_distribution']) && is_array($fieldAnalysis['value_distribution']))
                                    <div class="mt-3">
                                        <p class="text-sm font-medium text-gray-700 mb-2">Response Distribution:</p>
                                        <div class="space-y-2">
                                            @foreach($fieldAnalysis['value_distribution'] as $value => $count)
                                                <div class="flex items-center justify-between text-sm">
                                                    <span class="text-gray-600">{{ $value }}</span>
                                                    <div class="flex items-center space-x-2">
                                                        <div class="w-24 bg-gray-200 rounded-full h-1.5">
                                                            <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ ($count / ($fieldAnalysis['response_count'] ?? 1)) * 100 }}%"></div>
                                                        </div>
                                                        <span class="text-gray-900 font-medium min-w-12 text-right">{{ $count }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-chart-bar text-4xl text-gray-300 mb-4 block"></i>
                        <p class="text-gray-600">No responses yet. Analytics will appear once people submit your form.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Response Status -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Form Status</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-sm text-gray-600">Form Status</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1">
                            @if($form->is_active)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-circle text-xs mr-2"></i> Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-pause text-xs mr-2"></i> Inactive
                                </span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Created</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $form->created_at->format('M j, Y') }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Last Updated</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $form->updated_at->format('M j, Y g:i A') }}</p>
                    </div>
                </div>

                @if($form->expires_at)
                    <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-900">
                            <i class="fas fa-info-circle mr-2"></i>
                            This form expires on <strong>{{ $form->expires_at->format('M j, Y g:i A') }}</strong>
                            @if($form->isExpired())
                                <span class="text-red-600 font-semibold">(Expired)</span>
                            @else
                                <span class="text-green-600">({{ $form->expires_at->diffForHumans() }})</span>
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('forms.show', $form) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Form
            </a>
            <a href="{{ route('forms.edit', $form) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                <i class="fas fa-edit mr-2"></i>
                Edit Form
            </a>
        </div>
    </div>
</x-app-layout>
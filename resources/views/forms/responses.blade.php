<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Responses</h2>
                <p class="text-sm text-gray-600 mt-1">{{ $form->title }} · {{ $responses->total() }} total</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('forms.show', $form) }}" class="px-3 py-2 text-sm bg-gray-100 text-gray-800 rounded-md border border-gray-200 hover:bg-gray-200">Back to Form</a>
                <a href="{{ route('forms.analytics', $form) }}" class="px-3 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">Analytics</a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto">
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">All Responses</h3>
                    <p class="text-sm text-gray-600">Latest responses first. Each entry shows respondent info and submitted values.</p>
                </div>
                <div class="text-sm text-gray-600">
                    {{ $responses->firstItem() ?? 0 }}-{{ $responses->lastItem() ?? 0 }} of {{ $responses->total() }}
                </div>
            </div>

            @if($responses->count() === 0)
                <div class="p-6 text-sm text-gray-600">No responses yet.</div>
            @else
                <div class="divide-y divide-gray-200">
                    @foreach($responses as $response)
                        <div class="p-6 space-y-3">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm font-semibold text-gray-900">#{{ $response->id }}</span>
                                        <span class="text-xs text-gray-500">{{ $response->submitted_at->format('M j, Y g:i A') }} ({{ $response->submitted_at->diffForHumans() }})</span>
                                    </div>
                                    <div class="text-sm text-gray-700 mt-1">
                                        @if($response->respondent_name || $response->respondent_email)
                                            <span>{{ $response->respondent_name ?? 'Anonymous' }}</span>
                                            @if($response->respondent_email)
                                                <span class="text-gray-500"> · {{ $response->respondent_email }}</span>
                                            @endif
                                        @else
                                            <span class="text-gray-500">Anonymous</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">IP {{ $response->ip_address ?? 'n/a' }}</div>
                                </div>
                                <div class="text-sm text-gray-600">{{ count($response->responses ?? []) }} fields answered</div>
                            </div>

                            <div class="bg-gray-50 border border-gray-200 rounded-md p-4">
                                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($response->responses ?? [] as $key => $value)
                                        <div>
                                            <dt class="text-xs uppercase tracking-wide text-gray-500">{{ str_replace('_', ' ', ucfirst($key)) }}</dt>
                                            <dd class="text-sm text-gray-900 mt-1">
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
                    @endforeach
                </div>

                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $responses->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

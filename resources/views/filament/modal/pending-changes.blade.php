<div class="space-y-4">
    @if(empty($changes))
        <p class="text-gray-500">No pending changes found.</p>
    @else
        <div class="overflow-hidden bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dl class="space-y-4">
                    @foreach($changes as $change)
                        <div class="border-b border-gray-200 pb-4 last:border-b-0 last:pb-0">
                            <dt class="text-sm font-medium text-gray-900 mb-2">
                                {{ $change['field'] }}
                            </dt>
                            <dd class="text-sm text-gray-700">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Current</span>
                                        <div class="mt-1 p-2 bg-red-50 border border-red-200 rounded text-red-800">
                                            {{ is_array($change['original']) ? json_encode($change['original']) : ($change['original'] ?: 'Empty') }}
                                        </div>
                                    </div>
                                    <div>
                                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">New</span>
                                        <div class="mt-1 p-2 bg-green-50 border border-green-200 rounded text-green-800">
                                            {{ is_array($change['new']) ? json_encode($change['new']) : ($change['new'] ?: 'Empty') }}
                                        </div>
                                    </div>
                                </div>
                            </dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        </div>
    @endif
</div>
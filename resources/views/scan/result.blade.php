<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scan Results') }}
        </h2>
    </x-slot>

    @php
        /** @var array $result */
        $result = $result ?? session('scan_result');
    @endphp

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">UPC</p>
                            <p class="font-mono text-lg text-gray-900">{{ $result['upc'] ?? '' }}</p>
                        </div>
                        <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-sm font-medium text-indigo-800">
                            {{ $result['category'] ?? '' }}
                        </span>
                    </div>

                    <div>
                        <h3 class="text-md font-semibold text-gray-800">Materials Awarded</h3>
                        <ul class="mt-2 divide-y divide-gray-100">
                            @forelse ($result['materials'] ?? [] as $material)
                                <li class="flex items-center justify-between py-2">
                                    <span>{{ $material['name'] }}</span>
                                    <span class="font-mono text-gray-900">+{{ $material['awarded'] }} (total: {{ $material['total'] }})</span>
                                </li>
                            @empty
                                <li class="py-2 text-sm text-gray-600">No materials awarded.</li>
                            @endforelse
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-md font-semibold text-gray-800">Blueprint Fragments</h3>
                        <div class="flex items-center justify-between py-2">
                            <div>
                                <p class="font-medium text-gray-900">{{ $result['blueprint']['name'] ?? '' }}</p>
                                <p class="text-sm text-gray-600">
                                    Total: {{ $result['blueprint']['total_fragments'] ?? 0 }} / {{ $result['blueprint']['required_fragments'] ?? 0 }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-mono text-gray-900">+{{ $result['blueprint']['awarded'] ?? 0 }}</p>
                                @if (!empty($result['blueprint']['completed']))
                                    <span class="mt-1 inline-flex items-center rounded bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800">Blueprint Completed</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('scan.index') }}" class="text-indigo-600 hover:text-indigo-500 font-medium text-sm">Scan again</a>
                        <x-primary-button onclick="window.location='{{ route('dashboard') }}'">
                            {{ __('Back to Dashboard') }}
                        </x-primary-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

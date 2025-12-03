<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scan UPC') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('scan.process') }}" class="space-y-6">
                        @csrf
                        <div>
                            <label for="upc" class="block text-sm font-medium text-gray-700">UPC Code</label>
                            <input
                                id="upc"
                                name="upc"
                                type="text"
                                value="{{ old('upc') }}"
                                required
                                autofocus
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Enter UPC or code string"
                            >
                            @error('upc')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <p class="text-sm text-gray-600">Scanning will categorize the UPC and grant materials and blueprint fragments.</p>
                            <x-primary-button>
                                {{ __('Scan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            @if (session('scan_result'))
                <div class="mt-6 bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">Latest Scan</h3>
                                <p class="text-sm text-gray-600">UPC: <span class="font-mono">{{ session('scan_result.upc') }}</span></p>
                            </div>
                            <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-sm font-medium text-indigo-800">
                                {{ session('scan_result.category') }}
                            </span>
                        </div>

                        <div class="mt-4">
                            <h4 class="text-sm font-semibold text-gray-700">Materials Awarded</h4>
                            <ul class="mt-2 space-y-1 text-sm text-gray-700">
                                @foreach (session('scan_result.materials') as $material)
                                    <li class="flex items-center justify-between">
                                        <span>{{ $material['name'] }}</span>
                                        <span class="font-mono text-gray-900">+{{ $material['awarded'] }} (total: {{ $material['total'] }})</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="mt-4">
                            <h4 class="text-sm font-semibold text-gray-700">Blueprint Fragments</h4>
                            <div class="flex items-center justify-between text-sm text-gray-700">
                                <div>
                                    <p class="font-medium text-gray-900">{{ session('scan_result.blueprint.name') }}</p>
                                    <p class="text-gray-600">Total: {{ session('scan_result.blueprint.total_fragments') }} / {{ session('scan_result.blueprint.required_fragments') }}</p>
                                </div>
                                <span class="font-mono text-gray-900">+{{ session('scan_result.blueprint.awarded') }}</span>
                            </div>
                            @if (session('scan_result.blueprint.completed'))
                                <p class="mt-2 inline-flex items-center rounded bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800">Blueprint Completed</p>
                            @endif
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('scan.result') }}" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">View full results page →</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

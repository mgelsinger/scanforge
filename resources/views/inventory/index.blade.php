<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Inventory') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold text-gray-900">Materials</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
                        @forelse ($materials as $material)
                            <div class="rounded border border-gray-200 p-3">
                                <p class="text-sm font-semibold text-gray-900">{{ $material->name }}</p>
                                <p class="text-xs text-gray-600">{{ $material->category ?? 'Uncategorized' }}</p>
                                <p class="text-sm font-mono text-gray-800 mt-1">Qty: {{ $material->quantity }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-600">No materials yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Blueprints</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @forelse ($blueprints as $bp)
                            <div class="rounded border border-gray-200 p-3">
                                <p class="text-sm font-semibold text-gray-900">{{ $bp->name }}</p>
                                <p class="text-xs text-gray-600">Fragments: {{ $bp->fragments_collected }} / {{ $bp->required_fragments }}</p>
                                @if($bp->is_completed)
                                    <span class="mt-1 inline-flex items-center rounded bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">Completed</span>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-gray-600">No blueprints collected.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-3">
                    <h3 class="text-lg font-semibold text-gray-900">Blueprint Fragments</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @forelse ($fragments as $frag)
                            <div class="rounded border border-gray-200 p-3">
                                <p class="text-sm font-semibold text-gray-900">{{ $frag->blueprint?->name ?? 'Unknown Blueprint' }}</p>
                                <p class="text-sm font-mono text-gray-800">Qty: {{ $frag->quantity }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-600">No fragments yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-3">
                    <h3 class="text-lg font-semibold text-gray-900">Gear</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        @forelse ($gear as $item)
                            <div class="rounded border border-gray-200 p-3">
                                <p class="text-sm font-semibold text-gray-900">{{ $item->name }}</p>
                                <p class="text-xs text-gray-600">Type: {{ $item->type ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-600">Rarity: {{ $item->rarity }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-600">No gear crafted yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

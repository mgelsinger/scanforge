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
                    @if($materials->isEmpty())
                        <p class="text-sm text-gray-600 mt-2">You don’t have any materials yet. Try scanning some items first.</p>
                        <a href="{{ route('scan.index') }}" class="mt-3 inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-500">Go to Scan</a>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
                            @foreach ($materials as $material)
                                <div class="rounded border border-gray-200 p-3">
                                    <p class="text-sm font-semibold text-gray-900">{{ $material->name }}</p>
                                    <p class="text-xs text-gray-600">{{ $material->category ?? 'Uncategorized' }}</p>
                                    <p class="text-xs text-gray-500">Type: {{ $material->material_type ?? 'common' }} | Rarity: {{ $material->rarity ?? 'common' }}</p>
                                    <p class="text-sm font-mono text-gray-800 mt-1">Qty: {{ $material->quantity }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Blueprints</h3>
                    </div>
                    @if($blueprints->isEmpty())
                        <p class="text-sm text-gray-600">You haven’t discovered any blueprints yet.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach ($blueprints as $bp)
                                <div class="rounded border border-gray-200 p-3">
                                    <p class="text-sm font-semibold text-gray-900">{{ $bp->name }}</p>
                                    <p class="text-xs text-gray-600">Fragments: {{ $bp->fragments_collected }} / {{ $bp->required_fragments }}</p>
                                    @if($bp->is_completed)
                                        <span class="mt-1 inline-flex items-center rounded bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">Completed</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-3">
                    <h3 class="text-lg font-semibold text-gray-900">Blueprint Fragments</h3>
                    @if($fragments->isEmpty())
                        <p class="text-sm text-gray-600">You haven’t discovered any blueprint fragments yet.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach ($fragments as $frag)
                                <div class="rounded border border-gray-200 p-3">
                                    <p class="text-sm font-semibold text-gray-900">{{ $frag->blueprint?->name ?? 'Unknown Blueprint' }}</p>
                                    <p class="text-sm font-mono text-gray-800">Qty: {{ $frag->quantity }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-3">
                    <h3 class="text-lg font-semibold text-gray-900">Gear</h3>
                    @if($gear->isEmpty())
                        <p class="text-sm text-gray-600">No gear crafted yet. Craft gear in the Gear Forge.</p>
                        <a href="{{ route('craft.gear') }}" class="mt-3 inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-500">Go to Gear Forge</a>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            @foreach ($gear as $item)
                                <div class="rounded border border-gray-200 p-3">
                                    <p class="text-sm font-semibold text-gray-900">{{ $item->name }}</p>
                                    <p class="text-xs text-gray-600">Type: {{ $item->type ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-600">Rarity: {{ $item->rarity }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

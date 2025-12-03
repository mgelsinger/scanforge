<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $unit->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500">Tier</p>
                            <p class="text-lg font-semibold text-gray-900">Tier {{ $unit->tier ?? 1 }}</p>
                        </div>
                        @if(!$unit->isMaxTier())
                            <a href="{{ route('units.evolution.show', $unit) }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-500">Evolve</a>
                        @else
                            <span class="text-xs text-gray-500">Max tier reached</span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Rarity</p>
                            <p class="text-lg font-semibold text-gray-900">{{ ucfirst($unit->rarity) }}</p>
                        </div>
                        @if($unit->trait)
                            <div>
                                <p class="text-sm text-gray-500">Trait</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $unit->trait }}</p>
                            </div>
                        @endif
                        @if($unit->passive_trait)
                            <div>
                                <p class="text-sm text-gray-500">Passive</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $unit->passive_trait }}</p>
                            </div>
                        @endif
                        <div>
                            <p class="text-sm text-gray-500">Blueprint</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $unit->blueprint?->name ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="rounded border border-gray-200 p-3 text-center">
                            <p class="text-xs text-gray-500">HP</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $unit->hp }}</p>
                        </div>
                        <div class="rounded border border-gray-200 p-3 text-center">
                            <p class="text-xs text-gray-500">ATK</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $unit->attack }}</p>
                        </div>
                        <div class="rounded border border-gray-200 p-3 text-center">
                            <p class="text-xs text-gray-500">DEF</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $unit->defense }}</p>
                        </div>
                        <div class="rounded border border-gray-200 p-3 text-center">
                            <p class="text-xs text-gray-500">SPD</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $unit->speed }}</p>
                        </div>
                    </div>

                    <div class="rounded border border-dashed border-gray-300 p-4">
                        <p class="text-sm font-semibold text-gray-700">Gear Slots</p>
                        <p class="text-sm text-gray-600 mt-1">Gear slotting not yet implemented; coming in a future phase.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

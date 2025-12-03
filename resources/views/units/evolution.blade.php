<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Evolve') }} — {{ $unit->displayName() }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500">Current Tier</p>
                            <p class="text-lg font-semibold text-gray-900">Tier {{ $unit->tier ?? 1 }}</p>
                        </div>
                        @if(!$unit->isMaxTier())
                            <form method="POST" action="{{ route('units.evolution.evolve', $unit) }}">
                                @csrf
                                <x-primary-button {{ ($check['ok'] ?? false) ? '' : 'disabled' }}>
                                    {{ ($check['ok'] ?? false) ? __('Evolve') : __('Cannot evolve') }}
                                </x-primary-button>
                            </form>
                        @endif
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

                    @if(!$evolution)
                        <p class="text-sm text-gray-600">This unit has reached its maximum tier.</p>
                    @else
                        <div class="border border-gray-200 rounded p-4 space-y-3">
                            <h3 class="text-md font-semibold text-gray-900">Next Tier Preview (to Tier {{ $evolution->to_tier }})</h3>
                            @php($mods = $evolution->stat_modifiers ?? [])
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-sm">
                                <div class="rounded border border-gray-100 p-3 text-center">
                                    <p class="text-xs text-gray-500">HP</p>
                                    <p class="font-semibold text-gray-900">{{ $unit->hp + ($mods['hp'] ?? 0) }}</p>
                                    <p class="text-xs text-green-600">+{{ $mods['hp'] ?? 0 }}</p>
                                </div>
                                <div class="rounded border border-gray-100 p-3 text-center">
                                    <p class="text-xs text-gray-500">ATK</p>
                                    <p class="font-semibold text-gray-900">{{ $unit->attack + ($mods['attack'] ?? 0) }}</p>
                                    <p class="text-xs text-green-600">+{{ $mods['attack'] ?? 0 }}</p>
                                </div>
                                <div class="rounded border border-gray-100 p-3 text-center">
                                    <p class="text-xs text-gray-500">DEF</p>
                                    <p class="font-semibold text-gray-900">{{ $unit->defense + ($mods['defense'] ?? 0) }}</p>
                                    <p class="text-xs text-green-600">+{{ $mods['defense'] ?? 0 }}</p>
                                </div>
                                <div class="rounded border border-gray-100 p-3 text-center">
                                    <p class="text-xs text-gray-500">SPD</p>
                                    <p class="font-semibold text-gray-900">{{ $unit->speed + ($mods['speed'] ?? 0) }}</p>
                                    <p class="text-xs text-green-600">+{{ $mods['speed'] ?? 0 }}</p>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <p class="text-sm font-semibold text-gray-800">Required Materials</p>
                                @php
                                    $materials = \App\Models\Material::where('user_id', auth()->id())->get()->keyBy('name');
                                @endphp
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    @foreach(($evolution->required_materials ?? []) as $name => $qty)
                                        @php $have = $materials[$name]->quantity ?? 0; @endphp
                                        <div class="flex items-center justify-between rounded border border-gray-200 p-2">
                                            <span class="text-sm text-gray-800">{{ $name }}</span>
                                            <span class="text-sm font-mono {{ $have >= $qty ? 'text-green-700' : 'text-red-700' }}">{{ $have }} / {{ $qty }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                @if(!($check['ok'] ?? false))
                                    <p class="text-sm text-red-600">{{ $check['reason'] ?? 'Cannot evolve yet.' }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

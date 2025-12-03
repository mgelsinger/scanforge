<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Essence Transmuter') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('craft_success'))
                <div class="rounded-md bg-green-50 p-4 text-sm text-green-800">{{ session('craft_success') }}</div>
            @endif
            @if (session('craft_error'))
                <div class="rounded-md bg-red-50 p-4 text-sm text-red-800">{{ session('craft_error') }}</div>
            @endif

            @include('craft.partials.nav')

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <p class="text-sm text-gray-600">Convert surplus materials into refined essence. Transmutation always costs more than it returns, so pick carefully.</p>

                    @if($recipes->isEmpty())
                        <p class="text-sm text-gray-600">No transmutations available yet.</p>
                    @else
                        <div class="space-y-4">
                            @forelse ($recipes as $state)
                                @php($recipe = $state['recipe'])
                                <div class="rounded border border-gray-200 p-4 space-y-2">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $recipe->label ?? $recipe->output_material_name }}</h3>
                                            <p class="text-sm text-gray-600">Turns common materials into refined resources.</p>
                                        </div>
                                        <form method="POST" action="{{ route('craft.transmuter.transmute', $recipe) }}" class="flex items-center gap-2">
                                            @csrf
                                            <label class="text-xs text-gray-600" for="times-{{ $recipe->id }}">Times</label>
                                            <input
                                                id="times-{{ $recipe->id }}"
                                                type="number"
                                                name="times"
                                                value="{{ old('times', 1) }}"
                                                min="1"
                                                max="{{ max(1, $state['max_times']) }}"
                                                class="w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                            >
                                            <button type="submit"
                                                    @if(!$state['can_transmute']) disabled @endif
                                                    class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:cursor-not-allowed disabled:opacity-60">
                                                {{ $state['can_transmute'] ? __('Transmute') : __('Not enough materials') }}
                                            </button>
                                        </form>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm text-gray-800">
                                        <div class="bg-gray-50 rounded p-3">
                                            <p class="font-semibold text-gray-900">Input</p>
                                            <p>{{ $recipe->input_quantity }} × {{ $recipe->input_material_name }}</p>
                                            <p class="text-xs text-gray-600">You have: {{ $state['owned'] }}</p>
                                        </div>
                                        <div class="bg-gray-50 rounded p-3">
                                            <p class="font-semibold text-gray-900">Output</p>
                                            <p>{{ $recipe->output_quantity }} × {{ $recipe->output_material_name }}</p>
                                            <p class="text-xs text-gray-600">Owned: {{ $state['output_owned'] }}</p>
                                        </div>
                                        <div class="bg-gray-50 rounded p-3">
                                            <p class="font-semibold text-gray-900">Availability</p>
                                            <p class="text-sm {{ $state['can_transmute'] ? 'text-green-700' : 'text-red-700' }}">
                                                {{ $state['can_transmute'] ? $state['max_times'] . ' craft(s) possible' : 'Not enough input material' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-600">No transmutation recipes available.</p>
                            @endforelse
                        </div>

                        @if(!$recipes->contains(fn($r) => $r['can_transmute']))
                            <div class="rounded-md bg-yellow-50 p-4 text-sm text-yellow-800">
                                You don't have enough materials to transmute yet. Keep scanning or crafting to gather more.
                                <a href="{{ route('scan.index') }}" class="font-semibold underline">Go scan</a>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

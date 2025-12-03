<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Unit Foundry') }}
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

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <p class="text-sm text-gray-600">Craft forged units using completed blueprints and materials.</p>

                    <div class="space-y-4">
                        @forelse ($recipes as $recipe)
                            <div class="rounded border border-gray-200 p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $recipe->name }}</h3>
                                        <p class="text-sm text-gray-600">Requires blueprint: {{ $recipe->metadata['blueprint_name'] ?? 'N/A' }}</p>
                                    </div>
                                    <form method="POST" action="{{ route('craft.unit.craft') }}">
                                        @csrf
                                        <input type="hidden" name="recipe_id" value="{{ $recipe->id }}">
                                        <x-primary-button>{{ __('Craft Unit') }}</x-primary-button>
                                    </form>
                                </div>
                                <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700">Inputs</p>
                                        <ul class="mt-1 text-sm text-gray-700 space-y-1">
                                            @foreach ($recipe->inputs as $input)
                                                <li>{{ $input['name'] }} x {{ $input['quantity'] }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700">Output</p>
                                        <ul class="mt-1 text-sm text-gray-700 space-y-1">
                                            <li>Name: {{ $recipe->outputs['unit']['name'] ?? 'Forged Unit' }}</li>
                                            <li>Rarity: {{ $recipe->outputs['unit']['rarity'] ?? 'common' }}</li>
                                            <li>Stats: HP {{ $recipe->outputs['unit']['stats']['hp'] ?? '?' }}, ATK {{ $recipe->outputs['unit']['stats']['attack'] ?? '?' }}, DEF {{ $recipe->outputs['unit']['stats']['defense'] ?? '?' }}, SPD {{ $recipe->outputs['unit']['stats']['speed'] ?? '?' }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-600">No unit recipes available.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

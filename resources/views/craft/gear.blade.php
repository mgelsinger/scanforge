<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gear Forge') }}
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
                    <p class="text-sm text-gray-600">Forge gear items from collected materials.</p>

                    <div class="space-y-4">
                        @forelse ($recipes as $recipe)
                            <div class="rounded border border-gray-200 p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $recipe->name }}</h3>
                                        <p class="text-sm text-gray-600">Station: Gear Forge</p>
                                    </div>
                                    <form method="POST" action="{{ route('craft.gear.craft') }}">
                                        @csrf
                                        <input type="hidden" name="recipe_id" value="{{ $recipe->id }}">
                                        <x-primary-button>{{ __('Craft Gear') }}</x-primary-button>
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
                                            <li>Name: {{ $recipe->outputs['gear']['name'] ?? 'Crafted Gear' }}</li>
                                            <li>Type: {{ $recipe->outputs['gear']['type'] ?? 'N/A' }}</li>
                                            <li>Rarity: {{ $recipe->outputs['gear']['rarity'] ?? 'common' }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-600">No gear recipes available.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

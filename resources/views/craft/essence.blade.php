<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Essence Vault') }}
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
                    <p class="text-sm text-gray-600">Infuse essence to upgrade stats on forged units.</p>

                    <div class="space-y-4">
                        @forelse ($recipes as $recipe)
                            <div class="rounded border border-gray-200 p-4 space-y-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $recipe->name }}</h3>
                                        <p class="text-sm text-gray-600">Upgrade any owned unit.</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700">Inputs</p>
                                        <ul class="mt-1 text-sm text-gray-700 space-y-1">
                                            @foreach ($recipe->inputs as $input)
                                                <li>{{ $input['name'] }} x {{ $input['quantity'] }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700">Stat Mods</p>
                                        @php($mods = $recipe->outputs['stat_mods'] ?? [])
                                        <ul class="mt-1 text-sm text-gray-700 space-y-1">
                                            <li>HP +{{ $mods['hp'] ?? 0 }}</li>
                                            <li>ATK +{{ $mods['attack'] ?? 0 }}</li>
                                            <li>DEF +{{ $mods['defense'] ?? 0 }}</li>
                                            <li>SPD +{{ $mods['speed'] ?? 0 }}</li>
                                        </ul>
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('craft.essence.upgrade') }}" class="space-y-3">
                                    @csrf
                                    <input type="hidden" name="recipe_id" value="{{ $recipe->id }}">
                                    <div>
                                        <label for="target_unit_id" class="block text-sm font-medium text-gray-700">Select Unit</label>
                                        <select id="target_unit_id" name="target_unit_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            <option value="">{{ __('Choose a unit') }}</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->name }} (HP {{ $unit->hp }}, ATK {{ $unit->attack }}, DEF {{ $unit->defense }}, SPD {{ $unit->speed }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="flex justify-end">
                                        <x-primary-button>{{ __('Upgrade Unit') }}</x-primary-button>
                                    </div>
                                </form>
                            </div>
                        @empty
                            <p class="text-sm text-gray-600">No upgrade recipes available.</p>
                        @endforelse

                        @if ($units->isEmpty())
                            <p class="text-sm text-gray-500">You have no forged units yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

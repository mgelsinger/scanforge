<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                    <p class="text-xs text-gray-500">Materials</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $status['materials'] }}</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                    <p class="text-xs text-gray-500">Blueprints</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $status['blueprints'] }}</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                    <p class="text-xs text-gray-500">Forged Units</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $status['units'] }}</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-4">
                    <p class="text-xs text-gray-500">Rating</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $status['rating'] }}</p>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900">Next Actions</h3>
                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="rounded border border-gray-200 p-4">
                        <p class="text-sm font-semibold text-gray-800">1) Scan something</p>
                        <p class="text-xs text-gray-600 mt-1">Get materials and blueprint fragments.</p>
                        <a href="{{ route('scan.index') }}" class="mt-3 inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-500">Scan</a>
                    </div>
                    <div class="rounded border border-gray-200 p-4">
                        <p class="text-sm font-semibold text-gray-800">2) Craft a unit/gear</p>
                        <p class="text-xs text-gray-600 mt-1">Use the Unit Foundry or Gear Forge.</p>
                        <a href="{{ route('craft.unit') }}" class="mt-3 inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-500">Craft</a>
                    </div>
                    <div class="rounded border border-gray-200 p-4">
                        <p class="text-sm font-semibold text-gray-800">3) Battle</p>
                        <p class="text-xs text-gray-600 mt-1">Queue a match to climb the leaderboard.</p>
                        <a href="{{ route('matches.create') }}" class="mt-3 inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-500">Battle</a>
                    </div>
                </div>
                <div class="mt-4 text-sm text-gray-700">
                    @if($status['materials'] === 0)
                        <p>Start by scanning to earn materials.</p>
                    @elseif($status['units'] === 0)
                        <p>You have materials—craft your first unit next.</p>
                    @elseif($status['matches'] === 0)
                        <p>You have a unit—queue a battle to test it!</p>
                    @else
                        <p>Keep crafting and battling to raise your rating.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

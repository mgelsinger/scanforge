<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Choose Your Starter') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('starter_error'))
                <div class="rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800">{{ session('starter_error') }}</div>
            @endif
            <p class="text-sm text-gray-700">Pick one starter to begin. You’ll get a default team with your first forged unit.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($starters as $key => $starter)
                    <div class="rounded-lg border border-gray-200 bg-white shadow-sm p-4 flex flex-col space-y-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $starter['name'] }}</h3>
                                <p class="text-xs text-gray-500">{{ $starter['rarity'] }} • {{ $starter['role'] ?? 'Starter' }}</p>
                            </div>
                            <form method="POST" action="{{ route('starter.store') }}">
                                @csrf
                                <input type="hidden" name="starter" value="{{ $key }}">
                                <x-primary-button>{{ __('Choose') }}</x-primary-button>
                            </form>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-center">
                            <div class="rounded border border-gray-100 p-2">
                                <p class="text-xs text-gray-500">HP</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $starter['stats']['hp'] }}</p>
                            </div>
                            <div class="rounded border border-gray-100 p-2">
                                <p class="text-xs text-gray-500">ATK</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $starter['stats']['attack'] }}</p>
                            </div>
                            <div class="rounded border border-gray-100 p-2">
                                <p class="text-xs text-gray-500">DEF</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $starter['stats']['defense'] }}</p>
                            </div>
                            <div class="rounded border border-gray-100 p-2">
                                <p class="text-xs text-gray-500">SPD</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $starter['stats']['speed'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>

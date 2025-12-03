<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Battle Log') }}
            </h2>
            <a href="{{ route('matches.show', $match) }}" class="text-sm text-indigo-600 hover:text-indigo-500">{{ __('Back to match') }}</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Attacker</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $match->attackerTeam->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Defender</p>
                            <p class="text-lg font-semibold text-gray-900 text-right">{{ $match->defenderTeam->name }}</p>
                        </div>
                    </div>

                    @if (!$log)
                        <p class="text-sm text-gray-500">No log available.</p>
                    @else
                        <div class="space-y-2">
                            @foreach ($log->turns as $turn)
                                <div class="rounded border border-gray-200 p-3">
                                    <p class="text-xs text-gray-500">Turn {{ $turn['turn'] }}</p>
                                    <p class="text-sm text-gray-800">
                                        <span class="font-semibold">{{ ucfirst($turn['actor_team']) }} - {{ $turn['actor_name'] }}</span>
                                        hit
                                        <span class="font-semibold">{{ ucfirst($turn['target_team']) }} - {{ $turn['target_name'] }}</span>
                                        for <span class="font-mono text-gray-900">{{ $turn['damage'] }}</span> dmg
                                        (HP {{ $turn['target_remaining_hp'] }})
                                        @if (!empty($turn['target_defeated']))
                                            <span class="ml-2 inline-flex items-center rounded bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800">Defeated</span>
                                        @endif
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Match Result') }}
            </h2>
            <a href="{{ route('matches.create') }}" class="text-sm text-indigo-600 hover:text-indigo-500">{{ __('Queue another') }}</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('match_success'))
                <div class="rounded-md bg-green-50 p-4 text-sm text-green-800">{{ session('match_success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Attacker</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $match->attackerTeam->name }} ({{ $match->attacker_rating_before }} → {{ $match->attacker_rating_after }})</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Defender</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $match->defenderTeam->name }} ({{ $match->defender_rating_before }} → {{ $match->defender_rating_after }})</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-600">Result</p>
                        <p class="text-lg font-semibold text-gray-900">
                            @if($match->winnerTeam)
                                {{ $match->winnerTeam->name === $match->attackerTeam->name ? 'You won!' : 'You lost' }}
                            @else
                                Pending
                            @endif
                        </p>
                    </div>

                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-600">Rating change</p>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $match->rating_change ?? 0 }}
                        </p>
                    </div>

                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-600">Winner</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $match->winnerTeam?->name ?? 'Pending' }}</p>
                    </div>

                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-600">Next</p>
                        <div class="space-x-2">
                            <a href="{{ route('matches.create') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-500">Queue another battle</a>
                            @if ($match->battleLog)
                                <a href="{{ route('matches.log', $match) }}" class="inline-flex items-center rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-800 hover:bg-gray-200">View battle log</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

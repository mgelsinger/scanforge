<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Queue Match') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    @if (session('match_error'))
                        <div class="rounded-md bg-red-50 p-4 text-sm text-red-800">{{ session('match_error') }}</div>
                    @endif
                    <form method="POST" action="{{ route('matches.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="attacker_team_id" class="block text-sm font-medium text-gray-700">Your Team</label>
                            <select name="attacker_team_id" id="attacker_team_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">{{ __('Select team') }}</option>
                                @foreach ($myTeams as $team)
                                    <option value="{{ $team->id }}">{{ $team->name }} (Rating {{ $team->rating }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="defender_team_id" class="block text-sm font-medium text-gray-700">Opponent Team</label>
                            <select name="defender_team_id" id="defender_team_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">{{ __('Select opponent') }}</option>
                                @foreach ($opponents as $team)
                                    <option value="{{ $team->id }}">{{ $team->name }} (Owner {{ $team->user->name ?? 'Unknown' }}, Rating {{ $team->rating }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex justify-end">
                            <x-primary-button>{{ __('Queue Match') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

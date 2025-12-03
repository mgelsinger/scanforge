<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Teams') }}
            </h2>
            <a href="{{ route('teams.create') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white shadow hover:bg-indigo-500">
                {{ __('Create Team') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('team_success'))
                <div class="rounded-md bg-green-50 p-4 text-sm text-green-800">{{ session('team_success') }}</div>
            @endif
            @if (session('team_error'))
                <div class="rounded-md bg-red-50 p-4 text-sm text-red-800">{{ session('team_error') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @forelse ($teams as $team)
                        <div class="border-b border-gray-100 py-4 last:border-b-0">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $team->name }}</h3>
                                    <p class="text-sm text-gray-600">Rating: {{ $team->rating }} • Units: {{ $team->teamUnits->count() }}</p>
                                </div>
                                <a href="{{ route('teams.edit', $team) }}" class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">Edit</a>
                            </div>
                            <div class="mt-3 grid grid-cols-1 md:grid-cols-5 gap-3">
                                @foreach ($team->teamUnits->sortBy('position') as $slot)
                                    <div class="rounded border border-gray-200 p-3">
                                        <p class="text-xs text-gray-500">Pos {{ $slot->position }}</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ $slot->forgedUnit->name }}</p>
                                        <p class="text-xs text-gray-600">HP {{ $slot->forgedUnit->hp }} | ATK {{ $slot->forgedUnit->attack }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-600">No teams created yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

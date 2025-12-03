<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Leaderboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Player</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($leaders as $index => $user)
                                    <tr>
                                        <td class="px-4 py-2 text-sm text-gray-700">#{{ $index + 1 }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900 font-medium">{{ $user->name }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900 font-mono">{{ $user->rating }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-3 text-sm text-gray-600">No leaderboard data available.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <p class="mt-3 text-xs text-gray-500">Cached for 60 seconds for performance.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

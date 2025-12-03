<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create Team') }}
            </h2>
            <a href="{{ route('teams.index') }}" class="text-sm text-indigo-600 hover:text-indigo-500">{{ __('Back to Teams') }}</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('team_error'))
                        <div class="mb-4 rounded-md bg-red-50 p-4 text-sm text-red-800">{{ session('team_error') }}</div>
                    @endif
                    <form method="POST" action="{{ route('teams.store') }}" class="space-y-6">
                        @csrf
                        @include('teams._form', ['units' => $units, 'team' => null])
                        <div class="flex justify-end">
                            <x-primary-button>{{ __('Save Team') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

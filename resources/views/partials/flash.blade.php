@php
    $messages = [
        'status' => ['type' => 'success', 'text' => session('status')],
        'success' => ['type' => 'success', 'text' => session('success')],
        'error' => ['type' => 'error', 'text' => session('error')],
        'team_success' => ['type' => 'success', 'text' => session('team_success')],
        'team_error' => ['type' => 'error', 'text' => session('team_error')],
        'craft_success' => ['type' => 'success', 'text' => session('craft_success')],
        'craft_error' => ['type' => 'error', 'text' => session('craft_error')],
        'match_success' => ['type' => 'success', 'text' => session('match_success')],
        'match_error' => ['type' => 'error', 'text' => session('match_error')],
    ];
@endphp

<div class="space-y-2">
    @foreach ($messages as $message)
        @if (!empty($message['text']))
            @php
                $isError = $message['type'] === 'error';
                $bg = $isError ? 'bg-red-50 text-red-800' : 'bg-green-50 text-green-800';
                $border = $isError ? 'border-red-200' : 'border-green-200';
            @endphp
            <div class="rounded-md border {{ $border }} px-4 py-3 text-sm {{ $bg }}">
                {{ $message['text'] }}
            </div>
        @endif
    @endforeach
</div>

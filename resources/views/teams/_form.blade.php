@php($selected = $selected ?? [])
@php($team = $team ?? null)
<div class="space-y-4">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Team Name</label>
        <input type="text" name="name" id="name" value="{{ old('name', $team->name ?? '') }}" required
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
    </div>

    <div class="space-y-3">
        <p class="text-sm text-gray-700 font-semibold">Select up to 5 units and assign positions.</p>
        @for ($i = 1; $i <= 5; $i++)
            @php
                $unitId = old("units.$loop->index.id", $selected[$i] ?? null);
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 items-end border border-gray-200 rounded p-3">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Unit Slot {{ $i }}</label>
                    <select name="units[{{ $loop->index }}][id]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">{{ __('None') }}</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ (string)$unitId === (string)$unit->id ? 'selected' : '' }}>
                                {{ $unit->name }} (HP {{ $unit->hp }}, ATK {{ $unit->attack }}, DEF {{ $unit->defense }}, SPD {{ $unit->speed }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Position</label>
                    <input type="number" name="units[{{ $loop->index }}][position]" min="1" max="5"
                           value="{{ old("units.$loop->index.position", $unitId ? $i : null) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
            </div>
        @endfor
    </div>
</div>

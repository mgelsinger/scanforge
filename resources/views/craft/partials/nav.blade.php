<div class="flex flex-wrap gap-2 mb-4">
    <a href="{{ route('craft.unit') }}"
       class="px-3 py-1 rounded-md text-sm font-medium {{ request()->routeIs('craft.unit*') ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
        Unit Foundry
    </a>
    <a href="{{ route('craft.gear') }}"
       class="px-3 py-1 rounded-md text-sm font-medium {{ request()->routeIs('craft.gear*') ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
        Gear Forge
    </a>
    <a href="{{ route('craft.essence') }}"
       class="px-3 py-1 rounded-md text-sm font-medium {{ request()->routeIs('craft.essence*') ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
        Essence Vault
    </a>
    <a href="{{ route('craft.transmuter') }}"
       class="px-3 py-1 rounded-md text-sm font-medium {{ request()->routeIs('craft.transmuter*') ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
        Essence Transmuter
    </a>
</div>

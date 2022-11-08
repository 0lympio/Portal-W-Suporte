@props(['name'])

<div class="relative" x-show="{{ $name }}" x-transition.origin.top>
    <div class="absolute top-0 z-10 w-20 p-2 -mt-1 text-sm leading-tight text-white transform -translate-x-1/2 -translate-y-full bg-gray-400 rounded-lg shadow-lg">
        {{ $slot }}
    </div>
    <svg class="absolute z-10 w-6 h-6 text-gray-400 transform -translate-x-12 -translate-y-3 fill-current stroke-current" width="8" height="8">
        <rect x="14" y="-13" width="8" height="8" transform="rotate(45)" />
    </svg>
</div>

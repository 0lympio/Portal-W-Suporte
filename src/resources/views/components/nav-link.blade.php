@props(['active'])

@php
$classes = 'w-full relative inline-flex items-center px-4 py-2 text-gray-700 before:absolute before:bottom-0 before:left-0 before:border-2 ';
$classes .= $active ?? false ? 'bg-gray-100  before:h-full before:border-red-semparar ' : 'hover:bg-gray-100 hover:before:h-full hover:before:border-red-semparar before:h-1 transition-all duration-300 ease-in before:border-transparent ';
@endphp
<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

@props(['value', 'display' => 'block'])

<label {{ $attributes->merge(['class' => $display . ' font-medium text-sm text-gray-700']) }}>
    {{ $value ?? $slot }}
</label>

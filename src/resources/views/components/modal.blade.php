@props(['name', 'clickoutside' => true])

<div x-show="{{ $name }}" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
    <div @click.outside="{{ $clickoutside }} ? {{ $name }} = false : null" x-show="{{ $name }}"
        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-300"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
        {{ $attributes->merge(['class' => 'relative top-20 mx-auto p-2 border shadow-lg rounded-md bg-white ']) }}>
        <div class="mt-2">
            <div class="flex w-full justify-end text-2xl px-2">
                <button @click="{{ $name }} = false;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            @isset($title)
                <div class="w-full flex justify-center">
                    <h1 class="text-xl text-gray-semparar">{{ $title }}</h1>
                </div>
            @endisset
            {{ $slot }}
        </div>
    </div>
</div>

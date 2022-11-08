<div {{ $attributes->merge(['class' => 'mb-4']) }}>
    @if (isset($title))
        <h1 class="text-2xl text-gray-semparar">{{ $title }}</h1>
    @endif
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            class="p-3 rounded bg-green-500 text-green-100 my-2">
            {{ session('message') }}
        </div>
    @endif

    @if (isset($errors) && $errors->any())
        @foreach ($errors->all() as $error)
            <div class="p-3 rounded bg-red-400 text-red-100 my-2">
                {{ $error }}
            </div>
        @endforeach
    @endif

    {{ $buttons ?? '' }}
</div>

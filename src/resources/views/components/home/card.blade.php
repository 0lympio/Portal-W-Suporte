@props(['link', 'image'])
<a class="flex pt-0 pr-0 xl:pt-5 p-4 lg:h-32 xl:h-40 w-full" href="{{ $link }}">
    <img class="object-fill  min-w-[9rem] max-w-[9rem] h-32 hover:scale-105 transition-all duration-150 ease-out hover:ease-in shadow object-fit z-50 "
        src="{{ $image }}" alt="Imagem de Fique ON" />
    <div class="flex flex-col justify-start w-full h-32 bg-white relative">
        <span class="ml-2 mt-2 text-gray-900 xl:text-xl text-md font-medium lg:text-sm">{{ $title }}</span>
        {{ $slot }}
    </div>
</a>

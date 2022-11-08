<x-modal name="modalErrorSize" class="w-1/3">
    <div class="items-center text-center" @click.outside="location.reload();">
        <div class="text-4xl text-gray-500 my-3">
            <i class="fa-solid fa-file-circle-exclamation"></i>
        </div>

        <p class="mb-4">{{ $slot }}</p>

        <div class="flex justify-end mb-2">
            <x-button class="text-white bg-red-500 hover:bg-red-500" type="button" @click="location.reload();">OK</x-button>
        </div>
    </div>
</x-modal>

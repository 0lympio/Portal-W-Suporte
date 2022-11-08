<x-modal name="deleteModal" class="w-2/4 xl:w-1/3">
    <x-slot name="title">{{ $slot }}</x-slot>
    <div class="items-center px-4 py-3">
        <form id="form-delete" method="POST" :action="deleteUrl">
            <x-input type="hidden" name="_method" value="DELETE"></x-input>
            <x-input type="hidden" name="_token" value="{{ csrf_token() }}"></x-input>
            <div class="flex flex-row justify-end">
                <x-button @click="deleteModal = false;" type="button" class="button-red">
                    Cancelar
                </x-button>
                <x-button type="submit" class="button-green ml-2">
                    Confirmar
                </x-button>
            </div>
        </form>
    </div>
</x-modal>

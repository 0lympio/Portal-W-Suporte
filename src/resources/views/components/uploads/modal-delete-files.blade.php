@props(['clickoutside'])

<x-modal name="modalDeleteFiles" class="w-2/5 max-h-96" :clickoutside="$clickoutside">
    <div class="items-center text-center">
        <x-slot name="title"><span x-text="`Tem certeza que deseja excluir ${selected.length} arquivo${selected.length > 1 ? 's' : ''}?`"></span></x-slot>

        <div class="items-center px-4 py-3 text-center">
            <div class="flex flex-row justify-end">
                <x-button @click="modalDeleteFiles = false;" type="button" class="button-red">
                    Cancelar
                </x-button>
                <x-button type="button" class="button-green ml-2" @click="deleteFiles()">
                    Confirmar
                </x-button>
            </div>
        </div>
    </div>
</x-modal>

@props(['clickoutside'])

<x-modal name="modalWidthAndHeightPdf" class="w-96" :clickoutside="$clickoutside">
    <div class="items-center text-center">
        <x-slot name="title">Escolha a largura e a altura para o pdf</x-slot>
        <div class="items-center px-4 py-3 text-center">
            <div class="ml-2">
                <x-label for="width" value="Largura" />
                <x-input id="width" class="input-form" type="number" min="0" x-model="width" name="width" />
            </div>
            <div class="ml-2">
                <x-label for="height" value="Altura" />
                <x-input id="height" class="input-form" type="number" min="0" x-model="height" name="height" />
            </div>

            <div class="flex flex-row justify-end mt-3">
                <x-button @click="modalWidthAndHeightPdf = false;" type="button" class="button-red">
                    Cancelar
                </x-button>
                <x-button type="button" class="button-green ml-2" @click="copyLink(selectedFile); modalWidthAndHeightPdf = false;">
                    Copiar
                </x-button>
            </div>
        </div>
    </div>
</x-modal>

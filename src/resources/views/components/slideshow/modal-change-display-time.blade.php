<x-modal name="modalChangeDisplayTime" class="w-1/3 xl:w-1/4">
    <x-slot name="title">Alterar tempo de exibição</x-slot>
    <div class="items-center px-4 text-center">
        <form method="POST" action="{{ route('slideshow.displayTime') }}">
            @csrf
            @method('PUT')
            <div class="mt-4 mb-4">
                <x-label for="displayTime" value="Novo tempo de exibição" />
                <x-input id="displayTime" class="input-form" type="number" min="0" name="displayTime" required />
            </div>
            <div class="flex flex-row justify-end mb-2">
                <x-button
                    @click="modalChangeDisplayTime = false;"
                    type="button"
                    class="button-red">
                    Cancelar
                </x-button>
                <x-button
                    type="submit"
                    class="ml-2 button-green">
                    Confirmar
                </x-button>
            </div>
        </form>
    </div>
</x-modal>

<x-modal name="modalAddQuiz" class="w-1/3">
    <x-slot name="title">Adicionar enquete</x-slot>
    <div class="items-center px-4 text-center">
        <form method="POST" action="{{ route('questionnaires.store') }}">
            @csrf

            <div class="mt-4 mb-4">
                <x-label for="name" value="Nome da enquete" />
                <x-input id="name" class="input-form" type="text" name="name" required />
            </div>

            <div class="flex justify-end mb-3">
                <x-button @click="modalAddQuiz = false;" type="button" class="button-red">
                    Cancelar
                </x-button>
                <x-button class="button-green ml-2">Confirmar</x-button>
            </div>
        </form>
    </div>
</x-modal>

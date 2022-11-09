@props(['clickoutside'])

<x-modal name="createModal" class="w-1/4 xl:w-1/5" :clickoutside="$clickoutside">
    <x-slot name="title">Criar assessoria</x-slot>
    <div class="flex justify-center">
        <div class="sm:max-w-md mb-3 py-4 sm:rounded-sm p-3 flex justify-center">
            <form method="POST" action="{{ route('companies.store') }}">
                @csrf

                <div class="mt-4">
                    <x-label for="name" value="Nome" />
                    <x-input class="input-form" type="text" name="name" required maxlength="255" />
                </div>
                <div class="mt-4">
                    <x-label for="description" value="Descrição (opcional)" />
                    <x-input class="input-form" type="text" name="description" maxlength="255" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-button type="button" class="button-red" @click="createModal=false;">
                        Cancelar
                    </x-button>
                    <x-button class="ml-2 button-green">
                        Confirmar
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-modal>

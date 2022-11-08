@props(['clickoutside'])

<x-modal name="passwordModal" class="w-1/3 xl:w-1/4" :clickoutside="$clickoutside">
    <x-slot name="title">Alterar senha</x-slot>
    <div class="items-center px-4 py-3 text-center">
        <form method="POST" :action="'users/' + user.id + '/changePassword'">
            @csrf
            @method('PUT')
            <div class="mt-4 mb-4">
                <x-label for="password" value="Nova senha para o usuário" />
                <x-input id="password" class="input-form" type="password" name="password" required autofocus maxlength="255" />
            </div>
            <div class="flex flex-row justify-end">
                <x-button class="button-red" @click="passwordModal = false;" type="button">
                    Cancelar
                </x-button>
                <x-button class="text-white button-green ml-2" id="send-button" type="submit">
                    Confirmar
                </x-button>
            </div>
        </form>
    </div>
</x-modal>

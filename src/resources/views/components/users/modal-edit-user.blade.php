@props([
    'roles',
    'companies',
    'clickoutside'
])

<x-modal name="editModal" class="w-1/3 mb-24" :clickoutside="$clickoutside">
    <x-slot name="title">Editar usuário</x-slot>
    <div class="flex justify-center">
        <div class="sm:max-w-md mb-3 py-4 sm:rounded-sm p-3 flex justify-center">
            <form method="POST" :action="'users/' + user.id">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div class="mt-4">
                        <x-label for="name" value="Nome" />
                        <x-input id="name" class="input-form" type="text" name="name" required maxlength="255"
                            x-model="user.name" @keydown="generateLogin()" />
                    </div>

                    <div class="mt-4">
                        <x-label for="last_name" value="Sobrenome" />
                        <x-input id="last_name" class="input-form" type="text" name="last_name" maxlength="255"
                            x-model="user.last_name" @keydown="generateLogin()" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="mt-4">
                        <x-label for="email" value="Email" />
                        <x-input class="input-form" type="email" name="email" :value="old('email')" maxlength="255"
                            x-model="user.email" />
                    </div>

                    <div class="mt-4">
                        <x-label for="company" value="Assessoria" />
                        <select name="company_id" class="input-form select-form" required @change="generateLogin()"
                            x-model="user.company_id">
                            <option value="">Assessoria</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="mt-4">
                        <x-label for="position" value="Cargo" />
                        <x-input class="input-form" type="text" name="position" maxlength="255" x-model="user.position" />
                    </div>

                    <div class="mt-4">
                        <x-label for="segment" value="Segmento" />
                        <x-input class="input-form" type="text" name="segment" x-model="user.segment" />
                    </div>
                </div>

                <div class="mt-4">
                    <x-label for="role" value="Função" />
                    <select name="role" class="input-form select-form" required x-model="user.roles[0].id">
                        <option value="">Função</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    <div class="mt-4">
                        <x-label for="username" value="Login" />
                        <x-input x-model="user.username" id="username" disabled class="bg-gray-100 input-form"
                            type="text" name="username" required autofocus maxlength="255" />
                    </div>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-button type="button" class="button-red" @click="editModal=false;user=''">Cancelar</x-button>
                    <x-button class="ml-2 button-green">
                        Confirmar
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-modal>

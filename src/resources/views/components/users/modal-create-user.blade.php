@props([
    'roles',
    'companies',
    'clickoutside'
])

<x-modal name="createModal" class="w-1/3 mb-24" :clickoutside="$clickoutside">
    <x-slot name="title">Criar usuário</x-slot>
    <div class="flex justify-center">
        <div class="sm:max-w-md mb-3 py-4 sm:rounded-sm p-3 flex justify-center">
            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div class="mt-4">
                        <x-label for="name" value="Nome" />
                        <x-input x-model="user.name" class="input-form" type="text" name="name" required
                            maxlength="255" @keydown="generateLogin()" />
                    </div>

                    <div class="mt-4">
                        <x-label for="last_name" value="Sobrenome" />
                        <x-input x-model="user.last_name" class="input-form" type="text" name="last_name"
                            @keydown="generateLogin()" maxlength="255" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="mt-4">
                        <x-label for="email" value="Email (opcional)" />
                        <x-input class="input-form" type="email" name="email" maxlength="255" />
                    </div>

                    <div class="mt-4">
                        <x-label for="password" value="Senha" />
                        <x-input class="input-form" type="password" name="password" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="mt-4">
                        <x-label for="position" value="Cargo" />
                        <x-input class="input-form" type="text" name="position" maxlength="255" />
                    </div>

                    <div class="mt-4">
                        <x-label for="segment" value="Segmento" />
                        <x-input class="input-form" type="text" name="segment" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="mt-4">
                        <x-label for="role" value="Função" />
                        <select name="role" class="input-form select-form" required>
                            <option value="">Função</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach

                        </select>
                    </div>

                    <div class="mt-4">
                        <x-label for="company" value="Assessoria" />
                        <select name="company_id" x-model="user.company_id" @change="generateLogin()"
                            class="input-form select-form" required>
                            <option value="">Assessoria</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <x-label for="username" value="Login" />
                    <x-input class="bg-gray-100 input-form" type="text" x-model="user.username" readonly="readonly"
                        name="username" required autofocus maxlength="255" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-button type="button" class="button-red" @click="createModal=false;">
                        Cancelar
                    </x-button>
                    <x-button class="ml-2 button-green">
                        Registrar
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-modal>

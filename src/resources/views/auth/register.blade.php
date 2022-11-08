<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">

            {{-- Validation Errors --}}
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div class="mt-4">
                        <x-label for="username" value="Nome de usuário" />
                        <x-input id="username" class="input-form" type="text" name="username"
                            required autofocus maxlength="255" />
                    </div>

                    <div class="mt-4">
                        <x-label for="registration_id" value="Matrícula" />
                        <x-input id="registration_id" class="input-form" type="text" name="registration_id"
                            required autofocus maxlength="255" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="mt-4">
                        <x-label for="name" value="Nome" />
                        <x-input id="name" class="input-form" type="text" name="name"
                            required maxlength="255" />
                    </div>

                    <div class="mt-4">
                        <x-label for="last_name" value="Sobrenome" />
                        <x-input id="last_name" class="input-form" type="text" name="last_name"
                            maxlength="255" required />
                    </div>
                </div>

                <div class="mt-4">
                    <x-label for="email" value="Email" />
                    <x-input id="email" class="input-form" type="email" name="email"
                        maxlength="255" />
                </div>

                <div class="mt-4">
                    <x-label for="password" value="Senha" />
                    <x-input id="password" class="input-form" type="password" name="password" required
                        autocomplete="new-password" />
                </div>

                <div class="mt-4">
                    <x-label for="role_id" value="Função" />
                    <select name="role_id" id="role_id"
                        class="input-form select-form"
                        required>
                        <option value=""> </option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach

                    </select>
                </div>

                <div class="mt-4">
                    <x-label for="company_id" value="Assessoria" />
                    <select name="company_id" id="company_id"
                        class="input-form select-form"
                        required>

                        <option value=""> </option>

                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach

                    </select>
                </div>

                <div class="flex items-center justify-center mt-4">
                    <a href="{{ route('users.index') }}">
                        <x-button type="button" class="bg-gray-semparar hover:bg-gray-500">Cancelar</x-button>
                    </a>
                    <x-button class="ml-6">
                        Registrar
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>

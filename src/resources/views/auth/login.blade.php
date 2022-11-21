<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <img src="{{ asset('images/logo123.png') }}" alt="Logo W">
        </x-slot>
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('auth.login') }}">
            @csrf
            <!-- Email Address -->
            <div>
                <x-label for="username" :value="__('Nome de usuário')" />
                <x-input id="username" class="input-form" type="text" name="username" required autofocus />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Senha')" />

                <div class="relative">
                    <x-input id="password" class="input-form" type="password" name="password" required
                        autocomplete="current-password" />

                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-2xl cursor-pointer text-gray-700"
                        onclick="toggle()">
                        <div id="eye">
                            <i class="fa-regular fa-eye"></i>
                        </div>
                        <div class="hidden" id="eye-slash">
                            <i class="fa-regular fa-eye-slash"></i>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Remember Me -->
            <div class="flex justify-between mt-4">
                <x-label for="remember_me" class="inline-flex items-center">
                    <x-input id="remember_me" type="checkbox"
                        class="rounded border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        name="remember" />
                    <span class="ml-2 text-sm text-blue-800">{{ __('Lembrar-me') }}</span>
                </x-label>
                <a href="#" class="text-blue-800" onclick="handleModal(true)">Esqueci minha senha</a>
            </div>
            <div class="flex items-center justify-center mt-4">
                <x-button class="ml-2 button-blue">
                    {{ __('Acessar') }}
                </x-button>
            </div>
            </div>
        </form>
    </x-auth-card>

    <!-- Modal para redefinição de senha [INÍCIO]-->
    <div class="relative z-10 hidden" id="provideHelp" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Contate um administrador</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">Para ter acesso ao portal novamente, solicite uma
                                        nova senha de acesso ao administrador.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button"
                            class="inline-flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
                            onclick="handleModal(false)">Ok</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para redefinição de senha [FIM]-->

    <script>
        function toggle() {
            let eye = document.getElementById('eye');
            let eyeSlash = document.getElementById('eye-slash');
            let password = document.getElementById('password');

            let show = eye.classList.toggle('hidden');
            eyeSlash.classList.toggle('hidden');

            show ? password.type = 'text' : password.type = 'password';
        }
        function handleModal(param) { //Função par'ativar e desativar o modal de redefinição de senha
            let modal = document.getElementById('provideHelp');

            if(param) modal.classList.remove('hidden');
            else modal.classList.add('hidden');
        }
    </script>
</x-guest-layout>

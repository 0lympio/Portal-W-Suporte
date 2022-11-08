<x-app-layout>
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-gray-semparar">Criar novo perfil</h1>
        @if (session()->has('message'))
            <div class="p-3 rounded bg-green-500 text-green-100 my-2">
                {{ session('message') }}
            </div>
        @endif

        @if (isset($errors) && $errors->any())
            @foreach ($errors->all() as $error)
                <div class="p-3 rounded bg-red-400 text-red-100 my-2">
                    {{ $error }}
                </div>
            @endforeach
        @endif
    </div>
    <div class="flex w-full" x-data="accordion({{ collect($permissions) }})" x-init="initData()">
        <form class="bg-white py-4 w-full px-3" method="POST" action="{{ route('roles.store') }}">
            @csrf

            <div class="mt-4">
                <x-label for="name" value="Nome para o perfil" />
                <x-input id="name" class="input-form" type="text" name="name" required autofocus
                    maxlength="255" />
            </div>

            <div class="mt-4 mb-4">
                <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                    <input type="checkbox" name="all_permission"
                        class="permission toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />
                    <span for="all_permission"
                        class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></span>
                </div>
                <label for="toggle" class="text-xs text-gray-700"><span>Selecionar todas permissões</span></label>
            </div>

            <div class="w-full border border-gray-200">
                <ul class="shadow-box">
                    <template x-for="(item, index) in items">
                        <li class="relative border-b border-gray-200">
                            <button type="button" class="w-full px-8 py-6 text-left border-b-2"
                                @click="selected !== index ? selected = index : selected = null">
                                <div class="flex items-center pl-3">
                                    <span class="ml-3 capitalize" x-text="$t(item.name)"></span>
                                </div>
                            </button>
                            <div x-show.transition.in.duration.800ms="selected == index"
                                class="w-full bg-gray-50 transition-all duration-700 ease-in pl-0 py-6" style=""
                                x-ref="container1" :style="">

                                <template x-if="item.name === 'categories'">
                                    <div>
                                        <p class="mb-2 ml-4">Categorias habilitadas:</p>

                                        <template x-for="(category, index) in categories">
                                            <div class="m-6 justify-center">
                                                <div
                                                    class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                                    <input type="checkbox" :name="'categories[' + category.slug + ']'" :id="category.name"
                                                        class="permission input-checkbox-switch" />
                                                    <label :for="category.name"
                                                        class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                                                </div>
                                                <label for="toggle" class="capitalize text-xs text-gray-700"><span
                                                        x-text="category.name"></span></label>
                                            </div>
                                        </template>

                                        <p class="ml-4">Permissões:</p>
                                    </div>
                                </template>

                                <div class="p-0 flow-root">
                                    <template x-for="(permission, index) in item.data">
                                        <div class="flex items-center m-6 justify-center float-left">
                                            <div
                                                class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                                <input type="checkbox" :name="'permission[' + permission.id + ']'"
                                                    :id="permission.name" class="permission input-checkbox-switch" />
                                                <label :for="permission.name"
                                                    class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                                            </div>
                                            <label for="toggle" class="capitalize text-xs text-gray-700"><span
                                                    x-text="$t(permission.name)"></span></label>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </li>
                    </template>
                </ul>
            </div>

            <div class="flex items-center justify-center mt-4">
                <a href="{{ route('roles.index') }}">
                    <x-button type="button" class="button-red">Cancelar</x-button>
                </a>
                <x-button class="ml-2 button-green">
                    Registrar
                </x-button>
            </div>
        </form>
    </div>
    <script type="text/javascript">
        window.accordion = function(data) {
            return {
                items: [],
                selected: null,
                categories: @json($categories),

                initData() {
                    let arr = [];
                    let index = 0;
                    data.map((permission) => {
                        let name = permission.name.split('.');
                        if (arr[index] === undefined) {
                            arr[index] = {
                                name: name[0],
                                data: [{
                                    id: permission.id,
                                    name: name[1] || permission.name
                                }]
                            }
                        } else {
                            if (arr[index].name === name[0]) {
                                arr[index].data.push({
                                    id: permission.id,
                                    name: name[1] || permission.name
                                })
                            } else {
                                index += 1;
                                arr[index] = {
                                    name: name[0],
                                    data: [{
                                        id: permission.id,
                                        name: name[1] || permission.name
                                    }]
                                }
                            }
                        }
                    })
                    this.items = arr;
                }
            }
        }
        $(document).ready(function() {
            $('[name="all_permission"]').on('click', function() {
                if ($(this).is(':checked')) {
                    $.each($('.permission'), function() {
                        $(this).prop('checked', true);
                    });
                } else {
                    $.each($('.permission'), function() {
                        $(this).prop('checked', false);
                    });
                }
            });

        });
    </script>
</x-app-layout>

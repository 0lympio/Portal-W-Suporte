@props(['categories', 'clickoutside'])
<x-modal name="createModal" class="w-1/4 xl:w-1/5" :clickoutside="$clickoutside">
    <div class="mt-3">
        <x-slot name="title">Criar categoria</x-slot>
        <div class="flex justify-center">
            <div class="sm:max-w-md mb-3 py-4 sm:rounded-sm p-3 flex justify-center">
                <form method="POST" action="{{ route('categories.store') }}">
                    @csrf
                    <div class="grid grid-cols-1 gap-4">
                        <div class="mt-4">
                            <x-label for="name" value="Nome da categoria/sub-categoria" />
                            <x-input id="name" class="input-form" type="text" name="name" required autofocus
                                maxlength="255" />
                        </div>
                    </div>

                    <div x-data="{ open: '' }" x-cloak>
                        <div class="grid grid-cols-1 gap-4">
                            <div class="mt-4">
                                <x-label for="category_id" value="Sub-categoria de" />
                                <select name="category_id" id="category_id" class="input-form select-form"
                                    x-model="open">
                                    <option value=""></option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div x-show="open === ''">
                            <div class="grid grid-cols-1 gap-4">
                                <div class="mt-4">
                                    <x-label for="icon" value="Selecione o icone da categoria" />
                                    <select name="icon" id="icon" class="input-form select-form fa">
                                        <option value='<i class="fa-regular fa-file-lines"></i>'>&#xf15c;</option>
                                        <option value='<i class="fa-regular fa-bookmark"></i>'>&#xf02e;</option>
                                        <option value='<i class="fa-regular fa-user"></i>'>&#xf007;</option>
                                        <option value='<i class="fa-regular fa-clipboard"></i>'>&#xf328;</option>
                                        <option value='<i class="fa-regular fa-envelope"></i>'>&#xf0e0;</option>
                                        <option value='<i class="fa-regular fa-comments"></i>'>&#xf086;</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 mb-2">
                            <div class="mt-4">
                                <x-label for="menu" class="mr-2" display="inline-flex">
                                    Ir para o menu lateral?
                                </x-label>
                                <x-input name="isMenu" id="menu" type="checkbox" checked
                                    class="focus:ring-red-semparar h-4 w-4 text-red-semparar border-gray-300 rounded">
                                </x-input>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-center mt-4">
                        <x-button type="button" class="button-red" @click="createModal=false;">Cancelar</x-button>
                        <x-button class="ml-2 button-green">
                            Registrar
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-modal>

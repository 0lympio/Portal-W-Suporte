@props(['categories', 'clickoutside'])
<x-modal name="editModal" class="w-1/4 xl:w-1/5" :clickoutside="$clickoutside">
    <x-slot name="title">Editar categoria</x-slot>
    <div class="flex justify-center">
        <div class="sm:max-w-md mb-3 py-4 sm:rounded-sm p-3 flex justify-center">
            <form method="POST" :action="'categories/' + category.id">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-4">
                    <div class="mt-4">
                        <x-label for="name" value="Nome da categoria" />
                        <x-input id="name" x-model="category.name" class="input-form" type="text" name="name"
                            required autofocus maxlength="255" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    <div class="mt-4">
                        <x-label for="category_id" value="Categoria pertecente" />
                        <select name="category_id" x-model="category.category_id" id="category_id"
                            class="input-form select-form">
                            <option value=""></option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div x-show="category.category_id === null || category.category_id === ''">
                    <div class="grid grid-cols-1 gap-4">
                        <div class="mt-4">
                            <x-label for="icon" value="Selecione o icone da categoria" />
                            <select name="icon" id="icon" class="input-form select-form fa"
                                x-model="category.icon">
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
                        <input name="isMenu" id="menu"type="checkbox" x-model="category.isMenu"
                            :checked="category.isMenu == 1"
                            class="focus:ring-red-semparar h-4 w-4 text-red-semparar border-gray-300 rounded">
                    </div>
                </div>

                <div class="flex items-center justify-center mt-4">
                    <x-button type="button" class="button-red" @click="editModal=false;">Cancelar
                    </x-button>
                    <x-button class="ml-2 button-green">
                        Registrar
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-modal>

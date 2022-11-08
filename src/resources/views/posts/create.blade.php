<x-app-layout>
    <div x-data="init()" x-cloak>
        <form method="POST" action="{{ route('posts.store') }}">
            @csrf

            <div class="mx-4 pb-2 ">
                <div class="flex items-center">
                    <div class="flex items-center text-red-semparar relative">
                        <div @click="step = 1"
                            class="rounded-full transition duration-500 ease-in-out h-12 w-12 px-3.5 py-3 border-2 "
                            :class="step === 1 ? 'button-red' : step > 1 ? 'text-red' : ''">
                            <i class="fa fa-cog"></i>
                        </div>
                        <div @click="step = 1"
                            class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase text-red-semparar">
                            Configurações
                        </div>
                    </div>
                    <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-gray-300"
                        :class="{ 'border-red-semparar': step >= 2 }">
                    </div>
                    <div class="flex items-center relative">
                        <div @click="step = 2"
                            class="cursor-pointer text-gray-500 rounded-full transition duration-500 ease-in-out h-12 w-12 px-3.5 py-3 border-2  border-gray-300"
                            :class="step === 2 ? 'button-red' : step > 2 ? 'text-red' : ''">
                            <i class="fa fa-file-pen"></i>
                        </div>
                        <div
                            class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase text-red-semparar">
                            Detalhes
                        </div>
                    </div>
                    <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-gray-300"
                        :class="{ 'border-red-semparar': step === 3 }">
                    </div>
                    <div class="flex items-center text-gray-500 relative">
                        <div @click="step = 3"
                            class="rounded-full transition duration-500 ease-in-out h-12 w-12 px-3.5 p-3 border-2 border-gray-300"
                            :class="{ 'button-red': step === 3 }">
                            <i class="fa fa-pen"></i>
                        </div>
                        <div
                            class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase text-gray-500">
                            Conteúdo
                        </div>
                    </div>
                </div>
            </div>
            <!-- Conteúdo dos steps -->
            <div class="mt-8" :class="step !== 3 ? 'bg-white rounded p-4' : 'py-4'">
                <!-- Step 1 - Configurações -->
                <div x-show="step === 1">
                    <div class="font-bold text-gray-600 text-xs leading-8 uppercase h-6 mx-2 mt-3 mb-2">
                        Tipo do conteúdo
                    </div>
                    <div class="flex flex-col md:flex-row">
                        <div class="w-full flex-1 mx-2">
                            <x-posts.categories :categories="$categories"></x-posts.categories>
                        </div>
                    </div>

                    <input type="hidden" name="extras[type]" :value="category">

                    <template x-if="category !== null">
                        <div class="flex flex-col md:flex-row mt-2">
                            <div class="w-full flex-1 mx-2">
                                <div class="mt-2 items-center">
                                    <x-label for="popup" class="mr-2" display="inline-flex">
                                        Gerar pop-up?
                                    </x-label>
                                    <x-input name="popup" id="popup" type="checkbox"
                                        class="focus:ring-red-semparar h-4 w-4 text-red-semparar border-gray-300 rounded">
                                    </x-input>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template x-if="category === 'Treinamentos'">
                        <div class="flex flex-col md:flex-row mt-2 ml-2">

                            <div x-data="{ open: false }" class="mt-3">
                                <div class="mt-2 items-center">
                                    <x-label class="mr-2" display="inline-flex">
                                        Deseja associar a alguma enquete?
                                    </x-label>
                                    <x-input name="popup" id="popup" type="checkbox" @click="open = ! open"
                                        class="focus:ring-red-semparar h-4 w-4 text-red-semparar border-gray-300 rounded">
                                    </x-input>
                                </div>
                                <div x-show="open">
                                    <div class="grid grid-cols-1">
                                        <div class="mt-4 w-11/12">
                                            <x-label for="questionnaire_id" value="Enquete" />
                                            <select name="extras[questionnaire_id]" id="questionnaire_id"
                                                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <option value=""></option>
                                                @foreach ($questionnaires->where('associate', 1) as $questionnaire)
                                                    <option value="{{ $questionnaire->id }}">{{ $questionnaire->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mt-4 w-11/12">
                                            <x-label for="goal" value="Porcentagem para passar (0 a 100):">
                                            </x-label>
                                            <x-input id="goal" class="block mt-1 w-full" type="number"
                                                min="0" max="100" name="extras[goal]" autofocus
                                                maxlength="255" />
                                        </div>
                                        <div class="mt-4 w-11/12">
                                            <x-label for="tries" value="Tentativas:"></x-label>
                                            <x-input id="tries" class="block mt-1 w-full" type="number"
                                                name="extras[tries]" autofocus maxlength="255" />
                                        </div>
                                        <div class="mt-4 w-11/12">
                                            <x-label for="expire_time" value="Duração do teste (minutos):"></x-label>
                                            <x-input id="expire_time" class="block mt-1 w-full" type="number"
                                                name="extras[expire_time]" autofocus maxlength="255" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template x-if="category !== null && category !== 'Fique ON'">
                        <div class="flex flex-col md:flex-row mt-2">
                            <div class="w-full flex-1 mx-2">
                                <div class="mt-2 items-center">
                                    <x-label for="menu" class="mr-2" display="inline-flex">
                                        Ir para o menu lateral?
                                    </x-label>
                                    <x-input name="isMenu" id="menu" type="checkbox" checked
                                        class="focus:ring-red-semparar h-4 w-4 text-red-semparar border-gray-300 rounded">
                                    </x-input>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <!-- Step 2 - Detalhes -->
                <div x-show="step === 2">
                    <div class="flex flex-col md:flex-row">
                        <div class="w-full mx-2 flex-1">
                            <x-label for="title" value="Título"></x-label>
                            <x-input class="input-form placeholder:text-gray-400 placeholder:text-right"
                                type="text" id="title" name="title" required maxlength="255"
                                placeholder="255" value="{{ old('title') }}" />
                        </div>
                    </div>
                    <template x-if="category !== 'Procedimentos'">
                        <div class="flex flex-col md:flex-row mt-3">
                            <div class="w-full mx-2 flex-1">
                                <x-label for="description" value="Descrição (opcional)"></x-label>
                                <x-input class="input-form placeholder:text-gray-400 placeholder:text-right"
                                    type="text" id="description" name="description" maxlength="255"
                                    placeholder="255" value="{{ old('description') }}" />
                            </div>
                        </div>
                    </template>
                    <div class="flex flex-col md:flex-row mt-3 w-1/2">
                        <div class="w-full mx-2 flex-1">
                            <x-label for="published_at" value="Data de agendamento"></x-label>
                            <x-input class="input-form placeholder:text-gray-400 placeholder:text-right"
                                type="datetime-local" id="published_at" name="published_at" required maxlength="255"
                                value="{{ now('America/Sao_Paulo')->format('Y-m-d H:i') }}"
                                min="{{ now('America/Sao_Paulo')->format('Y-m-d H:i') }}" />
                        </div>
                    </div>
                    <div x-data="{ open: false }" class="mt-3">
                        <div>
                            <x-label class="mr-2" display="inline-flex" class="w-full mx-2 flex-1">
                                Adicionar data de Término da postagem?
                            </x-label>
                            <x-input name="popup" id="popup" type="checkbox" @click="open = ! open"
                                class="focus:ring-red-semparar h-4 w-4 text-red-semparar border-gray-300 rounded mx-2 flex-1">
                            </x-input>
                        </div>

                        <div x-show="open">
                            <div class="flex flex-col md:flex-row mt-3 w-1/2">
                                <div class="w-full mx-2 flex-1">
                                    <x-label for="disabled_at" value="Data de Término"></x-label>
                                    <x-input class="input-form placeholder:text-gray-400 placeholder:text-right"
                                        type="datetime-local" id="disabled_at" name="disabled_at" maxlength="255"
                                        min="{{ now('America/Sao_Paulo')->format('Y-m-d H:i') }}" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <template x-if="category !== 'Fique ON'">
                        <div class="flex flex-col md:flex-row mt-3">
                            <div class="w-full mx-2 flex-1">
                                <x-label for="thumbnail" value="Imagem do card"></x-label>
                                <div class="relative w-[30rem] h-[14rem]">
                                    <img class="object-cover w-full h-full rounded-lg"
                                        :src="thumbnail === null ? 'https://placehold.co/900x900/e2e8f0/e2e8f0' :
                                            '../storage/' +
                                            thumbnail" />
                                    <input type="hidden" name="thumbnail" :value="thumbnail">
                                    <div class="absolute cursor-pointer top-[36%] left-[43%]"
                                        @click="modalSetThumbnail = true">
                                        <button type="button" class="text-6xl text-gray-700 hover:text-black">
                                            <i class="fa-regular fa-image"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <!-- Step 3 - Conteúdo -->
                <div x-show="step === 3">
                    <x-tiny-mce.editor />
                    <x-tiny-mce.config />
                </div>
                <div class="flex p-2 mt-4">
                    <x-button @click="step -= 1" type="button"
                        class="hover:bg-gray-200 bg-gray-100 border-2 border-gray-semparar mr-2 text-gray-700"
                        x-show="step > 1">
                        Anterior
                    </x-button>
                    <x-button type="button" class="button-red">
                        <a href="{{ route('posts.index') }}">Cancelar</a>
                    </x-button>
                    <div class="flex-auto flex flex-row-reverse">
                        <x-button class="button-blue" @click="step += 1" x-show="step < 3" type="button">
                            Próximo
                        </x-button>
                        <x-button type="submit" class="button-green" x-show="step ===  3">
                            Pré-visualização
                        </x-button>
                    </div>
                </div>
            </div>
        </form>

        <x-posts.modal-set-thumbnail></x-posts.modal-set-thumbnail>
    </div>

    <script>
        function init() {
            return {
                step: 1,
                category: null,
                thumbnail: null,
                modalSetThumbnail: false,
                images: @json($images),
                videos: @json($videos),
                selectedImageInformation: null,
                selectedImage: null,
                icon: null,
                post: {},
            }
        }
    </script>
</x-app-layout>

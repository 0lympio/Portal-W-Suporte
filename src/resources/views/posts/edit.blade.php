<x-app-layout>
    <div x-data="init()" x-cloak>
        <form method="POST" action="{{ route('posts.update', $post->id) }}">
            @csrf
            @method('PUT')
            <div class="mx-4 pb-2 ">
                <div class="flex items-center">
                    <div class="flex items-center text-red-semparar relative">
                        <div @click="step = 1"
                            class="rounded-full transition duration-500 ease-in-out h-12 w-12 px-3.5 py-3 border-2 "
                            :class="step === 1 ? 'bg-red-semparar text-white border-red-semparar' : step > 1 ?
                                'text-red-semparar border-red-semparar' : ''">
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
                            :class="step === 2 ? 'bg-red-semparar text-white border-red-semparar' : step > 2 ?
                                'text-red-semparar border-red-semparar' : ''">
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
                            :class="{ 'bg-red-semparar text-white border-red-semparar': step === 3 }">
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
                    <div class="font-bold text-gray-600 text-xs leading-8 uppercase h-6 mx-2 mt-3 mb-2">Tipo do conteúdo
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
                                    <x-input name="popup" id="popup" type="checkbox" x-model="post.popup"
                                        ::checked="post.popup == 1"
                                        class="focus:ring-red-semparar h-4 w-4 text-red-semparar border-gray-300 rounded">
                                    </x-input>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template x-if="category === 'Treinamentos'">
                        <div class="flex flex-col md:flex-row mt-2">
                            <div class="grid grid-cols-1">
                                <div class="mt-4 w-11/12">
                                    <x-label for="questionnaire_id" value="Associar enquete" />
                                    <select
                                        class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        x-model="post.extras.questionnaire_id" name="extras[questionnaire_id]"
                                        id="questionnaire_id">
                                        <option value=""></option>
                                        @foreach ($questionnaires as $questionnaire)
                                            <option value="{{ $questionnaire->id }}">{{ $questionnaire->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mt-4 w-11/12">
                                    <x-label for="goal" value="Porcentagem para passar (0 a 100):"></x-label>
                                    <x-input id="goal" class="block mt-1 w-full" type="number" name="extras[goal]"
                                        min="0" max="100" maxlength="255" x-model="post.extras.goal" />
                                </div>
                                <div class="mt-4 w-11/12">
                                    <x-label for="tries" value="Tentativas:"></x-label>
                                    <x-input id="tries" class="block mt-1 w-full" type="number"
                                        name="extras[tries]" maxlength="255" x-model="post.extras.tries" />
                                </div>
                                <div class="mt-4 w-11/12">
                                    <x-label for="expire_time" value="Duração do teste (minutos):"></x-label>
                                    <x-input id="expire_time" class="block mt-1 w-full" type="number"
                                        name="extras[expire_time]" maxlength="255" x-model="post.extras.expire_time" />
                                </div>
                            </div>
                        </div>
                    </template>
                    <template x-if="category !== 'Fique ON'">
                        <div class="flex flex-col md:flex-row mt-2">
                            <div class="w-full flex-1 mx-2">
                                <div class="mt-2 items-center">
                                    <x-label for="menu" class="mr-2" display="inline-flex">
                                        Ir para o menu lateral?
                                    </x-label>
                                    <input name="isMenu" id="menu"type="checkbox" x-model="post.isMenu"
                                        :checked="post.isMenu == 1"
                                        class="focus:ring-red-semparar h-4 w-4 text-red-semparar border-gray-300 rounded">
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
                            <x-input class="block mt-1 w-full placeholder:text-gray-400 placeholder:text-right"
                                type="text" id="title" name="title" required maxlength="255"
                                placeholder="255" value="{{ old('title') }}" x-model="post.title" />
                        </div>
                    </div>
                    <template x-if="category !== 'Procedimentos'">
                        <div class="flex flex-col md:flex-row mt-3">
                            <div class="w-full mx-2 flex-1">
                                <x-label for="description" value="Descrição (opcional)"></x-label>
                                <x-input class="block mt-1 w-full placeholder:text-gray-400 placeholder:text-right"
                                    type="text" id="description" name="description" maxlength="255"
                                    placeholder="255" value="{{ old('description') }}" x-model="post.description" />
                            </div>
                        </div>
                    </template>
                    <div class="flex flex-col md:flex-row mt-3 w-1/2">
                        <div class="w-full mx-2 flex-1">
                            <x-label for="published_at" value="Data de agendamento"></x-label>
                            <x-input class="opacity-50 input-form placeholder:text-gray-400 placeholder:text-right"
                                type="datetime-local" id="published_at" name="published_at" maxlength="255" readonly
                                placeholder="255" value="{{ old('published_at') }}" x-model="post.published_at" />
                        </div>
                    </div>
                    <div class="flex flex-col md:flex-row mt-3 w-1/2">
                        <div class="w-full mx-2 flex-1">
                            <x-label for="disabled_at" value="Data de Término"></x-label>
                            <x-input class="input-form placeholder:text-gray-400 placeholder:text-right"
                                type="datetime-local" id="disabled_at" name="disabled_at" maxlength="255"
                                x-model="post.disabled_at"
                                min="{{ isset($post->disabled_at) ? date('Y-m-d H:i', strtotime($post->disabled_at)) : now()->format('Y-m-d H:i') }}" />
                        </div>
                    </div>

                    <div class="flex
                                flex-col md:flex-row mt-3">
                        <div class="w-full mx-2 flex-1">
                            <x-label for="thumbnail" value="Imagem do card"></x-label>
                            <div class="relative w-[30rem] h-[14rem]">
                                <img class="object-cover w-full h-full rounded-lg"
                                    :src="thumbnail === null ? 'https://placehold.co/900x900/e2e8f0/e2e8f0' :
                                        '{{ asset('storage') }}' + '/' + thumbnail" />

                                <input type="hidden" name="thumbnail" :value="thumbnail" x-model="thumbnail">

                                <div class="absolute cursor-pointer top-[36%] left-[43%]"
                                    @click="modalSetThumbnail = true">
                                    <button type="button" class="text-6xl text-gray-700 hover:text-black">
                                        <i class="fa-regular fa-image"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
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
                    <x-button type="button" class="bg-red-semparar hover:bg-red-600 text-white">
                        <a href="{{ route('posts.index') }}">Cancelar</a>
                    </x-button>
                    <div class="flex-auto flex flex-row-reverse">
                        <x-button class="hover:bg-blue-semparar bg-blue-semparar text-gray-100" @click="step += 1"
                            x-show="step < 3" type="button">
                            Próximo
                        </x-button>
                        <x-button type="submit" class="hover:bg-green-600 bg-green-semparar text-gray-100"
                            x-show="step ===  3">
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
                category: @json($post->extras['type']),
                subcategory: @json($post->category_id),
                post: @json($post),
                thumbnail: @json($post->thumb),
                icon: @json($post->category->icon),
                images: @json($images),
                videos: @json($videos),
                selectedImageInformation: null,
                modalSetThumbnail: false,
                selectedImage: null,
            }
        }
    </script>
</x-app-layout>

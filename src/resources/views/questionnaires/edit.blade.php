<x-app-layout>


    <div x-data="initData()" x-cloak>
        <form method="POST" action="{{ route('questionnaires.update', $questionnaire->id) }}">
            @csrf
            @method('PUT')
            <div class="mx-4 pb-2 ">
                <div class="flex items-center justify-center">
                    <div class="flex items-center relative">
                        <div @click="step = 1"
                            class="cursor-pointer text-gray-500 rounded-full transition duration-500 ease-in-out h-12 w-12 px-3.5 py-3 border-2  border-gray-300"
                            :class="step === 1 ? 'bg-red-semparar text-white border-red-semparar' : step > 1 ?
                                'text-red-semparar border-red-semparar' : ''">
                            <i class="fa fa-file-pen"></i>
                        </div>
                        <div
                            class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase text-red-semparar">
                            Detalhes
                        </div>
                    </div>
                    <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-gray-300"
                        :class="{ 'border-red-semparar': step === 2 }">
                    </div>
                    <div class="flex items-center text-gray-500 relative">
                        <div @click="step = 2"
                            class="rounded-full transition duration-500 ease-in-out h-12 w-12 px-3.5 p-3 border-2 border-gray-300"
                            :class="{ 'bg-red-semparar text-white border-red-semparar': step === 2 }">
                            <i class="fa fa-pen"></i>
                        </div>
                        <div
                            class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase text-gray-500">
                            Questões
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conteúdo dos steps -->
            <div class="mt-8" :class="step !== 3 ? 'bg-white rounded p-4' : 'py-4'">
                <!-- Step 1 - Configurações -->
                <div x-show="step === 1">
                    <div class="flex flex-col md:flex-row">
                        <div class="w-full mx-2 flex-1">
                            <x-label for="title" value="Título"></x-label>
                            <x-input class="block mt-1 w-full placeholder:text-gray-400 placeholder:text-right"
                                type="text" id="title" name="title" required maxlength="255" placeholder="255"
                                value="{{ old('title') }}" x-model="questionnaire.name" />
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row mt-2">
                        <div class="w-full flex-1 mx-2">
                            <div class="mt-2 items-center">
                                <x-label for="associate" class="mr-2" display="inline-flex">
                                    Associar enquete a treinamentos?
                                </x-label>
                                <x-input name="associate" id="associate" type="checkbox"
                                    x-model="questionnaire.associate" ::checked="questionnaire.associate == 1"
                                    class="focus:ring-red-semparar h-4 w-4 text-red-semparar border-gray-300 rounded">
                                </x-input>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row mt-5 w-1/2">
                        <div class="w-full mx-2 flex-1">
                            <x-label for="published_at" value="Data de agendamento"></x-label>
                            <x-input class="opacity-50 input-form placeholder:text-gray-400 placeholder:text-right"
                                type="datetime-local" id="published_at" name="published_at" required maxlength="255"
                                value="{{ old('published_at') }}" x-model="questionnaire.published_at" readonly />
                        </div>
                    </div>
                    <div class="flex flex-col md:flex-row mt-3 w-1/2">
                        <div class="w-full mx-2 flex-1">
                            <x-label for="disabled_at" value="Data de Término"></x-label>
                            <x-input class="input-form placeholder:text-gray-400 placeholder:text-right"
                                type="datetime-local" id="disabled_at" name="disabled_at" maxlength="255"
                                x-model="questionnaire.disabled_at"
                                min="{{ isset($questionnaire->disabled_at) ? date('Y-m-d H:i', strtotime($questionnaire->disabled_at)) : now()->format('Y-m-d H:i') }}" />
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row mt-3">
                        <div class="w-full mx-2 flex-1">
                            <x-label for="thumbnail" value="Imagem do card"></x-label>
                            <div class="relative w-[30rem] h-[14rem]">
                                <img class="object-cover w-full h-full rounded-lg"
                                    :src="thumbnail === null ? 'https://placehold.co/900x900/e2e8f0/e2e8f0' :
                                        '../../storage/' + thumbnail" />
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

                </div>
                <!-- Step 3 - Conteúdo -->
                <div x-show="step === 2">
                    <input type="hidden" name="questionnaire_id" :value="questionnaire.id">


                    <div class="mx-auto space-y-4">
                        <template x-if="questions.length === 0">
                            <div class="text-center text-gray-400">
                                <i class="fa-regular fa-folder-open fa-5x"></i>
                                <p class="mt-4 text-xl">Essa enquete ainda não possui perguntas adicionadas.</p>
                                <p class="text-xl">Clique no botão abaixo para adicionar</p>
                            </div>
                        </template>

                        <template x-if="questions.length > 0">
                            <template x-for="(question, indexQuestion) in questions" :key="indexQuestion">
                                <x-questions></x-questions>
                            </template>
                        </template>

                        <div class="flex justify-center">
                            <x-button type="button"
                                class="border border-gray-300 rounded-full leading-none text-gray-400 hover:text-gray-800 hover:border-gray-800"
                                @click="addNewQuestion()">
                                <i class="fa-solid fa-plus"></i>
                            </x-button>
                        </div>
                    </div>
                </div>

                <div class="flex p-2 mt-4">
                    <x-button @click="step -= 1" type="button"
                        class="hover:bg-gray-200 bg-gray-100 border-2 border-gray-semparar mr-2 text-gray-700"
                        x-show="step > 1">
                        Anterior
                    </x-button>
                    <x-button type="button" class="bg-red-semparar hover:bg-red-600 text-white">
                        <a href="{{ route('questionnaires.index') }}">Cancelar</a>
                    </x-button>
                    <div class="flex-auto flex flex-row-reverse">
                        <x-button class="hover:bg-blue-semparar bg-blue-semparar text-gray-100" @click="step += 1"
                            x-show="step < 2" type="button">
                            Próximo
                        </x-button>
                        <x-button type="submit" class="hover:bg-green-600 bg-green-semparar text-gray-100"
                            x-show="step ===  2">
                            Publicar
                        </x-button>
                    </div>
                </div>
            </div>
        </form>
        <x-posts.modal-set-thumbnail></x-posts.modal-set-thumbnail>
    </div>

    <script>
        function initData() {
            return {
                step: 1,
                modalSetThumbnail: false,
                images: @json($images),
                selectedImage: null,
                thumbnail: @json($questionnaire->thumb),
                questionnaire: @json($questionnaire),
                questions: @json($questions),
                images: @json($images),
                selectedQuestion: null,
                selectedImage: null,
                selectedImageInformation: null,

                types: [
                    'Múltipla escolha: texto',
                    'Múltipla escolha: imagem',
                    'Selecione todos os que se aplicam: texto',
                    'Respostas baseadas em texto',
                ],

                addNewQuestion() {
                    this.questions.push({
                        text: '',
                        type: 'Múltipla escolha: texto',
                        options: [],
                    });
                },

                removeQuestion(index) {
                    this.questions.splice(index, 1)
                },
            }
        }
    </script>
</x-app-layout>

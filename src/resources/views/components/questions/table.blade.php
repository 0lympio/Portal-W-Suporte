<div class="my-2" x-data="{ modalSetImage: false, selectedImageName: null, selectedImagePath: null }" x-cloak>
    <div class="bg-white p-5 w-full flex flex-col">
        <table>
            <thead>
                <tr>
                    <th class="th-table-quiz">#</th>
                    <th class="th-table-quiz">Alternativas</th>

                    <template x-if="question.type === 'Múltipla escolha: imagem'">
                        <th class="th-table-quiz">Imagem em destaque</th>
                    </template>

                    <template x-if="question.type !== 'Respostas baseadas em texto'"">
                        <th class="th-table-quiz">Correto</th>
                    </template>

                    <th class="th-table-quiz"></th>
                </tr>
            </thead>

            <tbody>
                <template x-for="(option, indexOption) in question.options" :key="indexOption">
                    <tr class="hover:bg-gray-100 text-gray-900 text-sm">
                        <td class="table-col" x-text="indexOption + 1"></td>

                        <td class="table-col">
                            <x-input class="input-form"
                                     type="text"
                                     ::name="`questions[${indexQuestion}][alternatives][${indexOption}][text]`"
                                     placeholder="Insira a alternativa"
                                     x-model="option.text"
                                     required />
                        </td>

                        <template x-if="question.type === 'Múltipla escolha: imagem'">
                            <td class="table-col"
                                x-id="['image-name', 'image-path']"
                                x-cloak>

                                <div class="flex items-center">
                                    <button type="button"
                                            class="text-xl"
                                            @click="modalSetImage = true; selectedImageName = $id('image-name'); selectedImagePath = $id('image-path')">
                                        <i class="fa-regular fa-image fa-2x text-gray-500 hover:text-gray-900"></i>
                                    </button>

                                    <input type="hidden"
                                           :name="`questions[${indexQuestion}][alternatives][${indexOption}][image]`"
                                           :id="$id('image-path')"
                                             x-model="option.image">

                                    <input type="hidden"
                                           :name="`questions[${indexQuestion}][alternatives][${indexOption}][imageName]`"
                                           :id="$id('image-name')"
                                             x-model="option.imageName">

                                    <span class="font-xs text-gray-500 ml-2"
                                          :id="$id('image-name')"
                                          x-text="option.imageName === null ? 'Nenhuma imagem selecionada' : option.imageName"></span>
                                </div>
                            </td>
                        </template>

                        <template x-if="question.type !== 'Respostas baseadas em texto'">
                            <td class="table-col" x-id="['checked-answer']">
                                <label :for="$id('checked-answer')" class="flex items-center cursor-pointer">
                                    <div class="relative">
                                        <input :id="$id('checked-answer')"
                                               type="checkbox"
                                               class="sr-only"
                                               :name="`questions[${indexQuestion}][alternatives][${indexOption}][checked]`"
                                               :checked="option.isCorrect == 1"
                                               x-model="option.isCorrect"
                                               @click="option.isCorrect = ! option.isCorrect;">
                                        <div class="w-10 h-4 bg-gray-400 rounded-full shadow-inner"></div>
                                        <div class="dot absolute w-6 h-6 bg-white rounded-full shadow -left-1 -top-1 transition"></div>
                                    </div>
                                </label>
                            </td>
                        </template>

                        <td class=" whitespace-no-wrap border-b border-gray-200">
                            <button type="button"
                                    class="text-xl"
                                    @click="removeAlternative(indexOption, question)">
                                    <i class="fa fa-trash text-red"></i>
                            </button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>

        <div class="flex justify-center">
            <x-button type="button"
                      class="mt-3 border border-gray-300 rounded-full leading-none text-gray-400 hover:text-gray-800 hover:border-gray-800"
                      @click="addNewAlternative(question); resize(75);">
                    <i class="fa-solid fa-plus"></i>
            </x-button>
        </div>
    </div>

    <x-questions.modal-set-image></x-questions.modal-set-image>
</div>

<script>
    function addNewAlternative(question) {
        question.options.push({
            text: '',
            image: null,
            imageName: null,
            isCorrect: false
        });
    }

    function removeAlternative(index, question) {
        question.options.splice(index, 1);
    }
</script>

<style>
    input:checked ~ .dot {
        transform: translateX(100%);
        background-color: rgb(34, 197, 94);
    }
</style>


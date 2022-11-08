<div class="flex items-center cursor-pointer">
    <div class="bg-indigo-100 text-indigo-400 w-8 h-8 md:w-10 md:h-10 rounded-md flex items-center justify-center font-bold text-lg font-display">
        <span x-text="indexQuestion + 1"></span>
    </div>

    <x-input class="block mt-1 w-2/4 mx-4"
             type="text"
             ::name="`questions[${indexQuestion}][text]`"
             placeholder="Insira a questão"
             x-model="question.text"
             required />

    <div class="bg-indigo-100 text-indigo-400 w-8 h-8 md:w-10 md:h-10 rounded-md flex items-center justify-center font-bold text-lg font-display"
         @click="selectedQuestion !== indexQuestion ? selectedQuestion = indexQuestion : selectedQuestion = null">
         <i class="fa-solid fa-angle-down" x-show="selectedQuestion !== indexQuestion"></i>
         <i class="fa-solid fa-angle-up" x-show="selectedQuestion === indexQuestion"></i>
    </div>

    <div class="flex justify-end w-2/5">
        <button type="button"
                class="text-xl"
                @click="removeQuestion(indexQuestion)">
            <i class="fa fa-trash text-red"></i>
        </button>
    </div>
</div>

<div class="relative overflow-hidden transition-all max-h-0 duration-1000"
     :style="selectedQuestion === indexQuestion ? `max-height: ${$el.scrollHeight}px` : ''"
     x-data="{
        resize: (elementHeight) => {
            $el.style.maxHeight = $el.scrollHeight + elementHeight + 'px'
        }
    }">

    <div class="mt-4">
        <x-label for="type" value="Tipo de pergunta" />
        <select :name="`questions[${indexQuestion}][type]`"
                class="input-form select-form"
                required
                x-model="question.type">

            <template x-for="(type, indexType) in types" :key="indexType">
                <option ::value="type" x-text="type" :selected="type === question.type"></option>
            </template>
        </select>
    </div>

    <!-- Tabela com as opções -->
    <x-questions.table></x-questions.table>
</div>

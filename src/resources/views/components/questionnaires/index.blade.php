@props(['questions', 'questionnaire', 'post' => null])

<div class="mt-6 flex flex-col justify-center">
    <div class="w-full overflow-hidden rounded bg-white px-6 py-4 shadow-md" x-data="init({{ collect($questions) }}, {{ collect($questionnaire) }}, {{ collect($post) }})" x-cloak
        id="quest-view">
        <form>
            @csrf
            <div class="mb-2">
                <template x-for="(question, questionIndex) in questions" :key="questionIndex">
                    <div x-show="current == questionIndex">
                        <span x-text="question.text" class="text-xl"></span>
                        <span x-show="question.type == 'Selecione todos os que se aplicam: texto'"
                            class="text-sm text-gray-500">Selecione todas as respostas que se aplicam</span>

                        <div x-show="question.type === 'Múltipla escolha: imagem'" class="grid grid-cols-3 gap-4">
                            <template x-for="(option, optionIndex) in question.options" :key="optionIndex">
                                <div class="w-60 cursor-pointer p-4"
                                    @click="check(questionIndex, option.text, optionIndex, option.isCorrect)">
                                    <div class="h-full overflow-hidden rounded-xl shadow hover:shadow-xl"
                                        :class="[isSelected(questionIndex, option.text) ? 'bg-blue-semparar text-white' :
                                            'bg-lightgray-semparar'
                                        ]">

                                        <img :src="'{{ asset('storage') }}' + '/' + option.image"
                                            class="duration-400 h-32 w-full scale-95 rounded-t-lg object-cover object-center transition-all hover:scale-100">

                                        <div class="p-6">
                                            <div
                                                class="flex w-full flex-col flex-wrap items-center justify-between border-t-2">
                                                <span @click="check(questionIndex, option.text)"
                                                    class="shadow-cla-blue mt-2 rounded-lg px-4 py-1 uppercase hover:scale-105"
                                                    x-text="option.text">
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div x-show="question.type === 'Múltipla escolha: texto' || question.type === 'Selecione todos os que se aplicam: texto'"
                            x-data="{ itemsCheckeds: [] }" x-cloak>
                            <template x-for="(option, optionIndex) in question.options" :key="optionIndex">
                                <div class="mx-2 w-full flex-1">
                                    <div class="mt-2 items-center" x-id="['alternative']">
                                        <x-input ::id="$id('alternative')" type="checkbox"
                                            class="h-5 w-5 mr-2 rounded border-gray-300 text-red-semparar focus:ring-red-semparar"
                                            @click="itemsCheckeds.push({element: $el, index: optionIndex}); check(questionIndex, option.text, optionIndex, option.isCorrect, itemsCheckeds)">
                                        </x-input>
                                        <x-label ::for="$id('alternative')" class="mr-2" display="inline-flex"
                                            class="text-lg font-normal cursor-pointer">
                                            <span x-text="option.text"></span>
                                        </x-label>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <template x-if="question.type === 'Respostas baseadas em texto'">
                            <textarea x-model="question.answer" class="mt-3 w-full rounded-lg shadow-md"></textarea>
                        </template>
                    </div>
                </template>
                <template x-if="showScore">
                    <div>
                        <div class="text-center p-5 flex-auto justify-center">
                            <div x-show="result()">
                                <i class="fa-regular fa-thumbs-up fa-2xl"></i>
                                <h2 class="text-xl font-bold py-4">Parabéns, você acertou!</h2>
                            </div>

                            <div x-show="!result()">
                                <i class="fa-regular fa-thumbs-down fa-2xl"></i>
                                <h2 class="text-xl font-bold py-4">
                                    Poxa! Não foi dessa vez. Ficou com dúvida, busque pelo procedimento ou Fala com a
                                    gente!
                                </h2>
                            </div>
                        </div>
                        <div class="p-3  mt-2 text-center space-x-4 md:block">
                            <button type="button" @click="window.location = document.referrer"
                                class="mb-2 md:mb-0 bg-green-500 border border-green-500 px-5 py-2 text-sm shadow-sm font-medium tracking-wider text-white rounded-md hover:shadow-lg hover:bg-green-600">
                                Concluir
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <x-button x-show="current > 0 " @click="previousQuestion()" type="button"
                class="float-left mt-2 bg-red-semparar text-white">
                Voltar
            </x-button>
            <x-button x-show="current == questions.length -1" @click="calculateScore()" type="button"
                class="float-right mt-2 ml-2 bg-green-semparar text-white">
                Enviar
            </x-button>
            <x-button x-show="current != null && current < questions.length -1" @click="nextQuestion()" type="button"
                class="float-right mt-2 bg-red-semparar text-white">
                Próxima
            </x-button>
        </form>
    </div>
</div>

<script>
    window.init = function(data, questionnaire, post) {
        return {
            showScore: false,
            score: 0,
            current: 0,
            selected: null,
            goal: post.length > 0 ? Number(post['extras']['goal']) : 0,
            questions: data.map((obj) =>
                Object.assign(obj, {
                    answer: [],
                })
            ),

            check(questionIndex, answer, optionIndex, isCorrect, itemsCheckeds = []) {
                if (this.questions[questionIndex].type === 'Selecione todos os que se aplicam: texto') {
                    if (this.questions[questionIndex].answer.some(item => item.index === optionIndex)) {
                        this.questions[questionIndex].answer = this.questions[questionIndex].answer.filter(
                            (data) => data.index != optionIndex
                        );
                    } else {
                        this.questions[questionIndex].answer.push({
                            text: answer,
                            index: optionIndex,
                            isCorrect: isCorrect
                        });
                    }
                } else if (this.questions[questionIndex].type === 'Múltipla escolha: imagem') {
                    this.questions[questionIndex].answer = {
                        text: answer,
                        isCorrect: isCorrect
                    };
                } else if (this.questions[questionIndex].type === 'Múltipla escolha: texto') {
                    this.questions[questionIndex].answer = {
                        text: answer,
                        isCorrect: isCorrect
                    };

                    itemsCheckeds.forEach(item => {
                        if (item.index != optionIndex) {
                            item.element.checked = false;
                        }
                    });
                }
            },

            nextQuestion() {
                this.current += 1;
            },

            previousQuestion() {
                this.current -= 1;
            },

            didNotAnswerAllQuestions() {
                let unanswered = 0;

                this.questions.forEach(element => {
                    if ((Array.isArray(element.answer) && element.answer.length === 0) || (element
                            .answer === '')) {
                        unanswered += 1;
                    }
                });

                return unanswered > 0;
            },

            calculateScore() {
                if (this.didNotAnswerAllQuestions()) {
                    return Swal.fire({
                        title: "",
                        text: "Para concluir a enquete é obrigatório responder todos os itens!",
                        icon: 'info',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'OK',
                        cancelButtonText: 'Não',
                        width: '400px',
                        customClass: 'swal-height'
                    });
                }

                this.questions.forEach((question) => {
                    let correctOption = question.options.filter((value) => {
                        if (question.type !== 'Respostas baseadas em texto') {
                            return value.isCorrect;
                        }

                        return question.options.map((value) => value.text);
                    });

                    if (question.type !== 'Respostas baseadas em texto' && question.type !==
                        'Selecione todos os que se aplicam: texto') {
                        if (question.answer.text === correctOption[0].text && question.answer.isCorrect ===
                            correctOption[0].isCorrect) {
                            this.score += 1;
                            question.isCorrect = 1
                        }
                    } else if (question.type === 'Selecione todos os que se aplicam: texto') {
                        if (correctOption.length === question.answer.length) {
                            let booleans = correctOption.map((option, index) => {
                                return option.text === question.answer[index].text && option
                                    .isCorrect === question.answer[index].isCorrect
                            });

                            if (booleans.every(element => element === true)) {
                                this.score += 1
                                question.isCorrect = 1
                            }
                        }
                    } else {
                        if (correctOption.some(option => option.text === question.answer)) {
                            this.score += 1
                            question.isCorrect = 1
                        }
                    }
                });

                this.send();

                this.current = null;

                const viewPost = document.getElementById('view-post');
                clearInterval(Alpine.$data(viewPost).intervalQuest);
            },

            send() {
                let token = document.getElementsByName('_token')[0].value;
                let formData = {
                    _token: token,
                    questions: this.questions,
                    questionnaire_id: questionnaire.id,
                }
                fetch("{{ route('questionnaires.reply') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(formData)
                    })
                    .then(this.showScore = true)
                    .catch(err => console.error(err));
            },

            isSelected(questionIndex, answer) {
                return this.questions[questionIndex].answer.text === answer;
            },

            result() {
                if (this.score === 0) {
                    return false
                }

                return (this.score / this.questions.length) >= (this.goal / 100);
            },
        };
    };
</script>

<style>
    .swal2-icon {
        width: 2em;
        height: 2em;
    }

    .swal2-icon .swal2-icon-content {
        display: flex;
        align-items: center;
        font-size: 1.75em;
    }

    .swal-height {
        height: 250px;
    }
</style>

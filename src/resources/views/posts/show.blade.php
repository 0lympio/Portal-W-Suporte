<x-app-layout>
    <div x-data="start({{ collect($post->extras) }})" x-cloak id="view-post">
        <div class="flex flex-col justify-center mt-2">
            <div x-show="showPost" id="post"
                class="w-full px-6 py-4 bg-white rounded overflow-hidden shadow-md ring-1 ring-gray-900/10">
                <div id="toPrint">
                    <p class="font-bold text-3xl prose text-center">{{ $post->title }}</p>
                    <div class="mt-4 text-gray-400 flex justify-between text-sm">
                        <p>Atualizado por {{ $post->lastModifiedBy->name }}
                            {{ $post->lastModifiedBy->last_name }} em
                            {{ \Carbon\Carbon::parse($post->updated_at)->format('d/m/Y') }} às
                            {{ \Carbon\Carbon::parse($post->updated_at)->format('H:i') }}
                        </p>
                        <p>Publicado por {{ $post->user->name }} {{ $post->user->last_name }} em
                            {{ \Carbon\Carbon::parse($post->published_at)->format('d/m/Y') }} às
                            {{ \Carbon\Carbon::parse($post->published_at)->format('H:i') }}
                        </p>
                    </div>
                    <p class="text-gray-400 text-sm">Versão {{ $post->version }}</p>
                    <div class="mt-4">
                        <p class="text-justify leading-relaxed text-gray-500">
                            {{ $post->description }}
                        </p>
                    </div>
                    <div class="mt-4 border-b-[1px] border-gray-semparar pb-2 mx-20"></div>
                    <div class="mt-4" id="content">
                        {!! $post->content !!}
                    </div>
                </div>
                <div class="flex items-center justify-center mt-6">
                    <a
                        @if ($post->popup) href="{{ url('feed-de-noticias') }}"
                        @else href="{{ url($post->category->slug) }}" @endif>
                        <x-button type="button" class="bg-gray-semparar hover:bg-gray-semparar text-white">
                            Voltar
                        </x-button>
                    </a>
                    <x-button @click="printDiv('{{ $post->title }}')" type="button" class="button-red ml-2">
                        Baixar conteúdo
                    </x-button>
                    <x-button x-show="!{{ $post_view->read }}" @click="markAsRead()" type="button"
                        class="button-blue ml-2">
                        Marcar como lido
                    </x-button>
                    @if ($post->extras['type'] === 'Treinamentos' && isset($questionnaire))
                        <x-button type="button" class="button-green ml-2" @click="beginTest()">
                            Iniciar teste
                        </x-button>
                    @endif

                    @can('posts.edit')
                        <a :href="`{{ $post->id }}/edit`">
                            <x-button type="button" class="button-green ml-2">
                                Editar
                            </x-button>

                        </a>
                    @endcan
                </div>
            </div>

            @if ($post->extras['type'] === 'Treinamentos' && isset($questionnaire))
                <div x-show="showQuest">
                    <h1 class="text-gray-semparar flex- flex-row items-center align-center">
                        <span class="text-2xl">
                            <i class="fa-solid fa-clipboard-question mx-2"></i>
                            Teste:
                        </span>
                        <span class="text-xl">{{ $questionnaire->name }}</span>
                    </h1>
                    <h2 class="text-red-600 m-2">Tempo restante: <span x-text="time_left"></span></h2>
                    <x-questionnaires :questionnaire="$questionnaire" :post="$post" :questions="$questionnaire->questions"></x-questionnaires>
                </div>
            @endif

            @can('comments.show')
                <p class="mt-4 font-bold text-gray-700 text-lg">{{ $comments->count() }}
                    comentário{{ $comments->count() != 1 ? 's' : '' }}</p>
            @endcan

            @can('comments.store')
                <div class="my-5">
                    <form class="comments-store" method="POST" action="{{ route('comments.store') }}">
                        @csrf
                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                        <x-tiny-mce.editor-comment />
                        <x-tiny-mce.config-comment />
                        <div class="flex items-center justify-end mt-3">
                            <x-button type="submit" class="button-green ml-2">
                                Comentar
                            </x-button>
                        </div>
                    </form>
                </div>
            @endcan
        </div>

        @can('comments.show')
            @foreach ($comments as $comment)
                <div class="comments-post">
                    <x-posts.comment :comment="$comment" :nivel="0"></x-posts.comment>

                    @can('comments.store')
                        <div class="my-5 f-show hide">
                            <form class="comments-store" method="POST" action="{{ route('comments.store') }}">
                                @csrf
                                <input type="hidden" name="post_id" value="{{ $post->id }}">
                                <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                                <x-tiny-mce.editor-comment />
                                <x-tiny-mce.config-comment />
                                <div class="flex items-center justify-end mt-3">
                                    <x-button type="submit" class="button-green ml-2">
                                        Comentar
                                    </x-button>
                                </div>
                            </form>
                        </div>
                    @endcan
                </div>
                @if (isset($commentsSubs[$comment->id]))
                    @foreach ($commentsSubs[$comment->id] as $commentDetail)
                        <div class="comments-post">
                            <x-posts.comment :comment="$commentDetail" :nivel="5"></x-posts.comment>

                            @can('comments.store')
                                <div class="my-5 f-show hide">
                                    <form class="comments-store" method="POST" action="{{ route('comments.store') }}">
                                        @csrf
                                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                                        <input type="hidden" name="comment_id" value="{{ $commentDetail->id }}">
                                        <x-tiny-mce.editor-comment />
                                        <x-tiny-mce.config-comment />
                                        <div class="flex items-center justify-end mt-3">
                                            <x-button type="submit" class="button-green ml-2">
                                                Comentar
                                            </x-button>
                                        </div>
                                    </form>
                                </div>
                            @endcan
                        </div>

                        @if (isset($commentsSubs[$commentDetail->id]))
                            @foreach ($commentsSubs[$commentDetail->id] as $commentDetail2)
                                <div class="comments-post">
                                    <x-posts.comment :comment="$commentDetail2" :nivel="10"></x-posts.comment>

                                    @can('comments.store')
                                        <div class="my-5 f-show hide">
                                            <form class="comments-store" method="POST" action="{{ route('comments.store') }}">
                                                @csrf
                                                <input type="hidden" name="post_id" value="{{ $post->id }}">
                                                <input type="hidden" name="comment_id" value="{{ $commentDetail2->id }}">
                                                <x-tiny-mce.editor-comment />
                                                <x-tiny-mce.config-comment />
                                                <div class="flex items-center justify-end mt-3">
                                                    <x-button type="submit" class="button-green ml-2">
                                                        Comentar
                                                    </x-button>
                                                </div>
                                            </form>
                                        </div>
                                    @endcan
                                </div>


                                @if (isset($commentsSubs[$commentDetail2->id]))
                                    @foreach ($commentsSubs[$commentDetail2->id] as $commentDetail3)
                                        <div class="comments-post">
                                            <x-posts.comment :comment="$commentDetail3" :nivel="15"></x-posts.comment>

                                            @can('comments.store')
                                                <div class="my-5 f-show hide">
                                                    <form class="comments-store" method="POST"
                                                        action="{{ route('comments.store') }}" height="200px">
                                                        @csrf
                                                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                                                        <input type="hidden" name="comment_id"
                                                            value="{{ $commentDetail3->id }}">
                                                        <x-tiny-mce.editor-comment />
                                                        <x-tiny-mce.config-comment />
                                                        <div class="flex items-center justify-end mt-3">
                                                            <x-button type="submit" class="button-green ml-2">
                                                                Comentar
                                                            </x-button>
                                                        </div>
                                                    </form>
                                                </div>
                                            @endcan
                                        </div>
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @endif


                @if (!$loop->last)
                    <div class="mx-4 mb-4 border-b border-gray-400 pb-2"></div>
                @endif
            @endforeach
        @endcan

        <x-comment-modal></x-comment-modal>
    </div>
    <script>
        $(document).ready(function() {
            $('.comments-store').on('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: "",
                    text: "Tem certeza que deseja enviar?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sim',
                    cancelButtonText: 'Não',
                    width: '400px',
                    customClass: 'swal-height'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: "",
                            text: "Obrigado pela sua mensagem. Em breve ela será publicada.",
                            icon: 'info',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'OK',
                            cancelButtonText: 'Não',
                            width: '400px',
                            customClass: 'swal-height'

                        }).then((result) => {
                            this.submit();
                        })
                    }
                })

            });

            $('body').
            on('click', '.comments-post button.b-show', function() {
                const card = $(this).closest('.comments-post')
                if (card.find('.f-show').hasClass('hide')) {
                    card.find('.f-show').removeClass('hide');
                } else {
                    card.find('.f-show').addClass('hide');
                }
            })
        });

        window.start = function(data) {
            return {
                deleteComment: false,
                commentId: null,
                showPost: true,
                showQuest: false,
                expire_time: data['expire_time'] * 60,
                time_left: '',
                intervalQuest: null,
                retries: 0,
                goal: 0,
                markAsRead() {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('posts.read', $post->id) }}",
                        success: function(data) {
                            if ($.isEmptyObject(data.error)) {
                                location.reload()
                            } else {
                                console.log(data.error)
                            }
                        }
                    });
                },

                beginTest() {
                    let url = "{{ route('questionnaires.view') }}";
                    let data = {
                        questionnaire_id: '{{ $questionnaire->id ?? '' }}'
                    };

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(data),
                        })
                        .then(response => response.json())
                        .then(data => console.log(data))
                        .catch(err => console.error(err));


                    this.showPost = false;
                    this.showQuest = true;
                    this.intervalQuest = setInterval(() => {
                        this.countDown()
                    }, 1000)
                },

                countDown() {
                    if (this.expire_time > 0) {
                        this.expire_time = this.expire_time - 1
                    }

                    let h = Math.floor(this.expire_time / 3600).toString().padStart(2, '0');
                    let m = Math.floor(this.expire_time % 3600 / 60).toString().padStart(2, '0');
                    let s = Math.floor(this.expire_time % 3600 % 60).toString().padStart(2, '0');
                    this.time_left = `${h}:${m}:${s}`;

                    if (this.time_left === '00:00:00') {
                        const questView = document.getElementById('quest-view');
                        Alpine.$data(questView).calculateScore();
                    }
                },

                printDiv() {
                    $("#toPrint").printThis({});
                },
            }
        };
    </script>
    <style>
        #content h1,
        #content h2,
        #content h3,
        #content h4,
        #content h5,
        #content h6 {
            font-size: revert !important;
            font-weight: revert !important;
        }

        #content ul,
        #content li,
        #content p {
            all: revert;
        }

        #content a {
            text-decoration: none;
            color: blue;
        }

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

        .m-l-5 {
            margin-left: 5%;
            width: 95%;
        }

        .m-l-10 {
            margin-left: 10%;
            width: 90%;
        }

        .m-l-15 {
            margin-left: 15%;
            width: 85%;
        }

        .hide {
            display: none;
        }

        .f-show .comments-store {
            margin-left: 10%;
        }
    </style>
</x-app-layout>

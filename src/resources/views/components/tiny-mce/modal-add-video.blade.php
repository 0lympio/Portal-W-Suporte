<div x-show="modalAddVideo" class="fixed inset-0 z-[9991] bg-gray-600 bg-opacity-50 h-full w-full">
    <div class="relative top-10 mx-auto p-2 border w-4/5 h-5/6 shadow-lg rounded-md bg-white overflow-y-auto"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90"
         x-show="modalAddVideo"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="ease-in duration-300"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-90">
        <div class="flex sticky">
            <h1 class="text-lg w-full ml-2 text-gray-semparar">Adicionar vídeos na publicação</h1>

            <div class="flex w-full justify-end text-2xl px-2">
                <button @click="modalAddVideo = false;" type="button">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>
        <div class="px-2 pt-2 mb-3">
            <div class="flex flex-row flex-wrap">
                <template x-if="videos.length > 0">
                    <template x-for="(video, index) in videos" :key="index">
                        <div class="px-4 pt-4 w-56">
                            <div class="hover:shadow-xl shadow h-full rounded-md overflow-hidden bg-gray-100 transition-all duration-400 cursor-pointer"
                                 :class="selected === index ? 'scale-90' : 'scale-100'"
                                 :id="index"
                                 @click="selected !== index ? selected = index : selected = null;
                                        path = '{{ asset('storage') }}' + '/' + video.path">

                                {{-- <img class="h-36 w-full object-cover object-center" :src="'{{ asset('storage') }}' + '/' + video.path" alt="blog" /> --}}
                                <div class="text-center text-8xl m-3">
                                    <i class="fa-solid fa-video"></i>
                                </div>

                                <div class="px-6 pt-2">
                                    <h2 class="tracking-widest text-xs font-medium text-gray-400 mb-1" x-text="new Date(video.created_at).toLocaleString()"></h2>
                                    <h1 class="text-sm font-medium text-gray-600 mb-3" x-text="video.name"></h1>
                                </div>
                            </div>
                        </div>
                    </template>
                </template>

                <template x-if="videos.length === 0">
                    <div class="text-center text-gray-400 w-full mt-8">
                        <i class="fa-regular fa-file fa-5x"></i>

                        <p class="mt-4 text-2xl">Não há vídeos na biblioteca de mídia</p>
                    </div>
                </template>
            </div>
        </div>
        <!-- Se clicar em um vídeo -->
        <div class="sticky -bottom-2 bg-white flex justify-end w-full pb-4 p-4" x-show="selected != null" x-transition>
            <x-button type="button"
                      class="text-white bg-green-500 hover:bg-green-600"
                      @click="modalAddVideo = false; selected = null;" id="button-add-video">
                Definir vídeo
            </x-button>
        </div>
    </div>
</div>

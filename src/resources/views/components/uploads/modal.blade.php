<!-- z-[9990] === O modal tem que estar com o alto grau de prioridade de sobreposição sobre qualquer outro elemento -->
<div x-show="uploadFileModal" class="fixed z-[9990] inset-0 bg-gray-600 bg-opacity-50">
    <div class="relative top-10 mx-auto p-2 border w-4/5 h-[88%] shadow-lg rounded-md bg-white overflow-y-auto"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90"
         x-show="uploadFileModal"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="ease-in duration-300"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-90"
         @click.outside="uploadFileModal = false;">
        <div class="mt-2">
            <div class="flex">
                <h1 class="text-lg w-full ml-2 text-gray-semparar">Selecione ou envie a mídia</h1>

                <div class="flex w-full justify-end text-2xl px-2">
                    <button @click="uploadFileModal = false;">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>
            <!-- Tabs -->
            <div class="mb-4 flex w-full">
                <button class="my-2 px-3 py-3 text-sm hover:bg-gray-100"
                        @click="tab = 'send_files'"
                        :class="tab === 'send_files' ? 'border-b-[1px] border-red-semparar' : ''">
                    Enviar arquivos
                </button>
                <button class="my-2 px-3 py-3 text-sm hover:bg-gray-100"
                        @click="tab = 'media_library'"
                        :class="tab === 'media_library' ? 'border-b-[1px] border-red-semparar' : ''">
                    Biblioteca de mídia
                </button>
            </div>
            <!-- Conteúdo das tabs -->
            <div class="mb-2 mt-2 sm:rounded-sm p-3 flex justify-center">
                <!-- Envio de arquivos -->
                <div x-show="tab === 'send_files'" x-transition:enter.duration.400ms>
                    <x-uploads.send-files></x-uploads.send-files>
                </div>
                <!-- Biblioteca de mídia -->
                <div x-show="tab === 'media_library'"  x-transition:enter.duration.400ms>
                    <x-uploads.media-library :files="$files"></x-uploads.media-librar>
                </div>
            </div>
        </div>
    </div>
</div>

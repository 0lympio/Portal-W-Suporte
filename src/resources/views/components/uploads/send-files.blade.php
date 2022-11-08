<div x-data="initSendFiles()" x-cloak>
    <form method="POST" action="{{ route('uploads.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="relative p-6 border-2 border-gray-300 border-dashed rounded-md w-[30rem] h-[16rem] overflow-y-auto"
            x-ref="borderDashed">
            <input class="cursor-pointer outline-none opacity-0 m-0 p-0 absolute w-full h-full left-0 top-0"
                type="file" name="files[]" multiple required accept="image/*,.pdf,video/*" x-ref="input"
                @dragover="files.length === 0 ? $refs.borderDashed.classList.add('border-blue-400') : null;"
                @dragleave="files.length === 0 ? $refs.borderDashed.classList.remove('border-blue-400') : null;"
                @change="files.push(...$event.target.files); modalErrorSize = validateFilesSize();">
            <template x-if="files.length > 0">
                <div class="flex flex-col">
                    <template x-for="(file, index) in files">
                        <div class="flex flex-row items-center m-2">
                            <div x-show="file.type == 'image/jpeg' || file.type == 'image/png'">
                                <img :src="URL.createObjectURL(file)" alt="" width="70" height="70">
                            </div>
                            <div x-show="file.type !== 'image/jpeg' && file.type !== 'image/png'">
                                <i class="fa-regular fa-file fa-3x"></i>
                            </div>
                            <span class="font-medium mx-2" x-text="file.name"></span>
                            <span class="text-xs mx-2 text-gray-500 mr-2"
                                x-text="(file.size / (1024 * 1024)).toFixed(2) + 'MB'"></span>
                        </div>
                    </template>
                </div>
            </template>
            <template x-if="files.length === 0">
                <div class="flex flex-col space-y-2 items-center justify-center text-gray-700">
                    <i class="fa-solid fa-cloud-arrow-up fa-5x" x-ref="icon"></i>
                    <p class="text-gray-700">Arraste seus arquivos aqui ou clique nesta área.</p>
                    <p class="text-sm text-gray-500">Tamanho máximo para upload de imagem: 10MB</p>
                    <p class="text-sm text-gray-500">Tamanho máximo para upload de documento: 50MB</p>
                    <p class="text-sm text-gray-500">Tamanho máximo para upload de vídeo: 70MB</p>
                </div>
            </template>
        </div>
        <div class="flex justify-center mt-4">
            <x-button class="bg-red-semparar mt-5 text-white">Enviar</x-button>
        </div>
    </form>

    <x-uploads.modal-error-size>O arquivo enviado é muito grande.</x-uploads.modal-error-size>
    <script>
        window.initSendFiles = function() {
            return {
                files: [],
                modalErrorSize: false,

                validateFilesSize() {
                    for (const file of this.files) {
                        if (file.type.startsWith('image/') && ((file.size / (1024 * 1024)) > 10)) {
                            return true;
                        } else if (file.type.startsWith('video/') && ((file.size / (1024 * 1024)) > 70)) {
                            return true
                        } else if ((file.size / (1024 * 1024)) > 50) {
                            return true
                        }
                    }

                    return false;
                },
            }
        }
    </script>
</div>

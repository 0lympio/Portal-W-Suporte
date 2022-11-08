<div x-data="initLibrary()" x-cloak>
    <div class="flex flex-row flex-wrap justify-center px-2 pt-2">
        <template x-if="files.length > 0">
            <template x-for="file in files" :key="file.id">
                <div class="px-4 pt-4 w-80">
                    <div class="hover:shadow-xl shadow h-full rounded-md overflow-hidden bg-gray-100">
                        <label :for="`file-${file.id}`" class="cursor-pointer scale-100">
                            <template x-if="file.mimetype.startsWith('image')">
                                <img class="h-44 w-full object-cover object-center" :src="'{{ asset('storage') }}' + '/' + file.path" alt="blog">
                            </template>
                            <template x-if="file.extension === 'pdf'">
                                <div class="text-9xl py-4 text-center items-center">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </div>
                            </template>
                            <template x-if="['pptx', 'ppt'].includes(file.extension)">
                                <div class="text-9xl py-4 text-center items-center">
                                    <i class="fa-solid fa-file-powerpoint"></i>
                                </div>
                            </template>
                            <template x-if="!(['pptx', 'ppt', 'pdf'].includes(file.extension) || file.mimetype.startsWith('image'))">
                                <div class="text-9xl py-4 text-center items-center">
                                    <i class="fa-solid fa-file"></i>
                                </div>
                            </template>
                        </label>

                        <div class="px-6 pt-2">
                            <h2 class="tracking-widest text-xs font-medium text-gray-400 mb-1" x-text="new Date(file.created_at).toLocaleString()"></h2>
                            <h1 class="text-sm font-medium text-gray-600 mb-3 line-clamp-1 overflow-hidden" x-text="file.name"></h1>

                            <div class="flex justify-between mt-2 mb-2 items-center">
                                <input type="checkbox"
                                        :id="`file-${file.id}`"
                                        ::value="file.id"
                                        class="focus:ring-red-700 h-5 w-6 text-red-ring-red-700 border-gray-300 rounded"
                                        @click="addOrRemoveFrom(file)" />

                                <template x-if="file.extension === 'pdf'">
                                    <x-button type="button" class="text-gray-700 text-2xl hover:text-green-600"
                                              @click="selectedFile = file; modalWidthAndHeightPdf = true;">
                                        <i class="fa-regular fa-copy"></i>
                                    </x-button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </template>

        <template x-if="files.length === 0">
            <div class="text-center text-gray-400">
                <i class="fa-regular fa-file fa-5x"></i>
                <p class="mt-4 text-2xl">A biblioteca de mídia está vazia</p>
            </div>
        </template>
    </div>

    <div class="sticky -bottom-2 bg-white flex w-full p-4" x-show="selected.length > 0">
        <!-- Visualização dos arquivos selecionados -->
        <div class="mr-2">
            <p x-text="`${selected.length} ite${selected.length > 1 ? 'ns' : 'm'} selecionado${selected.length > 1 ? 's' : ''}`" class="font-bold"></p>
            <p class="text-gray-700 cursor-pointer" @click="clearSelected()">Limpar</p>
        </div>

        <div class="flex flex-row overflow-x-hidden w-[50vw]">
            <template x-for="(item, index) in selected" :key="index">
                <div class="mr-2 h-12 w-12">
                    <template x-if="item.mimetype.startsWith('image')">
                        <img class="object-cover object-center min-w-[3rem] h-12" :src="'{{ asset('storage') }}' + '/' + item.path" alt="blog">
                    </template>
                    <template x-if="item.extension === 'pdf'">
                        <i class="fa-solid fa-file-pdf fa-3x"></i>
                    </template>
                    <template x-if="['pptx', 'ppt'].includes(item.extension)">
                        <i class="fa-solid fa-file-powerpoint fa-3x"></i>
                    </template>
                    <template x-if="!(['pptx', 'ppt', 'pdf'].includes(item.extension) || item.mimetype.startsWith('image'))">
                        <i class="fa-solid fa-file fa-3x"></i>
                    </template>
                </div>
            </template>
        </div>
        <!-- Remoção de itens -->
        <div class="flex justify-end ml-8">
            <button @click="modalDeleteFiles = true;" x-show="selected.length > 0">
                <i class="fa fa-trash text-red fa-2x"></i>
            </button>
        </div>
    </div>

    <x-uploads.modal-delete-files :clickoutside="false"></x-uploads.modal-delete-files>
    <x-uploads.modal-width-height-pdf :clickoutside="false"></x-uploads.modal-width-height-pdf>

    <script>
        window.initLibrary = function () {
            return {
                files: @json($files),
                width: 700,
                height: 500,
                selected: [],
                selectedFile: null,
                tooltip: false,
                modalDeleteFiles: false,
                modalWidthAndHeightPdf: false,

                copyLink(file) {
                    let textArea = document.createElement('textarea');
                    let path = `../storage/${file.path}`;

                    if (file.mimetype.startsWith('image')) {
                        textArea.textContent = path;
                    } else {
                        textArea.textContent = `<iframe src="${path}" width="${this.width}" height="${this.height}" frameborder="0" allowfullscreen="allowfullscreen"></iframe>`;
                    }

                    document.body.appendChild(textArea);
                    textArea.select();

                    try {
                        return document.execCommand('copy');
                    } catch (error) {
                        console.warn('Copy to clipboard failed.', error);
                        return false;
                    } finally {
                        document.body.removeChild(textArea);

                    }
                },

                getItems() {
                    let url = `{{ route('uploads.index') }}`;

                    fetch(url)
                        .then((response) => response.json())
                        .then((response) => {
                            this.files = response.data;
                            this.selected = [];
                        })
                        .catch((err) => console.error(err));
                },

                deleteFiles() {
                    let url = `{{ route('uploads.destroy') }}`;

                    fetch(url, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({ data: this.selected }),
                    })
                        .then((response) => response.json())
                        .then((_) => {
                            this.getItems();
                            this.clearSelected();
                            this.modalDeleteFiles = false;
                        })
                        .catch((err) => console.error(err));
                },

                addOrRemoveFrom(file) {
                    let inArray = this.selected.some(
                        item => item.id === file.id
                    );

                    if (inArray) {
                        this.selected = this.selected.filter(
                            item => item.id !== file.id
                        );
                    } else {
                        this.selected.push(file);
                    }
                },

                clearSelected() {
                    this.selected.forEach(file => {
                        let element = document.getElementById(`file-${file.id}`);
                        element.checked = false;
                    });

                    this.selected = [];
                },
            }
        }
    </script>
</div>

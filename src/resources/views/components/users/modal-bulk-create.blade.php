@props(['clickoutside'])

<x-modal name="modalBulkRegistration" class="w-2/3 md:w-1/3" :clickoutside="$clickoutside">
    <x-slot name="title">Inserir usuários em massa</x-slot>
    <div class="px-4 py-3">
        <form id="form-bulk-registration" method="POST" action="{{ route('users.import') }}"
              enctype="multipart/form-data">
            @csrf
            <!-- Arraste e solte -->
            <div class="relative overflow-hidden mt-1 px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md text-center text-gray-700"
                 x-data="{ file: null }">
                 <input class="absolute h-full left-0 top-0 w-full cursor-pointer outline-none opacity-0 m-0 p-0"
                       id="file-bulk-registration"
                       name="file_bulk_registration"
                       type="file"
                       required
                       @change="file = $event.target.files[0];">
                <template x-if="file === null">
                    <div>
                        <i class="fa-regular fa-file fa-3x"></i>
                        <p>Clique para enviar o arquivo</p>
                        <p>ou arraste e solte aqui</p>
                        <p class="text-xs text-gray-500">CSV ou XLSX</p>
                    </div>
                </template>
                <template x-if="file !== null">
                    <div>
                        <span class="font-medium text-gray-900" x-text="file.name"></span>
                        <span class="text-xs self-end text-gray-500" x-text="(file.size / (1024 * 1024)).toFixed(2) + 'MB'"></span>
                    </div>
                </template>
            </div>
            <!-- Dropdown com templates -->
            <div class="container mx-auto px-4 flex items-center justify-between py-4">
                <div class="relative" x-data="{ open: false }">
                    <button class="flex items-center" type="button" @click="open = !open">
                        Baixar template
                        <i class="fa-solid fa-angle-down ml-2"></i>
                    </button>
                    <ul class="absolute font-normal bg-white shadow overflow-hidden rounded w-48 border mt-2 py-1 right-0 z-20"
                        x-show="open" @click.outside="open = false">
                        <li>
                            <a class="flex items-center px-3 py-3 hover:bg-gray-200 text-gray-700"
                               href="{{ asset('templates/registro_de_usuario_template.csv') }}" download>
                                <span class="ml-2">CSV</span>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center px-3 py-3 hover:bg-gray-200 text-gray-700"
                               href="{{ asset('templates/registro_de_usuario_template.xlsx') }}" download>
                                <span class="ml-2">XLSX (Excel)</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Botões -->
            <div class="flex mt-3 flex-row justify-end">
                <x-button type="button" @click="modalBulkRegistration = false;" class="button-red m-2">
                    Cancelar
                </x-button>
                <x-button type="submit" class="button-green m-2" >
                    Enviar
                </x-button>
            </div>
        </form>
    </div>
</x-modal>

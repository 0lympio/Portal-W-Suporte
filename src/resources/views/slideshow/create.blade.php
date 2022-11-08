<x-app-layout>
    <x-title class="mb-0"></x-title>
    <div class="bg-white rounded p-4" x-data="init()" x-cloak>
        <form method="POST" action="{{ route('slideshow.store') }}">
            @csrf

            <div class="flex flex-col md:flex-row">
                <div class="w-full mx-2 flex-1">
                    <x-label for="link" value="Link (opcional)"></x-label>
                    <x-input class="input-form placeholder:text-gray-400 placeholder:text-right" type="text"
                        id="link" name="link" maxlength="255" placeholder="255"
                        value="{{ old('link') }}" />
                </div>
            </div>
            <div class="flex flex-col md:flex-row mt-3 w-1/2">
                <div class="w-full ml-2 flex-1">
                    <x-label for="published_at" value="Data de agendamento"></x-label>
                    <x-input class="input-form placeholder:text-gray-400 placeholder:text-right" type="datetime-local"
                        id="published_at" name="published_at" required maxlength="255"
                        value="{{ now('America/Sao_Paulo')->format('Y-m-d H:i') }}"
                        min="{{ now('America/Sao_Paulo')->format('Y-m-d H:i') }}" />
                </div>
            </div>
            <div x-data="{ open: false }" class="mt-3 mx-2">
                <div>
                    <x-label for="disabled_at" class="mr-2" display="inline-flex">
                        Adicionar data de término da postagem?
                    </x-label>
                    <x-input name="disabled_at" id="disabled_at" type="checkbox" @click="open = ! open"
                        class="focus:ring-red-semparar h-4 w-4 text-red-semparar border-gray-300 rounded">
                    </x-input>
                </div>
                <template x-if="open">
                    <div class="flex flex-col md:flex-row mt-3 w-1/2">
                        <div class="w-full flex-1">
                            <x-label for="disabled_at" value="Data de término"></x-label>
                            <x-input class="input-form placeholder:text-gray-400 placeholder:text-right"
                                type="datetime-local" id="disabled_at" name="disabled_at" maxlength="255"
                                min="{{ now('America/Sao_Paulo')->format('Y-m-d H:i') }}" />
                        </div>
                    </div>
                </template>
            </div>
            <div class="flex flex-col md:flex-row mt-3">
                <div class="w-full mx-2 flex-1">
                    <x-label for="image" value="Destaque"></x-label>
                    <div class="relative w-[30rem] h-56">
                        <img class="object-cover w-full h-full rounded-lg"
                            :src="thumbnail === null ? 'https://placehold.co/900x900/e2e8f0/e2e8f0' : '../../storage/' +
                                thumbnail" />
                        <input type="hidden" name="image" :value="thumbnail">
                        <div class="absolute cursor-pointer top-[36%] left-[43%]" @click="modalSetThumbnail = true">
                            <button type="button" class="text-6xl text-gray-700 hover:text-black">
                                <i class="fa-regular fa-image"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex p-2 mt-4">
                <x-button type="button" class="button-red">
                    <a href="{{ route('slideshow.index') }}">Cancelar</a>
                </x-button>
                <div class="flex-auto flex flex-row-reverse">
                    <x-button class="button-green" type="submit">
                        Finalizar
                    </x-button>
                </div>
            </div>
        </form>

        <x-posts.modal-set-thumbnail></x-posts.modal-set-thumbnail>
    </div>

    <script>
        function init() {
            return {
                thumbnail: null,
                modalSetThumbnail: false,
                selectedImageInformation: null,
                selectedImage: null,
                images: @json($images),

            }
        }
    </script>
</x-app-layout>

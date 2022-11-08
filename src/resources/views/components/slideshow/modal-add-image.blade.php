{{-- <x-modal name="modalAddImage" class="w-2/3">
    <x-slot name="title">
        Escolha as imagens e configure o tempo
    </x-slot>
    <div class="overflow-y-auto h-[70vh] px-2 pt-2">
        <form method="POST" action="{{ route('slideshow.addImages') }}">
            @csrf
            <div class="flex flex-row flex-wrap">
                @forelse ($images as $image)
                    <div class="px-4 pt-4 w-1/3">
                        <div class="hover:shadow-xl shadow h-full rounded-md overflow-hidden bg-gray-100 transition-all duration-400 scale-100 hover:scale-95">
                            <label for="image-checkbox-{{ $loop->iteration }}" class="cursor-pointer">
                                <span for="image-checkbox-{{ $loop->iteration }}">
                                    <img class="lg:h-60 md:h-36 w-full object-cover object-center" src="{{ Storage::url($image->path) }}" alt="blog">
                                </span>
                            </label>

                            <div class="px-6 pt-2">
                                <h2 class="tracking-widest text-xs font-medium text-gray-400 mb-1">{{ $image->created_at->format('d/m/Y H:i') }}</h2>
                                <h1 class="text-sm font-medium text-gray-600 mb-3">{{ $image->name }}</h1>

                                <div class="flex justify-center mt-2 mb-2">
                                    <input @checked($slides->has($image->id))
                                           type="checkbox"
                                           id="image-checkbox-{{ $loop->iteration }}"
                                           name="slide-images[]"
                                           value="{{ serialize($image->only(['path', 'id'])) }}"
                                           class="focus:ring-red-700 h-5 w-6 text-red-ring-red-700 border-gray-300 rounded" />
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex justify-center w-full h-full mt-10">
                        <div class="text-center text-gray-400">
                            <i class="fa-regular fa-image fa-5x"></i>
                            <p class="mt-4 text-2xl">Não há imagens na sua biblioteca de mídia</p>
                        </div>
                    </div>
                @endforelse
            </div>

            @if ($images->isNotEmpty())
                <div class="flex sticky bottom-0 bg-white justify-end text-center mt-6">
                    <div class="m-4">
                        <x-button type="submit" class="button-green">
                            Adicionar imagens
                        </x-button>
                    </div>
                </div>
            @endif
        </form>
    </div>
</x-modal> --}}

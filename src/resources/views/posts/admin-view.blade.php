<x-app-layout>
    <x-title>
        <x-slot name="title">Pré-visualização</x-slot>
    </x-title>
    <div class="flex flex-col justify-center mt-2">
        <div id="post" class="w-full px-6 py-4 bg-white rounded overflow-hidden shadow-md ring-1 ring-gray-900/10">
            <div id="toPrint">
                <p class="font-bold text-3xl prose text-center">{{ $post->title }}</p>
                <div class="mt-4 text-gray-400 flex justify-between text-sm">
                    <p>Publicado por {{ $post->user->name }} {{ $post->user->last_name }} em
                        {{ \Carbon\Carbon::parse($post->published_at)->format('d/m/Y') }} às
                        {{ \Carbon\Carbon::parse($post->published_at)->format('H:i') }}
                    </p>
                    <p>Última atualização por {{ $post->lastModifiedBy->name }}
                        {{ $post->lastModifiedBy->last_name }} em
                        {{ \Carbon\Carbon::parse($post->updated_at)->format('d/m/Y') }} às
                        {{ \Carbon\Carbon::parse($post->updated_at)->format('H:i') }}
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
            <div class="flex items-center justify-between mt-6">
                <a href="{{ route('posts.index') }}">
                    <x-button type="button" class="bg-gray-semparar hover:bg-gray-semparar text-white">
                        Voltar
                    </x-button>
                </a>
                @if ($enableButton)
                    <form action="{{ route('admin.publish', $post->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <x-button type="submit" class="button-green hover:bg-gray-semparar text-white">
                            Publicar
                        </x-button>
                    </form>
                @endif
            </div>
        </div>
    </div>

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
    </style>
</x-app-layout>

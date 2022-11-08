<x-app-layout>
    <x-home.banner></x-home.banner>
    <div class="flex flex-row mt-4 content-between">
        <x-slideshow :slides="$slides"></x-slideshow>
        <div class="flex flex-col items-center ml-5 w-2/6">
            <x-home.card :link="route('faqs.index')"
                image="{{ Storage::url($imagesHome->where('type', 'Fique ON')->first()->path) }}">

                <x-slot name="title">Fique on</x-slot>

                <ul class="text-xs pt-0">
                    <li class="m-2 mt-0 ">
                        <p class="line-clamp-3 overflow-hidden">{{ $faq_posts[0]->title ?? '' }}</p>
                    </li>
                    <li
                        class="absolute bottom-3 right-1 bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-200 dark:text-green-900">
                        Veja mais</li>
                </ul>
            </x-home.card>
            <x-home.card :link="route('questionnaires.open')"
                image="{{ Storage::url($imagesHome->where('type', 'Enquetes')->first()->path) }}">

                <x-slot name="title">Enquetes</x-slot>

                <ul class="text-xs pt-0">
                    <li class="m-2 mt-0 ">
                        <p class="line-clamp-2 overflow-hidden">{{ $quest_post->name ?? '' }}</p>
                    </li>

                    @if (isset($quest_post->name))
                        <span
                            class="absolute bottom-3 right-1 bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-red-200 dark:text-red-900">
                            Novo
                        </span>
                    @endif
                </ul>
            </x-home.card>
            <x-home.card :link="route('content.show', 'treinamentos')"
                image="{{ Storage::url($imagesHome->where('type', 'Treinamentos')->first()->path) }}">

                <x-slot name="title">Treinamentos</x-slot>

                <ul class="text-xs pt-0">
                    <li class="m-2 mt-0 ">
                        <p class="line-clamp-2 overflow-hidden">{{ $training_post->title ?? '' }}</p>
                    </li>
                    @if (isset($training_post))
                        <span
                            class="absolute bottom-3 right-1 bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-red-200 dark:text-red-900">
                            Novo
                        </span>
                    @endif
                </ul>
            </x-home.card>
        </div>
    </div>
</x-app-layout>

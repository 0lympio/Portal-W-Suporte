@props(['questionnaire'])

<div class="p-4 w-80">
    <div class="hover:shadow-xl shadow h-full rounded-xl overflow-hidden">
        <a href="{{ route('questionnaires.show', $questionnaire->id) }}">
            <div class="w-80">
                <img class="h-36 w-full object-cover object-center scale-110 transition-all duration-400 hover:scale-100"
                    src="{{ Storage::url($questionnaire->thumb) }}" alt="blog">
            </div>
        </a>
        <div class="p-6 bg-white">
            <h1 class="title-font post-title xl:h-12 xl:line-clamp-2 line-clamp-1 ">{{ $questionnaire->name }}</h1>
            <div class="flex items-center justify-between flex-wrap border-t-2 h-full">
                <a href="{{ route('questionnaires.show', $questionnaire->id) }}"
                    class="mt-2 bg-gradient-to-r from-red-300 to-red-500 hover:scale-105  shadow-cla-blue px-4 py-1 rounded-lg">
                    Responder
                </a>
            </div>
        </div>
    </div>
</div>

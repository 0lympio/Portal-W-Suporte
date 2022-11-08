<x-app-layout>
    <h1 class="text-gray-semparar flex- flex-row items-center align-center">
        <span class="text-3xl">
            <i class="fa-solid fa-clipboard-question mx-2"></i>
            Enquete:
        </span>
        <span class="text-xl">{{ $questionnaire->name }}</span>
    </h1>
    <x-questionnaires :questions="$questionnaire->questions" :questionnaire="$questionnaire"></x-questionnaires>
</x-app-layout>

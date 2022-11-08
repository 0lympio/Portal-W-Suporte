<x-app-layout>
    <h1 class="text-xl text-gray-semparar">Enquete</h1>
    @if ($questionnaires->count() === 0)
        <x-no-data name="Enquetes" />
    @else
        <div class="flex flex-row flex-wrap">
            @foreach ($questionnaires as $questionnaire)
                <x-questionnaires.questionnaire-card :questionnaire="$questionnaire" />
            @endforeach
        </div>
    @endif
</x-app-layout>

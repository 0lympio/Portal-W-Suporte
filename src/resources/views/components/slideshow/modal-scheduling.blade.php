<x-modal name="modalScheduling" class="w-1/3 xl:w-1/4">
    <x-slot name="title">Alterar data de agendamento e término</x-slot>
    <div class="items-center px-4">
        <form method="POST" action="{{ route('slideshow.scheduling') }}">
            @csrf
            @method('PUT')

            <div class="mt-4 mb-4">
                <div class="w-full mx-2 flex-1">
                    <x-label for="published_at" value="Data de agendamento"></x-label>
                    <x-input class="input-form" type="datetime-local" id="published_at" name="published_at" required
                        maxlength="255" value="{{ now('America/Sao_Paulo')->format('Y-m-d H:i') }}" x-model="item.published_at"
                        min="{{ now('America/Sao_Paulo')->format('Y-m-d H:i') }}" />
                </div>

                <div x-data="{ open: false }">
                    <div class="mt-3 mx-2">
                        <x-label for="disabled_at" class="mr-2" display="inline-flex">
                            Adicionar data de término da postagem?
                        </x-label>
                        <x-input name="disabled_at" id="disabled_at" type="checkbox" @click="open = ! open" x-model="item.disabled_at"
                            class="focus:ring-red-semparar h-4 w-4 text-red-semparar border-gray-300 rounded"
                            :min="item.disabled_at ? date('Y-m-d H:i', strtotime($post->disabled_at)) : {{ now()->format('Y-m-d H:i') }}">
                        </x-input>
                    </div>

                    <template x-if="open">
                        <div class="w-full mt-4 flex-1 mx-2">
                            <x-label for="disabled_at" value="Data de término"></x-label>
                            <x-input class="input-form" type="datetime-local" id="disabled_at" name="disabled_at" required
                                maxlength="255" min="{{ now('America/Sao_Paulo')->format('Y-m-d H:i') }}" />
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex flex-row justify-end mb-2">
                <x-button @click="modalScheduling = false;" type="button" class="button-red">
                    Cancelar
                </x-button>
                <x-button type="submit" class="ml-2 button-green">
                    Confirmar
                </x-button>
            </div>
        </form>
    </div>
</x-modal>



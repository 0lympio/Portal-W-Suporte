@props(['approvalModal'])

<x-modal name="approvalModal" class="w-2/4 xl:w-1/3">
    <x-slot name="title">{{ $slot }}</x-slot>
    <div class="items-center px-4 py-3">
        <form id="form-rejected" method="POST" action="{{ route('approvals.approver') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" :value="aprovedStatus">
            <input type="hidden" name="id" :value="aprovedId">
            <div class="flex flex-row justify-end">
                <x-button @click="approvalModal = false;" type="button" class="button-red">
                    Cancelar
                </x-button>
                <x-button type="submit" class="button-green ml-2">
                    Confirmar
                </x-button>
            </div>
        </form>
    </div>
</x-modal>

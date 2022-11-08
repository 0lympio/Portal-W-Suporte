<div x-show="deleteComment" class="fixed inset-0 z-50 bg-gray-600 bg-opacity-50 h-full w-full">
    <div class="relative top-10 mx-auto p-2 border w-2/5 shadow-lg rounded-md bg-white">
        <div class="flex">
            <div class="flex w-full justify-end text-2xl px-2">
                <button type="button" @click="deleteComment = false;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>

        <h1 class="mb-5 mt-3 text-lg w-full ml-2 text-gray-semparar text-center">Tem certeza que deseja deletar esse
            comentário?</h1>
        <div class="flex flex-row justify-end mb-2">
            <form id="form-rejected" method="POST" :action="`/comments/${commentId}`">
                @csrf
                @method('DELETE')
                <div class="flex flex-row justify-end">
                    <x-button @click="deleteComment = false;" type="button" class="button-red">
                        Cancelar
                    </x-button>
                    <x-button type="submit" class="button-green ml-2">
                        Confirmar
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</div>

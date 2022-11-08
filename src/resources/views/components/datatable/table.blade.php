<div class="bg-white p-5 shadow-md w-full flex flex-col"
     x-init="
        initData();
        hasSearch ? $watch('searchInput', value => { search(value) }) : ''
    ">
    <!-- Cabeçalho -->
    <div class="flex justify-between items-center">
        <template x-if="availableFilters.length > 0">
            <div class="flex justify-start w-full ml-3">
                <div class="flex space-x-2 items-center">
                    <div class="text-gray-700 text-xl">
                        <i class="fa-solid fa-filter"></i>
                    </div>
                    <select class="pr-9 py-1.5 text-base input-datatable" x-model="filter.type" @change="changeFilter()">
                        <template x-for="(item, index) in availableFilters" :key="index">
                            <option :value="item.name" x-text="item.text"></option>
                        </template>
                    </select>
                </div>
                <x-datatable.filters></x-datatable.filters>
            </div>
        </template>

        <template x-if="hasReportFilters">
            <div class="flex justify-start w-full ml-3">
                <x-button @click="filtersModal = true">
                    <div class="flex space-x-2 items-center">
                        <div class="text-gray-700 text-xl">
                            <i class="fa-solid fa-filter"></i>
                        </div>
                    </div>
                </x-button>
            </div>
        </template>

        <template x-if="hasSearch">
            <div class="flex justify-end w-full">
                <x-input class="input-datatable py-1.5 text-lg" x-model="searchInput" type="text" placeholder="Filtrar..."></x-input>
            </div>
        </template>
    </div>
    <!-- Tabela -->
    <table class="mt-5">
        {{ $slot }}
        <tr x-show="isEmpty()">
            <td colspan="5" class="text-center py-3 text-gray-900 text-sm">Nenhum registro correspondente encontrado.
            </td>
        </tr>
    </table>
    <!-- Rodapé -->
    <div class="flex flex-row justify-end p-4">
        <div class="flex space-x-2 items-center mr-5">
            <p>Registros por página</p>
            <select class="pr-8 py-1.5 text-base input-datatable" x-model="view" @change="changeView()">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
        <div class="relative block py-1.5 px-3 cursor-pointer border-0 bg-transparent outline-none transition-all duration-300 rounded text-gray-500  focus:shadow-none"
            @click.prevent="changePage(1)">
            <span>Primeira</span>
        </div>
        <div class="relative block py-1.5 px-3 cursor-pointer border-0 bg-transparent outline-none transition-all duration-300 rounded text-gray-500  focus:shadow-none"
            @click="changePage(currentPage - 1)">
            <span class="text-gray-700"><</span>
        </div>
        <template x-for="item in pages">
            <div @click="changePage(item)"
                 class="relative text-gray-700 cursor-pointer block py-1.5 px-3 border-0  outline-none transition-all duration-300 rounded hover:bg-red-500 shadow-md focus:shadow-md"
                 :class="{ 'bg-red-900 text-white': currentPage === item }">
                <span class="" x-text="item"></span>
            </div>
        </template>
        <div class="relative cursor-pointer block py-1.5 px-3 border-0 bg-transparent outline-none transition-all duration-300 rounded text-gray-800 hover:text-gray-800 hover:bg-gray-200 focus:shadow-none"
            @click="changePage(currentPage + 1)">
            <span>></span>
        </div>
        <div class="relative cursor-pointer block py-1.5 px-3 border-0 bg-transparent outline-none transition-all duration-300 rounded text-gray-800 hover:text-gray-800 hover:bg-gray-200 focus:shadow-none"
            @click.prevent="changePage(pagination.lastPage)">
            <span>Última</span>
        </div>
    </div>
</div>

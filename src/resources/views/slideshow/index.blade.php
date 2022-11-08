<x-app-layout>
    <div x-data="dataTable({{ collect($slides) }})" x-cloak>
        <x-title>
            <x-slot name="title">Destaques</x-slot>
            @canany('slideshow.displayTime', 'slideshow.addImages')
                <x-slot name="buttons">
                    <div class="flex justify-end">
                        @can('slideshow.addImages')
                            <a href="{{ route('slideshow.create') }}">
                                <x-button class="title-button">
                                    Adicionar imagem
                                </x-button>
                            </a>
                        @endcan
                        @can('slideshow.displayTime')
                            <x-button class="title-button ml-2" @click="modalChangeDisplayTime = true;">
                                Alterar tempo de exibição
                            </x-button>
                        @endcan
                    </div>
                </x-slot>
            @endcanany
        </x-title>
        <div class="content">
            <x-datatable.table>
                <thead class="border-b-2">
                    <x-datatable.head order="">Imagem</x-datatable.head>
                    <x-datatable.head order="">Tempo de exibição</x-datatable.head>
                    <x-datatable.head order="">Data de modificação</x-datatable.head>
                    @can('slideshow.destroy')
                        <x-datatable.head order="">Ação</x-datatable.head>
                    @endcan
                </thead>
                <tbody>
                    <template x-for="(item, index) in items" :key="index">
                        <tr x-show="checkView(index + 1)" class="hover:bg-gray-200 text-gray-900 text-xs">
                            <td class="table-col">
                                <div class="w-56">
                                    <div
                                        class="h-full rounded-md overflow-hidden bg-gray-100 transition-all duration-400">
                                        <img class="h-36 w-full object-cover object-center"
                                            :src="'../storage/' + item.path" alt="blog">
                                    </div>
                                </div>
                            </td>
                            <td class="table-col">
                                <span x-text="item.duration"></span><span
                                    x-text="item.duration > 1 ? ' segundos' : ' segundo'"></span>
                            </td>
                            <td class="table-col" x-data="{ date: new Date(item.updated_at) }">
                                <span x-text="date.toLocaleString()"></span>
                            </td>
                            <td class="table-col">
                                @can('slideshow.destroy')
                                    <x-datatable.button @click="deleteModal=true;deleteUrl=`slideshow/${item.id}`"
                                        class="fa fa-trash text-red" />
                                @endcan
                                <a :href="`slideshow/${item.id}/edit`">
                                    <x-datatable.button class="fa fa-edit text-blue" />
                                </a>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </x-datatable.table>
        </div>

        <x-delete-modal>Tem certeza que deseja deletar este destaque?</x-delete-modal>
        <x-slideshow.modal-change-display-time></x-slideshow.modal-change-display-time>
    </div>

    <script>
        window.dataTable = function(data) {
            return {
                deleteModal: false,
                deleteUrl: '',
                hasSearch: false,
                hasFilter: false,
                modalAddImage: false,
                hasReportFilters: false,
                modalChangeDisplayTime: false,
                modalScheduling: false,
                items: [],
                view: 5,
                pages: [],
                offset: 5,
                pagination: {
                    total: data.length,
                    lastPage: Math.ceil(data.length / 5),
                    perPage: 5,
                    currentPage: 1,
                    from: 1,
                    to: 1 * 5
                },
                currentPage: 1,

                initData() {
                    this.items = data
                    this.showPages()
                },

                checkView(index) {
                    return index > this.pagination.to || index < this.pagination.from ? false : true
                },

                checkPage(item) {
                    if (item <= this.currentPage + 5) {
                        return true
                    }
                    return false
                },

                changePage(page) {
                    if (page >= 1 && page <= this.pagination.lastPage) {
                        this.currentPage = page
                        const total = this.items.length
                        const lastPage = Math.ceil(total / this.view) || 1
                        const from = (page - 1) * this.view + 1
                        let to = page * this.view
                        if (page === lastPage) {
                            to = total
                        }
                        this.pagination.total = total
                        this.pagination.lastPage = lastPage
                        this.pagination.perPage = this.view
                        this.pagination.currentPage = page
                        this.pagination.from = from
                        this.pagination.to = to
                        this.showPages()
                    }
                },

                showPages() {
                    const pages = []
                    let from = this.pagination.currentPage - Math.ceil(this.offset / 2)
                    if (from < 1) {
                        from = 1
                    }
                    let to = from + this.offset - 1
                    if (to > this.pagination.lastPage) {
                        to = this.pagination.lastPage
                    }
                    while (from <= to) {
                        pages.push(from)
                        from++
                    }
                    this.pages = pages
                },

                changeView() {
                    this.changePage(1)
                    this.showPages()
                },

                isEmpty() {
                    return this.pagination.total ? false : true
                }
            }
        }
    </script>
</x-app-layout>

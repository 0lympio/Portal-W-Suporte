<x-app-layout>
    <div x-data="dataTable({{ collect($imagesHome) }})" x-cloak>
        <x-title>
            <x-slot name="title">Imagens da home</x-slot>
        </x-title>

        <div class="content">
            <x-datatable.table>
                <thead class="border-b-2">
                    <x-datatable.head order="">Imagem</x-datatable.head>
                    <x-datatable.head order="">Tipo</x-datatable.head>
                    <x-datatable.head order="">Data de modificação</x-datatable.head>
                    <x-datatable.head order="">Ação</x-datatable.head>
                </thead>
                <tbody>
                    <template x-for="(item, index) in items" :key="index">
                        <tr x-show="checkView(index + 1)" class="hover:bg-gray-200 text-gray-900 text-xs">
                            <td class="table-col">
                                <div class="w-56">
                                    <div class="h-full rounded-md overflow-hidden bg-gray-100 transition-all duration-400">
                                        <img class="h-36 w-full object-cover object-center"
                                            :src="'../storage/' + item.path" alt="blog">
                                    </div>
                                </div>
                            </td>

                            <td class="px-10 py-4 whitespace-no-wrap border-b border-gray-200 text-sm">
                                <span x-text="item.type"></span>
                            </td>

                            <td class="px-8 py-4 whitespace-no-wrap border-b border-gray-200 text-sm"
                                x-data="{ date: new Date(item.updated_at) }">
                                <span x-text="date.toLocaleString()"></span>
                            </td>
                            <td class="px-10 py-4 whitespace-no-wrap border-b border-gray-200 text-sm">
                                <x-datatable.button class="fa fa-edit text-blue"
                                    @click="modalAddImage = true; editId = item.id;" />
                            </td>
                        </tr>
                    </template>
                </tbody>
            </x-datatable.table>
        </div>

        <x-home.modal-set-image></x-home.modal-set-image>
    </div>

    <script>
        window.dataTable = function(data) {
            return {
                editId: null,
                hasReportFilters: false,
                hasSearch: false,
                hasFilter: false,
                images: @json($images),
                selected: { index: null, image: null },
                modalAddImage: false,
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

<x-app-layout>
    <div x-data="dataTable({{ collect($questionnaires) }})" x-cloak>
        <x-title>
            <x-slot name="title">Enquetes</x-slot>
        </x-title>
        <x-datatable.table>
            <thead class="border-b-2">
            <x-datatable.head width="50%" order="">Nome</x-datatable.head>
            <x-datatable.head width="50%" order="">Tipo</x-datatable.head>
            </thead>
            <tbody>
            <template x-for="(item, index) in items" :key="index">
                <tr class="hover:bg-gray-200 text-gray-900 text-sm"
                    x-show="checkView(index + 1)">
                    <td class="table-col cursor-pointer" @click="location.href += `/${item.id}`">
                        <span x-text="item.name" class="w-1/2"></span>
                    </td>
                    <td class="table-col cursor-pointer" @click="location.href += `/${item.id}`">
                        <span x-text="item.type" class="w-1/2"></span>
                    </td>
                </tr>
            </template>
            </tbody>
        </x-datatable.table>
    </div>

    <script>
        window.dataTable = function (data) {
            return {
                hasSearch: true,
                hasFilter: false,
                searchInput: '',
                hasReportFilters: false,
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
                    this.items = data;
                    this.showPages();
                    console.log(this.items)
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

                search(value) {
                    if (value.length > 0) {
                        const options = {
                            includeScore: true,
                            useExtendedSearch: true,
                            shouldSort: true,
                            keys: ['name'],
                            threshold: 0
                        }
                        const fuse = new Fuse(data, options)
                        this.items = fuse.search(value).map(elem => elem.item)
                    } else {
                        this.items = data
                    }
                    this.changePage(1)
                    this.showPages()
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

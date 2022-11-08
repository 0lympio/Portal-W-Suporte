<x-app-layout>
    <div x-data="dataTable({{ collect($data) }})" x-cloak>
        <div>
            <x-title>
                <x-slot name="title">Relatório de Postagens</x-slot>
                <x-slot name="buttons">
                    <div class="flex justify-end">
                        <x-button @click="exportar()"
                                  class="title-button"><i class="fa-solid fa-file-excel p-1"></i>Exportar
                        </x-button>
                    </div>
                </x-slot>
            </x-title>
            <div class="content">
                <x-datatable.table>
                    <thead class="border-b-2">
                        <x-datatable.head width="20%" order="">Titulo</x-datatable.head>
                        <x-datatable.head width="20%" order="">Data publicação</x-datatable.head>
                        <x-datatable.head width="20%" order="">Data termino</x-datatable.head>
                        <x-datatable.head width="10%" order="">Tipo</x-datatable.head>
                        <x-datatable.head width="10%" order="">Leituras</x-datatable.head>
                        <x-datatable.head width="10%" order="">Visualizações</x-datatable.head>
                        <x-datatable.head width="20%" order="">Responsável</x-datatable.head>
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in items" :key="index">
                            <tr class="hover:bg-gray-200 text-gray-900 text-sm"
                                x-show="checkView(index + 1)">
                                <td class="table-col">
                                    <span x-text="item.title"></span>
                                </td>
                                <td class="table-col">
                                    <span x-text="item.published_at"></span>
                                </td>
                                <td class="table-col">
                                    <span x-text="item.disabled_at"></span>
                                </td>
                                <td class="table-col">
                                    <span x-text="item.type"></span>
                                </td>
                                <td class="table-col">
                                    <span x-text="item.readings"></span>
                                </td>
                                <td class="table-col">
                                    <span x-text="item.views"></span>
                                </td>
                                <td class="table-col">
                                    <span x-text="item.user"></span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </x-datatable.table>
            </div>
        </div>
    </div>

    <script>
        window.dataTable = function (data) {
            return {
                modalAddQuiz: false,
                deleteModal: false,
                hasReportFilters: false,
                deleteUrl: '',
                hasSearch: true,
                availableFilters: [
                    { name: 'all', text: 'Todos' },
                    { name: 'categories', text: 'Por tipo' },
                    { name: 'specific_date', text: 'Por data de publicação' },
                ],
                filter: {
                    type: 'all',
                    start: '',
                    end: '',
                    element: 'all',
                },
                searchInput: '',
                companies: [],
                types: @json($categories),
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
                            keys: ['title'],
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

                exportar(){
                    let url = `{{ route('reports.posts.export') }}`;

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ data: this.items }),
                    })
                        .then((res) => { return res.blob(); })
                        .then((data) => {
                            let link = document.createElement('a');
                            link.href = URL.createObjectURL(data);
                            link.download = 'postagens.xlsx';
                            link.click();
                        })
                        .catch(err => console.error(err));
                },

                changeFilter() {
                    if (this.filter.type === 'all') {
                        this.items = data;
                        this.changeView();
                    }
                },

                changeDateFilter() {
                    let startDate = (new Date(this.filter.start)).getTime()
                    let endDate = (new Date(this.filter.end)).getTime();

                    this.items = data;

                    this.items = this.items.filter(item => {
                        let [ date ] = item.published_at.split(' ');
                        let [ day, month, year ] = date.split('/');

                        let milliseconds = (new Date(`${year}-${month}-${day}`)).getTime();

                        return milliseconds >= startDate && milliseconds <= endDate;
                    });
                },

                changeTypeFilter() {
                    this.items = data;

                    if (this.filter.element !== 'all') {
                        this.items = this.items.filter(
                            item => item.type === this.filter.element
                        );
                    }

                    this.changeView();
                },

                isEmpty() {
                    return this.pagination.total ? false : true
                }
            }
        }
    </script>
</x-app-layout>

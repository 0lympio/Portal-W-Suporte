<x-app-layout>
    <div x-data="dataTable({{ collect($data) }})" x-cloak>
        <div>
            <x-title>
                <x-slot name="title">Relatório de faturamento</x-slot>
                <x-slot name="buttons">
                    <div class="flex justify-end">
                        <x-button @click="exportar()" class="title-button">
                            <i class="fa-solid fa-file-excel p-1"></i>
                            Exportar
                        </x-button>
                    </div>
                </x-slot>
            </x-title>
            <div class="content">
                <x-datatable.table>
                    <thead class="border-b-2 text-center">
                        <x-datatable.head width="20%" order="" class="justify-center" :justifyCenter="true">Assessoria</x-datatable.head>
                        <x-datatable.head width="20%" order="" class="justify-center" :justifyCenter="true">Mês</x-datatable.head>
                        <x-datatable.head width="20%" order="" class="justify-center" :justifyCenter="true">Ativos</x-datatable.head>
                        <x-datatable.head width="20%" order="" class="justify-center" :justifyCenter="true">Inativos</x-datatable.head>
                        <x-datatable.head width="20%" order="" class="justify-center" :justifyCenter="true">Total</x-datatable.head>
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in items" :key="index">
                            <tr class="hover:bg-gray-200 text-gray-900 text-sm text-center" x-show="checkView(index + 1)">
                                <td class="table-col">
                                    <span x-text="item.company"></span>
                                </td>
                                <td class="table-col">
                                    <span x-text="new Date(item.month).toLocaleString()"></span>
                                </td>
                                <td class="table-col">
                                    <span x-text="item.active"></span>
                                </td>
                                <td class="table-col">
                                    <span x-text="item.inactive"></span>
                                </td>
                                <td class="table-col">
                                    <span x-text="item.total"></span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </x-datatable.table>
            </div>
        </div>

        <x-reports.filters.billing :clickoutside="false" :companies="$companies"></x-reports.filters.billing>
    </div>

    <script>
        window.dataTable = function(data) {
            return {
                hasSearch: false,
                searchInput: '',
                filtersModal: false,
                hasReportFilters: true,
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

                exportar() {
                    let url = `{{ route('reports.billing.export') }}`;

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                data: this.items
                            }),
                        })
                        .then((res) => {
                            return res.blob();
                        })
                        .then((data) => {
                            let link = document.createElement('a');
                            link.href = URL.createObjectURL(data);
                            link.download = 'faturamento.xlsx';
                            link.click();
                        })
                        .catch(err => console.error(err));
                },

                changeFilter() {
                    let company = document.querySelector(`[name='company']`).value;
                    let startDate = document.querySelector(`[name='startDate']`).value;
                    let endDate = document.querySelector(`[name='endDate']`).value;

                    let params = {
                        company,
                        startDate,
                        endDate
                    };

                    let url = `{{ route('reports.billing.filter') }}`;

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                ...params
                            }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            this.items = data;
                            this.filtersModal = false;
                            this.changeView();
                        })
                        .catch(err => console.error(err));
                },

                isEmpty() {
                    return this.pagination.total ? false : true
                }
            }
        }
    </script>
</x-app-layout>

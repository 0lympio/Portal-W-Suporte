<x-app-layout>
    <div x-data="dataTable({{ collect($data) }})" x-cloak>
        <div>
            <x-title>
                <x-slot name="title">Relatório de login/logout</x-slot>
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
                    <x-datatable.head width="20%" order="">Nome</x-datatable.head>
                    <x-datatable.head width="20%" order="">Username</x-datatable.head>
                    <x-datatable.head width="20%" order="">Assessoria</x-datatable.head>
                    <x-datatable.head width="12%" order="">Último acesso</x-datatable.head>
                    <x-datatable.head width="12%" order="">Entrada</x-datatable.head>
                    <x-datatable.head width="12%" order="">Saída</x-datatable.head>
                    </thead>
                    <tbody>
                    <template x-for="(item, index) in items" :key="index">
                        <tr class="hover:bg-gray-200 text-gray-900 text-sm"
                            x-show="checkView(index + 1)">
                            <td class="table-col">
                                <span x-text="item.name"></span>
                            </td>
                            <td class="table-col">
                                <span x-text="item.username"></span>
                            </td>
                            <td class="table-col">
                                <span x-text="item.company"></span>
                            </td>
                            <td class="table-col">
                                <span x-text="item.date"></span>
                            </td>
                            <td class="table-col">
                                <span x-text="item.entry"></span>
                            </td>
                            <td class="table-col">
                                <span x-text="item.exit"></span>
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
                hasReportFilters: false,
                modalAddQuiz: false,
                deleteModal: false,
                deleteUrl: '',
                hasSearch: true,
                availableFilters: [
                    { name: 'last_7_days', text: 'Últimos 7 dias' },
                    { name: 'users', text: 'Por usuário' },
                    { name: 'companies', text: 'Por assessoria' },
                    { name: 'specific_date', text: 'Por período' },
                ],
                searchInput: '',
                users: @json($users),
                companies: @json($companies),
                items: [],
                view: 5,
                filter: {
                    type: '7',
                    start: '7',
                    end: 'today',
                    element: 'all',
                },
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
                            keys: ['name', 'username'],
                            threshold: 0
                        }
                        const fuse = new Fuse(data, options)
                        this.items = fuse.search(value).map(elem => elem.item)
                    } else {
                        this.items = data
                    }

                    this.changeView();
                },

                changeView() {
                    this.changePage(1)
                    this.showPages()
                },

                exportar() {
                    let url = `{{ route('reports.loginLogout.export') }}`;

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
                            link.download = 'login_logout.xlsx';
                            link.click();
                        })
                        .catch(err => console.error(err));
                },

                changeFilter() {
                    if (this.filter.type === 'last_7_days') {
                        this.changeDateFilter();
                    }
                },

                changeDateFilter() {
                    let queryParams = {};

                    if (this.filter.type !== 'last_7_days') {
                        queryParams = { start: this.filter.start, end: this.filter.end };
                    } else {
                        queryParams = { start: 'last_7_days', end: 'now' };
                    }

                    let url = `{{ route('reports.loginLogout.filter') }}?` + new URLSearchParams(queryParams).toString();

                    fetch(url)
                        .then(response => response.json())
                        .then(response => {
                            this.items = response.data;
                            data = this.items;
                            this.changeView();
                        })
                        .catch(err => console.error(err));
                },

                changeUserFilter() {
                    this.items = data;

                    if (this.filter.element !== 'all') {
                        this.items = this.items.filter(
                            item => item.username === this.filter.element
                        );
                    }

                    this.changeView();
                },

                changeCompanyFilter() {
                    this.items = data;

                    if (this.filter.element !== 'all') {
                        this.items = this.items.filter(
                            item => item.company === this.filter.element
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

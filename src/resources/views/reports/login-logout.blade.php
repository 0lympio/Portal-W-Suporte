<x-app-layout>
    <div x-data="dataTable({{ collect($data) }})" x-cloak>
        <div>
            <x-title>
                <x-slot name="title">Relatório de usuários</x-slot>
                <x-slot name="buttons">
                    <div class="flex justify-end">
                        <x-button @click="exportar()" class="title-button"><i
                                class="fa-solid fa-file-excel p-1"></i>Exportar
                        </x-button>
                    </div>
                </x-slot>
            </x-title>
            <div class="content">
                <x-datatable.table>
                    <thead class="border-b-2">
                        <x-datatable.head width="20%" order="">Nome</x-datatable.head>
                        <x-datatable.head width="20%" order="">Login</x-datatable.head>
                        <x-datatable.head width="20%" order="">Status</x-datatable.head>
                        <x-datatable.head width="12%" order="">Data de cadastro</x-datatable.head>
                        <x-datatable.head width="12%" order="">Perfil</x-datatable.head>
                        <x-datatable.head width="12%" order="">Assessoria</x-datatable.head>
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in items" :key="index">
                            <tr class="hover:bg-gray-200 text-gray-900 text-sm" x-show="checkView(index + 1)">
                                <td class="table-col">
                                    <span x-text="item.name"></span>
                                </td>
                                <td class="table-col">
                                    <span x-text="item.username"></span>
                                </td>
                                <td class="table-col">
                                    <span x-text="item.status"></span>
                                </td>
                                <td class="table-col">
                                    <span x-text="new Date(item.created_at).toLocaleString()"></span>
                                </td>
                                <td class="table-col">
                                    <span x-text="item.profile"></span>
                                </td>
                                <td class="table-col">
                                    <span x-text="item.company"></span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </x-datatable.table>
            </div>
        </div>
        <x-reports.filters.login :clickoutside="false" :companies="$companies" :roles="$roles"></x-reports.filters.login>
    </div>

    <script>
        window.dataTable = function(data) {
            return {
                hasReportFilters: true,
                hasSearch: false,
                filtersModal: false,
                searchInput: '',
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
                    console.log(this.items)
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
                    let params = this.getFilterParameters();

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
                        .then((res) => {
                            return res.blob();
                        })
                        .then((data) => {
                            let link = document.createElement('a');
                            link.href = URL.createObjectURL(data);
                            link.download = 'usuarios.xlsx';
                            link.click();
                        })
                        .catch(err => console.error(err));
                },

                changeFilter() {
                    let url = `{{ route('reports.loginLogout.filter') }}`;
                    let params = this.getFilterParameters();

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

                getFilterParameters() {
                    let company = document.querySelector(`[name='company']`).value;
                    let status = document.querySelector(`[name='status']`).value;
                    let profile = document.querySelector(`[name='profile']`).value;
                    let startDate = document.querySelector(`[name='startDate']`).value;
                    let endDate = document.querySelector(`[name='endDate']`).value;

                    return params = {
                        company,
                        status,
                        profile,
                        startDate,
                        endDate
                    };
                },

                isEmpty() {
                    return this.pagination.total ? false : true
                }
            }
        }
    </script>
</x-app-layout>

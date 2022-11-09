<x-app-layout>
    <div x-data="dataTable({{ collect($companies) }})" x-cloak>
        <div>
            <x-title>
                <x-slot name="title">Lista de assessorias</x-slot>
                <x-slot name="buttons">
                    <div class="flex justify-end">
                        @can(['companies.store'])
                            <x-button @click="createModal = true;" class="title-button">
                                Criar assessoria
                            </x-button>
                        @endcan
                    </div>
                </x-slot>
            </x-title>
            <div class=" content">
                <x-datatable.table>
                    <thead class="border-b-2">
                        <x-datatable.head order="name">Nome</x-datatable.head>
                        <x-datatable.head order="">Descrição</x-datatable.head>
                        <x-datatable.head order="">Data de criação</x-datatable.head>
                        @canany(['companies.edit', 'companies.destroy'])
                            <x-datatable.head order="">Ações</x-datatable.head>
                        @endcanany
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in items" :key="index">
                            <tr x-show="checkView(index + 1)" class="hover:bg-gray-200 text-gray-900 text-xs">
                                <td class="table-col">
                                    <span x-text="item.name"></span> <span x-text="item.last_name"></span>
                                </td>
                                <td class="table-col">
                                    <span x-text="item.description"></span>
                                </td>
                                <td class="table-col">
                                    <span x-text="new Date(item.created_at).toLocaleString()"></span>
                                </td>
                                @canany(['companies.edit', 'companies.destroy'])
                                    <td class="actions">
                                        @can(['companies.edit'])
                                            <x-datatable.button @click="company = item; editModal=true;"
                                                class="fa fa-edit text-blue" />
                                        @endcan
                                        @can(['companies.destroy'])
                                            <x-datatable.button @click="deleteModal=true; deleteUrl=`companies/${item.id}`"
                                                class="fa fa-trash text-red" />
                                        @endcan
                                    </td>
                                @endcanany
                            </tr>
                        </template>
                    </tbody>
                </x-datatable.table>
            </div>
        </div>

        <x-delete-modal>
            Tem certeza que deseja deletar essa assessoria?
            <div class="text-base">
                <i class="fa-solid fa-circle-exclamation"></i>
                Todos os usuários dessa assessoria também serão removidos.
            </div>
        </x-delete-modal>
        <x-companies.modal-create-company :clickoutside="false"></x-companies.modal-create-company>
        <x-companies.modal-edit-company :clickoutside="false"></x-companies.modal-edit-company>
    </div>
    <script>
        window.dataTable = function(data) {
            return {
                deleteUrl: '',
                company: {},
                createModal: false,
                editModal: false,
                deleteModal: false,
                hasSearch: true,
                items: [],
                view: 5,
                searchInput: '',
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
                sorted: {
                    field: 'name',
                    rule: 'asc'
                },

                initData() {
                    this.items = data.sort(this.compareOnKey('name', 'asc'))
                    this.showPages()
                },

                compareOnKey(key, rule) {
                    return function(a, b) {
                        if (key === 'name' || key === 'registration_id' || key === 'email') {
                            let comparison = 0
                            let fieldA = a[key] || '';
                            let fieldB = b[key] || '';
                            if (key !== 'id') {
                                fieldA = fieldA.toUpperCase()
                                fieldB = fieldB.toUpperCase()
                            }
                            if (rule === 'asc') {
                                if (fieldA > fieldB) {
                                    comparison = 1;
                                } else if (fieldA < fieldB) {
                                    comparison = -1;
                                }
                            } else {
                                if (fieldA < fieldB) {
                                    comparison = 1;
                                } else if (fieldA > fieldB) {
                                    comparison = -1;
                                }
                            }
                            return comparison
                        } else {
                            if (rule === 'asc') {
                                return a.year - b.year
                            } else {
                                return b.year - a.year
                            }
                        }
                    }
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

                search(value) {
                    if (value.length > 0) {
                        const options = {
                            includeScore: true,
                            useExtendedSearch: true,
                            shouldSort: true,
                            keys: ['name', 'last_name', 'email'],
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

                sort(field, rule) {
                    this.items = this.items.sort(this.compareOnKey(field, rule))
                    this.sorted.field = field
                    this.sorted.rule = rule
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
                },
            }
        }
    </script>
</x-app-layout>

<x-app-layout>
    <div x-data="dataTable({{ collect($categories) }})" x-cloak>
        <div>
            <x-title>
                <x-slot name="title">Lista de categorias</x-slot>
                <x-slot name="buttons">
                    <div class="flex justify-end">
                        @can(['categories.create'])
                            <x-button @click="createModal = true;" class="title-button">Criar categoria
                            </x-button>
                        @endcan
                    </div>
                </x-slot>
            </x-title>
            <div class=" content">
                <x-datatable.table>
                    <thead class="border-b-2">
                        <x-datatable.head order="name">Categoria</x-datatable.head>
                        <x-datatable.head order="nameFather">Sub-categoria de</x-datatable.head>
                        <x-datatable.head order="created_at">Data de criação</x-datatable.head>
                        @can('categories.status')
                            <x-datatable.head order="status">Status</x-datatable.head>
                        @endcan
                        @canany(['categories.edit', 'categories.destroy'])
                            <x-datatable.head order="" class="">Ações</x-datatable.head>
                        @endcanany
                    </thead>
                    <tbody>
                        @php
                            // var_dump($categories[5]->subCategoria->name);
                        @endphp
                        <template x-for="(item, index) in items" :key="index">
                            <tr x-show="checkView(index + 1)" class="hover:bg-gray-200 text-gray-900 text-xs">
                                <td class="table-col">
                                    <span x-text="item.name"></span> <span x-text="item.last_name"></span>
                                </td>
                                <td class="table-col">
                                    <span x-text="item.sub_categoria.name"></span> <span
                                        x-text="item.last_name_father"></span>
                                </td>
                                <td class="table-col" x-data="{ date: new Date(item.created_at) }">
                                    <span x-text="date.toLocaleString()"></span>
                                </td>
                                @can('categories.status')
                                    <td class="table-col" x-id="['status']">
                                        <div
                                            class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                            <input class="input-checkbox-switch" type="checkbox" name="status"
                                                :id="$id('status')" :checked="item.status == 1" x-model="item.status"
                                                @click="changeStatus(item.id, ! item.status)" />
                                            <label
                                                class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"
                                                :for="$id('status')">
                                            </label>
                                        </div>
                                    </td>
                                @endcan
                                @canany(['categories.edit', 'categories.destroy'])
                                    <td class="actions">
                                        @can('categories.edit')
                                            <x-datatable.button @click="category=item;editModal=true;"
                                                class="fa fa-edit text-blue" />
                                        @endcan
                                        @can('categories.destroy')
                                            <x-datatable.button @click="deleteModal=true;deleteUrl=`categories/${item.id}`"
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
        <x-delete-modal>Tem certeza que deseja deletar essa categoria ?</x-delete-modal>
        <x-categories.modal-create-category :categories="$categories" :clickoutside="false"></x-categories.modal-create-category>
        <x-categories.modal-edit-category :categories="$categories" :clickoutside="false"></x-categories.modal-edit-category>
    </div>
    <script>
        window.dataTable = function(data) {
            return {
                hasSearch: true,
                hasReportFilters: false,
                hasFilter: false,
                deleteModal: false,
                createModal: false,
                editModal: false,
                deleteUrl: '',
                category: {},
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
                    console.log(this.items);
                },
                compareOnKey(key, rule) {
                    return function(a, b) {
                        if (key === 'name') {
                            let comparison = 0
                            let fieldA = a[key];
                            let fieldB = b[key];
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
                changeStatus(id, status) {
                    let url = `{{ route('categories.status') }}/${id}`;

                    console.log(url)
                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                status: status
                            }),
                        })
                        .then(response => response.json())
                        .then(data => console.log(data))
                        .catch(err => console.error(err))
                },
            }
        }
    </script>
</x-app-layout>

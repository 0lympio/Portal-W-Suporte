<x-app-layout>
    <div x-data="dataTable({{ collect($posts) }})" x-cloak>
        <div>
            <x-title>
                <x-slot name="title">Postagens</x-slot>
                <x-slot name="buttons">
                    <div class="flex justify-end">
                        @can('posts.create')
                            <a href="{{ route('posts.create') }}">
                                <x-button class="button-blue">
                                    Nova postagem
                                </x-button>
                            </a>
                        @endcan
                    </div>
                </x-slot>
            </x-title>
            <div class="content">
                <x-datatable.table>
                    <thead>
                        <x-datatable.head order="title" width="30%">Titulo</x-datatable.head>
                        <x-datatable.head order="extras" width="10%">Tipo</x-datatable.head>
                        <x-datatable.head order="published_at" width="20%">Data de publicação</x-datatable.head>
                        <x-datatable.head order="updated_at" width="20%">Data de alteração</x-datatable.head>
                        <x-datatable.head order="disabled_at" width="20%">Data de Remoção</x-datatable.head>
                        @can('posts.status')
                            <x-datatable.head order="status_id" width="10%">Status</x-datatable.head>
                        @endcan
                        @canany('posts.read', 'posts.edit', 'posts.destroy')
                            <x-datatable.head order="" width="10%">Ações</x-datatable.head>
                        @endcanany
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in items" :key="index">
                            <tr x-show="checkView(index + 1)" class="hover:bg-gray-200 text-gray-900 text-xs">
                                <td class="table-col">
                                    <span x-text="item.title"></span> <span x-text="item.last_name"></span>
                                </td>
                                <td class="table-col">
                                    <span x-text="item.extras"></span>
                                </td>
                                <td class="table-col">
                                    <span x-text="new Date(item.published_at).toLocaleString()"></span>
                                </td>
                                <td class="table-col">
                                    <span x-text="new Date(item.updated_at).toLocaleString()"></span>
                                </td>
                                <td class="table-col">
                                    <span
                                        x-text="item.disabled_at ? new Date(item.disabled_at).toLocaleString() : 'Indefinido' "></span>
                                </td>
                                @can('posts.status')
                                    <td class="table-col" x-id="['status']">
                                        <div
                                            class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                            <input class="input-checkbox-switch" type="checkbox" name="status"
                                                :id="$id('status')" :checked="item.status_id == 1"
                                                x-model="item.status_id" @click="changeStatus(item.id, ! item.status_id)" />
                                            <label
                                                class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"
                                                :for="$id('status')">
                                            </label>
                                        </div>
                                    </td>
                                @endcan
                                @canany('posts.read', 'posts.edit', 'posts.destroy')
                                    <td class="actions">
                                        @can('posts.read')
                                            <a :href="`admin/view/${item.id}`">
                                                <x-datatable.button class="fa fa-eye text-green" />
                                            </a>
                                        @endcan
                                        @can('posts.edit')
                                            <a :href="`posts/${item.id}/edit`">
                                                <x-datatable.button class="fa fa-edit text-blue" />
                                            </a>
                                        @endcan
                                        @can('posts.destroy')
                                            <x-datatable.button @click="deleteModal = true;deleteUrl=`posts/${item.id}`"
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
        <x-delete-modal name="deleteModal">Tem certeza que deseja deletar essa postagem?</x-delete-modal>
    </div>
    <script>
        window.dataTable = function(data) {
            return {
                deleteModal: false,
                hasReportFilters: false,
                deleteUrl: '',
                items: [],
                view: 5,
                hasSearch: true,
                hasFilter: false,
                searchInput: '',
                pages: [],
                types: @json($categories),
                offset: 5,
                availableFilters: [{
                        name: 'all',
                        text: 'Todos'
                    },
                    {
                        name: 'categories',
                        text: 'Por tipo'
                    },
                ],
                filter: {
                    type: 'all',
                    element: 'all',
                },
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
                    field: 'title',
                    rule: 'asc'
                },
                initData() {
                    this.items = data.sort(this.compareOnKey(this.sorted.field, this.sorted.rule))

                    this.items.forEach(function(element, index) {
                        if (this[index].disabled_at === null) {
                            this[index].disabled_at = ''
                        }
                        this[index].status_id = this[index].status_id.toString();
                    }, this.items);

                    this.showPages()
                },
                compareOnKey(key, rule) {
                    return function(a, b) {
                        let comparison = 0
                        let fieldA = a[key].toUpperCase();
                        let fieldB = b[key].toUpperCase();

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
                    if (this.filter.element !== 'all') {
                        this.items = data.filter(
                            item => item.extras === this.filter.element
                        )
                    } else {
                        this.items = data
                    }

                    this.items = this.items.filter(
                        item => item.title.toLowerCase().includes(value.toLowerCase())
                    );

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
                    let url = `{{ route('posts.status') }}/${id}`;

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                status_id: status
                            }),
                        })
                        .then(response => response.json())
                        .then(data => console.log(data))
                        .catch(err => console.error(err))
                },

                changeFilter() {
                    if (this.filter.type === 'all') {
                        this.searchInput = '';
                        this.items = data;
                        this.changeView();
                    }
                },

                changeTypeFilter() {
                    this.items = data;

                    if (this.filter.element !== 'all') {
                        this.searchInput = '';

                        this.items = this.items.filter(
                            item => item.extras === this.filter.element
                        );
                    }

                    this.changeView();
                },
            }
        }
    </script>
</x-app-layout>

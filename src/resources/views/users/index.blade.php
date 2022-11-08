<x-app-layout>
    <div x-data="dataTable({{ collect($users) }})" x-cloak>
        <div>
            <x-title>
                <x-slot name="title">Lista de usuários</x-slot>
                @canany(['users.store', 'users.import'])
                    <x-slot name="buttons">
                        <div class="flex justify-end">
                            @can(['users.store'])
                                <x-button @click="createModal = true;" class="title-button">Criar usuário
                                </x-button>
                            @endcan
                            @can(['users.import'])
                                <x-button class="title-button ml-2" @click="modalBulkRegistration = true;">
                                    Registro em massa
                                </x-button>
                            @endcan
                        </div>
                    </x-slot>
                @endcanany
            </x-title>
            <div class=" content">
                <x-datatable.table>
                    <thead class="border-b-2">
                        <x-datatable.head order="name">Nome</x-datatable.head>
                        <x-datatable.head order="">Assessoria</x-datatable.head>
                        <x-datatable.head order="">Perfil</x-datatable.head>
                        <x-datatable.head order="">Data de criação</x-datatable.head>
                        @can(['users.status'])
                            <x-datatable.head order="">Ativo</x-datatable.head>
                        @endcan
                        @canany(['users.update', 'users.destroy', 'users.changePassword'])
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
                                    <span x-text="item.company.name"></span>
                                </td>

                                <td class="table-col">
                                    <span x-text="item.roles[0].name"></span>
                                </td>
                                <td class="table-col">
                                    <span x-text="new Date(item.created_at).toLocaleString()"></span>
                                </td>
                                @can(['users.status'])
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
                                @canany(['users.update', 'users.destroy', 'users.changePassword'])
                                    <td class="actions">
                                        @can(['users.update'])
                                            <x-datatable.button @click="user=item;editModal=true;"
                                                class="fa fa-edit text-blue" />
                                        @endcan
                                        @can(['users.destroy'])
                                            <x-datatable.button @click="deleteModal=true;deleteUrl=`users/${item.id}`"
                                                class="fa fa-trash text-red" />
                                        @endcan
                                        @can(['users.changePassword'])
                                            <x-datatable.button @click="passwordModal=true;user=item"
                                                class="fa fa-key text-green" />
                                        @endcan
                                    </td>
                                @endcanany
                            </tr>
                        </template>
                    </tbody>
                </x-datatable.table>
            </div>
        </div>

        <x-delete-modal>Tem certeza que deseja deletar esse usuário?</x-delete-modal>
        <x-users.modal-bulk-create :clickoutside="false"></x-users.modal-bulk-create>
        <x-users.modal-create-user :roles="$roles" :companies="$companies" :clickoutside="false"></x-users.modal-create-user>
        <x-users.modal-edit-user :roles="$roles" :companies="$companies" :clickoutside="false"></x-users.modal-edit-user>
        <x-users.modal-password :clickoutside="false"></x-users.modal-password>
    </div>
    <script>
        window.dataTable = function(data) {
            return {
                deleteModal: false,
                createModal: false,
                editModal: false,
                hasReportFilters: false,
                passwordModal: false,
                modalBulkRegistration: false,
                hasSearch: true,
                hasFilter: false,
                deleteUrl: '',
                user: {},
                companies: @json($companies),
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

                generateLogin() {
                    if (this.user.name && this.user.last_name && this.user.company_id) {
                        let name = this.user.name.replace(/\s+/g, '');
                        let last_name = this.user.last_name.split(' ');
                        let company = this.companies.filter((company) => company.id == this.user.company_id);

                        let login =
                            name +
                            (last_name[0] ? last_name[0][0] : "") +
                            (last_name[1] ? last_name[1][0] : "") +
                            "." +
                            company[0].name.split(" ").join("");

                        this.user.username = login.toLowerCase();
                    }
                },

                changeStatus(id, status) {
                    let url = `{{ route('users.status') }}/${id}`;

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
                        .catch(err => console.error(err));
                },
            }
        }

        function createLogin() {
            let name = document.getElementById('name').value;
            let last_name = document.getElementById('last_name').value.split(' ');
            let company = document.getElementById('company_id')
            let loginField = document.getElementById('username')
            let login = name + (last_name[0][0] || '') + (last_name[1][0] || '') + '.' + company.selectedOptions[0]
                .innerText;
            loginField.value = login.toLowerCase();
        }
    </script>
</x-app-layout>

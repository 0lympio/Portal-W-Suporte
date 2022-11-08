<x-app-layout>
    <div x-data="dataTable({{ collect($approvals) }})" x-cloak>
        <div>
            <x-title>
                <x-slot name="title">Lista de Aprovações</x-slot>
            </x-title>
            <div class=" content">
                <x-datatable.table>
                    <thead class="border-b-2">
                        <x-datatable.head order="name">Usuário</x-datatable.head>
                        <x-datatable.head order="">Comentário</x-datatable.head>
                        <x-datatable.head order="">Postagem</x-datatable.head>
                        <x-datatable.head order="">Data de criação</x-datatable.head>
                        @canany(['approvals.approver', 'approvals.rejected'])
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
                                    <span x-html="item.comment"></span>
                                </td>

                                <td class="table-col">
                                    <span x-text="item.title"></span>
                                </td>
                                <td class="table-col">
                                    <span x-text="new Date(item.created_at).toLocaleString()"></span>
                                </td>
                                @canany(['approvals.approver', 'approvals.rejected'])
                                    <td class="table-col">

                                        <div class="flex items-center">
                                            @can('approvals.approver')
                                                <x-datatable.button class="fa-solid fa-check text-blue"
                                                    @click="approvalModal=true;aprovedId=item.id;aprovedStatus=1" />
                                            @endcanany
                                            @can('approvals.rejected')
                                                <x-datatable.button
                                                    @click="approvalModal=true;aprovedId=item.id;aprovedStatus=3"
                                                    class="fa-solid fa-xmark text-red" />
                                            @endcan
                                            {{-- <x-datatable.button @click="commentModal=true;comment=item.comment"
                                            class="fa fa-eye text-green" /> --}}

                                        </div>

                                    </td>
                                @endcanany

                            </tr>
                        </template>
                    </tbody>
                </x-datatable.table>
            </div>

        </div>
        <x-approval-modal name="approvalModal">Tem certeza que deseja atualizar essa pendência?
        </x-approval-modal>
        {{-- <x-comment-modal name="commentModal">Comentario:</x-comment-modal> --}}
    </div>
    <script>
        window.dataTable = function(data) {
            return {
                approvalModal: false,
                hasReportFilters: false,
                // commentModal: false,
                createModal: false,
                editModal: false,
                passwordModal: false,
                modalBulkRegistration: false,
                hasSearch: true,
                hasFilter: false,
                rejectedUrl: '',
                aprovedId: '',
                aprovedStatus: '',
                // comment: '',
                user: {},
                approvals: @json($approvals),
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
                    this.items = data;
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
                        let name = this.user.name;
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

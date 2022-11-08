<x-app-layout>
    <div x-data="dataTable({{ collect($questionnaires) }})" x-cloak>
        <div>
            <x-title>
                <x-slot name="title">Enquetes</x-slot>
                <x-slot name="buttons">
                    <div class="flex justify-end">
                        @can(['questionnaires.create'])
                            <a href="{{ route('questionnaires.create') }}">
                                <x-button class="button-blue">
                                    Nova enquete
                                </x-button>
                            </a>
                        @endcan
                    </div>
                </x-slot>
            </x-title>

            <div class="content">
                <x-datatable.table>
                    <thead class="border-b-2">
                        <x-datatable.head width="70%" order="">Nome</x-datatable.head>
                        @can('questionnaires.status')
                            <x-datatable.head width="10%" order="">Aberto</x-datatable.head>
                        @endcan
                        @canany('questionnaires.edit', 'questionnaires.show', 'questionnaires.destroy')
                            <x-datatable.head width="20%" order="">Ações</x-datatable.head>
                        @endcanany
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in items" :key="index">
                            <tr class="hover:bg-gray-200 text-gray-900 text-sm"
                                x-show="checkView(index + 1)">
                                <td class="table-col">
                                    <span x-text="item.name"></span>
                                </td>
                                @can('questionnaires.status')
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
                                @canany('questionnaires.edit', 'questionnaires.show', 'questionnaires.destroy')
                                    <td class="actions">
                                        @can('questionnaires.show')
                                            <a :href="`questionnaires/${item.id}`" @click="setQuestionnaireView(item.id)">
                                                <x-datatable.button class="fa fa-eye text-green" />
                                            </a>
                                        @endcan
                                        @can('questionnaires.edit')
                                            <a :href="`questionnaires/${item.id}/edit`">
                                                <x-datatable.button class="fa fa-edit text-blue" />
                                            </a>
                                        @endcan
                                        @can('questionnaires.destroy')
                                            <x-datatable.button @click="deleteModal=true;deleteUrl=`questionnaires/${item.id}`"
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
        <x-delete-modal>Tem certeza que deseja excluir essa enquete?</x-delete-modal>
    </div>

    <script>
        window.dataTable = function(data) {
            return {
                modalAddQuiz: false,
                hasReportFilters: false,
                deleteModal: false,
                deleteUrl: '',
                hasSearch: true,
                hasFilter: false,
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
                changeStatus(id, status) {
                    let url = `{{ route('questionnaires.status') }}/${id}`;

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
                        .catch(err => console.error(err));
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
                },

                setQuestionnaireView(id) {
                    let url = "{{ route('questionnaires.view') }}";
                    let data = {
                        questionnaire_id: id
                    };

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(data),
                        })
                        .then(response => response.json())
                        .then(data => console.log(data))
                        .catch(err => console.error(err));
                }
            }
        }
    </script>
</x-app-layout>

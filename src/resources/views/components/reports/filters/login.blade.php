@props(['clickoutside', 'companies', 'roles'])

<x-modal name="filtersModal" class="w-1/3 mb-24" :clickoutside="$clickoutside" x-data="filterValidation()" x-cloak>
    <x-slot name="title">Filtrar</x-slot>
    <div class="flex justify-center">
        <div class="sm:max-w-md mb-4 py-4 sm:rounded-sm p-3 flex justify-center">
            <form method="POST" action="{{ route('reports.loginLogout.filter') }}">
                @csrf
                <div class="mt-4">
                    <x-label for="company" value="Assessoria" />
                    <select name="company" class="input-form select-form">
                        <option value="">Todas</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="mt-4">
                        <x-label for="status" value="Status" />
                        <select name="status" class="input-form select-form">
                            <option value="">Status</option>
                            <option value="1">Ativos</option>
                            <option value="0">inativos</option>
                        </select>
                    </div>

                    <div class="mt-4">
                        <x-label for="profile" value="Tipo de perfil:" />
                        <select name="profile" class="input-form select-form">
                            <option value="">Qualquer</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="grid grid-cols-2 gap-4">
                    <div class="mt-4">
                        <x-label for="startDate" value="Data inicial" />
                        <x-input class="input-form py-1" type="date" name="startDate"
                            @change="setMinimumDateInEndValue()" />
                    </div>

                    <div class="mt-4">
                        <x-label for="endDate" value="Data final" />
                        <x-input class="input-form py-1" type="date" name="endDate"
                            @change="setMaximumDateAtInitialValue()" />
                    </div>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-button type="button" class="button-red" @click="filtersModal=false;">
                        Cancelar
                    </x-button>
                    <x-button class="ml-2 button-green disabled:bg-gray-400 disabled:cursor-default" type="button"
                        id="fernando" @click="changeFilter()" x-bind:disabled="buttonDisabled">
                        Filtrar
                    </x-button>
                </div>
            </form>
        </div>

    </div>
    <script>
        window.filterValidation = function() {
            return {
                buttonDisabled: true,

                invalidDate() {
                    let endDate = document.querySelector(`[name='endDate']`).value;
                    let startDate = document.querySelector(`[name='startDate']`).value;

                    this.buttonDisabled = endDate === '' || startDate === '';
                },

                setMinimumDateInEndValue() {
                    let endDate = document.querySelector(`[name='endDate']`);
                    let startDate = document.querySelector(`[name='startDate']`);
                    endDate.min = startDate.value;

                    this.invalidDate();
                },

                setMaximumDateAtInitialValue() {
                    let endDate = document.querySelector(`[name='endDate']`);
                    let startDate = document.querySelector(`[name='startDate']`);
                    startDate.max = endDate.value;

                    this.invalidDate();
                }
            }
        }
    </script>
</x-modal>

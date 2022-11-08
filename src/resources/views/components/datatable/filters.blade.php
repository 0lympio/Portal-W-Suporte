<div x-show="filter.type === 'specific_date'" x-transition:enter>
    <div class="flex">
        <div class="mb-5 ml-2">
            <x-label for="init" value="Início" class="ml-1" />
            <x-input x-ref="init" id="init" class="input-datatable py-1 text-lg" type="date" name="init" required />
        </div>

        <div class="mb-5 ml-2">
            <x-label for="end" value="Fim" class="ml-1" />
            <x-input id="end" x-ref="end" class="input-datatable py-1 text-lg" type="date" name="end"/>
        </div>

        <div class="mt-4 ml-2">
            <div class="text-indigo-400 w-8 h-8 md:w-10 md:h-10 rounded-md flex items-center justify-center font-bold text-xl font-display cursor-pointer"
                 @click="filter.start = $refs.init.value;
                         filter.end = $refs.end.value;
                         changeDateFilter();">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
        </div>
    </div>
</div>

<div x-show="filter.type === 'specific_month_and_year'" x-transition:enter>
    <div class="flex">
        <div class="mb-5 ml-2">
            <x-label for="monthAndYear" value="Escolha o mês e o ano" class="ml-1" />
            <x-input x-ref="monthAndYear" id="monthAndYear" class="input-datatable py-1 text-base" type="month" name="monthAndYear" required />
        </div>

        <div class="mt-4 ml-2">
            <div class="text-indigo-400 w-8 h-8 md:w-10 md:h-10 rounded-md flex items-center justify-center font-bold text-xl font-display cursor-pointer"
                 @click="filter.monthAndYear = $refs.monthAndYear.value;
                         changeMonthAndYearFilter()">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
        </div>
    </div>
</div>

<div x-show="filter.type === 'users'" x-transition:enter>
    <div class="flex space-x-2 items-center ml-3">
        <select class="pr-9 py-1.5 text-base input-datatable" x-model="filter.element" @change="changeUserFilter()">
            <option value="all">Todos os usuários</option>

            <template x-for="(user, index) in users" :key="index">
                <option :value="user.username">
                    <span x-text="user.name"></span>
                    <span x-text="user.last_name || ''"></span>
                </option>
            </template>
        </select>
    </div>
</div>

<div x-show="filter.type === 'companies'" x-transition:enter>
    <div class="flex space-x-2 items-center ml-3">
        <select class="pr-9 py-1.5 text-base input-datatable" x-model="filter.element" @change="changeCompanyFilter()">
            <option value="all">Todas as assessorias</option>

            <template x-for="(company, index) in companies" :key="index">
                <option :value="company.name">
                    <span x-text="company.name"></span>
                </option>
            </template>
        </select>
    </div>
</div>

<div x-show="filter.type === 'categories'" x-transition:enter>
    <div class="flex space-x-2 items-center ml-3">
        <select class="pr-9 py-1.5 text-base input-datatable" x-model="filter.element" @change="changeTypeFilter()">
            <option value="all">Todos os tipos</option>

            <template x-for="(type, index) in types" :key="index">
                <option :value="type.name">
                    <span x-text="type.name"></span>
                </option>
            </template>
        </select>
    </div>
</div>

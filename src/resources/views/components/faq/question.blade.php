<div>
    <button class="flex items-center justify-between w-full"
        @click="selected != faq.id ? selected = faq.id : selected = null;">

        <i x-show="selected != faq.id" class="fa fa-plus w-4 h-4 text-red"></i>
        <i x-show="selected == faq.id" class="fa fa-minus w-4 h-4 text-red"></i>

        <h1 class="w-full mx-4 text-md text-gray-700 flex text-left" x-html="highlightSearch(faq.title)"></h1>

        <div class="flex justify-end w-52">
            <span x-text="new Date(faq.created_at).toLocaleString()" class="text-xs"></span>
        </div>

    </button>

    <div x-data="{ id: $id('container') }" id="content">
        <div class="overflow-hidden transition-all max-h-0 duration-700 mt-2" :id="id"
            :style="selected == faq.id ? 'max-height: ' + document.querySelector('#' + id).scrollHeight + 'px' : ''"
            x-html="faq.content">
        </div>
    </div>
    <hr class="my-4 border-red-900">
</div>

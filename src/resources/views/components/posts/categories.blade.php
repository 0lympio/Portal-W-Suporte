@props(['categories'])

<div class="flex" x-data=" { show : false }">
    <button @click="show = !show"
            class="flex-shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium text-center text-red-semparar  border  rounded-l-lg"
            type="button">
        <div class="inline-flex items-center">
            <span x-show="!category">Selecione uma categoria</span>
            <span x-show="category" x-html="icon" class="h-3.5 w-3.5 rounded-full mr-2"></span>
            <span x-show="category" x-text="category"></span>
        </div>
    </button>
    <div x-show="show" id="dropdown-states" style="margin: 0px; transform: translate(0px, 42px);position:absolute"
         class="z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow ">
        <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" >
            @foreach ($categories as $category)
                <li>
                    <button class="inline-flex py-2 px-4 w-full text-sm text-red-semparar hover:bg-red-semparar hover:text-white"
                            type="button"
                            @click=" category = '{{$category->name}}'; icon='{{ $category->icon }}'; show= !show ">
                        <div class="inline-flex items-center">
                            <span class="h-3.5 w-3.5 rounded-full mr-2">{!! $category->icon !!}</span>
                            {!! $category->name !!}
                        </div>
                    </button>
                </li>
            @endforeach
        </ul>
    </div>
    <select x-show="category" name="category_id" id="categories"
            class="border text-gray-900 text-sm rounded-r-lg border-gray-300 block w-full p-2.5">
        @foreach ($categories as $category)
            <template x-if="category == '{{$category->name}}'">
                <option value="{{$category->id}}">Atual</option>
            </template>
            @foreach ($category->children as $subcategory)
                <x-posts.subcategories :category="$subcategory"
                                       :parent="$category">{{$category->name}}</x-posts.subcategories>
            @endforeach
        @endforeach
    </select>
</div>

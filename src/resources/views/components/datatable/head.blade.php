@props(['order', 'justifyCenter' => false])

<th
{!! $attributes->merge(['class' => "px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50"]) !!}
>
<div class="flex items-center space-x-2" :class="{'justify-center': {{ $justifyCenter }}}">
    {{$slot}}
    @if($order != "")
    <div class="flex flex-col ml-2">
        <svg @click="sort('{{$order}}', 'asc')" fill="none" fill="none"
             stroke-linecap="round"
             stroke-linejoin="round" stroke-width="4" viewBox="0 0 24 24"
             stroke="currentColor"
             class="text-gray-500 h-3 w-3 cursor-pointer fill-current"
             :class="{'text-blue-500': sorted.field === '{{$order}}' && sorted.rule === 'asc'}">
            <path d="M5 15l7-7 7 7"></path>
        </svg>
        <svg @click="sort('{{$order}}', 'desc')" fill="none" stroke-linecap="round"
             stroke-linejoin="round" stroke-width="4" viewBox="0 0 24 24"
             stroke="currentColor"
             class="text-gray-500 h-3 w-3 cursor-pointer fill-current"
             :class="{'text-blue-500': sorted.field === '{{$order}}' && sorted.rule === 'desc'}">
            <path d="M19 9l-7 7-7-7"></path>
        </svg>
    </div>
        @endif
</div>
</th>

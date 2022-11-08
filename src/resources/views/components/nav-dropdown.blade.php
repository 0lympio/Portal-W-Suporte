@props(['category_children' => [], 'posts' => [], 'ml' => 12, 'text_size' => 'text-md'])

@if (sizeof($category_children) > 0 || sizeof($posts) > 0)
    <ul class="dropdown-menu bg-white text-gray-semparar z-[100] ml-{{ $ml }} {{ $text_size }} shadow-md">
        @foreach ($category_children as $child)
            @if ($child->status == 1)
                <li class="dropdown relative w-44">
                    <x-nav-link href="{{ route('content.show', ['category' => $child->slug]) }}"
                        class="p-2 hover:text-red-semparar font-bold">{{ $child->name }}</x-nav-link>
                    @if (sizeof($child->children) > 0)
                        <x-nav-dropdown :category_children="$child->children->sortBy('name', SORT_NATURAL|SORT_FLAG_CASE)"
                            :posts="$child->posts->sortBy('title', SORT_NATURAL|SORT_FLAG_CASE)" :ml="1" text_size="text-xs" />
                    @else
                        <x-nav-dropdown :posts="$child->posts->where('status_id', '1')->sortBy('title', SORT_NATURAL|SORT_FLAG_CASE)"
                            :ml="12" text_size="text-xs" />
                    @endif
                </li>
            @endif
        @endforeach

        @foreach ($posts as $post)
            @if ($post->isMenu)
                <li class="dropdown relative w-44">
                    <x-nav-link href="{{ route('posts.show', $post->slug) }}" class="p-2 hover:text-red-semparar">
                        &#x2022; {{ $post->title }}</x-nav-link>
                </li>
            @endif
        @endforeach
    </ul>
@endif

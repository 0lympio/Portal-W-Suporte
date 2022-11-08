@props(['category'])

@if ($category->slug !== 'feed-de-noticias')

    @if ($category->posts->where('status_id', 1)->count() === 0)
        <x-no-data :name="$category->name" />
    @else
        @foreach ($category->posts->where('status_id', 1) as $post)
            <x-post-card :post="$post" />
        @endforeach
    @endif
@else
    @if (sizeof($popups) === 0)
        <x-no-data :name="$category->name" />
    @else
        @foreach ($popups as $popup)
            <x-post-card :post="$popup" />
        @endforeach
    @endif
@endif

@props(['post'])

<div class="p-4 w-80">
    <div class="hover:shadow-xl shadow h-full rounded-xl overflow-hidden">
        <a href="{{ route('posts.show', $post->slug) }}">
            <div class="w-80">
                <img class="h-36 w-full object-cover object-center scale-110 transition-all duration-400 hover:scale-100" src="{{ Storage::url($post->thumb) }}" alt="blog">
            </div>
        </a>
        <div class="p-6 bg-white">
            <h2 class="text-xs title-font font-medium text-gray-400 mb-1">{{ $post->category->name }}</h2>
            <h1 class="title-font post-title xl:h-12 xl:line-clamp-2 line-clamp-1 ">{{ $post->title }}</h1>
            <p class="post-description h-14 line-clamp-2">{{ $post->description }}</p>
            <div class="flex items-center justify-between flex-wrap border-t-2 h-full">
                <a href="{{ route('posts.show', $post->slug) }}"
                    class="mt-2 bg-gradient-to-r from-red-300 to-red-500 hover:scale-105  shadow-cla-blue px-4 py-1 rounded-lg">
                    Leia mais
                </a>
                <div class="text-gray-400 text-sm">
                    <span>
                        {{ $post->views->count() }}
                        <i class="fa fa-eye ml-2"></i>
                    </span>
                    <span>
                        {{ $post->views->where('read', 1)->count() }}
                        <i class="fa fa-book-open ml-2"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

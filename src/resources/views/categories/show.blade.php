<x-app-layout>
    <h1 class="text-xl text-gray-semparar">{{ $category->name }}</h1>
    <div class="flex flex-row flex-wrap">
        <x-category-post-card :category="$category" :ml="12" />
    </div>
</x-app-layout>

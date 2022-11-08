@props(['category', 'parent'])

<template x-if="category == '{{ $parent->name }}'">
    <option :selected="post.category_id == {{ $category->id }}" value="{{ $category->id }}">{{ $slot }} > {{ $category->name }}</option>
</template>
@foreach ($category->children as $subcategory)
    <x-posts.subcategories :category="$subcategory" :parent="$parent">{{ $slot }} > {{ $category->name }}</x-posts.subcategories>
@endforeach

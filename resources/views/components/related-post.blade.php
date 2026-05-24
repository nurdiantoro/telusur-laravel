<div class="my-6 rounded-xl border bg-gray-50 p-4">

    <div class="font-bold text-red-600">
        Baca juga:
    </div>

    <a href="{{ route('post.detail', ['category' => $related->category->slug ?? 'opini', 'slug' => $related->slug]) }}"
        class="font-semibold hover:underline">
        {{ $related->title }}
    </a>

</div>

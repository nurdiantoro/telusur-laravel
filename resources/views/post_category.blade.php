@include('layout.header')
<div class="md:w-300 mx-auto px-4 py-8 min-h-[200vh] bg-white">

    <div class="flex flex-col md:flex-row gap-8">

        {{-- Main Content --}}
        <div class="md:w-3/4 flex flex-col">
            <div class="hidden md:flex flex-row gap-2 items-center mb-4">
                <a href="{{ route('home') }}" class="text-sm text-warna-01 font-bold hover:underline">Home</a>
                <span class="w-2 h-auto"><x-fas-angle-right /></span>
                <a href="{{ $category->slug }}" class="text-sm text-gray-600 hover:underline">
                    {{ $category->name }}
                </a>
            </div>

            <div class="flex flex-col gap-4">
                @foreach ($posts as $post)
                    <a href="{{ route('post.detail', ['category' => $category->slug, 'slug' => $post->slug]) }}"
                        class="flex flex-row gap-4 group">
                        <img src="{{ $post->cover_preview ? $post->cover_preview : asset('img/no_image.webp') }}"
                            alt="{{ $post->title }}" class="w-1/3 h-auto object-cover rounded-md">
                        <div class="flex flex-col">
                            <h2 class="font-bold group-hover:text-merah-01">{{ $post->title }}</h2>
                            <time class="text-sm text-gray-500">{{ $post->publish_time->format('F d, Y') }}</time>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-6 mx-auto">
                {{ $posts->onEachSide(1)->links() }}
            </div>

        </div>

        {{-- side div --}}
        <div class="md:w-1/4">
            @include('layout.sidebar')
        </div>
    </div>
</div>
@include('layout.footer')

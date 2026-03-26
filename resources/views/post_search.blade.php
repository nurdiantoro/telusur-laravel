@extends('layout.app')
@section('content')
    <div class="flex flex-col md:flex-row gap-8">

        {{-- Main Content --}}
        <div class="md:w-3/4 flex flex-col">
            <div class="hidden md:flex flex-row gap-2 items-center mb-4">
                <a href="{{ route('home') }}" class="text-sm text-warna-01 font-bold hover:underline">Search</a>
                <span class="w-2 h-auto"><x-fas-angle-right /></span>
                <span href="" class="text-sm text-gray-600 hover:underline">
                    {{ request('search_input') }}
                </span>
            </div>

            <div class="flex flex-col gap-4">
                @foreach ($posts as $post)
                    <a href="{{ route('post.detail', [$post->category->slug ?? 'no_category', $post->slug ?? 'no_slug']) }}"
                        class="flex flex-row gap-4 group">
                        <img src="{{ $post->spatie_preview ? $post->spatie_preview : asset('img/no_image.webp') }}"
                            alt="{{ $post->title }}" class="w-1/3 h-auto object-cover rounded-md">
                        <div class="flex flex-col">
                            <h2 class="font-bold group-hover:text-warna-03">{{ $post->title }}</h2>
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
@endsection

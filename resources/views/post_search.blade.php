@extends('layout.app')
@section('content')
    <div class="flex flex-col gap-8 md:flex-row">

        {{-- Main Content --}}
        <div class="flex flex-col md:w-3/4">
            <div class="mb-4 hidden flex-row items-center gap-2 md:flex">
                <a href="{{ route('home') }}" class="text-warna-01 text-sm font-bold hover:underline">Search</a>
                <span class="h-auto w-2"><x-fas-angle-right /></span>
                <span href="" class="text-sm text-gray-600 hover:underline">
                    {{ request('search_input') }}
                </span>
            </div>

            <div class="flex flex-col gap-4">
                @foreach ($posts as $post)
                    <a href="{{ route('post.detail', [$post->category->slug ?? 'no_category', $post->slug ?? 'no_slug']) }}"
                        class="group flex flex-row gap-4">
                        <img src="{{ $post->gallery?->spatie_preview ?: asset('img/no_image.webp') }}"
                            alt="{{ $post->title }}" class="aspect-video w-1/3 rounded-md object-cover">
                        <div class="flex flex-col">
                            <h2 class="group-hover:text-warna-03 line-clamp-2 font-bold">{{ $post->title }}</h2>
                            <time class="text-sm text-gray-500">{{ $post->publish_time->format('F d, Y') }}</time>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mx-auto mt-6">
                {{ $posts->onEachSide(1)->links() }}
            </div>

        </div>

        {{-- side div --}}
        <div class="md:w-1/4">
            @include('layout.sidebar')
        </div>
    </div>
@endsection

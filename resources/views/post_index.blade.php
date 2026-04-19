@extends('layout.app')
@section('content')
    <div class="flex flex-col gap-8 md:flex-row">

        {{-- Main Content --}}
        <div class="flex flex-col md:w-3/4">
            <div class="mb-4 hidden flex-row items-center gap-2 md:flex">
                <a href="{{ route('home') }}" class="text-warna-01 text-sm font-bold hover:underline">Home</a>
                <span class="h-auto w-2">
                    <x-fas-angle-right />
                </span>
                <a href="{{ $category->slug ?? request()->segment(1) }}" class="text-sm text-gray-600 hover:underline">
                    {{ $category->name ?? request()->segment(1) }}
                </a>
            </div>

            <div class="flex flex-col gap-4">
                @foreach ($posts as $post)
                    <a href="{{ route('post.detail', ['category' => $post->category->slug ?? $post->type, 'slug' => $post->slug]) }}"
                        class="group grid grid-cols-1 items-start gap-6 md:grid-cols-3">

                        <div class="md:col-span-1">
                            <div class="aspect-2/1 w-full overflow-hidden rounded-md bg-gray-100">
                                <img src="{{ $post->gallery?->spatie_thumbnail ??
                                    ($post->type === 'video' && $post->video_url
                                        ? 'https://img.youtube.com/vi/' . $post->video_url . '/hqdefault.jpg'
                                        : asset('img/no_image.webp')) }}"
                                    alt="{{ $post->title }}"
                                    class="h-full w-full rounded-md object-cover transition duration-300 group-hover:scale-105">
                            </div>
                        </div>
                        <div class="flex flex-col justify-center md:col-span-2">
                            <div class="space-y-2">
                                <span class="inline-block w-fit bg-red-600 px-3 py-1 text-xs font-bold text-white">
                                    {{ $post->category->name ?? $post->type }}
                                </span>
                                <h2 class="group-hover:text-warna-03 font-bold">{{ $post->title }}</h2>
                                <time class="text-sm text-gray-500">{{ $post->publish_time->diffForHumans() }}</time>
                            </div>
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

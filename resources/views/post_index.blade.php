@extends('layout.app')
@section('content')
    <div class="mt-3 flex flex-col gap-8 md:mt-0 md:flex-row">

        {{-- Main Content --}}
        <div class="flex flex-col px-4 md:w-3/4 md:px-0">

            {{-- Breadcrumb --}}
            <div class="mb-4 flex flex-row flex-nowrap items-center gap-2">
                <a href="{{ route('home') }}" class="text-warna-01 font-bold hover:underline">Home</a>
                <span>
                    <x-fas-angle-right class="h-2 w-2" />
                </span>
                <a href="{{ $category->slug ?? request()->segment(1) }}" class="text-gray-600 hover:underline">
                    {{ $category->name ?? request()->segment(1) }}
                </a>
            </div>

            <div class="flex flex-col gap-4 md:gap-2">
                @foreach ($posts as $post)
                    <a href="{{ route('post.detail', ['category' => $post->category->slug ?? $post->type, 'slug' => $post->slug]) }}"
                        class="group mb-4 grid grid-cols-1 items-start gap-3 md:grid-cols-3 md:gap-6">

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
                            <div class="flex flex-col gap-2">
                                <span class="inline-block w-fit bg-red-600 px-3 py-1 text-xs font-bold text-white">
                                    {{ $post->category->name ?? $post->type }}
                                </span>
                                <h2 class="group-hover:text-warna-03 mb-0 font-bold">{{ $post->title }}</h2>
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

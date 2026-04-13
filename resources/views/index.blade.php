@extends('layout.app')
@section('content')
    {{-- Search Khusus di home --}}
    <div class="mb-8 w-full rounded-2xl bg-cover bg-center px-2 py-24 text-center"
        style="background-image: url('{{ asset('img/city.webp') }}');">

        <h2 class="mb-4 text-3xl font-bold text-white">Temukan Berita Menarik</h2>

        <form class="mx-auto mb-4 flex max-w-3xl items-center justify-between rounded-lg border border-gray-300 bg-white p-2"
            method="GET" action="{{ route('search') }}">

            <input type="search" required autocomplete="off" placeholder="Netanyahu meninggal..." name="search_input"
                class="mr-3 w-full appearance-none border-none bg-transparent px-2 py-1 leading-tight focus:outline-none" />

            <button type="submit" class="hover:text-warna-03 cursor-pointer p-2 text-gray-500">
                {{-- <x-fas-search class="h-[1em]" /> --}}
            </button>
        </form>

        <div class="flex flex-row flex-wrap justify-center gap-6">
            @foreach ($suggestTags as $suggestTag)
                <a href="{{ route('post.tag', $suggestTag->slug) }}"
                    class="hover:text-warna-03 text-sm text-white">#{{ $suggestTag->slug }}</a>
            @endforeach
        </div>
    </div>

    {{-- Hot news --}}
    <div class="mb-6 hidden flex-row items-center justify-between gap-2 border-b border-gray-300 pb-3 md:flex">
        <div
            class="bg-linear-to-r from-warna-03 to-warna-04 text-nowrap px-2 py-1 text-sm font-bold text-white md:text-base">
            Hot news</div>

        <div class="swiper hotNews hidden min-w-0 flex-1 overflow-hidden">
            <div class="swiper-wrapper">
                @foreach ($beritaUtama as $post)
                    <a href="{{ $post->category->slug . '/' . $post->slug }}"
                        class="swiper-slide hover:text-warna-03 min-w-0 truncate md:text-lg">{{ $post->title }}</a>
                @endforeach
            </div>
        </div>

        <div class="hidden md:flex">
            <button type="button" class="hotNews-prev cursor-pointer text-gray-500 hover:text-gray-700">
                <x-heroicon-o-arrow-left-circle class="h-8" />
            </button>
            <button type="button" class="hotNews-next cursor-pointer text-gray-500 hover:text-gray-700">
                <x-heroicon-o-arrow-right-circle class="h-8" />
            </button>
        </div>
    </div>

    {{-- Main --}}
    <div class="flex flex-col gap-8 md:flex-row">

        {{-- Main Div --}}
        <div class="flex flex-col gap-12 md:w-3/4">

            {{-- Berita Utama Carousel --}}
            <div class="hidden md:block">
                <div class="swiper highlightNews group relative hidden aspect-video w-full overflow-hidden rounded-xl">
                    <div class="swiper-wrapper">
                        @foreach ($beritaUtama as $post)
                            <a href="{{ $post->category->slug . '/' . $post->slug }}" class="swiper-slide">
                                <img src="{{ $post->gallery?->spatie_preview ?: asset('img/no_image.webp') }}"
                                    alt="{{ $post->title }}" class="h-full w-full object-cover" loading="lazy">

                                <div class="bg-linear-to-t absolute inset-0 from-black/60 via-black/20 to-transparent">
                                </div>
                                <div class="absolute bottom-8 left-8 right-8 flex flex-col gap-2 text-white">
                                    <span class="inline-block w-fit bg-red-600 px-3 py-1 text-sm font-bold"
                                        data-swiper-parallax="-90%">
                                        {{ $post->category?->name ?? 'No Category' }}
                                    </span>
                                    <div class="text-lg" data-swiper-parallax-x="100">
                                        {{ $post->title }}
                                    </div>
                                    <div class="text-sm opacity-50" data-swiper-parallax-x="-300"
                                        data-swiper-parallax-duration="600">
                                        {{ $post->publish_time->diffForHumans() }}
                                    </div>
                                </div>

                            </a>
                        @endforeach
                    </div>

                    <div
                        class="absolute -left-2 bottom-0 top-0 z-10 flex items-center justify-center text-white opacity-0 transition-all duration-300 group-hover:left-4 group-hover:opacity-100">
                        <button class="highlightNews-prev cursor-pointer rounded-full bg-black/20 p-3 hover:bg-black/40">
                            <x-heroicon-c-chevron-left class="h-6 text-white" />
                        </button>
                    </div>
                    <div
                        class="absolute -right-2 bottom-0 top-0 z-10 flex items-center justify-center text-white opacity-0 transition-all duration-300 group-hover:right-4 group-hover:opacity-100">
                        <button class="highlightNews-next cursor-pointer rounded-full bg-black/20 p-3 hover:bg-black/40">
                            <x-heroicon-c-chevron-right class="h-6 text-white" /></button>
                    </div>
                </div>
            </div>

            {{-- Berita Utama --}}
            <div>
                <div
                    class="before:bg-warna-01 relative mb-6 border-b border-gray-200 before:absolute before:top-full before:h-1 before:w-16">
                    <h2 class="mb-2 text-2xl font-bold">Berita Utama</h2>
                </div>

                <div x-data="beritaUtama()" x-init="init()">

                    <!-- Skeleton -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2" x-ref="skeleton">
                        @for ($n = 0; $n < 6; $n++)
                            <div class="flex animate-pulse gap-4">
                                <div class="h-20 w-28 rounded-md bg-gray-200"></div>
                                <div class="flex-1 space-y-2">
                                    <div class="h-4 w-1/3 rounded bg-gray-200"></div>
                                    <div class="h-4 w-1/2 rounded bg-gray-200"></div>
                                    <div class="h-4 w-3/4 rounded bg-gray-200"></div>
                                </div>
                            </div>
                        @endfor
                    </div>

                    <!-- Content -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <template x-for="post in apiPosts" :key="post.id">
                            <a :href="(post.category?.slug) + '/' + post.slug" class="group flex items-start gap-4">

                                <!-- Thumbnail -->
                                <div class="shrink-0">
                                    <img :src="post.thumbnail" :alt="post.title"
                                        class="h-20 w-28 rounded-md object-cover transition duration-300 group-hover:scale-105">
                                </div>

                                <!-- Content -->
                                <div class="flex flex-col">

                                    <!-- ✅ langsung pakai -->
                                    <div class="mb-1 text-xs text-gray-500" x-text="post.publish_time"></div>

                                    <h3 class="group-hover:text-warna-03 text-sm font-semibold leading-snug transition"
                                        x-text="post.title">
                                    </h3>
                                </div>
                            </a>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Artikel Terbaru --}}
            <div>
                <div
                    class="before:bg-warna-01 relative mb-6 border-b border-gray-200 before:absolute before:top-full before:h-1 before:w-16">
                    <h2 class="mb-2 text-2xl font-bold">Artikel Terbaru</h2>
                </div>

                <div x-data="beritaTerbaru()" x-init="init()">
                    {{-- Skeleton --}}
                    <div class="grid grid-cols-1 items-stretch gap-6 md:grid-cols-2 lg:grid-cols-3" x-ref="skeleton">
                        @for ($n = 0; $n < 6; $n++)
                            <div class="animate-pulse">
                                <div class="aspect-video w-full rounded-md bg-gray-200"></div>
                                <div class="mt-4 space-y-2">
                                    <div class="h-4 w-1/3 rounded bg-gray-200"></div>
                                    <div class="h-4 w-1/2 rounded bg-gray-200"></div>
                                    <div class="h-4 w-3/4 rounded bg-gray-200"></div>
                                </div>
                            </div>
                        @endfor
                    </div>

                    <!-- Content -->
                    <div class="grid grid-cols-1 items-stretch gap-6 md:grid-cols-2 lg:grid-cols-3">
                        <template x-for="post in apiPosts" :key="post.id">
                            <a :href="post.category.slug + '/' + post.slug" class="group mb-2 flex h-full flex-col">
                                <div class="aspect-video w-full overflow-hidden rounded-md bg-gray-100">
                                    <img :src="post.thumbnail" :alt="post.title"
                                        class="h-full w-full object-cover transition duration-300 ease-out group-hover:scale-105">
                                </div>
                                <div class="mt-4 flex grow flex-col gap-1">
                                    <span class="inline-block w-fit bg-red-600 px-3 py-1 text-xs font-bold text-white"
                                        x-text="post.category.name.toUpperCase()">
                                    </span>
                                    <h2 class="group-hover:text-warna-03 line-clamp-2 text-lg font-bold leading-snug transition"
                                        x-text="post.title">
                                    </h2>
                                    <div class="text-xs text-gray-500" x-text="post.publish_time">
                                    </div>
                                </div>
                            </a>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Berita Video --}}
            <div>
                <div
                    class="before:bg-warna-01 relative mb-6 border-b border-gray-200 before:absolute before:top-full before:h-1 before:w-16">
                    <h2 class="mb-2 text-2xl font-bold">Berita Video</h2>
                </div>

                <div x-data="beritaVideo()" x-init="init()">
                    <div class="grid grid-cols-2 gap-6" x-ref="skeleton">
                        @for ($n = 0; $n < 6; $n++)
                            <div class="animate-pulse">
                                <div class="aspect-video w-full rounded-md bg-gray-200"></div>
                                <div class="mt-4 space-y-2">
                                    <div class="h-4 w-1/3 rounded bg-gray-200"></div>
                                    <div class="h-4 w-1/2 rounded bg-gray-200"></div>
                                    <div class="h-4 w-3/4 rounded bg-gray-200"></div>
                                </div>
                            </div>
                        @endfor
                    </div>
                    <div class="grid grid-cols-2 gap-6">
                        <template x-for="post in apiPosts" :key="post.id">
                            <a :href="post.category.slug + '/' + post.slug"
                                class="group relative flex flex-col items-start gap-4 rounded-xl">

                                {{-- Image --}}
                                <div class="w-full">
                                    <div class="aspect-video w-full overflow-hidden rounded-md bg-gray-100">
                                        <img :src="post.thumbnail" :alt="post.title"
                                            class="h-full w-full object-cover transition duration-300">
                                    </div>
                                </div>

                                {{-- Content --}}
                                <div class="flex flex-col justify-center gap-2">

                                    {{-- Category --}}
                                    <span class="inline-block w-fit bg-red-600 px-3 py-1 text-xs font-bold text-white"
                                        x-text="post.category.name.toUpperCase()">
                                    </span>

                                    {{-- Title --}}
                                    <h2 class="group-hover:text-warna-03 line-clamp-2 text-lg font-bold leading-snug transition"
                                        x-text="post.title">
                                    </h2>

                                    {{-- Author & Date --}}
                                    <div class="text-xs text-gray-500" x-text="post.publish_time">
                                    </div>

                                </div>

                                {{-- Hover Effect --}}
                                <div
                                    class="pointer-events-none absolute inset-0 scale-90 rounded-xl bg-gray-400 opacity-0 transition duration-300 ease-out group-hover:scale-105 group-hover:opacity-10">
                                </div>

                            </a>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Opini --}}
            <div>
                <div
                    class="before:bg-warna-01 relative mb-6 border-b border-gray-200 before:absolute before:top-full before:h-1 before:w-16">
                    <h2 class="mb-2 text-2xl font-bold">Opini</h2>
                </div>
                <div x-data="beritaOpini()" x-init="init()">
                    <div class="flex flex-col gap-6" x-ref="skeleton">
                        @for ($n = 0; $n < 6; $n++)
                            <div class="animate-pulse">
                                <div class="group grid grid-cols-1 items-start gap-6 md:grid-cols-3">

                                    {{-- image --}}
                                    <div class="md:col-span-1">
                                        <div class="aspect-2/1 w-full rounded-md bg-gray-200"></div>
                                    </div>

                                    {{-- content --}}
                                    <div class="flex flex-col justify-center md:col-span-2">
                                        <div class="space-y-2">
                                            <div class="h-4 w-1/3 rounded bg-gray-200"></div>
                                            <div class="h-4 w-1/2 rounded bg-gray-200"></div>
                                            <div class="h-4 w-3/4 rounded bg-gray-200"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                    <div class="flex flex-col gap-6">
                        <template x-for="post in apiPosts" :key="post.id">
                            <a :href="post.category?.slug ? : 'opini' + '/' + post.slug"
                                class="group grid grid-cols-1 items-start gap-6 md:grid-cols-3">

                                {{-- Image --}}
                                <div class="md:col-span-1">
                                    <div class="aspect-2/1 w-full overflow-hidden rounded-md bg-gray-100">
                                        <img :src="post.thumbnail" :alt="post.title"
                                            class="h-full w-full rounded-md object-cover transition duration-300 group-hover:scale-105">
                                    </div>
                                </div>

                                {{-- Content --}}
                                <div class="flex flex-col justify-center md:col-span-2">

                                    {{-- Author & Date --}}
                                    <div class="mb-2 text-xs text-gray-500" x-text="post.publish_time">
                                    </div>

                                    {{-- Title --}}
                                    <h2 class="group-hover:text-warna-03 mb-3 text-xl font-bold leading-snug transition"
                                        x-text="post.title">
                                    </h2>

                                </div>

                            </a>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        {{-- side div --}}
        <aside class="w-full md:w-1/4">
            @include('layout.sidebar')
        </aside>
    </div>
@endsection

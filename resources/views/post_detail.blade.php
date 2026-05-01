@extends('layout.app')
@section('content')

    <div class="flex flex-col gap-8 md:flex-row">
        {{-- Main Content --}}
        <div class="flex flex-col md:w-3/4">

            {{-- Breadcrumb --}}
            <div class="mb-4 flex flex-row items-center gap-2">
                <a href="{{ route('home') }}" class="text-warna-01 text-sm font-bold hover:underline">Home</a>
                <span>
                    <x-fas-angle-right class="h-4 w-4" />
                </span>
                <a href="{{ route('post.category', $post->category->slug ?? $post->type) }}"
                    class="text-sm text-gray-600 hover:underline">
                    {{ $post->category->name ?? $post->type }}
                </a>
            </div>

            <article class="mb-4">
                {{-- Title --}}
                <h1 class="mb-2 text-3xl font-bold">{{ $post->title }}</h1>

                {{-- Meta --}}
                <div class="text-gray-500">
                    <span>by {{ $post->author?->name ?: 'Admin' }}</span> |
                    <time>{{ $post->publish_time->translatedFormat('j F Y') }}</time>
                </div>

                {{-- Cover Image --}}
                <figure class="mb-6 mt-4">
                    @if ($post->type == 'video')
                        <div class="aspect-video w-full">
                            <iframe src="https://www.youtube.com/embed/{{ $post->video_url }}" class="h-full w-full"
                                frameborder="0" allowfullscreen>
                            </iframe>
                        </div>
                    @else
                        <img src="{{ $post->gallery?->spatie_preview ?: asset('img/no_image.webp') }}"
                            alt="{{ $post->title }}" class="h-auto w-full">
                    @endif
                    <figcaption class="text-sm">{{ $post->caption }}</figcaption>

                    <div class="mt-2 flex items-center justify-end">
                        @php
                            $url = urlencode(url()->current());
                            // $title = urlencode($post->title);
                        @endphp
                        <!-- Facebook -->
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $url }}" target="_blank"
                            rel="noopener"
                            class="flex flex-row items-center gap-1 bg-blue-600 px-3 py-2 text-sm text-white hover:bg-blue-700">
                            <div class="h-4 w-4">
                                <x-fab-facebook />
                            </div>
                        </a>

                        <!-- Twitter / X -->
                        <a href="https://twitter.com/intent/tweet?url={{ $url }}&text={{ $post->title }}"
                            target="_blank" rel="noopener"
                            class="flex flex-row items-center gap-1 bg-black px-3 py-2 text-sm text-white hover:bg-gray-800">
                            <div class="h-4 w-4">
                                <x-fab-x-twitter />
                            </div>
                        </a>

                        <!-- WhatsApp -->
                        <a href="https://wa.me/?text={{ $post->title }}%20{{ $url }}" target="_blank"
                            rel="noopener"
                            class="flex flex-row items-center gap-1 bg-green-600 px-3 py-2 text-sm text-white hover:bg-green-700">
                            <div class="h-4 w-4">
                                <x-fab-whatsapp />
                            </div>
                        </a>

                        <!-- Telegram -->
                        <a href="https://t.me/share/url?url={{ $url }}&text={{ $post->title }}" target="_blank"
                            rel="noopener"
                            class="flex flex-row items-center gap-1 bg-sky-500 px-3 py-2 text-sm text-white hover:bg-sky-600">
                            <div class="h-4 w-4">
                                <x-fab-telegram />
                            </div>
                        </a>
                    </div>
                </figure>

                {{--
                |
                |
                |
                |
                |
                |
                |
                |
                |
                --}}
                <div
                    class="article-content prose prose-p:text-neutral-800 prose-h2:text-neutral-800 prose-h3:text-neutral-800 prose-a:text-warna-01 max-w-none">
                    {!! $post->content !!}</div>
                {{--
                |
                |
                |
                |
                |
                |
                |
                |
                |
                --}}

                {{-- Tags --}}
                @if ($post->tags->count() > 0)
                    <div class="mb-6 flex flex-row flex-wrap gap-2 text-gray-700">
                        <div class="flex flex-row items-center justify-center gap-2">
                            <span class="w-4">
                                <x-fas-tag />
                            </span>
                            <span> tags :</span>
                        </div>
                        @foreach ($post->tags as $tag)
                            <a href="{{ route('post.tag', $tag->slug) }}"
                                class="rounded border border-gray-200 bg-gray-100 px-1 text-sm hover:bg-gray-200">{{ $tag->name }}</a>
                        @endforeach
                    </div>
                @endif
            </article>

            @if (app()->environment('production'))
                <div>
                    {!! $adsense->script !!}
                </div>
            @endif

            {{-- Komentar --}}
            @if ($comments->count() > 0)
                <div class="mb-6 mt-6">
                    <h2 class="mb-4 font-bold">Tinggalkan Komentar</h2>
                    <div class="flex flex-col gap-4">
                        @foreach ($comments as $comment)
                            <article class="flex flex-col gap-2 rounded-lg bg-gray-50 p-4">
                                <h3 class="font-bold capitalize">{{ $comment->name }}</h3>
                                <p class="">{{ $comment->comment }}</p>
                                <time
                                    class="self-end text-xs text-gray-700">{{ $comment->created_at->translatedFormat('j F Y - H:i') }}</time>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Form komentar --}}
            <form method="POST" action="{{ route('post.comment', $post->id) }}"
                class="mb-8 flex flex-col gap-2 rounded-lg bg-gray-50 p-4">
                @csrf
                <h2 class="font-bold">Komentar</h2>
                <textarea name="comment" id="comment" rows="5"
                    class="w-full rounded-md border border-gray-300 bg-white p-2 focus:outline-none focus:drop-shadow">{{ old('comment') }}</textarea>
                @error('comment')
                    <small class="text-red-500">{{ $message }}</small>
                @enderror

                <div class="flex flex-col gap-2 md:flex-row md:gap-4">

                    <div class="md:w-1/2">
                        <input type="text" name="name" placeholder="Nama" value="{{ old('name') }}"
                            class="w-full rounded-md border border-gray-300 bg-white p-2 focus:outline-none focus:drop-shadow">
                        @error('name')
                            <small class="text-red-500">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="md:w-1/2">
                        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}"
                            class="w-full rounded-md border border-gray-300 bg-white p-2 focus:outline-none focus:drop-shadow">
                        @error('email')
                            <small class="text-red-500">{{ $message }}</small>
                        @enderror
                    </div>

                    <input type="hidden" name="jangan_diisi">
                </div>
                <button type="submit"
                    class="bg-warna-02 hover:bg-warna-01 block w-full cursor-pointer self-end rounded-md px-4 py-2 text-white md:w-auto">
                    Kirim
                </button>
            </form>

            {{-- Artikel Terkait --}}
            <div>
                <h2 class="mb-6 font-bold">Artikel Terkait</h2>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    @foreach ($otherArticles as $post)
                        <a href="{{ route('post.detail', [$post->category->slug ?? $post->type, $post->slug]) }}"
                            class="group flex items-start gap-4">

                            {{-- Thumbnail --}}
                            <div class="shrink-0">
                                <img src="{{ $post->gallery?->spatie_thumbnail ??
                                    ($post->type === 'video' && $post->video_url
                                        ? 'https://img.youtube.com/vi/' . $post->video_url . '/hqdefault.jpg'
                                        : asset('img/no_image.webp')) }}"
                                    class="h-20 w-28 rounded-md object-cover transition duration-300 group-hover:scale-105">
                            </div>

                            {{-- Content --}}
                            <div class="flex flex-col">

                                {{-- Author & Date --}}
                                <div class="mb-1 text-xs text-gray-500">
                                    {{ $post->publish_time->diffForHumans() }}
                                </div>

                                {{-- Title --}}
                                <h3
                                    class="group-hover:text-warna-03 line-clamp-3 text-sm font-semibold leading-snug transition">
                                    {{ $post->title }}
                                </h3>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- side div --}}
        <div class="md:w-1/4">
            @include('layout.sidebar')
        </div>
    </div>
@endsection

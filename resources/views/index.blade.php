@include('layout.header')



<div class="md:max-w-300 mx-auto px-4 py-8 min-h-[200vh] bg-white">

    {{-- Search Khusus di home --}}
    <div class="w-full text-center py-24 bg-cover bg-center rounded-2xl mb-8 px-2"
        style="background-image: url('{{ asset('img/city.webp') }}');">

        <h2 class="text-white font-bold text-3xl mb-4">Temukan Berita Menarik</h2>

        <form
            class="bg-white rounded-lg border border-gray-300 flex items-center p-2 justify-between max-w-3xl mx-auto mb-4"
            method="GET" action="{{ route('search') }}">

            <input type="search" required autocomplete="off" placeholder="Netanyahu meninggal..." name="search_input"
                class="appearance-none bg-transparent border-none w-full mr-3 py-1 px-2 leading-tight focus:outline-none" />

            <button type="submit" class="cursor-pointer text-gray-500 hover:text-warna-03 p-2">
                <x-fas-search class="h-[1em]" />
            </button>
        </form>

        <div class="flex flex-row flex-wrap gap-6 justify-center">
            @foreach ($suggestTags as $suggestTag)
                <a href="{{ route('post.tag', $suggestTag->slug) }}"
                    class="text-sm text-white hover:text-warna-03">#{{ $suggestTag->slug }}</a>
            @endforeach
        </div>
    </div>

    {{-- Highlight title --}}
    <div class="flex flex-row justify-between items-center gap-2 pb-3 mb-6 border-b border-gray-300">
        <span class="px-2 py-1 bg-linear-to-r from-warna-03 to-warna-04 text-white font-bold">Hot news</span>
        <a href="{{ $post->category->slug . '/' . $post->slug }}"
            class="text-lg grow hover:text-warna-03">{{ $post->title }}</a>
        <div class="hidden md:flex gap-2">
            <button type="button" class="cursor-pointer text-gray-500 hover:text-gray-700">
                {{ svg('feathericon-arrow-left-circle') }}
            </button>
            <button type="button" class="cursor-pointer text-gray-500 hover:text-gray-700">
                {{ svg('feathericon-arrow-right-circle') }}
            </button>
        </div>
    </div>

    <div class="flex flex-col md:flex-row gap-8">

        {{-- Main Div --}}
        <div class="md:w-3/4 flex flex-col gap-12">

            {{-- Card --}}
            <div class="relative aspect-2/1 bg-cover bg-center flex flex-col justify-end p-6 group rounded-lg overflow-hidden"
                style="background-image: url('{{ $post->getFirstMediaUrl('preview', 'preview') ?: asset('img/no_image.webp') }}');">

                <div class="absolute inset-0 bg-linear-to-t from-black/60 via-black/20 to-transparent"></div>

                <div class="relative text-white">
                    <span
                        class="px-2 py-1 bg-linear-to-r from-warna-03 to-warna-04 text-white font-bold mb-2 inline-block">
                        {{ $firstCategory?->name ?? 'No Category' }}
                    </span>

                    <h2 class="text-2xl font-bold mb-2">{{ $post->title }}</h2>
                    <span>{{ $post->publish_time->translatedFormat('j F Y') }}</span>
                </div>
                {{-- Arrow --}}
                <div
                    class="absolute top-0 bottom-0 right-0 text-white transition-opacity cursor-pointer z-10 flex items-center justify-center">
                    <div
                        class="w-8 h-8 text-xl translate-x-1/2 group-hover:-translate-x-1/2 opacity-0 group-hover:opacity-100 duration-400 ease-out">
                        {{ svg('fas-arrow-right') }}</div>
                </div>
                <div
                    class="absolute top-0 bottom-0 left-0 text-white transition-opacity cursor-pointer z-10 flex items-center justify-center">
                    <div
                        class="w-8 h-8 text-xl -translate-x-1/2 group-hover:translate-x-1/2 opacity-0 group-hover:opacity-100 duration-400 ease-out">
                        {{ svg('fas-arrow-left') }}</div>
                </div>
            </div>

            {{-- Berita Populer --}}
            <div>
                <div
                    class="border-b mb-6 border-gray-200 before:absolute before:w-16 before:top-full before:h-1 before:bg-warna-01 relative">
                    <h2 class="text-2xl font-bold mb-2">Berita Terbaru</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($beritaPopulers as $post)
                        @php
                            $category = $post->category;
                            $author = $post->author;
                            $url = ($category?->slug ?? '#') . '/' . $post->slug;
                        @endphp

                        <article class="flex gap-4 items-start group">
                            {{-- Thumbnail --}}
                            <a href="{{ $url }}" class="shrink-0">
                                <img src="{{ $post->spatie_thumbnail ?: asset('img/no_image.webp') }}"
                                    alt="{{ $post->title }}" class="w-28 h-20 object-cover rounded-md">
                            </a>

                            {{-- Content --}}
                            <div class="flex flex-col">
                                <div class="text-xs text-gray-500 mb-1">
                                    <span class="text-warna-03 font-semibold">
                                        By {{ $author?->name ?? 'Admin' }}
                                    </span>
                                    <span class="mx-1">•</span>
                                    <span>{{ $post->publish_time->translatedFormat('d F Y') }}</span>
                                </div>

                                <a href="{{ $url }}">
                                    <h3 class="font-semibold text-sm leading-snug group-hover:text-warna-03 transition">
                                        {{ $post->title }}
                                    </h3>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            <div>
                <div
                    class="border-b mb-6 border-gray-200 before:absolute before:w-16 before:top-full before:h-1 before:bg-warna-01 relative">
                    <h2 class="text-2xl font-bold mb-2">Artikel Terbaru</h2>
                </div>

                <div class="flex flex-col gap-6">
                    @foreach ($beritaTerbaru as $index => $post)
                        <?php
                        $url = $post->category->slug . '/' . $post->slug;
                        ?>
                        <article class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start group">
                            {{-- Image --}}
                            <a href="{{ $url }}" class="block md:col-span-1 peer">
                                <div class="aspect-2/1 w-full overflow-hidden rounded-md bg-gray-100">

                                    <img src="{{ $post->spatie_thumbnail ?: asset('img/no_image.webp') }}"
                                        alt="{{ $post->title }}" class="w-full h-full  object-cover rounded-md">
                                </div>
                            </a>

                            {{-- Content --}}
                            <div class="flex flex-col justify-center md:col-span-2">

                                {{-- Category --}}
                                <a href="{{ $post->category->slug }}"
                                    class="inline-block bg-red-600 text-white text-xs font-bold px-3 py-1 mb-3 w-fit">
                                    {{ strtoupper($post->category->name ?? 'No Category') }}
                                </a>

                                {{-- Meta --}}
                                <div class="text-xs text-gray-500 mb-2">
                                    <a href="{{ $post->author->name }}"
                                        class="text-red-600 font-semibold hover:underline">
                                        By {{ $post->author->name ?? 'Admin' }}
                                    </a>
                                    <span class="mx-1">•</span>
                                    <span>{{ $post->publish_time->translatedFormat('d F Y') }}</span>
                                </div>

                                {{-- Title --}}
                                <a href="{{ $url }}">
                                    <h2
                                        class="text-xl font-bold leading-snug mb-3 group-hover:text-warna-03 transition">
                                        {{ $post->title }}
                                    </h2>
                                </a>

                                {{-- Excerpt --}}
                                <p class="text-gray-600 text-sm leading-relaxed line-clamp-3">
                                    {{ $post->excerpt }}
                                </p>
                            </div>

                        </article>
                    @endforeach
                </div>
            </div>

            <div>
                <div
                    class="border-b mb-6 border-gray-200 before:absolute before:w-16 before:top-full before:h-1 before:bg-warna-01 relative">
                    <h2 class="text-2xl font-bold mb-2">Opini</h2>
                </div>

                <div class="flex flex-col gap-6">
                    @foreach ($beritaTerbaru as $index => $post)
                        <?php
                        $url = $post->category->slug . '/' . $post->slug;
                        ?>
                        <article class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start group">
                            {{-- Image --}}
                            <a href="{{ $url }}" class="block md:col-span-1 peer">
                                <div class="aspect-2/1 w-full overflow-hidden rounded-md bg-gray-100">

                                    <img src="{{ $post->spatie_thumbnail ?: asset('img/no_image.webp') }}"
                                        alt="{{ $post->title }}" class="w-full h-full  object-cover rounded-md">
                                </div>
                            </a>

                            {{-- Content --}}
                            <div class="flex flex-col justify-center md:col-span-2">

                                {{-- Category --}}
                                <a href="{{ $post->category->slug }}"
                                    class="inline-block bg-red-600 text-white text-xs font-bold px-3 py-1 mb-3 w-fit">
                                    {{ strtoupper($post->category->name ?? 'No Category') }}
                                </a>

                                {{-- Meta --}}
                                <div class="text-xs text-gray-500 mb-2">
                                    <a href="{{ $post->author->name }}"
                                        class="text-red-600 font-semibold hover:underline">
                                        By {{ $post->author->name ?? 'Admin' }}
                                    </a>
                                    <span class="mx-1">•</span>
                                    <span>{{ $post->publish_time->translatedFormat('d F Y') }}</span>
                                </div>

                                {{-- Title --}}
                                <a href="{{ $url }}">
                                    <h2
                                        class="text-xl font-bold leading-snug mb-3 group-hover:text-warna-03 transition">
                                        {{ $post->title }}
                                    </h2>
                                </a>

                                {{-- Excerpt --}}
                                <p class="text-gray-600 text-sm leading-relaxed line-clamp-3">
                                    {{ $post->excerpt }}
                                </p>
                            </div>

                        </article>
                    @endforeach
                </div>
            </div>

            <div>
                <div
                    class="border-b mb-6 border-gray-200 before:absolute before:w-16 before:top-full before:h-1 before:bg-warna-01 relative">
                    <h2 class="text-2xl font-bold mb-2">Berita Video</h2>
                </div>

                <div class="flex flex-col gap-6">
                    @foreach ($beritaTerbaru as $index => $post)
                        <?php
                        $url = $post->category->slug . '/' . $post->slug;
                        ?>
                        <article class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start group">
                            {{-- Image --}}
                            <a href="{{ $url }}" class="block md:col-span-1 peer">
                                <div class="aspect-2/1 w-full overflow-hidden rounded-md bg-gray-100">

                                    <img src="{{ $post->spatie_thumbnail ?: asset('img/no_image.webp') }}"
                                        alt="{{ $post->title }}" class="w-full h-full  object-cover rounded-md">
                                </div>
                            </a>

                            {{-- Content --}}
                            <div class="flex flex-col justify-center md:col-span-2">

                                {{-- Category --}}
                                <a href="{{ $post->category->slug }}"
                                    class="inline-block bg-red-600 text-white text-xs font-bold px-3 py-1 mb-3 w-fit">
                                    {{ strtoupper($post->category->name ?? 'No Category') }}
                                </a>

                                {{-- Meta --}}
                                <div class="text-xs text-gray-500 mb-2">
                                    <a href="{{ $post->author->name }}"
                                        class="text-red-600 font-semibold hover:underline">
                                        By {{ $post->author->name ?? 'Admin' }}
                                    </a>
                                    <span class="mx-1">•</span>
                                    <span>{{ $post->publish_time->translatedFormat('d F Y') }}</span>
                                </div>

                                {{-- Title --}}
                                <a href="{{ $url }}">
                                    <h2
                                        class="text-xl font-bold leading-snug mb-3 group-hover:text-warna-03 transition">
                                        {{ $post->title }}
                                    </h2>
                                </a>

                                {{-- Excerpt --}}
                                <p class="text-gray-600 text-sm leading-relaxed line-clamp-3">
                                    {{ $post->excerpt }}
                                </p>
                            </div>

                        </article>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- side div --}}
        <div class="md:w-1/4">
            @include('layout.sidebar')
        </div>
    </div>

</div>
@include('layout.footer')

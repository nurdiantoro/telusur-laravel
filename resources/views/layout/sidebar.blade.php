<aside class="flex flex-col gap-10">

    {{-- ===================== --}}
    {{-- Sidebar Ads --}}
    {{-- ===================== --}}
    @if ($sidebarAds->count())
        <section class="flex flex-col gap-3">
            @foreach ($sidebarAds as $ads)
                <a href="{{ $ads->link }}" target="_blank" rel="noopener noreferrer" class="block">

                    <img src="{{ $ads->getFirstMediaUrl('imagesCollection', 'preview') ?: asset('img/no_image.webp') }}"
                        alt="Advertisement" class="h-auto w-full rounded-lg shadow-sm transition hover:opacity-90">
                </a>
            @endforeach
        </section>
    @endif

    {{-- ===================== --}}
    {{-- Popular News --}}
    {{-- ===================== --}}
    @if ($beritaPopulers->count())
        <section class="flex flex-col">

            <h2 class="text-merah-01 mb-4 border-b pb-2 text-sm font-bold uppercase tracking-wide">
                Berita Populer
            </h2>

            <div class="flex flex-col gap-4">
                @foreach ($beritaPopulers as $post)
                    <article class="group flex gap-3">

                        {{-- Thumbnail --}}
                        <a href="{{ route('post.detail', [$post->category->slug, $post->slug]) }}" class="shrink-0">
                            <img src="{{ $post->gallery?->spatie_thumbnail ?: asset('img/no_image.webp') }}"
                                alt="{{ $post->title }}"
                                class="h-20 w-24 rounded-md object-cover transition duration-300 group-hover:scale-105">
                        </a>

                        {{-- Content --}}
                        <div class="flex flex-col">
                            <a href="{{ route('post.detail', [$post->category->slug, $post->slug]) }}">
                                <h3
                                    class="group-hover:text-merah-01 line-clamp-3 text-sm font-semibold leading-snug transition">
                                    {{ $post->title }}
                                </h3>
                            </a>
                        </div>

                    </article>
                @endforeach
            </div>

        </section>
    @endif

</aside>

<aside class="flex flex-col gap-10">

    {{-- ===================== --}}
    {{-- Sidebar Ads --}}
    {{-- ===================== --}}
    @if ($sidebarAds->count())
        <section class="flex flex-col gap-3">
            @foreach ($sidebarAds as $ads)
                <a href="{{ $ads->link }}" target="_blank" rel="noopener noreferrer" class="block">

                    <img src="{{ $ads->getFirstMediaUrl('imagesCollection', 'preview') ?: asset('img/no_image.webp') }}"
                        alt="Advertisement" class="w-full h-auto rounded-lg shadow-sm hover:opacity-90 transition">
                </a>
            @endforeach
        </section>
    @endif


    {{-- ===================== --}}
    {{-- Popular News --}}
    {{-- ===================== --}}
    @if ($beritaPopulers->count())
        <section class="flex flex-col">

            <h2 class="font-bold mb-4 border-b pb-2 text-merah-01 uppercase tracking-wide text-sm">
                Berita Populer
            </h2>

            <div class="flex flex-col gap-4">
                @foreach ($beritaPopulers as $post)
                    <article class="flex gap-3 group">

                        {{-- Thumbnail --}}
                        <a href="{{ route('post.detail', [$post->category->slug, $post->slug]) }}" class="shrink-0">
                            <img src="{{ $post->spatie_thumbnail ?: asset('img/no_image.webp') }}"
                                alt="{{ $post->title }}"
                                class="w-24 h-20 object-cover rounded-md group-hover:scale-105 transition duration-300">
                        </a>

                        {{-- Content --}}
                        <div class="flex flex-col">
                            <a href="{{ route('post.detail', [$post->category->slug, $post->slug]) }}">
                                <h3
                                    class="text-sm font-semibold leading-snug line-clamp-3 group-hover:text-merah-01 transition">
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

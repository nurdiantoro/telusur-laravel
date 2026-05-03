<aside class="flex flex-col gap-10 px-4 md:px-0">

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
    <section class="flex flex-col">

        <h2 class="text-merah-01 mb-4 border-b border-gray-300 text-sm font-bold uppercase tracking-wide text-gray-800">
            Berita Populer
        </h2>

        <div class="flex flex-col gap-4">
            <!-- Content -->
            @foreach ($beritaPopuler as $post)
                <a href="{{ route('post.detail', [$post->category?->slug ?: $post->type, $post->slug]) }}"
                    class="group flex gap-3">
                    <!-- Thumbnail -->
                    <div class="shrink-0 overflow-hidden rounded-md bg-gray-200">
                        <img src="{{ $post->gallery?->spatie_preview ?: asset('img/no_image.webp') }}"
                            alt="{{ $post->title }}"
                            class="h-20 w-24 bg-gray-200 object-cover transition duration-300 ease-out group-hover:scale-110"
                            loading="lazy">
                    </div>
                    <!-- Content -->
                    <div class="flex flex-col">
                        <h3
                            class="group-hover:text-merah-01 group-hover:text-warna-03 line-clamp-2 text-sm font-semibold leading-snug transition">
                            {{ $post->title }}
                        </h3>
                        <span class="text-xs text-gray-500">
                            {{ $post->publish_time->diffForHumans() }}
                        </span>
                    </div>
                </a>
            @endforeach

        </div>
    </section>

</aside>

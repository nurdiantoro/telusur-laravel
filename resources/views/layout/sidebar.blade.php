<div class="sticky top-5">
    <div class="flex flex-col">
        <div class="flex flex-col gap-2 mb-10">
            @foreach ($sidebarAds as $ads)
                <a href="{{ $ads->link }}" target="_blank" rel="noopener noreferrer">
                    <img src="{{ $ads->getFirstMediaUrl('imagesCollection', 'preview') ?: asset('img/no_image.webp') }}"
                        alt="Advertisement" class="w-full h-auto rounded-md">
                </a>
            @endforeach
        </div>

        <h2 class="font-bold mb-3 border-b border-gray-200 text-merah-01">Berita Populer</h2>
        <div class="flex flex-col gap-3">
            @foreach ($beritaPopulers as $beritaPopuler)
                <article class="flex gap-4 items-start group">
                    {{-- Thumbnail --}}
                    <a href="{{ route('post.detail', [$beritaPopuler->category->slug, $beritaPopuler->slug]) }}"
                        class="shrink-0">
                        <img src="{{ $beritaPopuler->spatie_thumbnail ?: asset('img/no_image.webp') }}"
                            alt="{{ $beritaPopuler->title }}" class="w-28 h-20 object-cover rounded-md">
                    </a>

                    {{-- Content --}}
                    <div class="flex flex-col">

                        <a href="{{ route('post.detail', [$beritaPopuler->category->slug, $beritaPopuler->slug]) }}">
                            <h3
                                class="font-semibold text-sm leading-snug group-hover:text-merah-01 transition line-clamp-3">
                                {{ $beritaPopuler->title }}
                            </h3>
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</div>

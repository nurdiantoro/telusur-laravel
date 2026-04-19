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
    <section class="flex flex-col" x-data="beritaPopuler()" x-init="init()">

        <h2 class="text-merah-01 mb-4 border-b border-gray-300 text-sm font-bold uppercase tracking-wide text-gray-800">
            Berita Populer
        </h2>

        <div class="flex flex-col gap-4" x-ref="skeleton">

            <!-- Skeleton -->
            <div class="flex flex-col gap-4">
                @for ($i = 0; $i < 6; $i++)
                    <div class="flex animate-pulse gap-3">
                        <div class="h-20 w-24 rounded-md bg-gray-200"></div>
                        <div class="flex-1 space-y-2">
                            <div class="h-4 w-3/4 rounded bg-gray-200"></div>
                            <div class="h-4 w-1/2 rounded bg-gray-200"></div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        <div class="flex flex-col gap-4">
            <!-- Content -->
            <template x-for="post in apiPosts" :key="post.id">
                <a :href="`${post.category.slug}/${post.slug}`" class="group flex gap-3">
                    <!-- Thumbnail -->
                    <div class="shrink-0 overflow-hidden rounded-md bg-gray-200">
                        <img :src="post.thumbnail" :alt="post.title"
                            class="h-20 w-24 object-cover transition duration-300 ease-out group-hover:scale-110">
                    </div>
                    <!-- Content -->
                    <div class="flex flex-col">
                        <h3 class="group-hover:text-merah-01 group-hover:text-warna-03 line-clamp-2 text-sm font-semibold leading-snug transition"
                            x-text="post.title"></h3>
                        <span x-text="post.publish_time" class="text-xs text-gray-500"></span>
                    </div>
                </a>
            </template>

        </div>
    </section>

</aside>

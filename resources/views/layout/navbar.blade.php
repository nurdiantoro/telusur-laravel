<div class="z-100 fixed top-0 flex w-full flex-col items-center md:static">
    {{-- Navbar Mobile --}}
    <div class="flex w-full items-center justify-center border-gray-200 bg-white md:border-b" x-data="{ showSearch: false }"
        x-init="$watch('showSearch', val => {
            if (window.innerWidth < 768) {
                document.body.classList.toggle('overflow-hidden', val)
            }
        })">
        <div
            class="md:w-300 z-100 relative flex w-full flex-col items-center px-4 py-2 shadow-lg md:flex-row md:items-end md:justify-center md:px-0 md:py-4 md:shadow-none">

            {{-- Tengah / Logo --}}
            <a href="{{ route('home') }}" class="md:flex md:flex-1 md:justify-start">
                <img src="{{ asset('img/logo-telusur.webp') }}" alt="Logo" fetchpriority="high" loading="eager"
                    class="h-12 md:h-20">
            </a>

            {{-- Kanan --}}
            <div class="relative mt-2 flex w-full flex-row items-center justify-between gap-4 md:flex-1 md:justify-end">
                <div class="flex gap-4 transition-all duration-500 ease-in-out"
                    :class="showSearch ? 'opacity-0' : 'opacity-100'">
                    <a href="#" class="md:hover:text-warna-01 font-semibold text-black md:text-gray-500">Index</a>
                    <a href="#" class="md:hover:text-warna-01 font-semibold text-black md:text-gray-500">Opini</a>
                    <a href="#" class="md:hover:text-warna-01 font-semibold text-black md:text-gray-500">Berita
                        Video</a>
                </div>

                <form method="GET" action="{{ route('search') }}" x-cloak x-data x-init="$watch('showSearch', val => val && $nextTick(() => $refs.searchInput.focus()))"
                    :class="showSearch ? 'max-w-120 mt-2 opacity-100' : 'max-w-0 opacity-0'"
                    class="border-warna-02 absolute -bottom-1 right-10 z-50 hidden w-full flex-row gap-2 overflow-hidden rounded-lg border-2 bg-white shadow transition-all duration-500 ease-in-out md:flex">

                    <input type="text" placeholder="Cari..." x-ref="searchInput" name="search_input" required
                        class="w-full px-3 py-2 focus:outline-none">
                    <button type="submit" class="hover:text-warna-03 cursor-pointer p-2 text-gray-500">
                        <x-heroicon-c-magnifying-glass class="h-6" />
                    </button>
                </form>

                <button type="button" @click="showSearch = !showSearch"
                    class="hover:text-warna-03 relative h-8 w-8 text-gray-500">
                    <div :class="showSearch
                        ?
                        'opacity-0 rotate-90 scale-75' :
                        'opacity-100 rotate-0 scale-100'"
                        class="absolute inset-0 flex items-center justify-center transition-all duration-300 ease-in-out">
                        <x-heroicon-c-magnifying-glass class="h-6" />
                    </div>
                    <div x-cloak
                        :class="showSearch
                            ?
                            'opacity-100 rotate-0 scale-100' :
                            'opacity-0 -rotate-90 scale-75'"
                        class="absolute inset-0 flex items-center justify-center transition-all duration-300 ease-in-out">
                        <x-heroicon-c-x-mark class="h-6" />
                    </div>
                </button>
            </div>

            {{-- Search Form Mobile --}}
            <form method="GET" action="{{ route('search') }}" x-cloak x-data x-init="$watch('showSearch', val => val && $nextTick(() => $refs.searchInput.focus()))"
                :class="showSearch ? 'max-h-40 mt-2 opacity-100' : 'max-h-0 opacity-0'"
                class="flex w-full flex-row gap-2 overflow-hidden rounded-lg border border-gray-300 transition-all duration-500 ease-in-out md:hidden">

                <input type="text" placeholder="Cari..." x-ref="searchInput" name="search_input" required
                    class="w-full px-3 py-2 focus:outline-none">
                <button type="submit" class="hover:text-warna-03 cursor-pointer p-2 text-gray-500">
                    <x-heroicon-c-magnifying-glass class="h-6" />
                </button>
            </form>

            <div x-cloak :class="showSearch ? 'max-h-40 mt-2 opacity-100 my-2' : 'max-h-0 opacity-0 my-0'"
                class="flex w-full flex-col gap-1 overflow-hidden opacity-0 transition-all duration-500 ease-in-out md:hidden">
                <span class="text-sm text-gray-500">Sering dicari</span>
                @for ($i = 0; $i < 3; $i++)
                    <a href="#" class="flex items-center gap-2 overflow-hidden">
                        <x-heroicon-c-arrow-trending-up class="text-warna-03 h-6 shrink-0" />
                        <span class="truncate text-sm text-gray-600 hover:text-black">
                            Lorem, ipsum dolor sit amet consectetur adipisicing elit
                        </span>
                    </a>
                @endfor
            </div>
        </div>

        <div x-show="showSearch" x-transition.opacity @click="showSearch = false" class="-z-1 fixed inset-0 bg-black/50"
            x-cloak></div>
    </div>

    {{-- Navbar Desktop --}}
    <div class="hidden w-full border-b border-gray-200 bg-neutral-100 md:block">
        <ul class="md:w-300 mx-auto flex flex-wrap justify-between md:flex-row">
            <li
                class="{{ Route::is('home') ? 'text-warna-01 border-b-2 border-warna-01' : 'text-neutral-500 hover:text-warna-01' }} px-2 py-4 font-bold uppercase">
                <a href="{{ route('home') }}">
                    Home
                </a>
            </li>
            @foreach ($navbarCategories as $navbar)
                <li
                    class="{{ request()->route('category') == $navbar->slug ? 'text-warna-01 border-b-2 border-warna-01' : 'text-neutral-500 hover:text-warna-01' }} px-2 py-4 font-bold uppercase">

                    <a href="{{ route('post.category', $navbar->slug) }}">
                        {{ $navbar->name }}
                    </a>

                </li>
            @endforeach
        </ul>
    </div>
</div>

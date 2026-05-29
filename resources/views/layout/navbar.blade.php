<div class="z-100 fixed top-0 flex w-full flex-col items-center md:static" x-data="{ showSearch: false, showNav: false }" x-init="$watch('showSearch', val => {
    if (window.innerWidth < 768) {
        document.body.classList.toggle('overflow-hidden', val)
    }
});
$watch('showNav', val => {
    if (window.innerWidth < 768) {
        document.body.classList.toggle('overflow-hidden', val)
    }
});">

    {{-- Navbar Atas --}}
    <div class="flex w-full items-center justify-center border-b border-gray-200 bg-white">
        <div
            class="md:w-300 z-100 relative flex w-full flex-row items-center px-4 py-2 md:items-end md:justify-center md:px-0 md:py-4">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="w-full md:flex md:flex-1 md:justify-start">
                <img src="{{ asset('img/logo-telusur-new.png') }}" class="h-12 md:h-20">
            </a>

            {{-- Kanan --}}
            <div class="relative flex items-center justify-between gap-4 md:flex-1 md:justify-end">

                {{-- Menu text --}}
                <div class="flex items-center transition-all duration-500"
                    :class="showSearch ? 'opacity-0' : 'opacity-100'">
                    <div class="mr-4 hidden items-center gap-4 md:flex">
                        <a href="{{ route('index_post') }}" class="font-semibold text-gray-500">Index</a>
                        <a href="{{ route('opini') }}" class="font-semibold text-gray-500">Opini</a>
                        <a href="{{ route('video') }}" class="font-semibold text-gray-500">Berita Video</a>
                    </div>
                    <a target="_blank" href="https://www.instagram.com/"
                        class="p-2 font-semibold text-[#E1306C] hover:bg-gray-100">
                        <x-fab-instagram class="h-6" />
                    </a>
                    <a target="_blank" href="https://www.linkedin.com/"
                        class="p-2 font-semibold text-[#0A66C2] hover:bg-gray-100">
                        <x-fab-linkedin class="h-6" />
                    </a>
                    <a target="_blank" href="https://www.youtube.com/"
                        class="p-2 font-semibold text-[#FF0000] hover:bg-gray-100">
                        <x-fab-youtube class="h-6" />
                    </a>
                </div>

                {{-- Search desktop TARO DISINI --}}
            </div>

            {{-- MOBILE TARO DISINI --}}
        </div>

        {{-- Overlay Mobile --}}
        <div x-show="showSearch || showNav || showMore" x-transition.opacity
            @click="showSearch = false; showNav = false; showMore = false"
            class="-z-1 fixed inset-0 bg-black/50 md:hidden" x-cloak></div>
    </div>

    {{-- Navbar Category --}}
    <div class="w-full border-b border-gray-200 bg-gray-100" x-data="{ showMore: false }">

        {{-- MOBILE TOP MENU --}}
        <div class="flex items-center justify-between px-6 md:hidden">
            <div class="flex items-center gap-4 overflow-x-auto">
                {{-- Home --}}
                <a href="{{ route('home') }}"
                    class="{{ Route::is('home') ? 'text-warna-01' : 'text-gray-500' }} whitespace-nowrap font-bold uppercase md:py-4">
                    Home
                </a>

                {{-- Ambil 2 kategori pertama --}}
                @foreach ($navbarCategories->take(2) as $category)
                    <?php $isActive = request()->route('category') == $category->slug; ?>
                    <a href="{{ route('post.category', $category->slug) }}"
                        class="{{ $isActive ? 'text-warna-01' : 'text-gray-500' }} whitespace-nowrap font-bold uppercase md:py-4">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>

            {{-- Hamburger --}}
            <button @click="showMore = !showMore" class="relative h-12 p-2 text-gray-700">
                <div :class="showMore ? 'opacity-0 rotate-90' : 'opacity-100'"
                    class="absolute right-0 flex -translate-y-1/2 items-center justify-center transition-all duration-300">
                    <x-heroicon-c-bars-3 class="h-6 w-6" />
                </div>
                <div x-cloak :class="showMore ? 'opacity-100' : 'opacity-0 -rotate-90'"
                    class="absolute right-0 flex -translate-y-1/2 items-center justify-center transition-all duration-300">
                    <x-heroicon-c-x-mark class="h-6 w-6" />
                </div>

                {{-- <x-heroicon-m-bars-3 class="h-6 w-6" /> --}}
            </button>
        </div>

        {{-- MOBILE DROPDOWN --}}
        {{-- kategori setelah 2 pertama --}}
        <div x-show="showMore" x-collapse class="border-t border-gray-200 bg-gray-100 md:hidden" x-cloak>
            <ul class="flex flex-col">
                @foreach ($navbarCategories->skip(2) as $category)
                    <?php $isActive = request()->route('category') == $category->slug; ?>
                    <li class="border-b border-gray-200">
                        <a href="{{ route('post.category', $category->slug) }}"
                            class="{{ $isActive ? 'text-warna-01' : 'text-gray-500' }} block px-6 py-3 font-bold uppercase">
                            {{ $category->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- DESKTOP NAV --}}
        <div class="hidden md:block">
            <nav x-data="{ openMenu: null }" class="md:w-300 mx-auto">
                <ul class="flex flex-wrap justify-between">
                    <li
                        class="{{ Route::is('home') ? 'text-warna-01' : 'text-gray-500 hover:text-warna-01' }} px-3 font-bold uppercase md:py-4">
                        <a href="{{ route('home') }}">Home</a>
                    </li>
                    @foreach ($navbarCategories as $category)
                        <?php $isActive = request()->route('category') == $category->slug; ?>
                        <li class="relative">
                            <div class="flex items-center justify-between px-3 font-bold uppercase md:py-4"
                                @click="openMenu = openMenu === '{{ $category->slug }}' ? null : '{{ $category->slug }}'">
                                <a href="{{ route('post.category', $category->slug) }}"
                                    class="{{ $isActive ? 'text-warna-01' : 'text-gray-500 hover:text-warna-01' }} flex-1">
                                    {{ $category->name }}
                                </a>
                                @if ($category->children->isNotEmpty())
                                    <x-heroicon-m-chevron-down class="ml-2 h-5 w-5 transition-transform duration-300"
                                        x-bind:class="{ 'rotate-180': openMenu === '{{ $category->slug }}' }" />
                                @endif
                            </div>
                            @if ($category->children->isNotEmpty())
                                <div x-show="openMenu === '{{ $category->slug }}'" x-collapse
                                    class="min-w-50 absolute mt-2 overflow-hidden rounded border border-gray-200 bg-gray-100">
                                    @foreach ($category->children as $sub)
                                        @php
                                            $isSubActive = request()->route('category') == $sub->slug;
                                        @endphp
                                        <a href="{{ route('post.category', $sub->slug) }}"
                                            class="{{ $isSubActive ? 'text-warna-01' : 'text-gray-500 hover:text-warna-01' }} block px-5 py-2 font-bold uppercase">
                                            {{ $sub->name }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif

                        </li>
                    @endforeach

                </ul>

            </nav>

        </div>

        {{-- Overlay Mobile --}}
        <div x-show="showMore" x-transition.opacity @click="showMore = false"
            class="-z-1 fixed inset-0 bg-black/50 md:hidden" x-cloak></div>
    </div>

</div>

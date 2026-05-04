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
    <div class="flex w-full items-center justify-center border-gray-200 bg-white md:border-b">

        <div
            class="md:w-300 z-100 relative flex w-full flex-col items-center px-4 py-2 shadow-lg md:flex-row md:items-end md:justify-center md:px-0 md:py-4 md:shadow-none">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="md:flex md:flex-1 md:justify-start">
                <img src="{{ asset('img/logo-telusur-new.png') }}" class="h-12 md:h-20">
            </a>

            {{-- Kanan --}}
            <div class="relative mt-2 flex w-full items-center justify-between gap-4 md:flex-1 md:justify-end">

                {{-- Menu text --}}
                <div class="flex gap-4 transition-all duration-500" :class="showSearch ? 'opacity-0' : 'opacity-100'">
                    <a href="{{ route('index_post') }}" class="font-semibold text-black md:text-gray-500">Index</a>
                    <a href="{{ route('opini') }}" class="font-semibold text-black md:text-gray-500">Opini</a>
                    <a href="{{ route('video') }}" class="font-semibold text-black md:text-gray-500">Berita Video</a>
                </div>

                {{-- Search desktop --}}
                <form method="GET" action="{{ route('search') }}" x-cloak x-data x-init="$watch('showSearch', val => val && $nextTick(() => $refs.searchInput.focus()))"
                    :class="showSearch ? 'max-w-120 mt-2 opacity-100' : 'max-w-0 opacity-0'"
                    class="absolute -bottom-1 right-16 z-50 hidden w-full flex-row gap-2 overflow-hidden rounded-lg border-2 bg-white shadow transition-all duration-500 md:flex">

                    <input type="text" x-ref="searchInput" name="search_input" required class="w-full px-3 py-2">
                    <button type="submit" class="p-2 text-gray-500">
                        <x-heroicon-c-magnifying-glass class="h-6" />
                    </button>
                </form>

                {{-- BUTTONS --}}
                <div class="flex items-center gap-2">

                    {{-- SEARCH --}}
                    <button type="button"
                        @click="
                            showSearch = !showSearch;
                            if (showSearch) showNav = false;
                        "
                        class="relative h-8 w-8 text-gray-500">

                        <div :class="showSearch ? 'opacity-0 rotate-90 scale-75' : 'opacity-100'"
                            class="absolute inset-0 flex items-center justify-center transition-all duration-300">
                            <x-heroicon-c-magnifying-glass class="h-6" />
                        </div>

                        <div x-cloak :class="showSearch ? 'opacity-100' : 'opacity-0 -rotate-90 scale-75'"
                            class="absolute inset-0 flex items-center justify-center transition-all duration-300">
                            <x-heroicon-c-x-mark class="h-6" />
                        </div>
                    </button>

                    {{-- HAMBURGER (MOBILE ONLY) --}}
                    <button type="button"
                        @click="
                            showNav = !showNav;
                            if (showNav) showSearch = false;
                        "
                        class="relative h-8 w-8 text-gray-500 md:hidden">

                        <div :class="showNav ? 'opacity-0 rotate-90 scale-75' : 'opacity-100'"
                            class="absolute inset-0 flex items-center justify-center transition-all duration-300">
                            <x-heroicon-c-bars-3 class="h-6" />
                        </div>

                        <div x-cloak :class="showNav ? 'opacity-100' : 'opacity-0 -rotate-90 scale-75'"
                            class="absolute inset-0 flex items-center justify-center transition-all duration-300">
                            <x-heroicon-c-x-mark class="h-6" />
                        </div>
                    </button>

                </div>
            </div>

            {{-- Search Mobile --}}
            <form method="GET" action="{{ route('search') }}" x-cloak x-data x-init="$watch('showSearch', val => val && $nextTick(() => $refs.searchInput.focus()))"
                :class="showSearch ? 'max-h-40 mt-2 opacity-100' : 'max-h-0 opacity-0'"
                class="flex w-full gap-2 overflow-hidden rounded-lg border md:hidden">

                <input type="text" x-ref="searchInput" name="search_input" required class="w-full px-3 py-2">
                <button type="submit" class="p-2 text-gray-500">
                    <x-heroicon-c-magnifying-glass class="h-6" />
                </button>
            </form>

        </div>

        {{-- Overlay Mobile --}}
        <div x-show="showSearch || showNav" x-transition.opacity @click="showSearch = false; showNav = false"
            class="-z-1 fixed inset-0 bg-black/50 md:hidden" x-cloak></div>

    </div>

    {{-- Navbar Category --}}
    <div class="w-full border-b border-gray-200 bg-gray-100" :class="showNav ? 'block' : 'hidden md:block'">

        <nav x-data="{ openMenu: null }" class="md:w-300 mx-auto">

            <ul
                class="flex max-h-[70vh] flex-col overflow-y-auto md:max-h-none md:flex-row md:flex-wrap md:justify-between">

                <li
                    class="{{ Route::is('home') ? 'text-warna-01 border-b-2 border-warna-01' : 'text-gray-500 hover:text-warna-01' }} px-3 py-4 font-bold uppercase">
                    <a href="{{ route('home') }}">Home</a>
                </li>

                @foreach ($navbarCategories as $category)
                    @php $isActive = request()->route('category') == $category->slug; @endphp

                    <li class="">

                        {{-- Parent (klik untuk toggle di mobile) --}}
                        <div class="flex items-center justify-between px-3 py-4 font-bold uppercase"
                            @click="openMenu = openMenu === '{{ $category->slug }}' ? null : '{{ $category->slug }}'">

                            <a href="{{ route('post.category', $category->slug) }}"
                                class="{{ $isActive ? 'text-warna-01' : 'text-gray-500 hover:text-warna-01' }} flex-1">
                                {{ $category->name }}
                            </a>

                            @if ($category->children->isNotEmpty())
                                {{-- Icon (Heroicon) --}}
                                <x-heroicon-m-chevron-down class="ml-2 h-5 w-5 transition-transform duration-300"
                                    x-bind:class="{ 'rotate-180': openMenu === '{{ $category->slug }}' }" />
                            @endif
                        </div>

                        {{-- Subcategory --}}
                        @if ($category->children->isNotEmpty())
                            <div x-show="openMenu === '{{ $category->slug }}'" x-collapse
                                class="md:min-w-50 overflow-hidden border-gray-200 bg-gray-100 md:absolute md:mt-2 md:rounded md:border">

                                @foreach ($category->children as $sub)
                                    @php $isSubActive = request()->route('category') == $sub->slug; @endphp
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

</div>

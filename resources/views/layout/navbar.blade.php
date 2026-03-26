<div class="fixed top-0 z-50 flex w-full flex-col items-center md:static">
    {{-- Logo --}}
    <div class="flex w-full items-center justify-center border-gray-200 bg-white md:border-b" x-data="{ showSearch: false }">
        <div
            class="md:w-300 relative flex w-full flex-col items-center px-4 py-2 shadow-lg md:flex-row md:items-end md:justify-center md:px-0 md:py-4 md:shadow-none">

            {{-- Tengah / Logo --}}
            <a href="{{ route('home') }}" class="md:flex md:flex-1 md:justify-start">
                <img src="{{ asset('img/logo-telusur.webp') }}" alt="Logo" fetchpriority="high" loading="eager"
                    class="h-12 md:h-20">
            </a>

            {{-- Kanan --}}
            <div class="flex w-full items-center justify-between gap-4 md:flex-1 md:justify-end">
                <div class="flex gap-4">
                    <a href="#" class="hover:text-warna-01 font-semibold text-gray-500">Index</a>
                    <a href="#" class="hover:text-warna-01 font-semibold text-gray-500">Opini</a>
                    <a href="#" class="hover:text-warna-01 font-semibold text-gray-500">Berita Video</a>
                </div>

                <div>
                    <button id="darkmode-toggle" type="button"
                        class="cursor-pointer rounded-lg px-3 text-gray-500 hover:bg-gray-100">
                        <x-heroicon-c-sun />
                    </button>
                </div>

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

            <form method="GET" action="{{ route('search') }}" x-data x-init="$watch('showSearch', val => val && $nextTick(() => $refs.searchInput.focus()))"
                :class="showSearch ? 'max-h-40 mt-2' : 'max-h-0'"
                class="flex w-full flex-row gap-2 overflow-hidden transition-all duration-500 ease-in-out md:hidden">

                <input type="text" placeholder="Cari..." x-ref="searchInput" name="search_input" required
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none">
                <button type="submit" class="hover:text-warna-03 cursor-pointer p-2 text-gray-500">
                    <x-heroicon-c-magnifying-glass class="h-6" />
                </button>
            </form>
        </div>
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

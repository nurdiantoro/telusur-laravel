<div class="flex flex-col items-center w-full ">
    {{-- Logo --}}
    <div class="border-b border-gray-200 w-full flex items-center justify-center">
        <div
            class="relative w-full md:w-300 flex items-center md:items-end md:justify-center flex-col md:flex-row md:py-4 px-4 md:px-0">

            {{-- Tengah --}}
            <a href="{{ route('home') }}" class="md:flex-1 md:flex md:justify-start">
                <img src="{{ asset('img/logo-telusur.webp') }}" alt="Logo" fetchpriority="high" loading="eager"
                    class="h-20">
            </a>

            {{-- Kanan --}}
            <div class="md:flex-1 flex justify-between md:justify-end items-center gap-4 w-full">
                <div class="flex gap-4">
                    <a href="#" class="font-semibold text-gray-500 hover:text-warna-01">Index</a>
                    <a href="#" class="font-semibold text-gray-500 hover:text-warna-01">Opini</a>
                    <a href="#" class="font-semibold text-gray-500 hover:text-warna-01">Berita Video</a>
                </div>
                <div>
                    <button id="darkmode-toggle" type="button"
                        class="text-gray-500 h-10 px-3 rounded-lg hover:bg-gray-100 cursor-pointer">
                        <x-feathericon-sun />
                    </button>
                </div>
                {{-- <div id="darkmode-toggle"
                    class="text-gray-500 h-10 w-10 p-2 rounded-lg border border-gray-200 hover:bg-gray-100">
                    <x-feathericon-moon />
                </div> --}}
            </div>
        </div>
    </div>

    {{-- Navbar Desktop --}}
    <div class="w-full border-b border-gray-200 bg-neutral-100 md:block hidden">
        <ul class="md:w-300 flex md:flex-row mx-auto justify-between flex-wrap">
            <li
                class="uppercase font-bold px-2 py-4 {{ Route::is('home') ? 'text-warna-01 border-b-2 border-warna-01' : 'text-neutral-500 hover:text-warna-01' }}">
                <a href="{{ route('home') }}">
                    Home
                </a>
            </li>
            @foreach ($navbarCategories as $navbar)
                <li
                    class="uppercase font-bold px-2 py-4 {{ request()->route('category') == $navbar->slug ? 'text-warna-01 border-b-2 border-warna-01' : 'text-neutral-500 hover:text-warna-01' }}">

                    <a href="{{ route('post.category', $navbar->slug) }}">
                        {{ $navbar->name }}
                    </a>

                </li>
            @endforeach
        </ul>
    </div>
</div>

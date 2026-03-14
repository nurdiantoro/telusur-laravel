<div class="flex md:flex-col items-center w-full">
    {{-- Logo --}}
    <div class="border-b border-gray-200 w-full flex items-center justify-center">
        <div class="relative md:w-300 flex items-end justify-center flex-row py-4">
            <!-- Kiri (kosong) -->
            <div class="flex-1"></div>

            {{-- Tengah --}}
            <a href="{{ route('home') }}" class="flex-1 flex justify-center">
                <img src="{{ asset('img/logo-telusur.webp') }}" alt="Logo" fetchpriority="high" loading="eager"
                    class="h-20">
            </a>

            {{-- Kanan --}}
            <div class="flex-1 flex justify-end">
                <button id="darkmode-toggle" type="button"
                    class="text-gray-500 h-10 px-3 rounded-lg border border-gray-200 hover:bg-gray-100 cursor-pointer">
                    <x-feathericon-sun />
                </button>
                {{-- <div id="darkmode-toggle"
                    class="text-gray-500 h-10 w-10 p-2 rounded-lg border border-gray-200 hover:bg-gray-100">
                    <x-feathericon-moon />
                </div> --}}
            </div>
        </div>
    </div>

    {{-- Navbar Desktop --}}
    <div class="w-full border-b border-gray-200 bg-neutral-100">
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

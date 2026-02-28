<div class="navbar md:flex-col md:gap-2 p-0">
    {{-- Logo --}}
    <div class="mx-auto">
        <img src="{{ asset('img/logo-telusur.webp') }}" alt="Logo" width="300px" height="86px">
    </div>

    {{-- Navbar Mobile --}}
    <div class="navbar-start">
        <div class="dropdown">
            <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                </svg>
            </div>
            <ul tabindex="-1" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow">
                <li><a class="text-white">Item 1</a></li>
                <li>
                    <a>Parent</a>
                    <ul class="p-2">
                        <li><a class="text-white">Submenu 1</a></li>
                        <li><a class="text-white">Submenu 2</a></li>
                    </ul>
                </li>
                <li><a class="text-white">Item 3</a></li>
            </ul>
        </div>

    </div>

    {{-- Navbar Desktop --}}
    <div class="navbar-center hidden lg:flex bg-neutral-950 w-full static">
        <ul class="menu menu-horizontal px-1 mx-auto">
            <li>
                <details>
                    <summary class="text-white">POLHUKAM</summary>
                    <ul class="p-2 bg-base-100 w-40 z-1">
                        <li><a class="text-white">POLITIK</a></li>
                        <li><a class="text-white">PARLEMEN</a></li>
                    </ul>
                </details>
            </li>
            <li><a class="text-white">MEGAPOL</a></li>
            <li><a class="text-white">DAERAH</a></li>
            <li><a class="text-white">NASIONAL</a></li>
            <li><a class="text-white">INTERNASIONAL</a></li>
            <li><a class="text-white">EKUIN</a></li>
            <li><a class="text-white">SPORT</a></li>
            <li><a class="text-white">GAYA HIDUP</a></li>
            <li><a class="text-white">TELUSURIA</a></li>
            <li><a class="text-white">OPINI</a></li>
            <li><a class="text-white">INDEKS</a></li>
        </ul>
    </div>

    {{-- Search --}}
    <form class="bg-white rounded-lg border border-gray-300 flex items-center p-2 justify-between md:w-1/4"
        method="post">
        <input type="search" required placeholder="Search"
            class="appearance-none bg-transparent border-none w-full mr-3 py-1 px-2 leading-tight focus:outline-none" />
        <button type="submit" class="cursor-pointer ">
            <x-fas-search class="h-[1em] opacity-50" />
        </button>
    </form>
</div>

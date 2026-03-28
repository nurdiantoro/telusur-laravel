<footer class="w-full bg-neutral-950 p-4 md:p-10">
    <div
        class="flex-justify-between md:max-w-300 mx-auto mb-4 flex flex-col gap-6 border-b border-neutral-600 pb-4 md:flex-row">

        {{-- Tentang Kami --}}
        <div class="flex flex-col md:w-2/5">
            {{-- <div class="w-full border-b pb-4 mb-4 border-neutral-600 text-white font-bold text-xl">TENTANG KAMI</div> --}}
            <img src="{{ asset('img/logo-telusur.webp') }}" alt="Logo Telusur" loading="lazy" width="300px" height="86px">
            <div class="text-sm text-neutral-400">
                <p>Telusur.co.id diterbitkan 17 Februari 2017 oleh PT Telusur Info Media, dan telah Terverifikasi
                    Administrasi
                    dan Faktual oleh Dewan Pers No. 302/DP-Terverifikasi/K/X/2018</p>
                <br>
                <p>Pada tanggal 5 Oktober 2018, juga Terdaftar di
                    Kemenkominfo No. 00840/DJAI.PSE/04/2018.</p>
                <br>

            </div>
        </div>

        {{-- Kategori --}}
        <div class="flex flex-col md:w-2/5">
            <div class="mb-4 w-full border-b border-neutral-600 pb-2 text-xl font-bold text-white">Kategori</div>
            <div class="flex flex-wrap">
                @foreach ($categories as $category)
                    <a href="{{ route('post.category', $category->slug) }}"
                        class="hover:text-warna-02 w-1/3 py-2 text-sm text-neutral-400 transition-colors">{{ $category->name }}</a>
                @endforeach
            </div>
        </div>

        {{-- Notifikasi --}}
        <div class="flex flex-col md:w-1/5">
            <div class="mb-4 w-full border-b border-neutral-600 pb-2 text-xl font-bold text-white">Notifikasi</div>
            <div class="text-sm text-neutral-400">
                <p>Daftarkan email kamu untuk mendapatkan informasi terbaru</p>
                <form action="" method="get">
                    <input type="email" placeholder="Masukkan email Anda"
                        class="focus:ring-warna-02 mb-2 w-full rounded bg-neutral-800 p-2 text-white focus:outline-none focus:ring-2">
                    <button type="submit"
                        class="bg-warna-02 hover:bg-warna-03 w-full rounded p-2 text-white transition-colors">Berlangganan</button>
                </form>
            </div>
        </div>
    </div>

    <div class="md:max-w-300 mx-auto flex flex-wrap divide-x divide-gray-400 text-center text-xs md:text-left">
        <div class="px-2 text-neutral-500">&copy; COPYRIGHT {{ date('Y') }} telusur.co.id |
            <a href="{{ route('kebijakan') }}" class="text-warna-02 hover:underline">Kebijakan Privacy</a> |
            <a href="{{ route('pedoman') }}" class="text-warna-02 hover:underline">Pedoman Pemberitaan</a> |
            <a href="{{ route('disclaimer') }}" class="text-warna-02 hover:underline">Disclaimer</a> |
            <a href="{{ route('about') }}" class="text-warna-02 hover:underline">Tentang Kami</a> |
            <a href="{{ route('terms') }}" class="text-warna-02 hover:underline">Terms Of Use</a>
        </div>
    </div>
</footer>

<footer class="bg-neutral-950  w-full md:p-10 p-4">
    <div
        class="flex gap-6 md:flex-row flex-col flex-justify-between md:max-w-300 mx-auto border-b pb-4 mb-4 border-neutral-600">
        <div class="flex flex-col">
            {{-- <div class="w-full border-b pb-4 mb-4 border-neutral-600 text-white font-bold text-xl">TENTANG KAMI</div> --}}
            <img src="{{ asset('img/logo-telusur.webp') }}" alt="Logo Telusur" loading="lazy" width="300px" height="86px">
            <div class="text-neutral-400 text-sm">
                <p>Telusur.co.id diterbitkan 17 Februari 2017 oleh PT Telusur Info Media, dan telah Terverifikasi
                    Administrasi
                    dan Faktual oleh Dewan Pers No. 302/DP-Terverifikasi/K/X/2018</p>
                <br>
                <p>Pada tanggal 5 Oktober 2018, juga Terdaftar di
                    Kemenkominfo No. 00840/DJAI.PSE/04/2018.</p>
                <br>

            </div>
        </div>
        <div class="flex flex-col">
            <div class="w-full border-b pb-4 mb-4 border-neutral-600 text-white font-bold text-xl">Kategori</div>
            <div class="flex flex-wrap">
                @foreach ($categories as $category)
                    <a href="{{ route('post.category', $category->slug) }}"
                        class="text-neutral-400 text-sm hover:text-warna-02 transition-colors w-1/2 py-2">{{ $category->name }}</a>
                @endforeach
            </div>
        </div>
        <div class="flex flex-col">
            <div class="w-full border-b pb-4 mb-4 border-neutral-600 text-white font-bold text-xl">Notifikasi</div>
            <div class="text-neutral-400 text-sm">
                <p>Daftarkan email kamu untuk mendapatkan informasi terbaru</p>
                <form action="" method="get">
                    <input type="email" placeholder="Masukkan email Anda"
                        class="w-full p-2 rounded bg-neutral-800 text-white mb-2 focus:outline-none focus:ring-2 focus:ring-warna-02">
                    <button type="submit"
                        class="w-full bg-warna-02 text-white p-2 rounded hover:bg-warna-03 transition-colors">Berlangganan</button>
                </form>
            </div>
        </div>
    </div>


    <div class="flex divide-x divide-gray-400 text-xs md:max-w-300 mx-auto flex-wrap text-center md:text-left">
        <div class="px-2 text-neutral-500">&copy; COPYRIGHT {{ date('Y') }} telusur.co.id |
            <a href="{{ route('kebijakan') }}" class="text-warna-02 hover:underline">Kebijakan Privacy</a> |
            <a href="{{ route('pedoman') }}" class="text-warna-02 hover:underline">Pedoman Pemberitaan</a> |
            <a href="{{ route('disclaimer') }}" class="text-warna-02 hover:underline">Disclaimer</a> |
            <a href="{{ route('about') }}" class="text-warna-02 hover:underline">Tentang Kami</a> |
            <a href="{{ route('terms') }}" class="text-warna-02 hover:underline">Terms Of Use</a>
        </div>
    </div>
</footer>
</body>

</html>

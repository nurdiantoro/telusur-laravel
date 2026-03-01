@include('layout.header')
<div class="md:w-300 mx-auto px-4 py-8 min-h-[200vh] bg-white">

    {{-- Highlight title --}}
    <div class="flex flex-row justify-between gap-2 pb-3 mb-6 border-b border-gray-300">
        <span class="px-2 py-1 bg-linear-to-r from-merah-01 to-merah-02 text-white font-bold">Hot news</span>
        <span class="text-lg text-gray-700 grow">This is the highlight section of our application. </span>
        <div class="hidden md:flex gap-2">
            <button type="button" class="cursor-pointer text-gray-500 hover:text-gray-700">
                {{ svg('feathericon-arrow-left-circle') }}
            </button>
            <button type="button" class="cursor-pointer text-gray-500 hover:text-gray-700">
                {{ svg('feathericon-arrow-right-circle') }}
            </button>
        </div>
    </div>

    <div class="flex flex-row gap-8">

        {{-- Main News --}}
        <div class="w-2/3 flex flex-col gap-12">
            <div class="relative aspect-2/1 bg-cover bg-center flex flex-col justify-end p-6 group"
                style="background-image: url('{{ $post->getFirstMediaUrl('preview', 'preview') ?: $post->getFirstMediaUrl('preview') }}');">
                <!-- overlay agar teks lebih terbaca -->
                <div class="absolute inset-0 bg-linear-to-t from-black/60 via-black/20 to-transparent ">
                </div>

                <!-- konten -->
                <div class="relative text-white">
                    <span
                        class="px-2 py-1 bg-linear-to-r from-merah-01 to-merah-02 text-white font-bold mb-2 inline-block">{{ $firstCategory->name }}</span>
                    <h2 class="text-2xl font-bold mb-2">{{ $post->title }}</h2>
                    <span>{{ $post->created_at->format('j F Y') }}</span>
                </div>

                {{-- Arrow --}}
                <div
                    class="absolute top-0 bottom-0 right-0 text-white transition-opacity cursor-pointer z-10 flex items-center justify-center">
                    <div
                        class="w-8 h-8 text-xl translate-x-1/2 group-hover:-translate-x-1/2 opacity-0 group-hover:opacity-100 duration-400 ease-out">
                        {{ svg('fas-arrow-right') }}</div>
                </div>
                <div
                    class="absolute top-0 bottom-0 left-0 text-white transition-opacity cursor-pointer z-10 flex items-center justify-center">
                    <div
                        class="w-8 h-8 text-xl -translate-x-1/2 group-hover:translate-x-1/2 opacity-0 group-hover:opacity-100 duration-400 ease-out">
                        {{ svg('fas-arrow-left') }}</div>
                </div>
            </div>
            <div>
                <div
                    class="border-b-6 mb-6 border-gray-200 before:absolute before:w-16 before:top-full before:h-1.5 before:bg-warna-01 relative">
                    <h2 class="text-2xl font-bold mb-6">Berita Terbaru</h2>
                </div>
                @foreach ($beritaPopulers as $index => $post)
                    {{-- 2 berita utama --}}
                    <div class="featured-post">
                        {{-- style khusus --}}
                        <img src="{{ $post->cover_preview }}" alt="{{ $post->title }}">
                        {{ $post->title }}
                    </div>
                    {{-- 4 berita lainnya --}}
                    <div class="regular-post">
                        {{ $post->title }}
                    </div>
                @endforeach
            </div>
        </div>
        <div class="w-1/3">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Vel tempore ut nemo. Quis quidem quae ipsum, maxime
            quaerat necessitatibus est facere quasi animi, nesciunt tempora rem vel nobis libero sequi.</div>
    </div>




</div>
@include('layout.footer')

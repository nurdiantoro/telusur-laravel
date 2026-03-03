@include('layout.header')
<div class="md:w-300 mx-auto px-4 py-8 min-h-[200vh] bg-white">

    <div class="flex flex-col md:flex-row gap-8">

        {{-- Main Content --}}
        <div class="md:w-2/3 flex flex-col">
            <div class="border-b border-gray-200 pb-4 mb-4">
                <h1 class="text-3xl font-bold">{{ $post->title }}</h1>
            </div>
            <div>
                <span>by {{ $post->author->name }}</span>
                <span>{{ $post->created_at->format('F d, Y') }}</span>
            </div>
            <div class="mb-6">
                <img src="{{ $post->cover_preview ? $post->cover_preview : asset('img/no_image.webp') }}"
                    alt="{{ $post->title }}" class="w-full h-auto">
                <span class="text-sm italic">{{ $post->caption }}</span>
                @php
                    $url = urlencode(url()->current());
                    $title = urlencode($post->title);
                @endphp
                <div class="flex items-center justify-end gap-1">
                    <!-- Facebook -->
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $url }}" target="_blank"
                        rel="noopener"
                        class="px-3 py-2 text-sm bg-blue-600 text-white hover:bg-blue-700 flex flex-row items-center gap-1">
                        <div class="w-4 h-4"><x-fab-facebook /></div> Facebook
                    </a>

                    <!-- Twitter / X -->
                    <a href="https://twitter.com/intent/tweet?url={{ $url }}&text={{ $title }}"
                        target="_blank" rel="noopener"
                        class="px-3 py-2 text-sm bg-black text-white hover:bg-gray-800 flex flex-row items-center gap-1">
                        <div class="w-4 h-4"><x-fab-x-twitter /></div> X
                    </a>

                    <!-- WhatsApp -->
                    <a href="https://wa.me/?text={{ $title }}%20{{ $url }}" target="_blank"
                        rel="noopener"
                        class="px-3 py-2 text-sm bg-green-600 text-white hover:bg-green-700 flex flex-row items-center gap-1">
                        <div class="w-4 h-4"><x-fab-whatsapp /></div> WhatsApp
                    </a>

                    <!-- Telegram -->
                    <a href="https://t.me/share/url?url={{ $url }}&text={{ $title }}" target="_blank"
                        rel="noopener"
                        class="px-3 py-2 text-sm bg-sky-500 text-white hover:bg-sky-600 flex flex-row items-center gap-1">
                        <div class="w-4 h-4"><x-fab-telegram /></div> Telegram
                    </a>
                </div>
            </div>
            <div>{!! $post->content !!}</div>
        </div>

        {{-- side div --}}
        <div class="md:w-1/3">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Vel tempore ut nemo. Quis quidem quae ipsum, maxime
            quaerat necessitatibus est facere quasi animi, nesciunt tempora rem vel nobis libero sequi.</div>
    </div>
</div>
@include('layout.footer')

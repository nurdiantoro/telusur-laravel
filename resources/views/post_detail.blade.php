@include('layout.header')
<div class="md:w-300 mx-auto px-4 py-8 min-h-[200vh] bg-white">

    <div class="flex flex-col md:flex-row gap-8">

        {{-- Main Content --}}
        <div class="md:w-3/4 flex flex-col">
            <div class="hidden md:flex flex-row gap-2 items-center mb-4">
                <a href="{{ route('home') }}" class="text-sm text-warna-01 font-bold hover:underline">Home</a>
                <span class="w-2 h-auto"><x-fas-angle-right /></span>
                <a href="{{ $post->category->slug }}" class="text-sm text-gray-600 hover:underline">
                    {{ $post->category->name }}
                </a>
            </div>

            <article>
                {{-- Title --}}
                <h1 class="text-3xl font-bold mb-2">{{ $post->title }}</h1>

                {{-- Meta --}}
                <div class="text-gray-500">
                    <span>by {{ $post->author->name }}</span> -
                    <time>{{ $post->created_at->translatedFormat('j F Y') }}</time>
                </div>

                {{-- Thumbnail --}}
                <figure class="mb-6 mt-4">
                    <img src="{{ $post->spatie_preview ? $post->spatie_preview : asset('img/no_image.webp') }}"
                        alt="{{ $post->title }}" class="w-full h-auto">
                    <figcaption class="text-sm italic">{{ $post->caption }}</figcaption>
                    @php
                        $url = urlencode(url()->current());
                        $title = urlencode($post->title);
                    @endphp
                    <div class="flex items-center justify-end gap-1 mt-2">
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
                        <a href="https://t.me/share/url?url={{ $url }}&text={{ $title }}"
                            target="_blank" rel="noopener"
                            class="px-3 py-2 text-sm bg-sky-500 text-white hover:bg-sky-600 flex flex-row items-center gap-1">
                            <div class="w-4 h-4"><x-fab-telegram /></div> Telegram
                        </a>
                    </div>
                </figure>

                {{-- Content --}}
                <div class="article-content border-b border-gray-200 mb-6">{!! str($post->content)->sanitizeHtml() !!}</div>
            </article>

            {{-- Komentar --}}
            <div class="mt-6 mb-6">
                <h2 class="font-bold mb-4">Tinggalkan Komentar</h2>
                <article class="border-b border-gray-200 p-4 rounded-lg bg-gray-100">
                    <h3 class="font-bold">Nama komentator</h3>
                    <time class="text-sm text-gray-700">10 Agustus 2026 - 13:00</time>
                    <p class="mt-2">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Praesentium laudantium
                        at, id sequi
                        quibusdam facere repellat modi dolores perspiciatis ipsa repellendus! Autem dignissimos veniam
                        adipisci cumque corrupti eum mollitia hic.</p>
                </article>
            </div>

            {{-- Form komentar --}}
            <form action="" class="p-4 rounded-lg bg-gray-100 flex flex-col gap-2 mb-6">
                @csrf
                <h2 class="font-bold">Komentar</h2>
                <textarea name="comment" id="comment" rows="5"
                    class="w-full border border-gray-300 bg-white rounded-md p-2 focus:outline-none focus:drop-shadow"></textarea>
                <div class="flex flex-col md:flex-row gap-2 md:gap-4">
                    <div class="md:w-1/2">
                        <input type="text" name="comment" placeholder="Nama"
                            class="w-full border border-gray-300 bg-white rounded-md p-2 focus:outline-none focus:drop-shadow">
                    </div>
                    <div class="md:w-1/2">
                        <input type="email" name="email" placeholder="Email"
                            class="w-full border border-gray-300 bg-white rounded-md p-2 focus:outline-none focus:drop-shadow">
                    </div>
                    <input type="hidden" name="jangan_diisi">
                </div>
                <button type="submit"
                    class="bg-warna-02 text-white px-4 py-2 rounded-md hover:bg-warna-01 self-end w-full md:w-auto block">
                    Kirim
                </button>
            </form>

            <div>
                <h2 class="font-bold">Artikel Terkait</h2>
                <div class="flex flex-wrap">
                    @foreach ($otherArticles as $article)
                        <div class="p-2 w-1/3">
                            <div class="py-4 group">
                                <img src="{{ $post->cover_preview ?: asset('img/no_image.webp') }}"
                                    alt="{{ $post->title }}" class="w-full h-auto object-cover rounded-md">
                                <a href="{{ route('post.detail', ['category' => $article->category->slug, 'slug' => $article->slug]) }}"
                                    class=" text-warna-01 group-hover:text-warna-02 font-bold block mt-2">
                                    {{ $article->title }}
                                </a>
                                <p class="text-sm text-gray-600">{{ $article->publish_time->format('F d, Y') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- side div --}}
        <div class="md:w-1/4">
            @include('layout.sidebar')
        </div>
    </div>
</div>
@include('layout.footer')

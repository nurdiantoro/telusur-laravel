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

            <article class="border-b border-gray-200 mb-6">
                {{-- Title --}}
                <h1 class="text-3xl font-bold mb-2">{{ $post->title }}</h1>

                {{-- Meta --}}
                <div class="text-gray-500">
                    <span>by {{ $post->author->name ?: 'Admin' }}</span> -
                    <time>{{ $post->publish_time->translatedFormat('j F Y') }}</time>
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
                <div class="article-content">{!! str($post->content)->sanitizeHtml() !!}</div>

                <div class="flex gap-2 flex-row flex-wrap mb-6 text-gray-700">
                    <div class="flex flex-row justify-center items-center gap-2">
                        <span class="w-4"> <x-fas-tag /></span>
                        <span> tags :</span>
                    </div>
                    @foreach ($post->tags as $tag)
                        <a href="{{ route('post.tag', $tag->slug) }}"
                            class="px-1 rounded bg-gray-100 border-gray-200 border text-sm hover:bg-gray-200">{{ $tag->name }}</a>
                    @endforeach
                </div>
            </article>

            {{-- Komentar --}}
            @if ($comments->count() > 0)
                <div class="mt-6 mb-6">
                    <h2 class="font-bold mb-4">Tinggalkan Komentar</h2>
                    @foreach ($comments as $comment)
                        <article class="border-b border-gray-200 p-4 rounded-lg bg-gray-100 flex flex-col gap-2">
                            <h3 class="font-bold capitalize">{{ $comment->name }}</h3>
                            <p class="">{{ $comment->comment }}</p>
                            <time
                                class="text-xs text-gray-700 self-end">{{ $comment->created_at->translatedFormat('j F Y - H:i') }}</time>
                        </article>
                    @endforeach
                </div>
            @endif

            {{-- Form komentar --}}
            <form method="POST" action="{{ route('post.comment', $post->id) }}"
                class="p-4 rounded-lg bg-gray-100 flex flex-col gap-2 mb-6">
                @csrf
                <h2 class="font-bold">Komentar</h2>
                <textarea name="comment" id="comment" rows="5"
                    class="w-full border border-gray-300 bg-white rounded-md p-2 focus:outline-none focus:drop-shadow">{{ old('comment') }}</textarea>
                @error('comment')
                    <small class="text-red-500">{{ $message }}</small>
                @enderror

                <div class="flex flex-col md:flex-row gap-2 md:gap-4">

                    <div class="md:w-1/2">
                        <input type="text" name="name" placeholder="Nama" value="{{ old('name') }}"
                            class="w-full border border-gray-300 bg-white rounded-md p-2 focus:outline-none focus:drop-shadow">
                        @error('name')
                            <small class="text-red-500">{{ $message }}</small>
                        @enderror
                    </div>


                    <div class="md:w-1/2">
                        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}"
                            class="w-full border border-gray-300 bg-white rounded-md p-2 focus:outline-none focus:drop-shadow">
                        @error('email')
                            <small class="text-red-500">{{ $message }}</small>
                        @enderror
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
                            <a href="{{ route('post.detail', ['category' => $article->category->slug, 'slug' => $article->slug]) }}"
                                class="py-4 group">

                                <div class="aspect-video overflow-hidden rounded-md">
                                    <img src="{{ $article->spatie_preview ?: asset('img/no_image.webp') }}"
                                        alt="{{ $article->title }}" class="w-full h-full object-cover">
                                </div>

                                <div class="text-warna-01 group-hover:text-warna-02 font-bold block mt-2">
                                    {{ $article->title }}
                                </div>

                                <p class="text-sm text-gray-600">
                                    {{ $article->publish_time->translatedFormat('j F Y') }}
                                </p>

                            </a>
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

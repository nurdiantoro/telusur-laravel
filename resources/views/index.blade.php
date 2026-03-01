@include('layout.header')
<div class="md:w-300 mx-auto px-4 py-8 min-h-[200vh] bg-white">

    {{-- Highlight title --}}
    <div class="flex flex-row justify-between gap-2 pb-3 mb-6 border-b border-gray-300">
        <span class="px-2 py-1 bg-linear-to-r from-red-500 to-red-700 text-white font-bold ">Hot news</span>
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

    {{-- Hightlight News --}}
    <div class="flex flex-row">
        <div class="w-2/3">
            <div>
                <img src="{{ $post->getFirstMediaUrl('cover', 'webp') ?: $post->getFirstMediaUrl('cover') }}"
                    alt="{{ $post->title }}" />
                <h1 class="text-4xl font-bold mb-4">Welcome to Our Application</h1>
                <p class="text-lg text-gray-700 mb-6">This is the welcome page of our application. Here you can find
                    information
                    about our features and how to get started.</p>
                <a href="/register" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Get
                    Started</a>
            </div>
        </div>
        <div class="w-1/3">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Vel tempore ut nemo. Quis quidem quae ipsum, maxime
            quaerat necessitatibus est facere quasi animi, nesciunt tempora rem vel nobis libero sequi.</div>
    </div>


</div>
@include('layout.footer')

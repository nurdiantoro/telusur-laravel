<div x-data="{
    open: false,
    selected: $wire.$get('data.gallery_id'),

    select(id) {
        this.selected = id
        $wire.set('data.gallery_id', id)
        this.open = false
    }
}">

    <!-- Trigger -->
    <button type="button" @click="open = true" class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-500">
        Pilih Dari Gallery
    </button>

    <!-- Selected Preview -->
    <div class="mt-3" x-show="selected">
        @foreach ($galleries as $gallery)
            <template x-if="selected == {{ $gallery->id }}">
                <img src="{{ $gallery->spatie_thumbnail }}"
                    class="aspect-video w-full overflow-hidden rounded-lg border border-gray-300 object-cover p-2">
            </template>
        @endforeach
    </div>

    <!-- Modal -->
    <div x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="max-h-[80vh] w-3/4 overflow-y-auto rounded-lg bg-white p-4">

            <!-- Header -->
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-bold">Pilih Gallery</h2>
                <button @click="open = false">✕</button>
            </div>

            <!-- Grid -->
            <div class="grid grid-cols-6 gap-4">
                @foreach ($galleries as $gallery)
                    <div class="group cursor-pointer rounded-lg border p-2 transition hover:border-blue-500 hover:bg-blue-50"
                        @click="select({{ $gallery->id }})"
                        :class="selected == {{ $gallery->id }} ? 'border-blue-500 bg-blue-50' : 'border-gray-300'">

                        <div class="aspect-video w-full overflow-hidden rounded">
                            <img src="{{ $gallery->spatie_thumbnail }}"
                                class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                        </div>
                        <div class="mt-2 line-clamp-1 text-center">
                            {{ $gallery->title }}
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>

</div>

<div x-data="{
    selected: @entangle($getStatePath()).defer
}" class="grid grid-cols-3 gap-4">

    @foreach ($galleries as $gallery)
        <div @click="selected = {{ $gallery->id }}"
            :class="selected == {{ $gallery->id }} ? 'ring-2 ring-primary-500' : ''"
            class="cursor-pointer rounded-lg border p-2 transition hover:shadow">
            <img src="{{ $gallery->spatie_thumbnail }}" class="h-32 w-full rounded object-cover">

            <div class="mt-2 text-center text-sm font-medium">
                {{ $gallery->title }}
            </div>
        </div>
    @endforeach

</div>

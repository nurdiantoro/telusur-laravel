<div x-data="{
    open: false,
    selected: null,
    galleries: [],
    search: '',
    page: 1,
    lastPage: 1,
    loading: false,
    uploading: false,
    uploadProgress: 0,
    file: null,
    preview: null,
    title: '',
    selectedItem: null,

    init() {
        this.open = false
        this.selected = $wire?.$get('data.gallery_id') ?? null
        if (this.selected) {
            this.fetchSelected()
        }
        this.fetchData()
    },

    async fetchSelected() {
        try {
            let res = await fetch(`/api/galleries/id/${this.selected}`)
            let data = await res.json()

            this.selectedItem = data.data
        } catch (e) {
            console.error(e)
        }
    },

    async fetchData() {
        this.loading = true

        try {
            let res = await fetch(`/api/galleries?search=${this.search}&page=${this.page}`)
            let data = await res.json()

            this.galleries = data.data
            this.lastPage = data.meta.last_page
        } catch (e) {
            console.error(e)
        }

        this.loading = false
    },

    handleFile(e) {
        const file = e.target.files[0]
        if (!file) return

        this.file = file
        this.preview = URL.createObjectURL(file)
        this.title = file.name.split('.').slice(0, -1).join('.')
    },

    upload() {
        if (!this.file) return

        this.uploading = true
        this.uploadProgress = 0

        const formData = new FormData()
        formData.append('file', this.file)
        formData.append('title', this.title)

        const xhr = new XMLHttpRequest()

        xhr.open('POST', '/api/galleries/upload', true)
        xhr.setRequestHeader('Accept', 'application/json')

        xhr.upload.onprogress = (event) => {
            if (event.lengthComputable) {
                this.uploadProgress = Math.round((event.loaded / event.total) * 100)
            }
        }

        xhr.onload = () => {
            if (xhr.status === 200) {
                const data = JSON.parse(xhr.responseText)

                this.galleries.unshift(data.data)
                this.selected = data.data.id
                $wire.$set('data.gallery_id', data.data.id)

                this.resetUpload()
            } else {
                console.error(xhr.responseText)
            }

            this.uploading = false
        }

        xhr.onerror = () => {
            console.error('Network error')
            this.uploading = false
        }

        xhr.send(formData)
        this.selectedItem = data.data
    },

    resetUpload() {
        this.file = null
        this.preview = null
        this.title = ''
        this.uploadProgress = 0
        this.$refs.file.value = ''
    },

    select(id) {
        this.selected = id
        this.selectedItem = this.galleries.find(g => g.id === id) ?? null
        $wire.$set('data.gallery_id', id)
        this.open = false
    },
}" x-init="init()">

    <!-- Trigger -->
    <input type="file" class="hidden" x-ref="file" @change="handleFile">
    <button type="button" @click="$refs.file.click()" :disabled="uploading"
        class="rounded-lg bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-500">
        Upload Image
    </button>
    <button type="button" @click="open = true; fetchData()"
        class="rounded-lg bg-gray-600 px-4 py-2 text-sm text-white hover:bg-gray-500">
        Pilih Dari Gallery
    </button>
    <div x-show="uploading" class="mt-2 w-full">
        <div class="h-2 w-full rounded bg-gray-200">
            <div class="h-2 rounded bg-blue-500 transition-all" :style="'width:' + uploadProgress + '%'"></div>
        </div>

        <div class="mt-1 text-xs text-gray-600" x-text="uploadProgress + '%'"></div>
    </div>

    <!-- Selected Preview -->
    <template x-if="selectedItem">
        <div class="mt-3">
            <img :src="selectedItem.thumbnail"
                class="aspect-video w-full rounded-lg border border-gray-300 object-cover p-2">
        </div>
    </template>

    {{-- Upload Preview --}}
    <div x-show="preview" class="mt-3 space-y-2">

        <!-- Preview -->
        <img :src="preview" class="aspect-video w-full rounded-lg border object-cover">

        <!-- Input Title -->
        <input type="text" x-model="title" placeholder="Nama gambar" class="w-full rounded border px-3 py-2">

        <!-- Action -->
        <div class="flex gap-2">
            <button type="button" @click="upload" class="rounded bg-green-600 px-4 py-2 text-white">
                Simpan
            </button>

            <button type="button" @click="resetUpload" class="rounded bg-gray-400 px-4 py-2 text-white">
                Batal
            </button>
        </div>

    </div>

    <!-- Modal -->
    <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="max-h-[90vh] w-full max-w-5xl overflow-y-auto rounded-lg bg-white p-4">

            <!-- Header -->
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-bold">Pilih Gallery</h2>

                <div class="flex items-center gap-2">
                    <button type="button" @click="open = false">
                        <x-heroicon-c-x-mark class="h-6 text-[#ef4443]" />
                    </button>
                </div>
            </div>

            <!-- Search -->
            <div class="max-w-125 mx-auto">
                <input type="text" x-model="search" @input.debounce.300ms="page = 1; fetchData()"
                    placeholder="Cari Gambar"
                    class="mb-4 w-full rounded-lg px-3 py-2 ring-1 ring-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <!-- Loading -->
            {{-- <div x-show="loading" class="py-4 text-center">
                Loading...
            </div> --}}

            <!-- Grid -->
            <div class="grid grid-cols-2 gap-2 sm:grid-cols-3 md:grid-cols-4">
                <template x-for="item in galleries" :key="item.id">
                    <div @click="select(item.id)"
                        :class="selected == item.id ?
                            'border-blue-500 bg-blue-50' :
                            'border-white'"
                        class="group cursor-pointer rounded-lg border p-2 transition hover:border-blue-500 hover:bg-blue-50">
                        <div class="aspect-video w-full overflow-hidden rounded bg-gray-50">
                            <img :src="item.thumbnail || '{{ asset('img/no_image.webp') }}'"
                                class="h-full w-full object-cover transition group-hover:scale-105">
                        </div>

                        <div class="mt-2 line-clamp-1 text-center text-xs" x-text="item.title"></div>
                    </div>
                </template>
            </div>

            <!-- Pagination -->
            <div class="mt-4 flex items-center justify-center gap-4">
                <button type="button" class="rounded border bg-blue-500 px-3 py-1 text-white hover:bg-blue-400"
                    @click="if(page > 1){ page--; fetchData() }">
                    Prev
                </button>

                <span x-text="'Page '+page + ' of ' + lastPage"></span>

                <button type="button" class="rounded border bg-blue-500 px-3 py-1 text-white hover:bg-blue-400"
                    @click="if(page < lastPage){ page++; fetchData() }">
                    Next
                </button>
            </div>

        </div>
    </div>

</div>

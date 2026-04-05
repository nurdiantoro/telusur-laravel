function galleryPicker() {
    return {
        /*
        |--------------------------------------------------------------------------
        | State
        |--------------------------------------------------------------------------
        */
        open: false,
        selected: null,
        selectedItem: null,

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

        /*
        |--------------------------------------------------------------------------
        | Init
        |--------------------------------------------------------------------------
        */
        init() {
            this.open = false

            // Ambil value dari Livewire (jika ada)
            if (typeof $wire !== 'undefined') {
                this.selected = $wire.$get('data.gallery_id') ?? null
            }

            // Ambil data gambar terpilih (untuk edit case)
            if (this.selected) {
                this.fetchSelected()
            }

            // Load list gallery
            this.fetchData()
        },

        /*
        |--------------------------------------------------------------------------
        | Fetch Selected Image (fix pagination bug)
        |--------------------------------------------------------------------------
        */
        async fetchSelected() {
            try {
                let res = await fetch(`/api/galleries/id/${this.selected}`)
                let data = await res.json()

                this.selectedItem = data.data
            } catch (e) {
                console.error(e)
            }
        },

        /*
        |--------------------------------------------------------------------------
        | Fetch Gallery List (Paginated)
        |--------------------------------------------------------------------------
        */
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

        /*
        |--------------------------------------------------------------------------
        | Handle File Input
        |--------------------------------------------------------------------------
        */
        handleFile(e) {
            const file = e.target.files[0]
            if (!file) return

            this.file = file
            this.preview = URL.createObjectURL(file)

            // Auto isi title tanpa extension
            this.title = file.name.split('.').slice(0, -1).join('.')
        },

        /*
        |--------------------------------------------------------------------------
        | Upload Image (with progress)
        |--------------------------------------------------------------------------
        */
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

            // Progress
            xhr.upload.onprogress = (event) => {
                if (event.lengthComputable) {
                    this.uploadProgress = Math.round((event.loaded / event.total) * 100)
                }
            }

            // Success
            xhr.onload = () => {
                if (xhr.status === 200) {
                    const data = JSON.parse(xhr.responseText)

                    // Inject ke list
                    this.galleries.unshift(data.data)

                    // Set selected
                    this.selected = data.data.id
                    this.selectedItem = data.data

                    // Sync ke Livewire
                    if (typeof $wire !== 'undefined') {
                        $wire.$set('data.gallery_id', data.data.id)
                    }

                    this.resetUpload()
                } else {
                    console.error(xhr.responseText)
                }

                this.uploading = false
            }

            // Error
            xhr.onerror = () => {
                console.error('Network error')
                this.uploading = false
            }

            xhr.send(formData)
        },

        /*
        |--------------------------------------------------------------------------
        | Reset Upload State
        |--------------------------------------------------------------------------
        */
        resetUpload() {
            this.file = null
            this.preview = null
            this.title = ''
            this.uploadProgress = 0

            if (this.$refs.file) {
                this.$refs.file.value = ''
            }
        },

        /*
        |--------------------------------------------------------------------------
        | Select Image
        |--------------------------------------------------------------------------
        */
        select(id) {
            this.selected = id

            // Cari dari list dulu
            this.selectedItem = this.galleries.find(g => g.id === id) ?? null

            // Fallback kalau gak ketemu (pagination case)
            if (!this.selectedItem) {
                this.fetchSelected()
            }

            if (typeof $wire !== 'undefined') {
                $wire.$set('data.gallery_id', id)
            }

            this.open = false
        }
    }
}

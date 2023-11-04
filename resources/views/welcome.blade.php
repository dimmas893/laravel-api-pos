<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Document</title>
</head>

<body>
    <div id="app">
        @{{ name }}

        <div class="row mb-4">
            <div class="row mt-3">
                <div class="col">
                    <div class="input-group mb-3">
                        <input v-model="search" type="text" class="form-control" placeholder="Search"
                            aria-label="Cari" aria-describedby="basic-addon1" />
                        <div class="input-group-prepend">
                            <span @click="loadData" style="cursor: pointer" class="input-group-text"
                                id="basic-addon1"><button>Cari</button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Dropdown filter untuk bestseller -->
            <div class="col-6">
                <select v-model="categoryFilter" @change="loadData" class="form-control">
                    <option value="" selected>--- All category ---</option>
                    <option v-for="category in categories" :value="category.id">
                        @{{ category.name }}
                    </option>
                </select>
            </div>
            <div class="col-6" id="myTable_length">
                <select v-model="perPage" @change="loadData" class="form-control">
                    <option value="10" selected>10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-lg-6">
                                <h5 class="card-title fw-semibold mb-4">Table Product</h5>
                            </div>
                            <div class="col-lg-6 text-end">
                                <a href="/admin/product/create" class="btn btn-success">
                                    <i class="bi bi-plus-circle"></i> Create
                                </a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table text-nowrap mb-0 align-middle" id="myTable">
                                <thead class="text-dark fs-4">
                                    <tr>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Id</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Name</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Kode</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Stock</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Price</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Category</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Description</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Image</h6>
                                        </th>
                                        <th class="border-bottom-0">
                                            <h6 class="fw-semibold mb-0">Action</h6>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="col-md-4 mt-4" v-for="(product, index) in products"
                                        :key="product.id">
                                        <td>@{{ calculateProductId(index) }}</td>
                                        <td>@{{ product.name }}</td>
                                        <td>@{{ product.kode }}</td>
                                        <td>@{{ product.stock }}</td>
                                        <td>@{{ product.price }}</td>
                                        <td>@{{ product.category_id }}</td>
                                        <td>@{{ product.description }}</td>
                                        <td>
                                            <img :src="getProductImageUrl(product.image)" alt="Product Image"
                                                style="max-width: 100px; max-height: 100px" />
                                        </td>

                                        <td>
                                            <a class="btn btn-info" :href="'/admin/product/edit/' + product.id"><i
                                                    class="bi bi-pencil-fill"></i>
                                            </a>

                                            <button class="btn btn-danger mx-2" @click="deleteProduct(product.id)">
                                                delete
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <li class="page-item" :class="{ disabled: currentPage === 1 }">
                                <a class="page-link" @click="filter(currentPage - 1)" href="#">
                                    Kembali
                                </a>
                            </li>
                            <li class="page-item" v-for="(paginate, index) in link" :key="index"
                                :class="{ active: paginate.active }">
                                <!-- Hanya tampilkan label yang berupa angka -->
                                <a class="page-link" @click="filter(paginate.label)"
                                    v-if="paginate.label.match(/^\d+$/)" href="#">
                                    @{{ paginate.label }}
                                </a>
                            </li>
                            <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                                <a class="page-link" @click="filter(currentPage + 1)" href="#">
                                    Selanjutnya
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.0/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-loading-overlay@1.1.0/dist/js-loading-overlay.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js">
    </script>
    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute(
            'content');




        JsLoadingOverlay.setOptions({
            'overlayBackgroundColor': '#666666',
            'overlayOpacity': 0.6,
            "spinnerIcon": "ball-spin",
            'spinnerColor': '#000',
            'spinnerSize': '2x',
            'overlayIDName': 'overlay',
            'spinnerIDName': 'spinner',
            'offsetY': 0,
            'offsetX': 0,
            'lockScroll': false,
            'containerID': null,
        });
        const member = {
            name: 'dimmas',
            categoryFilter: "",
            products: [], // Data produk
            search: "", // Pencarian
            perPage: 10, // Jumlah item per halaman
            currentPage: 1, // Halaman saat ini
            totalPages: 1, // Total halaman
            link: [], // Informasi halaman
            categories: [],
            token: '{{ csrf_token() }}',
        }
        const vm = new Vue({
            el: '#app',
            data: member,
            mounted() {
                this.loadData();
                this.categorieData();
            },
            methods: {

                getProductImageUrl(image) {
                    const URL_BASE = "https://anandadimmasbudiarto.my.id/aplikasi/pos/public/assets/images/";
                    return URL_BASE + image;
                },
                setProducts(data) {
                    this.products = data.data;
                    this.totalPages = data.last_page;
                    this.link = data.links;
                },
                loadData() {
                    JsLoadingOverlay.show();
                    axios
                        .get("https://anandadimmasbudiarto.my.id/aplikasi/pos/api/products?page=" +
                            this.currentPage +
                            "&category_id=" +
                            this.categoryFilter +
                            "&perPage=" +
                            this.perPage +
                            "&search=" +
                            this.search
                        )
                        .then((response) => {
                            const currentPageBeforeFilter = this
                                .currentPage; // Simpan halaman saat ini sebelum filter

                            this.setProducts(response.data);

                            // Jika hanya ada satu halaman setelah filter, pindah ke halaman terakhir yang berisi data
                            if (response.data.last_page === 1) {
                                this.currentPage = response.data.last_page;
                            } else {
                                // Jika tidak, periksa jika halaman saat ini masih valid
                                if (this.currentPage > response.data.last_page) {
                                    // Jika tidak valid, atur halaman saat ini ke halaman terakhir yang berisi data
                                    this.currentPage = response.data.last_page;
                                }
                            }

                            // Jika halaman sebelum filter berbeda dengan halaman saat ini, perbarui data
                            if (currentPageBeforeFilter !== this.currentPage) {
                                this.loadData();
                            }
                        })
                        .finally(() => {
                            JsLoadingOverlay.hide();
                            // this.$loader.hide();
                        });
                },
                deleteProduct(productId) {
                    const options = {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': this.token
                        }
                    };
                    const self = this;
                    const deleteButton = $(`.btn-delete[data-id="${productId}"]`);
                    const allDeleteButtons = $(".btn-delete"); // Mengambil semua tombol "Delete"

                    // Simpan halaman saat ini sebelum menghapus
                    const currentPageBeforeDelete = this.currentPage;

                    // Menonaktifkan semua tombol "Delete" selain yang sedang diklik
                    Swal.fire({
                        title: "Konfirmasi",
                        text: "Apakah Anda yakin ingin menghapus produk ini?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Ya, Hapus",
                        cancelButtonText: "Tidak",
                    }).then((result) => {
                        // Mengaktifkan kembali semua tombol "Delete" setelah proses selesai
                        if (result.isConfirmed) {
                            allDeleteButtons.attr("disabled", true).html("disable klik");
                            deleteButton.html("Loading...");
                            axios
                                .delete("https://anandadimmasbudiarto.my.id/aplikasi/pos/api/products/" +
                                    productId, options)

                                .then((response) => {
                                    if (response.status === 200) {
                                        // Setelah penghapusan berhasil, periksa jumlah data yang tersisa
                                        if (this.products.length === 1) {
                                            // Jika hanya ada satu data di halaman paginate, kembali ke halaman sebelumnya atau halaman saat ini - 1
                                            if (currentPageBeforeDelete > 1) {
                                                this.filter(currentPageBeforeDelete - 1);
                                            } else {
                                                this.loadData();
                                            }
                                        } else {
                                            // Jika masih ada data lain di halaman paginate, perbarui data
                                            this.loadData();
                                        }
                                    } else {
                                        Swal.fire("Gagal!", "Gagal menghapus produk.", "error");
                                    }

                                })
                                .catch((error) => {
                                    console.error(error);
                                    Swal.fire(
                                        "Error!",
                                        "Terjadi kesalahan saat menghapus produk.",
                                        "error"
                                    );
                                });
                        }
                    });
                },
                filter(page) {
                    this.currentPage = page; // Set halaman saat ini
                    this.loadData(); // Memuat data sesuai dengan halaman yang dipilih
                },
                categorieData() {
                    axios
                        .get("https://anandadimmasbudiarto.my.id/aplikasi/pos/api/categories")
                        .then((response) => this.setCategories(response.data.data))
                        .finally(() => {
                            // Matikan loading setelah selesai
                        });
                },
                calculateProductId(index) {
                    return (this.currentPage - 1) * this.perPage + index + 1;
                },

                setCategories(data) {
                    this.categories = data;
                },
            },
        })
    </script>
</body>

</html>

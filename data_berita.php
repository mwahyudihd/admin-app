<?php
$current_page = 'news';
require 'controllers/auth.php';
?>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Table - Berita</title>
    <!-- Link ke file CSS Tailwind atau stylesheet Anda -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/images/ulbi-icons.png" type="image/x-icon">
    <script>
        const removeOneNews = (id, base) => Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
        }).then((result) => {
        if (result.isConfirmed) {
            fetch(`${base}api/data_berita.php?id=${id}&action=delete`, {
                method: 'DELETE',
            })
            .then(response => response.json())
            .then(data => {
                if (data.statusCode == 200) {
                    sessionStorage.setItem('status', 'deleted');
                    showAlert("Deleted!", data.message, "success", false);
                    setTimeout(() => {
                        window.location.href = `${base}data_berita.php`;
                    }, 1000);
                } else {
                    sessionStorage.setItem("status", "error");
                    showAlert("Error!", "There was an error deleting the News.", "error");
                }
            })
            .catch(error => {
                sessionStorage.setItem("status", "error");
                showAlert("Error!", "There was an error with the request.", "error")
            });
        }
        });
    </script>
</head>
<body class="bg-gray-100" x-data="redirect()">
    <!-- Main Wrapper dengan kontrol sidebar -->
    <div class="flex flex-col h-screen" x-data="{ sidebarOpen: true }">

        <?php require 'components/loading.php'; ?>
        <!-- Navigation Bar -->
        <?php require 'components/header.php'; ?>

        <!-- Main Content Area, adjusted for sidebar visibility -->
        <div class="w-full h-full overflow-y-auto">

            <div class="w-full h-10 bg-white mb-2"></div>

            <!-- Main Content -->
            <div :class="{'ml-0': !sidebarOpen, 'ml-4': sidebarOpen}" class="flex-1 p-6 bg-gray-50 overflow-auto transition-all">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold">Daftar Semua Berita</h1>
                    <a href="news_form.php" class="bg-blue-500 text-white px-4 py-2 rounded">Tambahkan Data</a>
                </div>
                <div class="bg-white p-4 rounded shadow-md"
                x-data="{
                        data: [],  // Data pengguna
                        limit: 10,  // Default limit
                        offset: 0,  // pagination
                        globalIndex: 1,  // Menambahkan variabel untuk nomor urut global
                        searchQuery: '',
                        noNext: false,
                        noPrev: true,
                        fetchNews() {
                            let url = `${base}api/data_berita.php?entries=${this.limit}&page=${this.offset}&action=getAll`;
                            fetch(url)
                                .then(response => response.json())
                                .then(data => {
                                    this.data = data;
                                })
                                .catch(error => console.error('Error fetching data:', error));
                        },
                        changePage(page) {
                            this.offset = page;
                            this.globalIndex = page * this.limit + 1; // Update nomor urut global saat halaman berubah
                            this.fetchNews();  // Fetch ulang data untuk halaman baru
                        },
                        remove(id, baseUrl){
                            removeOneNews(id, baseUrl);
                        },
                        fetchSearchResults() {
                            if (this.searchQuery.trim() === '') {
                                this.data = null;
                                this.fetchNews();
                            } else {
                                let url = `${base}api/data_berita.php?q=${this.searchQuery}&action=search`;
                                fetch(url)
                                    .then(response => response.json())
                                    .then(dataResponse => {
                                        this.data = dataResponse.data;  // Update data berdasarkan hasil pencarian
                                    })
                                    .catch(error => console.error('Error fetching search data:', error));
                            }
                        }
                    }" x-init="fetchNews()"
                >
                    <div class="flex justify-between items-center mb-4 overflow-x-auto">
                        <div class="flex items-center">
                            <label class="mr-2" for="entries">Show</label>
                            <input class="border rounded p-1 w-16"
                            id="entries" 
                            type="number" 
                            x-model="limit"
                            @input="fetchNews()"
                            min="1" step="1" 
                            :value="limit"
                            />
                            <span class="ml-2">entries</span>
                        </div>
                        <div class="flex items-center">
                            <label class="mr-2" for="search">Search:</label>
                            <input class="border rounded p-1" 
                            id="search" 
                            type="text"
                            x-model="searchQuery"
                            @keyup="fetchSearchResults()"
                            placeholder="Enter Title"
                            />
                        </div>
                    </div>
                    
                    <!-- Wrapper untuk table dengan overflow horizontal -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b">No</th>
                                    <th class="py-2 px-4 border-b">Judul Berita</th>
                                    <th class="py-2 px-4 border-b">Creator</th>
                                    <th class="py-2 px-4 border-b">No Telpon</th>
                                    <th class="py-2 px-4 border-b">Tanggal Upload</th>
                                    <th class="py-2 px-4 border-b">Action</th>
                                </tr>
                            </thead>
                            <tbody >
                                <template x-if="data.length === 0">
                                    <tr>
                                        <td colspan="7" class="mx-2 py-4">No data is found.</td>
                                    </tr>
                                </template>

                                <template x-for="(row, index) in data" :key="row.id">
                                    <tr class="text-justify">
                                        <td class="py-2 px-4 border-b text-center" x-text="globalIndex + index"></td>
                                        <td class="py-2 px-4 border-b overflow-x-clip" x-text="row.judul_berita"></td>
                                        <td class="py-2 px-4 border-b" x-text="row.creator"></td>
                                        <td class="py-2 px-4 border-b" x-text="row.notelp"></td>
                                        <td class="py-2 px-4 border-b" x-text="dateConvert(row.tanggal_upload) || 'N/A'"></td>
                                        <td class="flex py-2 px-4 border-b text-center">
                                            <button class="text-green-500" @click="getTo(`detail_berita.php?id=${row.id}`)"><i class="fas fa-search"></i> Detail</button>
                                            <button class="text-red-500 ml-2" id="row.id" @click="remove(row.id, base)"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                        <div class="flex justify-between mt-4">
                            <button @click="changePage(offset - 1)" :disabled="offset <= 0">Previous</button>
                            <p type="text" x-html="offset" class="size-2"></p>
                            <button @click="changePage(offset + 1)" :disabled="data.length === 0">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <!-- Footer -->
        <?php require 'components/footer.php'; ?>
        <script src="assets/js/flashSession.js"></script>
    </div>
</body>
</html>

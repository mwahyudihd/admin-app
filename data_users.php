<?php
$current_page = 'user';
session_start();
?>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Dashboard</title>
    <!-- Link ke file CSS FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Link ke file CSS Tailwind atau stylesheet Anda -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- sweet alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Memuat Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="assets/js/alert.js"></script>
</head>
<body class="bg-gray-100">
    <!-- Main Wrapper dengan kontrol sidebar -->
    <div class="flex flex-col h-screen" x-data="{ sidebarOpen: true }">

        <!-- Navigation Bar -->
        <?php include('components/header.php') ?>

        <!-- Main Content Area, adjusted for sidebar visibility -->
        <div class="w-full h-full overflow-y-auto">

            <div class="w-full h-10 bg-white mb-2"></div>

            <!-- Main Content -->
            <div :class="{'ml-0': !sidebarOpen, 'ml-4': sidebarOpen}" class="flex-1 p-6 bg-gray-50 overflow-auto transition-all">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold">Daftar Semua UKM</h1>
                    <a href="form.php" class="bg-blue-500 text-white px-4 py-2 rounded">Tambahkan Data</a>
                </div>
                <div class="bg-white p-4 rounded shadow-md"
                    x-data="{
                        users: [],  // Data pengguna
                        limit: 10,  // Default limit
                        offset: 0,  // pagination
                        globalIndex: 1,  // Menambahkan variabel untuk nomor urut global
                        noNext: false,
                        noPrev: true,
                        fetchUsers() {
                            let url = `http://localhost/admin-ulbi/controllers/c_data_users.php?entries=${this.limit}&page=${this.offset}&id=<?php echo $_SESSION['user_id']; ?>`;
                            fetch(url)
                                .then(response => response.json())
                                .then(data => {
                                    this.users = data;  // Update data pengguna di Alpine.js
                                })
                                .catch(error => console.error('Error fetching data:', error));
                        },
                        changePage(page) {
                            this.offset = page;
                            this.globalIndex = page * this.limit + 1; // Update nomor urut global saat halaman berubah
                            this.fetchUsers();  // Fetch ulang data untuk halaman baru
                        },
                        remove(id){
                            setRemove(id);
                        }
                    }" x-init="fetchUsers()" 
                >
                    <div class="flex justify-between items-center mb-4 overflow-x-auto">
                        <div class="flex items-center">
                            <label class="mr-2" for="entries">Show</label>
                            <input class="border rounded p-1 w-16" id="entries" type="number"
                            id="entries" 
                            type="number" 
                            x-model="limit"
                            @input="fetchUsers()"
                            min="1" step="1" 
                            :value="limit"
                            />
                            <span class="ml-2">entries</span>
                        </div>
                        <div class="flex items-center">
                            <label class="mr-2" for="search">Search:</label>
                            <input class="border rounded p-1" id="search" type="text"/>
                        </div>
                    </div>
                    
                    <!-- Wrapper untuk table dengan overflow horizontal -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white overflow-y-auto">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b">No</th>
                                    <th class="py-2 px-4 border-b">Nama Lengkap</th>
                                    <th class="py-2 px-4 border-b">Alamat Email</th>
                                    <th class="py-2 px-4 border-b">No Telpon</th>
                                    <th class="py-2 px-4 border-b">Status Langganan</th>
                                    <th class="py-2 px-4 border-b">Waktu Daftar</th>
                                    <th class="py-2 px-4 border-b">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tb-user">
                                <template x-if="users.length === 0">
                                    <tr>
                                        <td colspan="7" class="mx-2 py-4">No data is found.</td>
                                    </tr>
                                </template>

                                <template x-for="(user, index) in users" :key="user.id">
                                    <tr>
                                        <td class="py-2 px-4 border-b text-center" x-text="globalIndex + index"></td> <!-- Gunakan globalIndex yang sudah diperbarui -->
                                        <td class="py-2 px-4 border-b" x-text="user.nama"></td>
                                        <td class="py-2 px-4 border-b" x-text="user.email"></td>
                                        <td class="py-2 px-4 border-b" x-text="user.notelp"></td>
                                        <td class="py-2 px-4 border-b" x-text="user.status_langganan"></td>
                                        <td class="py-2 px-4 border-b" x-text="user.waktu_daftar || 'N/A'"></td>
                                        <td class="flex py-2 px-4 border-b text-center">
                                            <button class="text-green-500"><i class="fas fa-search"></i> Detail</button>
                                            <button class="text-yellow-500 ml-2"><i class="fas fa-edit"></i></button>
                                            <button class="text-red-500 ml-2" id="user.id" @click="remove(user.id)"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                        <div class="flex justify-between mt-4">
                            <button @click="changePage(offset - 1)" :disabled="offset <= 0">Previous</button>
                            <p type="text" x-html="offset" class="size-2"></p>
                            <button @click="changePage(offset + 1)" :disabled="users.length === 0">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <!-- Footer -->
        <?php include('components/footer.php') ?>

    </div>
    <?php if (isset($_SESSION['status']) && $_SESSION['status'] == 'success') { ?>
        <script>
            showAlert("Berhasil!", "Data berhasil ditambahkan!", "success", false);
            setTimeout(() => {
                <?php $_SESSION['status'] = null; ?>
            }, 1000);
        </script>

    <?php } ?>
    <?php if (isset($_SESSION['status']) && $_SESSION['status'] == 'deleted') { ?>
        <script>
            showAlert("User terhapus!", "Data user berhasil dihapus!", "success");
            setTimeout(() => {
                <?php $_SESSION['status'] = null; ?>
            }, 1000);
        </script>

    <?php } ?>
    <?php if (isset($_SESSION['status']) && $_SESSION['status'] == 'error') { ?>
        <script>
            showAlert("Ops!", "Data gagal dimuat!", "error");
            setTimeout(() => {
                <?php $_SESSION['status'] = null; ?>
            }, 1000);
        </script>
    <?php } ?>
</body>
</html>

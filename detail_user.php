<?php
$current_page = 'user';
require 'controllers/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail - User</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/images/ulbi-icons.png" type="image/x-icon">
</head>
<body x-data="userForm()">
    <div class="flex flex-col h-screen" x-data="{ sidebarOpen: true }">
        <?php require 'components/loading.php'; ?>
        <?php require 'components/header.php'; ?>
        <div x-data="redirect()" class="size-full">

            <!-- Main Section -->
            <div class="w-full h-full overflow-y-auto"
            x-data="{
                data: {
                    email: '',
                    password: '',
                    nama: '',
                    notelp: '',
                    status_langganan: '',
                    waktu_daftar: '',
                    role: '',
                    created_at: '',
                    foto: ''  // Foto default akan diisi oleh data dari fetchUser
                },
                fetchUser() {
                    let url = `${base}api/select_user.php?id=<?= $_GET['id'] ?>`;
                    fetch(url)
                        .then(response => response.json())
                        .then(resData => {
                            this.data = resData.data;
                        })
                        .catch(error => {
                            console.error('Error fetching data:', error);
                        });
                },
                previewImage(event) {
                    const file = event.target.files[0];
                    const reader = new FileReader();
                    
                    reader.onload = (e) => {
                        this.data.foto = e.target.result; // Set photo URL
                    }
    
                    if (file) {
                        reader.readAsDataURL(file); // Membaca file sebagai URL data
                    }
                },
                submitForm(baseUrl) {
                    const formData = new FormData();
                    const foto = document.getElementById('foto').files[0]; // Ambil file yang dipilih
                    if (foto) {
                        formData.append('foto', foto); // Masukkan file ke FormData
                    }
    
                    // Menambahkan data lainnya
                    formData.append('id', <?= $_GET['id'] ?>);
                    formData.append('email', this.data.email);
                    formData.append('password', this.data.password);
                    formData.append('nama', this.data.nama);
                    formData.append('notelp', this.data.notelp);
                    formData.append('status_langganan', this.data.status_langganan);
                    formData.append('waktu_daftar', this.data.waktu_daftar);
                    formData.append('role', this.data.role);
    
                    fetch(`${baseUrl}controllers/c_update_user.php`, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Data berhasil diperbarui', data);
                        // Tampilkan pesan atau tindakan selanjutnya
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
                }
            }" x-init="fetchUser()"
            >
                <div class="w-full h-10 bg-white mb-2"></div>
                <form action="controllers/c_update_user.php" method="post" enctype="multipart/form-data">
                    <main :class="{'ml-0': !sidebarOpen, 'ml-4': sidebarOpen}" class="flex-1 p-6 bg-gray-50 overflow-auto transition-all">
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h2 class="text-2xl font-bold mb-6">
                                Detail <span x-text="data.nama"></span>
                            </h2>
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex-1 flex justify-center">
                                    <img id="existingImage" alt="User Logo" class="w-24 h-24 rounded-full object-cover" :src="data.foto" width="400" height="400" />
                                    <img id="imagePreview" alt="Preview Image" class="w-24 h-24 rounded-full object-cover" style="display: none;" :src="data.foto" width="400" height="400" />
                                </div>
                                <?php if (isset($_GET['edit'])) { ?>
                                    <!-- Tombol untuk memilih file gambar baru -->
                                    <input type="file" id="foto" class="hidden" hidden @change="previewImage($event)" name="foto" />
                                    <a type="button" class="cursor-pointer bg-orange-500 text-white px-4 py-2 rounded" @click="document.getElementById('foto').click();">
                                        Choose File
                                    </a>
                                <?php } ?>
                            </div>
                            <table class="w-full border-collapse border border-gray-300">
                                <tbody>
                                    <tr>
                                        <td class="border border-gray-300 bg-gray-200">Email</td>
                                        <td class="border border-gray-300 p-2">
                                            <?php if (isset($_GET['edit'])) { ?>
                                                <input type="email" x-model="data.email" class="w-full h-full" name="email" />
                                            <?php } else { ?>
                                                <span class="font-bold" x-text="data.email"></span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border border-gray-300 p-2 bg-gray-200">Password</td>
                                        <td class="border border-gray-300 p-2">
                                            <?php if (isset($_GET['edit'])) { ?>
                                                <input type="password" x-model="data.password" class="w-full h-full" name="password">
                                            <?php } else { ?>
                                                <input type="password" x-model="data.password" class="w-full h-full" readonly>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border border-gray-300 p-2 bg-gray-200">Nama</td>
                                        <td class="border border-gray-300 p-2">
                                            <?php if (isset($_GET['edit'])) { ?>
                                                <input type="text" class="w-full h-full" x-model="data.nama" name="nama">
                                            <?php } else { ?>
                                                <span class="font-bold" x-text="data.nama"></span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border border-gray-300 p-2 bg-gray-200">No Telpon</td>
                                        <td class="border border-gray-300 p-2">
                                            <?php if (isset($_GET['edit'])) { ?>
                                                <input type="number" class="w-full h-full" x-model="data.notelp" name="notelp">
                                            <?php } else { ?>
                                                <span class="font-bold" x-text="data.notelp"></span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border border-gray-300 p-2 bg-gray-200">Status Langganan</td>
                                        <td class="border border-gray-300 p-2">
                                            <?php if (isset($_GET['edit'])) { ?>
                                                <input type="text" class="w-full h-full" x-model="data.status_langganan" name="status_langganan">
                                            <?php } else { ?>
                                                <span class="font-bold" x-text="data.status_langganan"></span>
                                            <?php }?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border border-gray-300 p-2 bg-gray-200">Waktu Daftar</td>
                                        <td class="border border-gray-300 p-2">
                                        <?php if (isset($_GET['edit'])) { ?>
                                            <input type="date" class="w-full h-full" x-model="data.waktu_daftar" name="waktu_daftar">
                                        <?php } else { ?>
                                            <span class="font-bold" x-text="dateConvert(data.waktu_daftar)"></span>
                                        <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border border-gray-300 p-2 bg-gray-200">Status di ULBI</td>
                                        <td class="border border-gray-300 p-2">
                                            <?php if (isset($_GET['edit'])) { ?>
                                                <input type="text" class="w-full h-full" x-model="data.role" name="role">
                                            <?php }else{ ?>
                                                <span x-text="data.role" class="font-bold"></span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <?php if (isset($_GET['edit'])) { ?>
                                <div class="flex justify-between mt-6">
                                    <button class="bg-green-600 text-white px-6 py-2 rounded" @click="submitForm(base)">UPDATE</button>
                                    <a class="bg-red-600 text-white px-6 py-2 rounded cursor-pointer" @click="getTo('data_users.php')">CANCEL</a>
                                </div>
                            <?php } ?>
                        </div>
                    </main>
                </form>
            </div>
        </div>
        </div>
    <?php require 'components/footer.php'; ?>
</body>
</html>

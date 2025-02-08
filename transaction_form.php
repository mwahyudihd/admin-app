<?php
$current_page = 'transaction';
?>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Form - Transaction</title>
    <!-- Link ke file CSS Tailwind atau stylesheet Anda -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/images/ulbi-icons.png" type="image/x-icon">
</head>
<body class="bg-gray-100">
    <!-- Main Wrapper dengan kontrol sidebar -->
    <div class="flex flex-col h-screen" x-data="{ sidebarOpen: true }">
        <?php require 'components/loading.php'; ?>

        <!-- Navigation Bar -->
        <?php require 'components/header.php'; ?>

        <!-- Main Content Area, adjusted for sidebar visibility -->
        <div class="w-full h-full overflow-auto">
            
            <div class="w-full h-10 bg-white mb-2"></div>

            <!-- Main Content -->
            <div :class="{'ml-0': !sidebarOpen, 'ml-4': sidebarOpen}" class="flex-1 p-6 bg-gray-50 overflow-auto transition-all">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold">Tambahkan Transaksi</h1>
                </div>
                <div class="bg-white p-4 rounded shadow-md" x-data="redirect()">
                <form class="grid grid-cols-2 gap-6" action="controllers/c_add_transaction.php" method="post">
                    <div x-data="{
                        kode: '',
                        generateRandomString() {
                            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                            
                            let randomString = '';

                            // Generate 3 characters for the first part (before '-')
                            for (let i = 0; i < 3; i++) {
                                randomString += characters.charAt(Math.floor(Math.random() * characters.length));
                            }

                            // Add the dash in the middle
                            randomString += '-';

                            // Generate 5 characters for the second part (after '-')
                            for (let i = 0; i < 5; i++) {
                                randomString += characters.charAt(Math.floor(Math.random() * characters.length));
                            }

                            return randomString;
                        },
                        setKodeVal() {
                            this.kode = this.generateRandomString(); // Call the generateRandomString() using `this`
                        }
                    }">
                        <label class="block mb-2">
                            Kode Transaksi
                        </label>
                        <div class="relative">
                            <!-- Input field -->
                            <input x-model="kode" class="w-full p-2 border rounded" name="kode" type="text" required />

                            <!-- Eye icon inside input field -->
                            <a class="absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer" @click="setKodeVal()">
                                <i class="fas fa-dice"></i>
                            </a>
                        </div>
                    </div>

                    <div>
                        <label class="block mb-2">
                            Kuantitas
                        </label>
                        <input class="w-full p-2 border rounded" value="1" name="qty" type="number" required />
                    </div>
                    <div>
                        <label class="block mb-2">
                            Nama Konsumen
                        </label>
                        <input class="w-full p-2 pl-3 pr-10 border rounded" name="nama_customer" type="text" required />
                    </div>
                    <div>
                        <label class="block mb-2">
                            Email
                        </label>
                        <input class="w-full p-2 pl-3 pr-10 border rounded" name="email" type="email" required />
                    </div>
                    <div>
                        <label class="block mb-2">
                            Harga
                        </label>
                        <input class="w-full p-2 border rounded" name="harga" type="number" required />
                    </div>
                    <div x-data="{
                        listMethod: [],
                        fetchMethod() {
                            let url = `${base}api/payment.php?action=listMethod`;
                            fetch(url)
                                .then(response => response.json())
                                .then(resData => {
                                    this.listMethod = resData.data;
                                })
                                .catch(error => {
                                    console.error('Error fetching data:', error);
                                });
                        }
                    }" x-init="fetchMethod()">
                        <label class="block mb-2" for="option-select">
                            Saluran Pembayaran
                        </label>
                        <select name="metode" class="w-full border border-gray-300 p-2 rounded" id="option-select">
                            
                            <option value="" disabled selected>Pilih Metode</option>
                            
                            <template x-for="item in listMethod" :key="item">
                                <option x-text="item" :value="item"></option>
                            </template>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2">
                            Produk
                        </label>
                        <input class="w-full p-2 border rounded" type="text" name="produk" required />
                    </div>
                    <div x-data="{ status: '', customStatus: '' }">
                        <label class="block mb-2">
                            Status
                        </label>

                        <select name="status" id="status" x-model="status" class="w-full p-2 border rounded">
                            <option value="" disabled selected>Pilih Status</option>
                            <option value="pending">Pending</option>
                            <option value="aktif">Aktif</option>
                            <option value="diproses">Diproses</option>
                            <option value="berhasil">Berhasil</option>
                            <option value="selesai">Selesai</option>
                            <option value="dibatalkan">Dibatalkan</option>
                            <option value="menunggu_pembayaran">Menunggu Pembayaran</option>
                            <option value="dikirim">Dikirim</option>
                            <option value="terkirim">Terkirim</option>
                            <option value="gagal">Gagal</option>
                            <option value="refund">Refund</option>
                            <option value="verifikasi">Verifikasi</option>
                            <option value="menunggu_konfirmasi">Menunggu Konfirmasi</option>
                            <option value="custom">Status Lainnya</option>
                        </select>

                        <div x-show="status === 'custom'" class="mt-2">
                            <input type="text" name="custom_status" id="custom_status" x-model="customStatus" placeholder="Masukkan status lainnya" class="w-full p-2 border rounded" />
                        </div>
                    </div>
                    <div>
                        <label class="block mb-2">
                            Deadline
                        </label>
                        <input class="w-full p-2 border rounded" name="deadline" type="datetime-local" required />
                    </div>
                    <div>
                        <button class="w-full p-2 border rounded bg-blue-500 text-white flex items-center justify-center">Submit</button>
                    </div>
                    <div>
                        <a href='data_transaksi.php' class="w-full p-2 border rounded bg-green-500 text-white flex items-center justify-center">Kembali</a>
                    </div>
                </form>

                </div>
            </div>
        </div>
        </div>

        <!-- Footer -->
        <?php require 'components/footer.php'; ?>

    </div>
</body>
</html>

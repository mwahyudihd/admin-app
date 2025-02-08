<?php
$current_page = 'event';
?>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Form - Event</title>
    <!-- Link ke file CSS Tailwind atau stylesheet Anda -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/images/ulbi-icons.png" type="image/x-icon">
</head>
<body class="bg-gray-100" x-data="redirect()">
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
                <div class="bg-white p-8 rounded shadow-md w-full max-w-4xl" x-data="
                    {
                        listUkm: [],
                        fetchUkm() {
                            let url = `${base}api/list_ukm.php?getName`;
                            fetch(url)
                                .then(response => response.json())
                                .then(resData => {
                                    this.listUkm = resData.data;
                                })
                                .catch(error => {
                                    console.error('Error fetching data:', error);
                                });
                        }
                    }
                    " x-init="fetchUkm()">
                    <h1 class="text-2xl font-bold mb-6">Tambahkan Event</h1>
                    <form action="controllers/c_add_event.php" method="post" enctype="multipart/form-data">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block mb-2">Nama Acara</label>
                                <input type="text" class="w-full border border-gray-300 p-2 rounded" name="judul">
                            </div>
                            <div>
                                <label class="block mb-2">Tanggal Acara</label>
                                <input type="date" class="w-full border border-gray-300 p-2 rounded" name="tanggal_acara">
                            </div>
                            <div>
                                <label class="block mb-2">Deskripsi</label>
                                <textarea class="w-full border border-gray-300 p-2 rounded h-24" name="deskripsi" ></textarea>
                            </div>
                            <div>
                                <label class="block mb-2">Waktu Acara</label>
                                <input type="time" class="w-full border border-gray-300 p-2 rounded" name="waktu_acara">
                            </div>
                            <div>
                                <label class="block mb-2" for="option-select">UKM</label>
                                <select name="ukm" class="w-full border border-gray-300 p-2 rounded" id="option-select">
                                    
                                    <option value="" disabled selected>Pilih UKM</option>
                                    
                                    <template x-for="item in listUkm" :key="item">
                                        <option x-text="item" :value="item"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block mb-2">Lokasi Acara</label>
                                <input type="text" class="w-full border border-gray-300 p-2 rounded" name="lokasi_acara">
                            </div>
                            <div>
                                <label class="block mb-2">No Telpon</label>
                                <input type="text" class="w-full border border-gray-300 p-2 rounded" name="notelp">
                            </div>
                            <div>
                                <label class="block mb-2">Harga Tiket</label>
                                <input type="number" class="w-full border border-gray-300 p-2 rounded" name="harga_tiket">
                            </div>
                            <div class="col-span-1 md:col-span-2" x-data="{ fileName: '', fileSize: '', file: null }">
                                <label class="block mb-2">
                                    Upload Image
                                </label>
                                
                                <!-- Wrapper for the custom file input button -->
                                <div class="relative">
                                    <!-- Hidden file input -->
                                    <input name="foto" type="file" x-ref="fileInput" class="absolute inset-0 w-1/2 h-full opacity-0 cursor-pointer" 
                                        @change=" 
                                            if ($refs.fileInput.files[0]) {
                                                fileName = $refs.fileInput.files[0].name; 
                                                fileSize = ($refs.fileInput.files[0].size / 1024 / 1024).toFixed(2) + ' MB';
                                            } else {
                                                fileName = ''; 
                                                fileSize = '';
                                            }
                                        " required />
                                    
                                    <!-- Custom styled button -->
                                    <a href="#" class="w-full p-2 border rounded bg-orange-500 text-white flex items-center justify-center cursor-pointer">
                                        <i class="fas fa-upload mr-2"></i>
                                        Upload Image
                                    </a>
                                </div>
                                <!-- Display the uploaded file name and size -->
                                <p class="text-xs text-red-500 mt-2" x-text="fileName ? `File Uploaded: ${fileName}, Size: ${fileSize}` : 'Direkomendasikan 1080x1080px dan tidak lebih dari 2Mb'">
                                </p>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-center">
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700">Simpan</button>
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
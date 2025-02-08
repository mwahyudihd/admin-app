<?php
$current_page = 'user';
?>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Form - User</title>
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
                    <h1 class="text-2xl font-semibold">Tambahkan User</h1>
                </div>
                <div class="bg-white p-4 rounded shadow-md">
                <form class="grid grid-cols-2 gap-6" action="controllers/c_register.php" method="post" enctype="multipart/form-data">
                    <div>
                        <label class="block mb-2">
                            Email
                        </label>
                        <input class="w-full p-2 border rounded" name="email" type="email" required />
                    </div>
                    <div>
                        <label class="block mb-2">
                            Waktu Daftar
                        </label>
                        <input class="w-full p-2 border rounded" name="waktu_daftar" type="date" required />
                    </div>
                    <div x-data="{ showPass: false }">
                        <label class="block mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <!-- Input field -->
                            <input class="w-full p-2 pl-3 pr-10 border rounded" :type="showPass ? 'text' : 'password'" placeholder="Enter password" name="password" required />
                            
                            <!-- Eye icon inside input field -->
                            <a class="absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer" @click="showPass = !showPass">
                                <i class="fas" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </a>
                        </div>
                    </div>
                    <div>
                        <label class="block mb-2">
                            Status di ULBI
                        </label>
                        <input class="w-full p-2 border rounded" name="role" type="text" required />
                    </div>
                    <div>
                        <label class="block mb-2">
                            Nama
                        </label>
                        <input class="w-full p-2 border rounded" name="nama" type="text" required />
                    </div>
                    <div x-data="{ fileName: '', fileSize: '', file: null }">
                        <label class="block mb-2">
                            Upload Image
                        </label>
                        
                        <!-- Wrapper for the custom file input button -->
                        <div class="relative">
                            <!-- Hidden file input -->
                            <input name="foto" type="file" x-ref="fileInput" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" 
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

                    <div>
                        <label class="block mb-2">
                            No Telpon
                        </label>
                        <input class="w-full p-2 border rounded" type="text" name="notelp" required />
                    </div>
                    <div>
                        <label class="block mb-2">
                            Status Langganan
                        </label>
                        <input class="w-full p-2 border rounded" name="status_langganan" type="text" required />
                    </div>
                    <div>
                        <a href='data_users.php' class="w-full p-2 border rounded bg-green-500 text-white flex items-center justify-center">Kembali</a>
                    </div>
                    <div>
                        <button class="w-full p-2 border rounded bg-blue-500 text-white flex items-center justify-center">Submit</button>
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

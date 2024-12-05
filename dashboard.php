<?php
session_start(); // Pastikan session dimulai sebelum melakukan pengecekan session

// Memeriksa apakah user sudah login dengan memeriksa session
if (!empty($_SESSION['role'])) {
    redirectTo('/login.php'); // Jika belum login, redirect ke halaman login
}
$current_page = 'dashboard';
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
    <!-- Memuat Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100">
    <!-- Main Wrapper dengan kontrol sidebar -->
    <div class="flex flex-col h-screen" x-data="{ sidebarOpen: true }">

        <!-- Navigation Bar -->
        <?php include('components/header.php') ?>

        <!-- Main Content Area, adjusted for sidebar visibility -->
        <div class="w-full h-full overflow-auto">
            <div class="w-full h-10 bg-white mb-2"></div>
            <div :class="{'ml-0': !sidebarOpen, 'ml-4': sidebarOpen}" class="flex-1 p-6 bg-gray-50 overflow-auto transition-all">
                <div class="flex">
                    <div class="w-full h-auto">
                        <div class="flex-2">
                        <h2 class="text-2xl font-bold mb-4">
                        Key Performance Index (Overview)
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-orange-500 text-white p-4 rounded-lg flex items-center justify-between">
                            <div>
                            <i class="fas fa-users text-3xl">
                            </i>
                            </div>
                            <div class="text-right">
                            <p class="text-lg">
                            Users Total
                            </p>
                            <p class="text-3xl font-bold">
                            12.000
                            </p>
                            </div>
                        </div>
                        <div class="bg-orange-500 text-white p-4 rounded-lg flex items-center justify-between">
                            <div>
                            <i class="fas fa-chart-line text-3xl">
                            </i>
                            </div>
                            <div class="text-right">
                            <p class="text-lg">
                            Revenue
                            </p>
                            <p class="text-3xl font-bold">
                            Rp.6.590.084
                            </p>
                            </div>
                        </div>
                        </div>
                        <h3 class="text-xl font-bold mb-4">
                        Order History
                        </h3>
                        <div class="bg-white p-4 rounded-lg shadow">
                        <img alt="Line chart showing order history for Basic Version and Premium" height="300" src="https://storage.googleapis.com/a1aa/image/ZD745XWwN7qwPBISKJhbaZ9AX1uLssKyRa2uxNqjHnWfBz7JA.jpg" width="600"/>
                        </div>
                        </div>
                    </div>
                    <div class="h-auto w-full">
                        <!-- Sidebar -->
                        <div class="w-full lg:w-full lg:ml-6 mt-6 lg:mt-0">
                        <h2 class="text-xl font-bold mb-4">
                        Latest posts
                        </h2>
                        <div class="bg-white p-4 rounded-lg shadow mb-4">
                        <div class="flex items-center mb-4">
                            <img alt="Profile picture of Majestin" class="rounded-full mr-3" height="40" src="https://storage.googleapis.com/a1aa/image/Fiu5kInnDWIqBFX7TSw7BkedFrKNp6mJJwTMzO7Sfs7eHMvnA.jpg" width="40"/>
                            <div>
                            <p class="font-bold">
                            Welcome onboard Majestin!
                            </p>
                            <p class="text-gray-500">
                            Hi Majestin!
                            </p>
                            </div>
                        </div>
                        <div class="flex items-center mb-4">
                            <i class="fas fa-bullhorn text-2xl text-gray-500 mr-3">
                            </i>
                            <div>
                            <p class="font-bold">
                            New internal update
                            </p>
                            <p class="text-gray-500">
                            The tool got some new features. Yay!
                            </p>
                            </div>
                        </div>
                        <div class="flex items-center mb-4">
                            <i class="fas fa-file-alt text-2xl text-gray-500 mr-3">
                            </i>
                            <div>
                            <p class="font-bold">
                            Annual Report
                            </p>
                            <p class="text-gray-500">
                            Revenue Rp1.000.000
                            </p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-user-plus text-2xl text-gray-500 mr-3">
                            </i>
                            <div>
                            <p class="font-bold">
                            Pelanggan Premium Baru
                            </p>
                            <p class="text-gray-500">
                            2 orang baru saja berlangganan
                            </p>
                            </div>
                        </div>
                    </div>
                </div>
                    

            <!-- Main Content -->
        </div>
        </div>
        </div>
                        </div>
        <!-- Footer -->
        
    </div>
    <?php include('components/footer.php') ?>
</body>
</html>

<?php
require 'controllers/auth.php';

$current_page = 'dashboard';
?>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Dashboard</title>
    <!-- Link ke file CSS Tailwind atau stylesheet Anda -->
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/count.data.js"></script>
    <script src="assets/js/revenue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="assets/js/chart.data.js"></script>
    <link rel="shortcut icon" href="assets/images/ulbi-icons.png" type="image/x-icon">
</head>
<body class="bg-gray-100" x-data="redirect()">
    <!-- Main Wrapper dengan kontrol sidebar -->
    <div class="flex flex-col h-screen" x-data="{ sidebarOpen: true, currency(num){
                                if (typeof num !== 'number' && typeof num !== 'string') {
                                    throw new Error('Input harus berupa angka atau string yang valid.');
                                }
                            
                                let numStr = num.toString();
                            
                                let [integerPart, decimalPart] = numStr.split('.');
                            
                                let formatted = '';
                                let counter = 0;
                            
                                for (let i = integerPart.length - 1; i >= 0; i--) {
                                    formatted = integerPart[i] + formatted;
                                    counter++;
                            
                                    if (counter % 3 === 0 && i !== 0) {
                                        formatted = '.' + formatted;
                                    }
                                }
                            
                                if (decimalPart) {
                                    formatted += '.' + decimalPart;
                                }
                                return formatted;
                            } }">
        <?php require 'components/loading.php'; ?>
        <!-- Navigation Bar -->
        <?php require 'components/header.php'; ?>

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
                            <div x-data="fetchUserData(base)"  class="text-right">
                                <p class="text-lg">
                                Users Total
                                </p>
                                
                                <div x-show="isLoading" class="mt-4">
                                    <p>Loading...</p>
                                </div>

                                <div x-show="error" class="mt-4 text-red-500">
                                    <p>Error: <span x-text="errorMessage"></span></p>
                                </div>

                                <div x-show="!isLoading && !error" class="mt-4">
                                    <p class="text-3xl font-bold"><span x-text="userCount"></span></p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-orange-500 text-white p-4 rounded-lg flex items-center justify-between"
                        x-data="getTotalHarga(base)" >
                            <div x-init="fetchRev">
                            <i class="fas fa-chart-line text-3xl">
                            </i>
                            </div>
                            <div class="text-right">
                            <p class="text-lg">
                            Revenue
                            </p>
                            <p class="text-2xl font-bold">
                            Rp.<span x-text="currency(revenue)"></span>
                            </p>
                            </div>
                        </div>
                        </div>
                        <h3 class="text-xl font-bold mb-4">
                        Order History
                        </h3>
                            <div x-data="chartData(base)" x-init="fetchData()" class="w-full border rounded-lg p-4">
                            <!-- Chart Container -->
                                <div id="chartContainer" class="w-full max-w-4xl">
                                    <canvas id="myChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="h-auto w-full" x-data="{
                        users: [],
                        totalUsers: 0,
                        isOpen: false,
                        async fetchData() {
                            try {
                                const response = await fetch(`${base}api/new_user.php`);
                                const data = await response.json();
                                this.totalUsers = Number(data.total_users);
                                this.users = data.data;
                            } catch (error) {
                                console.error('Error fetching data:', error);
                            }
                        },
                        latestProfile: false,
                        annualReport: false,
                        info: false
                    }" x-init="fetchData">
                        <!-- Sidebar -->
                        <div class="w-full lg:w-full lg:ml-6 mt-6 lg:mt-0">
                        <h2 class="text-xl font-bold mb-4">
                        Latest posts
                        </h2>
                        <div class="bg-white p-4 rounded-lg shadow mb-4">
                        <div class="flex items-center mb-4 cursor-pointer" @click="latestProfile = !latestProfile">
                            <img alt="Profile" class="rounded-full mr-3 object-cover" height="40" src="<?= $_SESSION['foto'] ?>" width="40"/>
                            <div>
                            <p class="font-bold">
                            Welcome onboard <?= $_SESSION['nama'] ?>
                            </p>
                            <p class="text-gray-500">
                            Hi <?= $_SESSION['nama'] ?>!
                            </p>
                            </div>
                        </div>
                        <div x-show="latestProfile" @click.outside="latestProfile = false" class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full absolute right-[30%] top-[20%]" x-transition:leave.opacity.duration.500ms x-transition:enter.opacity.duration.500ms>
                            <div class="flex justify-between items-start">
                                <img alt="Profile picture" class="w-16 h-16 rounded-full object-cover" height="100" src="<?= $_SESSION['foto'] ?>" width="100"/>
                                <button class="text-blue-500 text-xl" @click="latestProfile = !latestProfile">
                                    <i class="fas fa-times">
                                    </i>
                                </button>
                            </div>
                            <h2 class="text-xl font-semibold mt-4">
                                Welcome onboard <span><?= $_SESSION['nama'] ?>!</span>
                            </h2>
                            <p class="mt-2 text-gray-600">
                                Welcome onboard! We're thrilled to have you with us and look forward to achieving great things together. If there's anything you need or any way we can support you, please don't hesitate to reach out. Let's make this journey a remarkable one!
                            </p>
                        </div>
                        <div class="flex items-center mb-4 cursor-pointer" @click="info = ! info">
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
                            <div x-show="info" @click.outside="info = false" class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full absolute right-[30%] top-[20%]" x-transition:leave.opacity.duration.500ms x-transition:enter.opacity.duration.500ms>
                                <div class="flex justify-between items-start">
                                    <img alt="Profile picture" class="w-16 h-16 rounded-full object-cover" height="100" src="https://storage.googleapis.com/a1aa/image/H64o9YbrhVJ9NZKIP1be0budePAwwgejR9qTe7miambC7a8PB.jpg" width="100"/>
                                    <button class="text-blue-500 text-xl" @click="info = !info">
                                        <i class="fas fa-times">
                                        </i>
                                    </button>
                                </div>
                                <h2 class="text-xl font-semibold mt-4">
                                    New internal update
                                </h2>
                                <p class="mt-2 text-gray-600">
                                    The tool got some exciting new features, and we're absolutely thrilled about it-yay! These enhancements are set to significantly improve our productivity and streamline our workflows. We can't wait for you to explore and benefit from all the new capabilities!
                                </p>
                            </div>
                        </div>
                        <div x-data="newTotalHarga(base)" x-show="transaksi" class="flex items-center mb-4 cursor-pointer" @click="annualReport = ! annualReport" x-init="getTransaksi">
                            <i class="fas fa-file-alt text-2xl text-gray-500 mr-3">
                            </i>
                            <div>
                            <p class="font-bold">
                            Annual Report
                            </p>
                            <p class="text-gray-500">
                                Revenue Rp. <span x-text="currency(total_harga)"></span>
                            </p>
                            </div>
                            <div x-show="annualReport" @click.outside="annualReport = false" class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full absolute right-[30%] top-[20%]" x-transition:leave.opacity.duration.500ms x-transition:enter.opacity.duration.500ms>
                                <div class="flex justify-between items-start">
                                    <img alt="Profile picture" class="w-16 h-16 rounded-full object-cover" height="100" src="https://storage.googleapis.com/a1aa/image/H64o9YbrhVJ9NZKIP1be0budePAwwgejR9qTe7miambC7a8PB.jpg" width="100"/>
                                    <button class="text-blue-500 text-xl" @click="annualReport = !annualReport">
                                        <i class="fas fa-times">
                                        </i>
                                    </button>
                                </div>
                                <h2 class="text-xl font-semibold mt-4">
                                    Annual Report
                                </h2>
                                <p class="mt-2 text-gray-600">
                                    Our company has achieved a remarkable milestone with our revenue reaching an impressive Rp. <span x-text="currency(total_harga)"></span> . This significant achievement reflects our team's hard work and dedication, and it marks a promising step forward in our growth and success.
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center" x-show="totalUsers > 0">
                            <i class="fas fa-user-plus text-2xl text-gray-500 mr-3">
                            </i>
                            <div class="cursor-pointer" @click="isOpen = !isOpen">
                                <p class="font-bold">
                                    Pelanggan Premium Baru
                                </p>
                                <p class="text-gray-500">
                                    <span x-text="totalUsers"></span> orang baru saja berlangganan
                                </p>
                            </div>
                            <div x-show="isOpen" @click.outside="isOpen = false" class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full absolute lg:right-[30%] lg:top-[20%] right-1/2 top-1/2" x-transition:leave.opacity.duration.500ms x-transition:enter.opacity.duration.500ms>
                                <div class="flex justify-between items-start">
                                    <img alt="Subscibers picture" class="w-16 h-16 rounded-full object-cover" height="100" src="https://storage.googleapis.com/a1aa/image/H64o9YbrhVJ9NZKIP1be0budePAwwgejR9qTe7miambC7a8PB.jpg" width="100"/>
                                    <button class="text-blue-500 text-xl" @click="isOpen = !isOpen">
                                        <i class="fas fa-times">
                                        </i>
                                    </button>
                                </div>
                                <h2 class="text-xl font-semibold mt-4">
                                    Pelanggan Premium Baru
                                </h2>
                                <p class="mt-2 text-gray-600">
                                Baru saja, <span x-text="totalUsers"></span> orang telah berlangganan layanan kami, menambah jumlah pelanggan setia yang terus berkembang. Kami sangat senang menyambut mereka dan berharap mereka menikmati semua manfaat dan fitur yang kami tawarkan. Terima kasih atas dukungannya!
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
    <?php require 'components/footer.php'; ?>
</body>
</html>

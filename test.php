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
        <nav class="flex justify-between items-center p-4 bg-blue-700 text-white shadow-md w-full">
            <!-- Logo or title of the app di pojok kiri -->
            <div class="flex items-center space-x-4 pl-4"> <!-- Add pl-4 for padding-left -->
                <span class="text-xl font-semibold">ADMINISTRATOR</span>
                <button @click="sidebarOpen = !sidebarOpen" class="text-blue-500 hover:text-blue-700">
                    <!-- Ikon List FontAwesome -->
                    <i class="fas fa-list text-white"></i>
                </button>
            </div>

            <!-- Icon Buttons di pojok kanan -->
            <div class="flex items-center space-x-6 pr-4"> <!-- Add pr-4 for padding-right and space-x-6 for more space between icons -->
                <button class="text-blue-500 hover:text-blue-700">
                    <!-- Ikon Bell FontAwesome -->
                    <i class="fas fa-bell text-white"></i>
                </button>
                <button class="text-blue-500 hover:text-blue-700">
                    <!-- Ikon Ellipsis FontAwesome -->
                    <i class="fa-solid fa-ellipsis-vertical text-white"></i>
                </button>
            </div>
        </nav>

        <!-- Content Wrapper -->
        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar -->
            <div x-show="sidebarOpen" x-transition class="w-64 bg-white h-screen shadow-md p-4 fixed md:relative z-10">
                <div class="flex items-center mb-6">
                    <img alt="ULBI App Logo" class="w-10 h-10 rounded-full bg-blue-500" height="40" src="assets/images/ulbi-icons.png" width="40"/>
                    <span class="ml-2 text-lg font-semibold">ULBI App</span>
                </div>
                <nav>
                    <a class="flex items-center p-2 text-gray-700 hover:bg-gray-200" href="#">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="ml-2">Dashboard</span>
                    </a>
                    <a class="flex items-center p-2 text-gray-700 hover:bg-gray-200" href="#">
                        <i class="fas fa-building"></i>
                        <span class="ml-2">Data UKM</span>
                    </a>
                    <a class="flex items-center p-2 text-gray-700 hover:bg-gray-200" href="#">
                        <i class="fas fa-users"></i>
                        <span class="ml-2">Data User</span>
                    </a>
                    <a class="flex items-center p-2 text-gray-700 hover:bg-gray-200" href="#">
                        <i class="fas fa-newspaper"></i>
                        <span class="ml-2">Data Berita</span>
                    </a>
                    <a class="flex items-center p-2 text-gray-700 hover:bg-gray-200" href="#">
                        <i class="fas fa-calendar-alt"></i>
                        <span class="ml-2">Data Event</span>
                    </a>
                    <a class="flex items-center p-2 text-gray-700 hover:bg-gray-200" href="#">
                        <i class="fas fa-store"></i>
                        <span class="ml-2">Data Merchant</span>
                    </a>
                    <a class="flex items-center p-2 text-gray-700 hover:bg-gray-200" href="#">
                        <i class="fas fa-exchange-alt"></i>
                        <span class="ml-2">Data Transaksi</span>
                    </a>
                </nav>
            </div>

            <!-- ini tolong rapikan dong -->
            <div class="bg-white w-full h-10"></div>
            <!-- ini tolong rapikan dong -->
             
            <!-- Main Content Area, adjusted for sidebar visibility -->
            <div :class="{'ml-0': !sidebarOpen, 'ml-4': sidebarOpen}" class="flex-1 p-6 bg-gray-50 overflow-auto transition-all">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold">Daftar Semua UKM</h1>
                    <button class="bg-blue-500 text-white px-4 py-2 rounded">Tambahkan Data</button>
                </div>
                <div class="bg-white p-4 rounded shadow-md">
                    <div class="flex justify-between items-center mb-4 overflow-x-auto">
                        <div class="flex items-center">
                            <label class="mr-2" for="entries">Show</label>
                            <input class="border rounded p-1 w-16" id="entries" type="number" value="4"/>
                            <span class="ml-2">entries</span>
                        </div>
                        <div class="flex items-center">
                            <label class="mr-2" for="search">Search:</label>
                            <input class="border rounded p-1" id="search" type="text"/>
                        </div>
                    </div>
                    
                    <!-- Wrapper untuk table dengan overflow horizontal -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b">No</th>
                                    <th class="py-2 px-4 border-b">Nama</th>
                                    <th class="py-2 px-4 border-b">Alamat Email</th>
                                    <th class="py-2 px-4 border-b">No Telpon</th>
                                    <th class="py-2 px-4 border-b">Status Langganan</th>
                                    <th class="py-2 px-4 border-b">Waktu Daftar</th>
                                    <th class="py-2 px-4 border-b">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Example Data Row -->
                                <tr>
                                    <td class="py-2 px-4 border-b text-center">1</td>
                                    <td class="py-2 px-4 border-b">Himajo</td>
                                    <td class="py-2 px-4 border-b">himajo@gmail.com</td>
                                    <td class="py-2 px-4 border-b">08257946713</td>
                                    <td class="py-2 px-4 border-b">ULBIAN VIP</td>
                                    <td class="py-2 px-4 border-b">7 Juni 2024</td>
                                    <td class="py-2 px-4 border-b text-center">
                                        <button class="text-green-500"><i class="fas fa-eye"></i> Detail</button>
                                        <button class="text-yellow-500 ml-2"><i class="fas fa-edit"></i></button>
                                        <button class="text-red-500 ml-2"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-blue-700 text-white text-center p-4 mt-6">
            <p>&copy; 2024 ULBI App. All Rights Reserved.</p>
            <div class="flex justify-center space-x-4">
                <a href="#" class="text-white hover:text-gray-300"><i class="fab fa-facebook"></i></a>
                <a href="#" class="text-white hover:text-gray-300"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-white hover:text-gray-300"><i class="fab fa-instagram"></i></a>
            </div>
        </footer>
    </div>
</body>
</html>

<nav class="flex justify-between items-center p-4 bg-blue-700 text-white shadow-md w-full">
    <!-- Logo or title of the app di pojok kiri -->
    <div class="flex items-center space-x-4 pl-4">
        <span class="text-xl font-semibold">ADMINISTRATOR</span>
        <button @click="sidebarOpen = !sidebarOpen" class="text-blue-500 hover:text-blue-700">
            <i class="fas fa-list text-white"></i>
        </button>
    </div>

    <!-- Icon Buttons di pojok kanan -->
    <div class="flex items-center space-x-6 pr-4">
        <button class="text-blue-500 hover:text-blue-700">
            <i class="fas fa-bell text-white"></i>
        </button>
        
        <!-- Tombol Ellipsis dengan dropdown -->
        <div x-data="{ open: false }">
            <button @click="open = !open" class="text-blue-500 p-3 hover:text-blue-700">
                <i class="fa-solid fa-ellipsis-vertical text-white"></i>
            </button>

            <!-- Dropdown menu -->
            <div x-show="open" @click.outside="open = false" x-transition class="absolute right-1 mt-2 w-48 bg-white text-black rounded-lg shadow-lg">
                <ul>
                    <li>
                        <a href="/profile" class="block px-4 py-2 hover:bg-blue-100">Profile</a>
                    </li>
                    <li>
                        <a href="/settings" class="block px-4 py-2 hover:bg-blue-100">Settings</a>
                    </li>
                    <li>
                        <a href="controllers/c_logout.php" class="block px-4 py-2 hover:bg-blue-100">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
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
            <a class="flex items-center p-2 <?php echo ($current_page == 'dashboard') ? 'text-green-500' : 'text-gray-700' ?> hover:bg-gray-200" href="dashboard.php">
                <i class="fas fa-tachometer-alt"></i>
                <span class="ml-2">Dashboard</span>
            </a>
            <a class="flex items-center p-2 <?php echo ($current_page == 'data-ukm') ? 'text-green-500' : 'text-gray-700' ?> hover:bg-gray-200" href="data_ukm.php">
                <i class="fas fa-building"></i>
                <span class="ml-2">Data UKM</span>
            </a>
            <a class="flex items-center p-2 <?php echo ($current_page == 'user') ? 'text-green-500' : 'text-gray-700' ?> hover:bg-gray-200" href="data_users.php">
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
            <a class="flex items-center p-2 <?php echo ($current_page == 'merchant') ? 'text-green' : 'text-gray-700' ?> hover:bg-gray-200" href="#">
                <i class="fas fa-store"></i>
                <span class="ml-2">Data Merchant</span>
            </a>
            <a class="flex items-center p-2 text-gray-700 hover:bg-gray-200" href="#">
                <i class="fas fa-exchange-alt"></i>
                <span class="ml-2">Data Transaksi</span>
            </a>
        </nav>
</div>
<!-- Link ke file CSS FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<!-- sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/alert.js"></script>
<script src="assets/js/route.js"></script>
<script src="assets/js/converter.js"></script>
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
        
        <!-- Tombol Ellipsis dengan dropdown -->
        <div x-data="{ open: false, 
        confirmLogout(){
            Swal.fire({
                title: `Logout`,
                icon: `question`,
                text: `Do you want clear your session?`,
                showCancelButton: true,
                confirmButtonText: 'Logout'
                }).then((result) => {
                if (result.isConfirmed) {
                    setTimeout(() => {
                        window.location.replace('controllers/logout.php');
                    }, 700);
                    Swal.fire('logout!', '', 'success').then(() => {
                        return isConfirm();
                    });
                } else {
                    console.log('Logout cancelled');
                }
                });
            }
        }">
            <button @click="open = !open" class="text-blue-500 p-3 hover:text-blue-700">
                <i class="fa-solid fa-ellipsis-vertical text-white"></i>
            </button>

            <!-- Dropdown menu -->
            <div x-data="redirect()" x-show="open" @click.outside="open = false" x-transition class="absolute right-1 mt-2 w-48 bg-white text-black rounded-lg shadow-lg">
                <ul>
                    <li>
                        <a @click="getTo(`detail_user.php?id=<?= $_SESSION['user_id'] ?>`)" class="block px-4 py-2 cursor-pointer hover:bg-blue-100">Profile</a>
                    </li>
                    <li>
                        <a @click="getTo(`detail_user.php?id=<?= $_SESSION['user_id'] ?>&edit`)" class="block px-4 py-2 hover:bg-blue-100 cursor-pointer">Settings</a>
                    </li>
                    <li>
                        <a @click="confirmLogout()" class="block px-4 py-2 cursor-pointer hover:bg-blue-100">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>


<!-- Content Wrapper -->
<div class="flex flex-1 overflow-hidden" x-data="convert()">
    <!-- Sidebar -->
    <div x-show="sidebarOpen" x-transition class="w-64 bg-white h-screen shadow-md p-4 fixed md:relative z-10">
        <div class="p-4 flex items-center">
            <img alt="App Logo" class="w-10 h-10 rounded-full bg-blue-500" height="40" src="assets/images/ulbi-icons.png" width="40"/>
            <div class="ml-2">
                <h2 class="text-lg font-bold">
                    ULBI App
                </h2>
                <p class="text-green-500">
                    Online
                </p>
            </div>
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
            <a class="flex items-center p-2 <?php echo ($current_page == 'news') ? 'text-green-500' : 'text-gray-700' ?> hover:bg-gray-200" href="data_berita.php">
                <i class="fas fa-newspaper"></i>
                <span class="ml-2">Data Berita</span>
            </a>
            <a class="flex items-center p-2 <?php echo ($current_page == 'event') ? 'text-green-500' : 'text-gray-700' ?> hover:bg-gray-200" href="data_event.php">
                <i class="fas fa-calendar-alt"></i>
                <span class="ml-2">Data Event</span>
            </a>
            <a class="flex items-center p-2 <?php echo ($current_page == 'transaction') ? 'text-green-500' : 'text-gray-700' ?> hover:bg-gray-200" href="data_transaksi.php">
                <i class="fas fa-wifi"></i>
                <span class="ml-2">Data Transaksi</span>
            </a>
            <a class="flex items-center p-2 <?php echo ($current_page == 'payment') ? 'text-green-500' : 'text-gray-700' ?> hover:bg-gray-200" href="payment_settings.php">
                <i class="fas fa-credit-card"></i>
                <span class="ml-2">Informasi Pembayaran</span>
            </a>
        </nav>
    </div>
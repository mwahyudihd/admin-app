<?php
$current_page = 'payment';
?>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Form - Berita</title>
    <!-- Link ke file CSS Tailwind atau stylesheet Anda -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/images/ulbi-icons.png" type="image/x-icon">
</head>
<body class="bg-gray-100">
    <!-- Main Wrapper dengan kontrol sidebar -->
    <div class="flex flex-col h-screen" x-data="{ sidebarOpen: true }">
        <?php require 'components/loading.php'; ?>

        <!-- Navigation Bar -->
        <?php require 'components/header.php' ?>

        <!-- Main Content Area, adjusted for sidebar visibility -->
        <div class="w-full h-full overflow-auto">
            
            <div class="w-full h-10 bg-white mb-2"></div>

            <!-- Main Content -->
            <div :class="{'ml-0': !sidebarOpen, 'ml-4': sidebarOpen}" class="flex-1 p-6 bg-gray-50 overflow-auto transition-all">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold">Tambahkan Metode Pembayaran</h1>
                </div>
                <div class="bg-white p-4 rounded shadow-md">
                <form class="font-bold grid grid-cols-1 w-1/2 gap-6" action="controllers/c_add_payment.php" method="post" >
                    <div>
                        <label class="block mb-2 font-bold">
                            NOMOR (REKENING/TELP/VA)
                        </label>
                        <input class="w-full font-normal p-2 border rounded" name="nomor" type="text" required />
                    </div>
                    <div>
                        <label class="block mb-2 font-bold">
                            A/N (ATAS NAMA)
                        </label>
                        <input class="w-full p-2 border rounded font-normal" name="atas_nama" type="text" required />
                    </div>
                    <div>
                        <label class="block mb-2">
                            METODE
                        </label>
                        <input class="w-full p-2 border rounded font-normal" type="text" name="metode" required />
                    </div>

                    <div>
                        <button class="w-full p-2 border rounded bg-blue-500 text-white flex items-center justify-center">Submit</button>
                    </div>
                    <div>
                        <a href='payment_settings.php' class="w-full p-2 border rounded bg-green-500 text-white flex items-center justify-center">Kembali</a>
                    </div>
                </form>

                </div>
            </div>
        </div>
        </div>

        <!-- Footer -->
        <?php require 'components/footer.php' ?>

    </div>
</body>
</html>
<?php
$current_page = 'payment';
require 'controllers/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Transaction</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/images/ulbi-icons.png" type="image/x-icon">
</head>
<body x-data="redirect()">
    <div class="flex flex-col h-screen" z-0 x-data="{ sidebarOpen: true }">
        <?php require 'components/loading.php'; ?>
        <?php require 'components/header.php'; ?>

        <!-- Main Section -->
        <div class="w-full h-full overflow-y-auto"
        x-data="{
            data: {
                atas_nama: '',
                nomor: '',
                metode: ''
            },
            fetchMtd() {
                let url = `${base}api/payment.php?action=findOne&id=<?= $_GET['id'] ?>`;
                fetch(url)
                    .then(response => response.json())
                    .then(resData => {
                        this.data = resData.data;
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                    });
            }
        }" x-init="fetchMtd()"
        >
            <div class="w-full h-10 bg-white mb-2"></div>
            <form action="controllers/c_update_payment.php" method="post">
                <main :class="{'ml-0': !sidebarOpen, 'ml-4': sidebarOpen}" class="flex-1 p-6 bg-gray-50 overflow-auto transition-all">
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h2 class="text-2xl font-bold mb-6">
                            DETAIL METODE PEMBAYARAN
                        </h2>
                        <table class="w-full border-collapse border border-gray-300" >
                            <tbody >
                                <tr>
                                    <td class="border border-gray-300 p-2 bg-gray-200">METODE</td>
                                    <td class="border border-gray-300 p-2">
                                        <?php if (isset($_GET['edit'])) { ?>
                                            <input type="number" value="<?= $_GET['id'] ?>" name="id" class="hidden" hidden readonly />
                                            <input type="text" x-model="data.metode" class="w-full h-full" name="metode" />
                                        <?php } else { ?>
                                            <span class="font-bold" x-text="data.metode"></span>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 p-2 bg-gray-200">A/N (ATAS NAMA)</td>
                                    <td class="border border-gray-300 p-2">
                                        <?php if (isset($_GET['edit'])) { ?>
                                            <input type="text" x-model="data.atas_nama" name="atas_nama" class="w-full h-full">
                                        <?php } else { ?>
                                            <span class="font-bold"x-text="data.atas_nama"></span>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 p-2 bg-gray-200">NO/VA/REK</td>
                                    <td class="border border-gray-300 p-2">
                                        <?php if (isset($_GET['edit'])) { ?>
                                            <input type="text" class="w-full h-full" x-model="data.nomor" name="nomor">
                                        <?php } else { ?>
                                            <span class="font-bold" x-text="data.nomor"></span>
                                        <?php } ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <?php if(isset($_GET['edit'])){ ?>
                        <div class="flex justify-between mt-6">
                            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded" >UPDATE</button>
                            <a class="bg-red-600 text-white px-6 py-2 rounded" @click="getTo('payment_settings.php')">CANCEL</a>
                        </div>
                        <?php } ?>
                    </div>
                </main>
            </form>
        </div>
    </div>
    <?php require 'components/footer.php'; ?>
</body>
</html>
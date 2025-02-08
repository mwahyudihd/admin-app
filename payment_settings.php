<?php
$current_page = 'payment';
require 'controllers/auth.php';
?>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Table - Event</title>
    <!-- Link ke file CSS Tailwind atau stylesheet Anda -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/images/ulbi-icons.png" type="image/x-icon">
    <script>
        const removeOneEvent = (id, base) => Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
        }).then((result) => {
        if (result.isConfirmed) {
            fetch(`${base}api/del_payment.php?id=${id}`, {
                method: 'DELETE',
            })
            .then(response => response.json())
            .then(data => {
                if (data.statusCode == 200) {
                    sessionStorage.setItem('status', 'deleted');
                    showAlert("Deleted!", data.message, "success", false);
                    setTimeout(() => {
                        window.location.href = `${base}payment_settings.php`;
                    }, 1000);
                } else {
                    sessionStorage.setItem("status", "error");
                    showAlert("Error!", "There was an error deleting the user.", "error");
                }
            })
            .catch(error => {
                sessionStorage.setItem("status", "error");
                showAlert("Error!", "There was an error with the request.", "error")
            });
        }
        });
    </script>
</head>
<body class="bg-gray-100" x-data="redirect()">
    <!-- Main Wrapper dengan kontrol sidebar -->
    <div class="flex flex-col h-screen" x-data="{ sidebarOpen: true }">

        <?php require 'components/loading.php'; ?>
        <!-- Navigation Bar -->
        <?php require 'components/header.php'; ?>

        <!-- Main Content Area, adjusted for sidebar visibility -->
        <div class="w-full h-full overflow-y-auto">

            <div class="w-full h-10 bg-white mb-2"></div>

            <!-- Main Content -->
            <div :class="{'ml-0': !sidebarOpen, 'ml-4': sidebarOpen}" class="flex-1 p-6 bg-gray-50 overflow-auto transition-all">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold">Daftar Metode Pembayaran</h1>
                    <a href="payment_form.php" class="bg-blue-500 text-white px-4 py-2 rounded">Tambahkan Metode</a>
                </div>
                <div class="bg-white p-4 rounded shadow-md"
                x-data="{
                        data: [],
                        fetchPayMethod() {
                            let url = `${base}api/payment.php?action=getAll`;
                            fetch(url)
                                .then(response => response.json())
                                .then(res => {
                                    this.data = res.data;
                                })
                                .catch(error => console.error('Error fetching data:', error));
                        },
                        remove(id, baseUrl){
                            removeOneEvent(id, baseUrl);
                        },
                    }" x-init="fetchPayMethod()"
                >
                    <div class="flex justify-between items-center mb-4 overflow-x-auto">
                        <div class="flex items-center">
                        </div>
                        <div class="flex items-center">
                        </div>
                    </div>
                    
                    <!-- Wrapper untuk table dengan overflow horizontal -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b">No</th>
                                    <th class="py-2 px-4 border-b">Metode</th>
                                    <th class="py-2 px-4 border-b">NO/VA/REK</th>
                                    <th class="py-2 px-4 border-b">A/N</th>
                                    <th class="py-2 px-4 border-b">Action</th>
                                </tr>
                            </thead>
                            <tbody >
                                <template x-if="data.length === 0">
                                    <tr>
                                        <td colspan="7" class="mx-2 py-4">No data is found.</td>
                                    </tr>
                                </template>

                                <template x-for="(row, index) in data" :key="row.id">
                                    <tr class="text-justify">
                                        <td class="py-2 px-4 border-b text-center" x-text="index+=1"></td>
                                        <td class="py-2 px-4 border-b overflow-x-clip" x-text="row.metode"></td>
                                        <td class="py-2 px-4 border-b" x-text="row.nomor"></td>
                                        <td class="py-2 px-4 border-b" x-text="row.atas_nama"></td>
                                        <td class="flex py-2 px-4 border-b text-center">
                                            <button class="text-green-500" @click="getTo(`detail_payment.php?id=${row.id}`)"><i class="fas fa-search"></i> Detail</button>
                                            <button class="text-yellow-500 ml-2" @click="getTo(`detail_payment.php?id=${row.id}&edit`)"><i class="fas fa-edit"></i></button>
                                            <button class="text-red-500 ml-2" id="row.id" @click="remove(row.id, base)"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <!-- Footer -->
        <?php require 'components/footer.php'; ?>
        <script src="assets/js/flashSession.js"></script>
    </div>
</body>
</html>
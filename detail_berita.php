<?php
$current_page = 'news';
require 'controllers/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail - Berita</title>
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
                judul_berita: '',
                creator: '',
                notelp: '',
                tanggal_upload: '',
                deskripsi: '',
                foto: '',
                publish: ''
            },
            fetchNews() {
                let url = `${base}api/data_berita.php?id=<?= $_GET['id'] ?>&action=findOne`;
                fetch(url)
                    .then(response => response.json())
                    .then(resData => {
                        this.data = resData.data;
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                    });
            },
            submitForm(statusData) {
                fetch(`${base}api/data_berita.php?id=<?= $_GET['id'] ?>&action=publish`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',  // Memberitahukan server bahwa kita mengirim JSON
                    },
                    body: JSON.stringify({  // Mengonversi objek menjadi string JSON
                        'publish': Number(!statusData)
                    })
                })
                .then(response => response.json())
                .then(data => {
                    sessionStorage.setItem('status', 'updated');
                    sessionStorage.setItem('message', `${data.message}`);
                    window.location.reload();
                })
                .catch((error) => {
                    sessionStorage.setItem('status', 'error');
                    console.error('Error:', error);
                    window.location.reload();
                });
            }
        }" x-init="fetchNews()"
        >
            <div class="w-full h-10 bg-white mb-2"></div>
                <main :class="{'ml-0': !sidebarOpen, 'ml-4': sidebarOpen}" class="flex-1 p-6 bg-gray-50 overflow-auto transition-all">
                <div class="max-w-full mx-auto bg-white p-6 shadow-md">
                    <h1 class="text-2xl font-bold mb-4">
                        Detail Berita
                    </h1>
                    <div class="mb-4">
                        <img 
                            alt="News Illustration or Picture" 
                            class="max-w-4xl mx-auto h-auto mb-4 object-cover" 
                            height="400"
                            width="800" 
                            x-bind:src="data.foto" />
                    </div>
                    <div class="mb-10 relative">
                        <table class="w-full border-collapse border border-gray-300">
                        <tr>
                        <td class="border border-gray-300 p-2 font-semibold">
                        Judul Berita
                        </td>
                        <td class="border border-gray-300 p-2">
                            <span class="font-bold" x-text="data.judul_berita"></span>
                        </td>
                        </tr>
                        <tr>
                        <td class="border border-gray-300 p-2 font-semibold">
                        Creator
                        </td>
                        <td class="border border-gray-300 p-2">
                            <span x-text="data.creator"></span>
                        </td>
                        </tr>
                        <tr>
                        <td class="border border-gray-300 p-2 font-semibold">
                        No Telpon
                        </td>
                        <td class="border border-gray-300 p-2">
                            <span x-text="data.notelp"></span>
                        </td>
                        </tr>
                        <tr>
                        <td class="border border-gray-300 p-2 font-semibold">
                        Tanggal Upload
                        </td>
                        <td class="border border-gray-300 p-2">
                            <span x-text="dateConvert(data.tanggal_upload)"></span>
                        </td>
                        </tr>
                        <tr>
                        <td class="border border-gray-300 p-2 font-semibold">
                            Deskripsi
                        </td>
                            <td class="border border-gray-300 p-2 text-justify">
                                <p x-text="data.deskripsi" style="white-space: pre-line;"></p>
                            </td>
                        </tr>
                        <tr>
                        <td class="border border-gray-300 p-2 font-semibold">
                        Status
                        </td>
                        <td class="border border-gray-300 p-2">
                            <span x-show="Boolean(!data.publish)">Non-published</span>
                            <span x-show="Boolean(data.publish)">Published</span>
                        </td>
                        </tr>
                        </table>
                        <a href="data_berita.php" class="bg-green-700 text-white py-2 px-4 border rounded-lg absolute left-0 mt-2">Kembali</a>
                        <button @click="submitForm(data.publish)" :class="Boolean(data.publish) ? 'bg-red-600' : 'bg-yellow-600'" class="right-0 absolute border mt-2 rounded-lg text-white px-4 py-2" x-text="Boolean(data.publish) ? 'None Publish' : 'Publish'"></button>
                    </div>

                    </div>
                </main>
        </div>
    </div>
    <script src="assets/js/flashSession.js"></script>
    <script>
        let sessionMessage = sessionStorage.getItem("message");
        if (sessionFlash && sessionFlash == "updated") {
            showAlert("Berhasil!", sessionMessage, "success", false);
            setTimeout(() => {
                sessionStorage.removeItem("status");
                sessionStorage.removeItem("message");
            }, 1000);
        }
    </script>
    <script>
        
    </script>
    <?php require 'components/footer.php'; ?>
</body>
</html>

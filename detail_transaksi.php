<?php
$current_page = 'transaction';
require 'controllers/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail - Transaction</title>
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
                id: '',
                kode: '',
                nama_customer: '',
                produk: '',
                qty: '',
                harga: '',
                total_harga: '',
                metode: '',
                status: '',
                deadline: '',
                bukti_pembayaran: '',
                created_at: '',
                email: ''
            },
            fetchTr() {
                let url = `${base}api/data_transaksi.php?id=<?= $_GET['id'] ?>&action=getOne`;
                fetch(url)
                    .then(response => response.json())
                    .then(resData => {
                        this.data = resData.data;
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                    });
            },
            setAdminFee(cost){
                const percentage = 0.15; //15%
                return percentage * Math.round(cost);
            },
            reduceByAdminFee(cost){
                const percentage = 0.15; //15%
                return Math.round(cost) - (percentage * Math.round(cost));
            }
        }" x-init="fetchTr()"
        >
            <div class="w-full h-10 bg-white mb-2"></div>
            <form action="controllers/c_update_transaction.php" method="post">
                <main :class="{'ml-0': !sidebarOpen, 'ml-4': sidebarOpen}" class="flex-1 p-6 bg-gray-50 overflow-auto transition-all">
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h2 class="text-2xl font-bold mb-6">
                            Detail Transaksi
                        </h2>
                        <table class="w-full border-collapse border border-gray-300" >
                            <tbody x-data="{
                            currency(num){
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
                            },
                            setNormalWord(word) {
                                const words = word.replaceAll('_', ' ').split(' ');

                                const formattedWords = words.map(w => w.charAt(0).toUpperCase() + w.slice(1).toLowerCase());

                                const result = formattedWords.join(' ');
                                return result;
                            }
                            }">
                                <tr>
                                    <td class="border border-gray-300 p-2 bg-gray-200">Kode Transaksi</td>
                                    <td class="border border-gray-300 p-2">
                                        <?php if (isset($_GET['edit'])) { ?>
                                            <input type="number" value="<?= $_GET['id'] ?>" name="id" class="hidden" hidden readonly />
                                            <input type="text" x-model="data.kode" class="w-full h-full" name="kode" />
                                        <?php } else { ?>
                                            <span class="font-bold" x-text="data.kode"></span>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 p-2 bg-gray-200">Nama Konsumen</td>
                                    <td class="border border-gray-300 p-2">
                                        <?php if (isset($_GET['edit'])) { ?>
                                            <input type="text" x-model="data.nama_customer" name="nama_customer" class="w-full h-full">
                                        <?php } else { ?>
                                            <span class="font-bold"x-text="data.nama_customer"></span>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php if(!isset($_GET['edit'])){?>
                                    <tr>
                                        <td class="border border-gray-300 p-2 bg-gray-200">Email</td>
                                        <td class="border border-gray-300 p-2">
                                            <span class="font-bold"x-text="data.email"></span>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <?php if(!isset($_GET['edit'])){?>
                                    <tr>
                                        <td class="border border-gray-300 p-2 bg-gray-200">Waktu Transaksi</td>
                                        <td class="border border-gray-300 p-2">
                                            <span class="font-bold"x-text="dateConvert(data.created_at)"></span>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td class="border border-gray-300 p-2 bg-gray-200">Produk</td>
                                    <td class="border border-gray-300 p-2">
                                        <?php if (isset($_GET['edit'])) { ?>
                                            <input type="text" class="w-full h-full" x-model="data.produk" name="produk">
                                        <?php } else { ?>
                                            <span class="font-bold" x-text="data.produk"></span>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 p-2 bg-gray-200">Kuantitas</td>
                                    <td class="border border-gray-300 p-2">
                                        <?php if (isset($_GET['edit'])) { ?>
                                            <input type="number" class="w-full h-full" x-model="data.qty" name="qty">
                                        <?php } else { ?>
                                            <span class="font-bold" x-text="data.qty"></span>
                                        <?php }?>
                                    </td>
                                </tr>
                                <?php if(!isset($_GET['edit'])){?>
                                    <tr>
                                        <td class="border border-gray-300 p-2 bg-gray-200">Harga</td>
                                        <td class="border border-gray-300 p-2">
                                            Rp. <span class="font-bold" x-text="currency(data.harga)"></span>
                                        </td>
                                    </tr>
                                <?php }?>
                                <?php if (!isset($_GET['edit'])) { ?>
                                    <tr>
                                        <td class="border border-gray-300 p-2 bg-gray-200">
                                            Admin Fee
                                        </td>
                                        <td class="border border-gray-300 p-2">
                                            Rp. <span class="font-bold" x-text="currency(setAdminFee(data.total_harga))"></span> <strong>(15%)</strong>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td class="border border-gray-300 p-2 bg-gray-200"><?php echo (isset($_GET['edit'])) ? 'Harga' : 'Total Harga' ?></td>
                                    <td class="border border-gray-300 p-2">
                                    <?php if (isset($_GET['edit'])) { ?>
                                        <input type="number" class="w-full h-full" x-model="data.harga" name="harga" />
                                    <?php } else { ?>
                                        Rp. <span class="font-bold" x-text="currency(reduceByAdminFee(data.total_harga))"></span>
                                    <?php } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 p-2 bg-gray-200">Saluran Pembayaran</td>
                                    <td class="border border-gray-300 p-2" x-data="{
                                        methods: [],
                                        selectedMethod: '',
                                        fetchPaymentMethods() {
                                            fetch('http://localhost/admin-ulbi/api/payment.php?action=listMethod')
                                                .then(response => response.json())
                                                .then(resData => {
                                                    if (resData.statusCode === 200) {
                                                        this.methods = resData.data;
                                                    } else {
                                                        console.error('Error fetching payment methods:', resData.message);
                                                    }
                                                })
                                                .catch(error => {
                                                    console.error('Fetch error:', error);
                                                });
                                        }
                                    }" x-init="fetchPaymentMethods()">
                                        <?php if (isset($_GET['edit'])) { ?>
                                            <select name="metode" x-model="selectedMethod" class="w-full p-2 border rounded">
                                                <option :value="data.metode" x-text="data.metode"></option>
                                                <template x-for="method in methods" :key="method">
                                                    <template x-if="method != data.metode">
                                                        <option :value="method" x-text="method"></option>
                                                    </template>
                                                </template>
                                            </select>
                                        <?php } else { ?>
                                            <span class="font-bold" x-text="data.metode"></span>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 p-2 bg-gray-200">Status</td>
                                    <td class="border border-gray-300 p-2" x-data="{ statusSelect: data.status, customStatus: '' }">
                                        <?php if (isset($_GET['edit'])) { ?>
                                            <select name="status" id="status" x-model="statusSelect" class="w-full p-2 border rounded">
                                                <option :value="data.status" x-text="setNormalWord(data.status)" selected></option>
                                                <option value="pending">Pending</option>
                                                <option value="diproses">Diproses</option>
                                                <option value="berhasil">Berhasil</option>
                                                <option value="selesai">Selesai</option>
                                                <option value="dibatalkan">Dibatalkan</option>
                                                <option value="menunggu_pembayaran">Menunggu Pembayaran</option>
                                                <option value="dikirim">Dikirim</option>
                                                <option value="terkirim">Terkirim</option>
                                                <option value="gagal">Gagal</option>
                                                <option value="refund">Refund</option>
                                                <option value="verifikasi">Verifikasi</option>
                                                <option value="menunggu_konfirmasi">Menunggu Konfirmasi</option>
                                                <option value="custom">Status Lainnya</option>
                                            </select>

                                            <div x-show="statusSelect === 'custom'" class="mt-2">
                                                <input type="text" name="custom_status" id="custom_status" x-model="customStatus" placeholder="Masukkan status lainnya" class="w-full p-2 border rounded" />
                                            </div>
                                        <?php } else { ?>
                                            <span class="font-bold" x-text="setNormalWord(data.status)"></span>
                                        <?php }?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 p-2 bg-gray-200">Deadline</td>
                                    <td class="border border-gray-300 p-2">
                                        <?php if (isset($_GET['edit'])) { ?>
                                            <input type="datetime-local" step="1" class="w-full h-full" x-model="data.deadline" name="deadline">
                                        <?php } else { ?>
                                            <span class="font-bold" x-text="data.deadline"></span>
                                        <?php }?>
                                    </td>
                                </tr>
                                <?php if(!isset($_GET['edit'])){?>
                                    <tr>
                                        <td class="border border-gray-300 p-2 bg-gray-200">Bukti Pembayaran</td>
                                        <td class="border border-gray-300 p-2">
                                            <template x-if="data.bukti_pembayaran">
                                                <a class="text-blue-800 underline" x-bind:href="data.bukti_pembayaran" >Lihat Gambar &raquo;</a>
                                            </template>
                                            <template x-if="!data.bukti_pembayaran">
                                                <a class="text-red-500" href="#" >Belum melakukan pembayaran!</a>
                                            </template>
                                        </td>
                                    </tr>
                                <?php }?>
                            </tbody>
                        </table>
                        <?php if(isset($_GET['edit'])){ ?>
                        <div class="flex justify-between mt-6">
                            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded" >UPDATE</button>
                            <a class="bg-red-600 text-white px-6 py-2 rounded" @click="getTo('data_transaksi.php')">CANCEL</a>
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

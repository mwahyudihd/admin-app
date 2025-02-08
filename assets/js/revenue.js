function getTotalHarga(base){
    return { 
        revenue: '',
        fetchRev() {
            let url = `${base}api/data_transaksi.php?category=totally`;
            fetch(url)
                .then(response => response.json())
                .then(result => {
                    this.revenue = result.total_harga;
                })
                .catch(error => console.error('Error fetching data:', error));
        } 
    }
}

function newTotalHarga(base){
    return {
        transaksi: [],
        total_harga: 0,
        getTransaksi() {
            fetch(`${base}api/data_transaksi.php?category=currently`)
                .then(response => response.json())
                .then(data => {
                    if (data.statusCode === 200) {
                        this.transaksi = data.data;
                        this.calculateTotalHarga();
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        },
        calculateTotalHarga() {
            this.total_harga = this.transaksi.reduce((acc, transaksi) => {
                return acc + parseFloat(transaksi.total_harga);
            }, 0);
        }
    }
}
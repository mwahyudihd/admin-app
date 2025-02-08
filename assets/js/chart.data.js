function chartData(base) {
    return {
        successData: [],
        failData: [],
        labels: [],
        chart: null,

        async fetchData() {
            try {
                const successResponse = await fetch(`${base}api/data_transaksi.php?category=successTransaction`);
                const successData = await successResponse.json();

                const failResponse = await fetch(`${base}api/data_transaksi.php?category=fail`);
                const failData = await failResponse.json();

                this.successData = successData.data.map(item => parseFloat(item.total_harga));
                this.failData = failData.data.map(item => parseFloat(item.total_harga));

                this.labels = Array.from({ length: this.successData.length }, (_, index) => `${index + 1}`);

                this.createChart();
            } catch (error) {
                console.error("Error fetching data:", error);
            }
        },

        createChart() {
            const ctx = document.getElementById('myChart').getContext('2d');
            this.chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: this.labels,
                    datasets: [
                        {
                            label: 'Profit',
                            data: this.successData,
                            borderColor: 'rgba(22, 44, 255, 0.8)',
                            fill: false,
                            tension: 0.1
                        },
                        {
                            label: 'Lost',
                            data: this.failData,
                            borderColor: 'rgba(255, 0, 0, 0.8)',
                            fill: false,
                            tension: 0.1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }
    };
}
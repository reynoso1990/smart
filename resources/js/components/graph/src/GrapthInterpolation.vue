<template>
    <div class="chart-interpolation-container d-flex align-items-center">
        <canvas ref="canvas"></canvas>
    </div>
</template>

<style>
.chart-interpolation-container {
    position: relative;
    min-height: 350px;
    width: 99%;
}
</style>

<script>
import Chart from "chart.js";

export default {
    props: ["allData"],
    data() {
        return {
            chart: null,

            labels: [
                "Ene",
                "Feb",
                "Mar",
                "Abr",
                "May",
                "Jun",
                "Jul",
                "Ago",
                "Set",
                "Oct",
                "Nov",
                "Dic"
            ],

            datasets: [
                {
                    label: "Venta sunat",
                    fill: false,
                    cubicInterpolationMode: "monotone",
                    tension: 0.4,
                    backgroundColor: "rgb(38, 148, 212)",
                    borderColor: "rgb(38, 148, 212)", // Establece el color de la línea
                    borderWidth: 2,
                    data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                },
                {
                    label: "Venta interna",
                    fill: false,
                    tension: 0.4,
                    backgroundColor: "rgb(22, 198, 40)",
                    borderColor: "rgb(22, 198, 40)",
                    borderWidth: 2,
                    data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                },
                {
                    tension: 0.4,
                    label: "Compras + Gastos",
                    fill: false,
                    backgroundColor: "rgb(255, 99, 132)",
                    borderColor: "rgb(255, 99, 132)",
                    borderWidth: 2,
                    // data: [100, 200, 150, 250, 180, 0, 0, 0, 0, 0, 0, 0]
                    //llena data con 12 numeros aleatorios entre 100 y 650
                    data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                }
            ],
            options: {
                legend:{
                    padding: 25
                },
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: "Datos"
                    }
                },
                interaction: {
                    intersect: false
                },
                scales: {
                    xAxes: [
                        {
                            display: true,
                            scaleLabel: {
                                display: true
                            }
                        }
                    ],
                    yAxes: [
                        {
                            display: true,
                            scaleLabel: {
                                display: true,
                            },
                            ticks: {
                                callback: function(value, index, values) {
                                    return "S/." + value.toFixed(2); // Agrega el signo de moneda y ajusta a 2 decimales
                                }
                            }
                        }
                    ]
                }
            },
            salesSunat: [],
            sales: [],
            purchasesExpenses: []
        };
    },
    created() {
        this.title = "Comprobantes";
    },
    mounted() {},
    watch: {
        allData() {
            this.createChart();
            // console.log(this.allData
        }
    },
    methods: {
        createChart() {
            if (this.chart) {
                this.chart.destroy();
            }
            this.salesSunat = this.allData.map(item => Number(item.sunat_sale));
            this.sales = this.allData.map(item => Number(item.internal_sale));
            this.purchasesExpenses = this.allData.map(item =>
                Number(item.purchase_expense)
            );

            this.datasets[0].data = this.salesSunat;
            this.datasets[1].data = this.sales;
            this.datasets[2].data = this.purchasesExpenses;
            this.chart = new Chart(this.$refs.canvas.getContext("2d"), {
                type: "line",
                data: {
                    labels: this.labels,
                    datasets: this.datasets
                },
                options: this.options
            });
        }
    }
};
</script>

<template>
    <div>
        <div class="page-header pr-0">
            <h2>
                <a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a>
            </h2>
            <ol class="breadcrumbs">
                <li class="active">
                    <span>{{ title }}</span>
                </li>
            </ol>
            <div class="right-wrapper pull-right"></div>
        </div>
        <div class="card mb-0">
            <div class="card-header">
                <h3 class="my-0">{{ title }}</h3>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th colspan="2"></th>
                        <th class="text-center" colspan="5">
                            FISICO
                        </th>
                        <th
                            style="border-bottom:1px solid black;margin-left:2px"
                            class="text-center"
                            colspan="5"
                        >
                            VALORADO
                        </th>
                    </tr>
                    <tr slot="heading">
                        <th>#</th>
                        <th>Producto</th>
                        <th class="text-center">INGRESO</th>
                        <th class="text-center">SALIDA</th>
                        <th class="text-center">SALDO</th>
                        <th class="text-center">SALDO ANTERIOR</th>
                        <th class="text-center">SALDO TOTAL</th>
                        <th class="text-center">INGRESO</th>
                        <th class="text-center">SALIDA</th>
                        <th class="text-center">SALDO</th>
                        <th class="text-center">SALDO ANTERIOR</th>
                        <th class="text-center">SALDO TOTAL</th>
                    </tr>

                    <tr></tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td>{{ row.description }}</td>
                        <td class="text-right">
                            {{ Number(row.purchase_stock).toFixed(2) }}
                        </td>
                        <td class="text-right">
                            {{
                                Number(row.sale_stock.replace("-", "")).toFixed(
                                    2
                                )
                            }}
                        </td>
                        <td class="text-right">
                            {{
                                (
                                    Number(row.purchase_stock) +
                                    Number(row.sale_stock)
                                ).toFixed(2)
                            }}
                        </td>
                        <td class="text-right">
                            {{ row.past_stock.toFixed(2) }}
                        </td>
                        <td class="text-right">
                            {{
                                (
                                    Number(row.past_stock) +
                                    Number(row.purchase_stock) +
                                    Number(row.sale_stock)
                                ).toFixed(2)
                            }}
                        </td>
                        <td class="text-right">
                            {{ row.purchase_val.toFixed(2) }}
                        </td>
                        <td class="text-right">
                            {{ row.sale_val.toFixed(2).replace("-", "") }}
                        </td>
                        <td class="text-right">
                            {{
                                (
                                    Number(row.purchase_val) +
                                    Number(row.sale_val)
                                ).toFixed(2)
                            }}
                        </td>
                        <td class="text-right">
                            {{ row.past_val.toFixed(2) }}
                        </td>
                        <td class="text-right">
                            {{
                                (
                                    Number(row.past_val) +
                                    Number(row.purchase_val) +
                                    Number(row.sale_val)
                                ).toFixed(2)
                            }}
                        </td>

                        <!-- <td class="text-right">{{ row.total }}</td>
                        <td class="text-right">
                            {{ row.purchase_price }}
                        </td>
                        <td class="text-right">{{ row.total_cost }}</td> -->
                    </tr>
                </data-table>
            </div>
        </div>
    </div>
</template>
<style>
.text-right {
    text-align: right !important;
}
</style>
<script>
import DataTable from "../../components/DataTableStockDate.vue";

export default {
    components: { DataTable },
    data() {
        return {
            title: null,
            resource: "reports/valued-kardex"
        };
    },
    created() {
        this.title = "Stock Histórico";
    },
    methods: {
        clickDownloadFormatSunat(item_id) {
            this.$eventHub.$emit("exportFormatSunat", item_id);
        }
    }
};
</script>

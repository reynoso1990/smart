<template>
    <div class="card">
        <div class="card-header">
            <h3 class="my-0">Dashboard Ventas - Compras</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Descripción</th>
                            <th>Mostrar en dashboard</th>
                            <th>Venta Sunat</th>
                            <th>Venta Interna</th>
                            <th>Venta Gasto + Compra</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, index) in months" :key="index">
                            <td>
                                {{ index + 1 }}
                            </td>
                            <td>
                                {{ row.period }}
                            </td>
                            <td>
                                <el-switch
                                    v-model="row.show"
                                    active-color="#13ce66"
                                    inactive-color="#ff4949"
                                    active-text="Manual"
                                    inactive-text="Auto."
                                >
                                </el-switch>
                            </td>
                            <td>
                                <el-input-number
                                    v-model="row.sunat_sale"
                                    :min="0"
                                    :max="1000000"
                                    label="Cantidad"
                                ></el-input-number>
                            </td>
                            <td>
                                <el-input-number
                                    v-model="row.internal_sale"
                                    :min="0"
                                    :max="1000000"
                                    label="Cantidad"
                                ></el-input-number>
                            </td>
                            <td>
                                <el-input-number
                                    v-model="row.purchase_expense"
                                    :min="0"
                                    :max="1000000"
                                    label="Cantidad"
                                ></el-input-number>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" class="text-right">
                                <button
                                    class="btn btn-primary"
                                    @click="saveData"
                                >
                                    Guardar
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
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
import { deletable } from "../../../../../../resources/js/mixins/deletable";
 import moment from 'moment'
export default {
    mixins: [deletable],
    props: ["typeUser"],
    components: {},
    data() {
        return {
            showDialog: false,
            resource: "sunat_purchase_sale",
            recordId: null,
            records: [],
            months: []
        };
    },
    created() {
        this.$eventHub.$on("reloadData", () => {
            this.getData();
        });
        this.getData();
        this.setMonths();
    },
    methods: {
       async  saveData(){
            let periods = this.months;
            const response = await this.$http.post(`/${this.resource}`, {periods});
            if(response.data.success){
                this.$message.success(response.data.message);
            }else{
                this.$message.error("Ocurrio un error al guardar los datos");
            }

        },
        setMonths(year) {
            if (!year) {
                year = moment().format("YYYY");
            }
            this.months = [];
            for (let i = 1; i <= 12; i++) {
                let m = i < 10 ? `0${i}` : i;
                let date = moment(`${year}-${m}-01`).format("YYYY-MM-DD");
                this.months.push({
                    id: i,
                    show: false,
                    sunat_sale: 0,
                    internal_sale: 0,
                    purchase_expense: 0,
                    period: date,
                });
            }
        },
        async getData() {
            const response = await this.$http.get(`/${this.resource}/records/${moment().format("YYYY")}`);
               if(response.data.records.length > 0){
                 this.months = response.data.records.map((row) => {
                    row.show = row.show == 1 ? true : false;
                    return row;
                });
               }
        },
        clickCreate(recordId = null) {
            this.recordId = recordId;
            this.showDialog = true;
        },
        clickDelete(id) {
            this.destroy(`/${this.resource}/${id}`).then(() =>
                this.$eventHub.$emit("reloadData")
            );
        }
    }
};
</script>

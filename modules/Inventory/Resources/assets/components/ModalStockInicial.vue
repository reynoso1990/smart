<template>
    <el-dialog
        @open="open"
        @close="close"
        :visible="showDialog"
        title="Stock inicial por mes"
        append-to-body
        v-loading="loading"
    >
        <div class="d-flex align-items-center" v-if="currentWarehouse">
            <span>
                <strong>{{ currentWarehouse.warehouse_description }}</strong>
                : {{ currentWarehouse.stock }}
                (stock actual)
            </span>
            <el-button
                type="warning"
                style="margin-left:15px"
                v-if="needAdjustment"
                @click="adjustmentStock"
            >
                Ajustar stock a {{ currentWarehouse.correct_stock }}
            </el-button>
        </div>
        <table class="table table-responsive">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Mes</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(record, idx) in records" :key="idx">
                    <td>
                        {{ customIndex(idx) }}
                    </td>
                    <td>{{ record.init_date }}</td>
                    <td>{{ record.stock }}</td>
                </tr>
            </tbody>
        </table>
        <el-pagination
            @current-change="getRecords"
            layout="total, prev, pager, next"
            :total="pagination.total"
            :current-page.sync="pagination.current_page"
            :page-size="pagination.per_page"
        >
        </el-pagination>
    </el-dialog>
</template>

<script>
import queryString from "query-string";
export default {
    props: ["showDialog", "item_id", "warehouse_id"],
    data() {
        return {
            records: [],
            loading: false,
            pagination: {},
            record: null,
            currentWarehouse: null,
            needAdjustment: false
        };
    },
    created() {},
    methods: {
        async adjustmentStock() {
            try {
                this.loading = true;
                const response = await this.$http(
                    `/reports/kardex/stock_adjustment?item_id=${
                        this.item_id
                    }&warehouse_id=${this.warehouse_id}&correct_stock=${
                        this.currentWarehouse.correct_stock
                    }`
                );
                if (response.status == 200) {
                    this.$message.success("Stock ajustado");
                    this.close();
                } else {
                    this.$message.error("Ocurrió un error");
                }
            } catch (e) {
                console.log(e);
                this.$message.error("Ocurrió un error");
            } finally {
                this.loading = false;
            }
        },
        async getRecord() {
            try {
                //items/record/{item}
                const response = await this.$http(
                    `/reports/kardex/item_adjustment?item_id=${
                        this.item_id
                    }&warehouse_id=${this.warehouse_id}`
                );

                console.log(response);
                let {
                    success,
                    correct_stock,
                    warehouse_description,
                    stock
                } = response.data;
                this.currentWarehouse = {
                    warehouse_description,
                    stock,
                    correct_stock
                };
                this.needAdjustment = correct_stock != stock;
            } catch (e) {
                console.log("error: "+e);
            } finally {
            }
        },
        customIndex(index) {
            return (
                this.pagination.per_page * (this.pagination.current_page - 1) +
                index +
                1
            );
        },
        getQueryParameters() {
            return queryString.stringify({
                page: this.pagination.current_page,
                limit: this.limit
            });
        },
        async getRecords() {
            try {
                this.loading = true;
                const response = await this.$http(
                    `/reports/kardex/get_init_stock?${this.getQueryParameters()}&item_id=${
                        this.item_id
                    }&warehouse_id=${
                        this.warehouse_id ? this.warehouse_id : ""
                    }`
                );
                this.pagination = response.data.meta;
                this.pagination.per_page = parseInt(
                    response.data.meta.per_page
                );
                this.records = response.data.data;
            } catch (e) {
                console.log(e);
            } finally {
                this.loading = false;
            }
        },
        async open() {
            this.needAdjustment = false;
            this.currentWarehouse = null;
            this.records = [];
            await this.getRecords();
            await this.getRecord();
        },
        lastDay(date) {
            const parseDate = moment(date);
            const month = parseDate.month();
            parseDate.month(month + 1);
            parseDate.date(0);
            const lastDayOfMont = parseDate.format("YYYY-MM-DD");
            return lastDayOfMont;
        },
        close() {
            this.$emit("update:showDialog", false);
        }
    }
};
</script>

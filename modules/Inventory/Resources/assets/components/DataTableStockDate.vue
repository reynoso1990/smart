<template>
    <div>
        <div class="row">
            <div class="col-md-12 col-lg-12 col-xl-12 ">
                <div class="row mt-2">
                    <div class="col-md-3">
                        <label class="control-label">Desde la fecha</label>
                        <el-date-picker
                            v-model="form.start"
                            type="date"
                            value-format="yyyy-MM-dd"
                            format="dd/MM/yyyy"
                            :clearable="false"
                        ></el-date-picker>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label">Hasta la fecha</label>
                        <el-date-picker
                            v-model="form.date"
                            type="date"
                            value-format="yyyy-MM-dd"
                            format="dd/MM/yyyy"
                            :clearable="false"
                        ></el-date-picker>
                    </div>

                    <!-- <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Establecimiento</label>
                            <el-select
                                v-model="form.establishment_id"
                                clearable
                                filterable
                            >
                                <el-option
                                    v-for="option in establishments"
                                    :key="option.id"
                                    :value="option.id"
                                    :label="option.name"
                                ></el-option>
                            </el-select>
                        </div>
                    </div> -->

                    <div
                        class="col-lg-7 col-md-7 col-md-7 col-sm-12"
                        style="margin-top:40px"
                    >
                        <el-button
                            class="submit"
                            type="primary"
                            @click.prevent="getRecordsByFilter"
                            :loading="loading_submit"
                            icon="el-icon-search"
                            >Buscar
                        </el-button>

                        <template v-if="records.length > 0">
                            <el-button
                                class="submit"
                                type="success"
                                @click.prevent="clickDownload('excel')"
                                ><i class="fa fa-file-excel"></i> Exportar Excel
                            </el-button>
                        </template>

                        <!-- <el-tooltip class="item"
                                    content="Formato SUNAT 13.1"
                                    effect="dark"
                                    placement="top">

                            <el-button class="submit" type="success" @click.prevent="clickDownload('excel-format-sunat')"><i
                                class="fa fa-file-excel"></i> Exportar Format Sunat
                            </el-button>
                        </el-tooltip> -->
                    </div>
                </div>
                <div class="row mt-1 mb-4"></div>
            </div>

            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <slot name="heading"></slot>
                        </thead>
                        <tbody>
                            <slot
                                v-for="(row, index) in records"
                                :row="row"
                                :index="customIndex(index)"
                            ></slot>
                        </tbody>
                    </table>
                    <div>
                        <el-pagination
                            @current-change="getRecords"
                            layout="total, prev, pager, next"
                            :total="pagination.total"
                            :current-page.sync="pagination.current_page"
                            :page-size="pagination.per_page"
                        >
                        </el-pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.font-custom {
    font-size: 15px !important;
}
</style>
<script>
import moment from "moment";
import queryString from "query-string";

export default {
    props: {
        resource: String
    },
    data() {
        return {
            loading_submit: false,
            loading_search: false,
            columns: [],
            records: [],
            pagination: {},
            search: {},
            totals: {},
            form: {}
        };
    },
    computed: {},
    created() {
        this.initForm();
    },
    async mounted() {},
    methods: {
        clickDownload(type) {
            let query = queryString.stringify({
                ...this.form
            });
            window.open(
                `/reports/valued-kardex/general_stock_excel?${this.getQueryParameters()}`,
                "_blank"
            );
        },
        initForm() {
            this.form = {
                date: moment().format("YYYY-MM-DD"),
                start: moment().format("YYYY-MM-DD")
            };
        },
        customIndex(index) {
            return (
                this.pagination.per_page * (this.pagination.current_page - 1) +
                index +
                1
            );
        },
        async getRecordsByFilter() {
            if (!this.isValidEvent(this.form)) return;
            this.loading_submit = await true;
            await this.getRecords();
            this.loading_submit = await false;
        },
        isValidEvent(event) {
            if (!event.start || !event.date) {
                this.$message.error("Faltan las propiedades 'start' o 'date'.");
                return false;
            }

            const start = moment(event.start);
            const date = moment(event.date);

            if (!start.isValid() || !date.isValid()) {
                this.$message.error("La fecha de inicio o fin no es válida.");
                return false;
            }

            if (start.isSame(date, "day")) {
                this.$message.error(
                    "La fecha de inicio y fin no pueden ser iguales."
                );
                return false;
            }

            if (date.isBefore(start)) {
                this.$message.error(
                    "La fecha de fin debe ser posterior a la de inicio."
                );
                return false;
            }

            return true;
        },
        async getRecords() {
            const response = await this.$http.get(
                `/reports/valued-kardex/general_stock?${this.getQueryParameters()}`
            );

            this.records = response.data.records;
            this.pagination = response.data.pagination;
            // this.pagination.per_page = parseInt(response.data.meta.per_page);
            // this.initTotals()
        },
        getQueryParameters() {
            return queryString.stringify({
                page: this.pagination.current_page,
                limit: this.limit,
                ...this.form
            });
        }
    }
};
</script>

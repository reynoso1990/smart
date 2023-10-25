<template>
    <div v-loading="loading_submit">
        <div class="row">
            <div class="col-md-12 col-lg-12 col-xl-12">
                <div class="row" v-if="applyFilter">
                    <div class="col-lg-4 col-md-4 col-sm-12 pb-2">
                        <div class="d-flex">
                            <div style="width: 100px">Filtrar por:</div>
                            <el-select
                                v-model="search.column"
                                placeholder="Select"
                                @change="changeClearInput"
                            >
                                <el-option
                                    v-for="(label, key) in columns"
                                    :key="key"
                                    :value="key"
                                    :label="label"
                                ></el-option>
                            </el-select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12 pb-2">
                        <template
                            v-if="
                                search.column === 'date_of_issue' ||
                                search.column === 'date_of_due' ||
                                search.column === 'date_of_payment' ||
                                search.column === 'delivery_date'
                            "
                        >
                            <el-date-picker
                                v-model="search.value"
                                type="date"
                                style="width: 100%"
                                placeholder="Buscar"
                                value-format="yyyy-MM-dd"
                                @change="getRecords"
                            >
                            </el-date-picker>
                        </template>
                        <template v-else-if="search.column == 'person_type_id'">
                            <el-select
                                v-model="search.value"
                                @change="getRecords"
                            >
                                <el-option
                                    v-for="(label, key) in personTypes"
                                    :key="key"
                                    :value="label.id"
                                    :label="label.description"
                                ></el-option>
                            </el-select>
                        </template>
                        <template v-else-if="search.column == 'unit_type_id'">
                            <el-select
                                v-model="search.value"
                                placeholder="Select"
                                @change="getRecords"
                                filterable
                            >
                                <el-option
                                    v-for="(label, key) in unitTypes"
                                    :key="key"
                                    :value="label.id"
                                    :label="label.description"
                                ></el-option>
                            </el-select>
                        </template>
                        <template v-else>
                            <el-input
                                placeholder="Buscar"
                                v-model="search.value"
                                style="width: 100%"
                                prefix-icon="el-icon-search"
                                @input="getRecords"
                            >
                            </el-input>
                        </template>
                    </div>
                    <div
                        class="col-lg-3 col-md-4 col-sm-12 pb-2"
                        v-if="
                            (resource == 'persons/customers' &&
                                search.column == 'name') ||
                            search.column == 'internal_code'
                        "
                    >
                        <div class="d-flex align-items-center">
                            <el-button
                                type="text"
                                @click="ordenar('desc')"
                                v-if="search.order == 'asc'"
                            >
                                <i class="el-icon-arrow-up"></i> Ordenar
                                Ascendente
                            </el-button>
                            <el-button
                                v-else
                                type="text"
                                @click="ordenar('asc')"
                            >
                                <i class="el-icon-arrow-down"></i> Ordenar
                                Descendente
                            </el-button>
                        </div>
                    </div>
                    <div
                        class="col-lg-3 col-md-4 col-sm-12 pb-2"
                        v-if="resource == 'items'"
                    >
                        <div class="d-flex align-items-center">
                            <el-button
                                type="text"
                                :disabled="!order"
                                @click="ordenar('desc')"
                                v-if="search.order_price == 'asc'"
                            >
                                <i class="el-icon-arrow-up"></i> Ordenar
                                Ascendente S/.
                            </el-button>
                            <el-button
                                v-else
                                type="text"
                                :disabled="!order"
                                @click="ordenar('asc')"
                            >
                                <i class="el-icon-arrow-down"></i> Ordenar
                                Descendente S/.
                            </el-button>
                            <el-checkbox
                                style="margin-left: 5px"
                                v-model="order"
                                @change="orderPrice"
                            >
                                Ordenar por Precio
                            </el-checkbox>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" v-if="records.length > 0 && isDriver">
                <div class="col-md-3">
                    <el-button
                        class="submit"
                        type="success"
                        @click.prevent="clickDownload('excel')"
                        ><i class="fa fa-file-excel"></i> Exportar
                        Excel</el-button
                    >
                </div>
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

<script>
import queryString from "query-string";

export default {
    props: {
        type: String,
        isDriver: Boolean,
        configuration: {
            type: Object,
            required: false,
            default: () => {
                return {};
            },
        },
        productType: {
            type: String,
            required: false,
            default: "",
        },
        resource: String,
        applyFilter: {
            type: Boolean,
            default: true,
            required: false,
        },
        pharmacy: Boolean,
    },
    data() {
        return {
            order: false,
            search: {
                column: null,
                value: null,
                order: "asc",
                order_price: null,
            },
            columns: [],
            records: [],
            personTypes: [],
            pagination: {},
            loading_submit: false,
            fromPharmacy: false,
            unitTypes: [],
        };
    },
    created() {
        if (this.pharmacy !== undefined && this.pharmacy === true) {
            this.fromPharmacy = true;
        }
        this.$eventHub.$on("reloadData", () => {
            this.getRecords();
        });
        this.$root.$refs.DataTable = this;

        if (this.type === "customers") {
            this.getPersonTypes();
        }
      
    },
    async mounted() {
        let column_resource = _.split(this.resource, "/");
        await this.$http
            .get(`/${_.head(column_resource)}/columns`)
            .then((response) => {
                this.columns = response.data;
                this.search.column = _.head(Object.keys(this.columns));
            });
        await this.getRecords();
        if (this.resource == "items") {
            await this.getUnitTypes();
        }
    },
    methods: {
        clickDownload(type) {
            window.open(
                `/package-handler/export_packages/${type}?${this.getQueryParameters()}`,
                "_blank"
            );
        },
        orderPrice() {
            if (!this.order) {
                this.search.order_price = null;
            } else {
                this.search.order_price = "asc";
            }
            this.getRecords();
        },
        async getUnitTypes() {
            this.loading_submit = true;
            const response = await this.$http.get(`/unit_types/records`);
            if (response.status == 200) {
                this.unitTypes = response.data.data;
            }
            this.loading_submit = false;
        },
        ordenar(value) {
            if (this.resource == "items") {
                this.search.order_price = value;
            } else {
                this.search.order = value;
            }
            this.getRecords();
        },
        customIndex(index) {
            return (
                this.pagination.per_page * (this.pagination.current_page - 1) +
                index +
                1
            );
        },
        async getPersonTypes() {
            const response = await this.$http.get(
                `/person-types/records?column=description&isPharmacy=false&page=1&value`
            );
            this.personTypes = response.data.data;
        },
        getRecords() {
            this.loading_submit = true;
            return this.$http
                .get(`/${this.resource}/records?${this.getQueryParameters()}`)
                .then((response) => {
                    this.records = response.data.data;
                    this.pagination = response.data.meta;
                    this.pagination.per_page = parseInt(
                        response.data.meta.per_page
                    );
                })
                .catch((error) => {})
                .then(() => {
                    this.loading_submit = false;
                });
        },
        getQueryParameters() {
            if (this.productType == "ZZ") {
                this.search.type = "ZZ";
            }
            if (this.productType == "PRODUCTS") {
                // Debe listar solo productos
                this.search.type = this.productType;
            }
            return queryString.stringify({
                page: this.pagination.current_page,
                limit: this.limit,
                driver: this.isDriver,
                isPharmacy: this.fromPharmacy,
                ...this.search,
            });
        },
        changeClearInput() {
            this.search.value = "";
            this.getRecords();
        },
        getSearch() {
            return this.search;
        },
    },
};
</script>

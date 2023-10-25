<template>
    <div v-loading="loading_submit">
        <div class="row ">
            <div class="col-md-12 col-lg-12 col-xl-12 ">
                <div v-if="applyFilter" class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12 pb-2">
                        <span style="width:100%">
                            Filtrar por:
                        </span>
                        <el-select
                            v-model="search.column"
                            placeholder="Select"
                            @change="changeClearInput"
                        >
                            <el-option
                                v-for="(label, key) in columns"
                                :key="key"
                                :label="label"
                                :value="key"
                            ></el-option>
                        </el-select>
                    </div>
                    <div
                        class="col-lg-3 col-md-3 col-sm-12 pb-2 d-flex align-items-end"
                    >
                        <template
                            v-if="
                                search.column === 'date_of_issue' ||
                                    search.column === 'date_of_due' ||
                                    search.column === 'date_of_payment' ||
                                    search.column === 'person_date' ||
                                    search.column === 'delivery_date'
                            "
                        >
                            <el-date-picker
                                v-model="search.value"
                                placeholder="Buscar"
                                style="width: 100%;"
                                type="date"
                                value-format="yyyy-MM-dd"
                                @change="getRecords"
                            >
                            </el-date-picker>
                        </template>
                        <template v-else-if="search.column === 'cat_period_id'">
                            <el-select
                                v-model="search.value"
                                @change="getRecords"
                                class="w-100"
                            >
                                <el-option
                                    :value="1"
                                    :label="`Mensual`"
                                ></el-option>
                                <el-option
                                    :value="2"
                                    :label="`Anual`"
                                ></el-option>
                            </el-select>
                        </template>
                        <template v-else>
                            <el-input
                                v-model="search.value"
                                placeholder="Buscar"
                                prefix-icon="el-icon-search"
                                style="width: 100%;"
                                @input="getRecords"
                            >
                            </el-input>
                        </template>
                    </div>

                    <template v-if="type == 'children'">
                        <div class="col-lg-3 col-md-3 col-sm-12 pb-2">
                            <span style="width:100%">
                                Filtrar por
                                {{
                                    getOpcionalName(
                                        "grades",
                                        "grado"
                                    ).toLowerCase()
                                }}:
                            </span>
                            <el-select
                             clearable
                                v-model="search.grade"
                                placeholder="Select"
                                @change="changeClearInput"
                            >
                                <el-option
                                    v-for="(label, key) in grades"
                                    :key="key"
                                    :label="label.name"
                                    :value="label.name"
                                ></el-option>
                            </el-select>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 pb-2">
                            <span style="width:100%">
                                Filtrar por
                                {{
                                    getOpcionalName(
                                        "sections",
                                        "sección"
                                    ).toLowerCase()
                                }}:
                            </span>
                            <el-select
                             clearable
                                v-model="search.section"
                                placeholder="Select"
                                @change="changeClearInput"
                            >
                                <el-option
                                    v-for="(label, key) in sections"
                                    :key="key"
                                    :label="label.name"
                                    :value="label.name"
                                ></el-option>
                            </el-select>
                        </div>
                    </template>
                </div>
            </div>

            <div class="col-md-12">
                <div class="container">
                    <table class="table tablex">
                        <thead class="fixed-header">
                            <slot name="heading"></slot>
                        </thead>
                        <tbody>
                            <slot
                                v-for="(row, index) in table_data"
                                :index="customIndex(index)"
                                :row="row"
                            ></slot>
                        </tbody>
                        <tfoot v-if="type == 'children'">
                            <tr>
                        <td colspan="8"></td>
                        <td>Totales Generales</td>
                        <td class="text-center" v-for="(total,idx) in generalTotals" :key="idx">
                        <small>    {{total.toFixed(2)}}</small>
                        </td>
                        <td></td>
                    </tr>
                        </tfoot>
                    </table>
                    <div>
                        <el-pagination
                            :current-page.sync="pagination.current_page"
                            :page-size="pagination.per_page"
                            :total="pagination.total"
                            layout="total, prev, pager, next"
                            @current-change="getRecords"
                        >
                        </el-pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.fixed-header {
  position: sticky !important;
  position: -webkit-sticky !important;
  top: 0 !important;
  background-color: #fff; /* Ajusta el color de fondo deseado */
  z-index: 1; /* Ajusta el z-index según sea necesario */
}
.tablex {
  /* height: 100vh !important;  */
  overflow-y: scroll !important;
}
</style>
<script>
import queryString from "query-string";
import { mapActions, mapState } from "vuex/dist/vuex.mjs";

export default {
    props: {
        suscriptionames: Object,
        type: String,
        extraquery: {
            type: Object,
            required: false,
            default: () => {}
        },
        productType: {
            type: String,
            required: false,
            default: ""
        },
        // resource: String,
        applyFilter: {
            type: Boolean,
            default: true,
            required: false
        },
        pharmacy: Boolean,

    },
    data() {
        return {
            generalTotals :[],
            time: null,
            search: {
                column: null,
                value: null
            },
            columns: [],
            records: [],
            sections: [],
            grades: [],
            pagination: {},
            loading_submit: false,
            fromPharmacy: false
        };
    },
    created() {
        this.loadConfiguration();
        if (this.pharmacy !== undefined && this.pharmacy === true) {
            this.fromPharmacy = true;
        }
        this.$eventHub.$on("reloadData", () => {
            //  console.log('Dispara reloadData')
            this.getRecords();
        });
        this.$root.$refs.DataTable = this;
    },
    async mounted() {
        let column_resource = _.split(this.resource, "/");
        let url = _.head(column_resource);
        await this.$http.get(`/suscription/${url}/columns`).then(response => {
            this.columns = response.data;
            this.search.column = _.head(Object.keys(this.columns));
        });
        await this.getRecords();
        if (this.type == "children") {
            this.getCollegeData();
        }
    },
    methods: {
        getOpcionalName(key, defaultName) {
            if (
                this.suscriptionames &&
                this.suscriptionames[key] != undefined
            ) {
                return this.suscriptionames[key];
            }
            return defaultName;
        },
        async getCollegeData() {
            const response = await this.$http.get(
                `/suscription/college/section_grades`
            );

            if (response.status == 200) {
                this.sections = response.data.sections;
                this.grades = response.data.grades;
            }
        },
        ...mapActions(["loadConfiguration"]),
        customIndex(index) {
            return (
                this.pagination.per_page * (this.pagination.current_page - 1) +
                index +
                1
            );
        },
        getRecords() {
            if (this.time) {
                clearTimeout(this.time);
            }
            this.time = setTimeout(() => {
                this.loading_submit = true;
                let url = `/suscription/${this.resource}/records`;
                return this.$http
                    .post(url, this.getQueryParameters())
                    .then(response => {
                        this.records = response.data.data;
                        this.$store.commit("setTableData", this.records);
                        this.pagination = response.data.meta;
                        this.pagination.per_page = parseInt(
                            response.data.meta.per_page
                        );
                        if(response.data.totals){
                            this.generalTotals = response.data.totals;
                        }
                    })
                    .catch(error => {})
                    .then(() => {
                        this.loading_submit = false;
                    });
            }, 500);
        },
        getQueryParameters() {
            if (this.productType == "ZZ") {
                this.search.type = "ZZ";
            }
            return {
                page: this.pagination.current_page,
                limit: this.limit,
                isPharmacy: this.fromPharmacy,
                ...this.search,
                ...this.extraquery
            };
        },
        changeClearInput() {
            this.search.value = "";
            this.getRecords();
        },
        getSearch() {
            return this.search;
        }
    },

    computed: {
        ...mapState(["config", "table_data", "resource"])
    }
};
</script>

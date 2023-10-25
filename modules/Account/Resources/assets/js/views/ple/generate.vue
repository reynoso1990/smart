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
        </div>

        <div v-loading="loading" class="card mb-0 pt-2 pt-md-0">
            <div class="card-header">
                <h3 class="my-0">{{ title }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label>Periodo</label>
                        <el-date-picker
                            v-model="form.month"
                            :clearable="false"
                            format="MM/yyyy"
                            type="month"
                            value-format="yyyy-MM"
                        ></el-date-picker>
                    </div>
                    <div class="col-md-3">
                        <label>Libro</label>
                        <el-select v-model="form.type">
                            <el-option
                                key="sale"
                                label="Venta 14.1"
                                value="sale"
                            ></el-option>
                            <el-option
                                key="purchase"
                                label="Compra 8.1"
                                value="purchase"
                            ></el-option>
                        </el-select>
                    </div>
                </div>
            </div>
            <div class="form-actions text-end pt-2">
                <el-button
                    :loading="loading_submit"
                    type="primary"
                    @click.prevent="clickDownload"
                >
                    <template v-if="loading_submit">
                        Generando...
                    </template>
                    <template v-else>
                        Generar
                    </template>
                </el-button>
            </div>
            <!--</div>-->
        </div>
        <modal-result
            :showDialog.sync="showDialog"
            :form.sync="form"
        ></modal-result>
    </div>
</template>

<script>
import ModalResult from "./modal_result.vue";
import queryString from "query-string";
import { mapActions, mapState } from "vuex";

export default {
    components: { ModalResult },
    props: ["currencies", "configuration"],
    computed: {
        ...mapState(["config", "currencys"])
    },
    data() {
        return {
            loading: false,
            loading_submit: false,
            title: null,
            resource: "/account/ple",
            error: {},
            form: {},
            showDialog: false
        };
    },
    created() {
        this.title = "Libros Electrónicos";
        this.$store.commit("setConfiguration", this.configuration);
        this.loadConfiguration();
    },
    mounted() {
        this.initForm();
    },
    methods: {
        ...mapActions(["loadConfiguration"]),
        initForm() {
            this.errors = {};
            this.form = {
                month: moment().format("YYYY-MM"),
                type: "sale"
            };
        },
        async clickDownload() {
            this.showDialog = true;
            return;
            let query = queryString.stringify({
                ...this.form
            });
            const response = await this.$http(
                `${this.resource}/generate?${query}`
            );

            console.log(response);
            // window.open(`${this.resource}/generate?${query}`);
            // console.log(response);

            // window.open(`/${this.resource}/format/download?${query}`, "_blank");
        }
    }
};
</script>

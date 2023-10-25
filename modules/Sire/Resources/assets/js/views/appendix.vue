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
                        <label>Anexo</label>
                        <el-select v-model="form.appendix">
                            <el-option
                            v-for="appendix in appendixes"
                                :key="appendix"
                                :label="`Anexo ${appendix}`"
                                :value="appendix"
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

    </div>
</template>

<script>
import queryString from "query-string";
import { mapActions, mapState } from "vuex";

export default {
    components: {  },
    props: [ "configuration"],
    computed: {
        ...mapState(["config", "currencys"])
    },
    data() {
        return {
            loading: false,
            loading_submit: false,
            title: null,
            resource: "/sire",
            error: {},
            form: {},
            appendixes:[2,3,4,5],
            showDialog: false,
            data: {}
        };
    },
    created() {
        this.title = "SIRE - Anexos " ;
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
                appendix: 2
            };
        },
          download() {
            const blob = new Blob([this.data.content], {
                type: "text/plain;charset=utf-8"
            });
            const link = document.createElement("a");
            link.download = this.data.name;
            link.href = URL.createObjectURL(blob);
            link.click();

        },
        async clickDownload() {
   
            let query = queryString.stringify({
                ...this.form
            });
            const response = await this.$http(
                `${this.resource}/generate?${query}`
            );

            this.data = response.data;
            this.download();

            console.log(response);
            // window.open(`${this.resource}/generate?${query}`);
            // console.log(response);

            // window.open(`/${this.resource}/format/download?${query}`, "_blank");
        }
    }
};
</script>

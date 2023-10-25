<template>
    <div class="card">
        <div class="card-header">
            <h3 class="my-0">
                Enviar documento PSE
                <el-tooltip
                    class="item"
                    content="Enviar facturas / boletas al PSE"
                    effect="dark"
                    placement="top-start"
                >
                    <i class="fa fa-info-circle"></i>
                </el-tooltip>
            </h3>
        </div>
        <div class="card-body">
            <form autocomplete="off" @submit.prevent="submit">
                <div class="row pt-1">
                    <div class="col-12">
                        <h4 class="control-label">
                            Enviar a servicio web externo
                            <el-tooltip
                                class="item"
                                content="Envia el documento al servicio web externo para agregar la firma digital y enviar SUNAT / OSE"
                                effect="dark"
                                placement="top-start"
                            >
                                <i class="fa fa-info-circle"></i>
                            </el-tooltip>
                        </h4>
                        <div
                            :class="{ 'has-danger': errors.pse }"
                            class="form-group"
                        >
                            <el-switch
                                v-model="form.pse"
                                active-text="Si"
                                inactive-text="No"
                            ></el-switch>
                            <small
                                v-if="errors.pse"
                                class="form-control-feedback"
                                v-text="errors.pse[0]"
                            ></small>
                        </div>
                    </div>
                      <div class="col-12">
                        <h4 class="control-label">
                            URL
                            <el-tooltip
                                class="item"
                                content="Web del servicio externo"
                                effect="dark"
                                placement="top-start"
                            >
                                <i class="fa fa-info-circle"></i>
                            </el-tooltip>
                        </h4>
                        <div
                            :class="{ 'has-danger': errors.pse_url }"
                            class="form-group"
                        >
                            <el-input v-model="form.pse_url"></el-input>
                            <small
                                v-if="errors.pse_url"
                                class="form-control-feedback"
                                v-text="errors.pse_url[0]"
                            ></small>
                        </div>
                    </div>
                    <div class="col-12">
                        <h4 class="control-label">
                            Token
                            <el-tooltip
                                class="item"
                                content="Token necesario para hacer el uso del pse"
                                effect="dark"
                                placement="top-start"
                            >
                                <i class="fa fa-info-circle"></i>
                            </el-tooltip>
                        </h4>
                        <div
                            :class="{ 'has-danger': errors.pse_token }"
                            class="form-group"
                        >
                            <el-input v-model="form.pse_token"></el-input>
                            <small
                                v-if="errors.pse_token"
                                class="form-control-feedback"
                                v-text="errors.pse_token[0]"
                            ></small>
                        </div>
                    </div>
                </div>

                <div class="form-actions text-end pt-2">
                    <el-button
                        type="primary"
                        native-type="submit"
                        :loading="loading_submit"
                        >Guardar</el-button
                    >
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            resource: "companies",
            recordId: null,
            form: {},
            errors: {},
            loading_submit: false,
        };
    },
    created() {
        this.initForm();
        this.getData();
    },
    methods: {
        submit() {
            let {pse, pse_url, pse_token} = this.form;
            if(pse && !pse_url && !pse_token){
                this.$message.error('Debe ingresar la url y el token');
                return;
            }
            this.loading_submit = true;
            this.$http
                .post(`/${this.resource}/pse`, this.form)
                .then((response) => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch((error) => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data;
                    } else {
                        console.log(error);
                    }
                })
                .then(() => {
                    this.loading_submit = false;
                });
        },
        initForm() {
            this.form = {
                send_document_to_pse: false,
                url_signature_pse: null,
                url_send_cdr_pse: null,
                client_id_pse: null,
                url_login_pse: null,
                password_pse: null,
                user_pse: null,
            };

            this.errors = {};
        },
        getData() {
            this.$http.get(`/${this.resource}/pse`).then((response) => {
                this.form = response.data;
            });
        },
    },
};
</script>

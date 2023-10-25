<template>
    <div class="d-flex">
        <el-button
            type="primary"
            @click.prevent="deleteDocuments"
            :loading="loading_submit"
            >Eliminar documentos de prueba</el-button
        >
        <el-tooltip content="No será posible recrear pdf de documentos">
            <el-button
                type="primary"
                @click.prevent="deleteItems"
                :loading="loading_submit"
                >Eliminar catálogo de productos</el-button
            >
        </el-tooltip>
        <div class="col-md-3" style="margin-left:15px;">
            <label for="erase_item" class="w-100"
                >Eliminar item individualmente
                <el-tooltip content="No será posible recrear pdf de documentos">
                    <i class="el-icon-info"></i>
                </el-tooltip>
            </label>
            <el-switch
            @change="setConfiguration"
                v-model="configuration.erase_item_indivual"
                active-text="Si"
                inactive-text="No"
            ></el-switch>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            loading_submit: false,
            loading_submit_items: false,
            resource: "options",
            errors: {},
            form: {},
            configuration:{},
            loading_submit_voided: false,
        };
    },
    created(){
        this.getConfiguration();
    },
    methods: {
        setConfiguration(){
            this.loading_submit = true;
            this.$http.post(`/configurations`, this.configuration).then((response) => {
                if (response.data.success) {
                    this.$message.success(response.data.message);
                } else {
                    this.$message.error(response.data.message);
                }
            })
            .catch((error) => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors;
                } else {
                    console.log(error);
                }
            })
            .then(() => {
                this.loading_submit = false;
            });
        },
        getConfiguration(){
            this.$http.get(`/configurations/record`).then((response) => {
                console.log(response);
                this.configuration = response.data.data;
            });
        },
        initForm() {
            this.errors = {};
            this.form = {};
        },
        deleteItems() {
            //$confirm
            this.$confirm(
                "¿Está seguro de eliminar los items?",
                "Advertencia",
                {
                    confirmButtonText: "Sí",
                    cancelButtonText: "No",
                    type: "warning",
                }
            )
                .then(() => {
                    this.loading_submit_items = true;
                    this.$http
                        .post(`/${this.resource}/delete_items`)
                        .then((response) => {
                            if (response.data.success) {
                                this.$message.success(response.data.message);
                            } else {
                                this.$message.error(response.data.message);
                            }
                        })
                        .catch((error) => {
                            if (error.response.status === 422) {
                                this.errors = error.response.data.errors;
                            } else {
                                console.log(error);
                            }
                        })
                        .then(() => {
                            this.loading_submit_items = false;
                        });
                })
                .catch(() => {});
        },
        deleteDocuments() {
            this.loading_submit = true;
            this.$http
                .post(`/${this.resource}/delete_documents`)
                .then((response) => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch((error) => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    } else {
                        console.log(error);
                    }
                })
                .then(() => {
                    this.loading_submit = false;
                });
        },
        consultVoided() {
            this.loading_submit_voided = true;
            this.$http
                .get(`/voided/status_masive`)
                .then((response) => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                    } else {
                        this.$message.error("Sucedio un error");
                    }
                })
                .catch((error) => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    } else {
                        console.log(error);
                    }
                })
                .then(() => {
                    this.loading_submit_voided = false;
                });
        },
    },
};
</script>

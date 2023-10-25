<template>
    <div class="col-lg-6 col-md-12 0">
        <div class="card">
            <div class="card-header">
                <h3 class="my-0">Número de columnas de productos</h3>
            </div>
            <div class="card-body">
                <form autocomplete="off" @submit.prevent="submit">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-4 mt-4">
                                    <div
                                        class="form-group"
                                        :class="{
                                            'has-danger':
                                                errors.columns_virtual_store
                                        }"
                                    >
                                        <el-slider
                                            @change="submit"
                                            v-model="form.columns_virtual_store"
                                            :min="2"
                                            :max="6"
                                        ></el-slider>
                                        <small
                                            class="text-danger"
                                            v-if="errors.columns_virtual_store"
                                            v-text="
                                                errors.columns_virtual_store[0]
                                            "
                                        ></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="form-actions text-end pt-2">
                        <el-button
                            type="primary"
                            native-type="submit"
                            :loading="loading_submit"
                            >Guardar</el-button
                        >
                    </div> -->
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            loading_submit: false,
            // headers: headers_token,
            resource: "ecommerce",
            errors: {},
            form: {},
            soap_sends: [],
            soap_types: []
        };
    },
    async created() {
        await this.initForm();

        await this.$http.get(`/${this.resource}/record`).then(response => {
            if (response.data !== "") {
                let data = response.data.data;
                this.form.id = data.id;
                this.form.columns_virtual_store = data.columns_virtual_store;
            }
        });
    },
    methods: {
        openPaypal() {
         
        },
        initForm() {
            this.errors = {};
            this.form = {
                id: null,
                script_paypal: ""
            };
        },
        submit() {
            this.loading_submit = true;
            this.$http
                .post(`/${this.resource}/configuration_columns`, this.form)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data;
                    } else {
                        console.log(error);
                    }
                })
                .then(() => {
                    this.loading_submit = false;
                });
        }
    }
};
</script>

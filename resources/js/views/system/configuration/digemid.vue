<template>
    <div class="card">
        <div class="card-header">
            <h3 class="my-0">Actualizar cátalogo de DIGEMID</h3>
        </div>
        <div class="card-body">
            <form autocomplete="off" @submit.prevent="submit">
                <div class="form-body">
                    <div class="col-md-12">
                        <div
                            class="form-group"
                            :class="{ 'has-danger': errors.file }"
                        >
                            <input class="d-none" type="file" ref="file" 
                            @change="form.file = $event.target.files[0]"
                            />
                            <el-button
                                type="primary"
                                @click="$refs.file.click()"
                            >
                                <i class="fa fa-upload"></i> Seleccionar
                            </el-button>
                            <br>
                            <span class="form-control-feedback">{{
                                form.file ? form.file.name : ""
                            }}</span>
                        </div>
                    </div>
                </div>
                <div class="form-actions text-end pt-2">
                    <el-button
                        type="primary"
                        native-type="submit"
                        :loading="loading_submit"
                        >{{
                            loading_submit
                                ? "Cargando..."
                                : "Actualizar catálogo"
                        
                        }}</el-button
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
            loading_submit: false,
            resource: "configurations",
            errors: {},
            form: {},
        };
    },
    async created() {
        await this.initForm();
    },
    methods: {
        initForm() {
            this.errors = {};
            this.form = {
                file: null,
            };
        },
        submit() {
            this.loading_submit = true;
            let { file } = this.form;
            this.form = new FormData();
            this.form.append("file", file);
            if (!file){
                this.$message.error("Debe seleccionar un archivo");
                this.loading_submit = false;
                return;
            }
            this.$http
                .post(`/${this.resource}/digemid`, this.form)
                .then((response) => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                    } else {
                        this.$message.error(response.data.message);
                    }
                    //clean form file
                    this.initForm();
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
    },
};
</script>

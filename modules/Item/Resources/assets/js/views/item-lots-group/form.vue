<template>
    <el-dialog
        :title="titleDialog"
        :visible="showDialog"
        @close="close"
        @open="create"
    >
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div
                            class="form-group"
                            :class="{ 'has-danger': errors.code }"
                        >
                            <label class="control-label">Lote</label>
                            <el-input v-model="form.code"></el-input>
                            <small
                                class="text-danger"
                                v-if="errors.code"
                                v-text="errors.code[0]"
                            ></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div
                            class="form-group"
                            :class="{ 'has-danger': errors.code }"
                        >
                            <label class="control-label"
                                >Fecha de vencimiento</label
                            >
                            <el-date-picker
                                v-model="form.date_of_due"
                            ></el-date-picker>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div
                            class="form-group"
                            :class="{ 'has-danger': errors.code }"
                        >
                            <label class="control-label">Estado</label>
                            <el-select v-model="form.state_id">
                                <el-option
                                    v-for="(dt, idx) in states"
                                    :key="idx"
                                    :value="dt.id"
                                    :label="dt.description"
                                ></el-option>
                            </el-select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-end mt-3">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button
                    type="primary"
                    native-type="submit"
                    :loading="loading_submit"
                    >Guardar</el-button
                >
            </div>
        </form>
    </el-dialog>
</template>

<script>
export default {
    props: ["showDialog", "recordId", "states"],
    data() {
        return {
            loading_submit: false,
            titleDialog: null,
            resource: "item-lots-group",
            errors: {},
            form: {}
        };
    },
    created() {
        this.initForm();
    },
    methods: {
        initForm() {
            this.errors = {};

            this.form = {
                id: null,
                code: null
            };
        },
        create() {
            this.titleDialog = this.recordId ? "Editar lote" : "Nueva lote";
            if (this.recordId) {
                this.$http
                    .get(`/${this.resource}/record/${this.recordId}`)
                    .then(response => {
                        this.form = response.data;
                    });
            }
        },
        submit() {
            this.loading_submit = true;
            this.$http
                .post(`${this.resource}`, this.form)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                        this.$eventHub.$emit("reloadData");
                        this.close();
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data;
                    } else {
                        console.log(error.response);
                    }
                })
                .then(() => {
                    this.loading_submit = false;
                });
        },
        close() {
            this.$emit("update:showDialog", false);
            this.initForm();
        }
    }
};
</script>

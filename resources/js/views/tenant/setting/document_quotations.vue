<template>
    <div class="card">
        <div class="card-header">
            <h3 class="my-0">
                Campo Adicional de Cotizaciones
            </h3>
        </div>
        <div class="row card-body" v-loading="loading">
            <div class="col-md-3">
                <label for="sale_note">Campo Adcional de Cotización</label>
                <el-input v-model="form.quotations_optional"></el-input>
            </div>

            <div class="col-md-3">
                <label for="technical_service">Valor del Campo de Cotización</label>
                <el-input v-model="form.quotations_optional_value"></el-input>
            </div>

        </div>
        <span slot="footer" class="dialog-footer d-flex justify-content-end">
            <el-button @click="submit" type="primary">Guardar</el-button>
        </span>
    </div>
</template>

<script>
export default {
    name: "DocumentName",
    data() {
        return {
            loading: false,
            form: {},
            pdf: false,
            typeUser: ""
        };
    },
    created() {
        this.getRecord();
    },
    methods: {
        async submit() {
            try {
                this.loading = true;
                const response = await this.$http.post(
                    `/name_quotations`,
                    this.form
                );
                if (response.status == 200) {
                    this.$message.success(response.data.message);
                    this.getRecord();
                }
            } catch (e) {
                console.log(e);
            } finally {
                this.loading = false;
            }
        },
        async getRecord() {
            try {
                this.loading = true;
                const response = await this.$http.get(`/name_quotations/record`);

                if (response.status == 200) {
                    if (response.data.data) {
                        this.form = response.data.data;
                    } else {
                        this.form = { id: 1 };
                    }
                }
            } catch (e) {
                console.log(e);
            } finally {
                this.loading = false;
            }
        }
    },
    mounted() {}
};
</script>

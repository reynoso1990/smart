<template>
    <div class="card">
        <div class="card-header">
            <h3 class="my-0">
                Nombres opcionales de documentos
            </h3>
        </div>
        <div class="row card-body" v-loading="loading">
            <div class="col-md-3">
                <label for="sale_note">Nota de venta</label>
                <el-input v-model="form.sale_note"></el-input>
            </div>
            <div class="col-md-3">
                <label for="quotation">Cotización</label>
                <el-input v-model="form.quotation"></el-input>
            </div>
            <div class="col-md-3">
                <label for="order_sale">Pedido</label>
                <el-input v-model="form.orden_note"></el-input>
            </div>
            <div class="col-md-3">
                <label for="sale_opportunity">Oportunidad de venta</label>
                <el-input v-model="form.sale_opportunity"></el-input>
            </div>
            <div class="col-md-3">
                <label for="technical_service">Servicio Técnico</label>
                <el-input v-model="form.technical_service"></el-input>
            </div>
            <div class="col-md-3">
                <label for="contract">
                    Contrato
                </label>
                <el-input v-model="form.contract"></el-input>
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
                    `/name_document`,
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
                const response = await this.$http.get(`/name_document/record`);
                console.log(response);
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

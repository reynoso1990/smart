<template>
    <div class="card">
        <div class="card-header">
            <h3 class="my-0">Mostrar Qrs en los documentos</h3>
        </div>
        <div class="row card-body" v-loading="loading">
            <div class="row col-6">
                <div class="col-12">
                    <strong>YAPE</strong>
                </div>
                <div class="col-6">
                    <label for="yape_document" class="w-100"
                        >Facturas/Boletas</label
                    >
                    <el-switch
                        active-text="Sí"
                        inactive-text="No"
                        v-model="form.yape_qr_documents"
                    ></el-switch>
                </div>
                <div class="col-6">
                    <label for="yape_document" class="w-100"
                        >Nota de venta</label
                    >
                    <el-switch
                        active-text="Sí"
                        inactive-text="No"
                        v-model="form.yape_qr_sale_notes"
                    ></el-switch>
                </div>
                <div class="col-6">
                    <label for="yape_document" class="w-100"
                        >Cotizaciones</label
                    >
                    <el-switch
                        active-text="Sí"
                        inactive-text="No"
                        v-model="form.yape_qr_quotations"
                    ></el-switch>
                </div>
            
            </div>
            <div class="row col-6">
                <div class="col-12">
                    <strong>PLIN</strong>
                </div>
                <div class="col-6">
                    <label for="yape_document" class="w-100"
                        >Facturas/Boletas</label
                    >
                    <el-switch
                        active-text="Sí"
                        inactive-text="No"
                        v-model="form.plin_qr_documents"
                    ></el-switch>
                </div>
                <div class="col-6">
                    <label for="yape_document" class="w-100"
                        >Notas de venta</label
                    >
                    <el-switch
                        active-text="Sí"
                        inactive-text="No"
                        v-model="form.plin_qr_sale_notes"
                    ></el-switch>
                </div>
                <div class="col-6">
                    <label for="yape_document" class="w-100"
                        >Cotizaciones</label
                    >
                    <el-switch
                        active-text="Sí"
                        inactive-text="No"
                        v-model="form.plin_qr_quotations"
                    ></el-switch>
                </div>
              
            </div>
        </div>
        <span slot="footer" class="dialog-footer d-flex justify-content-end">
            <el-button @click="submit" type="primary">Guardar</el-button>
        </span>
    </div>
</template>

<script>
export default {
  
    data() {
        return {
            loading: false,
            form: {},
            pdf: false,
            typeUser: "",
        };
    },
    created() {
        this.initForm();
        this.getRecord();
    },
    methods: {
        initForm(){
            this.form = {
                yape_qr_documents: false,
                yape_qr_sale_notes: false,
                yape_qr_quotations: false,
                plin_qr_documents: false,
                plin_qr_sale_notes: false,
                plin_qr_quotations: false,
            }
        },
        async submit() {
            try {
                this.loading = true;
                const response = await this.$http.post(
                    `/configurations`,
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
                const response = await this.$http.get(`/configurations/record`);
                console.log(response);
                if (response.status == 200) {
                    if (response.data.data) {
                        this.form = response.data.data;
                    } else {
                        this.form = {  };
                    }
                }
            } catch (e) {
                console.log(e);
            } finally {
                this.loading = false;
            }
        },
    },
    mounted() {},
};
</script>

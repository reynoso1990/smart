<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @open="create" width="30%" :close-on-click-modal="false"
        :close-on-press-escape="false" :show-close="false">

        <div class="row">

            <template v-if="form.upload_filename">

                <div class="col-lg-6 col-md-6 col-sm-6 text-center font-weight-bold mt-4">
                    <p>Imprimir A4</p>
                    <button type="button" class="btn btn-lg btn-info waves-effect waves-light" @click="clickPrint('a4')">
                        <i class="fa fa-file-alt"></i>
                    </button>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 text-center font-weight-bold mt-4">
                    <p>Descargar Archivo</p>
                    <button type="button" class="btn btn-lg btn-info waves-effect waves-light" @click="clickDownload()">
                        <i class="fa fa-download"></i>
                    </button>
                </div>
            </template>
            <template v-else>

                <div class="col-lg-12 col-md-12 col-sm-12 text-center font-weight-bold mt-4">
                    <p>Imprimir A4</p>
                    <button type="button" class="btn btn-lg btn-info waves-effect waves-light" @click="clickPrint('a4')">
                        <i class="fa fa-file-alt"></i>
                    </button>
                </div>
            </template>
        </div>
        <div class="row mt-3">
            <div class="col-md-12 mt-2">
                <el-input v-model="form.customer_telephone">
                    <template slot="prepend">+51</template>
                    <el-button slot="append" @click="clickSendWhatsapp" :loading="loading_Whatsapp">Enviar PDF
                        <i class="fab fa-whatsapp"></i>
                    </el-button>
                </el-input>
                <small v-if="errors.customer_telephone" class="text-danger"
                    v-text="errors.customer_telephone[0]"></small>
            </div>
            <div class="col-md-12 mt-2">
                <el-input v-model="form.customer_telephone" placeholder="Enviar link por WhatsApp">
                    <template slot="prepend">+51</template>
                    <el-button slot="append" @click="clickSendWhatsapp2">Enviar URL
                        <el-tooltip class="item" content="Se recomienta tener abierta la sesión de Whatsapp web"
                            effect="dark" placement="top-start">
                            <i class="fab fa-whatsapp"></i>
                        </el-tooltip>
                    </el-button>
                </el-input>
                <small v-if="errors.customer_telephone" class="text-danger"
                    v-text="errors.customer_telephone[0]"></small>
            </div>
        </div>
        <span slot="footer" class="dialog-footer row">
            <div class="col-md-7">
                <el-input v-model="form.customer_email">
                    <el-button slot="append" icon="el-icon-message" @click="clickSendEmail"
                        :loading="loading">Enviar</el-button>
                </el-input>
            </div>
            <div class="col-md-5">
                <template v-if="showClose">
                    <el-button @click="clickClose">Cerrar</el-button>
                </template>
                <template v-else>
                    <el-button @click="clickFinalize">Ir al listado</el-button>
                    <el-button type="primary" @click="clickNewDocument">{{ button_text }}</el-button>
                </template>
            </div>
        </span>
    </el-dialog>
</template>

<script>

export default {
    props: ['showDialog', 'recordId', 'showClose', 'type', 'isUpdate'],
    data() {
        return {
            titleDialog: null,
            loading: false,
            resource: 'purchase-orders',
            button_text: 'Nueva OC',
            errors: {},
            form: {},
            loading_Whatsapp:false,
        }
    },
    created() {
        this.initForm()
    },
    methods: {
        clickPrint(format) {
            window.open(`/${this.resource}/print/${this.form.external_id}/${format}`, '_blank');
        },
        clickDownload() {
            window.open(`/${this.resource}/download-attached/${this.form.external_id}`, '_blank');
        },
        initForm() {
            this.errors = {}
            this.form = {
                id: null,
                external_id: null,
                number: null,
                customer_email: null,
                upload_filename: null,
                download_pdf: null
            }
        },
        clickSendWhatsapp2() {
            if (!this.form.customer_telephone) {
                return this.$message.error('El número es obligatorio')
            }
            window.open(`https://wa.me/51${this.form.customer_telephone}?text=${this.form.message_text}`, '_blank');

        },

        clickSendWhatsapp() {
            if (!this.form.customer_telephone) {
                return this.$message.error('El número es obligatorio')
            }
            this.loading_Whatsapp = true
            let form = {
                id: this.recordId,
                type_id : "OC",
                customer_telephone: this.form.customer_telephone,
                mensaje: "Su comprobante de pago electrónico " + this.form.number + " ha sido generado correctamente"
            }
            this.$http.post(`/whatsapp`, form)
                .then(response => {
                    if (response.data.success == true) {
                        this.$message.success(response.data.message)
                        this.loading_Whatsapp = false
                    }else{
                        this.$message.error(response.data.message)
                        this.loading_Whatsapp = false
                    }
            }).catch(error => {
                    this.loading_Whatsapp = false
                    if (error.response.status === 422) {
                        this.$message.error(error.response.data.message);
                    } else {
                        this.$message.error(error.response.data.message);
                    }
                 })
            .finally(() => {
                this.loading_Whatsapp = false
                this.$message.error(error.response.data.message);
            });
        },
        create() {
            this.$http.get(`/${this.resource}/record/${this.recordId}`)
                .then(response => {
                    this.form = response.data.data
                    let typei = this.type == 'edit' ? 'editada' : 'registrada'
                    this.titleDialog = `Orden de Compra ${typei}: ` + this.form.number_full
                })
            this.button_text = this.isUpdate ? 'Continuar' : 'Nueva OC'
        },

        clickFinalize() {
            location.href = `/${this.resource}`
        },
        clickNewDocument() {
            this.clickClose()
        },
        clickClose() {
            this.$emit('update:showDialog', false)
            this.initForm()
        },

        clickSendEmail() {
            this.loading = true
            this.$http.post(`/${this.resource}/email`, {
                customer_email: this.form.customer_email,
                id: this.form.id
            })
                .then(response => {
                    if (response.data.success) {
                        this.$message.success('El correo fue enviado satisfactoriamente')
                    } else {
                        this.$message.error('Error al enviar el correo')
                    }
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors
                    } else {
                        this.$message.error(error.response.data.message)
                    }
                })
                .then(() => {
                    this.loading = false

                })
        },
    }
}
</script>

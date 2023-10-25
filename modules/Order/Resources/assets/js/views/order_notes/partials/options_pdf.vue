<template>
    <div>
        <el-dialog :title="titleDialog" :visible="showDialog" @open="create" width="30%"
                >
            <div class="row">
                <div class="col text-center font-weight-bold">
                    <p>Imprimir A4</p>
                    <button type="button" class="btn btn-lg btn-info waves-effect waves-light" @click="clickToPrint('a4')">
                        <i class="fa fa-print"></i>
                    </button>
                </div>
                <div class="col text-center font-weight-bold">
                    <p>Imprimir Ticket</p>
                    <button type="button" class="btn btn-lg btn-info waves-effect waves-light" @click="clickToPrint('ticket')">
                        <i class="fa fa-print"></i>
                    </button>
                </div>
                <div class="col text-center font-weight-bold" v-if="configuration.ticket_58">
                    <p>Imprimir Ticket 58MM</p>
                    <button type="button" class="btn btn-lg btn-info waves-effect waves-light" @click="clickToPrint('ticket_58')"
                    >
                      <i class="fa fa-print"></i>
                    </button>
                </div>
                <div class="col text-center font-weight-bold">
                    <p>Imprimir A5</p>
                    <button type="button" class="btn btn-lg btn-info waves-effect waves-light" @click="clickToPrint('a5')">
                        <i class="fa fa-print"></i>
                    </button>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 text-center font-weight-bold">
                    <p>Descargar A4</p>
                    <button type="button" class="btn btn-lg btn-info waves-effect waves-light" @click="clickDownload('a4')">
                        <i class="fa fa-download"></i>
                    </button>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 text-center font-weight-bold">
                    <p>Descargar Ticket</p>
                    <button type="button" class="btn btn-lg btn-info waves-effect waves-light" @click="clickDownload('ticket')">
                        <i class="fa fa-download"></i>
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-2">
                            <el-input v-model="form.customer_telephone">
                                <template slot="prepend">+51</template>
                                <el-button slot="append"
                                           @click="clickSendWhatsapp" :loading="loading_whatsapp">Enviar PDF
                                            <i class="fab fa-whatsapp"></i>
                                </el-button>
                            </el-input>
                            <small v-if="errors.customer_telephone"
                                   class="text-danger"
                                   v-text="errors.customer_telephone[0]"></small>
                        </div>
                        <div class="col-md-12 mt-2">
                            <el-input v-model="form.customer_telephone" placeholder="Enviar link por WhatsApp">
                                <template slot="prepend">+51</template>
                                <el-button slot="append"
                                           @click="clickSendWhatsapp2">Enviar URL
                                    <el-tooltip class="item"
                                                content="Se recomienta tener abierta la sesión de Whatsapp web"
                                                effect="dark"
                                                placement="top-start">
                                        <i class="fab fa-whatsapp"></i>
                                    </el-tooltip>
                                </el-button>
                            </el-input>
                            <small v-if="errors.customer_telephone"
                                   class="text-danger"
                                   v-text="errors.customer_telephone[0]"></small>
                        </div>
            </div>

            <!-- <span slot="footer" class="dialog-footer row"> -->
            <span slot="footer" class="dialog-footer">
                <!-- <div class="col-md-6">
                    <el-input v-model="form.customer_email">
                        <el-button slot="append" icon="el-icon-message"   @click="clickSendEmail" :loading="loading">Enviar</el-button>
                    </el-input>
                </div> -->
                <!-- <div class="col-md-6">  -->
                    <el-button @click="clickClose">Cerrar</el-button>
                <!-- </div> -->

            </span>
        </el-dialog>


    </div>
</template>

<script>


    export default {

        props: ['showDialog', 'recordId', 'showClose','configuration'],
        data() {
            return {
                titleDialog: null,
                resource: 'order-notes',
                form: {},
                loading: false,
                loading_whatsapp:false,
                errors:{}
            }
        },
        created() {
            this.initForm()
        },
        methods: {
            initForm() {
                this.form = {
                    id: null,
                    external_id: null,
                     customer_telephone: null,

                }
            },
            create() {
                this.$http.get(`/${this.resource}/record/${this.recordId}`)
                    .then(response => {
                        this.form = response.data.data
                        this.titleDialog = `Pedido registrado: ${this.form.identifier}`
                    })
            },
            clickClose() {
                this.$emit('update:showDialog', false)
                this.initForm()
            },
            clickToPrint(format){
                window.open(`/${this.resource}/print/${this.form.external_id}/${format}`, '_blank');
            } ,
            clickDownload(format){
                window.open(`/${this.resource}/download/${this.form.external_id}/${format}`, '_blank');
            } ,
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
                this.loading_whatsapp = true
                let form = {
                    id: this.recordId,
                    type_id: "PD",
                    customer_telephone: this.form.customer_telephone,
                    mensaje: "Pedido  N° " + this.form.identifier + " ha sido generado correctamente"
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
            clickSendEmail()
            {
                this.loading = true
                this.$http.post(`/${this.resource}/email`, {
                    customer_email: this.customer_email,
                    id: this.form.id,
                    customer_id: this.form.order_note.customer_id
                })
                .then(response => {
                    if (response.data.success) {
                        this.$message.success('El correo fue enviado satisfactoriamente')
                    } else {
                        this.$message.error('Error al enviar el correo')
                    }
                })
                .catch(error => {
                    this.$message.error('Error al enviar el correo')
                })
                .then(() => {
                    this.loading = false
                })
            }
        }
    }
</script>

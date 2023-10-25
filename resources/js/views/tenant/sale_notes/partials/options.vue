<template>
    <div>
        <el-dialog :title="titleDialog" :visible="showDialog" @open="create"
                :close-on-click-modal="false"
                :close-on-press-escape="false"
                append-to-body
                width="800px"
                :show-close="false">

            <div class="row justify-content-md-center">
                <div class="col-lg-12 col-md-12 col-sm-12 container-tabs">
                    <el-tabs v-model="activeName">
                        <template v-if="configuration && configuration.package_handlers">
                                 <el-tab-pane label="Imprimir recibo" name="first">
                            <iframe :src="form.print_receipt" type="application/pdf" width="100%" height="400px"/>
                        </el-tab-pane>
                        </template>
                        <template v-else>
                              <el-tab-pane label="Imprimir A4" name="first">
                            <iframe :src="form.print_a4" type="application/pdf" width="100%" height="400px"/>
                        </el-tab-pane>
                        <el-tab-pane label="Imprimir Ticket" name="fourth" v-if="ShowTicket80">
                            <iframe :src="form.print_ticket" type="application/pdf" width="100%" height="400px"/>
                        </el-tab-pane>
                        <el-tab-pane label="Imprimir Ticket 58MM" name="third" v-if="ShowTicket58">
                            <iframe :src="form.print_ticket_58" type="application/pdf" width="100%" height="400px"/>
                        </el-tab-pane>
                        <el-tab-pane label="Imprimir Ticket 50MM" name="fifth" v-if="ShowTicket50">
                            <iframe :src="form.print_ticket_50" type="application/pdf" width="100%" height="400px"/>
                        </el-tab-pane>
                        <el-tab-pane label="Imprimir A5" name="second">
                            <iframe :src="form.print_a5" type="application/pdf" width="100%" height="400px"/>
                        </el-tab-pane>
                        </template>
                    </el-tabs>

                </div>
               <template v-if="configuration && configuration.package_handlers">
                 <div class="col-md-2 text-center font-weight-bold mt-3">
                    <button class="btn btn-info"
                            type="button"
                            @click="clickPrint('receipt')">
                        <i class="fa fa-receipt"></i>
                    </button>
                    <p>80MM</p>
                </div>
               </template>
               <template v-else>
                 <div class="col-md-2 text-center font-weight-bold mt-3">
                    <button class="btn btn-info"
                                   type="button"
                                   @click="clickPrint('a4')">
                            <i class="fa fa-file-alt"></i>
                    </button>
                    <p>A4</p>
                </div>
                <div class="col-md-2 text-center font-weight-bold mt-3">
                    <button class="btn btn-info"
                            type="button"
                            @click="clickPrint('ticket')">
                        <i class="fa fa-receipt"></i>
                    </button>
                    <p>80MM</p>
                </div>

                <div class="col-md-2 text-center font-weight-bold mt-3">
                    <button class="btn btn-info"
                            type="button"
                            @click="clickPrint('ticket_58')">
                        <i class="fa fa-receipt"></i>
                    </button>
                    <p>58MM</p>
                </div>

                <div class="col-md-2 text-center font-weight-bold mt-3">
                        <button class="btn btn-info"
                                type="button"
                                @click="clickPrint('ticket_50')">
                            <i class="fa fa-file-alt"></i>
                        </button>
                        <p>50MM</p>                  
                </div>

                <div class="col-md-2 text-center font-weight-bold mt-3">

                    <button class="btn btn-info waves-effect waves-light"
                            type="button"
                            @click="clickPrint('a5')">
                        <i class="fa fa-file-alt"></i>
                    </button>
                    <p>A5</p>
                </div>
               </template>
<!--                     
                    <a :href="`https://docs.google.com/viewer?url=${form.print_a4}?format=pdf`" class="btn mx-3 btn-primary btn-lg" target="_BLANK">
                        <i class="far fa-file-pdf"></i>
                        <br>
                        <span>PDF A4</span>
                    </a>
                    <a :href="`https://docs.google.com/viewer?url=${form.print_a5}?format=pdf`" class="btn btn-primary mx-3 btn-lg" target="_BLANK">
                        <i class="far fa-file-pdf"></i>
                        <br>
                        <span>PDF A5</span>
                    </a>
                    <a :href="`https://docs.google.com/viewer?url=${form.print_ticket_58}?format=pdf`" class="btn mx-3 btn-primary btn-lg" target="_BLANK">
                        <i class="far fa-file-pdf"></i>
                        <br>
                        <span>PDF TICKET 58MM</span>
                    </a>
                    <a :href="`https://docs.google.com/viewer?url=${form.print_ticket_50}?format=pdf`" class="btn mx-3 btn-primary btn-lg" target="_BLANK">
                        <i class="far fa-file-pdf"></i>
                        <br>
                        <span>PDF TICKET 50MM</span>
                    </a>
                    <a :href="`https://docs.google.com/viewer?url=${form.print_ticket}?format=pdf`" class="btn mx-3 btn-primary btn-lg" target="_BLANK">
                        <i class="far fa-file-pdf"></i>
                        <br>
                        <span>PDF TICKET</span>
                    </a> -->
            </div>
            <span slot="footer" class="dialog-footer row">
                <div class="col-md-12 mt-2">
                    <el-input v-model="form.customer_email">
                        <el-button slot="append" icon="el-icon-message"   @click="clickSendEmail" :loading="loading">Enviar</el-button>
                    </el-input>
                </div>
                <div class="col-md-12 mt-2">
                        <el-input v-model="form.customer_telephone">
                            <template slot="prepend">+51</template>
                            <el-button slot="append"
                                       @click="clickSendWhatsapp" :loading="loading_Whatsapp">Enviar PDF
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
            <div class="col-md-6 mt-3">&nbsp;</div>
                <div class="col-md-6 mt-3">
                <template v-if="showClose">
                    <el-button @click="clickClose">Cerrar</el-button>
                </template>
                <template v-else>
                    <el-button @click="clickFinalize">Ir al listado</el-button>
                     <el-popover
                        :open-delay="1000"
                         placement="top-start"
                         width="145"
                         trigger="hover"
                         content="Presiona ALT + N">
                            <el-button slot="reference"
                                       type="primary"
                                       ref="new_note"
                                       @click="clickNewSaleNote"
                            >
                                Nueva nota de venta
                            </el-button>
                        </el-popover>
                </template>
                </div>
            </span>
        </el-dialog>

    </div>
</template>

<script>
export default {
    props: ['showDialog', 'recordId', 'showClose','configuration'],
    data() {
        return {
            serviceUrl:"https://ej2services.syncfusion.com/production/web-services/api/pdfviewer",
            titleDialog: null,
            loading: false,
            resource: 'sale-notes',
            resource_documents: 'documents',
            errors: {},
            form: {},
            document:{},
            document_types: [],
            all_series: [],
            series: [],
            loading_submit:false,
            showDialogOptions: false,
            documentNewId: null,
            activeName: 'first',
            isSafari: false,
            loading_Whatsapp:false
        }
    },
    created() {
        this.initForm()
    },
    mounted() {
        if(navigator.userAgent.indexOf("Safari") != -1) {
            this.isSafari = true
        }
    },
    computed: {
        ShowTicket58: function () {
            if (this.configuration === undefined) return false;
            if (this.configuration == null) return false;
            if (this.configuration.show_ticket_58 === undefined) return false;
            if (this.configuration.show_ticket_58 == null) return false;
            if (
                this.configuration.show_ticket_58 !== undefined &&
                this.configuration.show_ticket_58 !== null) {
                return this.configuration.show_ticket_58;
            }
            return false;
        },
        ShowTicket80: function () {
            if (this.configuration === undefined) return false;
            if (this.configuration == null) return false;
            if (this.configuration.show_ticket_80 === undefined) return false;
            if (this.configuration.show_ticket_80 == null) return false;
            if (
                this.configuration.show_ticket_80 !== undefined &&
                this.configuration.show_ticket_80 !== null) {
                return this.configuration.show_ticket_80;
            }
            return false;
        },
        ShowTicket50: function () {
            if (this.configuration === undefined) return false;
            if (this.configuration == null) return false;
            if (this.configuration.show_ticket_50 === undefined) return false;
            if (this.configuration.show_ticket_50 == null) return false;
            if (
                this.configuration.show_ticket_50 !== undefined &&
                this.configuration.show_ticket_50 !== null) {
                return this.configuration.show_ticket_50;
            }
            return false;
        }
    },
    methods: {
        initForm() {
            this.errors = {}
            this.form = {
                id: null,
                customer_telephone:null,
                external_id: null,
                identifier: null,
                date_of_issue:null,
                print_ticket: null,
                print_ticket_58: null,
                print_a4: null,
                print_a5: null,
                series:null,
                number:null,
            }
        },
        create() {
            this.$http.get(`/${this.resource}/record/${this.recordId}`)
                .then(response => {
                    this.form = response.data.data
                    this.titleDialog = `Nota de venta registrada:  ${this.form.serie}-${this.form.number}`
                })
        },
        clickFinalize() {
            location.href = `/${this.resource}`
        },
        clickNewSaleNote() {
            this.clickClose()
        },
        clickClose() {
            this.$emit('update:showDialog', false)
            this.initForm()
        },
        clickDownload(){
            window.open(`/downloads/saleNote/sale_note/${this.form.external_id}`, '_blank');
        },
        clickToPrint(format){
            window.open(`/${this.resource}/print/${this.form.id}/${format}`, '_blank');
        },
        clickSendEmail() {
            this.loading=true
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
                    this.loading=false

                })
        },
        clickSendWhatsapp2() {
            if (!this.form.customer_telephone) {
                return this.$message.error('El número es obligatorio')
            }
            window.open(`https://wa.me/51${this.form.customer_telephone}?text=${this.form.message_text}`, '_blank');

        },
        clickPrint(format) {
            if(format== 'receipt'){

                window.open(`/sale-notes/receipt/${this.form.id}`, '_blank');
            }else{

                window.open(`/sale-notes/print/${this.form.external_id}/${format}`, '_blank');
            }
        },

        clickSendWhatsapp() {
            if (!this.form.customer_telephone) {
                return this.$message.error('El número es obligatorio')
            }
            
            this.loading_Whatsapp = true
            let form = {
                id: this.recordId,
                type_id : "NV",
                customer_telephone: this.form.customer_telephone,
                mensaje: "La Nota de venta N° " + this.form.serie + this.form.number + " ha sido generado correctamente"
            }
            this.$http.post(`/whatsapp`, form)
            .then(response => {
                    if (response.data.success == true) {
                        this.$message.success(response.data.message)
                        this.loading_Whatsapp = false
                    }
                }).catch(error => {
                    this.loading_Whatsapp = false
                    this.$message.error(error.response.data.message);
                 })
            .finally(() => {
                this.loading_Whatsapp = false
                this.$message.error(error.response.data.message);
            });
        },
    }
}
</script>

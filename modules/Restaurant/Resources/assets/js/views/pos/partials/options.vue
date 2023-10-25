<template>
    <el-dialog   :visible="showDialog"  @open="create" width="60%" append-to-body top="7vh" :close-on-click-modal="false" :close-on-press-escape="false" :show-close="false">
        <span slot="title">
            <div class="widget-summary widget-summary-xs pl-3 p-2">

                <div class="widget-summary-col">
                    <div class="summary row">
                        <div class="col-md-6">
                            <h4 class="title">Venta exitosa : comprobante {{form.number}}</h4>
                        </div>
                        <div class="col-md-6">
                            <h4 class="title">Estado de comprobante: {{ (statusDocument.sent) ? 'Enviado a Sunat':'No enviado a Sunat'}}</h4>
                            <h4 class="title">Envio automático: {{ (configuration.send_auto) ? 'Activado':'Desactivado'}}</h4>

                        </div>
                    </div>
                </div>
            </div>
        </span>
        <div class="form-body el-dialog__body_custom">
            <div class="row">
                <div class="col-md-12 m-bottom">
                    <el-tabs v-model="activeName"  >
                        <el-tab-pane label="Imprimir Ticket" name="first">
                            <embed :src="form.print_ticket" type="application/pdf" width="100%" height="450px"/>
                        </el-tab-pane>
                        <el-tab-pane label="Imprimir A4" name="second">
                            <embed :src="form.print_a4" type="application/pdf" width="100%" height="450px"/>
                        </el-tab-pane>
                        <el-tab-pane label="Imprimir A5" name="third">
                            <embed :src="form.print_a5" type="application/pdf" width="100%" height="450px"/>
                        </el-tab-pane>
                    </el-tabs>
                </div>
                <div class="row col-md-12">
                    <div class="col-md-6">
                        <el-input v-model="form.customer_email">
                            <el-button slot="append" icon="el-icon-message"   @click="clickSendEmail" :loading="loading">Enviar</el-button>
                        </el-input>
                        <!-- <small class="text-danger" v-if="errors.customer_email" v-text="errors.customer_email[0]"></small> -->

                    </div>
                    <!-- <div class="col-md-1">
                    </div> -->
                    <div class="col-md-6">
                        <el-button  type="primary"  class="float-right" @click="clickNewSale">Nueva venta</el-button>
                    </div>
                </div>

            </div>
        </div>
    </el-dialog>
</template>

<script>
    export default {
        props: ['showDialog', 'recordId', 'statusDocument','resource','userId'],
        data() {
            return {
                titleDialog: null,
                loading: false,
                errors: {},
                form: {},
                company: {},
                configuration: {},
                activeName: 'first',

            }
        },
        async created() {
            this.initForm()
        },
        mounted() {
             qz.security.setCertificatePromise((resolve, reject) => {
                this.$http.get('/api/qz/crt/override', {
                    responseType: 'text'
                }).then(response => {
                    resolve(response.data);
                }).catch(error => {
                    reject(error.data);
                });
            });
            /*  qz.security.setCertificatePromise((resolve, reject) => {
                this.$http.get('/api/qz/crt/override', {
                    responseType: 'text'
                }).then(response => {
                    resolve(response.data);
                }).catch(error => {
                    reject(error.data);
                });
            });
            qz.security.setSignaturePromise((toSign) => {
                return (resolve, reject) => {
                    this.$http.post('/api/qz/signing', {request: toSign})
                        .then(response => {
                            resolve(response.data);
                        }).catch(error => {
                        reject(error.data);
                    });
                };
            }); */
        },
        methods: {
            clickNewSale(){
                this.initForm()
                this.$eventHub.$emit('cancelSale')

            },
            initForm() {
                this.errors = {};
                this.configuration = {};
                this.form = {
                    customer_email: null,
                    download_pdf: null,
                    print_a4: null,
                    print_a5: null,
                    print_ticket: null,
                    external_id: null,
                    number: null,
                    id: null,
                    print_ticket_base64: null,
                    format_printer:null,
                    printer:null,
                    direct_printing:null
                }
            },
            async create() {
                await this.$http.get(`/${this.resource}/record/${this.recordId}`).then(response => {
                    this.form = response.data.data;
                     this.titleDialog = 'Comprobante: '+this.form.number;
                });

                await  this.$http.get(`/pos/status_configuration`).then(response => {
                    this.configuration = response.data
                });

                if(this.form.direct_printing=="1"){

                    let formatoPdf=""
                    if(this.form.format_printer="1"){
                         formatoPdf=this.form.print_a4
                    }else if(this.form.format_printer="2"){
                           formatoPdf=this.form.print_a5
                    }else if(this.form.format_printer="3"){
                           formatoPdf=this.form.print_ticket
                    }else if(this.form.format_printer="4"){
                           formatoPdf=this.form.print_ticket
                    }
                    await this.clickPrintPos(this.form.printer,this.form.print_ticket);
                }

            },
            clickSendEmail() {

                if(this.form.customer_email == null || this.form.customer_email == '') return this.$message.error('Ingrese el correo')
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
            async clickPrintPos(printerName,formatoPdf) {
                try {
                    let config = qz.configs.create(printerName, {legacy: true});
                    if (!qz.websocket.isActive()) {
                        await qz.websocket.connect(config);
                    }
                    let data = [
                        {
                            type: 'pdf',
                            format: 'file',
                            data: formatoPdf
                        }
                    ];
                    qz.print(config, data).catch((e) => {
                        this.$message.error(e.message);
                    });
                //       qz.print(config, data).catch((e) => {
               //         this.$message.error(e.message);
               //     });
                 //   this.clickNewSale();
                } catch (e) {
                    this.$message.error(e.message);
                }
            }
            // clickConsultCdr(document_id) {
            //     this.$http.get(`/${this.resource}/consult_cdr/${document_id}`)
            //         .then(response => {
            //             if (response.data.success) {
            //                 this.$message.success(response.data.message)
            //                 this.$eventHub.$emit('reloadData')
            //             } else {
            //                 this.$message.error(response.data.message)
            //             }
            //         })
            //         .catch(error => {
            //             this.$message.error(error.response.data.message)
            //         })
            // },
            // clickFinalize() {
            //     location.href = (this.isContingency) ? `/contingencies` : `/${this.resource}`
            // },
            // clickNewDocument() {
            //     this.clickClose()
            // },
            // clickClose() {
            //     this.$emit('update:showDialog', false)
            //     this.initForm()
            // },
        }
    }
</script>

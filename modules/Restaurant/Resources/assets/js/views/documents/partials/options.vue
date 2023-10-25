<template>

    <el-dialog  v-loading="loading_print" :element-loading-text="message" :title="titleDialog" :visible="showDialog" @open="create" :close-on-click-modal="false" :close-on-press-escape="false" :show-close="false" append-to-body>
         <div class="row mb-4" v-if="form.response_message">
            <div class="col-md-12">
                <el-alert
                    :title="form.response_message"
                    :type="form.response_type"
                    show-icon>
                </el-alert>
            </div>
        </div>
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 text-center font-weight-bold" v-if="!locked_emission.success">
                <el-alert    :title="locked_emission.message"    type="warning"    show-icon>  </el-alert>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-12 text-center font-weight-bold mt-3">
                <button type="button" class="btn btn-lg btn-info waves-effect waves-light" @click="clickPrint('a4')">
                    <i class="fa fa-file-alt"></i>
                </button>
                <p>Imprimir A4</p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 text-center font-weight-bold mt-3">
                <button type="button" class="btn btn-lg btn-info waves-effect waves-light" @click="clickPrint('a5')">
                    <i class="fa fa-receipt"></i>
                </button>
                <p>Imprimir A5</p>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 text-center font-weight-bold mt-3">

                <button type="button" class="btn btn-lg btn-info waves-effect waves-light" @click="clickPrint('ticket')">
                    <i class="fa fa-receipt"></i>
                </button>
                 <p>Imprimir Ticket 80MM</p>
            </div>

             <div class="col-lg-3 col-md-3 col-sm-12 text-center font-weight-bold mt-3">

                <button type="button" class="btn btn-lg btn-info waves-effect waves-light" @click="clickPrint('ticket_50')">
                    <i class="fa fa-receipt"></i>
                </button>
                <p>Imprimir Ticket 50MM</p>
            </div>


            <div class="col-lg-12 col-md-12 col-sm-12 text-center font-weight-bold mt-3" v-if="form.image_detraction">
                <a :href="`${this.form.image_detraction}`" download class="text-center font-weight-bold ">Descargar constancia de pago - detracción</a>
            </div>
        </div>
        <!-- <div class="row mt-4">
            <div class="col-lg-6 col-md-6 col-sm-12 text-center">
                <button type="button" class="btn btn-lg waves-effect waves-light btn-outline-secondary" @click="clickDownload('a4')">
                    <i class="fa fa-download"></i>&nbsp;&nbsp;Descargar A4
                </button>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 text-center">
                <button type="button" class="btn btn-lg waves-effect waves-light btn-outline-secondary" @click="clickDownload('ticket')">
                    <i class="fa fa-download"></i>&nbsp;&nbsp;Descargar Ticket
                </button>
            </div>
        </div> -->
        <div class="row mt-3">
            <div class="col-md-12">
                <el-input v-model="form.customer_email">
                    <el-button slot="append" icon="el-icon-message" @click="clickSendEmail" :loading="loading">Enviar</el-button>
                 <i slot="prefix" class="el-icon-edit-outline"></i></el-input>
                <small class="text-danger" v-if="errors.customer_email" v-text="errors.customer_email[0]"></small>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <el-input v-model="form.customer_telephone">
                    <template slot="prepend">+51</template>
                        <el-button slot="append" @click="clickSendWhatsapp" >Enviar
                            <el-tooltip class="item" effect="dark"  content="Es necesario tener aperturado Whatsapp web" placement="top-start">
                                <i class="fab fa-whatsapp" ></i>
                            </el-tooltip>
                        </el-button>
                 <i slot="prefix" class="el-icon-edit-outline"></i></el-input>
                <small class="text-danger" v-if="errors.customer_telephone" v-text="errors.customer_telephone[0]"></small>
            </div>
        </div>
        <!-- <div class="row m-t-10" >
            <div class="col-md-12 text-center">
                <button type="button" class="btn waves-effect waves-light btn-outline-primary"
                        @click.prevent="clickConsultCdr(form.id)">Consultar CDR</button>
            </div>
        </div> -->
         <span slot="footer" class="dialog-footer">
            <template v-if="showClose">
                <el-button @click="clickClose">Cerrar</el-button>
            </template>
            <template v-else>
                <el-button class="list" @click="clickFinalize">Ir al listado</el-button>
                <el-button type="primary" @click="clickNewDocument">Nuevo comprobante</el-button>
            </template>
        </span>
    </el-dialog>
</template>

<script>
    export default {
        props: ['showDialog', 'recordId',"configuration" ,'showClose','isContingency','generatDispatch','dispatchId','editDocument','print'],
        data() {
            return {
                titleDialog: null,
                loading: false,
                resource: 'documents',
                errors: {},
                form: {},
                company: {},
                locked_emission:{},
                loading_print:false,
                message:""
            }
        },
        async created() {
            this.initForm()
            await this.$http.get(`/companies/record`)
                .then(response => {
                    if (response.data !== '') {
                        this.company = response.data.data
                    }
                })
        },
        mounted() {

        },
        methods: {

            clickSendWhatsapp() {

                if(!this.form.customer_telephone){
                    return this.$message.error('El número es obligatorio')
                }

                window.open(`https://wa.me/51${this.form.customer_telephone}?text=${this.form.message_text}`, '_blank');

            },
            initForm() {
                this.errors = {};
                this.form = {
                    customer_email: null,
                    download_pdf: null,
                    external_id: null,
                    number: null,
                    image_detraction: null,
                    id: null,
                    response_message:null,
                    response_type:null,
                    customer_telephone:null,
                    message_text:null
                };
                this.locked_emission = {
                    success: true,
                    message: null
                }
                this.company = {
                    soap_type_id: null,
                }
            },
            async print_pdf(PrinterName, FileLink){
              try {
                let config = qz.configs.create(PrinterName, {scaleContent : false});
                    if (!qz.websocket.isActive()) {
                        await qz.websocket.connect(config);
                    }
                    let data = [
                        {
                            type: 'pdf',
                            format: 'file',
                            data: FileLink
                        }
                    ];
                    qz.print(config, data).catch((e) => {
                        this.$message.error(e.message);
                    });


                } catch (e) {
                    this.$message.error(e.message);
                }
        },
            async create() {
                this.loading_print=true
                this.message="Cargando la información del Comprobante"
                let printer_directo=null
                let printer=null
                let formatoPdf=null
                await this.$http.get(`/${this.resource}/record/${this.recordId}`).then(response => {
                    this.form = response.data.data;
                    this.titleDialog = 'Comprobante: '+this.form.number;
                this.loading_print=false
               // }
                    if(this.generatDispatch) window.open(`/dispatches/create/${this.form.id}/i/${this.dispatchId}`)
                });

                await this.$http.get(`/${this.resource}/locked_emission`).then(response => {
                    this.locked_emission = response.data
                 });

            },
            async clickPrintPos(printerName,formatoPdf) {
                try {
                    this.message="Espere imprimiendo el Comprobante "+this.form.number
                    this.loading_print=true
                    let config = qz.configs.create(printerName, {scaleContent : false},{ jobName:this.form.number });
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

                    this.loading_print=false
                    this.clickClose()
                } catch (e) {
                    this.$message.error(e.message);
                }
            },
            clickPrint(format){
                // if(this.configuration.print_direct==1){
                //     if(format=="a4"){
                //         this.clickPrintPos(this.form.printer,this.form.print_a4)
                //     }
                //     if(format=="a5"){
                //         this.clickPrintPos(this.form.printer,this.form.print_a4)
                //     }
                //     if(format=="ticket"){
                //         this.clickPrintPos(this.form.printer,this.form.ticket)
                //     }
                //     if(format=="ticket_50"){
                //         this.clickPrintPos(this.form.printer,this.form.ticket_50)
                //     }
                //     }else{
                        window.open(`/print/document/${this.form.external_id}/${format}`, '_blank');
                 //   }
                },
                clickDownloadImage() {
                    window.open(`${this.form.image_detraction}`, '_blank');
                },
            clickDownload(format) {
                window.open(`${this.form.download_pdf}/${format}`, '_blank');
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
            clickConsultCdr(document_id) {
                this.$http.get(`/${this.resource}/consultarcdr/${document_id}`)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            this.$eventHub.$emit('reloadData')
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        this.$message.error(error.response.data.message)
                    })
            },
            clickFinalize() {
                location.href = (this.isContingency) ? `/contingencies` : `/${this.resource}`
            },
            clickNewDocument() {
                this.clickClose()
            },
            clickClose() {
                this.$emit('update:showDialog', false)
                if(this.editDocument==true){
                    location.href=`/${this.resource}`
                }
                this.initForm()
            },
        }
    }
</script>

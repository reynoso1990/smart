<template>
     <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight2" aria-labelledby="offcanvasRightLabel">
                        <div class="offcanvas-header">
                          <h5 id="offcanvasRightLabel">
                              <p class="h4 txt-info p-10 txt-primary f-w-700"><i class="icofont icofont-fork-and-knife"></i>
                        <a class="badge badge bg-dark text-white" href="javascript:void(0)">
                            <template v-if="localOrden.length>0 && localOrden.length<=9">
                                0{{localOrden.length}}
                            </template>
                            <template v-else>
                                {{localOrden.length}}
                            </template>
                        </a>
                              Ordenes Pendiente</p>
                          </h5>
                          <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                             <section class="scroll-section" id="checkboxes">
                                  <div class="row align-items-start" v-if="configuration.commands_fisico==true">
                                   <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Nº Comanda</label>
                                        <el-input v-model="commands_fisico" autofocus @focus="clear_command()" @keyup.enter.native="sendOrden()"></el-input>
                                    </div>
                                   </div>
                                  </div>

                                <div class="d-flex justify-content-end mb-2">
                                    <div class="btn-group mr-2" role="group" aria-label="First group">
                                    <button type="button" :disabled="disableSend" class="btn btn-success" @click="sendOrden()" :loading="loading">
                                        <i class="icofont-checked"></i>
                                        <template v-if="disableSend==false">
                                            Enviar Pedido - Cobrar
                                        </template>
                                        <template v-else>
                                            Enviando Pedido
                                        </template>
                                    </button>
                                    <button type="button" class="btn btn-danger"  id="close_offcanvas"  data-bs-dismiss="offcanvas" aria-label="Close"><i class="icofont-checked"></i><i class="icofont-close-line"></i> Cancelar</button>
                                </div>
                                    <!-- <div class="btn-group check-all-container mt-n1" v-if="localOrden.length>0">
                                    <div class="btn btn-sm btn-primary" @click="sendOrden()" :loading="loading">
                                            <i class="icofont-checked"></i>   Enviar Pedido - Cobrar
                                     </div>
                                       <div class="btn btn-sm btn-danger" id="close_offcanvas"  data-bs-dismiss="offcanvas" aria-label="Close" @click="close()">
                                         <i class="icofont-close-line"></i> Cancelar
                                     </div>
                                    </div> -->
                                </div>
                                   <div class="row align-items-start">
                                    <!-- <div class="col-md-6 text-end pt-2 pb-2 font-weight-light">
                                          <label class="control-label w-100">Para llevar</label>
                                            <el-switch v-model="to_carry" active-text="Si" inactive-text="No"></el-switch>
                                    </div> -->

                                    <div class="col-md-6 text-end pt-2 pb-2 font-weight-light">
                                       <h3 class="badge text-success lead-font-weight-700"> Total Pedido: S/. {{total.toFixed(2)}}</h3>
                                    </div>

                                   </div>
                                <div class="scroll-out">
                                    <div class="scroll-by-count os-host os-theme-dark os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-transition" data-count="4" id="checkboxTable"
                                    style="height: calc(100vh - 8rem);">
                                    <div class="os-resize-observer-host observed">
                                        <div class="os-resize-observer" style="left: 0px; right: auto;">
                                        </div>
                                    </div>
                                    <div class="os-size-auto-observer observed" style="height: calc(100% + 1px); float: left;">
                                    <div class="os-resize-observer">
                                    </div>
                                    </div>
                                    <div class="os-content-glue" style="margin: 0px 5px;">
                                    </div>
                                    <div class="os-padding">
                                    <div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll;">
                                    <div class="os-content" style="padding: 0px 5px; height: 100%; width: 100%;">


                                         <div class="col-sm-12 col-md-12 col-lg-12  col-xl-12" v-for="(order_pend,indexx) in localOrden" :key="indexx">
                                         <!--  -->
                                        <div class="coupon rounded d-flex justify-content-between mb-2" id="card">

                                        <div class="tengah py-2 d-flex w-100 justify-content-start  p-2">
                                            <div>
                                                <h3 class="lead font-weight-light">  {{ order_pend.food.description.toUpperCase() }} </h3>
                                                <p class="badge bg-foreground text-uppercase font-weight-light p-0">
                                                    <div class="row align-items-end">
                                                    <div class="col-md-5">
                                                    <span class="text-muted">
                                                        Cantidad<br>
                                                    <div class="input-group spinner" data-trigger="spinner">
                                                        <input type="text" readonly class="form-control text-center" v-model="order_pend.quantity" data-rule="currency" />
                                                        <div class="input-group-text">
                                                            <button type="button" class="spin-up" data-spin="up" @click="sumar_orden(indexx)">
                                                                <span class="arrow"></span>
                                                            </button>
                                                            <button type="button" class="spin-down" data-spin="down" @click="restar_orden(indexx)">
                                                                <span class="arrow"></span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    </span>

                                                    </div>

                                                    <div class="col-md-5">
                                                     <span class="time font-weight-light">
                                                        <span class="text-muted">
                                                                Precio <br>
                                                            <el-input type="number" v-model="order_pend.food.price" @input="update_price(indexx,order_pend.food.price)">
                                                            <template slot="prepend" v-if="order_pend.food.item.currency_type_id=='PEN'">
                                                                S/
                                                            </template>
                                                        <template slot="prepend" v-if="order_pend.food.item.currency_type_id=='USD'">
                                                                $
                                                            </template>
                                                        </el-input>
                                                        </span>
                                                    </span>
                                                    </div>
                                                    <div class="col-md-2 p-0">
                                                        <span class="time font-weight-light">
                                                            <span class="text-muted">
                                                                <el-button class="text-white" type="danger" icon="el-icon-delete" @click="deleteFood(indexx)">
                                                                </el-button>
                                                            </span>
                                                        </span>
                                                    <!-- <el-button-group>
                                                        <el-button class="text-white" type="danger" icon="el-icon-delete" @click="deleteFood(indexx)">
                                                        </el-button>
                                                        <el-button class="text-white" type="info" icon="el-icon-s-order"  @click="openLocalObservationDialog(indexx)">
                                                        </el-button>
                                                    </el-button-group> -->
                                                    </div>
                                                    </div>
                                                </p>
                                            </div>
                                        </div>

                                        </div>
             </div>



                                    </div>
                                </div>
                                </div>
                                    </div>
                                </div>
                                </section>
                        </div>
                     </div>
</template>
<script>
 export default {
    props: ["localOrden", "configuration", "ordens","date_opencash","percentage_igv"],
    created() {},

    data() {
        return {
            pin: "",
            totalOrdenItems: 0.0,
            total: 0.0,
            disableSend:false,
            totalOrden: 0.0,
            loading: false,
            commands_fisico:"",
            AllSelected:false,
            showDialogPing: false,
            dialogLocalObservation: false,
            currentLocalOrden: null,
            localObservation: null,
            dialogObservation: false,
            observation: null,
            loadingObservation: false,
            currentOrden: null,
            form_ped: {},
            to_carry:false,
        };
    },
    watch: {
        ordens(newOrdens, _) {
            this.calculateTotal(newOrdens);
        },
    },
    mounted() {

    },
    async created(){
        qz.security.setCertificatePromise((resolve, reject) => {
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
                this.$http.post('/api/qz/signing', {
                        request: toSign
                    })
                    .then(response => {
                        resolve(response.data);
                    }).catch(error => {
                        reject(error.data);
                    });
            };
        });
    },
    methods: {

        addNumberPin(number) {
            if (this.pin.length >= 4) {
                return;
            }
            this.pin += number.toString();
        },
        close(){
               this.$emit("update:localOrden", []);
        },
        update_price(index,sale_unit_price){
            let localOrden_update = this.localOrden;
            localOrden_update[index].food.sale_unit_price = sale_unit_price
            this.$emit("update:localOrden", localOrden_update);
             this.calculateTotal()
        },
        sumar_orden(index) {
            let localOrden_quantity = this.localOrden;
            localOrden_quantity[index].quantity = localOrden_quantity[index].quantity + 1
            this.$emit("update:localOrden", localOrden_quantity);
            this.calculateTotal()
        },
        restar_orden(index) {
            let localOrden_quantity = this.localOrden;
            if (localOrden_quantity[index].quantity > 1) {
                localOrden_quantity[index].quantity = localOrden_quantity[index].quantity - 1
                this.$emit("update:localOrden", localOrden_quantity);
            }
            this.calculateTotal()
        },
        async printTicket() {
            let id = this.ordens[0].orden_id;
            //  let total = this.totalOrdenItems;
            //   try {
            //     printjs({
            //       printable: `restaurant/worker/print-ticket?id=${id}&total=${total}`,
            //       type: "pdf",
            //       showModal: true,
            //       modalMessage: "Espere por favor...",
            //     });
            //   } catch (e) {
            //     console.log(e.response);
            //   }
            try {
                const response = await this.$http.get(`/restaurant/worker/record/${id}`);
                let config = qz.configs.create(response.data.printer, {
                    scaleContent: false
                });
                if (!qz.websocket.isActive()) {
                    await qz.websocket.connect(config);
                }
                let data = [{
                    type: 'pdf',
                    format: 'file',
                    data: response.data.print
                }];
                qz.print(config, data).catch((e) => {
                    this.$message.error(e.message);
                });

                //this.$message.success("se esta imprimiendo el comprobante con exito");
                // qz.websocket.disconnect()
            } catch (e) {
                this.$message.error(e.message);
            }
        },

        view_orders() {
            $(".style-switcher").animate({
                right: "0"
            }, 300).addClass("active")

        },
        selectAllCats(){

        },
        closeDialog(ordenId = null) {
            let ordenToAdd = [...this.localOrden];
            ordenToAdd = ordenToAdd.map((o) => ({
                status_orden_id: 1,
                food: {
                    description: o.food.description,
                    price: o.food.price
                },
                observations: o.observation,
            }));
            // let allOrdens = [...ordenToAdd, ...this.ordens];
            this.$emit("updateOrdens", ordenId);
            this.$emit("listtables")
            // this.$emit("update:ordens", allOrdens);
            this.$emit("update:localOrden", []);
            this.$eventHub.$emit("reloadData");
            this.totalOrdenItems = 0.0;
            this.total = 0.0;
            this.totalOrden = 0.0;
        },
    clear_command(){
        this.commands_fisico=null
    },
    async sendOrden(){

        if(this.configuration.commands_fisico==true){
            if(this.commands_fisico=="" || this.commands_fisico==null || this.commands_fisico.length==0){
                            return  this.$message.error("Debe ingresar la comanda fisica");
            }
        }
         this.disableSend=true
        let form_submit={
            id:null,
            caja: true,
            date_opencash:this.date_opencash,
            printing:this.configuration.print_commands,
            commands_fisico:this.commands_fisico,
            print_kitchen : this.configuration.print_kitchen,
            to_carry: this.to_carry,
            orden:{
                table_id:1,
                status_orden_id: 1,
            },
            items:this.localOrden,
            pin:null
        }
        this.loading = true;
        const responses = await this.$http.post("/restaurant/worker/send-orden", form_submit);
        if (responses.status == 200) {
            this.commands_fisico=""
            this.to_carry=false
            let IdOrdensend=responses.data.id
            if (responses.data.success== true) {
                this.$emit("paymentsOrden",  responses.data.id);
                this.loading = false;
                this.$message.success(responses.data.message);
                 this.disableSend=false
                document.querySelector('#close_offcanvas').click()
            } else {
                 this.loading = false;
                  this.$message.error(responses.data.message);
            }
        }
       this.loading = false;
       this.disableSend=false

        },
        formatUrlImage(url) {
            if (!url) return;
            let formated = "storage/uploads/items/" + url;
            return `/${formated}`;
        },
        async cancelGeneralOrden() {
            this.loading = true;
            try {
                let res = await this.$confirm(
                    "Desea cancelar toda la orden?",
                    "Cancelar", {
                        confirmButtonText: "Ok",
                        cancelButtonText: "Cancelar",
                        type: "warning",
                    }
                );
                if (res) {
                    let form = {
                        id: this.ordens[0].orden_id
                    };
                    const response = await this.$http.post("cancel-orden", form);
                    if (response.status == 200) {
                        const {
                            message
                        } = response.data;
                        this.$message.success(message);
                        this.$eventHub.$emit("reloadData");
                        this.$emit("ordenDeleted");
                    }
                }
            } catch (e) {
                if (e != "cancel") {
                    console.log(e);
                    this.$message.error("Ocurrió un error");
                }
            }
            this.loading = false;
        },
        calculateTotal(w = null) {
            this.totalOrdenItems = 0.0;
            this.total = 0.0;
            this.totalOrden = 0.0;
            let OrdenPen = 0;
            let OrdenPenAtendidos = 0;
            let items_document = []
             let percentage_igv = this.percentage_igv
           // let data.forEach(this.localOrden, function (item) {
            this.localOrden.forEach((item, index, ) => {
                 items_document.push({
                    codigo_interno: item.food.id,
                    descripcion:item.food.description,
                    codigo_producto_sunat: "",
                    unidad_de_medida: item.food.item.unit_type_id,
                    cantidad: item.quantity,
                    valor_unitario: _.round(item.food.item.sale_unit_price/(1+(percentage_igv/100)),2),
                    codigo_tipo_precio: "01",
                    precio_unitario: item.food.item.sale_unit_price,
                    codigo_tipo_afectacion_igv: item.food.item.sale_affectation_igv_type_id,
                    total_base_igv:_.round((item.food.item.sale_unit_price*item.quantity)/ (1+(this.percentage_igv/100)),2),
                    porcentaje_igv: this.percentage_igv,
                    total_igv: _.round((_.round(item.food.item.sale_unit_price*item.quantity,2)/(1+(this.percentage_igv/100)))*(this.percentage_igv/100),2),
                    total_impuestos: _.round((_.round(item.food.item.sale_unit_price*item.quantity,2)/(1+(this.percentage_igv/100)))*(this.percentage_igv/100),2),
                    total_valor_item: _.round(_.round(item.food.item.sale_unit_price/(1+(this.percentage_igv/100)),2)*item.quantity,2),
                    total_item: _.round(item.food.item.sale_unit_price*item.quantity,2)

                });
             });
 
             let nTotal_poratendidos = _.forEach(this.localOrden, function (value) {
                OrdenPen = parseFloat(OrdenPen) + (value.quantity * value.food.price)
            });
            this.totalOrden = _.round(OrdenPen, 2)
            let nTotal_atendidos = _.forEach(this.ordens, function (values) {
                OrdenPenAtendidos = parseFloat(OrdenPenAtendidos) + (values.quantity * values.food.price)
            });
            this.totalOrdenItems = _.round(OrdenPenAtendidos, 2)
            this.total = this.totalOrden + this.totalOrdenItems;
            this.$emit("total_sales", this.total);
            this.$emit("items_document",items_document)

        },
        deleteFood(idx) {
            this.$emit("deletedFood", idx);
            this.calculateTotal()
        },
        async submit() {
            //this.loading = true;
            this.showDialogPing = true
            this.open_orders()
        },
        async cancelOrden(id) {
            try {
                let res = await this.$confirm(
                    "Desea cancelar este pedido?",
                    "Cancelar", {
                        confirmButtonText: "Ok",
                        cancelButtonText: "Cancelar",
                        type: "warning",
                    }
                );
                if (res) {
                    const response = await this.$http.delete(`delete-orden/${id}`);
                    if (response.status == 200) {
                        const {
                            message
                        } = response.data;
                        let newOrdenItems = [...this.ordens];
                        newOrdenItems = newOrdenItems.filter((n) => n.id != id);
                        this.$emit("update:ordens", newOrdenItems);
                        this.$eventHub.$emit("reloadData");
                        this.$message.success(message);
                    }
                }
            } catch (e) {
                //todo
                if (e != "cancel") {
                    this.$message.error("Ocurrió un error");
                }
            }
        },
        async ordenReady(id) {
            this.loading = true;
            try {
                const response = await this.$http.get(`ordens-ready/${id}`);

                const {
                    success,
                    message
                } = response.data;
                success ? this.$message.success(message) : this.$message.error(message);
                if (success) {
                    let cloneOrdenItems = [...this.ordens];
                    cloneOrdenItems = cloneOrdenItems.map((o) => {
                        if (o.id == id) {
                            o.status_orden_id = 3;
                        }
                        return o;
                    });
                    this.$emit("update:ordens", cloneOrdenItems);
                }
            } catch (e) {
                console.log(e);
                this.$message.error("Ocurrió un error");
            }
            this.loading = false;
        },
        async changeObservation() {
            //this.localObservation
            this.loadingObservation = true;
            const response = await this.$http.post("change-observation", {
                observation: this.observation,
                id: this.currentOrden,
            });
            if (response.status == 200) {
                this.$eventHub.$emit("reloadData");
                let newOrdenItems = [...this.ordens];
                newOrdenItems.find((n) => n.id == this.currentOrden).observations =
                    this.observation;
            }
            this.loadingObservation = false;
            this.closeObservationDialog();
        },
        openObservationDialog(id, idx) {
            this.currentOrden = id;
            this.observation = this.ordens[idx].observations;
            this.dialogObservation = true;
        },
        closeObservationDialog() {
            this.dialogObservation = false;
            this.observation = null;
        },
        changeLocalObservation() {
            let ordenModified = [...this.localOrden];
            ordenModified[this.currentLocalOrden].observation = this.localObservation;
            this.$emit("update:localOrden", ordenModified);
            this.closeLocalObservationDialog();
        },
        openLocalObservationDialog(idx) {
            this.currentLocalOrden = idx;
            this.localObservation = this.localOrden[idx].observation;
            this.dialogLocalObservation = true;
        },
        closeLocalObservationDialog() {
            this.dialogLocalObservation = false;
            this.currentLocalOrden = null;
            this.localObservation = null;
        },
    },
};
</script>

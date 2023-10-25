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
                                <div class="d-flex justify-content-end mb-2">
                                    <div class="btn-group check-all-container mt-n1" v-if="localOrden.length>0">
                                    <div class="btn btn-sm btn-primary" @click="sendOrden()">
                                             <!-- <input type="checkbox" class="form-check-input" id="checkAll" v-model="AllSelected"  > -->
                                            <i class="icofont-checked"></i>   Enviar Pedido - Cobrar
                                     </div>
                                       <div class="btn btn-sm btn-danger"  data-bs-dismiss="offcanvas" aria-label="Close" @click="close()">
                                             <!-- <input type="checkbox" class="form-check-input" id="checkAll" v-model="AllSelected"  > -->
                                         <i class="icofont-close-line"></i>   Cancelar
                                     </div>

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
                                    <div class="os-content-glue" style="margin: 0px -15px;">
                                    </div>
                                    <div class="os-padding">
                                    <div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll;">
                                    <div class="os-content" style="padding: 0px 15px; height: 100%; width: 100%;">

                                        <div v-for="(order_pend,indexx) in localOrden" :key="indexx">
                                        <div class="card mb-1 pt-2 pb-2 border">
                                            <div class="card-body pt-0 pb-0 h-100"  >
                                            <div class="row g-0 h-100 align-content-center">
                                                <div class="col-12 col-md-3 d-flex flex-column justify-content-center mb-1 mb-md-0">
                                                <div class="text-muted text-small d-md-none">Cantidad</div>
                                                    <el-input-number size="mini" controls-position="right"	 v-model="order_pend.quantity"></el-input-number>
                                                     <!-- <div class="input-group spinner" data-trigger="spinner">

                                                        <input type="text" class="form-control text-center" v-model="order_pend.quantity" data-rule="currency" />
                                                        <div class="input-group-text">
                                                            <button type="button" class="spin-up" data-spin="up" @click="sumar_orden(indexx)">
                                                                <span class="arrow"></span>
                                                            </button>
                                                            <button type="button" class="spin-down" data-spin="down" @click="restar_orden(indexx)">
                                                                <span class="arrow"></span>
                                                            </button>
                                                        </div>
                                                    </div> -->
                                                </div>
                                                 <div class="col-12 col-md-4 d-flex flex-column justify-content-center mb-1 mb-md-0">
                                                <div class="text-muted text-small d-md-none">Detalle</div>
                                                    <div class="text-alternate">
                                                        {{ order_pend.food.description.toUpperCase() }}
                                                    </div>
                                                </div>

                                                <div class="col-6 col-md-2 d-flex flex-column justify-content-center align-items-md-center mb-1 mb-md-0">
                                                <div class="text-muted text-small d-md-none">Precio</div>
                                                <div class="text-alternate">

                                                   <el-input-number size="mini" :controls="false" :precision="2" v-model="order_pend.food.price"></el-input-number>
                                                </div>
                                                </div>

                                                <div class="col-12 col-md-3  d-flex align-items-center justify-content-center text-alternate text-medium justify-content-center">
                                                <div class="text-alternate">
                                                    <el-button-group>
                                                        <el-button class="text-white" type="danger" icon="el-icon-delete" circle @click="deleteFood(indexx)">
                                                        </el-button>
                                                        <el-button class="text-white" type="info" icon="el-icon-s-order" circle  @click="openLocalObservationDialog(indexx)">
                                                        </el-button>
                                                    </el-button-group>
                                                 </div>

                                                </div>
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
    props: ["localOrden", "configuration", "ordens"],
    created() {},

    data() {
        return {
            pin: "",
            totalOrdenItems: 0.0,
            total: 0.0,
            totalOrden: 0.0,
            loading: false,
            AllSelected:false,
            showDialogPing: false,
            dialogLocalObservation: false,
            currentLocalOrden: null,
            localObservation: null,
            dialogObservation: false,
            observation: null,
            loadingObservation: false,
            currentOrden: null,
            form_ped: {}
        };
    },
    watch: {
        ordens(newOrdens, _) {
            this.calculateTotal(newOrdens);
        },
    },
    mounted() {

    },
    created(){
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

               //s this.$message.success("se esta imprimiendo el comprobante con exito");
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
    async sendOrden(){
             //this.form_submit=this.form_pe
        let form_submit={
            id:null,
            caja:true,
            orden:{
                table_id:1,
                status_orden_id: 1,
            },
            items:this.localOrden,
            pin:null
        }
        this.loading = true;
        const response = await this.$http.post("/restaurant/worker/send-orden", form_submit);
        if (response.status == 200) {
            const { success, message } = response.data;
            if (success) {
            const { ordenId } = response.data;
                         for (let index = 0; index < response.data.copies; index++) {
                            let config = qz.configs.create(response.data.printer, {scaleContent : false},{ copies: response.data.copies },{legacy: true});
                            if (!qz.websocket.isActive()) {
                                await qz.websocket.connect(config);
                            }
                            let data = [
                                {
                                    type: 'pdf',
                                    format: 'file',
                                    data: response.data.print
                                }
                            ];
                            qz.print(config, data).catch((e) => {
                                this.$message.error(e.message);
                            });
                         }
            this.$emit("cobrar",  response.data.id);
            this.$message.success(message);
          //      this.loading = false;
            } else {
            this.$message.error(message);
            this.loading = false;
            }
        }
       this.loading = false;
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
            let nTotal_poratendidos = _.forEach(this.localOrden, function (value) {
                OrdenPen = parseFloat(OrdenPen) + (value.quantity * value.food.price)
            });
            this.totalOrden = _.round(OrdenPen, 2)
            let nTotal_atendidos = _.forEach(this.ordens, function (values) {
                OrdenPenAtendidos = parseFloat(OrdenPenAtendidos) + (values.quantity * values.food.price)
            });
            this.totalOrdenItems = _.round(OrdenPenAtendidos, 2)
            this.total = this.totalOrden + this.totalOrdenItems;
        },
        deleteFood(idx) {
            this.$emit("deletedFood", idx);
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

<template>
<div v-loading="loading">
    <el-dialog @close="closeLocalObservationDialog" :visible="dialogLocalObservation" append-to-body title="Editando observación">
        <span>
        <label class="control-label"> Observación </label>
        <el-input v-model="localObservation"></el-input>
        </span>
        <span slot="footer" class="dialog-footer">
            <button class="btn btn-sm btn-secondary" @click="changeLocalObservation">
                Cambiar
            </button>
              <button class="btn btn-sm btn-light" @click="closeLocalObservationDialog">
                Cerrar
            </button>
        </span>
    </el-dialog>

    <el-dialog key="db" v-loading="loadingObservation" @close="closeLocalObservationDialog" :visible="dialogObservation" append-to-body title="Editando observación">
        <label class="control-label"> Observación </label>
        <el-input v-model="observation"></el-input>
        <div class="row mt-1 d-flex flex-row justify-content-end">
            <button class="btn btn-sm btn-primary" @click="changeObservation">
                Cambiar
            </button>
        </div>
    </el-dialog>

    <template>
        <Pinform
            :showDialogPing.sync="showDialogPing"
            :to_carry.sync="to_carry"
            :configuration.sync="configuration"
            :ordenSelectedId.sync="ordenSelectedId"
            :tableId.sync="tableId"
            :localOrden.sync="localOrden"
            @add="closeDialog">
        </Pinform>
    </template>

    <div id="styleSwitcher" class="style-switcher">
        <a id="styleSwitcherOpen" style="overflow-y: auto" class="style-switcher-open" href="javascript:void(0)" @click="open_orders()"><i class="fa fa-shopping-basket fa-2x"></i></a>

        <div class="product-wrapper-grid list-view  p-2" id="scroll1" style="height:calc(100vh - 14rem);overflow-y: auto;overflow-x:hidden;margin-bottom:15px;">
            <div class="el-dialog__headers">
                <span class="el-dialog__title">Lista de Ordenes

                </span>
                    <button
                        type="button"
                        aria-label="Close"
                        class="el-dialog__headerbtn" @click="open_orders()">
                        <i class="el-dialog__close el-icon el-icon-close"></i>
                    </button>
            </div>
            <div class="row" v-if="ordens.length != 0">

                <div class="col-12">
                    <p class="h4 txt-info p-10 txt-primary f-w-700"><i class="icofont icofont-fork-and-knife"></i>
                        <a class="badge badge bg-dark text-white" href="javascript:void(0)">
                            <template v-if="ordens.length>0 && ordens.length<=9">
                                0{{ordens.length}}
                            </template>
                            <template v-else>
                                {{ordens.length}}
                            </template>
                        </a>
                        Orden de Pedido Nº {{ordenSelectedId}}</p>
                </div>
                <div class="col-md-12" v-for="(ord_row, idxx) in ordens" :key="idxx">

                    <div class="card mb-2" id="card">
                        <div class="row">
                            <div class="col-auto">
                                <template v-if="ord_row.food.image=='imagen-no-disponible.jpg'">
                                    <img src="/images/comida.png" alt="User Img" class="card-img card-img-horizontal h-100 thumbail">
                                </template>
                                <template v-else>
                                    <img :src="formatUrlImage(ord_row.food.image)" class="card-img card-img-horizontal h-100 thumbail" />
                                </template>
                            </div>
                            <div class="col position-relative h-100 p-0 m-0">
                                <div class="card-body p-2">
                                    <div class="row h-100">
                                        <div class="col-12 mb-md-0 d-flex align-items-center p-1">
                                            <div class="pt-0 pb-0 pe-2">
                                                <div class="h6 mb-0 clamp-line" data-line="1"> {{ ord_row.food.description.toUpperCase() }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row h-100">
                                        <div class="col-5 mb-md-0 d-flex align-items-center p-1">
                                            {{ord_row.quantity}} x {{ ord_row.food.price }}
                                        </div>

                                        <div class="col-5 d-flex justify-content-start justify-content-md-start align-items-center p-1">

                                            <el-button-group>
                                                <el-tooltip v-if="ord_row.status_orden_id != 3" effect="dark" content="Pedido listo" placement="top-start">
                                                    <el-button @click="ordenReady(ord_row.id)" type="success" icon="el-icon-check" size="mini" circle></el-button>
                                                </el-tooltip>
                                                <el-tooltip effect="dark" content="Cancelar pedido" placement="top-start">
                                                    <el-button type="danger" icon="el-icon-delete" @click="cancelOrden(ord_row.id)" circle size="mini">
                                                    </el-button>
                                                </el-tooltip>
                                            </el-button-group>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="ordens.length > 0" class="row d-flex flex-row p-2">
                    <div class="col-12 d-flex justify-content-end">
                        <el-button-group>
                            <el-button type="success" @click="printTicket">
                                <i class="icofont-printer"></i> Imprimir ticket
                            </el-button>
                            <el-button type="danger" @click="cancelGeneralOrden">
                                <i class="icofont-close-line"></i> Cancelar
                            </el-button>
                        </el-button-group>
                    </div>
                </div>
            </div>

            <div class="row" v-if="localOrden.length != 0">

                <div class="col-12">
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
                </div>
                <div class="col-md-12" v-for="(order_pend, idx) in localOrden" :key="idx">
                    <div class="card mb-2"  id="card">
                        <div class="row">
                            <div class="col-auto">
                                <template v-if="order_pend.food.image=='imagen-no-disponible.jpg'">
                                    <img src="/images/comida.png" alt="User Img" class="card-img card-img-horizontal h-100 thumbail">
                                </template>
                                <template v-else>
                                    <img :src="formatUrlImage(order_pend.food.image)" class="card-img card-img-horizontal h-100 thumbail" />
                                </template>
                            </div>
                            <div class="col position-relative h-100 p-0 m-0">
                                <div class="card-body p-1">
                                    <div class="row h-100">
                                        <div class="col-12 mb-md-0 d-flex align-items-center">
                                            <div class="pt-0 pb-0 pe-2">
                                                <div class="h6 mb-0 clamp-line" data-line="1">
                                                         {{ order_pend.food.description.toUpperCase() }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row h-100">
                                        <div class="col-5 mb-md-0 d-flex align-items-center">
                                            <div class="input-group spinner" data-trigger="spinner">
                                                <input type="text" :disabled="true" class="form-control text-center" v-model="order_pend.quantity" data-rule="currency" @change="change_quantity(idx,order_pend.quantity,parseInt(order_pend.food.item.stock) )" />
                                                <div class="input-group-text">
                                                    <button type="button" class="spin-up" data-spin="up" @click="sumar_orden(idx,parseInt(order_pend.food.item.stock))">
                                                        <span class="arrow"></span>
                                                    </button>
                                                    <button type="button" class="spin-down" data-spin="down" @click="restar_orden(idx)">
                                                        <span class="arrow"></span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3 d-flex justify-content-start justify-content-md-start align-items-center p-1">
                                            <div class="h6 mb-0">S/ {{ order_pend.food.price }}</div>
                                        </div>
                                        <div class="col-4 d-flex justify-content-start justify-content-md-start align-items-center p-1">
                                            <el-button-group>
                                                <el-button class="text-white" type="danger" icon="el-icon-delete" circle @click="deleteFood(idx)">
                                                </el-button>
                                                <el-button class="text-white" type="info" icon="el-icon-s-order" circle  @click="openLocalObservationDialog(idx)">
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

        <div class="row p-2" v-if="localOrden.length != 0">
            <!-- <div class="col-12 f-w-700 text-end pt-2 pb-2">
                <label class="control-label w-100">Para llevar</label>
                <el-switch v-model="to_carry" active-text="Si" inactive-text="No"></el-switch>
            </div> -->
            <div class="col-12 d-flex justify-content-end">
                <button @click="submit" class="btn btn-success btn-sm">
                    Enviar pedido
                </button>
            </div>
        </div>

        <div class="d-flex flex-column p-2">
            <div v-show="localOrden.length > 0" class="row p-r-10 ">
                <div class="col-12 f-w-700 text-end p-t-5">
                    POR ATENDER S/ {{ totalOrden.toFixed(2) }}
                </div>

                <div class="col-12 f-w-700 text-end p-t-5">
                    ATENDIDO S/ {{ totalOrdenItems.toFixed(2) }}
                </div>

                <div class="col-12 f-w-700 text-end p-t-5 p-b-5">
                    TOTAL S/ {{ total.toFixed(2) }}
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<style scoped>
.button-code {
    margin-right: 1.5px !important;
}

.pin-code {
    font-weight: bold;
    font-size: 20px !important;
    letter-spacing: 10px;
}
</style>

<script>
import printjs from "print-js";
import Pinform from "./paid.vue";
export default {
    props: ["localOrden", "configuration", "tableId", "ordens", "ordenSelectedId"],
    created() {},
    components: {
        Pinform
    },
    data() {
        return {
            pin: "",
            totalOrdenItems: 0.0,
            total: 0.0,
            totalOrden: 0.0,
            loading: false,
            showDialogPing: false,
            dialogLocalObservation: false,
            currentLocalOrden: null,
            localObservation: null,
            dialogObservation: false,
            observation: null,
            loadingObservation: false,
            currentOrden: null,
            form_ped: {},
            to_carry:false
        };
    },
    watch: {
        ordens(newOrdens, _) {
            this.calculateTotal(newOrdens);
        },
    },
    mounted() {
        /* Add here all your JS customizations */
        $(".switcher-hover").mouseenter(function () {
            $("#switcher-list").toggleClass("fade show active");
            $("#switcher-top").toggleClass("fade show active");
        });
        $(".switcher-hover").mouseleave(function () {
            $("#switcher-list").toggleClass("fade show active");
            $("#switcher-top").toggleClass("fade show active");
        });
        // $(".close_switcher").click(function() {
        //   $(".style-switcher").animate({right: "-" + $(".style-switcher").width() + "px"}, 300).removeClass("active")
        // })
    },
    methods: {

        addNumberPin(number) {
            if (this.pin.length >= 4) {
                return;
            }
            this.pin += number.toString();
        },
        change_quantity(index,quantity,stock){
            let stock_disp=stock;
                  let localOrden_quantity = this.localOrden;
             if(this.configuration.sales_stock==false || this.configuration.sales_stock==0){
                localOrden_quantity[index].quantity = quantity
             }else{
                if (localOrden_quantity[index].quantity < stock) {
                 localOrden_quantity[index].quantity = quantity
                }else{
                        localOrden_quantity[index].quantity = 1
                        this.$alert('Stock Insuficiente..... <br> Stock Disponible: '+parseInt(stock_disp), 'Aviso de Advertencia', {
                        dangerouslyUseHTMLString: true,
                            confirmButtonText: 'Aceptar',
                            type: 'error'
                        });
                }
             }

                this.$emit("update:localOrden", localOrden_quantity);

        },
        sumar_orden(index,stock) {
               let stock_disp=stock;
               let localOrden_quantity = this.localOrden;
             if(this.configuration.sales_stock==true || this.configuration.sales_stock==1){
                localOrden_quantity[index].quantity = localOrden_quantity[index].quantity + 1
                this.$emit("update:localOrden", localOrden_quantity);
             }else{

            if (localOrden_quantity[index].quantity < stock) {
                 localOrden_quantity[index].quantity = localOrden_quantity[index].quantity + 1
                 this.$emit("update:localOrden", localOrden_quantity);
             }else{
                 localOrden_quantity[index].quantity = 1
                this.$alert('Stock Insuficiente <br> Stock Disponible: '+parseInt(stock_disp), 'Aviso de Advertencia', {
                       dangerouslyUseHTMLString: true,
                        confirmButtonText: 'Aceptar',
                        type: 'error'
                });
             }

             }



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
                let config = qz.configs.create(response.data.printer,{ host:"192.168.1.38", usingSecure: false, scaleContent: false });
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

                this.$message.success("se esta imprimiendo el comprobante con exito");
                // qz.websocket.disconnect()
            } catch (e) {
                this.$message.error(e.message);
            }
        },
        open_orders() {
            $(".style-switcher").hasClass("active") ? $(".style-switcher").animate({
                right: "-" + $(".style-switcher").width() + "px"
            }, 300).removeClass("active") : $(".style-switcher").animate({
                right: "0"
            }, 300).addClass("active")

        },
        view_orders() {
            $(".style-switcher").animate({
                right: "0"
            }, 300).addClass("active")

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
            this.to_carry=false;
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
            this.$emit("deleteFood", idx);
            if (this.localOrden.length == 0) {
                // this.clearPin();
            }
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

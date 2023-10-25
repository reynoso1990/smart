<template>
  <el-dialog
    :visible="is_payment"
    @open="date_of_issue()"
    :close-on-click-modal="false"
    :close-on-press-escape="false"
    :show-close="false"
    append-to-body
    top="2vh"
  >
    <div class="card mb-0">
      <div class="card-header bg-primary text-white">
        <h5 class="my-0 text-white">Punto de Venta</h5>
      </div>
      <div class="card-body pt-1"  v-loading="loading_submit">
          <div class="row pt-2">
            <div class="col-lg-12">
                <div class="card card-default mb-2 border">
                    <div class="card-body">
                    <!--  -->
                        <div class="row  justify-content-between">
                                <div class="col-12 col-md-5 col-lg-5">
                                <label class="control-label">Comprobante de Pago</label>
                                <div class="form-group">
                                    <el-radio-group
                                    v-model="form.document_type_id"
                                    size="small"
                                    @change="
                                        filterSeries();
                                        date_of_issue();
                                    ">
                                    <el-radio-button label="01">FACTURA </el-radio-button>
                                    <el-radio-button label="03">BOLETA </el-radio-button>
                                    <el-radio-button label="80">N. VENTA </el-radio-button>
                                    </el-radio-group>
                                </div>
                                </div>
                                <div class="col-6 col-md-3 col-lg-3">
                                <label class="control-label">Serie</label>
                                    <div class="form-group">
                                        <el-select v-model="form.series_id" class="w-100">
                                        <el-option
                                            v-for="option in series"
                                            :key="option.id"
                                            :label="option.number"
                                            :value="option.id"
                                        >
                                        </el-option>
                                        </el-select>
                                    </div>
                                </div>

                                <div class="col-6 col-md-4 col-lg-4 ">
                                        <div class="form-group">
                                            <label class="control-label">Fecha de Emisiòn</label>
                                            <el-date-picker
                                            v-model="form.date_of_issue"
                                            type="date"
                                            value-format="yyyy-MM-dd"
                                            :clearable="false"
                                            format="dd-MM-yyyy"
                                            :picker-options="datEmision"
                                            >
                                            </el-date-picker>
                                        </div>
                                </div>

                        </div>
                    </div>
                     <!--  -->
                </div>
            </div>
            <!-- <div class="col-md-2 col-sm-6">
              <p class="my-0"><small>Ultima Fecha</small></p>

            </div> -->
             <div class="col-lg-12">
              <div class="card card-default mb-2 border">
                <div class="card-body text-center text-dark p-2">
                  <div class="row justify-content-between">

                    <div class="col-lg-12">
                      <label class="control-label text-left  d-flex align-items-start justify-content-start">Enviar Comprobante por whatsapp</label>
                      <el-input v-model="form.customer_telephone">
                         <template slot="prepend">+51</template>
                      </el-input>
                    </div>

                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="card card-default mb-2 border">
                <div class="card-body text-center text-dark p-2">
                  <div class="row justify-content-between">

                    <div class="col-lg-8">
                      <label class="control-label text-left  d-flex align-items-start justify-content-start">Forma de Pago</label>
                      <div class="radio-tile-group2 flex-wrap">
                        <div class="input-container2 border rounded-sm">
                          <input
                            id="cash"
                            v-model="method_payments"
                            class="radio-button2"
                            type="radio"
                            name="method_payment"
                            value="01"
                            @change="method_payment('Efectivo')"
                          />
                          <div class="radio-tile2">
                            <div class="icon walk-icon">
                              <i class="far fa-money-bill-alt"></i>
                            </div>
                            <label for="cash" class="radio-tile-label2"
                              >Efectivo</label
                            >
                          </div>
                        </div>
                        <div class="input-container2 border rounded-sm">
                          <input
                            id="Tarjeta"
                            v-model="method_payments"
                            class="radio-button2"
                            type="radio"
                            name="method_payment"
                            value="02"
                            @change="method_payment('Tarjeta')"
                          />
                          <div class="radio-tile2">
                            <div class="icon bike-icon">
                              <i class="fas fa-credit-card"></i>
                            </div>
                            <label for="Tarjeta" class="radio-tile-label2"
                              >Tarjeta</label
                            >
                          </div>
                        </div>
                        <div class="input-container2 border rounded-sm">
                          <input
                            id="yape"
                            v-model="method_payments"
                            class="radio-button2"
                            type="radio"
                            name="method_payment"
                            value="03"
                            @change="method_payment('Yape')"/>
                          <div class="radio-tile2">
                            <div class="icon bike-icon">
                              <i class="fas fa-credit-card"></i>
                            </div>
                            <label for="Tarjeta" class="radio-tile-label2"
                              >Yape</label
                            >
                          </div>
                        </div>

                        <div class="input-container2 border rounded-sm">
                          <input
                            id="plin"
                            v-model="method_payments"
                            class="radio-button2"
                            type="radio"
                            name="method_payment"
                            value="04"
                            @change="method_payment('PLIN')"/>
                          <div class="radio-tile2">
                            <div class="icon bike-icon">
                              <i class="fas fa-credit-card"></i>
                            </div>
                            <label for="Tarjeta" class="radio-tile-label2"
                              >PLIN</label
                            >
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-4">
                       <label class="control-label">Monto a cobrar</label>
                      <h3 class="mb-2 mt-2" style="font-size: 16px !important">
                        {{ currencyTypeActive.symbol }} {{ form.total }}
                      </h3>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="card card-default border mb-2">
                <div class="card-body">
                  <div class="row justify-content-between">
                       <div class="col-lg-6">
                      <label class="control-label text-center  d-flex align-items-center justify-content-center">Impresión de Comprobante</label>
                      <div class="radio-tile-group2 flex-wrap">
                        <div class="input-container2 border rounded-sm">
                          <input
                            id="imprimir"
                            v-model="printerOn"
                            class="radio-button2"
                            type="radio"
                            name="imprimir"
                            value="1"/>
                          <div class="radio-tile2">
                            <div class="icon walk-icon">
                              <i class="fa fa-print"></i>
                            </div>
                            <label for="cash" class="radio-tile-label2"
                              >Imprimir</label
                            >
                          </div>
                        </div>
                        <div class="input-container2 border rounded-sm">
                          <input
                            id="noimprimir"
                            v-model="printerOn"
                            class="radio-button2"
                            type="radio"
                            name="noimprimir"
                            value="0"

                          />
                          <div class="radio-tile2">
                            <div class="icon bike-icon">
                              <i class="fa fa-print"></i>
                            </div>
                            <label for="Tarjeta" class="radio-tile-label2"
                              >No Imprimir</label
                            >
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-lg-3">
                      <div class="form-group">
                        <label class="control-label">Ingrese monto</label>
                        <el-input
                          ref="enter_amount"
                          v-model="form.enter_amount"
                          @blur="diferen()"
                          @input="enterAmount()"
                          @keyup.enter.native="clickPayment();inputAmount(form.enter_amount)"
                        >
                          <template slot="prepend">{{
                            currencyTypeActive.symbol
                          }}</template>
                        </el-input>
                      </div>
                    </div>


                    <div class="col-lg-2">
                      <div
                        class="form-group"
                        :class="{ 'has-danger': form.difference < 0 }"
                      >
                        <label
                          class="control-label"
                          v-text="form.difference < 0 ? 'Faltante' : 'Vuelto'"
                        ></label>
                        <!-- <el-input v-model="difference" :disabled="true">
                                        <template slot="prepend">{{currencyTypeActive.symbol}}</template>
                                    </el-input> -->
                        <h4
                          class="
                            control-label
                            font-weight-semibold
                            m-0
                            text-center
                            m-b-0
                          "
                        >
                          {{ currencyTypeActive.symbol }} {{ form.difference }}
                        </h4>
                      </div>
                    </div>
                       <div class="col-lg-12"  v-if="form_payment.payment_method_type_id == '01'">
                        <div class="btn-group btn-group-square m-0 flex-wrap" role="group">
                          <button class="btn btn-outline-primary btn_responsive" @click="setAmountCash(10)">
                            {{ currencyTypeActive.symbol }}10
                          </button>
                          <button class="btn btn-outline-primary btn_responsive"
                            @click="setAmountCash(20)">
                            {{ currencyTypeActive.symbol }}20
                          </button>
                            <button class="btn btn-outline-primary btn_responsive" @click="setAmountCash(50)">
                                {{ currencyTypeActive.symbol }}50
                            </button>
                            <button
                                class="btn btn-outline-primary btn_responsive"
                                @click="setAmountCash(100)">
                                {{ currencyTypeActive.symbol }}100
                            </button>
                                <button class="btn btn-outline-primary btn_responsive"
                                @click="setAmountCash(200)">
                                {{ currencyTypeActive.symbol }}200
                            </button>
                          </div>
                        </div>
                  </div>
                </div>
              </div>
            </div>
             </div>
            <div class="card card-default border">
                <div class="card-body">
                <div class="row ">

                    <div class="col-lg-4">
                        <div class="btn-group btn-group-square" role="group">
                            <button
                                class="btn btn-block btn-primary"
                                @click="clickPayment"
                                :disabled="button_payment">
                                <i class="fas fa-money-bill-alt"></i> PAGAR
                            </button>
                                <button class="btn btn-block btn-dark" @click="back">
                                <i class="fa fa-reply"></i> Cerrar
                            </button>
                        </div>
                    </div>
                </div>
             </div>
              </div>

          </div>

          <multiple-payment-form
            :showDialog.sync="showDialogMultiplePayment"
            :payments="payments"
            @add="addRow"
          ></multiple-payment-form>

          <!-- <sale-notes-options :showDialog.sync="showDialogSaleNote"
                          :recordId="saleNotesNewId"
                          :showClose="true"></sale-notes-options>  -->

          <card-brands-form
            :showDialog.sync="showDialogNewCardBrand"
            :external="true"
            :recordId="null"
          ></card-brands-form>
      </div>
   </el-dialog>
</template>

<style>
.c-width {
  width: 80px !important;
  padding: 0 !important;
  margin-right: 0 !important;
}
.control-label,
h4,
h5,
label {
  color: #000;
}

</style>

<script>
// import * as shajs from 'sha.js';
import _ from "lodash";
import printjs from "print-js";
import CardBrandsForm from "../../../../../../../../resources/js/views/card_brands/form.vue";
// import SaleNotesOptions from '../../sale_notes/partials/options.vue'
//import OptionsForm from './options.vue'
import MultiplePaymentForm from "./multiple_payment.vue";
export default {
  components: { CardBrandsForm, MultiplePaymentForm },

  props: [
    "form",
    "customer",
    "currencyTypeActive",
    "exchangeRateSale",
    "is_payment",
    "soapCompany",
    "direct_printing",
    "auth_login",
    "payments",
    "configuration",
    "idOrden",
    "company",
    "desarrollador",
    "percentage_igv",
    "all_series",
    "date_opencash",
    "documents_data"
  ],
  data() {
    return {
      enabled_discount: false,
      discount_amount: 0,
      loading_submit: false,
      showDialogOptions: false,
      showDialogMultiplePayment: false,
      showDialogSaleNote: false,
      showDialogNewCardBrand: false,
      documentNewId: null,
      saleNotesNewId: null,
      resource_options: null,
      has_card: false,
      method_payments: "01",
      number:null,
      resource: "pos",
      resource_documents: "documents",
      resource_payments: "document_payments",
      amount: 0,
      printerOn: 0,
      button_payment: false,
      input_item: "",
      form_payment: {},
      series: [],
       cards_brand: [],
      cancel: false,
      form_cash_document: {},
      statusDocument: {},
      payment_method_types: [],
      last_date: null,
       datEmision: {
        disabledDate(time) {
          return time.getTime() > moment();
        },
      },
    };
  },
  async created() {
    await this.getTables();
    await this.date_of_issue();
    await this.initFormPayment();
    await this.setInitialAmount();
    this.$eventHub.$on("reloadDataCardBrands", (card_brand_id) => {
      this.reloadDataCardBrands(card_brand_id);
    });

    // this.$eventHub.$on("localSPayments", (payments) => {
    //   this.payments = payments;
    // });
    await this.getFormPosLocalStorage();


    qz.security.setCertificatePromise((resolve, reject) => {
      this.$http
        .get("/api/qz/crt/override", {
          responseType: "text",
        })
        .then((response) => {
          resolve(response.data);
        })
        .catch((error) => {
          reject(error.data);
        });
    });

    qz.security.setSignaturePromise((toSign) => {
      return (resolve, reject) => {
        this.$http
          .post("/api/qz/signing", { request: toSign })
          .then((response) => {
            resolve(response.data);
          })
          .catch((error) => {
            reject(error.data);
          });
      };
    });

    this.setAmountCash(this.form.total);
    // if(this.form.document_type_id=="03"){
    //     this.form.document_type_id = "80";
    // }
    this.filterSeries()
  },
  mounted() {
    this.teclasInit();
     Echo.channel("print_orden").listen(`.print-order-${this.configuration.socket_channel}`,
        (e) => {
              if (e.data.direct_printing == true) {
                this.Printer(e.data.printer, e.data.print, e.data.copies, e.data.user_id,e.data.multiple_boxes);
            }
        }
    );
  },
  methods: {
    changeEnabledDiscount() {
      if (!this.enabled_discount) {
        this.discount_amount = 0;
        this.deleteDiscountGlobal();
        this.reCalculateTotal();
      }
    },
    teclasInit() {
      document.onkeydown = (e) => {
        const key = e.key;

        if (key == "F4") {
          //Agregar cliente
          this.clickPayment();
        }
        if (key == "F6") {
          //Agregar Producto
          //this.clickAddItemInvoice()
          //return false;
        }
        if (key == "F7" && this.form.items.length > 0) {
          //Guardar
          // this.submit()
        }
        if (key == "F8") {
          //Cancelar
          // this.close()
        }
      };
    },
    async date_of_issue() {
        let form_efectivo={
            enter_amount:0,
            difference:0
        }
        const response_efectivo = await this.$http.post(`/efectivo`,form_efectivo);
       // this.$refs.enter_amount.$el.getElementsByTagName("input")[0].focus();
        console.log("this.idOrdennnnnnnnnnnnnnnnnnnn",this.idOrden)
    },
    async Printer(Printer, linkpdf, copies,auth=null,multiple_boxes=false) {
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
        if(this.printerOn==1){
            if(multiple_boxes==true){
                    if(this.auth_login==auth){
                    let config = qz.configs.create(Printer, {
                        scaleContent: false
                    });
                    if (!qz.websocket.isActive()) {
                        await qz.websocket.connect(config);
                    }
                    let data = [{
                        type: 'pdf',
                        format: 'file',
                        data: linkpdf
                    }];
                    qz.print(config, data).catch((e) => {
                    this.$message.error(e.message);
                    });
                    for (let index = 0; index < copies; index++) {
                        qz.print(config, data).catch((e) => {
                            this.$message.error(e.message);
                        });
                    }
                }
            }
            if(multiple_boxes==false){

                    let config = qz.configs.create(Printer, {
                        scaleContent: false
                    });
                    if (!qz.websocket.isActive()) {
                        await qz.websocket.connect(config);
                    }
                    let data = [{
                        type: 'pdf',
                        format: 'file',
                        data: linkpdf
                    }];

                    qz.print(config, data).catch((e) => {
                        this.$message.error(e.message);
                    });
                    for (let index = 0; index < copies; index++) {
                        qz.print(config, data).catch((e) => {
                            this.$message.error(e.message);
                        });
                    }
            }
        }
        },

    clickSendWhatsapp(document_type_id,document_id,number){
                 console.log("this.form.customer_telephone",this.form.customer_telephone)
                if(this.form.customer_telephone!=null){

                let form={
                    id:this.recordId,
                    document_id:document_id,
                    document_type_id:document_type_id,
                    customer_telephone:this.form.customer_telephone,
                    mensaje:"Su comprobante de pago electrónico "+number+" de *"+this.company.name+"*, ha sido generado correctamente a través del facturador electrónico de  *"+this.desarrollador+"*"
                 }
                 console.log("form",form)
                this.$http.post(`/whatsapp`,form)
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
                    this.$message.error(error.response.data.message);
                 })
            .finally(() => {
                this.loading_Whatsapp = false
                this.$message.error(error.response.data.message);
            });
                }


    },
    changeDateOfIssue() {
      this.form.date_of_due = this.form.date_of_issue;
      if (
        moment(this.form.date_of_issue) < moment().day(-1) &&
        this.configuration.restrict_receipt_date
      ) {
        this.$message.error("No puede seleccionar una fecha menor a 6 días.");
        this.dateValid = false;
      } else {
        this.dateValid = true;
      }
      this.form.date_of_due = this.form.date_of_issue;
      this.searchExchangeRateByDate(this.form.date_of_issue).then(
        (response) => {
          this.form.exchange_rate_sale = response;
        }
      );
      this.form.exchange_rate_sale = 1;
    },
    NuevaVenta() {
      this.$parent.nueva_venta();
    },

    async setInitialAmount() {
      this.enter_amount = this.form.total;
      this.form.payments = this.payments;
       await this.$refs.enter_amount.$el
        .getElementsByTagName("input")[0]
        .focus();
      await this.$refs.enter_amount.$el
        .getElementsByTagName("input")[0]
        .select();
      //await this.$refs.enter_amount.$el.getElementsByTagName('input')[0].change()
      //console.log("this.$refs.enter_amount.$el.getElementsByTagName('input')[0]")
    },
    inputDiscountAmount() {
      if (this.enabled_discount) {
        if (
          this.discount_amount &&
          !isNaN(this.discount_amount) &&
          parseFloat(this.discount_amount) > 0
        ) {
          if (this.discount_amount >= this.form.total)
            return this.$message.error(
              "El monto de descuento debe ser menor al total de venta"
            );

          this.reCalculateTotal();
        } else {
          this.deleteDiscountGlobal();
          this.reCalculateTotal();
        }
      }
    },
    discountGlobal() {
      let global_discount = parseFloat(this.discount_amount);
      let base = parseFloat(this.form.total);
      let amount = parseFloat(global_discount);
      let factor = _.round(amount / base, 4);

      let discount = _.find(this.form.discounts, { discount_type_id: "03" });

      if (global_discount > 0 && !discount) {
        this.form.total_discount = _.round(amount, 2);

        this.form.total = _.round(this.form.total - amount, 2);

        this.form.total_value = _.round(this.form.total / (1+(this.percentage_igv/100)), 2);
        this.form.total_taxed = this.form.total_value;

        this.form.total_igv = _.round(this.form.total_value * (this.percentage_igv/100), 2);
        this.form.total_taxes = this.form.total_igv;

        this.form.discounts.push({
          discount_type_id: "03",
          description:
            "Descuentos globales que no afectan la base imponible del IGV/IVAP",
          factor: factor,
          amount: amount,
          base: base,
        });
      } else {
        let index = this.form.discounts.indexOf(discount);

        if (index > -1) {
          this.form.total_discount = _.round(amount, 2);
          this.form.total = _.round(this.form.total - amount, 2);
          this.form.total_value = _.round(this.form.total / (1+(this.percentage_igv/100)), 2);
          this.form.total_taxed = this.form.total_value;
          this.form.total_igv = _.round(this.form.total_value * (this.percentage_igv/100), 2);
          this.form.total_taxes = this.form.total_igv;

          this.form.discounts[index].base = base;
          this.form.discounts[index].amount = amount;
          this.form.discounts[index].factor = factor;
        }
      }
      // this.form.difference = this.enter_amount - this.form.total
      // console.log(this.form.discounts)
    },
    method_payment(method_pay) {
      this.form.payment_condition_id = "01";
      this.form.method_pay=method_pay
      this.form.afectar_caja =  true ;
    },
    reCalculateTotal() {
      let total_discount = 0;
      let total_charge = 0;
      let total_exportation = 0;
      let total_taxed = 0;
      let total_exonerated = 0;
      let total_unaffected = 0;
      let total_free = 0;
      let total_igv = 0;
      let total_value = 0;
      let total = 0;
      let total_plastic_bag_taxes = 0;

      this.form.items.forEach((row) => {
        total_discount += parseFloat(row.total_discount);
        total_charge += parseFloat(row.total_charge);

        if (row.affectation_igv_type_id === "10") {
          total_taxed += parseFloat(row.total_value);
        }
        if (row.affectation_igv_type_id === "20") {
          total_exonerated += parseFloat(row.total_value);
        }
        if (row.affectation_igv_type_id === "30") {
          total_unaffected += parseFloat(row.total_value);
        }
        if (row.affectation_igv_type_id === "40") {
          total_exportation += parseFloat(row.total_value);
        }
        if (["10", "20", "30", "40"].indexOf(row.affectation_igv_type_id) < 0) {
          total_free += parseFloat(row.total_value);
        }
        if (
          ["10", "20", "30", "40"].indexOf(row.affectation_igv_type_id) > -1
        ) {
          total_igv += parseFloat(row.total_igv);
          total += parseFloat(row.total);
        }
        total_value += parseFloat(row.total_value);
        total_plastic_bag_taxes += isNaN(
          parseFloat(row.total_plastic_bag_taxes)
        )
          ? 0.0
          : parseFloat(row.total_plastic_bag_taxes);
      });

      this.form.total_exportation = _.round(total_exportation, 2);
      this.form.total_taxed = _.round(total_taxed, 2);
      this.form.total_exonerated = _.round(total_exonerated, 2);
      this.form.total_unaffected = _.round(total_unaffected, 2);
      this.form.total_free = _.round(total_free, 2);
      this.form.total_igv = _.round(total_igv, 2);
      this.form.total_value = _.round(total_value, 2);
      this.form.total_taxes = _.round(total_igv, 2);
      this.form.total_plastic_bag_taxes = _.round(total_plastic_bag_taxes, 2);
      // this.form.total = _.round(total, 2)

      this.form.total = _.round(total + this.form.total_plastic_bag_taxes, 2);

      this.discountGlobal();
    },
    deleteDiscountGlobal() {
      let discount = _.find(this.form.discounts, { discount_type_id: "03" });
      let index = this.form.discounts.indexOf(discount);

      if (index > -1) {
        this.form.discounts.splice(index, 1);
        this.form.total_discount = 0;
      }
    },

    getFormPosLocalStorage() {
      let form_pos = localStorage.getItem("form_pos");
      form_pos = JSON.parse(form_pos);
      if (form_pos) {
        this.form.payments = form_pos.payments;
      }
    },
    clickAddPayment() {
      this.showDialogMultiplePayment = true;
    },
    reloadDataCardBrands(card_brand_id) {
      this.$http.get(`/${this.resource}/table/card_brands`).then((response) => {
        this.cards_brand = response.data;
        this.form_payment.card_brand_id = card_brand_id;
        this.changePaymentMethodType();
      });
    },
    getDescriptionPaymentMethodType(id) {
      let payment_method_type = _.find(this.payment_method_types, { id: id });
      return payment_method_type ? payment_method_type.description : "";
    },
    changePaymentMethodType() {
      let payment_method_type = _.find(this.payment_method_types, {
        id: this.form_payment.payment_method_type_id,
      });
      this.has_card = payment_method_type.has_card;
      this.form_payment.card_brand_id = payment_method_type.has_card
        ? this.form_payment.card_brand_id
        : null;
    },
    addRow(payments) {
      this.form.payments = payments;
      let acum_payment = 0;

      this.form.payments.forEach((item) => {
        acum_payment += parseFloat(item.payment);
      });
      // this.amount = acum_payment
      this.setAmount(acum_payment);

      // console.log(this.form.payments)
    },
    setAmount(amount) {
      // this.amount = parseFloat(this.amount) + parseFloat(amount)
      this.form.enter_amount=amount
      this.amount = parseFloat(amount); //+ parseFloat(amount)
      //   this.form.enter_amount =  parseFloat(amount) //+ parseFloat(amount)
      this.inputAmount(amount);
    },
    setAmountCash(amount) {
      this.setAmount(amount);

    },
    async diferen(){
       let differen=parseFloat(this.form.enter_amount) - parseFloat(this.form.total)
       if(differen<0){
           this.$message.error("el monto de Efectivo es menor al total de venta");
       }
    },
    async enterAmount(amount = 0) {

      this.amount = amount;

      let differen=parseFloat(this.form.enter_amount) - parseFloat(this.form.total)

       this.form.difference = parseFloat(differen);
      if (this.form.difference<0) {
        this.button_payment = true;
        this.form.difference = differen
      } else if (this.form.difference >= 0) {
        this.button_payment = false;
        parseFloat(this.form.enter_amount) - parseFloat(this.form.total)
      } else {
        this.button_payment = true;
      }
       //this.form.difference = _.round(this.form.difference, 2);

      this.$eventHub.$emit("eventSetFormPosLocalStorage", this.form);
     // this.setAmountCash(amount)
    },
    getLocalStoragePayment(key, re_default = null) {
      let ls_obj = localStorage.getItem(key);
      ls_obj = JSON.parse(ls_obj);

      if (ls_obj) {
        return ls_obj;
      }

      return re_default;
    },
    setLocalStoragePayment(key, obj) {
     return localStorage.setItem(key, JSON.stringify(obj));

    },
    inputAmount(amount = null) {
      this.enterAmount(amount);
      this.form.difference = this.form.enter_amount - this.form.total;
      if (isNaN(this.form.difference)) {
        this.button_payment = true;
        this.form.difference = "-";
      } else if (this.form.difference >= 0) {
        this.button_payment = false;
        this.form.difference = this.amount - this.form.total;
      } else {
        this.button_payment = true;
      }
      //   this.form.difference = _.round(this.form.difference,2)
      // this.form_payment.payment = this.amount

      //this.$eventHub.$emit("eventSetFormPosLocalStorage", this.form);
      //this.lStoPayment()
    },
    lStoPayment() {
      this.setLocalStoragePayment("enter_amount", this.form.enter_amount);
      this.setLocalStoragePayment(
        "amount",
        this.amount == 0 ? this.form.total : this.amount
      );
      this.setLocalStoragePayment(
        "difference",
        this.amount == 0 ? this.form.total - this.amount : 0
      );
    },
    initFormPayment() {
      this.form.difference = this.form.total - this.form.enter_amount;
      this.form_payment = {
        id: null,
        date_of_payment: moment().format("YYYY-MM-DD"),
        payment_method_type_id: "01",
        reference: null,
        card_brand_id: null,
        document_id: null,
        sale_note_id: null,
        payment: this.form.total,
      };

      this.form_cash_document = {
        document_id: null,
        sale_note_id: null,
      };
    },

    cleanLocalStoragePayment() {
      this.setLocalStoragePayment("form_pos", null);
      this.setLocalStoragePayment("amount", null);
      this.setLocalStoragePayment("enter_amount", null);
      this.setLocalStoragePayment("difference", null);
    },
    sleep(ms) {
      return new Promise((resolve) => setTimeout(resolve, ms));
    },

    async clickPayment() {
        if(this.customer.identity_document_type_id=="1" && this.form.document_type_id=="01"){
            return this.$message.error("No puede emitir Factura con DNI")
        }
         if (!this.form.series_id) {
            return this.$message.warning(
            "El establecimiento no tiene series disponibles para el comprobante"
            );
        }
        this.form.printerOn=(this.printerOn==1) ? true : false;
        if (this.form.document_type_id === "80") {
            this.form.prefix = "NV";
            this.form.paid = 1;
            this.resource_documents = "sale-note";
            this.resource_payments = "sale_note_payments";
            this.resource_options = this.resource_documents;
        } else {
            this.form.prefix = null;
            this.resource_documents = "documents";
            this.resource_payments = "document_payments";
            this.resource_options = this.resource_documents;
        }
        if (this.orden != null) {
            this.form.additional_information = `Orden N°${this.orden}`;
        }
        this.form.advances = 0.0;
        this.form.total_advances = 0.0;
        this.form.total_payment = this.form.total;
        this.form.payments = [
            {
            payment_method_type_id: "01",
            date_of_payment: this.form.date_of_issue,
            payment: this.form.total,
            },
        ];
        this.loading_submit = true;
        try {
              let form_efectivo={
                enter_amount:this.form.enter_amount,
                difference:this.form.difference
            }
            console.log("this.documents_data",this.documents_data)
              const response = await this.$http.post(`${this.company.webservice}/api/${this.resource_documents}`,this.documents_data,{
                headers: {
                  Authorization: `Bearer ${this.company.token}`
                }
              });
          if (response.status == 200) {
            let printer = ""
            let linkpdf = ""
            let user_id = ""
            if(response.data.success==true){
                 let document_id=0
                 
                if (this.form.document_type_id === "80") {
                    this.number=response.data.data.number
                    document_id=response.data.data.id;
                     this.form_cash_document.sale_note_id = response.data.data.id;
                     printer = response.data.data.printer
                     linkpdf = response.data.data.print_ticket
                } else {
                    document_id=response.data.data.id;
                    this.form_cash_document.document_id = response.data.data.id;
                    this.number = response.data.data.number
                    printer = response.data.data.printer
                    linkpdf = response.data.data.print_ticket
                }
                 this.documentNewId = response.data.data.id;
             
                 if (this.form.printerOn == true) {
                    this.Printer(printer,linkpdf, 0, null, false);  
                }
   
                if (this.idOrden) {
                    const response2 = await this.$http.post("pos/orden_payment", {
                    id: this.idOrden    ,
                    customer_id: this.customer.id,
                    document: {
                        isNoteSale: this.form.document_type_id === "80",
                        id: this.documentNewId,
                    },
                    });
                     if(response2.data.success== true) {
                        this.clickSendWhatsapp(this.form.document_type_id,this.documentNewId,this.number)
                        
                    }
                }
                this.$emit("limpiarForm");
                this.loading_submit = false;
                this.back()
            }

            }else{
                 this.loading_submit = true;
                  this.$alert('<strong>Ocurrio un error </strong>'+response.statusCode+"<br>"+this.resource_documents, 'HTML String', {
                    dangerouslyUseHTMLString: true
                    });
            }

        } catch (error) {

        }

    },

    async clickPrintPos(printerName, formatoPdf,userId=null) {
      try {
       let config = qz.configs.create(printerName, { legacy: true }, {scaleContent : false});
        if (!qz.websocket.isActive()) {
          await qz.websocket.connect(config);
        }
        let data = [
          {
            type: "pdf",
            format: "file",
            data: formatoPdf,
          },
        ];
        qz.print(config, data).catch((e) => {
          this.$message.error(e.message);
        });
      } catch (e) {
        this.$message.error(e.message);
      }
    },
    saveCashDocument() {
      this.$http
        .post(`/cash/cash_document`, this.form_cash_document)
        .then((response) => {
          if (response.data.success) {
            // console.log(response)
          } else {
            this.$message.error(response.data.message);
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    savePaymentMethod() {
      this.$http
        .post(`/${this.resource_payments}`, this.form_payment)
        .then((response) => {
          if (response.data.success) {
            // console.log(response)
          } else {
            this.$message.error(response.data.message);
          }
        })
        .catch((error) => {
          if (error.response.status === 422) {
            this.records[index].errors = error.response.data;
          } else {
            console.log(error);
          }
        });
    },
    getTables() {
    //   this.$http.get(`/${this.resource}/payment_tables`).then((response) => {
    //     this.all_series = response.data.series;
    //     this.payment_method_types = response.data.payment_method_types;
    //     this.cards_brand = response.data.cards_brand;
        this.filterSeries();
    //   });
    },

    async clickCancel() {
      this.loading_submit = true;

      this.loading_submit = false;
      this.cleanLocalStoragePayment();
      this.$eventHub.$emit("cancelSale");
    },
    back() {
      this.$emit("limpiarForm");
      this.$emit("update:is_payment", false);
    },
    async initLStoPayment() {
      this.amount = await this.getLocalStoragePayment(
        "amount",
        this.form.total
      );
      this.form.enter_amount = await this.getLocalStoragePayment(
        "enter_amount",
        this.form.total
      );
      this.form.difference = await this.getLocalStoragePayment(
        "difference",
        this.form.total - this.form.enter_amount
      );
    },
    filterSeries() {
      if(this.documents_data.datos_del_cliente_o_receptor.numero_documento.length<11 && this.form.document_type_id=="01"){
        this.form.document_type_id ="80";
        return this.$message.error("El N° Documento debe ser RUC de 11 digitos");
      }
      this.form.series_id = null;
      this.series = _.filter(this.all_series, {
        document_type_id: this.form.document_type_id,
      });
     
      this.form.series_id = this.series.length > 0 ? this.series[0].id : null;
      this.documents_data.serie_documento = this.series[0].number
      this.documents_data.codigo_tipo_documento = this.series[0].document_type_id
      this.documents_data.datos_del_cliente_o_receptor.codigo_tipo_documento_identidad = this.series[0].document_type_id =="01" ? "6" : "1"

    },
  },
};
</script>

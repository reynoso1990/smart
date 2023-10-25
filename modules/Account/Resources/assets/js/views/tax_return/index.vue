<template>
    <div>
        <div class="page-header pr-0">
            <h2>
                <a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a>
            </h2>
            <ol class="breadcrumbs">
                <li class="active">
                    <span>{{ title }}</span>
                </li>
            </ol>
        </div>

        <div v-loading="loading" class="card mb-0 pt-2 pt-md-0">
            <div class="card-header">
                <h3 class="my-0">{{ title }}</h3>
            </div>
            <div class="card-body">
                <el-tabs v-model="activeName" type="border-card">
                    <el-tab-pane name="1" label="INFORMACIÓN GENERAL">
                        <div class="row">
                            <div class="col-md-6 col-sm-4">
                                <label for="period">Periodo Tributario</label>
                                <el-date-picker
                                    v-model="form.period"
                                    format="MM-yyyy"
                                    value-format="MM-yyyy"
                                    type="month"
                                    placeholder="Elija un periodo"
                                    @change="getRecords"
                                >
                                </el-date-picker>
                            </div>

                            <div class="col-md-6 col-sm-4">
                                <label for="period">Tipo de moneda</label>
                                <el-select v-model="form.currency_type_id">
                                    <el-option
                                        v-for="(currency, idx) in currencies"
                                        :key="idx"
                                        :label="currency.name"
                                        :value="currency.id"
                                    >
                                    </el-option>
                                </el-select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="d-flex flex-column col-md-6">
                                <span class="bold"
                                    >Impuesto General a las Ventas:</span
                                >
                                <ol>
                                    <li>
                                        <el-radio v-model="form.igv" label="1">
                                            IGV Cuenta propia</el-radio
                                        >

                                        <ul>
                                            <li>
                                                <el-checkbox :value="true">
                                                    IGV Cuenta propia - Tasa 18%
                                                </el-checkbox>
                                            </li>
                                            <li>
                                                <el-checkbox disabled>
                                                    IGV Cuenta propia - Tasa 10%
                                                </el-checkbox>
                                            </li>
                                        </ul>
                                    </li>
                                </ol>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex flex-column col-md-6">
                                    <span class="bold"
                                        >Impuesto a la Renta / Régimen de
                                        Renta:</span
                                    >
                                    <ol>
                                        <li>
                                            <el-radio-group
                                                @change="changeReg"
                                                v-model="reg"
                                            >
                                                <el-radio
                                                    class="mt-1"
                                                    style="display:block;"
                                                    :label="3"
                                                    >Régimen General</el-radio
                                                >
                                                <el-radio
                                                    class="mt-1"
                                                    style="display:block;"
                                                    :label="6"
                                                    >Régimen Especial</el-radio
                                                >
                                                <el-radio
                                                    class="mt-1"
                                                    style="display:block;"
                                                    :label="9"
                                                    >Régimen Tributario
                                                    MYPE</el-radio
                                                >
                                            </el-radio-group>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 d-flex justify-content-center">
                                <el-button type="primary" @click="movePage(2)"
                                    >Siguiente<i class="el-icon-arrow-right"></i
                                ></el-button>
                            </div>
                        </div>
                    </el-tab-pane>
                    <el-tab-pane name="2" label="DETALLE DE DECLARACIÓN">
                        <el-tabs v-model="activeTax">
                            <el-tab-pane
                                v-if="form.sales"
                                label="IGV"
                                name="first"
                            >
                                <div class="row">
                                    <el-divider
                                        content-position="left"
                                        class="text-muted"
                                    >
                                        Ventas</el-divider
                                    >
                                </div>
                                <div class="row mt-3">
                                    <div
                                        class="col-md-4 d-flex align-items-end"
                                    >
                                        <h6>Ventas Netas Gravadas</h6>
                                    </div>
                                    <div class="col-md-4">
                                        <el-input
                                            class="to_right"
                                            v-model="saleTotal"
                                            readonly
                                        >
                                            <template
                                                slot="prepend"
                                                style="background-color:gray !important;"
                                                >100</template
                                            >
                                        </el-input>
                                    </div>
                                    <div class="col-md-4">
                                        <el-input
                                            class="to_right"
                                            v-model="saleTaxTotal"
                                            readonly
                                        >
                                            <template
                                                slot="prepend"
                                                style="background-color:gray !important;"
                                                >101</template
                                            >
                                        </el-input>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-4"></div>
                                    <div
                                        class="col-md-4 d-flex align-items-end justify-content-end"
                                    >
                                        <h6>TOTAL VENTAS</h6>
                                    </div>

                                    <div class="col-md-4">
                                        <el-input
                                            class="to_right"
                                            v-model="saleTaxTotal"
                                            readonly
                                        >
                                            <template
                                                slot="prepend"
                                                style="background-color:gray !important;"
                                                >131</template
                                            >
                                        </el-input>
                                    </div>
                                </div>

                                <div class="row mt-5">
                                    <el-divider
                                        content-position="left"
                                        class="text-muted"
                                        >Compras</el-divider
                                    >
                                </div>
                                <div class="row mt-3">
                                    <div
                                        class="col-md-4 d-flex align-items-end"
                                    >
                                        <h6>
                                            Compras Netas Destinadas a vtas.
                                            gravas
                                        </h6>
                                    </div>
                                    <div class="col-md-4">
                                        <el-input
                                            class="to_right"
                                            readonly
                                            v-model="purchaseTotal"
                                        >
                                            <template
                                                slot="prepend"
                                                style="background-color:gray !important;"
                                                >107</template
                                            >
                                        </el-input>
                                    </div>
                                    <div class="col-md-4">
                                        <el-input
                                            class="to_right"
                                            readonly
                                            v-model="purchaseTaxTotal"
                                        >
                                            <template
                                                slot="prepend"
                                                style="background-color:gray !important;"
                                                >108</template
                                            >
                                        </el-input>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div
                                        class="col-md-4 d-flex align-items-end"
                                    >
                                        <h6>
                                            Compras Netas Destinadas a vtas.
                                            gravas Ley N° 31556 10%
                                        </h6>
                                    </div>
                                    <div class="col-md-4">
                                        <el-input
                                            class="to_right"
                                            readonly
                                            v-model="purchaseTotal_10"
                                        >
                                            <template
                                                slot="prepend"
                                                style="background-color:gray !important;"
                                                >156</template
                                            >
                                        </el-input>
                                    </div>
                                    <div class="col-md-4">
                                        <el-input
                                            class="to_right"
                                            readonly
                                            v-model="purchaseTaxTotal_10"
                                        >
                                            <template
                                                slot="prepend"
                                                style="background-color:gray !important;"
                                                >157</template
                                            >
                                        </el-input>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-4"></div>
                                    <div
                                        class="col-md-4 d-flex align-items-end justify-content-end"
                                    >
                                        <h6>TOTAL COMPRAS</h6>
                                    </div>

                                    <div class="col-md-4">
                                        <el-input
                                            class="to_right"
                                            v-model="allPurchaseTaxTotal"
                                        >
                                            <template
                                                slot="prepend"
                                                style="background-color:gray !important;"
                                                >178</template
                                            >
                                        </el-input>
                                    </div>
                                </div>
                            </el-tab-pane>
                            <el-tab-pane
                                v-if="form.rent"
                                label="RENTA"
                                name="second"
                            >
                                <div class="row" v-if="reg != 6">
                                    <div
                                        class="col-md-4 d-flex align-items-end"
                                    >
                                        <h6>Coeficiente - Art 85° inc a</h6>
                                    </div>
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4">
                                        <el-input
                                            @input="calcPerCoef"
                                            class="to_right"
                                            v-model="coefficient"
                                        >
                                            <template
                                                slot="prepend"
                                                style="background-color:gray !important;"
                                                >380</template
                                            >
                                        </el-input>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div
                                        class="col-md-4 d-flex align-items-end"
                                    >
                                        <h6>Porcentaje - Art 85° inc b</h6>
                                    </div>
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4">
                                        <el-input
                                            readonly
                                            v-model="percentage"
                                            class="to_right"
                                        >
                                            <template
                                                slot="prepend"
                                                style="background-color:gray !important;"
                                                >380</template
                                            >
                                        </el-input>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div
                                        class="col-md-4 d-flex align-items-end"
                                    >
                                        <h6>Ingresos Netos</h6>
                                    </div>
                                    <div class="col-md-4">
                                        <el-input
                                            class="to_right"
                                            readonly
                                            v-model="saleTotal"
                                        >
                                            <template
                                                slot="prepend"
                                                style="background-color:gray !important;"
                                                >301</template
                                            >
                                        </el-input>
                                    </div>
                                    <div class="col-md-4">
                                        <el-input
                                            class="to_right"
                                            readonly
                                            v-model="rentTotal"
                                        >
                                            <template
                                                slot="prepend"
                                                style="background-color:gray !important;"
                                                >312</template
                                            >
                                        </el-input>
                                    </div>
                                </div>
                            </el-tab-pane>
                        </el-tabs>
                        <div class="row mt-3">
                            <div class="col-12 d-flex justify-content-center">
                                <el-button @click="movePage(1)"
                                    ><i class="el-icon-arrow-left"></i
                                    >Anterior</el-button
                                >
                                <el-button type="primary" @click="movePage(3)"
                                    >Siguiente<i class="el-icon-arrow-right"></i
                                ></el-button>
                            </div>
                        </div>
                    </el-tab-pane>
                    <el-tab-pane
                        name="3"
                        v-if="form.determination"
                        :label="'DETERMINACIÓN DE LA DEUDA'"
                    >
                        <div class="row">
                            <div class="col-md-4 d-flex align-items-end">
                                <h6>Impuesto resultante o Saldo a favor</h6>
                            </div>
                            <div class="col-md-4">
                                <el-input
                                    class="to_right"
                                    readonly
                                    v-model="resultTax"
                                >
                                    <template slot="prepend"
                                        >140</template
                                    >
                                </el-input>
                            </div>
                            <div class="col-md-4">
                                <el-input
                                    class="to_right"
                                    readonly
                                    v-model="rentTotal"
                                >
                                    <template slot="prepend"
                                        >302</template
                                    >
                                </el-input>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4 d-flex align-items-end">
                                <h6>Saldo a favor del periodo anterior**</h6>
                            </div>
                            <div class="col-md-4">
                                <el-input
                                    class="to_right"
                                    @input="calculeTax(false)"
                                    v-model="taxInput"
                                >
                                    <template slot="prepend"
                                        >145</template
                                    >
                                </el-input>
                            </div>
                            <div class="col-md-4">
                                <el-input
                                    class="to_right"
                                    @input="calculeTax(true)"
                                    v-model="rentInput"
                                >
                                    <template slot="prepend"
                                        >303</template
                                    >
                                </el-input>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4 d-flex align-items-end">
                                <h6>Tributo a pagar o saldo a favor</h6>
                            </div>
                            <div class="col-md-4">
                                <el-input
                                    readonly
                                    class="to_right"
                                    v-model="tax"
                                >
                                    <template slot="prepend"
                                        >184</template
                                    >
                                </el-input>
                            </div>
                            <div class="col-md-4">
                                <el-input
                                    readonly
                                    class="to_right"
                                    v-model="rent"
                                >
                                    <template
                                        slot="prepend"
                                        style="background-color:gray !important;"
                                        >304</template
                                    >
                                </el-input>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4 d-flex align-items-end">
                                <h6>Percepciones del periodo</h6>
                            </div>
                            <div class="col-md-4">
                                <el-input class="to_right" disabled>
                                    <template slot="prepend"
                                        >171</template
                                    >
                                </el-input>
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4 d-flex align-items-end">
                                <h6>
                                    Saldos de percepciones de periodos
                                    anteriores
                                </h6>
                            </div>
                            <div class="col-md-4">
                                <el-input class="to_right" disabled>
                                    <template slot="prepend"
                                        >168</template
                                    >
                                </el-input>
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4 d-flex align-items-end">
                                <h6>Saldos de percepciones no aplicadas</h6>
                            </div>
                            <div class="col-md-4">
                                <el-input class="to_right" disabled>
                                    <template slot="prepend"
                                        >164</template
                                    >
                                </el-input>
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4 d-flex align-items-end">
                                <h6>Retenciones del periodo</h6>
                            </div>
                            <div class="col-md-4">
                                <el-input class="to_right" disabled>
                                    <template slot="prepend"
                                        >179</template
                                    >
                                </el-input>
                            </div>
                            <div class="col-md-4">
                                <el-input class="to_right" disabled>
                                    <template
                                        slot="prepend"
                                        style="background-color:gray !important;"
                                        >326</template
                                    >
                                </el-input>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4 d-flex align-items-end">
                                <h6>
                                    Saldo de Retenciones de periodos anteriores
                                </h6>
                            </div>
                            <div class="col-md-4">
                                <el-input class="to_right" disabled>
                                    <template slot="prepend"
                                        >176</template
                                    >
                                </el-input>
                            </div>
                            <div class="col-md-4">
                                <el-input class="to_right" disabled>
                                    <template
                                        slot="prepend"
                                        style="background-color:gray !important;"
                                        >327</template
                                    >
                                </el-input>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4 d-flex align-items-end">
                                <h6>Saldo de Retenciones no aplicadas</h6>
                            </div>
                            <div class="col-md-4">
                                <el-input class="to_right" disabled>
                                    <template slot="prepend"
                                        >165</template
                                    >
                                </el-input>
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4 d-flex align-items-end">
                                <h6>
                                    Compensación saldo a favor del exportador
                                </h6>
                            </div>
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <el-input class="to_right" disabled>
                                    <template slot="prepend"
                                        >305</template
                                    >
                                </el-input>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4 d-flex align-items-end">
                                <h6>Pagos a Cuenta en Exceso</h6>
                            </div>
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <el-input class="to_right" disabled>
                                    <template slot="prepend"
                                        >336</template
                                    >
                                </el-input>
                            </div>
                        </div>
                        <div class="row mt-6">
                            <div class="col-md-4 d-flex align-items-end">
                                <h6>SUBTOTAL</h6>
                            </div>
                            <div class="col-md-4">
                                <el-input
                                    readonly
                                    class="to_right"
                                    v-model="tax"
                                >
                                    <template slot="prepend"
                                        >681</template
                                    >
                                </el-input>
                            </div>
                            <div class="col-md-4">
                                <el-input
                                    readonly
                                    class="to_right"
                                    v-model="rent"
                                >
                                    <template slot="prepend"
                                        >682</template
                                    >
                                </el-input>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4 d-flex align-items-end">
                                <h6>Pagos previos</h6>
                            </div>
                            <div class="col-md-4">
                                <el-input disabled class="to_right">
                                    <template slot="prepend"
                                        >185</template
                                    >
                                </el-input>
                            </div>
                            <div class="col-md-4">
                                <el-input disabled class="to_right">
                                    <template slot="prepend"
                                        >317</template
                                    >
                                </el-input>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4 d-flex align-items-end">
                                <h6>Interés moratorio</h6>
                            </div>
                            <div class="col-md-4">
                                <el-input class="to_right" disabled>
                                    <template slot="prepend"
                                        >187</template
                                    >
                                </el-input>
                            </div>
                            <div class="col-md-4">
                                <el-input class="to_right" disabled>
                                    <template slot="prepend"
                                        >319</template
                                    >
                                </el-input>
                            </div>
                        </div>
                        <div class="row mt-2 bg-muted rounded">
                            <div class="row mt-4">
                                <div class="col-md-4 d-flex align-items-end">
                                    <h6>Total Deuda Tributaria</h6>
                                </div>
                                <div class="col-md-4">
                                    <el-input
                                        readonly
                                        class="to_right"
                                        v-model="tax"
                                    >
                                        <template slot="prepend"
                                            >188</template
                                        >
                                    </el-input>
                                </div>
                                <div class="col-md-4">
                                    <el-input
                                        readonly
                                        class="to_right"
                                        v-model="rent"
                                    >
                                        <template slot="prepend"
                                            >324</template
                                        >
                                    </el-input>
                                </div>
                            </div>
                            <div class="row mt-3 bg-gray mb-3">
                                <div class="col-md-4 d-flex align-items-end">
                                    <h6>Importe a Pagar</h6>
                                </div>
                                <div class="col-md-4">
                                    <el-input
                                        readonly
                                        class="to_right"
                                        v-model="tax"
                                    >
                                        <template slot="prepend"
                                            >189</template
                                        >
                                    </el-input>
                                </div>
                                <div class="col-md-4">
                                    <el-input
                                        readonly
                                        class="to_right"
                                        v-model="rent"
                                    >
                                        <template slot="prepend"
                                            >307</template
                                        >
                                    </el-input>
                                </div>
                            </div>
                        </div>
                    </el-tab-pane>
                </el-tabs>
            </div>
        </div>
    </div>
</template>
<style>
.el-input-group__prepend {
    background-color: #f5f7fa !important;
}

.to_right input {
    text-align: right !important;
}
</style>
<script>
import queryString from "query-string";
import { mapActions, mapState } from "vuex";

export default {
    components: {},
    props: ["configuration"],
    computed: {
        ...mapState(["config", "currencys"])
    },
    data() {
        return {
            regexPattern: "^\\d*\\.?\\d*$",
            coefficient: 0,
            percentage: 1,
            loading: false,
            loading_submit: false,
            title: null,
            resource: "/account/tax_return",
            error: {},
            form: {},
            showDialog: false,
            activeName: "1",
            activeTax: "first",
            reg: 9,
            currencies: [
                { id: "PEN", name: "SOLES" },
                { id: "USD", name: "DOLARES" }
            ],
            rentTotal: 0,
            saleTotal: 0,
            saleTaxTotal: 0,
            purchaseTotal: 0,
            purchaseTaxTotal: 0,
            purchaseTotal_10: 0,
            purchaseTaxTotal_10: 0,
            resultTax: 0,
            taxInput: 0,
            rentInput: 0,
            tax: 0,
            rent: 0,
            percentageForCalculate: 1,
            allPurchaseTaxTotal: 0
        };
    },
    created() {
        this.title = "Declaración Mensual SUNAT";
        this.$store.commit("setConfiguration", this.configuration);
        this.loadConfiguration();
    },
    mounted() {
        this.initForm();
    },
    methods: {
        calcPerCoef() {
            if (isNaN(this.coefficient)) {
                this.coefficient = 0;
                this.$message.error("El coeficiente debe ser un número");
            }

            this.percentageForCalculate =
                this.coefficient > this.percentage
                    ? this.coefficient
                    : this.percentage;

            this.rentTotal = _.round(
                this.saleTotal * (this.percentageForCalculate / 100)
            );
            this.calculeTax(true);
        },
        changeReg() {
            this.percentage = this.reg == 9 ? 1 : 1.5;
            this.calcPerCoef();
        },
        movePage(num) {
            switch (num) {
                case 1:
                    this.activeName = "1";
                    break;
                case 2:
                    this.activeName = "2";
                    break;
                default:
                    this.activeName = "3";
                    break;
            }
        },
        calculeTax(isRent = false) {
            if (isRent) {
                this.rent = this.rentTotal - this.rentInput;
            } else {
                this.tax = this.resultTax - this.taxInput;
            }
        },
        async getRecords() {
            try {
                this.loading = true;
                const response = await this.$http(
                    `${this.resource}/records?date=${this.form.period}`
                );
                if (response.status == 200) {
                    const {
                        sale_total,
                        purchase_total,
                        purchase_total_10
                    } = response.data;
                    this.saleTotal = _.round(sale_total);
                    this.saleTaxTotal = _.round(this.saleTotal * 0.18);
                    this.purchaseTotal = _.round(purchase_total);
                    this.purchaseTaxTotal = _.round(this.purchaseTotal * 0.18);
                    this.purchaseTotal_10 = _.round(purchase_total_10);
                    this.purchaseTaxTotal_10 = _.round(
                        this.purchaseTotal_10 * 0.1
                    );
                    this.rentTotal = _.round(
                        this.saleTotal * (this.percentageForCalculate / 100)
                    );

                    this.resultTax =
                        this.saleTaxTotal -
                        (this.purchaseTaxTotal + this.purchaseTaxTotal_10);
                    this.tax = this.resultTax;
                    this.rent = this.rentTotal;
                    this.allPurchaseTaxTotal =
                        this.purchaseTaxTotal + this.purchaseTaxTotal_10;
                } else {
                    const { message } = response.data;
                    this.$message.error(message);
                }
            } catch (e) {
                console.log(e);
            } finally {
                this.loading = false;
            }
        },
        ...mapActions(["loadConfiguration"]),
        initForm() {
            this.errors = {};
            this.form = {
                currency_type_id: "PEN",
                igv: "1",
                sales: {
                    totalValue: 0,
                    totalIgv: 0,
                    total: 0
                },
                purchases: {
                    totalValue: 0,
                    totalIgv: 0,
                    total: 0
                },
                rent: {
                    coefficient: 0,
                    percentage: 0,
                    totalRent: 0,
                    total: 0
                },
                determination: { favorIgv: 0 }
            };
        }
    }
};
</script>

<template>
    <el-dialog
        :title="titleDialog"
        class="hheader"
        @open="open"
        @close="close"
        :visible="showDialog"
        width="80%"
        top="1vh"
        append-to-body
        v-loading="loading"
    >
        <div class="">
            <div class="row">
                <div class="col-md-4 ">
                    <el-card class="cardradius">
                        <div
                            class="mb-2 d-flex flex-column align-items-center jusfity-content-start"
                        >
                            <div
                                class="mb-2 overflow-hidden rounded-circle bg-primary d-flex align-items-center  jusfity-content-center text-center text-white"
                                style="width:100px;height:100px"
                            >
                                <template v-if="customer.name">
                                    <label
                                        class="text-center"
                                        v-if="!customer.photo_filename"
                                    >
                                        {{
                                            customer.name
                                                .split(" ")
                                                .map(word =>
                                                    word[0].toUpperCase()
                                                )
                                                .join("")
                                        }}
                                    </label>
                                    <img
                                        v-else
                                        class="img-fluid"
                                        style="max-width:100%;height:100%;"
                                        :src="
                                            `/storage/uploads/users/${
                                                customer.photo_filename
                                            }`
                                        "
                                    />
                                </template>
                            </div>
                            <span class="text-center">{{ customer.name }}</span>
                            <span class="text-muted">{{
                                customer.number
                            }}</span>
                        </div>

                        <div
                            class="mb-2 d-flex flex-column align-items-start jusfity-content-start"
                        >
                            <label for="data" class="text-muted mb-1"
                                >DATOS</label
                            >
                            <div class="mt-1 row w-100">
                                <div class="col-md-1 d-flex align-items-center">
                                    <el-tooltip content="Nombre Comercial">
                                        <i
                                            class="text-primary fas fa-user-check"
                                        ></i>
                                    </el-tooltip>
                                </div>
                                <div class="col-md-10">
                                    <span class="d-block">{{
                                        customer.trade_name || "N/A"
                                    }}</span>
                                </div>
                            </div>
                            <div class="mt-1 row w-100">
                                <div class="col-md-1 d-flex align-items-center">
                                    <el-tooltip content="Días de crédito">
                                        <i
                                            class="text-primary fas fa-credit-card"
                                        ></i>
                                    </el-tooltip>
                                </div>
                                <div class="col-md-10">
                                    <!-- <small>Días de crédito</small> -->
                                    <span class="d-block">{{
                                        customer.credit_days
                                    }}</span>
                                </div>
                            </div>
                            <div class="mt-1 row w-100">
                                <div class="col-md-1 d-flex align-items-center">
                                    <el-tooltip content="Código interno">
                                        <i
                                            class="text-primary fas fa-window-restore"
                                        ></i>
                                    </el-tooltip>
                                </div>
                                <div class="col-md-10">
                                    <span
                                        class="d-block"
                                        style="margin:0px;padding:0px;"
                                        >{{
                                            customer.internal_code || "N/A"
                                        }}</span
                                    >
                                </div>
                            </div>
                            <div class="mt-1 row w-100">
                                <div class="col-md-1 d-flex align-items-center">
                                    <el-tooltip content="Tipo de Cliente">
                                        <i
                                            class="text-primary fas fa-user-tag"
                                        ></i>
                                    </el-tooltip>
                                </div>
                                <div class="col-md-10">
                                    <span class="d-block">{{
                                        customer.person_type
                                            ? customer.person_type.description
                                            : "N/A"
                                    }}</span>
                                </div>
                            </div>
                            <div class="mt-1 row w-100">
                                <div class="col-md-1 d-flex align-items-center">
                                    <el-tooltip content="Código de Barra">
                                        <i
                                            class="text-primary fas fa-barcode"
                                        ></i>
                                    </el-tooltip>
                                </div>
                                <div class="col-md-10">
                                    <span class="d-block">{{
                                        customer.barcode || "N/A"
                                    }}</span>
                                </div>
                            </div>
                            <label for="data" class="text-muted mb-1 mt-3"
                                >CONTACTO</label
                            >

                            <div class="mt-1 row w-100">
                                <div class="col-md-1 d-flex align-items-center">
                                    <el-tooltip content="Teléfono">
                                        <i
                                            class="text-primary fas fa-phone"
                                        ></i>
                                    </el-tooltip>
                                </div>
                                <div class="col-md-10">
                                    <span class="d-block">{{
                                        customer.telephone || "N/A"
                                    }}</span>
                                </div>
                            </div>

                            <div class="mt-1 row w-100">
                                <div class="col-md-1 d-flex align-items-center">
                                    <el-tooltip content="Correo electrónico">
                                        <i
                                            class="text-primary fas fa-envelope"
                                        ></i>
                                    </el-tooltip>
                                </div>
                                <div class="col-md-10">
                                    <span class="d-block">{{
                                        customer.email || "N/A"
                                    }}</span>
                                </div>
                            </div>
                            <div class="mt-1 row w-100">
                                <div class="col-md-1 d-flex align-items-center">
                                    <el-tooltip content="Dirección">
                                        <i
                                            class="text-primary fas fa-map-marker-alt"
                                        ></i>
                                    </el-tooltip>
                                </div>
                                <div class="col-md-10">
                                    <span class="d-block">{{
                                        customer.address || "N/A"
                                    }}</span>
                                </div>
                            </div>
                            <label for="data" class="text-muted mb-1 mt-3"
                                >DESCUENTO</label
                            >
                            <div class="mt-1 row w-100">
                                <div class="col-md-1 d-flex align-items-center">
                                    <el-tooltip content="Tipo de descuento">
                                        <i
                                            class="text-primary fas fa-percent"
                                        ></i>
                                    </el-tooltip>
                                </div>
                                <div class="col-md-10">
                                    <span class="d-block">{{
                                        customer.discount_type == "01"
                                            ? "Mayorista"
                                            : "Minorista"
                                    }}</span>
                                </div>
                            </div>
                            <div class="mt-1 row w-100">
                                <div class="col-md-1 d-flex align-items-center">
                                    <el-tooltip content="Descuento (monto)">
                                        <i class="text-primary fas fa-tag"></i>
                                    </el-tooltip>
                                </div>
                                <div class="col-md-10">
                                    <span class="d-block">{{
                                        customer.discount_amount
                                    }}</span>
                                </div>
                            </div>
                        </div>
                    </el-card>
                    <div class="mt-1"></div>
                    <el-card v-if="customer.seller" class="cardradius">
                        <div class="row w-100">
                            <div
                                class="col-md-3 d-flex flex-column justify-content-center"
                            >
                                <div
                                    class="
                                    overflow-hidden
                                    rounded-circle bg-primary text-center text-white
                                    d-flex align-items-center justify-content-center text-center"
                                    style="width:80px;height:80px;"
                                >
                                    <template v-if="customer.seller">
                                        <label
                                            v-if="
                                                !customer.seller.photo_filename
                                            "
                                        >
                                            {{
                                                customer.seller.name
                                                    .split(" ")
                                                    .map(word =>
                                                        word[0].toUpperCase()
                                                    )
                                                    .join("")
                                            }}
                                        </label>
                                        <img
                                            v-else
                                            class="img-fluid"
                                            style="max-width:100%;height:100%;"
                                            :src="
                                                `/storage/uploads/users/${
                                                    customer.seller
                                                        .photo_filename
                                                }`
                                            "
                                        />
                                    </template>
                                </div>
                            </div>
                            <div
                                class=" col-md-5 d-flex  flex-column justify-content-center"
                            >
                                <label for="seller">
                                    <h5>
                                        {{ customer.seller.name }}
                                    </h5>
                                </label>
                                <label for="seller_number">
                                    <small>
                                        Vendedor
                                    </small>
                                </label>
                            </div>
                            <div
                                v-show="
                                    customer.seller.telephone ||
                                        customer.seller.personal_cell_phone
                                "
                                class="col-md-4 d-flex flex-column align-items-center justify-content-center"
                            >
                                <el-button size="mini" class="text-center">
                                    <div v-if="customer.seller.telephone">
                                        {{ customer.seller.telephone }}
                                    </div>
                                    <div
                                        class="mt-2"
                                        v-if="
                                            customer.seller
                                                .personal_cell_phone &&
                                                customer.seller
                                                    .personal_cell_phone !=
                                                    customer.seller.telephone
                                        "
                                    >
                                        {{
                                            customer.seller.personal_cell_phone
                                        }}
                                    </div>
                                </el-button>
                            </div>
                        </div>
                    </el-card>
                </div>
                <div class="col-md-8 ">
                    <div class="row" v-loading="loadingMonths">
                        <div
                            class="col-md-3"
                            v-for="(m, idx) in months"
                            :key="idx"
                        >
                            <el-card class="cardradius cardradius3">
                                <div
                                    class="d-flex flex-column align-items-center"
                                >
                                    <div
                                        class="m-2 rounded-circle border border-primary justify-content-center d-flex align-items-center text-primary"
                                        style="width:50px;height:50px"
                                    >
                                        <i
                                            class="text-primary fas fa-wallet"
                                        ></i>
                                    </div>

                                    <span for="month" class="d-block mt-2"
                                        >Mes</span
                                    >
                                    <span for="m" class="d-block m-1">
                                        {{ m.month }}
                                    </span>
                                    <table class="table">
                                        <tr>
                                            <td>
                                                <small>Facturas</small>
                                            </td>
                                            <td class="text-end">
                                                <small>
                                                    {{ m.f.toFixed(2) }}
                                                </small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <small>Boletas</small>
                                            </td>
                                            <td class="text-end">
                                                <small>
                                                    {{ m.b.toFixed(2) }}
                                                </small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <small>N/V</small>
                                            </td>
                                            <td class="text-end">
                                                <small>
                                                    {{ m.n.toFixed(2) }}
                                                </small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <small>TOTAL</small>
                                            </td>
                                            <td class="text-end">
                                                <small>
                                                    {{ m.total.toFixed(2) }}
                                                </small>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </el-card>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div
                            v-if="loaded"
                            class="col-md-3 mt-2 xx d-flex flex-column justify-content-start align-items-start"
                            v-loading="loadingCalendar"
                        >
                            <span class="text-primary">FECHAS DE COMPRA</span>
                            <calendar
                                :firstDayOfWeek="1"
                                :hasInput="false"
                                :value="value"
                                :changePane="changePane"
                                :onDrawDate="saleDays"
                                :onDayClick="onDayClick"
                            ></calendar>
                        </div>
                        <div class="col-md-9 mt-2">
                            <span class="text-primary"
                                >PRODUCTOS MÁS VENDIDOS</span
                            >
                            <div class=" scrollx">
                                <el-card
                                    v-for="(data, idx) in items"
                                    :key="idx"
                                >
                                    <div class="row">
                                        <div
                                            class="col-md-4 d-flex flex-column justify-content-center"
                                        >
                                            <img
                                                :src="
                                                    `${
                                                        !data.image.includes(
                                                            'no-disponible'
                                                        )
                                                            ? '/storage/uploads/items/'
                                                            : '/logo/'
                                                    }${data.image}`
                                                "
                                                :alt="idx"
                                                class="w-75 h-75"
                                            />
                                        </div>
                                        <div class="col-md-8">
                                            <button
                                                type="button"
                                                class="btn btn-outline-primary mb-1"
                                            >
                                                {{ data.description }}
                                            </button>
                                            <br />
                                            PRECIO: {{ data.price.toFixed(2) }}
                                            <br />
                                            STOCK: {{ data.stock }} <br />
                                            VENDIDOS: {{ data.total }} <br />
                                        </div>
                                    </div>
                                </el-card>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <el-dialog
            width="450px"
            :visible.sync="visible"
            append-to-body
            :close-on-click-modal="true"
            :title="title"
        >
            <table class="w-100 ">
                <thead>
                    <tr>
                        <th width="60%"></th>
                        <th width="40%"></th>
                    </tr>
                </thead>
                <tbody>
                    <template v-if="documents.f && documents.f.length != 0">
                        <tr>
                            <td colspan="2">
                                Facturas
                            </td>
                        </tr>
                        <tr v-for="f in documents.f" :key="f.description">
                            <td>{{ f.description }}</td>

                            <td>
                                {{
                                    f.currency_type_id == "PEN" ? "S/. " : "$ "
                                }}
                                {{ f.total.toFixed(2) }}
                                <small>
                                    {{ showExchange(f) }}
                                </small>
                            </td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td>S/. {{ documents.f_total.toFixed(2) }}</td>
                        </tr>
                    </template>
                    <template v-if="documents.b && documents.b.length != 0">
                        <tr>
                            <td colspan="2">
                                Boletas
                            </td>
                        </tr>
                        <tr v-for="b in documents.b" :key="b.description">
                            <td>{{ b.description }}</td>

                            <td>
                                {{ b.currency_type_id == "PEN" ? "S/. " : "$ "
                                }}{{ b.total.toFixed(2) }}
                                <small>
                                    {{ showExchange(b) }}
                                </small>
                            </td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td>S/. {{ documents.b_total.toFixed(2) }}</td>
                        </tr>
                    </template>
                    <template v-if="documents.n && documents.n.length != 0">
                        <tr>
                            <td colspan="2">
                                Notas de venta
                            </td>
                        </tr>
                        <tr v-for="n in documents.n" :key="n.description">
                            <td>{{ n.description }}</td>

                            <td>
                                {{ n.currency_type_id == "PEN" ? "S/. " : "$ "
                                }}{{ n.total.toFixed(2) }}
                                <small>{{ showExchange(n) }}</small>
                            </td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td>S/. {{ documents.n_total.toFixed(2) }}</td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </el-dialog>
    </el-dialog>
</template>

<style>
.hheader .el-dialog__body {
    background: #f9f9f9 !important;
}
.scrollx {
    margin: 4px, 4px;
    padding: 4px;
    height: 460px;
    overflow-x: hidden;
    overflow-y: auto;
    text-align: justify;
}
/* .datepicker-inner {
    width: 440px;
}
.datepicker-body span {
    width: 60px;
} */

.datepicker-dateRange-item-active:hover,
.datepicker-dateRange-item-active {
    background-color: #2499e3 !important;
}
.xx .datepicker {
    padding: 0px;
}
.xx .datepicker-wrapper {
    width: 100% !important;
}
.xx .datepicker-wrapper .datepicker-popup .datepicker-inner {
    width: 100% !important;
}
.xx .datepicker-ctrl p,
.datepicker-ctrl span,
.datepicker-body span {
    width: 13% !important;
}
.cardradius.el-card.is-always-shadow {
    padding: 5px;
    border-radius: 12px;
}
.cardradius3 .el-card__body {
    padding: 0px;
}
</style>
<script>
import moment from "moment";
import Calendar from "vue2-slot-calendar";

export default {
    components: { Calendar },
    props: ["number", "showDialog"],
    data() {
        return {
            calendarData: {},
            loadingCalendar: false,
            calendarConfigs: {
                sundayStart: false,
                dateFormat: "dd/mm/yyyy",
                isDatePicker: false,
                isDateRange: false
            },
            date: null,
            customer: {},
            loading: false,
            titleDialog: "Historial de Clientes",
            loadingMonths: false,
            months: [],
            value: moment().format("MM/DD/YYYY"),
            loaded: false,
            dates: [],
            ft: [],
            bv: [],
            nv: [],
            documents: {},
            visible: false,
            title: "",
            items: []
        };
    },

    created() {},
    mounted() {
        //Selecciona todos los elementos que tienen la clase "mi-clase"
        // this.translations("es");
    },
    computed: {
        text() {
            return this.translations(this.lang);
        }
    },
    methods: {
        showExchange(document) {
            if (document.currency_type_id == "PEN") {
                return "";
            }
            return " | TC: " + document.exchange_rate_sale;
        },
        async getItems() {
            let { id } = this.customer;
            try {
                const response = await this.$http(
                    `/reports/customers/items?id=${id}`
                );
                if (response.status == 200) {
                    this.items = Object.values(response.data.items);
                    this.items.sort((a, b) => b.total - a.total);
                }
            } catch (e) {
                console.log(e);
            } finally {
            }
        },

        onDayClick(d) {
            if (
                this.dates.some(
                    x =>
                        d.getFullYear() === x.getFullYear() &&
                        d.getMonth() === x.getMonth() &&
                        d.getDate() === x.getDate()
                )
            ) {
                this.documents.f = this.ft.filter(
                    x =>
                        d.getFullYear() === x.date.getFullYear() &&
                        d.getMonth() === x.date.getMonth() &&
                        d.getDate() === x.date.getDate()
                );
                this.documents.b = this.bv.filter(
                    x =>
                        d.getFullYear() === x.date.getFullYear() &&
                        d.getMonth() === x.date.getMonth() &&
                        d.getDate() === x.date.getDate()
                );
                this.documents.n = this.nv.filter(
                    x =>
                        d.getFullYear() === x.date.getFullYear() &&
                        d.getMonth() === x.date.getMonth() &&
                        d.getDate() === x.date.getDate()
                );

                this.documents.n_total = this.documents.n.reduce(
                    (a, b) =>
                        a +
                        (b.currency_type_id == "PEN"
                            ? Number(b.total)
                            : Number(b.total) * Number(b.exchange_rate_sale)),
                    0
                );
                this.documents.f_total = this.documents.f.reduce(
                    (a, b) =>
                        a +
                        (b.currency_type_id == "PEN"
                            ? Number(b.total)
                            : Number(b.total) * Number(b.exchange_rate_sale)),
                    0
                );
                this.documents.b_total = this.documents.b.reduce(
                    (a, b) =>
                        a +
                        (b.currency_type_id == "PEN"
                            ? Number(b.total)
                            : Number(b.total) * Number(b.exchange_rate_sale)),
                    0
                );
                let { n_total, f_total, b_total } = this.documents;
                this.documents.total = n_total + b_total + f_total;
                this.title = `${d.toLocaleDateString()} - T. General: ${this.documents.total.toFixed(
                    2
                )}`;
                this.visible = true;
            }
        },
        async changePane(y, m, pa) {
            // this.value = null;
            let today = new Date();
            
            await this.getDocsMonth(m + 1, y);
this.changeTodayClass(today.getMonth() == m);
            //get the month of the current date and compare with "m"
        },
        saleDays(e) {
            if (e == undefined) return;
            if (this.dates.length != 0) {
                let dates = this.dates.filter(
                    d => d.getMonth() == e.date.getMonth()
                );
                if (
                    dates.some(
                        d =>
                            d.getFullYear() === e.date.getFullYear() &&
                            d.getMonth() === e.date.getMonth() &&
                            d.getDate() === e.date.getDate()
                    )
                ) {
                    e.sclass = `${e.sclass} text-primary font-weight-bolder`;
                }
            }
        },
        //customers/month
        async getDocsMonth(m, y) {
            this.dates = [];
            let { id } = this.customer;

            try {
                this.loadingCalendar = true;
                const response = await this.$http(
                    `/reports/customers/month?month=${m}&year=${y}&id=${id}`
                );
                if (response.status == 200) {
                    const { f, b, n } = response.data;
                    this.ft = f.map(x => {
                        let newDate = new Date(x.date);
                        newDate.setHours(newDate.getHours() + 10);
                        return {
                            ...x,
                            date: newDate
                        };
                    });
                    this.bv = b.map(x => {
                        let newDate = new Date(x.date);
                        newDate.setHours(newDate.getHours() + 10);
                        return {
                            ...x,
                            date: newDate
                        };
                    });
                    this.nv = n.map(x => {
                        let newDate = new Date(x.date);
                        newDate.setHours(newDate.getHours() + 10);
                        return {
                            ...x,
                            date: newDate
                        };
                    });
                    this.dates = [
                        ...f.map(f => f.date),
                        ...b.map(b => b.date),
                        ...n.map(n => n.date)
                    ];

                    this.loaded = true;
                }

                this.dates = [...new Set(this.dates)].map(d => {
                    let date = new Date(d);
                    date.setHours(date.getHours() + 10);
                    return date;
                });
            } catch (e) {
                console.log(e);
            } finally {
                this.loadingCalendar = false;
            }
        },
        //photo_filename
        //storage/uploads/users/
        async getDetail() {
            try {
                this.loading = true;

                const response = await this.$http(
                    `/reports/customers/detail/${this.number}`
                );
                const {
                    data: { customer }
                } = response;
                this.customer = customer;
            } catch (e) {
                console.log(e);
            } finally {
                this.loading = false;
            }
        },
        async getDataMonths() {
            let months = this.getLastFourMonths();
            let { id } = this.customer;
            if (!id) return;
            months = months.join("_");

            try {
                this.loadingMonths = true;
                const response = await this.$http(
                    `/reports/customers/months?months=${months}&id=${id}`
                );
                const {
                    data: { result }
                } = response;
                this.months = result.reverse().map(r => ({
                    ...r,
                    total: r.f + r.n + r.b
                }));
            } catch (e) {
                console.log(e);
            } finally {
                this.loadingMonths = false;
            }
        },

        getLastFourMonths() {
            let today = moment();
            let months = [];

            for (var i = 0; i < 4; i++) {
                let month = today.clone().subtract(i, "months");
                months.push(month.format("YYYY-MM-DD"));
            }
            return months;
        },
        changeTodayClass(isCurrentMonth = false) {
            let elements = document.querySelectorAll(
                ".day-cell.datepicker-dateRange-item-active"
            );
            if (elements.length > 0) {
                let element = elements[0];
                if (element) {
                    element.classList.remove(
                        "datepicker-dateRange-item-active"
                    );
                    if (isCurrentMonth) {
                        element.classList.add("bg-primary","text-white");
                    }
                }
            }
        },
        async open() {
            this.loaded = false;
            this.dates = [];
            let date = moment();
            let m = date.get("month");
            let y = date.get("year");
            await this.getDetail();
            await this.getDocsMonth(m + 1, y);
            await this.getDataMonths();
            this.changeTodayClass();
            await this.getItems();
        },
        close() {
            this.$emit("update:showDialog", false);
        }
    }
};
</script>

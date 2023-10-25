<template>
    <div>
        <div class="page-header pr-0">
            <h2>
                <a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a>
            </h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Cajas</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <template v-if="open_cash">
                    <button
                        v-if="
                            !configuration.cash_report_hidden ||
                            typeUser == 'admin'
                        "
                        type="button"
                        class="btn btn-custom btn-sm mt-2 mr-2"
                        @click.prevent="clickDownloadGeneral()"
                    >
                        <i class="fas fa-shopping-cart"></i> Reporte general
                    </button>

                    <button
                        type="button"
                        class="btn btn-custom btn-sm mt-2 mr-2"
                        @click.prevent="clickCreate()"
                    >
                        <i class="fas fa-shopping-cart"></i> Aperturar caja
                        chica POS
                    </button>
                </template>
                <!-- <template v-else>                 -->
                <!-- <button type="button" class="btn btn-success btn-sm  mt-2 mr-2" @click.prevent="clickOpenPos()"><i class="fas fa-shopping-cart" ></i> Aperturar punto de venta</button> -->
                <!-- </template> -->
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header">
                <h3 class="my-0">Listado de cajas</h3>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th># Referencia</th>
                        <th>Vendedor</th>
                        <th class="text-center">Apertura</th>
                        <th class="text-center">Cierre</th>
                        <th>Saldo inicial</th>
                        <th v-if="typeUser == 'admin'">Saldo final</th>
                        <th>Saldo real</th>
                        <!-- <th>Ingreso</th> -->
                        <!-- <th>Egreso</th> -->
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>

                    <tr></tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td>{{ row.reference_number }}</td>
                        <td>{{ row.user }}</td>
                        <td class="text-center">{{ row.opening }}</td>
                        <td class="text-center">{{ row.closed }}</td>
                        <td>{{ row.beginning_balance }}</td>
                        <td v-if="typeUser == 'admin'">
                            {{ row.final_balance }}
                        </td>
                        <!-- <td>{{ row.income }}</td>
                        <td>{{ row.expense }}</td> -->
                        <td>{{ row.money_count }}</td>
                        <td>{{ row.state_description }}</td>
                        <td class="text-center">
                            <!-- <button type="button" class="btn waves-effect waves-light btn-sm btn-primary" @click.prevent="clickDownload(row.id)">Reporte</button> -->

                            <div class="btn-group flex-wrap">
                                <button
                                    type="button"
                                    class="btn waves-effect waves-light btn-sm btn-primary dropdown-toggle"
                                    data-toggle="dropdown"
                                    aria-expanded="false"
                                    v-if="
                                        !configuration.cash_report_hidden ||
                                        typeUser == 'admin'
                                    "
                                >
                                    Reporte <span class="caret"></span>
                                </button>
                                <div
                                    class="dropdown-menu"
                                    role="menu"
                                    x-placement="bottom-start"
                                    style="
                                        position: absolute;
                                        will-change: transform;
                                        top: 0px;
                                        left: 0px;
                                        transform: translate3d(0px, 42px, 0px);
                                    "
                                >
                                    <a
                                        class="dropdown-item text-1"
                                        href="#"
                                        @click.prevent="
                                            clickDownloadReport(row.id, 'a4')
                                        "
                                        >PDF A4</a
                                    >
                                    <a
                                        class="dropdown-item text-1"
                                        href="#"
                                        @click.prevent="
                                            clickDownloadReport(
                                                row.id,
                                                'ticket'
                                            )
                                        "
                                        >PDF Ticket</a
                                    >
                                    <a
                                        class="dropdown-item text-1"
                                        href="#"
                                        @click.prevent="
                                            clickDownloadReport(
                                                row.id,
                                                'ticket',
                                                '58'
                                            )
                                        "
                                        >PDF Ticket 58</a
                                    >
                                    <a
                                        class="dropdown-item text-1"
                                        href="#"
                                        @click.prevent="
                                            clickDownloadReport(
                                                row.id,
                                                'simple_a4'
                                            )
                                        "
                                        >Simple A4</a
                                    >
                                    <a
                                        class="dropdown-item text-1"
                                        href="#"
                                        @click.prevent="
                                            clickDownloadReport(row.id, 'excel')
                                        "
                                        >Excel</a
                                    >
                                    <!-- <a class="dropdown-item text-1" href="#" @click.prevent="clickDownloadProducts(row.id, 'excel')">Excel</a> -->

                                    <a
                                        class="dropdown-item text-1"
                                        href="#"
                                        @click.prevent="
                                            clickReportSummaryDailyOperations(
                                                row.id
                                            )
                                        "
                                        >Resumen de Operaciones Diarias</a
                                    >

                                    <el-tooltip
                                        class="item"
                                        content="Reporte general de caja asociado a los pagos al contado con destino caja"
                                        effect="dark"
                                        placement="right-end"
                                    >
                                        <a
                                            class="dropdown-item text-1"
                                            href="#"
                                            @click.prevent="
                                                clickReportCashWithPayments(
                                                    row.id
                                                )
                                            "
                                            >Reporte general caja V2</a
                                        >
                                    </el-tooltip>
                                </div>
                            </div>

                            <!-- <button type="button" class="btn waves-effect waves-light btn-sm btn-primary" @click.prevent="clickDownloadProducts(row.id)">Reporte Productos</button> -->

                            <div class="btn-group flex-wrap">
                                <button
                                    v-if="
                                        !configuration.cash_report_hidden ||
                                        typeUser == 'admin'
                                    "
                                    type="button"
                                    class="btn waves-effect waves-light btn-sm btn-primary dropdown-toggle"
                                    data-toggle="dropdown"
                                    aria-expanded="false"
                                >
                                    Reporte Efectivo <span class="caret"></span>
                                </button>
                                <div
                                    v-if="
                                        !configuration.cash_report_hidden ||
                                        typeUser == 'admin'
                                    "
                                    class="dropdown-menu"
                                    role="menu"
                                    x-placement="bottom-start"
                                    style="
                                        position: absolute;
                                        will-change: transform;
                                        top: 0px;
                                        left: 0px;
                                        transform: translate3d(0px, 42px, 0px);
                                    "
                                >
                                    <!-- <a class="dropdown-item text-1" href="#" @click.prevent="clickDownloadProducts(row.id, 'pdf')">PDF</a> -->
                                    <a
                                        class="dropdown-item text-1"
                                        href="#"
                                        @click.prevent="
                                            clickDownloadReportCash(
                                                row.id,
                                                'excel'
                                            )
                                        "
                                        >Excel</a
                                    >
                                    <a
                                        class="dropdown-item text-1"
                                        href="#"
                                        @click.prevent="
                                            clickDownloadReportIncomeEgress(
                                                row.id
                                            )
                                        "
                                        >Ingresos y egresos</a
                                    >

                                    <el-tooltip
                                        class="item"
                                        content="Ingresos en efectivo con destino caja - Disponible para facturas, boletas y notas de venta"
                                        effect="dark"
                                        placement="right-end"
                                    >
                                        <a
                                            class="dropdown-item text-1"
                                            href="#"
                                            @click.prevent="
                                                clickReportPaymentsAssociatedCash(
                                                    row.id
                                                )
                                            "
                                            >Pagos asociados a caja</a
                                        >
                                    </el-tooltip>
                                </div>
                            </div>

                            <div class="btn-group flex-wrap">
                                <button
                                    type="button"
                                    class="btn waves-effect waves-light btn-sm btn-primary dropdown-toggle"
                                    data-toggle="dropdown"
                                    aria-expanded="false"
                                    v-if="
                                        !configuration.cash_report_hidden ||
                                        typeUser == 'admin'
                                    "
                                >
                                    Reporte Productos
                                    <span class="caret"></span>
                                </button>
                                <div
                                    class="dropdown-menu"
                                    role="menu"
                                    x-placement="bottom-start"
                                    style="
                                        position: absolute;
                                        will-change: transform;
                                        top: 0px;
                                        left: 0px;
                                        transform: translate3d(0px, 42px, 0px);
                                    "
                                >
                                    <a
                                        class="dropdown-item text-1"
                                        href="#"
                                        @click.prevent="
                                            clickDownloadProducts(row.id, 'pdf')
                                        "
                                        >Punto de venta - PDF</a
                                    >
                                    <a
                                        class="dropdown-item text-1"
                                        href="#"
                                        @click.prevent="
                                            clickDownloadProducts(
                                                row.id,
                                                'excel'
                                            )
                                        "
                                        >Punto de venta - Excel</a
                                    >
                                    <a
                                        class="dropdown-item text-1"
                                        href="#"
                                        @click.prevent="
                                            clickDownloadProducts(
                                                row.id,
                                                'pdf',
                                                true
                                            )
                                        "
                                        >Venta rápida - PDF</a
                                    >
                                </div>
                            </div>

                            <button
                                v-if="
                                    !configuration.cash_report_hidden ||
                                    typeUser == 'admin'
                                "
                                type="button"
                                class="btn waves-effect waves-light btn-sm btn-success"
                                @click.prevent="
                                    clickDownloadIncomeSummary(row.id)
                                "
                            >
                                R. Ingreso
                            </button>

                            <template v-if="row.state">
                                <button
                                    type="button"
                                    class="btn waves-effect waves-light btn-sm btn-warning"
                                    @click.prevent="closeCashDialog(row.id)"
                                >
                                    Cerrar caja
                                </button>
                                <button
                                    v-if="typeUser === 'admin'"
                                    type="button"
                                    class="btn waves-effect waves-light btn-sm btn-info"
                                    @click.prevent="clickCreate(row.id)"
                                >
                                    Editar
                                </button>
                                <button
                                    v-if="typeUser === 'admin'"
                                    type="button"
                                    class="btn waves-effect waves-light btn-sm btn-danger"
                                    @click.prevent="clickDelete(row.id)"
                                >
                                    Eliminar
                                </button>
                            </template>
                            <template v-else>
                                <button
                                    type="button"
                                    class="btn waves-effect waves-light btn-sm btn-warning"
                                    @click.prevent="clickReOpenCash(row.id)"
                                >
                                    Volver abrir caja
                                </button>
                            </template>

                            <button
                                v-if="
                                    !configuration.cash_report_hidden ||
                                    typeUser == 'admin'
                                "
                                type="button"
                                class="btn waves-effect waves-light btn-sm btn-info"
                                @click.prevent="clickOptions(row.id)"
                            >
                                C. Electrónico
                            </button>
                        </td>
                    </tr>
                </data-table>
            </div>
        </div>
        <cash-form
            :showDialog.sync="showDialog"
            :typeUser="typeUser"
            :recordId="recordId"
        ></cash-form>

        <cash-options
            :showDialog.sync="showDialogOptions"
            :recordId="recordId"
        ></cash-options>

        <el-dialog
            append-to-body
            :visible.sync="showDialogClose"
            @close="showDialogClose = false"
            title="Cerrar la caja"
            width="350px"
        >
            <p>¿Desea indicar el monto con el que cierra caja?</p>

            <div class="row">
                <div class="col-12">
                    <label for="countmoney"></label>
                    <el-input
                        class="w-100 text-right"
                        type="number"
                        v-model="countMoney"
                        placeholder="Ingrese el monto"
                    >
                    </el-input>
                </div>
            </div>
            <span slot="footer" class="dialog-footer">
                <el-button @click="showDialogClose = false">Cerrar</el-button>
                <el-button type="primary" @click="closeCash()"
                    >Guardar</el-button
                >
            </span>
        </el-dialog>
    </div>
</template>
<style>
.w-100.text-right.el-input.el-input--small .el-input__inner {
    width: 100%;
    text-align: right;
}
</style>
<script>
import DataTable from "../../../components/DataTable.vue";
import { deletable } from "../../../mixins/deletable";
import CashForm from "./form.vue";
import CashOptions from "./partials/options.vue";

export default {
    mixins: [deletable],
    components: { DataTable, CashForm, CashOptions },
    props: ["typeUser", "configuration"],
    data() {
        return {
            countMoney: 0,
            showDialogClose: false,
            showDialog: false,
            showDialogOptions: false,
            open_cash: true,
            resource: "cash",
            recordId: null,
            cash: null,
        };
    },
    async created() {
        console.log(this.typeUser);
        /*await this.$http.get(`/${this.resource}/opening_cash`)
                .then(response => {
                    this.cash = response.data.cash
                    this.open_cash = (this.cash) ? false : true
                })*/
        /*this.$eventHub.$on('openCash', () => {
                this.open_cash = false
            })*/
    },
    methods: {
        closeCashDialog(id) {
            this.recordId = id;
            this.showDialogClose = true;
        },
        async closeCash() {
            try {
                const response = await this.$http.get(
                    `/${this.resource}/close/${this.recordId}?countMoney=${this.countMoney}`
                );

                if (response.data.success) {
                    this.$eventHub.$emit("reloadData");
                    this.open_cash = true;
                    this.$message.success(response.data.message);
                    this.showDialogClose = false;
                } else {
                    console.log(response);
                }
            } catch (e) {
                console.log(e);
            }
        },
        createRegisterReopen(instance, done) {
            instance.confirmButtonLoading = true;
            instance.confirmButtonText = "Abriendo caja...";

            this.$http
                .get(`/${this.resource}/re_open/${this.recordId}`)
                .then((response) => {
                    if (response.data.success) {
                        this.$eventHub.$emit("reloadData");
                        this.open_cash = true;
                        this.$message.success(response.data.message);
                    } else {
                        console.log(response);
                    }
                })
                .catch((error) => {
                    console.log(error);
                })
                .then(() => {
                    instance.confirmButtonLoading = false;
                    instance.confirmButtonText = "Iniciar prueba";
                    done();
                });
        },
        clickReOpenCash(recordId) {
            this.recordId = recordId;
            const h = this.$createElement;
            this.$msgbox({
                title: "Volver abrir caja chica POS",
                type: "warning",
                message: h("p", null, [
                    h(
                        "p",
                        { style: "text-align: justify; font-size:15px" },
                        "¿Está seguro de volver abrir la caja?"
                    ),
                ]),

                showCancelButton: true,
                confirmButtonText: "Abrir",
                cancelButtonText: "Cancelar",
                beforeClose: (action, instance, done) => {
                    if (action === "confirm") {
                        this.createRegisterReopen(instance, done);
                    } else {
                        done();
                    }
                },
            })
                .then((action) => {})
                .catch((action) => {});
        },
        clickOptions(recordId) {
            this.showDialogOptions = true;
            this.recordId = recordId;
        },
        clickDownloadReport(id, template, mm = 80) {
            if (template == "ticket") {
                window.open(
                    `/${this.resource}/report-${template}/${id}/${mm}`,
                    "_blank"
                );
            } else if (template == "simple_a4") {
                window.open(
                    `/${this.resource}/simple/report-a4/${id}/`,
                    "_blank"
                );
            } else {
                window.open(
                    `/${this.resource}/report-${template}/${id}`,
                    "_blank"
                );
            }
        },
        clickDownload(id) {
            window.open(`/${this.resource}/report/${id}`, "_blank");
        },
        clickDownloadIncomeSummary(id) {
            window.open(
                `/${this.resource}/report/income-summary/${id}`,
                "_blank"
            );
        },
        clickCreate(recordId = null) {
            this.recordId = recordId;
            this.showDialog = true;
        },
        clickCloseCash(recordId) {
            this.recordId = recordId;
            const h = this.$createElement;
            this.$msgbox({
                title: "Cerrar caja chica POS",
                type: "warning",
                message: h("p", null, [
                    h(
                        "p",
                        { style: "text-align: justify; font-size:15px" },
                        "¿Está seguro de cerrar la caja?"
                    ),
                ]),

                showCancelButton: true,
                confirmButtonText: "Cerrar",
                cancelButtonText: "Cancelar",
                beforeClose: (action, instance, done) => {
                    if (action === "confirm") {
                        this.createRegister(instance, done);
                    } else {
                        done();
                    }
                },
            })
                .then((action) => {})
                .catch((action) => {});
        },
        createRegister(instance, done) {
            instance.confirmButtonLoading = true;
            instance.confirmButtonText = "Cerrando caja...";

            this.$http
                .get(`/${this.resource}/close/${this.recordId}`)
                .then((response) => {
                    if (response.data.success) {
                        this.$eventHub.$emit("reloadData");
                        this.open_cash = true;
                        this.$message.success(response.data.message);
                    } else {
                        console.log(response);
                    }
                })
                .catch((error) => {
                    console.log(error);
                })
                .then(() => {
                    instance.confirmButtonLoading = false;
                    instance.confirmButtonText = "Iniciar prueba";
                    done();
                });
        },
        clickOpenPos() {
            window.open("/pos");
        },
        clickDelete(id) {
            this.destroy(`/${this.resource}/${id}`).then(() =>
                this.$eventHub.$emit("reloadData")
            );
        },
        clickDownloadGeneral() {
            window.open(`/${this.resource}/report`, "_blank");
        },
        clickDownloadProducts(id, type, is_garage = false) {
            if (type == "excel") {
                window.open(
                    `/${this.resource}/report/products-excel/${id}`,
                    "_blank"
                );
                return;
            }

            window.open(
                `/${this.resource}/report/products/${id}/${is_garage}`,
                "_blank"
            );
            // window.open(`/${this.resource}/report/products/${id}`, '_blank');
        },
        clickDownloadReportCash(id, type) {
            if (type == "excel") {
                window.open(
                    `/${this.resource}/report/cash-excel/${id}`,
                    "_blank"
                );
                return;
            }

            window.open(`/${this.resource}/report/products/${id}`, "_blank");
        },
        clickDownloadReportIncomeEgress(id) {
            window.open(
                `/${this.resource}/report-cash-income-egress/${id}`,
                "_blank"
            );
        },
        clickReportSummaryDailyOperations(id) {
            window.open(
                `/cash-reports/summary-daily-operations/${id}`,
                "_blank"
            );
        },
        clickReportPaymentsAssociatedCash(id) {
            window.open(
                `/cash-reports/payments-associated-cash/${id}`,
                "_blank"
            );
        },
        clickReportCashWithPayments(id) {
            window.open(`/cash-reports/general-with-payments/${id}`, "_blank");
        },
    },
};
</script>

<template>
    <div>
        <div class="page-header pr-0">
            <h2>
                <a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a>
            </h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Letras de cambio</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <a
                    href="#"
                    @click.prevent="openGenerateDialog"
                    class="btn btn-custom btn-sm mt-2 mr-2"
                    ><i class="fa fa-plus-circle"></i> Nuevo</a
                >
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-body">
                <data-table ref="dataTable" :resource="resource">
                    <tr slot="heading">
                        <th>#</th>

                        <th>Cliente</th>
                        <th>Letra de cambio</th>
                        <!-- <th>Estado</th> -->

                        <th class="text-center">Moneda</th>
                        <th class="text-end">F. Vencimiento</th>

                        <th class="text-center">Por pagar</th>

                        <th class="text-center">Comprobantes</th>

                        <th class="text-center">Pagos</th>
                        <th></th>
                    </tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>

                        <td>
                            {{ row.customer_name }}<br /><small
                                v-text="row.customer_number"
                            ></small>
                        </td>
                        <td>{{ row.full_number }}</td>
                        <!-- <td>{{ row.state_type_description }}</td> -->

                        <td class="text-center">{{ row.currency_type_id }}</td>

                        <td class="text-end">
                            {{ row.date_of_due }}
                        </td>

                        <td class="text-center">
                            {{ row.total }}
                        </td>
                        <td class="text-center">
                            <template v-for="(document, i) in row.documents">
                                <label
                                    :key="i"
                                    v-text="document.number_full"
                                    class="d-block"
                                ></label>
                            </template>
                        </td>

                        <td class="text-center">
                            <button
                                type="button"
                                style="min-width: 41px"
                                class="btn waves-effect waves-light btn-sm btn-primary"
                                @click.prevent="clickPayment(row.id)"
                            >
                                <i class="fas fa-money-bill-alt"></i>
                            </button>
                        </td>

                        <td class="text-end">
                            <div class="ms-1">
                                <button
                                    class="btn btn-light btn-sm"
                                    type="button"
                                    id="dropdownMenuButton"
                                    data-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                >
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <!-- <button
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="Editar"
                                        type="button"
                                        class="dropdown-item"
                                        @click.prevent="clickCreate(row.id)"
                                        v-if="
                                            row.btn_generate &&
                                                row.state_type_id != '11'
                                        "
                                    >
                                        Editar
                                    </button> -->
                                    <button
                                        type="button"
                                        class="dropdown-item"
                                        @click.prevent="clickDownload(row.id)"
                                    >
                                        Imprimir
                                    </button>
                                    <button
                                        type="button"
                                        class="dropdown-item"
                                        @click.prevent="
                                            clickKillDocument(row.id)
                                        "
                                        v-if="configuration.delete_documents"
                                    >
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                        </td>
                        <td v-if="configuration.college">
                            <el-button type="primary" @click="openPeriod(row)">
                                Editar
                            </el-button>
                        </td>
                    </tr>
                </data-table>
            </div>
        </div>

        <generate
            :show.sync="showDialogGenerate"
            @getRecords="getRecords"
        ></generate>
        <payments-dialog
            :showDialog.sync="showDialogPayments"
            :documentId="recordId"
            :configuration="configuration"
        ></payments-dialog>
    </div>
</template>

<script>
import DataTable from "../../../components/DataTable.vue";
import PaymentsDialog from "./partials/payments.vue";
import Generate from "./generate.vue";
import { deletable } from "../../../mixins/deletable";

import { mapActions, mapState } from "vuex/dist/vuex.mjs";

export default {
    props: ["soapCompany", "typeUser", "configuration"],
    mixins: [deletable],
    components: {
        DataTable,
        Generate,
        PaymentsDialog,
    },
    computed: {
        ...mapState(["config"]),
    },
    data() {
        return {
            currentDocument: null,
            showPeriod: false,
            showModalGenerateCPE: false,
            showMigrateNv: false,
            resource: "bill-of-exchange",
            showDialogGenerate: false,
            saleNotesNewId: null,
            showDialogPayments: false,
            recordId: null,
            columns: {
                due_date: {
                    title: "Fecha de Vencimiento",
                    visible: false,
                },
                exchange_rate_sale: {
                    title: "Tipo de cambio",
                    visible: false,
                },
                total_free: {
                    title: "T.Gratuito",
                    visible: false,
                },
                total_exportation: {
                    title: "T.Exportación",
                    visible: false,
                },
                total_unaffected: {
                    title: "T.Inafecto",
                    visible: false,
                },
                total_exonerated: {
                    title: "T.Exonerado",
                    visible: false,
                },
                total_taxed: {
                    title: "T.Gravado",
                    visible: false,
                },
                total_igv: {
                    title: "T.IGV",
                    visible: false,
                },
                paid: {
                    title: "Estado de Pago",
                    visible: false,
                },
                type_period: {
                    title: "Tipo Periodo",
                    visible: true,
                },
                quantity_period: {
                    title: "Cantidad Periodo",
                    visible: true,
                },
                license_plate: {
                    title: "Placa",
                    visible: true,
                },
                total_paid: {
                    title: "Pagado",
                    visible: false,
                },
                total_pending_paid: {
                    title: "Por pagar",
                    visible: false,
                },
                seller_name: {
                    title: "Vendedor",
                    visible: false,
                },
                recurrence: {
                    title: "Recurrencia",
                    visible: false,
                },
                region: {
                    title: "Region",
                    visible: false,
                },
                date_payment: {
                    title: "Fecha de pago",
                    visible: false,
                },
            },
            // showDialogDeleteRelationInvoice: false,
            // dataDeleteRelation: {
            //     documents: {},
            //     id: ''
            // }
        };
    },
    created() {
        this.loadConfiguration();
    },
    filters: {},
    methods: {
        async clickKillDocument(id) {
            await this.$confirm(
                "¿Estás seguro de eliminar este registro?",
                "Advertencia",
                {
                    confirmButtonText: "Aceptar",
                    cancelButtonText: "Cancelar",
                    type: "warning",
                }
            )
                .then(async () => {
                    try {
                        const response = await this.$http.delete(
                            `/bill-of-exchange/${id}`
                        );
                        if (response.data.success) {
                            this.$message.success(response.data.mmessage);
                            this.$refs.dataTable.getRecords();
                        } else {
                            this.$message.error(response.data.message);
                        }
                    } catch (e) {
                        this.$message.error(
                            "Ocurrió un error al eliminar el registro"
                        );
                    }
                })
                .catch(() => {
                    
                });
        },
        clickDownload(id) {
            window.open("/bill-of-exchange/pdf/" + id, "_blank");
        },
        getRecords() {
            this.$refs.dataTable.getRecords();
        },

        clickPayment(recordId) {
            this.recordId = recordId;
            this.showDialogPayments = true;
        },
        openGenerateDialog() {
            this.showDialogGenerate = true;
        },

        ...mapActions(["loadConfiguration"]),
    },
};
</script>

<template>
    <div>
        <div class="page-header pr-0">
            <h2>
                <a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a>
            </h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Tickets de encomienda</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <a
                    href="#"
                    @click.prevent="clickCreate()"
                    class="btn btn-custom btn-sm  mt-2 mr-2"
                    ><i class="fa fa-plus-circle"></i> Nuevo</a
                >
                <!-- <a
                    href="#"
                    @click.prevent="onOpenModalGenerateCPE"
                    class="btn btn-custom btn-sm  mt-2 mr-2"
                    >Generar comprobante desde múltiples Notas</a
                >
                  <a
                    href="#"
                    @click.prevent="onOpenModalGenerateGuie"
                    class="btn btn-custom btn-sm  mt-2 mr-2"
                    >Generar guía desde múltiples Notas</a
                >
                <a
                    href="#"
                    v-if="config.send_data_to_other_server === true"
                    @click.prevent="onOpenModalMigrateNv"
                    class="btn btn-custom btn-sm  mt-2 mr-2"
                    >Migrar Datos</a
                > -->
            </div>
        </div>
        <div class="card mb-0">
            <div class="data-table-visible-columns">
                <el-dropdown :hide-on-click="false">
                    <el-button type="primary">
                        Mostrar/Ocultar columnas<i
                            class="el-icon-arrow-down el-icon--right"
                        ></i>
                    </el-button>
                    <el-dropdown-menu slot="dropdown">
                        <el-dropdown-item
                            v-for="(column, index) in columns"
                            :key="index"
                        >
                            <el-checkbox
                                v-model="column.visible"
                                >{{ column.title }}</el-checkbox
                            >
                        </el-dropdown-item>
                    </el-dropdown-menu>
                </el-dropdown>
            </div>
            <div class="card-body">
                <data-table
                :isDriver="isDriver"
                 ref="dataTable" :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th class="text-end" v-if="columns.seller_name.visible">
                            Vendedor
                        </th>

                        <th class="text-center">Fecha Emisión</th>
                      
                        <th>Remitente</th>
                        <th>Destinatario</th>
                        <th>Ticket</th>
                        <th>Partida</th>
                        <th>Destino</th>
                        <th
                            class="text-end"
                            v-if="columns.exchange_rate_sale.visible"
                        >
                            T.C.
                        </th>
                        <th class="text-center">Moneda</th>
                     
                      
                        <th class="text-end">Total</th>

                 
                 

                       
                        <th
                            class="text-center"
                            v-if="columns.license_plate.visible"
                        >
                            Placa
                        </th>
                        <th class="text-end">Acciones</th>
                     
                    </tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td class="text-end" v-if="columns.seller_name.visible">
                            {{ row.seller_name }}
                        </td>

                        <td class="text-center">{{ row.date_of_issue }}</td>
                   
                        <td>
                            {{ row.sender_name }}<br /><small
                                v-text="row.sender_number"
                            ></small>
                        </td>
                      <td>
                            {{ row.issuer_name }}<br /><small
                                v-text="row.issuer_number"
                            ></small>
                        </td>
                        <td>{{ row.ticket }}</td>
                        <td>{{ row.departure }}</td>
                        <td>{{ row.arrival }}</td>
                        <td
                            class="text-center"
                            v-if="columns.exchange_rate_sale.visible"
                        >
                            {{ row.exchange_rate_sale }}
                        </td>
                        <td class="text-center">{{ row.currency_type_id }}</td>

                    
                        <td class="text-end">{{ row.total }}</td>

                     
                 

                      

                        <!-- <td class="text-end" v-if="columns.recurrence.visible">
                            <template
                                v-if="
                                    row.type_period && row.quantity_period > 0
                                "
                            >
                                <el-switch
                                    :disabled="row.apply_concurrency"
                                    v-model="row.enabled_concurrency"
                                    active-text="Si"
                                    inactive-text="No"
                                    @change="changeConcurrency(row)"
                                ></el-switch>
                            </template>
                        </td> -->

                        <td
                            class="text-end"
                            v-if="columns.license_plate.visible"
                        >
                            {{ row.license_plate }}
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
                                

                                    <button
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="Editar"
                                        type="button"
                                        class="dropdown-item"
                                        @click.prevent="clickCreate(row.id)"
                                     
                                    >
                                        <!--                                        <i class="dropdown-item fas fa-file-signature"></i>-->
                                        Editar
                                    </button>

                               

                                    <button
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="Imprimir"
                                        v-if="row.state_type_id != '11'"
                                        type="button"
                                        class="dropdown-item"
                                        @click.prevent="clickOptions(row.id)"
                                    >
                                        <!--                                <i class="dropdown-item fas fa-print"></i>-->
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
                       
                    </tr>
                </data-table>
            </div>
        </div>
        <!-- <el-dialog
            title="Eliminar Documento Relacionado"
            :visible="showDialogDeleteRelationInvoice"
            >
            <table>
                <tr v-for="(document, index) in dataDeleteRelation.documents" :key="index">
                    <td>
                        <el-button
                            type="button"
                            class="btn waves-effect waves-light btn-sm btn-danger"
                            @click.prevent="deleteRelationInvoice(row.id)">
                            <i class="fas fa-trash"></i>
                        </el-button>
                    </td>
                    <td>
                        {{document.number_full}}
                    </td>
                </tr>
            </table>
        </el-dialog> -->
        <period-modal
            :showDialog.sync="showPeriod"
            :document="currentDocument"
        ></period-modal>
        <sale-note-payments
            :showDialog.sync="showDialogPayments"
            :documentId="recordId"
        ></sale-note-payments>

        <sale-notes-options
            :showDialog.sync="showDialogOptions"
            :recordId="saleNotesNewId"
            :showClose="true"
            :configuration="config"
        ></sale-notes-options>

        <sale-note-generate
            :show.sync="showDialogGenerate"
            :recordId="recordId"
            :showGenerate="true"
            :showClose="false"
        ></sale-note-generate>
        <ModalGenerateCPE :show.sync="showModalGenerateCPE"></ModalGenerateCPE>
        <ModalGenerateGuie :show.sync="showModalGenerateGuie"></ModalGenerateGuie>
        <UploadToOtherServer
            :configuration="config"
            :showMigrate.sync="showMigrateNv"
        ></UploadToOtherServer>
    </div>
</template>

<script>
import PeriodModal from "../../../../../modules/Suscription/Resources/assets/js/components/PeriodModal.vue";

import DataTable from "../../../components/DataTable.vue";
import UploadToOtherServer from "./partials/upload_other_server_group.vue";
import SaleNotePayments from "./partials/payments.vue";
import SaleNotesOptions from "./partials/options.vue";
import SaleNoteGenerate from "./partials/option_documents";
import { deletable } from "../../../mixins/deletable";
import ModalGenerateCPE from "./ModalGenerateCPE";
import ModalGenerateGuie from "./ModalGenerateGuie";
import { mapActions, mapState } from "vuex/dist/vuex.mjs";

export default {
    props: ["soapCompany", "typeUser", "configuration"],
    mixins: [deletable],
    components: {
        PeriodModal,
        DataTable,
        SaleNotePayments,
        SaleNotesOptions,
        SaleNoteGenerate,
        ModalGenerateCPE,
        UploadToOtherServer,
        ModalGenerateGuie
    },
    computed: {
        ...mapState(["config"])
    },
    data() {
        return {
            showModalGenerateGuie:false,
            currentDocument: null,
            showPeriod: false,
            showModalGenerateCPE: false,
            showMigrateNv: false,
            resource: "package-handler",
            showDialogPayments: false,
            showDialogOptions: false,
            showDialogGenerate: false,
            saleNotesNewId: null,
            recordId: null,
            isDriver:true,
            columns: {
                // due_date: {
                //     title: "Fecha de Vencimiento",
                //     visible: false
                // },
                exchange_rate_sale: {
                    title: "Tipo de cambio",
                    visible: false
                },
                // total_free: {
                //     title: "T.Gratuito",
                //     visible: false
                // },
                // total_exportation: {
                //     title: "T.Exportación",
                //     visible: false
                // },
                // total_unaffected: {
                //     title: "T.Inafecto",
                //     visible: false
                // },
                // total_exonerated: {
                //     title: "T.Exonerado",
                //     visible: false
                // },
                // total_taxed: {
                //     title: "T.Gravado",
                //     visible: false
                // },
                // total_igv: {
                //     title: "T.IGV",
                //     visible: false
                // },
                // paid: {
                //     title: "Estado de Pago",
                //     visible: false
                // },
                // type_period: {
                //     title: "Tipo Periodo",
                //     visible: true
                // },
                // quantity_period: {
                //     title: "Cantidad Periodo",
                //     visible: true
                // },
                license_plate: {
                    title: "Placa",
                    visible: true
                },
                // total_paid: {
                //     title: "Pagado",
                //     visible: false
                // },
                // total_pending_paid: {
                //     title: "Por pagar",
                //     visible: false
                // },
                seller_name: {
                    title: "Vendedor",
                    visible: false
                },
                // recurrence: {
                //     title: "Recurrencia",
                //     visible: false
                // },
                // region: {
                //     title: "Region",
                //     visible: false
                // },
                // date_payment: {
                //     title: "Fecha de pago",
                //     visible: false
                // }
            }
            // showDialogDeleteRelationInvoice: false,
            // dataDeleteRelation: {
            //     documents: {},
            //     id: ''
            // }
        };
    },
    created() {
        this.loadConfiguration();
        this.$store.commit("setConfiguration", this.configuration);
        console.log(this.columns);
        // this.getColumnsToShow();
    },
    filters: {
        period(name) {
            let res = "";
            switch (name) {
                case "month":
                    res = "Mensual";
                    break;
                case "year":
                    res = "Anual";
                    break;
                default:
                    break;
            }

            return res;
        }
    },
    methods: {
        async clickKillDocument(id) {
            try {
                const confirm = await this.$confirm(
                    "¿Está seguro de eliminar el documento y todos los registros relacionados?",
                    "Advertencia",
                    {
                        confirmButtonText: "Eliminar",
                        cancelButtonText: "Cancelar",
                        type: "warning"
                    }
                );
                if (confirm) {
                    const response = await this.$http.get(
                        `/${this.resource}/kill/${id}`
                    );
                    console.log(response);
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                        this.$refs.dataTable.getRecords();
                    } else {
                        this.$message.error(response.data.message);
                    }
                }
            } catch (e) {
                return;
            }
        },
        openPeriod(row) {
            console.log(row);
            this.currentDocument = { ...row, document_type_id: "80" };
            this.showPeriod = true;
        },
        ...mapActions(["loadConfiguration"]),
        getColumnsToShow(updated) {
            this.$http
                .post("/validate_columns", {
                    columns: this.columns,
                    report: "sale_notes_index", // Nombre del reporte.
                    updated: updated !== undefined
                })
                .then(response => {
                    if (updated === undefined) {
                        let currentCols = response.data.columns;
                        if (currentCols !== undefined) {
                            this.columns = currentCols;
                        }
                    }
                })
                .catch(error => {
                    console.error(error);
                });
        },
        duplicate(id) {
            this.$http
                .post(`${this.resource}/duplicate`, { id })
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(
                            "Se guardaron los cambios correctamente."
                        );
                        this.$eventHub.$emit("reloadData");
                    } else {
                        this.$message.error("No se guardaron los cambios");
                    }
                })
                .catch(error => {});
            this.$eventHub.$emit("reloadData");
        },
        onOpenModalGenerateGuie(){
            this.showModalGenerateGuie = true;
            
        },
        onOpenModalGenerateCPE() {
            this.showModalGenerateCPE = true;
        },
        onOpenModalMigrateNv() {
            this.showMigrateNv = true;
        },
        clickDownload(external_id) {
            window.open(
                `/sale-notes/downloadExternal/${external_id}`,
                "_blank"
            );
        },
        clickOptions(recordId) {
            this.saleNotesNewId = recordId;
            this.showDialogOptions = true;
        },
        sendToServer(recordId) {
            this.$http
                .post("/sale-notes/UpToOther", { sale_note_id: recordId })
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                        this.$eventHub.$emit("reloadData");
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch(error => {
                    if (
                        error.response !== undefined &&
                        error.response.status !== undefined &&
                        error.response.status.errors !== undefined &&
                        error.response.status === 422
                    ) {
                        this.errors = error.response.data.errors;
                    } else {
                        console.log(error);
                    }
                })
                .then(() => {});
        },
        clickGenerate(recordId) {
            this.recordId = recordId;
            this.showDialogGenerate = true;
        },
        clickPayment(recordId) {
            this.recordId = recordId;
            this.showDialogPayments = true;
        },
        clickCreate(id = "") {
            location.href = `/package-handler/create/${id}`;
        },
        changeConcurrency(row) {
            this.$http
                .post(`/${this.resource}/enabled-concurrency`, row)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                        this.$eventHub.$emit("reloadData");
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    } else {
                        console.log(error);
                    }
                })
                .then(() => {});
        },
        clickVoided(id) {
            this.anular(`/${this.resource}/anulate/${id}`).then(() =>
                this.$eventHub.$emit("reloadData")
            );
        },
        // deleteRelationInvoice(saleNote) {
        //     this.dataDeleteRelation.documents = saleNote.documents
        //     this.dataDeleteRelation.id = saleNote.id
        //     this.showDialogDeleteRelationInvoice = true
        // },
        sendDeleteRelationInvoice(id) {
            this.$http
                .post(`${this.resource}/delete-relation-invoice`, { id })
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(
                            "Se ha eliminado el comprobante relacionado correctamente."
                        );
                        this.$eventHub.$emit("reloadData");
                    } else {
                        this.$message.error("No se guardaron los cambios");
                    }
                })
                .catch(error => {
                    console.log(error);
                });
            this.$eventHub.$emit("reloadData");
        }
    }
};
</script>

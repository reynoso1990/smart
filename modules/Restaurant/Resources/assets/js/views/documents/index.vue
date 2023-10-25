<template>
    <div>
        <div class="container-fluid p-l-0 p-r-0">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h6><span>Comprobantes</span></h6>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/dashboard">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">
                                <span class="text-muted"
                                    >Facturas - Boletas</span
                                >
                            </li>
                        </ol>
                    </div>
                    <div
                        class="col-12 col-md-6 d-flex align-items-start justify-content-end"
                    >
                        <!-- Contact Button Start -->
                        <button
                            type="button"
                            class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto"
                            v-if="
                                typeUser == 'admin' || typeUser == 'superadmin'
                            "
                            @click.prevent="clickNuevo()"
                        >
                            <i class="icofont-plus-circle"></i>
                            <span>Nuevo</span>
                        </button>
                        <!-- Contact Button End -->

                        <!-- Dropdown Button Start -->
                        <div class="ms-1">
                            <button
                                type="button"
                                class="btn btn-outline-primary btn-icon btn-icon-only"
                                data-bs-offset="0,3"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                                data-submenu
                            >
                                <i data-cs-icon="more-horizontal"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <button
                                    class="dropdown-item"
                                    type="button"
                                    @click.prevent="clickValidate()"
                                >
                                    Validar CPE
                                </button>
                                <button
                                    class="dropdown-item"
                                    type="button"
                                    @click.prevent="
                                        clickDownloadReportPagos('excel')
                                    "
                                >
                                    Reporte de Pagos
                                </button>
                                <button
                                    class="dropdown-item"
                                    type="button"
                                    @click.prevent="clickImport()"
                                    v-if="import_documents == true"
                                >
                                    Importar Formato 1
                                </button>
                                <button
                                    class="dropdown-item"
                                    type="button"
                                    @click.prevent="clickImportSecond()"
                                    v-if="import_documents_second == true"
                                >
                                    Importar Formato 2
                                </button>
                            </div>
                        </div>
                        <!-- Dropdown Button End -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid starts-->
        <div class="container-fluid p-l-0 p-r-0">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card mb-0">
                        <div class="card-header bg-primary rounded-top">
                            <h6 class="my-0  text-white">
                                Listado de Comprobante de Pagos
                            </h6>
                        </div>
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
                                        <el-checkbox v-model="column.visible">{{
                                            column.title
                                        }}</el-checkbox>
                                    </el-dropdown-item>
                                </el-dropdown-menu>
                            </el-dropdown>
                        </div>
                        <div class="card-body">
                            <data-table :resource="resource">
                                <tr slot="heading">
                                    <th
                                        class="text-left"
                                        v-if="typeUser != 'integrator'"
                                    >
                                        Acciones
                                    </th>
                                    <th>#</th>
                                    <th>SOAP</th>
                                    <th v-if="columns.user_name.visible">
                                        Usuario
                                    </th>
                                    <th class="text-center">Fecha Emisión</th>
                                    <th
                                        class="text-center"
                                        v-if="columns.date_of_due.visible"
                                    >
                                        Fecha Vencimiento
                                    </th>
                                    <th>Cliente</th>
                                    <th>Número</th>
                                    <th v-if="columns.notes.visible">
                                        Notas C/D
                                    </th>
                                    <th>Estado</th>
                                    <th class="text-center">Moneda</th>
                                    <th
                                        class="text-left"
                                        v-if="columns.total_exportation.visible"
                                    >
                                        T.Exportación
                                    </th>
                                    <th
                                        class="text-left"
                                        v-if="columns.total_free.visible"
                                    >
                                        T.Gratuita
                                    </th>
                                    <th
                                        class="text-left"
                                        v-if="columns.total_unaffected.visible"
                                    >
                                        T.Inafecta
                                    </th>
                                    <th
                                        class="text-left"
                                        v-if="columns.total_exonerated.visible"
                                    >
                                        T.Exonerado
                                    </th>
                                    <th class="text-left">T.Gravado</th>
                                    <th class="text-left">T.Igv</th>
                                    <th class="text-left">Total</th>
                                    <th class="text-center">Por pagar</th>
                                    <th class="text-center">Pago</th>

                                    <th class="text-center">Descargas</th>
                                    <!--<th class="text-center">Anulación</th>-->
                                </tr>

                                <tr></tr>
                                <tr
                                    slot-scope="{ index, row }"
                                    :class="{
                                        'text-danger':
                                            row.state_type_id === '11',
                                        'border border-secondary':
                                            row.state_type_id === '01',
                                        'border border-warning':
                                            row.state_type_id === '03',
                                        'border border-primary':
                                            row.state_type_id === '05',
                                        'border border-info':
                                            row.state_type_id === '07',
                                        'border border-dark':
                                            row.state_type_id === '09',
                                        'border border-danger':
                                            row.state_type_id === '11',
                                        'border border-warnnig':
                                            row.state_type_id === '13'
                                    }"
                                >
                                    <td
                                        :class="{
                                            'text-dark':
                                                row.state_type_id === '11'
                                        }"
                                        class="text-left"
                                        v-if="typeUser != 'integrator'"
                                    >
                                        <div
                                            class="dropdown-as-select d-inline-block"
                                            data-childselector="span"
                                            v-if="row.state_type_id != '11'"
                                        >
                                            <button
                                                class="btn p-0"
                                                type="button"
                                                data-bs-toggle="dropdown"
                                                aria-haspopup="true"
                                                aria-expanded="false"
                                            >
                                                <span
                                                    class="btn btn-primary dropdown-toggle"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    data-bs-delay="0"
                                                    title=""
                                                    data-bs-original-title="Item Count"
                                                    aria-label="Item Count"
                                                    >Acciones</span
                                                >
                                            </button>
                                            <div
                                                class="dropdown-menu dropdown-menu-end"
                                                style=""
                                            >
                                                <a
                                                    type="button"
                                                    class="dropdown-item"
                                                    @click.prevent="
                                                        clickDeleteDocument(
                                                            row.id
                                                        )
                                                    "
                                                    v-if="
                                                        row.btn_delete_doc_type_03
                                                    "
                                                    >Eliminar
                                                </a>
                                                <a
                                                    type="button"
                                                    class="dropdown-item"
                                                    @click.prevent="
                                                        clickChangeToRegisteredStatus(
                                                            row.id
                                                        )
                                                    "
                                                    v-if="
                                                        row.btn_change_to_registered_status
                                                    "
                                                    >Cambiar a estado registrado
                                                </a>
                                                <a
                                                    type="button"
                                                    class="dropdown-item"
                                                    @click.prevent="
                                                        clickReStore(row.id)
                                                    "
                                                    v-if="
                                                        row.document_type_id !=
                                                            '03'
                                                    "
                                                    >Volver a recrear
                                                </a>
                                                <a
                                                    class="dropdown-item"
                                                    @click.prevent="
                                                        clickVoided(row.id)
                                                    "
                                                    v-if="row.btn_voided"
                                                    >Anular</a
                                                >
                                                <a
                                                    type="button"
                                                    :href="
                                                        `/${resource}/note/${
                                                            row.id
                                                        }`
                                                    "
                                                    class="dropdown-item"
                                                    v-if="row.btn_note"
                                                    >Nota de Credito</a
                                                >

                                                <a
                                                    type="button"
                                                    class="dropdown-item"
                                                    @click.prevent="
                                                        clickEdit(row.id)
                                                    "
                                                    v-if="
                                                        row.state_type_id ===
                                                            '01' ||
                                                            row.state_type_id ===
                                                                '14'
                                                    "
                                                    >Modificar
                                                </a>
                                                <a
                                                    type="button"
                                                    class="dropdown-item"
                                                    @click.prevent="
                                                        clickValidarCpe(row.id)
                                                    "
                                                    >Validar CPE
                                                </a>
                                                <a
                                                    type="button"
                                                    class="dropdown-item"
                                                    @click.prevent="
                                                        clickResend(row.id)
                                                    "
                                                    v-if="
                                                        row.btn_resend &&
                                                            !isClient &&
                                                            row.document_type_id !=
                                                                '03'
                                                    "
                                                >
                                                    Reenviar
                                                </a>
                                                <a
                                                    type="button"
                                                    class="dropdown-item"
                                                    @click.prevent="
                                                        clickSendOnline(row.id)
                                                    "
                                                    v-if="
                                                        isClient &&
                                                            !row.send_server
                                                    "
                                                >
                                                    Enviar Servidor
                                                </a>
                                                <a
                                                    type="button"
                                                    class="dropdown-item"
                                                    @click.prevent="
                                                        clickCheckOnline(row.id)
                                                    "
                                                    v-if="
                                                        isClient &&
                                                            row.send_server &&
                                                            (row.state_type_id ===
                                                                '01' ||
                                                                row.state_type_id ===
                                                                    '03')
                                                    "
                                                >
                                                    Consultar Servidor
                                                </a>
                                                <a
                                                    type="button"
                                                    class="dropdown-item"
                                                    @click.prevent="
                                                        clickOptions(
                                                            row.id,
                                                            false
                                                        )
                                                    "
                                                >
                                                    Imprimir
                                                </a>

                                                <a
                                                    type="button"
                                                    class="dropdown-item"
                                                    @click.prevent="
                                                        clickPayment(row.id)
                                                    "
                                                    >Pagos</a
                                                >
                                            </div>
                                        </div>
                                    </td>
                                    <td
                                        :class="{
                                            'text-dark':
                                                row.state_type_id === '11'
                                        }"
                                    >
                                        {{ index }}
                                    </td>
                                    <td
                                        :class="{
                                            'text-dark':
                                                row.state_type_id === '11'
                                        }"
                                    >
                                        {{ row.soap_type_description }}
                                    </td>
                                    <td
                                        :class="{
                                            'text-dark':
                                                row.state_type_id === '11'
                                        }"
                                        v-if="columns.user_name.visible"
                                    >
                                        {{ row.user_name }}
                                        <br /><small
                                            v-text="row.user_email"
                                        ></small>
                                    </td>
                                    <td
                                        class="text-center"
                                        :class="{
                                            'text-dark':
                                                row.state_type_id === '11'
                                        }"
                                    >
                                        {{ row.date_of_issue }}
                                    </td>
                                    <td
                                        class="text-center"
                                        v-if="columns.date_of_due.visible"
                                        :class="{
                                            'text-dark':
                                                row.state_type_id === '11'
                                        }"
                                    >
                                        {{ row.date_of_due }}
                                    </td>
                                    <td
                                        :class="{
                                            'text-dark':
                                                row.state_type_id === '11'
                                        }"
                                    >
                                        {{ row.customer_name }}<br /><small
                                            class="badge bg-dark"
                                            v-text="row.customer_number"
                                        ></small>
                                    </td>
                                    <td
                                        :class="{
                                            'text-dark':
                                                row.state_type_id === '11'
                                        }"
                                    >
                                        {{ row.number }}<br />
                                        <small
                                            class="badge bg-dark"
                                            v-text="
                                                row.document_type_description
                                            "
                                        ></small
                                        ><br />
                                        <small
                                            class="badge bg-dark"
                                            v-if="row.affected_document"
                                            v-text="row.affected_document"
                                        ></small>
                                    </td>
                                    <td
                                        :class="{
                                            'text-dark':
                                                row.state_type_id === '11'
                                        }"
                                        v-if="columns.notes.visible"
                                    >
                                        <template
                                            v-for="(row, index) in row.notes"
                                        >
                                            <label class="d-block" :key="index"
                                                >{{
                                                    row.note_type_description
                                                }}: {{ row.description }}</label
                                            >
                                        </template>
                                    </td>

                                    <!-- <td>
                            {{ row.document_type_id == '07' ?  row.number : ''}}
                        </td> -->

                                    <td>
                                        <el-tooltip
                                            v-if="tooltip(row, false)"
                                            class="item"
                                            effect="dark"
                                            placement="bottom"
                                        >
                                            <div slot="content">
                                                {{ tooltip(row) }}
                                            </div>
                                            <span
                                                class="badge"
                                                :class="{
                                                    'bg-danger':
                                                        row.state_type_id ===
                                                        '11',
                                                    'badge bg-warning':
                                                        row.state_type_id ===
                                                        '13',
                                                    'badge bg-dark':
                                                        row.state_type_id ===
                                                        '01',
                                                    'bg-warning':
                                                        row.state_type_id ===
                                                        '03',
                                                    'bg-success':
                                                        row.state_type_id ===
                                                        '05',
                                                    'bg-info':
                                                        row.state_type_id ===
                                                        '07',
                                                    'bg-dark':
                                                        row.state_type_id ===
                                                        '09'
                                                }"
                                            >
                                                {{ row.state_type_description }}
                                            </span>
                                        </el-tooltip>
                                        <span
                                            v-else
                                            class="badge"
                                            :class="{
                                                'bg-danger':
                                                    row.state_type_id === '11',
                                                'badge bg-warning':
                                                    row.state_type_id === '13',
                                                'badge bg-dark':
                                                    row.state_type_id === '01',
                                                'bg-warning':
                                                    row.state_type_id === '03',
                                                'bg-success':
                                                    row.state_type_id === '05',
                                                'bg-info':
                                                    row.state_type_id === '07',
                                                'bg-dark':
                                                    row.state_type_id === '09'
                                            }"
                                        >
                                            {{ row.state_type_description }}
                                        </span>
                                        <template
                                            v-if="
                                                row.regularize_shipping &&
                                                    row.state_type_id === '01'
                                            "
                                        >
                                            <el-tooltip
                                                class="item"
                                                effect="dark"
                                                :content="
                                                    row.message_regularize_shipping
                                                "
                                                placement="top-start"
                                            >
                                                <i
                                                    class="fas fa-exclamation-triangle fa-lg"
                                                    style="color: #d2322d !important"
                                                ></i>
                                            </el-tooltip>
                                        </template>
                                    </td>

                                    <td
                                        :class="{
                                            'text-dark':
                                                row.state_type_id === '11'
                                        }"
                                        class="text-center"
                                    >
                                        {{ row.currency_type_id }}
                                    </td>
                                    <td
                                        :class="{
                                            'text-dark':
                                                row.state_type_id === '11'
                                        }"
                                        class="text-left"
                                        v-if="columns.total_exportation.visible"
                                    >
                                        {{ row.total_exportation }}
                                    </td>

                                    <td
                                        :class="{
                                            'text-dark':
                                                row.state_type_id === '11'
                                        }"
                                        class="text-left"
                                        v-if="columns.total_free.visible"
                                    >
                                        {{ row.total_free }}
                                    </td>

                                    <td
                                        :class="{
                                            'text-dark':
                                                row.state_type_id === '11'
                                        }"
                                        class="text-left"
                                        v-if="columns.total_unaffected.visible"
                                    >
                                        {{ row.total_unaffected }}
                                    </td>
                                    <td
                                        :class="{
                                            'text-dark':
                                                row.state_type_id === '11'
                                        }"
                                        class="text-left"
                                        v-if="columns.total_exonerated.visible"
                                    >
                                        {{ row.total_exonerated }}
                                    </td>
                                    <td
                                        :class="{
                                            'text-dark':
                                                row.state_type_id === '11'
                                        }"
                                        class="text-left"
                                    >
                                        {{ row.total_taxed }}
                                    </td>
                                    <td
                                        :class="{
                                            'text-dark':
                                                row.state_type_id === '11'
                                        }"
                                        class="text-left"
                                    >
                                        {{ row.total_igv }}
                                    </td>
                                    <td
                                        :class="{
                                            'text-dark':
                                                row.state_type_id === '11'
                                        }"
                                        class="text-left"
                                    >
                                        {{ row.total }}
                                    </td>
                                    <template v-if="row.balance > 0">
                                        <td
                                            :class="{
                                                'text-dark':
                                                    row.state_type_id === '11'
                                            }"
                                            class="text-left text-danger font-weight-bold"
                                        >
                                            {{ row.balance }}
                                        </td>
                                    </template>
                                    <template v-else>
                                        <td
                                            :class="{
                                                'text-dark':
                                                    row.state_type_id === '11'
                                            }"
                                            class="text-left"
                                        >
                                            {{ row.balance }}
                                        </td>
                                    </template>
                                    <template v-if="row.balance == 0.0">
                                        <td
                                            :class="{
                                                'text-dark':
                                                    row.state_type_id === '11'
                                            }"
                                            class="text-center"
                                        >
                                            <span
                                                class="badge rounded-pill bg-success text-white"
                                                >Pagado</span
                                            >
                                        </td>
                                    </template>
                                    <template v-else>
                                        <td
                                            :class="{
                                                'text-dark':
                                                    row.state_type_id === '11'
                                            }"
                                            class="text-center"
                                        >
                                            <span
                                                class="badge rounded-pill bg-warning text-white"
                                                >Pendiente</span
                                            >
                                        </td>
                                    </template>

                                    <td
                                        :class="{
                                            'text-dark':
                                                row.state_type_id === '11'
                                        }"
                                        class="text-center"
                                    >
                                        <button
                                            type="button"
                                            style="min-width: 41px"
                                            class="btn btn-sm btn-primary hover-outline"
                                            @click.prevent="
                                                clickDownload(row.download_xml)
                                            "
                                            v-if="row.has_xml"
                                        >
                                            XML
                                        </button>
                                        <button
                                            type="button"
                                            style="min-width: 41px"
                                            class="btn btn-sm btn-primary hover-outline"
                                            @click.prevent="
                                                clickDownload(row.download_pdf)
                                            "
                                            v-if="row.has_pdf"
                                        >
                                            PDF
                                        </button>
                                        <button
                                            type="button"
                                            style="min-width: 41px"
                                            class="btn btn-sm btn-primary hover-outline"
                                            @click.prevent="
                                                clickDownload(row.download_cdr)
                                            "
                                            v-if="row.has_cdr"
                                        >
                                            CDR
                                        </button>
                                    </td>
                                    <!--<td class="text-center">-->
                                    <!--<button type="button" class="btn waves-effect waves-light btn-sm btn-danger"-->
                                    <!--@click.prevent="clickDownload(row.download_xml_voided)"-->
                                    <!--v-if="row.has_xml_voided">XML</button>-->
                                    <!--<button type="button" class="btn waves-effect waves-light btn-sm btn-danger"-->
                                    <!--@click.prevent="clickDownload(row.download_cdr_voided)"-->
                                    <!--v-if="row.has_cdr_voided">CDR</button>-->
                                    <!--<button type="button" class="btn waves-effect waves-light btn-sm btn-warning"-->
                                    <!--@click.prevent="clickTicket(row.voided.id, row.group_id)"-->
                                    <!--v-if="row.btn_ticket">Consultar</button>-->
                                    <!--</td>-->
                                </tr>
                            </data-table>
                        </div>

                        <!-- <documents-voided :showDialog.sync="showDialogVoided"
                            :recordId="recordId"></documents-voided>

            <items-import :showDialog.sync="showImportDialog"></items-import>

            <document-import-second :showDialog.sync="showImportSecondDialog"></document-import-second> -->

                        <document-options
                            :showDialog.sync="showDialogOptions"
                            :editDocument="editDocument"
                            :configuration="configuration"
                            :recordId="recordId"
                            :print="print"
                            :showClose="true"
                        ></document-options>
                        <!-- <document-payments :showDialog.sync="showDialogPayments"
                               :documentId="recordId"></document-payments> -->

                        <!-- <DocumentValidate :showDialogValidate.sync="showDialogValidate"></DocumentValidate> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
//import DocumentValidate from './partials/validate.vue'
import DocumentOptions from "./partials/options.vue";
import DataTable from "../../../../../../../resources/js/components/DataTableDocuments.vue";
import { deletable } from "../../../../../../../resources/js/mixins/deletable";

export default {
    mixins: [deletable],
    props: [
        "isClient",
        "typeUser",
        "import_documents",
        "import_documents_second",
        "configuration"
    ],
    components: { DocumentOptions, DataTable },
    data() {
        return {
            showDialogReportPayment: false,
            showDialogVoided: false,
            showImportDialog: false,
            showDialogCDetraction: false,
            showDialogValidate: false,
            showImportSecondDialog: false,
            resource: "restaurant/documents",
            recordId: null,
            showDialogOptions: false,
            showDialogPayments: false,
            closeBox: false,
            showDialogEdit: false,
            loading_data: false,
            editDocument: false,
            print: false,
            columns: {
                notes: {
                    title: "Notas C/D",
                    visible: false
                },
                user_name: {
                    title: "Usuario",
                    visible: true
                },
                total_exportation: {
                    title: "T.Exportación",
                    visible: false
                },
                total_free: {
                    title: "T.Gratuito",
                    visible: false
                },
                total_unaffected: {
                    title: "T.Inafecto",
                    visible: false
                },
                total_exonerated: {
                    title: "T.Exonerado",
                    visible: false
                },
                date_of_due: {
                    title: "F. Vencimiento",
                    visible: false
                }
            }
        };
    },
    created() {
        // this.$http.get(`/${this.resource}/tables`)
        //         .then(response => {
        //           this.closeBox=response.data.closebox
        //         })
    },
    methods: {
        teclasInit() {
            document.onkeydown = e => {
                const key = e.key;
                if (key == "F3") {
                    //Agregar cliente
                    location.href = `/${this.resource}/create`;
                }
            };
        },

        clickVoided(recordId = null) {
            this.recordId = recordId;
            this.showDialogVoided = true;
        },
        clickNuevo() {
            location.href = `/${this.resource}/create`;
        },
        clickDownload(download) {
            window.open(download, "_blank");
        },
        clickResend(document_id) {
            this.$http
                .get(`/${this.resource}/send/${document_id}`)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                        this.$eventHub.$emit("reloadData");
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch(error => {
                    this.$message.error(error.response.data.message);
                });
        },
        clickSendOnline(document_id) {
            this.$http
                .get(`/${this.resource}/send_server/${document_id}/1`)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(
                            "Se envio satisfactoriamente el comprobante."
                        );
                        this.$eventHub.$emit("reloadData");

                        this.clickCheckOnline(document_id);
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch(error => {
                    this.$message.error(error.response.data.message);
                });
        },
        clickCheckOnline(document_id) {
            this.$http
                .get(`/${this.resource}/check_server/${document_id}`)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success("Consulta satisfactoria.");
                        this.$eventHub.$emit("reloadData");
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch(error => {
                    this.$message.error(error.response.data.message);
                });
        },
        clickCDetraction(recordId) {
            this.recordId = recordId;
            this.showDialogCDetraction = true;
        },
        clickValidarCpe(document_id) {
            this.loading_data = false;
            this.$http
                .get(`/${this.resource}/validate/${document_id}`)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                        this.$eventHub.$emit("reloadData");
                    } else {
                        this.$message.error(response.data.message);
                    }
                    this.loading_data = false;
                })
                .catch(error => {
                    this.$message.error(error.response.data.message);
                    this.loading_data = false;
                });
            this.loading_data = false;
        },
        clickEdit(recordId = null) {
            console.log("this.resource", this.resource);
            location.href = `/${this.resource}/create/${recordId}`;
        },
        clickOptions(recordId = null, printer) {
            this.recordId = recordId;
            this.showDialogOptions = true;
            this.print = printer;
        },
        clickReStore(document_id) {
            this.$http
                .get(`/${this.resource}/re_store/${document_id}`)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                        this.$eventHub.$emit("reloadData");
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch(error => {
                    this.$message.error(error.response.data.message);
                });
        },
        tooltip(row, message = true) {
            if (message) {
                if (row.shipping_status) return row.shipping_status.message;

                if (row.sunat_shipping_status)
                    return row.sunat_shipping_status.message;

                if (row.query_status) return row.query_status.message;
            }

            if (
                row.shipping_status ||
                row.sunat_shipping_status ||
                row.query_status
            )
                return true;

            return false;
        },
        clickPayment(recordId) {
            this.recordId = recordId;
            this.showDialogPayments = true;
        },
        clickChangeToRegisteredStatus(document_id) {
            this.$http
                .get(
                    `/${
                        this.resource
                    }/change_to_registered_status/${document_id}`
                )
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                        this.$eventHub.$emit("reloadData");
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch(error => {
                    this.$message.error(error.response.data.message);
                });
        },
        clickImport() {
            this.showImportDialog = true;
        },
        clickValidate() {
            (this.showDialogValidate = true),
                console.log(this.showDialogValidate);
        },
        clickDownloadReportPagos(type) {
            window.open(`/${this.resource}/payments/${type}`, "_blank");
        },
        clickImportSecond() {
            this.showImportSecondDialog = true;
        },
        clickDeleteDocument(document_id) {
            this.destroy(
                `/${this.resource}/delete_document/${document_id}`
            ).then(() => this.$eventHub.$emit("reloadData"));
        },
        clickReportPayments() {
            this.showDialogReportPayment = true;
        }
    },
    mounted() {
        this.teclasInit();
        //prueba haber
        //nada
    }
};
</script>

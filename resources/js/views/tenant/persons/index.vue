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
            <div class="right-wrapper pull-right">
                <button
                    class="btn btn-custom btn-sm  mt-2 mr-2"
                    type="button"
                    @click.prevent="clickExportMigration()"
                >
                    <i class="fa fa-download"></i> Exportar Migración
                </button>
                <button
                    class="btn btn-custom btn-sm  mt-2 mr-2"
                    type="button"
                    @click.prevent="clickExport()"
                >
                    <i class="fa fa-download"></i> Exportar
                </button>
                <button
                    class="btn btn-custom btn-sm  mt-2 mr-2"
                    type="button"
                    @click.prevent="clickImport()"
                >
                    <i class="fa fa-upload"></i> Importar
                </button>
                <button
                    class="btn btn-custom btn-sm  mt-2 mr-2"
                    type="button"
                    @click.prevent="clickCreate()"
                >
                    <i class="fa fa-plus-circle"></i> Nuevo
                </button>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header">
                <h3 class="my-0">Listado de {{ title }}</h3>
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
                            <el-checkbox
                                @change="getColumnsToShow(1)"
                                v-model="column.visible"
                                >{{ column.title }}</el-checkbox
                            >
                        </el-dropdown-item>
                    </el-dropdown-menu>
                </el-dropdown>
            </div>
            <div class="card-body">
                <data-table
                    :isDriver="driver"
                    :type="type"
                    :configuration="configuration"
                    :resource="resource + `/${this.type}`"
                >
                    <tr slot="heading">
                        <th>#</th>
                        <th>Nombre</th>
                        <th v-if="configuration.college">Tipo</th>
                        <th v-if="driver">Vehiculo</th>
                        <th v-if="!driver">
                            <template >Cód interno</template>
                        </th>
                        <th class="text-end">Tipo de documento</th>
                        <th class="text-end">Número</th>
                        <th
                            v-if="columns.person_type.visible === true"
                            class="text-center"
                        >
                            T. Cliente
                        </th>
                        <th
                            v-if="columns.observation.visible === true"
                            class="text-center"
                        >
                            Observaciones
                        </th>
                        <th
                            v-if="columns.zone.visible === true"
                            class="text-center"
                        >
                            Zona
                        </th>
                        <th
                            v-if="columns.website.visible === true"
                            class="text-center"
                        >
                            WebSite
                        </th>
                        <th
                            v-if="columns.credit_days.visible === true"
                            class="text-center"
                        >
                            Días de crédito
                        </th>
                        <th
                            v-if="columns.seller.visible === true"
                            class="text-center"
                        >
                            Vendedor asignado
                        </th>
                        <th
                            v-if="columns.email.visible === true"
                            class="text-center"
                        >
                            Correo
                        </th>
                        <th
                            v-if="columns.telephone.visible === true"
                            class="text-center"
                        >
                            Telefono
                        </th>
                        <th
                            v-if="columns.department.visible === true"
                            class="text-center"
                        >
                            Departamento
                        </th>
                        <th
                            v-if="columns.province.visible === true"
                            class="text-center"
                        >
                            Provincia
                        </th>
                        <th
                            v-if="columns.district.visible === true"
                            class="text-center"
                        >
                            Distrito
                        </th>
  <th
                            v-if="columns.address.visible === true"
                            class="text-center"
                        >
                            Dirección
                        </th>

                        <th class="text-center" v-if="showAccumulatedPoints">
                            Puntos acumulados
                        </th>

                        <th class="text-end">Acciones</th>
                    </tr>

                    <tr></tr>
                    <tr
                        slot-scope="{ index, row }"
                        :class="{ disable_color: !row.enabled }"
                    >
                        <td>{{ index }}</td>
                        <td>{{ row.name }}</td>
                        <td v-if="configuration.college">
                            <template v-if="row.parent_id == 0"
                                >{{ getOpcionalName("parents", "Padre") }}
                            </template>
                            <template v-else>{{
                                getOpcionalName("children", "Hijo")
                            }}</template>
                        </td>
                              <td v-if="driver">
                                {{row.barcode}}
                        </td>


                        <td v-if="!driver">{{ row.internal_code }}</td>
                        <td class="text-end">{{ row.document_type }}</td>
                        <td class="text-end">{{ row.number }}</td>
                        <td
                            v-if="columns.person_type.visible === true"
                            class="text-left"
                        >
                            {{ row.person_type }}
                        </td>
                        <td
                            v-if="columns.observation.visible === true"
                            class="text-left"
                        >
                            {{ row.observation }}
                        </td>
                        <td
                            v-if="columns.zone.visible === true"
                            class="text-left"
                        >
                            {{ row.zone ? row.zone.name : "" }}
                        </td>
                        <td
                            v-if="columns.website.visible === true"
                            class="text-left"
                        >
                            {{ row.website }}
                        </td>
                        <td
                            v-if="columns.credit_days.visible === true"
                            class="text-center"
                        >
                            {{ row.credit_days }}
                        </td>
                        <td
                            v-if="columns.seller.visible === true"
                            class="text-center"
                        >
                            {{
                                row.seller && row.seller.name
                                    ? row.seller.name
                                    : ""
                            }}
                        </td>
                        <td
                            v-if="columns.email.visible === true"
                            class="text-center"
                        >
                            {{ row.email }}
                        </td>
                        <td
                            v-if="columns.telephone.visible === true"
                            class="text-center"
                        >
                            {{ row.telephone ? row.telephone : "" }}
                        </td>
                        <td
                            v-if="columns.department.visible === true"
                            class="text-center"
                        >
                            {{
                                row.department ? row.department.description : ""
                            }}
                        </td>
                        <td
                            v-if="columns.province.visible === true"
                            class="text-center"
                        >
                            {{ row.province ? row.province.description : "" }}
                        </td>
                        <td
                            v-if="columns.district.visible === true"
                            class="text-center"
                        >
                            {{ row.district ? row.district.description : "" }}
                        </td>
 <td
                            v-if="columns.address.visible === true"
                            class="text-center"
                        >
                            {{ row.address ? row.address : "" }}
                        </td>
                        <td v-if="showAccumulatedPoints" class="text-center">
                            {{ row.accumulated_points }}
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
                                    <div v-if="row.enabled">
                                        <button
                                            class="dropdown-item"
                                            @click.prevent="clickCreate(row.id)"
                                        >
                                            Editar
                                        </button>
                                    </div>
                                    <div
                                        v-if="
                                            configuration.college &&
                                                row.parent_id == 0
                                        "
                                    >
                                        <button
                                            class="dropdown-item"
                                            @click.prevent="
                                                clickAddChild(row.id)
                                            "
                                        >
                                            Agregar hijo/a
                                        </button>
                                    </div>
                                    <button
                                        class="dropdown-item"
                                        v-if="typeUser === 'admin'"
                                        @click.prevent="clickDelete(row.id)"
                                    >
                                        Eliminar
                                    </button>
                                    <div v-if="typeUser === 'admin'">
                                        <button
                                            v-if="row.enabled"
                                            class="dropdown-item"
                                            @click.prevent="
                                                clickDisable(row.id)
                                            "
                                        >
                                            Inhabilitar
                                        </button>
                                        <button
                                            v-else
                                            class="dropdown-item"
                                            @click.prevent="clickEnable(row.id)"
                                        >
                                            Habilitar
                                        </button>
                                    </div>
                                    <button
                                        class="dropdown-item"
                                        @click.prevent="clickBarcode(row)"
                                    >
                                        Cod. Barras
                                    </button>
                                    <button
                                        class="dropdown-item"
                                        @click.prevent="clickPrintBarcode(row)"
                                    >
                                        Etiquetas
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </data-table>
            </div>

            <persons-form
                :api_service_token="api_service_token"
                :recordId="recordId"
                :showDialog.sync="showDialog"
                :type="type"
                :isDriver="driver"
            ></persons-form>

            <persons-import
                :showDialog.sync="showImportDialog"
                :type="type"
            ></persons-import>

            <persons-export
                :showDialog.sync="showExportDialog"
                :type="type"
            ></persons-export>
            <persons-export-migration
                :showDialog.sync="showExportMigrationDialog"
                :type="type"
            ></persons-export-migration>
        </div>
    </div>
</template>

<script>
import PersonsForm from "./form.vue";
import PersonsImport from "./import.vue";
import PersonsExport from "./partials/export.vue";
import PersonsExportMigration from "./partials/export_migration.vue";
import DataTable from "../../../components/DataTable.vue";
import { deletable } from "../../../mixins/deletable";

export default {
    mixins: [deletable],
    props: [
        "driver",
        "type",
        "typeUser",
        "api_service_token",
        "configuration",
        "suscriptionames"
    ],
    components: {
        PersonsForm,
        PersonsImport,
        PersonsExport,
        DataTable,
        PersonsExportMigration
    },
    data() {
        return {
            title: null,
            showDialog: false,
            showImportDialog: false,
            showExportDialog: false,
            resource: "persons",
            recordId: null,
            columns: {
                address: {
                    title: "Dirección",
                    visible: false
                },
                observation: {
                    title: "Observacion",
                    visible: false
                },
                zone: {
                    title: "Zona",
                    visible: false
                },
                website: {
                    title: "Sitio Web",
                    visible: false
                },
                person_type: {
                    title: "Tipo de cliente",
                    visible: false
                },
                credit_days: {
                    title: "Días de crédito",
                    visible: false
                },
                seller: {
                    title: "Vendedor asignado",
                    visible: false
                },
                email: {
                    title: "Correo electrónico",
                    visible: false
                },
                telephone: {
                    title: "Teléfono",
                    visible: false
                },
                department: {
                    title: "Departamento",
                    visible: false
                },
                province: {
                    title: "Provincia",
                    visible: false
                },
                district: {
                    title: "Distrito",
                    visible: false
                }
            },
            showExportMigrationDialog: false
        };
    },
    created() {
        console.log(this.driver, " driver");
        this.title = this.type === "customers" ? "Clientes" : "Proveedores";
        if(this.driver){
            this.title = "Conductores";
        }
        this.getColumnsToShow();
    },
    computed: {
        showAccumulatedPoints() {
            if (this.configuration) {
                return (
                    this.configuration.enabled_point_system &&
                    this.type === "customers"
                );
            }

            return false;
        }
    },
    methods: {
        getOpcionalName(key, defaultName) {
            if (
                this.suscriptionames &&
                this.suscriptionames[key] != undefined
            ) {
                return this.suscriptionames[key];
            }
            return defaultName;
        },
        clickAddChild(id) {},
        getColumnsToShow(updated) {
            this.$http
                .post("/validate_columns", {
                    columns: this.columns,
                    report: "client_index", // Nombre del reporte.
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

        clickCreate(recordId = null) {
            this.recordId = recordId;
            this.showDialog = true;
        },
        clickImport() {
            this.showImportDialog = true;
        },
        clickExportMigration() {
            this.showExportMigrationDialog = true;
        },
        clickExport() {
            this.showExportDialog = true;
        },
        clickDelete(id) {
            this.destroy(`/${this.resource}/${id}`).then(() =>
                this.$eventHub.$emit("reloadData")
            );
        },
        clickDisable(id) {
            this.disable(`/${this.resource}/enabled/${0}/${id}`).then(() =>
                this.$eventHub.$emit("reloadData")
            );
        },
        clickEnable(id) {
            this.enable(`/${this.resource}/enabled/${1}/${id}`).then(() =>
                this.$eventHub.$emit("reloadData")
            );
        },
        clickBarcode(row) {
            if (!row.barcode) {
                return this.$message.error(
                    "Para generar el código de barras debe registrar el código de barras."
                );
            }

            window.open(`/${this.resource}/barcode/${row.id}`);
        },
        clickPrintBarcode(row) {
            if (!row.barcode) {
                return this.$message.error(
                    "Para generar el código de barras debe registrar el código de barras."
                );
            }

            window.open(`/${this.resource}/export/barcode/print?id=${row.id}`);
        }
    }
};
</script>

<template>
    <div>
        <div class="page-header pr-0">
            <h2>
                <a href="/dashboard">
                    <i class="fas fa-tachometer-alt"> </i>
                </a>
            </h2>
            <ol class="breadcrumbs">
                <li class="active">
                    <span>
                        {{ typeText }}
                    </span>
                </li>
            </ol>
            <div class="right-wrapper pull-right">
                <button
                    class="btn btn-custom btn-sm  mt-2 mr-2"
                    type="button"
                    @click.prevent="clickCreate()"
                >
                    <i class="fa fa-plus-circle"> </i>
                    Nuevo
                </button>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header">
                <h3 class="my-0">Listado de {{ typeText }}</h3>
            </div>
            <div class="card-body">
                <data-table
                    :suscriptionames="suscriptionames"
                    :type="type"
                    :extraquery="{ users: type }"
                >
                    <tr slot="heading">
                        <th>
                            #
                        </th>
                        <th class="text-left">
                            Nombre
                        </th>
                        <th v-if="type != 'children'" class="text-left">
                            Documento
                        </th>
                        <th v-if="type != 'children'" class="text-end">
                            Número
                        </th>
                        <th v-if="type == 'children'" class="text-left">
                            Cliente
                        </th>
                        <th v-if="type == 'children'" class="text-center">
                            ⚠️
                        </th>
                        <th v-if="type == 'children'" class="text-left">
                            Celular
                        </th>
                        <th v-if="type == 'children'" class="text-left">
                            Fecha
                        </th>
                        <th v-if="type == 'children'" class="text-left">
                            Comentario
                        </th>
                        <th v-if="type == 'children'" class="text-left">
                            {{ getOpcionalName("grades", "Grado", true) }}
                        </th>

                        <th
                            v-if="type == 'children'"
                            class="text-center month_size"
                        >
                            Ene.
                        </th>
                        <th
                            v-if="type == 'children'"
                            class="text-center month_size"
                        >
                            Febr.
                        </th>
                        <th
                            v-if="type == 'children'"
                            class="text-center month_size"
                        >
                            Mar.
                        </th>
                        <th
                            v-if="type == 'children'"
                            class="text-center month_size"
                        >
                            Abr.
                        </th>
                        <th
                            v-if="type == 'children'"
                            class="text-center month_size"
                        >
                            May.
                        </th>
                        <th
                            v-if="type == 'children'"
                            class="text-center month_size"
                        >
                            Jun.
                        </th>
                        <th
                            v-if="type == 'children'"
                            class="text-center month_size"
                        >
                            Jul.
                        </th>
                        <th
                            v-if="type == 'children'"
                            class="text-center month_size"
                        >
                            Ago.
                        </th>
                        <th
                            v-if="type == 'children'"
                            class="text-center month_size"
                        >
                            Set.
                        </th>
                        <th
                            v-if="type == 'children'"
                            class="text-center month_size"
                        >
                            Oct.
                        </th>
                        <th
                            v-if="type == 'children'"
                            class="text-center month_size"
                        >
                            Nov.
                        </th>
                        <th
                            v-if="type == 'children'"
                            class="text-center month_size"
                        >
                            Dic.
                        </th>
                        <th class="text-end">
                            Acciones
                        </th>
                    </tr>

                    <tr></tr>
                    <tr slot-scope="{ index, row }">
                        <td>
                            {{ index }}
                        </td>
                        <td class="text-left">
                            {{ row.name }}
                            <template v-if="type == 'children'">
                                <br />
                                <small>
                                    <strong>{{ row.document_type }}</strong>
                                    {{ row.number }}
                                </small>
                            </template>
                        </td>
                        <td v-if="type != 'children'" class="text-left">
                            {{ row.document_type }}
                        </td>
                        <td v-if="type != 'children'" class="text-end">
                            {{ row.number }}
                        </td>
                        <td v-if="type == 'children'" class="text-left">
                            {{ row.parent.name }}
                        </td>
                        <td v-if="type == 'children'" class="text-left">
                            <div class="block color_hidden">
                                <el-color-picker
                                    @change="setColor(row)"
                                    v-model="row.color"
                                    :predefine="colors"
                                ></el-color-picker>
                            </div>
                        </td>
                        <td v-if="type == 'children'" class="text-left">
                            <template v-if="row.parent.telephone">
                                <label
                                    role="button"
                                    @click="openWhatsapp(row.parent.telephone)"
                                >
                                    {{ row.parent.telephone }}
                                </label>
                            </template>
                        </td>
                        <td
                            v-if="type == 'children'"
                            class="text-center"
                            style="width:150px !important;"
                        >
                            <el-date-picker
                                class="hidden_datepicker"
                                :ref="`datePicker_${row.id}`"
                                style="visibility: hidden;heigth:0 !important;"
                                value-format="yyyy-MM-dd"
                                v-model="row.person_date"
                                placement="bottom-start"
                                :append-to-body="true"
                                @change="saveDate(row)"
                            >
                            </el-date-picker>
                            <template v-if="!row.person_date">
                                <label role="button" @click="setDate(row)">
                                    📆
                                </label>
                            </template>
                            <template v-else>
                                <label role="button" @click="setDate(row)">
                                    <small>{{ row.person_date }}</small>
                                </label>
                                <i
                                    class="fas fa-times text-danger"
                                    role="button"
                                    @click="saveDate(row, true)"
                                ></i>
                            </template>

                            <!-- <label
                       
                             role="button" @click="setDate(row)">
                                <template v-if="!row.person_date">
                                    📆
                                </template>
                                <template v-else>
                                    <small>{{ row.person_date }}</small>
                                    <i class="fas fa-times text-danger"></i>
                                </template>
                            </label> -->
                        </td>
                        <td v-if="type == 'children'">
                            <template v-if="row.observation">
                                <el-tooltip
                                    class="item"
                                    effect="dark"
                                    :content="row.observation"
                                    placement="top-start"
                                >
                                    <el-input
                                        @input="
                                            saveObservation(
                                                row.id,
                                                row.observation
                                            )
                                        "
                                        v-model="row.observation"
                                    >
                                    </el-input>
                                </el-tooltip>
                            </template>
                            <template v-else>
                                <el-input
                                    @input="
                                        saveObservation(row.id, row.observation)
                                    "
                                    v-model="row.observation"
                                >
                                </el-input>
                            </template>
                        </td>
                        <td>
                            {{ row.student ? row.student.grade : "" }}
                        </td>

                        <td
                            v-show="type == 'children'"
                            class="text-center"
                            v-for="(month, idx) in row.months"
                            :key="idx"
                            @click="viewPayments(idx + 1, row)"
                        >
                            <span
                                :class="
                                    `h6 ${
                                        month == 0
                                            ? 'text-danger'
                                            : 'text-success'
                                    }`
                                "
                                role="button"
                            >
                                <small>{{ month.toFixed(2) }}</small>
                            </span>
                        </td>
                        <td class="text-end">
                            <button
                                class="btn waves-effect waves-light btn-sm btn-info"
                                type="button"
                                @click.prevent="clickCreate(getRowId(row))"
                            >
                                Editar
                            </button>
                            <!--
                            <button
                                class="btn waves-effect waves-light btn-sm btn-danger"
                                type="button"
                                @click.prevent="clickDelete(row.id)">
                                Eliminar
                            </button>
                            -->
                        </td>
                    </tr>
                </data-table>
            </div>

            <customers-form :recordId="recordId" :showDialog.sync="showDialog">
            </customers-form>
            <payment-modal
                :showDialog.sync="showPayments"
                :record="currentStudenMonth"
            >
            </payment-modal>
        </div>
    </div>
</template>

<style>
.el-color-dropdown__main-wrapper {
    display: none !important;
}
.el-color-dropdown__value {
    display: none !important;
}

.hidden_datepicker.el-date-editor.el-input,
.hidden_datepicker.el-date-editor.el-input__inner {
    width: 0px !important;
    height: 0px !important;
}
</style>
<script>
import { mapActions, mapState } from "vuex/dist/vuex.mjs";
import moment from "moment";
import CustomersForm from "./form.vue";
import DataTable from "../components/SuscriptionsDataTable.vue";
import { deletable } from "../../../../../../resources/js/mixins/deletable";
import PaymentModal from "./payment_modal.vue";
export default {
    props: ["configurations", "listtype", "suscriptionames"],
    mixins: [deletable],
    components: {
        CustomersForm,
        DataTable,
        PaymentModal
    },
    data() {
        return {
            showPayments: false,
            currentStudenMonth: null,
            time: null,
            showDialog: false,
            recordId: null,
            type: null,
            typeText: "Clientes",
            colors: [
                "#FF0000",
                "#0000FF",
                "#00FF00",
                "#FFFF00",
                "#FFA500",
                "#FFC0CB",
                "#800080",
                "#40E0D0",
                "#A52A2A",
                "#FFD700"
            ]
        };
    },
    computed: {
        ...mapState([
            "config",
            "resource"
            /*
            'countries',
            'all_departments',
            'all_provinces',
            'all_districts',
            'identity_document_types',
            'locations',
            */
        ])
    },
    created() {
        this.loadConfiguration();
        this.$store.commit("setConfiguration", this.configuration);
        this.$store.commit("setResource", "client");

        this.getCommonData();
        // Clientes
        if (this.listtype !== undefined) {
            this.type = this.listtype;
            if (this.type == "parent") {
                this.typeText = this.getOpcionalName("parents", "Padres");
            } else if (this.type == "children") {
                this.typeText = this.getOpcionalName("children", "Hijos");
            }
        }
        // this.getPersonData()
    },
    methods: {
        saveDate(row, resetDate = false) {
            let date = row.person_date;
            if (resetDate) {
                date = null;
                row.person_date = null;
            }
            this.$http
                .post("/suscription/client/suscription/setdate", {
                    date,
                    person_id: row.id
                })
                .then(response => {
                    if (response.status == 200) {
                        this.$message({
                            type: "success",
                            message: "Fecha guardada"
                        });
                    }
                })
                .catch(_ => {
                    this.$message({
                        type: "error",
                        message: "Error al guardar la fecha"
                    });
                });
            
        },
        setDate(row) {
            this.$refs[`datePicker_${row.id}`].showPicker();
        },
        setColor(row) {
            this.$http
                .post("/suscription/client/suscription/setcolor", {
                    color: row.color,
                    person_id: row.id
                })
                .then(response => {
                    if (response.status == 200) {
                        this.$message({
                            type: "success",
                            message: "Color guardado"
                        });
                    }
                })
                .catch(_ => {
                    this.$message({
                        type: "error",
                        message: "Error al guardar el color"
                    });
                });
            console.log(response);
        },
        getOpcionalName(key, defaultName, capitalize = false) {
            if (
                this.suscriptionames &&
                this.suscriptionames[key] != undefined
            ) {
                return this.suscriptionames[key];
            }
            if (capitalize) {
                return (
                    defaultName.charAt(0).toUpperCase() + defaultName.slice(1)
                );
            }
            return defaultName;
        },
        openWhatsapp(number) {
            window.open(`https://api.whatsapp.com/send?phone=51${number}`);
        },
        viewPayments(month, student) {
            let year = new Date().getFullYear();
            this.currentStudenMonth = { month, year, student };
            this.showPayments = true;
        },

        async saveObservation(id, observation) {
            if (this.time != null) {
                clearTimeout(this.time);
            }

            this.time = setTimeout(async () => {
                const response = await this.$http.post(
                    `/suscription/college/save_observation`,
                    {
                        id: id,
                        observation: observation
                    }
                );

                if (response.status == 200) {
                    this.$message({
                        type: "success",
                        message: "Comentario guardado"
                    });
                }
            }, 500);

            // this.$http
            //     .post(`/suscription/${this.resource}/saveObservation`, {
            //         id: id,
            //         observation: observation
            //     });
        },
        ...mapActions(["loadConfiguration"]),
        getRowId(row) {
            // Si es un hijo, mostraria el modal del padre
            if (row.parent_id > 0) {
                return row.parent_id;
            }
            return row.id;
        },
        clickCreate(recordId = null) {
            this.recordId = recordId;
            this.showDialog = true;
        },
        clickDelete(id) {
            this.destroy(`/${this.resource}/${id}`).then(() =>
                this.$eventHub.$emit("reloadData")
            );
        },
        getCommonData() {
            this.$http.post("/suscription/CommonData", {}).then(response => {
                this.$store.commit(
                    "setCurrencyTypes",
                    response.data.currency_types
                );
                this.$store.commit(
                    "setAffectationIgvTypes",
                    response.data.affectation_igv_types
                );
                this.$store.commit("setUnitTypes", response.data.unit_types);
                this.$store.commit(
                    "setPaymentMethodTypes",
                    response.data.payments_credit
                );
            });
        },

        getPersonData() {
            this.$http
                .post(`/suscription/${this.resource}/tables`)
                .then(response => {
                    this.api_service_token = response.data.api_service_token;
                    // console.log(this.api_service_token)

                    this.$store.commit("setCountries", response.data.countrie);
                    this.$store.commit(
                        "setAllDepartments",
                        response.data.departments
                    );
                    this.$store.commit(
                        "setAllProvinces",
                        response.data.provinces
                    );
                    this.$store.commit(
                        "setAllDistricts",
                        response.data.districts
                    );
                    this.$store.commit(
                        "setIdentityDocumentTypes",
                        response.data.identity_document_types
                    );
                    this.$store.commit("setLocations", response.data.locations);
                    this.$store.commit(
                        "setPersonTypes",
                        response.data.person_types
                    );
                });
        }
    }
};
</script>

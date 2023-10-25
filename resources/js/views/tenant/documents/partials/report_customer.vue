<template>
    <el-dialog
        title="Consulta de documentos"
        :visible="showDialog"
        @close="close"
        @open="create"
        width="80%"
        :show-close="false"
    >
        <data-table :resource="'reports/customers'" :customerId="customerId">
            <tr slot="heading">
                <th class="">#</th>
                <th class="">Fecha</th>
                <th class="">Tipo Documento</th>
                <th class="">Detalle</th>
                <th class="">Estado</th>
                <th class="">Nota C/D</th>
                <th class="">Moneda</th>
                <th class="">Número de Placa</th>
                <th class="">Serie</th>
                <th class="">Número</th>
                <th class="">Monto</th>
            </tr>

            <tr></tr>
            <tr slot-scope="{ index, row }">
                <td>{{ index }}</td>
                <td>{{ row.date_of_issue }}</td>
                <td>
                    {{ row.document_type_description }}
                </td>
                <td>
                    <button
                        @click="seeDetail(row)"
                        class="btn waves-effect waves-light btn-sm btn-info"
                        type="button"
                    >
                        <i class="fa fa-search"></i>
                    </button>
                </td>
                <td>
                    <span
                        class="badge bg-secondary text-white"
                        :class="{
                            'bg-danger': row.state_type_id === '11',
                            'bg-warning': row.state_type_id === '13',
                            'bg-secondary': row.state_type_id === '01',
                            'bg-info': row.state_type_id === '03',
                            'bg-success': row.state_type_id === '05',
                            'bg-secondary': row.state_type_id === '07',
                            'bg-dark': row.state_type_id === '09'
                        }"
                    >
                        {{ row.state_type_description }}
                    </span>
                </td>
                <td v-if="row.notes.length != 0">
                    {{ row.notes.map(n => `${n.description}`).join("/") }}
                </td>
                <td v-else>-</td>
                <td>{{ row.currency_type_id }}</td>
                <td>{{ row.plate_number }}</td>
                <td>{{ row.series }}</td>
                <td>{{ row.alone_number }}</td>
                <td>
                    {{
                        row.document_type_id == "07"
                            ? row.total == 0
                                ? "0.00"
                                : "-" + row.total
                            : row.document_type_id != "07" &&
                              (row.state_type_id == "11" ||
                                  row.state_type_id == "09")
                            ? "0.00"
                            : row.total
                    }}
                </td>
            </tr>
        </data-table>

        <span slot="footer" class="dialog-footer">
            <el-button @click.prevent="close()">Close</el-button>
        </span>
        <items-detail
            :showDialog.sync="showDetail"
            :document="currentDocument"
        ></items-detail>
    </el-dialog>
</template>

<script>
import DataTable from "../../../../../../modules/Report/Resources/assets/js/components/DataTableCustomers.vue";
import ItemsDetail from "./items_detail.vue";
export default {
    props: ["showDialog", "customerId"],
    components: {
        DataTable,
        ItemsDetail
    },
    data() {
        return {
            showDetail: false,
            currentDocument: null,
            customer_id: 0
        };
    },
    methods: {
        seeDetail(document) {
            this.currentDocument = document;
            this.showDetail = true;
        },
        create() {
            // if(this.customerId) {
            //   this.customer_id = this.customerId
            // }
        },
        close() {
            this.$emit("reloadData");
            this.$emit("update:showDialog", false);
        }
    }
};
</script>

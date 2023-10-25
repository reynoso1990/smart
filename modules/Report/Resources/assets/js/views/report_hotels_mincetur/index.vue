<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header">
            <h3 class="my-0">Reporte MINCETUR hoteles</h3>
        </div>
        <div class="card mb-0">
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                    <th>ITEM</th>
                    <th>APELLIDOS Y NOMBRES</th>
                    <th>SEXO</th>
                    <th>PAIS DE RESIDENCIA</th>
                    <th>REGIÓN DE RESIDENCIA</th>
                    <th>MOTIVO DE VIAJE</th>
                    <th>TIPO DE DOCUMENTO</th>
                    <th>N° DE DOCUMENTO</th>
                    <th>FECHA DE INGRESO</th>
                    <th>FECHA DE SALIDA</th>
                    <th>TIPO DE HABITACIÓN</th>
                    <th>N° DE HABITACIÓN</th>
                    <th>TARIFA S/</th>
                        <!-- <th  >Edad</th>
                         <th  >E. civil</th>
                         <th  >Nacionalidad</th>
                         <th  >Procedencia</th>
                         <th>Comprobante</th>-->

                    <tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                    <td>{{row.customer_name}}</td>
                    <td>{{row.sex}}</td>
                    <td>{{row.county}}</td>
                    <td>{{row.reg}}</td>
                    <td>{{row.reason}}</td>
                    <td>{{row.customer_document_type}}</td>
                    <td>{{row.customer_document_}}</td>
                    <td>{{row.start_date}}</td>
                    <td>{{row.end_date}}</td>
                    <td>{{row.category}}</td>
                    <td>{{row.room}}</td>
                    <td>{{row.room_rastes}}</td>

                    </tr>

                </data-table>


            </div>
    

        </div>

    </div>
</template>

<script>

import DataTable from '../../components/DataTableDocumentHotel.vue'

export default {
    components: {
        DataTable,
    },
    data() {
        return {
            resource: 'reports/report_hotels_mincetur/mincetur',
            form: {},
            recordItem: {},
            showItemModal: false,
            room_name: '',
        }
    },
    async created() {
    },
    methods: {


        clickProductsItems(row, room) {
            this.recordItem = row
            this.showItemModal = true
            this.room_name = room
        },
        checkProductDebts(row) {
            let str = '';
            let deb = 0;
            row.forEach(function (a, b) {
                if (a.payment_status !== 'PAID') {
                    deb += a.item.total;
                }
            });
            if (deb != 0) {
                str = `Debe ${deb} en producto(s)`;
            }

            return str;
        }
    },

}
</script>

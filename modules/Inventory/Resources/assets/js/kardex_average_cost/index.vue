<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-primary">
            <h6 class="my-0">Consulta kardex Costo Promedio</h6>
        </div> 
        <div class="card mb-0">
                <div class="card-body">
                    <data-table :resource="resource">
                        <tr slot="heading">
                            <th>#</th>
                             
                            <th>Fecha y hora transacción</th>
                            <th>Tipo transacción</th>
                            <th>Número</th>
                            <th>NV. Asociada</th>
                             <!--<th>Pedido</th> -->
                            <th>Doc. Asociado</th>
                            <th>Feha emisión</th>
                            <th>Entrada</th>
                            <th>Precio</th>
                            <th class="text-success font-weight-bold">Total Compra</th>
                            <th>Salida</th>
                            <th>Precio</th>
                            <th  class="text-danger font-weight-bold">Total Venta</th>
                            <th v-if="item_id">Saldo</th>
                            <th v-if="item_id">Costo unit</th>
                            <th  class="text-success font-weight-bold">Saldo Final</th>
                            <!--
                            <th >Almacen </th>
                            <th >Precio de almacen</th>
                        -->
                        <tr>
                        <tr slot-scope="{ index, row }">
                            <td>{{ index }}</td>
                            
                            <td>{{row.date_time}}</td>
                            <td>{{row.type_transaction}}</td>
                            <td>{{row.number}}</td>
                            <td>{{row.sale_note_asoc}}</td>
                             <!--<td>{{row.order_note_asoc}}</td>-->
                            <td>{{row.doc_asoc}}</td>
                            <td>{{row.date_of_issue}}</td>
                            <td>{{row.input}}</td>
                            <td>
                            <template v-if="row.purchase_cost>0">
                             {{row.currency_type_id}} {{row.purchase_cost}}
                            </template>
                            </td>
                            <td class="text-success font-weight-bold">
                            <template v-if="row.total_purchase_cost>0">
                              {{row.currency_type_id}} {{row.total_purchase_cost}}
                            </template>
                            </td>
                            
                            <td>{{row.output}}</td>
                            <td>
                            <template v-if="row.sales_cost>0">
                              {{row.currency_type_id}} {{row.sales_cost}}
                            </template>
                               
                            </td>
                            <td class="text-danger font-weight-bold">
                                <template v-if="row.total_sales>0">
                                   {{row.currency_type_id}} {{row.total_sales}}
                                </template>
                            </td> 
                            <td>
                                <template v-if="row.quantity_balance>0">
                                    {{row.quantity_balance}}
                                </template>
                            </td>
                            <td >
                                <template v-if="row.price_balance>0">
                                   {{row.currency_type_id}} {{row.price_balance}}
                                </template>
                            </td>
                            <td class="text-success font-weight-bold">
                                <template v-if="row.total_balance>0">
                                   {{row.currency_type_id}} {{row.total_balance}}
                                </template>
                            </td>
 
                        <!--
                            <td v-if="row.warehouse">{{row.warehouse}}</td>
                            <td v-if="row.item_warehouse_price">{{row.item_warehouse_price}}</td>
                            -->
                        </tr>

                    </data-table>


                </div>
        </div>

    </div>
</template>
<style>
td{
    font-size:11px !important ;
}
</style>
<script>

    import DataTable from '../../components/DataTableKardexAverage.vue'

    export default {
        components: {DataTable},
        data() {
            return {
                resource: 'reports/kardexaverage',
                form: {},
                item_id:null
            }
        },
        created() {
            this.$eventHub.$on('emitItemID', (item_id) => {
                // console.log(item_id)
                this.item_id = item_id
            })
        },
        methods: {


        }
    }
</script>

<template>
    <el-dialog :title="titleDialog"   :visible="showDialog"  @open="create"  :close-on-click-modal="false" :close-on-press-escape="false" :show-close="false">
        <div class="form-body">
            <div class="row" >
                <div class="col-lg-12">
                
                    <data-table :resource="resource" :form="form">
                        <tr slot="heading">
                            <th>#</th>
                            <th class="">Proveedor</th>
                            <th class="text-center">Documento</th>
                            <th class="text-center">Fecha</th>
                            <th class="text-center">Precio</th>  
                        <tr>
                        <tr slot-scope="{ index, row }">
                            <td>{{ index }}</td>
                            <td  class="">{{ row.supplier_name }}<br/><small v-text="row.supplier_number"></small></td>
                            <td  class="text-center">{{ row.number_full }}</td>
                            <td class="text-center">{{ row.date_of_issue }}</td> 
                            <td class="text-center">{{ row.price }} </td>  
                                
                        </tr>
                    </data-table>

                </div>
                
            </div>
        </div>
        
        <div class="form-actions text-end pt-2">
            <el-button @click.prevent="close()">Cerrar</el-button>
        </div>
    </el-dialog>
</template> 

<script>
    import DataTable from '../../components/SimpleDataTableParams.vue'

    export default {
        components: {DataTable},
        props: ['showDialog', 'item_id'],
        data() {
            return {
                titleDialog: 'Historial de compras',
                loading: false,
                resource: 'pos/history-purchases',
                form:{}
            }
        },
        async created() {
             
        },
        methods: {
            initForm(){
                this.form = {
                    item_id : this.item_id,
                    customer_id : null
                }
            },
            async create(){
                await this.initForm()
                await this.$eventHub.$emit('reloadSimpleDataTableParams')
                
            },   
            close() {
                this.$emit('update:showDialog', false)
            }, 
        }
    }
</script>

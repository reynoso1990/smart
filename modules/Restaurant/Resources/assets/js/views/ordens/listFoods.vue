<template>
    <div>
       <div class="row">
             <el-dialog
                title="Platos Atendidos"
                :visible.sync="showDialogFoods"
                  :before-close="closeDialog">
                <span>
                    <form autocomplete="off" @submit.prevent="submit">
                    <div class="form-body">
                        <div class="row" v-loading="loading">
                              <div class="col-md-12">
                                <template v-if="listFoods.length>0">
                                <table class="table table-responsive" >
                                    <thead>
                                        <tr>
                                            <th class="text-center">Cantidad</th>
                                            <th>Producto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(row, index) in listFoods" :key="index">
                                            <td  class="text-center">{{row.move_quantity}}</td>
                                             <td>{{row.description}}</td>
                                         </tr>
                                    </tbody>
                                </table>
                                </template>
                                <template v-else>
                                            No hay Productos
                                </template>
                              </div>

                        </div>
                    </div>
                    </form>
                </span>
                <span slot="footer" class="dialog-footer">
                    <el-button @click="closeDialog()">Cerrar</el-button>
                 </span>

            </el-dialog>
       </div>
    </div>
</template>
<script>



    export default {
         props: ['showDialogFoods',"listFoods"],
        data() {
            return {
                showDialog: false,
                open_cash: true,
                errors: {},
                resource: 'restaurant/worker/cash',
                date_closed:moment().format('YYYY-MM-DD'),
                date_start: moment().format('YYYY-MM-DD'),
                month_start: moment().format('YYYY-MM'),
                final_balance:0,
                cash:null,
                loading:false
            }
        },

        methods: {


            closeDialog(){
                 this.$emit('update:showDialogFoods', false)
            },


        }
    }
</script>

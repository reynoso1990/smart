<template>
    <div>
       <div class="row">
             <el-dialog
                title="Cierra de Caja"
                :visible.sync="showDialogClose"
                @open="dateclosed()"
                 :before-close="closeDialog">
                <span>
                    <form autocomplete="off" @submit.prevent="submit">
                    <div class="form-body">
                        <div class="row" v-loading="loading">
                              <div class="col-md-6">
                                    <div class="form-group" :class="{'has-danger': errors.date_closed}">
                                          <label class="control-label">Fecha de Cierre de Caja</label>
                                            <el-date-picker v-model="date_closed"
                                                            type="date"
                                                            clearable
                                                            format="dd/MM/yyyy"
                                                            value-format="yyyy-MM-dd"
                                                            @change="dateclosed()"

                                                            >
                                            </el-date-picker>
                                    </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group" :class="{'has-danger': errors.final_balance}">
                                          <label class="control-label">Saldo Final</label>
                                            <el-input v-model="final_balance"></el-input>
                                 </div>
                              </div>
                        </div>
                    </div>
                    </form>
                </span>
                <span slot="footer" class="dialog-footer">
                    <el-button @click="closeDialog()">Cerrar</el-button>
                    <el-button type="primary" @click="clickCloseCash()">Guardar</el-button>
                </span>

            </el-dialog>
       </div>
    </div>
</template>
<script>

     //'../../../../components/DataTable.vue'
    import {deletable} from '../../../../../../../resources/js/mixins/deletable'
    ///mixins/deletable'
    import CashForm from './form.vue'

    export default {
        mixins: [deletable],
         props: ['showDialogClose',"recordId"],
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

            clickCloseCash() {

                const h = this.$createElement;
                this.$msgbox({
                    title: 'Cerrar caja',
                    type: 'warning',
                    message: h('p', null, [
                        h('p', { style: 'text-align: justify; font-size:15px' }, '¿Está seguro de cerrar la caja?'),
                    ]),

                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    beforeClose: (action, instance, done) => {
                        if (action === 'confirm') {
                            this.createRegister(instance, done)
                        } else {
                            done();
                        }
                    }
                    })
                    .then(action => {
                        })
                    .catch(action => {
                    });
            },
            async dateclosed(date_closed){
                  this.loading= true
                    await this.$http.get(`/restaurant/cash/balance-final/${this.date_closed}`)
                .then(response => {
                   this.final_balance=(response.data.balance_total).toFixed(2)
                  this.loading= false
                })

            },
            closeDialog(){
                 this.$emit('update:showDialogClose', false)
            },
            async createRegister(instance, done){
                 this.$http.get(`/${this.resource}/close/${this.recordId}/${this.final_balance}`)
                    .then(response => {
                        if(response.data.success){
                            this.$eventHub.$emit('reloadData')
                            this.open_cash = true
                            this.$message.success(response.data.message)
                        }else{
                            console.log(response)
                        }
                    })
                    .catch(error => {
                        console.log(error)
                    })
                    .then(() => {
                        instance.confirmButtonLoading = false
                        instance.confirmButtonText = 'Iniciar prueba'
                        done()
                    })


                instance.confirmButtonLoading = true;
                instance.confirmButtonText = 'Cerrando caja...';
                window.open(`/restaurant/report-boxes/reports_resumen_type?date_end=${this.date_closed}&final_balance=${this.final_balance}&date_start=${this.date_closed}&month_end=${this.month_start}&month_start=${this.month_start}&period=between_dates&type=pdf`, '_blank')
                this.closeDialog()


            },


        }
    }
</script>

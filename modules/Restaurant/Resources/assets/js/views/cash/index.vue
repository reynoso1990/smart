<template>
    <div>
        <div class="container-fluid p-l-0 p-r-0">
            <div class="page-header">
              <div class="row">
                <div class="col-sm-6">
                  <h6><span>Cajas</span></h6>
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                     <li class="breadcrumb-item active"><span class="text-muted">Apertura de Caja</span></li>
                  </ol>
                </div>
            <div class="col-12 col-md-6 d-flex align-items-start justify-content-end">
                 <el-tooltip class="item" effect="dark" content="Aperturar caja" placement="bottom-end">
                    <button type="button" class="btn btn-outline-primary btn-icon btn-icon-start btn-sm" @click.prevent="clickCreate()">
                         <i data-feather="dollar-sign"></i>
                        <span>Nuevo</span>
                    </button>
                 </el-tooltip>
                    <button
                    class="btn btn-sm btn-icon btn-icon-only btn-outline-primary align-top float-end"
                    type="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                    aria-haspopup="true"
                    >
                    <i data-cs-icon="more-horizontal"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                    <a class="dropdown-item" href="javascript:void(0)" @click.prevent="clickDownloadGeneral()">   <i class="fa fa-upload"></i> Reporte general</a>
                     </div>
                  <!-- <div class="bookmark">
                  <ul>
                      <li>
                          <el-tooltip class="item" effect="dark" content="Reporte general" placement="bottom-end">
                            <a href="javascript:void(0)" @click.prevent="clickDownloadGeneral()"
                            data-container="body" data-bs-toggle="popover" data-placement="top" data-original-title="Tables">
                                <i data-feather="bar-chart"></i>
                            </a>
                          </el-tooltip>
                      </li>
                        <li>
                          <el-tooltip class="item" effect="dark" content="Aperturar caja" placement="bottom-end">
                            <a href="javascript:void(0)" @click.prevent="clickCreate()" data-container="body" data-bs-toggle="popover" data-placement="top" data-original-title="Tables">
                               <i data-feather="dollar-sign"></i>
                            </a>
                          </el-tooltip>
                      </li>
                    </ul>
                  </div> -->
                 </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
        <div class="container-fluid p-l-0 p-r-0">
        <div class="card mb-0">
            <div class="card-header bg-primary">
                <h6 class="my-0 text-white">Listado de cajas</h6>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th># Referencia</th>
                        <th>Vendedor</th>
                        <th class="text-center">Apertura</th>
                        <th class="text-center">Cierre</th>
                        <th>Saldo inicial</th>
                        <th>Saldo final</th>
                        <!-- <th>Ingreso</th> -->
                        <!-- <th>Egreso</th> -->
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td>{{ row.reference_number }}</td>
                        <td>{{ row.user }}</td>
                        <td class="text-center">{{ row.opening }}</td>
                        <td class="text-center">{{ row.closed }}</td>
                        <td>{{ row.beginning_balance }}</td>
                        <td>{{ row.final_balance }}</td>
                        <!-- <td>{{ row.income }}</td>
                        <td>{{ row.expense }}</td> -->
                        <td>{{ row.state_description }}</td>
                        <td class="text-center">


                            <template v-if="row.state">
                                <button type="button" class="btn waves-effect waves-light btn-sm btn-warning" @click.prevent="clickCloseCash(row.id)">Cerrar caja</button>
                                <button v-if="typeUser === 'admin'" type="button" class="btn waves-effect waves-light btn-sm btn-info" @click.prevent="clickCreate(row.id)">Editar</button>
                                <button v-if="typeUser === 'admin'" type="button" class="btn waves-effect waves-light btn-sm btn-danger" @click.prevent="clickDelete(row.id)">Eliminar</button>
                            </template>
                            <template>
                                <button type="button" class="btn waves-effect waves-light btn-sm btn-success" @click.prevent="printreport(row.date_closed)" v-if="row.state==false">
                                     <i class="fa fa-print"></i> Imprimir Reporte</button>
                            </template>
                        </td>
                    </tr>
                </data-table>
            </div>

        </div>
        </div>
        <cash-form :showDialog.sync="showDialog" :typeUser="typeUser"
                            :recordId="recordId"></cash-form>
        <CloseCash
            :recordId.sync="recordId"
            :showDialogClose.sync="showDialogClose"

            >
        </CloseCash>
    </div>
</template>

<script>

    import DataTable from '../../../../../../../resources/js/components/DataTable.vue'
    import CloseCash from './closecash.vue'
    //'../../../../components/DataTable.vue'
    import {deletable} from '../../../../../../../resources/js/mixins/deletable'
    ///mixins/deletable'
    import CashForm from './form.vue'

    export default {
        mixins: [deletable],
        components: { DataTable, CashForm,CloseCash},
        props: ['typeUser'],
        data() {
            return {
                showDialog: false,
                showDialogClose:false,
                open_cash: true,
                resource: 'restaurant/worker/cash',
                recordId: null,
                data_closed:moment().format('YYYY-MM-DD'),
                date_start: moment().format('YYYY-MM-DD'),
                month_start: moment().format('YYYY-MM'),

                cash:null,
            }
        },
        async created() {

            /*await this.$http.get(`/${this.resource}/opening_cash`)
                .then(response => {
                    this.cash = response.data.cash
                    this.open_cash = (this.cash) ? false : true
                })*/

            /*this.$eventHub.$on('openCash', () => {
                this.open_cash = false
            })*/

        },
        methods: {
            clickDownload(id) {
                window.open(`/${this.resource}/report/${id}`, '_blank');
            },
            clickDownloadIncomeSummary(id) {
                window.open(`/${this.resource}/report/income-summary/${id}`, '_blank');
            },
            clickCreate(recordId = null) {
                this.recordId = recordId
                this.showDialog = true
            },
            clickCloseCash(recordId) {
                this.recordId = recordId
                this.showDialogClose= true
            },
            printreport(date_closed){
                 window.open(`/restaurant/report-boxes/reports_resumen_type?date_end=${date_closed}&date_start=${date_closed}&month_end=${this.month_start}&month_start=${this.month_start}&period=between_dates&type=pdf`, '_blank')
            },
            createRegister(instance, done){
                instance.confirmButtonLoading = true;
                instance.confirmButtonText = 'Cerrando caja...';
                //this.$http.get(`/${this.resource}/close/${this.recordId}`)
                window.open(`/restaurant/report-boxes/reports_resumen_type?date_end=${this.date_start}&date_start=${this.date_start}&month_end=${this.month_start}&month_start=${this.month_start}&period=between_dates&type=pdf`, '_blank')
                this.showDialogClose=false
                    // .then(response => {
                    //     if(response.data.success){
                             this.$eventHub.$emit('reloadData')
                    //         this.open_cash = true
                    //         this.$message.success(response.data.message)
                    //          window.open(`/restaurant/report-boxes/reports_resumen_type?date_end=${this.date_start}&date_start=${this.date_start}&month_end=${this.month_start}&month_start=${this.month_start}&period=between_dates&type=pdf`, '_blank')
                    //         //window.open(`/${this.resource}/${type}/?${query}`, '_blank')
                    //     }else{
                    //         console.log(response)
                    //     }
                    // })
                    // .catch(error => {
                    //     console.log(error)
                    // })
                    // .then(() => {
                        instance.confirmButtonLoading = false
                 //       instance.confirmButtonText = 'Iniciar prueba'
                    //     done()
                    // })

            },
            clickOpenPos() {
                window.open('/pos')
            },
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            },
            clickDownloadGeneral()
            {
                  window.open(`/${this.resource}/report`, '_blank');
            },
            clickDownloadProducts(id)
            {
                window.open(`/${this.resource}/report/products/${id}`, '_blank');

            }
        }
    }
</script>

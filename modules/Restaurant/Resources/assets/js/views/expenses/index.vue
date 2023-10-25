<template>
    <div>
    <div class="container-fluid p-l-0 p-r-0">
                <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                    <h6><span>{{ title }}</span></h6>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active"><span class="text-muted">{{ title }}</span></li>
                    </ol>
                    </div>
                    <div class="col-12 col-md-6 d-flex align-items-start justify-content-end">
                        <!-- Contact Button Start -->
                        <button type="button" class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto" @click.prevent="clickCreate()">
                        <i class="icofont-plus-circle"></i>
                        <span>Nuevo</span>
                        </button>
                        <!-- Contact Button End -->
                    </div>
                </div>
                </div>
    </div>
          <!-- Container-fluid starts-->
    <div class="container-fluid p-l-0 p-r-0">
        <div class="card mb-0">
            <div class="card-header bg-primary">
                <h6 class="my-0 text-white">Listado de {{ title }}</h6>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th>Descripcion</th>
                        <th>Grupo</th>
                        <th>Categoria</th>
                         <th>Subcategoria</th>
                        <th>Fecha</th>
                        <th>Efectivo</th>
                        <th class="text-end">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td>{{ row.description }}</td>
                        <td>{{ row.groups }}</td>
                        <td>{{ row.categories.category }}</td>
                        <td>{{ row.subcategories.subcategory }}</td>
                         <td>{{ row.date }}</td>
                          <td>{{ row.amount }}</td>

                        <td class="text-end">
                            <button 
                                type="button" 
                                class="btn waves-effect waves-light btn-sm btn-primary" 
                                @click.prevent="clickCreate(row.id)">Editar
                            </button>
                            <button 
                                type="button" 
                                class="btn waves-effect waves-light btn-sm btn-danger"
                                 @click.prevent="clickDelete(row.id)">Eliminar
                            </button>
                        </td>
                    </tr>
                </data-table>
            </div>

            <boxForm
                :showDialog.sync="showDialog"
                :groupid.sync="groupid"
                :categoryid.sync="categoryid"
                :subcategoryid.sync="subcategoryid"
                :userid.sync="userid"
                :soaptypeid.sync="soaptypeid"
                :recordId="recordId">
            </boxForm>

        </div>
    </div>
    </div>
</template>

<script>

    import BoxReport from './report.vue'
    import BoxForm from './form.vue'
    import DataTable from '@components/DataTable.vue'
    import {deletable} from '../../../../../../../resources/js/mixins/deletable'
     export default {
        props: ["groupid","categoryid","userid","subcategoryid","soaptypeid"],
        mixins: [deletable],
        components: {DataTable, BoxForm,BoxReport},
        data() {
            return {
                title: null,
                showDialog: false,
                resource: 'restaurant/worker/expenses',
                recordId: null,
                showDialog_report:false,
            }
        },
        created() {
            this.title = 'Egresos Caja'
        },
        methods: {
            clickCreate(recordId = null) {
                this.recordId = recordId
                this.showDialog = true
            },
            clickReport(){
                //this.recordId = recordId
                this.showDialog_report = true
            },
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            }
        }
    }
</script>

<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>{{ title }}</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header">
                <h3 class="my-0">{{ title }}</h3>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th>Imagen</th>
                        <th>Titulo</th>
                        <th>Tipo de Vinculo</th>
                        <th>Ubicación</th>
                        <th>Fecha registro</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td>
                        <img :src="row.image" class="sw-7 sh-7 me-1 mb-1 d-inline-block bg-separator d-flex d-flex justify-content-center rounded-xl d-flex justify-content-center">    
                        </td>
                        <td>{{ row.title }}</td>
                        <td>{{ row.type }}</td>
                        <td>{{ row.location }}</td>
                        <td>{{ row.created_at }}</td>
                        <td class="text-end">
                            <button type="button" class="btn waves-effect waves-light btn-sm btn-info" @click.prevent="clickCreate(row.id)">Editar</button>
                             <template>
                             <button type="button" class="btn waves-effect waves-light btn-sm btn-danger" @click.prevent="clickDelete(row.id)">Eliminar</button>
                             </template>
                        </td>
                    </tr>
                </data-table>
            </div>

            <person-types-form :showDialog.sync="showDialog"
                          :recordId="recordId" ></person-types-form>
 
        </div>
    </div>
</template>

<script>

    import PersonTypesForm from './form.vue'
    import DataTable from '../../../components/DataTable.vue'
    import {deletable} from '../../../mixins/deletable'

    export default {
        props:['typeUser'],
        mixins: [deletable],
        components: {PersonTypesForm, DataTable},
        data() {
            return {
                title: null,
                showDialog: false,
                showImportDialog: false,
                resource: 'shortcuts',
                recordId: null,
            }
        },
        created() {
            this.title = 'Acceso Rapido'
        },
        methods: {
            clickCreate(recordId = null) {
                this.recordId = recordId
                this.showDialog = true
            },
            clickImport() {
                this.showImportDialog = true
            },
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            }
        }
    }
</script>

<template>
    <div class="card">
        <div class="card-header">
            <h3 class="my-0">Motivos de gastos
                <el-tooltip class="item" effect="dark" content="Gastos Diversos" placement="top-start">
                    <i class="fa fa-info-circle"></i>
                </el-tooltip>
            </h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Descripción</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(row, index) in records" :key="index">
                        <td>{{ index + 1 }}</td>
                        <td>{{ row.description }}</td>
                        <td class="text-end">
                            <button type="button" class="btn waves-effect waves-light btn-sm btn-info" @click.prevent="clickCreate(row.id)">Editar</button>

                              <template v-if="typeUser === 'admin'">
                                 <button type="button" class="btn waves-effect waves-light btn-sm btn-danger"  @click.prevent="clickDelete(row.id)">Eliminar</button>
                              </template>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <!-- <div class="row">
                <div class="col">
                    <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>
                </div>
            </div> -->
        </div>
        <expense-reasons-form :showDialog.sync="showDialog"
                         :recordId="recordId"></expense-reasons-form>
    </div>
</template>

<script>

    import ExpenseReasonsForm from './form.vue'
    import {deletable} from '@mixins/deletable'

    export default {
        mixins: [deletable],
        props: ['typeUser'],
        components: {ExpenseReasonsForm},
        data() {
            return {
                showDialog: false,
                resource: 'expense-reasons',
                recordId: null,
                records: [],
            }
        },
        created() {
            this.$eventHub.$on('reloadData', () => {
                this.getData()
            })
            this.getData()
        },
        methods: {
            getData() {
                this.$http.get(`/${this.resource}/records`)
                    .then(response => {
                        this.records = response.data.data
                    })
            },
            clickCreate(recordId = null) {
                this.recordId = recordId
                this.showDialog = true
            },
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            }
        }
    }
</script>

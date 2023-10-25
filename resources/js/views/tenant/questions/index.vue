<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Preguntas de ChatBot</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <button type="button" class="btn btn-primary btn-sm  mt-2 mr-2" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>
            </div>
        </div>

    <div class="card">
        <div class="card-header">
            <h3 class="my-0">Listado de Preguntas de ChatBot</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Key</th>
                        <th>Pregunta</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(row, index) in records" :key="index">
                        <td>{{ index + 1 }}</td>
                        <td>{{ row.option_key }}</td>
                        <td>{{ row.keywords }}</td>
                        <td>
                        <el-button-group>
                            <el-button type="primary" @click.prevent="clickCreate(row.id)" icon="el-icon-edit" circle></el-button>
                           <el-button type="danger" @click.prevent="clickDelete(row.id)" icon="el-icon-delete" circle></el-button>
                        </el-button-group>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
          </div>
        <Form :showDialog.sync="showDialog" :sender.sync="sender" :recordId="recordId" :api_whatsapp="api_whatsapp"></Form>

</div>
</template>

<script>

    import Form from './form.vue'
    import {functions} from '../../../mixins/functions'
    import {deletable} from '../../../mixins/deletable'

    export default {
        mixins: [functions,deletable],
        components: {Form},
        props: ["sender", "api_whatsapp"],
        data() {
            return {
                showDialog: false,
                resource: this.api_whatsapp+'/api/questions',
                records: [],
                recordId:null,
                data: null,
                form: {},
            }
        },
        created() {
             this.getData()
            this.$eventHub.$on('reloadData', () => {
                this.getData()
            })
        },
        methods: {

            getData() {
                this.$http.get(`${this.resource}/records/`+this.sender)
                    .then(response => {
                        this.records = response.data.data
                        if (this.records.length) {
                            this.form.last_date = this.records[0].date
                        }
                    })
            },
            clickCreate(id) {
                this.recordId = id
                this.showDialog = true
            },
            clickDelete(id) {
                this.destroy(`${this.resource}/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            }
        }
    }
</script>

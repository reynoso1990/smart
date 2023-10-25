<template>
    <el-dialog :title="title" :visible="showDialog" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">

                    <div class="col-lg-12">
                        <div class="form-group" :class="{'has-danger': errors.keywords}">
                            <label class="control-label">Pregunta</label>
                            <el-input v-model="form.keywords"></el-input>
                            <small class="text-danger" v-if="errors.keywords" v-text="errors.keywords[0]"></small>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group" :class="{'has-danger': errors.option_key}">
                            <label class="control-label">Palabra Clave</label>
                            <el-input v-model="form.option_key"></el-input>
                            <small class="text-danger" v-if="errors.option_key" v-text="errors.option_key[0]"></small>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group" :class="{'has-danger': errors.phone}">
                            <label class="control-label w-100">N° Celular</label>
                             {{form.phone}}
                            <small class="text-danger" v-if="errors.date" v-text="errors.phone[0]"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-end mt-4">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
            </div>
        </form>
    </el-dialog>

</template>

<script>

    import {functions} from '../../../mixins/functions'

    export default {
        mixins: [functions],
        props: ["sender", "api_whatsapp","showDialog","recordId"],
        data() {
            return {
                resource: this.api_whatsapp+'/api/questions',
                errors: {},
                form: {},
                loading_submit:false,
                data: null,
                title:null
            }
        },
        created() {
            this.initForm()
        },
        methods: {
            initForm() {
                this.errors = {}
                this.form = {
                    id:null,
                    option_key: null,
                    keywords: null,
                    phone: this.sender,
                    user_id: 1,
                }
            },
            async create(){
                if (this.recordId) {
                    this.title= "Editar Pregunta de ChatBoot"
                await this.$http
                    .get(`${this.resource}/record/${this.recordId}`)
                    .then(response => {
                        this.form = response.data.data[0];
                    });
                }else{
                    this.title= "Nuevo Pregunta de ChatBoot"
                }

            },
            submit() {
                this.loading_submit = true
                this.$http.post(`${this.resource}`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            this.$eventHub.$emit('reloadData')
                            this.close()
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data
                        } else {
                            console.log(error)
                        }
                    })
                    .then(() => {
                        this.loading_submit = false
                    })
            },
            close() {
                this.$emit('update:showDialog', false)
                this.initForm()
            },
        }
    }
</script>

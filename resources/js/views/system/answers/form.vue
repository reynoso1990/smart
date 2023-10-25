<template>
    <el-dialog :title="title" :visible="showDialog" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">

                    <div class="col-lg-12">
                        <div class="form-group" :class="{'has-danger': errors.replyMessage}">
                            <label class="control-label">Respuesta</label>
                            <el-input v-model="form.replyMessage"
                            type="textarea"
                            :autosize="{ minRows: 2, maxRows: 4}">
                            </el-input>
                            <small class="text-danger" v-if="errors.replyMessage" v-text="errors.replyMessage[0]"></small>
                        </div>
                    </div>
                    <div class="col-lg-4 pt-2">
                        <div class="form-group" :class="{'has-danger': errors.option_key}">
                            <label class="control-label w-100">Palabra Clave</label>
                            <el-select
                                v-model="form.option_key"
                                filterable>
                                <el-option
                                    v-for="option in options"
                                    :key="option.id"
                                    :label="option.replyMessage"
                                    :value="option.option_key"
                                ></el-option>
                            </el-select>
                            <small class="text-danger" v-if="errors.option_key" v-text="errors.option_key[0]"></small>
                        </div>
                    </div>
                    <div class="col-lg-4 pt-2">
                        <div class="form-group" :class="{'has-danger': errors.type}">
                            <label class="control-label w-100">Tipo de Contenido</label>
                            <el-select
                                v-model="form.type"
                                filterable>
                                <el-option
                                v-for="item in options_data"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value"
                                :disabled="item.disabled">
                                </el-option>
                            </el-select>
                            <small class="text-danger" v-if="errors.type" v-text="errors.type[0]"></small>
                        </div>
                    </div>
                    <div class="col-lg-4 pt-2">
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
                resource: this.api_whatsapp+'/api/answers',
                errors: {},
                form: {},
                loading_submit:false,
                data: null,
                options:[],
                options_data:[{
                value: 'chat',
                label: 'chat'
                }, {
                value: 'link',
                label: 'link',
                disabled: true
                }, {
                value: 'media',
                label: 'media',
                disabled: true
                }],
                title:null
            }
        },
        async created() {
            this.initForm()

        },
        methods: {
            initForm() {
                this.errors = {}
                this.form = {
                    id:null,
                    option_key: null,
                    replyMessage: null,
                    phone: this.sender,
                    type: 'chat',
                    media: '',
                }
            },
            async create(){
                console.log("recordId",this.recordId)
                await this.$http
                    .get(`${this.api_whatsapp}/api/questions/records/${this.sender}`)
                    .then(response => {
                        //console.log("response",response.data.data);
                        this.options = response.data.data
                    });
                if (this.recordId) {
                    this.title= "Editar Respuesta de ChatBoot"
                await this.$http
                    .get(`${this.resource}/record/${this.recordId}`)
                    .then(response => {
                        this.form = response.data.data[0];
                    });
                }else{
                    this.title= "Nuevo Respuesta de ChatBoot"
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

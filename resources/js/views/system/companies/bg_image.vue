<template>
    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="my-0">Imagen de Fondo</h3>
            </div>
            <div class="card-body">
                <form autocomplete="off"
                      @submit.prevent="submit">
                    <div class="form-body">
                        
                        <div class="row">
                             
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Logo</label>
                                    <el-input v-model="form.logo"
                                              :readonly="true">
                                        <el-upload slot="append"
                                                   :data="{'type': 'logo_admin'}"
                                                   :headers="headers"
                                                   :on-success="successUpload"
                                                   :on-error="errorUpload"
                                                   :show-file-list="false"
                                                   action="/configurations/bg_imagen">
                                            <el-button icon="el-icon-upload"
                                                       type="primary"></el-button>
                                        </el-upload>
                                    </el-input>
                                    <div class="sub-title text-danger"><small>Se recomienda 700x300 con fondo transparente</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Imagen Fondo</label>
                                    <el-input v-model="form.bg_image"
                                              :readonly="true">
                                        <el-upload slot="append"
                                                   :data="{'type': 'bg_default_admin'}"
                                                   :headers="headers"
                                                   :on-success="successUpload"
                                                   :on-error="errorUpload"
                                                   :show-file-list="false"
                                                   action="/configurations/bg_imagen">
                                            <el-button icon="el-icon-upload"
                                                       type="primary"></el-button>
                                        </el-upload>
                                    </el-input>
                                    <div class="sub-title text-danger"><small>Se recomienda resoluciones 700x300</small>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
    
                    </div>
                    <div class="form-actions text-end pt-2">
                       
                    </div>
                </form>
            </div>
        </div>
     
        
    </div>
</template>

<script>
import {mapActions, mapState} from "vuex";

export default {
    computed: {
        ...mapState([
            'config',
        ]),
    },
    data() {
        return {
            loading_submit: false,
            headers: headers_token,
            resource: 'configurations',
            errors: {},
            form: {},
            soap_sends: [],
            soap_types: [],
            toggle: false, //Creando el objeto a retornar con v-model
        }
    },
    async created() {
        await this.initForm()
        await this.$http.get(`/${this.resource}/record`)
            .then(response => {
                    this.form.bg_image = response.data.configuration.bg_image
                    this.form.logo = response.data.configuration.logo
                    this.form.whatsapp =response.data.configuration.whatsapp
            })
        this.events()
    },
    methods: {
        ...mapActions([
            'loadConfiguration',
        ]),
        events(){

            this.$eventHub.$on('reloadDataCompany', () => {
                this.getRecord()
            })

        },
        async getRecord(){

            await this.$http.get(`/${this.resource}/record`)
                    .then(response => {
                        if (response.data !== '') {
                            this.form = response.data.data
                        }
                    })
        },
        initForm() {
            this.errors = {}
            this.form = {
                id: null,
                bg_image:null,
                logo: null,
                whatsapp:null
            }
        },
        submit() {
            this.loading_submit = true
            this.$http.post(`/${this.resource}`, this.form)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message)
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
        successUpload(response, file, fileList) {

            if (response.success) {
                this.$message.success(response.message)
                if(response.type == 'bg_default_admin'){
                    this.form.bg_image= response.name
                }else{
                    this.form.logo= response.name
                }
              
            } else {
                this.$message({message: 'Error al subir el archivo', type: 'error'})
            }
        },
        errorUpload(error)
        {
            this.$message({message: 'Error al subir el archivo', type: 'error'})
        }
    }
}
</script>

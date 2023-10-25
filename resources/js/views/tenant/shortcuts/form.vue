<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="submit">
           <div class="row"  v-loading="loading_data">
            <div class="col-md-9">
                <div class="row">
                   <div class="col-md-12">
                    <div class="form-group" :class="{'has-danger': errors.title}">
                        <label class="control-label">Titulo <span class="text-danger">*</span></label>
                        <el-input v-model="form.title" dusk="description"></el-input>
                        <small class="text-danger" v-if="errors.title" v-text="errors.title[0]"></small>
                    </div>
                   </div>
                   <div class="col-md-12">
                    <div class="form-group" :class="{'has-danger': errors.description}">
                        <label class="control-label">Link https <span class="text-danger">*</span></label>
                        <el-input v-model="form.link" dusk="description"></el-input>
                        <small class="text-danger" v-if="errors.description" v-text="errors.description[0]"></small>
                    </div>
                   </div>
                   <div class="col-md-8">
                    <div class="form-group" :class="{'has-danger': errors.location}">
                        <label class="control-label">Posición de Acceso Rapido <span class="text-danger">*</span></label>
                        <el-select v-model="form.location" clearable >
                            <el-option
                                label="Izquierda"
                                value="Izquierda">
                            </el-option>
                            <el-option
                                label="Derecha"
                                value="Derecha">
                            </el-option>
                        </el-select>
                        <small class="text-danger" v-if="errors.location" v-text="errors.location[0]"></small>
                    </div>
                   </div>
                   <div class="col-md-4">
                    <div class="form-group" :class="{'has-danger': errors.type}">
                        <label class="control-label">video de Youtube <span class="text-danger">*</span></label>
                        <el-switch
                            v-model="form.type"
                            active-text="Si"
                            inactive-text="No">
                        </el-switch>
                         <small class="text-danger" v-if="errors.type" v-text="errors.type[0]"></small>
                    </div>
                   </div>
                </div>
            </div>
            <div class="col-md-3">
                <label class="control-label">Imagen <span class="text-danger">*</span></label>
                <el-upload
                        action="/shortcuts/uploads"
                        :data="{'type': 'shortcuts'}"
                        :headers="headers"
                        :on-success="successUpload"
                        :on-error="errorUpload"
                        :show-file-list="false"
                        class="avatar-uploader" >
                        <template v-if="!recordId">
                            <img
                            v-if="form.image"
                            :src="form.image"
                            class="avatar"
                        />
                        </template>
                        <template v-if="recordId">
                            <img
                            v-if="form.image_link"
                            :src="form.image_link"
                            class="avatar"
                        />
                        </template>
                        <i v-if="form.image==null" class="el-icon-plus avatar-uploader-icon" style="line-height:none !important"></i>
                    </el-upload>
                 <small class="text-danger" v-if="errors.image" v-text="errors.image[0]"></small>
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


    export default {
        props: ['showDialog', 'recordId'],
        data() {
            return {
                titleDialog: null,
                loading_submit: false,
                loading_data:false,
                resource: 'shortcuts',
                errors: {},
                form: {}, 
                headers: headers_token,
            }
        },
        created() {
            this.initForm() 
        }, 
        methods: {
            initForm() {
                this.errors = {}
                this.form = {
                    id: null,
                    image: null,
                    description:"",
                    type:false,
                    location:null,
                    temp_path:null,
                    filename:null,
                    image_link:null
                }
            },
            create() { 
                this.titleDialog = (this.recordId)? 'Editar Acceso Directo':'Nuevo Acceso Directo'

                if (this.recordId) {
                    this.loading_data = true 
                    this.$http.get(`/${this.resource}/record/${this.recordId}`)
                        .then(response => {
                            this.form = response.data.data
                            if(response.data.data.image_link!=null){
                                this.form.image_link = response.data.data.image_link;
                            }
                        this.loading_data = false 
                        })
                }
            }, 
            successUpload(response, file, fileList) {
                if (response.success) {
                    
                    this.form.filename = response.data.filename;
                    this.form.image = response.data.temp_image;
                    if(this.recordId){
                        this.form.image_link = response.data.temp_image;
                    }
                    this.form.temp_path = response.data.temp_path;
                } else {
                    this.$message.error(response.message);
                }
            },
            errorUpload(error)
            {
            this.$message({message: 'Error al subir el archivo', type: 'error'})
            },
            submit() {
                this.loading_submit = true
                this.$http.post(`/${this.resource}`, this.form)
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
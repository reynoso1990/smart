<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.name}">
                            <label class="control-label">Nombre</label>
                            <el-input v-model="form.name"></el-input>
                            <small class="text-danger" v-if="errors.name" v-text="errors.name[0]"></small>
                        </div>
                    </div> 
                      <div class="col-md-6">
                        <div class="form-group">
                            <div class="control-label">Imagen</div>
                              <div>
                                        <input
                                            type="file"
                                            ref="fileInput"
                                            style="display: none"
                                            @change="handleFileChange"
                                            accept="image/*"
                                        />
                                        <el-button
                                            type="primary"
                                            @click="openFileInput"
                                            >Seleccionar imagen</el-button
                                        >
                                        <div>
                            <img v-if="form.image" :src="form.image" alt="" width="250" height="250">

                                            <img v-if="selectedImage"
                                                :style="imageStyle"
                                                :src="selectedImage"
                                                alt="Imagen seleccionada"
                                                 width="250" height="250"
                                            />
                                        </div>
                                    </div>
                        </div>
                    </div>
                </div> 
            </div>
            <div class="form-actions text-end pt-2">
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
                loading_submit: false,
                titleDialog: null,
                resource: 'brands', 
                errors: {}, 
                form: {}, 
                   selectedImage: null,
                imageStyle: {
                objectFit: "cover",
                width: "250px",
                height: "250px"
            },
            }
        },
        created() {
            this.initForm() 
        },
        methods: {
                   openFileInput() {
            this.$refs.fileInput.click();
        },
              handleFileChange(event) {
            const file = event.target.files[0];
            const allowedTypes = ["image/jpeg", "image/png", "image/gif"];
            if (file && allowedTypes.includes(file.type)) {
                this.form.file = file;
                const reader = new FileReader();
                reader.onload = () => {
                    this.form.image = null;
                    this.selectedImage = reader.result;
                };
                reader.readAsDataURL(file);
            } else {
                this.selectedImage = null;
                this.$message.error(
                    "Por favor, seleccione un archivo de imagen válido (JPEG, PNG o GIF)."
                );
            }
        },
            initForm() { 
                                this.selectedImage = null

                this.errors = {} 

                this.form = {
                    id: null,
                    name: null, 
                    image:null,
                    file:null,
                }
            },
            create() {

                this.titleDialog = (this.recordId)? 'Editar marca':'Nueva marca'
                if (this.recordId) {
                    this.$http.get(`/${this.resource}/record/${this.recordId}`).then(response => {
                            this.form = response.data
                        })
                }
            },
            submit() {   
 

                this.loading_submit = true  
                           const formData = new FormData()
                formData.append('id', this.form.id)
                formData.append('name', this.form.name)
                if(this.form.file)
                formData.append('image', this.form.file)
                this.$http.post(`${this.resource}`, formData, {headers: {'Content-Type': 'multipart/form-data'}})
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
                            console.log(error.response)
                        }
                    })
                    .then(() => {
                        this.loading_submit = false
                    })
                    
            },  
            close() {
                this.$emit('update:showDialog', false)
                this.initForm()
            }
        }
    }
</script>
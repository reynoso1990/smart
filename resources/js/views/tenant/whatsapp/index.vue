<template>
    <div>
        <div class="page-header pr-0">
            <h2>
                <a href="/dashboard">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                        <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
                    </svg>
                </a>
            </h2>
            <ol class="breadcrumbs">
                <li class="active">
                    <span>Cuenta de Whatsapp</span>
                </li>
            </ol>

        </div>
        <div class="card mb-0">
            <div class="card-header">
                <h3 class="my-0 font-weight-bold">
                    <span  :class="{
                            'text-danger': state === false,
                            'text-success': state === true
                        }">
                        {{status_whatsapp}}
                    </span>
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div :class="{'has-danger': errors.identity_document_type_id}" class="form-group">
                            <label class="control-label">N° Empresa
                            </label>
                             {{ form.name }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div :class="{'has-danger': errors.identity_document_type_id}" class="form-group">
                            <label class="control-label">N° Celular Whatsapp<span class="text-danger">*</span>
                            </label>
                            <el-input v-model="form.sender" :readonly="true">
                                <el-button
                                    v-if="state == false"
                                    slot="append"
                                    :loading="loading_generar"
                                     @click.prevent="generarQr()">
                                    <template>
                                        <svg   xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                                            <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
                                        </svg>
                                             Generar QR Whatsapp
                                     </template>
                                </el-button>
                                <el-button
                                    slot="append"
                                    :loading="loading_status"
                                     @click.prevent="statuswhatsap()">
                                    <template>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                            <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                                        </svg>
                                            Estado de Whatsapp
                                    </template>
                                </el-button>
                                <el-button
                                    v-if="state == true"
                                    slot="append"
                                    :loading="loading_logout"
                                     @click.prevent="cerrarSession()">
                                    <template>
                                        <svg   xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                                            <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
                                        </svg>

                                            Cerrar Session

                                    </template>
                                </el-button>
                            </el-input>
                            <small v-if="errors.identity_document_type_id"
                                    class="text-danger"
                                    v-text="errors.identity_document_type_id[0]">
                            </small>
                        </div>
                    </div>

                </div>
            </div>


        </div>
    </div>
</template>
<script>


export default {
    props: ["api_whatsapp", "sender", "name"],
     data() {
        return {
            errors:[],
            loading_generar:false,
            loading_logout:false,
            loading_status:false,
            resource: this.api_whatsapp,
            state:null,
            status_whatsapp:"Whatsapp no conectado",

            form: {
                sender:this.sender,
                name: this.name,
            },

        };
    },
    async created() {
        this.loading_status = true
        await this.$http.post(`${this.resource}/api/status`,this.form)
        .then(response => {
            this.loading_status = false
            this.status_whatsapp = response.data.message
            this.state =response.data.success
        }) .catch(error => {
            if (error.response.status === 500) {
                this.$message.error('Error al intentar');
            } else {
                console.log(error.response.data.message)
            }
            this.loading_status = false
        });


    },

    methods: {
        async statuswhatsap(){
            await this.$http.post(`${this.resource}/api/status`,this.form)
            .then(response => {
                this.status_whatsapp = response.data.message
                this.$message.success(response.data.message)
                this.state =response.data.success
            }).catch(error => {
                if (error.response.status === 500) {
                    this.$message.error('Error al intentar');
                } else {
                    console.log(error.response.data.message)
                }
                this.loading_logout = false
            });
        },
        cerrarSession(){
            this.$confirm('¿Desea Cerrar Session de '+this.form.name+' ?', 'Eliminar', {
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    type: 'warning'
                }).then(() => {
                    this.loading_logout = true
                    this.$http.post(`${this.resource}/logoutwhatsapp`,this.form).then(res => {
                          console.log("response generar",res.data);
                            if(res.data.success) {
                                this.$message.success(res.data.message)
                                this.loading_logout = false
                                this.status_whatsapp = "Whatsapp no conectado"
                                this.state =false
                            }else{
                                this.$message.error(res.data.message)
                                this.loading_logout = false
                            }
                        })
                        .catch(error => {
                            if (error.response.status === 500) {
                                this.$message.error('Error al intentar');
                            } else {
                                console.log(error.response.data.message)
                            }
                            this.loading_logout = false
                        })
                }).catch(error => {
                    this.loading_generar=false;
                    console.log(error)
                });
        },
        generarQr() {
            this.$confirm('¿Desea Generar  QR Whatsapp de '+this.form.name+' ?', 'Eliminar', {
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    type: 'warning'
                }).then(() => {
                    this.loading_logout = true
                    this.$http.post(`${this.resource}/sessionwhatsapp`,this.form).then(res => {
                          console.log("response generar",res.data);
                            if(res.data.success) {
                                window.open(res.data.data.link,'_blank');
                                this.$message.success(res.data.data.message)
                                this.loading_logout = false
                            }else{
                                this.$message.error(res.data.data)
                                this.loading_logout = false
                            }
                        })
                        .catch(error => {
                            if (error.response.status === 500) {
                                this.$message.error('Error al intentar');
                            } else {
                                console.log(error.response.data.message)
                            }
                            this.loading_logout = false
                        })
                }).catch(error => {
                    this.loading_generar=false;
                    console.log(error)
                });
        },


    }
};
</script>

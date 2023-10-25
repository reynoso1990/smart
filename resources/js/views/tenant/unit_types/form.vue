<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <template v-if="showDialogNew==true">
                        <div class="col-md-3">
                            <div class="form-group" :class="{'has-danger': errors.code}">
                                <label class="control-label">Codigo
                                    <a href="javascript:void(0)"
                                        @click.prevent="showDialogNew = false">Cancelar</a>
                                </label>
                                <el-input v-model="formunit.code"></el-input>
                                <small class="text-danger" v-if="errors.code" v-text="errors.code[0]"></small>
                            </div>
                        </div>

                        <div class="col-md-9">
                            <div class="form-group" :class="{'has-danger': errors.description}">
                                <label class="control-label">Descripción</label>
                                <el-input v-model="formunit.description">
                                    <el-button slot="append" icon="el-icon-check" @click="save()" :loading="loading">Guardar</el-button>
                                </el-input>
                                <small class="text-danger" v-if="errors.description" v-text="errors.description[0]"></small>
                            </div>
                        </div>
                    </template>
                    <template  v-else>
                        <div class="col-md-8">
                        <div class="form-group" :class="{'has-danger': errors.description}">
                            <label class="control-label">Descripción
                                <a href="javascript:void(0)"
                                        @click.prevent="showDialogNew = true">[+ Nuevo]</a>
                            </label>
                            <!-- <el-input v-model="form.description"></el-input> -->
                             <el-select
                                v-model="form.list_units_id"
                                filterable
                                remote 
                                reserve-keyword
                                placeholder="Please enter a keyword"
                                :remote-method="searchRemoteItems"
                                :loading="loading_search"
                                @change="changeItem">
                                <el-option
                                v-for="item in list_units"
                                :key="item.id"
                                :label="item.description"
                                :value="item.id">
                                </el-option>
                            </el-select>
                        
                            <small class="text-danger" v-if="errors.description" v-text="errors.description[0]"></small>
                        </div>
                    </div>
                     <div class="col-md-2">
                        <div class="form-group" :class="{ 'has-danger': errors.id }">
                            <label class="control-label">Código</label>
                            <el-input v-model="form.id" :readonly="true"></el-input>
                            <small class="text-danger" v-if="errors.id" v-text="errors.id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group" :class="{'has-danger': errors.symbol}">
                            <label class="control-label">Símbolo</label>
                            <el-input v-model="form.symbol"></el-input>
                            <small class="text-danger" v-if="errors.symbol" v-text="errors.symbol[0]"></small>
                        </div>
                    </div>
                    </template>
                    
                    <div class="col-md-4" v-if="showDialogNew==false">
                        <div class="form-group" :class="{'has-danger': errors.active}">
                            <label class="control-label">Activo</label>
                            <el-switch v-model="form.active" active-text="Si" inactive-text="No"></el-switch>
                            <small class="text-danger" v-if="errors.active" v-text="errors.active[0]"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-end mt-4" >
                <el-button @click.prevent="close()" >Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit" v-if="showDialogNew==false">Guardar</el-button>
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
                loading:false,
                showDialogNew:false,
                titleDialog: null,
                resource: 'unit_types',
                list_units:[],
                errors: {},
                form: {},
                formunit:{},
                loading_search:false,
                options: [],
            }
        },
        created() {
            this.initForm()
            this.searchRemoteItems(" ")
        },
        methods: {
            initForm() {
                this.errors = {}
                this.formunit={
                    code:null,
                    description: "",
                }
                this.form = {
                    id: null,
                    description: "",
                    symbol: null,
                    list_units_id:null,
                    active: true
                }
            },
            async searchRemoteItems(input) {
                 this.list_units = []
                 if (input.length > 2) {
                    this.loading_search = true;
                    const params = {
                        input: input,
                    };
                    await this.$http
                        .get(`/unitmeasure/records`, { params })
                        .then(response => {
                            this.list_units = response.data.unitmeasure;
                            this.loading_search = false;
                    });
                 } else {
                 const params = {
                    input: "",
                };
               await this.$http.get(`/unitmeasure/records`, { params })
                    .then(response => {
                        this.list_units = response.data.unitmeasure;
                         
                    });
                this.loading_search = false;
            }
                
            },
            changeItem() {
                let code = _.find(this.list_units, { id : this.form.list_units_id });
                this.form.description = code.description;
                this.form.id = code.code;
           },
            save(){
                this.loading = true
                this.$http.post(`/list-units/store`, this.formunit)
                    .then(async response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            this.form.list_units_id = response.data.data.id
                            await this.searchRemoteItems(this.formunit.description)
                            let code = _.find(this.list_units, { id : this.form.list_units_id });
                            this.form.description = code.description;
                            this.form.id = code.code;
                            //this.changeItem();
                            this.showDialogNew=false
                            this.formunit={
                                code:null,
                                description: "",
                            }
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        this.loading = false
                        console.log("error",error)
                        if (error.response.status === 422) {
                            this.errors = error.response.data
                        } else {
                            console.log(error)
                        }
                    })
                    .then(() => {
                        this.loading = false
                    })
            },
            create() {
                this.titleDialog = (this.recordId)? 'Editar Unidad':'Nueva Unidad'
                if (this.recordId) {
                    this.$http.get(`/${this.resource}/record/${this.recordId}`)
                        .then(response => {
                            this.form = response.data.data
                        })
                }
                
                // this.$http.get(`/${this.resource}/tables`).then(response => {
                //     this.list_units = response.data.data
                // })
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

<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                 
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group"  >
                            <label class="control-label">Vendedor</label>
                            <!--<el-input v-model="form.user" readonly> <i slot="prefix" class="el-icon-edit-outline"></i></el-input> -->
                             <el-select :disabled="disableUser" v-model="form.user_id">
                                <el-option v-for="option in users" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <small class="text-danger" v-if="errors.user" v-text="errors.user[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.date_opening}">
                            <label class="control-label">Fecha de Apertura</label>
                            <el-date-picker
                            v-model="form.date_opening"
                            value-format="yyyy-MM-dd"
                            format="dd-MM-yyyy"
                            type="date"
                            placeholder="Pick a day">
                            </el-date-picker>
                            <!-- <el-input v-model="form.date_opening"> <i slot="prefix" class="el-icon-edit-outline"></i></el-input> -->
                            <small class="text-danger" v-if="errors.date_opening" v-text="errors.date_opening[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.beginning_balance}">
                            <label class="control-label">Saldo inicial</label>
                            <el-input v-model="form.beginning_balance"> <i slot="prefix" class="el-icon-edit-outline"></i></el-input>
                            <small class="text-danger" v-if="errors.beginning_balance" v-text="errors.beginning_balance[0]"></small>
                        </div>
                    </div>
                    <!-- <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.reference_number}">
                            <label class="control-label">Número de Referencia</label>
                            <el-input :maxlength="10" v-model="form.reference_number"> <i slot="prefix" class="el-icon-edit-outline"></i></el-input>
                            <small class="text-danger" v-if="errors.reference_number" v-text="errors.reference_number[0]"></small>
                        </div>
                    </div> -->
                </div> 
            </div>
            <div class="form-actions text-end pt-2 pb-2">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>


    export default {
        props: ['showDialog', 'recordId', 'typeUser'],
        data() {
            return {
                loading_submit: false,
                titleDialog: null,
                resource: 'restaurant/worker/cash',
                errors: {},
                form: {},
                user: {},
                all_departments: [],
                all_provinces: [],
                all_districts: [],
                provinces: [],
                districts: [],
                identity_document_types: [],
                users: []
            }
        },
        async created() {

           await this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.users = response.data.users
                    this.user = response.data.user
                })

            this.initForm()

        },
        computed: {
            disableUser() {
                if(this.typeUser == 'admin')
                {
                    return false
                }
                return true
            }
        },
        methods: {
            
            initForm() {
                this.errors = {}
                this.form = {
                    id: null,
                    user_id: this.user.id,
                   // user: null,
                    date_opening: moment().format('YYYY-MM-DD'),
                    time_opening: null,
                    date_closed: null,
                    time_closed: null,
                    beginning_balance: 0,
                    final_balance: 0,
                    income: 0, 
                    state: true,
                    reference_number: null
                }
            },
            create() {
                this.titleDialog = (this.recordId)? 'Editar Caja':'Aperturar Caja'
                if (this.recordId) {
                    this.$http.get(`/${this.resource}/record/${this.recordId}`)
                        .then(response => {
                            this.form = response.data.data 
                        })
                }else{                    
                    this.form.user_id = this.user.id //sesion
                    //this.form.user = this.user.name
                }
            },
            async openingCashCkeck()
            {
                let response =  await this.$http.get(`/${this.resource}/opening_cash_check/${this.form.user_id}`)
                    .then(response => {
                        let cash = response.data.cash 
                        return (cash) ? true : false                   
                    })
                return response
            },
            async submit() {
                this.loading_submit = true
                if(!this.recordId)
                {
                    if(await this.openingCashCkeck())
                    {
                        this.$message({
                            message: 'No puede crear caja, porfavor cierre caja para el usuario definido',
                            type: 'warning',
                            duration: 5000
                        });
                        this.loading_submit = false
                        return false
                    }
                }

              
                this.$http.post(`/${this.resource}`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            if(this.form.user_id === this.user.id) this.$eventHub.$emit('openCash')   
                            this.$eventHub.$emit('reloadData')                                                      
                            // window.open('/pos/init')
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
            } 
        }
    }
</script>
<template>
    <div class="card">
        <div class="card-header">
            <h3 class="my-0">Métodos de pago - ingreso
                <el-tooltip class="item" effect="dark" content="Manejo interno de la empresa / Ingresos" placement="top-start">
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
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Condición de pago</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(row, index) in records" :key="index">
                        <td>{{ index + 1 }}</td>
                        <td>{{ row.id }}</td>
                        <td>{{ row.description }}</td>
                        <td>
                            <el-select
                            v-model="row.condition_payment"
                            filterable
                            @change="changeConditionPayment(row)"
                            >
                                <el-option
                                    v-for="item in methods_payment"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                                </el-option>
                            </el-select>
                        </td>
                        <td class="text-end">

                            <template v-if="row.show_actions">

                                <button type="button" class="btn waves-effect waves-light btn-sm btn-info" @click.prevent="clickCreate(row.id)">Editar</button>

                                <template v-if="typeUser === 'admin'">
                                    <button type="button" class="btn waves-effect waves-light btn-sm btn-danger"  @click.prevent="clickDelete(row.id)">Eliminar</button>
                                </template>

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
        <payment-method-types-form :showDialog.sync="showDialog"
                         :recordId="recordId"></payment-method-types-form>
    </div>
</template>

<script>

    import PaymentMethodTypesForm from './form.vue'
    import {deletable} from '@mixins/deletable'

    export default {
        mixins: [deletable],
        props: ['typeUser'],
        components: {PaymentMethodTypesForm},
        data() {
            return {
                methods_payment: [
                    {
                        value:"is_cash",
                        label:"Contado"
                    },
                    {
                        value:"is_digital",
                        label:"Digital"  
                    },
                    {
                        value:"is_bank",
                        label:"Banco"  
                    },
                    {
                        value:"is_credit",
                        label:"Crédito"
                    }
                ],
                showDialog: false,
                resource: 'payment-method-types',
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
            changeConditionPayment(row){
                this.$http.post(`/${this.resource}/change_type`, {
                    id: row.id,
                    type: row.condition_payment
                })
                    .then(response => {
                        let {success} = response.data
                        if (success) {
                            this.$message.success('Se ha actualizado correctamente')
                        } else {
                            this.$message.error('Ha ocurrido un error')
                        }
                        this.$eventHub.$emit('reloadData')
                    });
            },
            getConditionPayment(row){
                let{is_cash, is_digital, is_bank, is_credit} = row;
                if(is_cash){
                    return "is_cash";
                }
                if(is_digital){
                    return "is_digital";
                }
                if(is_bank){
                    return "is_bank";
                }
                if(is_credit){
                    return "is_credit";
                }
            },
            getData() {
                this.$http.get(`/${this.resource}/records`)
                    .then(response => {
                        this.records = response.data.data
                        this.records = this.records.map(row => {
                            row.condition_payment = this.getConditionPayment(row)
                            return row
                        });

                    });
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

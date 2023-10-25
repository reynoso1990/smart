<template>
    <div>
        <header class="page-header">
            <h2><a href="/dashboard"><i class="fa fa-list-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Planes</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <button type="button" class="btn btn-primary btn-sm  mt-2 mr-2" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>
            </div>
        </header>
        
            <div class="row row-cols-1 row-cols-lg-3 g-2">
					
                    <template v-for="(row, index) in records">
                        <div class="col" :key="index">
                                <div class="card h-100 hover-scale-up">
                                <div class="card-body pb-0">
                                    <div class="d-flex flex-column align-items-center mb-4">
                                    <div class="bg-gradient-light sw-6 sh-6 rounded-xl d-flex justify-content-center align-items-center mb-2">
                                        <i class="text-white" data-acorn-icon="building-small"></i>
                                    </div>
                                    <div class="cta-4 text-primary mb-1">{{row.name}}</div>
                                     <div class="display-4">S/ {{row.pricing}}</div>
                                     </div>
                                   
                                </div>
                                <div class="card-footer pt-0 border-0">
                                    <div class="mb-4">
                                     
                                    <div class="row g-0 mb-2">
                                        <div class="col-auto">
                                        <div class="sw-3 me-1">
                                            <i class="d-inline-block text-primary align-top" data-acorn-icon="help" data-acorn-size="17"></i>
                                        </div>
                                        </div>
                                        <div class="col lh-1-25 text-alternate">
                                            <template v-if="row.limit_users === 0"><strong>Usuarios</strong> ilimitados</template>
                                            <template v-else><strong>{{row.limit_users}}</strong> usuarios</template>
                                        </div>
                                    </div>
                                    <div class="row g-0 mb-2">
                                        <div class="col-auto">
                                        <div class="sw-3 me-1">
                                            <i class="d-inline-block text-primary align-top" data-acorn-icon="clipboard" data-acorn-size="17"></i>
                                        </div>
                                        </div>
                                        <div class="col lh-1-25 text-alternate">
                                            <template v-if="row.limit_documents === 0"><strong>Comprobantes</strong> ilimitados</template>                                
                                            <template v-else><strong>{{row.limit_documents}}</strong> comprobantes</template>

                                        </div>
                                    </div>
                                    <div class="row g-0 mb-2">
                                        <div class="col-auto">
                                        <div class="sw-3 me-1">
                                            <i class="d-inline-block text-primary align-top" data-acorn-icon="database" data-acorn-size="17"></i>
                                        </div>
                                        </div>
                                        <div class="col lh-1-25 text-alternate">
                                            <template v-if="row.establishments_unlimited"><strong>Establecimientos</strong> ilimitados</template>                                
                                            <template v-else><strong>{{row.establishments_limit}}</strong> establecimientos</template>
                                          
                                        </div>
                                    </div>
                                    <div class="row g-0 mb-2">
                                        <div class="col-auto">
                                        <div class="sw-3 me-1">
                                            <i class="d-inline-block text-primary align-top" data-acorn-icon="database" data-acorn-size="17"></i>
                                        </div>
                                        </div>
                                        <div class="col lh-1-25 text-alternate">
                                            
                                        <template v-if="row.sales_unlimited"><strong>Ventas </strong> ilimitadas</template>                                
                                        <template v-else>Total ventas mensuales <strong> S/{{row.sales_limit}}</strong></template>
                                          
                                        </div>
                                    </div>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <button type="button" class="btn waves-effect waves-light btn-sm btn-danger float-right" style="margin-left:6px;" @click.prevent="clickDelete(row.id)"><i class="fas fa-trash"></i> </button>
                                       <button type="button" class="btn waves-effect waves-light btn-sm btn-primary float-right"  @click.prevent="clickCreate(row.id)"><i class="fas fa-edit"></i> </button>
                                    <!-- <a href="javascript:void(0)" @click.prevent="clickDelete(row.id)" class="btn btn-icon btn-icon-start btn-foreground hover-outline stretched-link">
                                        <i class="fas fa-trash"></i>
                                        <span>Borrar</span>
                                    </a>
                                    <a href="javascript:void(0)" @click.prevent="clickCreate(row.id)" class="btn btn-icon btn-icon-start btn-foreground hover-outline stretched-link">
                                        <i class="fas fa-edit"></i>
                                        <span>Modificar</span>
                                    </a> -->
                                    </div>
                                </div>
                                </div>
                            </div>
                         
                    </template>
						
						  
                </div>
            
        <system-plans-form :showDialog.sync="showDialog"
                            :plan_documents="plan_documents"
                             :recordId="recordId"></system-plans-form>
    </div>
</template>

<script>

    import PlansForm from './form.vue'
    import {deletable} from "../../../mixins/deletable" 

    export default {
        mixins: [deletable],
        components: {PlansForm},
        data() {
            return {
                showDialog: false,
                resource: 'plans',
                recordId: null,
                records: [],                
                plan_documents: [] ,
                aux:[]
            }
        },
        created() {            
                
            this.$eventHub.$on('reloadData', () => {
                this.getData()
            })
            this.getData()
            this.getPlanDocuments()
        },
        methods: {

            getPlanDocuments(){
                this.$http.get(`/${this.resource}/tables`).then(response => {
                            this.plan_documents = response.data.plan_documents 
                        })
            },
            getData() {
                this.$http.get(`/${this.resource}/records`)
                    .then(response => {
                        this.records = response.data.data                         
                    })
            },
            getDescriptions(plan_documents){

                let descriptions = []; 
                Object.values(plan_documents).forEach((itm, i) => {                    
                    descriptions.push(this.plan_documents[itm-1]) 
                });
                return descriptions
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

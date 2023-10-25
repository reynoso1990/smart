<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Usuarios</span></li>
            </ol>
            <div class="right-wrapper pull-right">

                <template v-if="showAccessTokenForDiscount">
                    <el-tooltip class="item" content="Genera un token aleatorio para permitir realizar ventas con un porcentaje de descuento superior al límite configurado - Para vendedores" effect="dark" placement="top-start">
                        <button type="button" class="btn btn-info btn-sm  mt-2 mr-2" @click.prevent="clickAccessTokenForDiscount()"><i class="fa fa-check"></i> Generar token</button>
                    </el-tooltip>
                </template>

                <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" v-if="typeUser == 'admin'" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>

                <!--<button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickImport()"><i class="fa fa-upload"></i> Importar</button>-->
            </div>
        </div>
        <h2 class="small-title">Lista de Usuarios</h2>
          <div class="row g-2">
            <div class="col-md-3"  v-for="(row, index) in records" :key="index">
              <div class="card h-100">
                <div class="card-body text-center">
                  <div class="sw-13 position-relatives mb-2">
                    <div class="thumb_profile">
                        <img :src="row.photo_filename" class="img-fluid rounded-xl" alt="thumb" />
                    </div>
                  </div>
                  {{ row.name }}
                  <!-- <a href="javascript:void(0)"  class="mb-3 stretched-link body-link">{{ row.name }}</a> -->
                  <div class="text-muted text-medium mb-2">{{ row.type }}</div>
                  <div class="row g-0 align-items-center mb-2">
                    <div class="col-auto">
                      <div class="sw-3 d-flex justify-content-center align-items-center">
                        <el-tooltip class="item" effect="dark" content="Correo Electronico" placement="left-end">
                              <i class="fa fa-envelope text-primary" aria-hidden="true"></i>
                        </el-tooltip>
                      </div>
                    </div>
                    <div class="col ps-3">
                      <div class="d-flex align-items-center lh-1-25">{{ row.email }}</div>
                    </div>
                  </div>

                  <div class="row g-0 align-items-center mb-2">
                    <div class="col-auto">
                      <div class="sw-3 d-flex justify-content-center align-items-center">
                      <el-tooltip class="item" effect="dark" content="N° Documento" placement="left-end">
                        <i class="fa fa-address-card text-primary" aria-hidden="true"></i>
                      </el-tooltip>
                      </div>
                    </div>
                    <div class="col ps-3">
                      <div class="d-flex align-items-center lh-1-25">{{ row.number }}</div>
                    </div>
                  </div>

                  <div class="row g-0 align-items-center mb-2">
                    <div class="col-auto">
                      <div class="sw-3 d-flex justify-content-center align-items-center">
                      <el-tooltip class="item" effect="dark" content="Cumpleaños" placement="left-end">
                        <i class="fa fa-birthday-cake text-primary" aria-hidden="true"></i>
                      </el-tooltip>
                      </div>
                    </div>
                    <div class="col ps-3">
                      <div class="d-flex align-items-center lh-1-25">{{ row.date_of_birth }}</div>
                    </div>
                  </div>

                  <div class="row g-0 align-items-center mb-2">
                    <div class="col-auto">
                      <div class="sw-3 d-flex justify-content-center align-items-center">
                        <el-tooltip class="item" effect="dark" content="Cargo" placement="left-end">  
                             <i class="fa fa-shopping-bag text-primary" aria-hidden="true"></i>
                        </el-tooltip>
                      </div>
                    </div>
                    <div class="col ps-3">
                      <div class="d-flex align-items-center lh-1-25">{{ row.position }}</div>
                    </div>
                  </div>

                  <div class="row g-0 align-items-center mb-2"  >
                    <div class="col-auto">
                      <div class="sw-3 d-flex justify-content-center align-items-center">
                        <el-tooltip class="item" effect="dark" content="Celular Personal" placement="left-end">
                           <i class="fa fa-phone text-primary" aria-hidden="true"></i>
                        </el-tooltip>
                      </div>
                    </div>
                    <div class="col ps-3">
                      <div class="d-flex align-items-center lh-1-25">{{row.personal_cell_phone}}</div>
                    </div>
                  </div>
                  <div class="mt-4">
                    <button type="button" class="btn btn-sm btn-outline-primary btn-icon btn-icon-start me-2" v-if="typeUser === 'admin'"  @click.prevent="clickCreate(row.id)">
                       <span>Editar</span>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-icon btn-icon-start me-2" @click.prevent="clickDelete(row.id)" v-if="row.id != 1 && typeUser === 'admin'">
                       <span>Eliminar</span>
                    </button>
                  </div>
                  <!-- <div class="text-muted d-inline-block text-small align-text-top">Email:{{ row.email }}</div>
                  <div class="text-muted d-inline-block text-small align-text-top">N° Documento:{{ row.number }}</div>
                  <div class="text-muted d-inline-block text-small align-text-top">Telef.:{{ row.personal_cell_phone }}</div> -->

                </div>
              </div>
            </div>
          </div>
          <users-form :showDialog.sync="showDialog"
                        :typeUser="typeUser"
                        :recordId="recordId"></users-form>

            <authorized-token-discount-form :showDialog.sync="showDialogAuthorizedTokenForDiscount" ></authorized-token-discount-form>
    </div>
</template>

<script>

    import UsersForm from './form1.vue'
    import AuthorizedTokenDiscountForm from './partials/authorized_token_discount.vue'
    import {deletable} from '../../../mixins/deletable'

    export default {
        props: ['typeUser', 'configuration'],
        mixins: [deletable],
        components: {UsersForm, AuthorizedTokenDiscountForm},
        data() {
            return {
                showDialog: false,
                showDialogAuthorizedTokenForDiscount: false,
                resource: 'users',
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
        computed:
        {
            showAccessTokenForDiscount()
            {
                return this.typeUser === 'admin' && this.configuration.restrict_seller_discount
            }
        },
        methods: {
            clickAccessTokenForDiscount()
            {
                this.showDialogAuthorizedTokenForDiscount = true
            },
            getData() {
                this.$http.get(`/${this.resource}/records`)
                    .then(response => {
                        this.records = response.data.data
                    })
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

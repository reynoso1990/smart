<template>
<div>
     <audio ref="audio">
          <source :src="sound" />
        </audio>
       <div class="container">
            <!-- Title and Top Buttons Start -->
            <div class="page-title-container">
              <div class="row">
                <!-- Title Start -->
                <div class="col-12 col-md-7">
                  <h1 class="mb-0 pb-0 display-4" id="title">Zona de Preparación</h1>
                </div>
                <div class="col-12 col-md-5">
                <button type="button" class="btn btn-danger w-100 text-white" @click="logout()" :loading="loading_logout" :element-loading-text="loading_text">
                    <i class="icofont-ui-power"></i> Salir
                </button>

                </div>
                <!-- Title End -->
              </div>
            </div>
            <!-- Title and Top Buttons End -->

            <!-- Content Start -->
            <div class="card mb-2">
              <div class="card-body h-100">
                <div class="container">
                    <div class="row">

                            <div class="col-md-3" v-for="(orden, index) in ordens" :key="index">
                                <div class="blog-card blog-card-blog">
                                    <div class="blog-card-image">

                                        <a href="javascript:void(0)">
                                            <template v-if="orden.food.image=='imagen-no-disponible.jpg'">
                                                 <img src="/imagen-no-disponible.jpg" :alt="orden.food.description"  />
                                            </template>
                                            <template v-else>
                                                <img :src="formatUrlImage(orden.food.image)" :alt="orden.food.description"  />
                                            </template>
                                        </a>

                                        <div class="ripple-cont"></div>
                                    </div>
                                    <div class="blog-table">
                                        <h6 class="blog-category blog-text-primary"><i class="far fa-newspaper"></i> ORDEN N°{{ orden.orden.id }} / MESA N° {{ orden.table.number }}</h6>
                                        <h5 class="blog-card-caption text-center">
                                            <a href="javascript:void(0)"> </a>
                                            <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                                                <ul class="breadcrumb pt-0 mb-0">
                                                    <li class="breadcrumb-item w-100"><a href="javascript:void(0)">CANTIDAD ({{orden.quantity}})</a></li>
                                                </ul>
                                            </nav>
                                             <h6 class="blog-category blog-text-success"><i class="far fa-newspaper"></i> {{ orden.food.description }}</h6>
                                        </h5>
                                         <p class="blog-card-description p-1 pb-0 mb-0 text-center">
                                            <span
                                                class="font-weight-bold"
                                                :class="orden.status_orden_id == 1 ? 'text-danger' : 'text-primary'">
                                                {{ orden.status }}
                                            </span>
                                            <i class="far fa-clock"></i>  HORA {{ orden.time.split(":").splice(0, 2).join(":") }}
                                        </p>
                                        <div class="ftr">
                                            <div class="author">
                                               <div class="btn-group btn-group-square m-0 flex-wrap" role="group">
                                                <button class="btn btn-outline-primary btn-sm" @click="ordenReady(orden.id)">
                                                   Pedido listo
                                                </button>
                                                <button class="btn btn-outline-danger btn-sm" @click="ordenCancel(orden.id)">
                                                           Cancelar pedido
                                                    </button>

                                                </div>
                                                    <!-- <el-button @click="ordenReady(orden.id)" type="success" icon="el-icon-check">
                                                        Pedido listo
                                                    </el-button>

                                                    <el-button type="danger" icon="el-icon-delete" class="me-2" @click="ordenCancel(orden.id)">
                                                        Cancelar pedido
                                                    </el-button> -->


                                            </div>
                                            <div class="stats w-100 text-primary">
                                                PARA LLEVAR: <b>{{ orden.to_carry }}</b>
                                            </div>
                                            <div class="stats w-100">
                                                OBSERVACIÓN: {{ orden.observations }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                  </div>

              </div>
            </div>
            <!-- Content End -->
          </div>


</div>

</template>

<script>
 export default {
  props: ["configuration","area_id"],

  data() {
    return {
      ordens: [],
      audio:HTMLAudioElement,
      sound:"https://turestobar.xyz/sound.ogg",
      loading_logout:false,
      loading_text:null
    };
  },
  mounted() {
       //this.play()
    Echo.channel("orden_ready").listen(
      `.order-${this.configuration.socket_channel}`,
      (e) => {
        let { order_item } = e;
        this.deleteOrden(order_item);
      }
    );
    Echo.channel("orden_delete").listen(
      `.order-delete-${this.configuration.socket_channel}`,
      (e) => {
        let { order_item } = e;
        this.deleteOrden(order_item);

      }

    );
    Echo.channel("orden_request").listen(
      `.order-request-${this.configuration.socket_channel}`,
      (e) => {
         console.log("Orden entra",e);
      if(this.area_id == e.order_item.food.area_id){
          this.ordens = [...this.ordens, e.order_item];
     //     this.play()
      }
      }
    )
    Echo.channel("print_orden").listen(`.print-order-${this.configuration.socket_channel}`,
            (e) => {
                console.log("imprimiendo",e)
                 if (e.data.direct_printing == true) {
                     if(e.data.printing== true){
                         this.Printer(e.data.printer, e.data.print, e.data.copies, e.data.user_id,e.data.multiple_boxes,e.data.typeuser,e.data.area_id);
                    }
               }
            }
        );
  },
  created() {
    this.getOrdens();
     qz.security.setCertificatePromise((resolve, reject) => {
                this.$http.get('/api/qz/crt/override', {
                    responseType: 'text'
                }).then(response => {
                    resolve(response.data);
                }).catch(error => {
                    reject(error.data);
                });
            });
            qz.security.setSignaturePromise((toSign) => {
                return (resolve, reject) => {
                    this.$http.post('/api/qz/signing', {
                            request: toSign
                        })
                        .then(response => {
                            resolve(response.data);
                        }).catch(error => {
                            reject(error.data);
                        });
                };
            });
  },
  methods: {
    deleteOrden(id) {
      this.ordens = this.ordens.filter((o) => o.id != id);
    },
    async Printer(Printer, linkpdf, copies,auth=null,multiple_boxes=false,typeuser=null,area_id=null) {
            qz.security.setCertificatePromise((resolve, reject) => {
                this.$http.get('/api/qz/crt/override', {
                    responseType: 'text'
                }).then(response => {
                    resolve(response.data);
                }).catch(error => {
                    reject(error.data);
                });
            });
            qz.security.setSignaturePromise((toSign) => {
                return (resolve, reject) => {
                    this.$http.post('/api/qz/signing', {
                            request: toSign
                        })
                        .then(response => {
                            resolve(response.data);
                        }).catch(error => {
                            reject(error.data);
                        });
                };
            });

            if(multiple_boxes==true && typeuser!='admin'){
                if(this.area_id==area_id){
                let config = qz.configs.create(Printer, {
                    scaleContent: false
                });
                if (!qz.websocket.isActive()) {
                    await qz.websocket.connect(config);
                }
                let data = [{
                    type: 'pdf',
                    format: 'file',
                    data: linkpdf
                }];

                qz.print(config, data).catch((e) => {
                this.$message.error(e.message);
               });
                for (let index = 0; index < copies; index++) {
                    qz.print(config, data).catch((e) => {
                        this.$message.error(e.message);
                    });
                }
                }
            }


        },
     formatUrlImage(url) {
      if (!url) return;
      let formated = url.replace("public", "storage");
       return `/${formated}`;
    },
     async play(){
        let audio = this.$refs.audio;
         audio.play();

    },
    async logout(){
        this.loading_logout=true;
        this.loading_text="Cerrando Session"
        await this.$http.post(`/logout`).then(response => {
            location.href = `/login`;
        })
        .catch(error => {
        console.log(error);
        });
    },
     formatUrlImage(url) {
        if (!url) return;
        let formated = "storage/uploads/items/" + url;
        return `/${formated}`;
    },
    async ordenReady(id) {
      this.loading = true;
      try {
        const response = await this.$http.get(`ordens-ready/${id}`);
        const { success, message } = response.data;

        success ? this.$message.success(message) : this.$message.error(message);
      } catch (e) {
        console.log(e);
        this.$message.error("Ocurrió un error");
      }
      this.loading = false;
    },
    async ordenCancel(id) {
      try {
        let res = await this.$confirm(
          "Desea cancelar este pedido?",
          "Cancelar",
          {
            confirmButtonText: "Ok",
            cancelButtonText: "Cancelar",
            type: "warning",
          }
        );
        if (res) {
          const response = await this.$http.delete(`delete-orden/${id}`);
          if (response.status == 200) {
            const { message } = response.data;
            this.$message.success(message);
          }
        }
      } catch (e) {
        //todo

        if (e != "cancel") {
          this.$message.error("Ocurrió un error");
        }
      }
    },
    async getOrdens() {
      try {
        const response = await this.$http.get("ordens-items");
        this.ordens = response.data.data.filter((o) => o.status_orden_id == 1);
      } catch (e) {
        console.log(e);
      }
    },
  },
};
</script>

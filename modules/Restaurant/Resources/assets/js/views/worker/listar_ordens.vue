<template>
<div>
    <div class="row">
        <div class="col-12 col-sm-12 h-100 text-center" v-if="ordens.length==0">
                <div class="card">
                    <label class="form-label">
                        <h1>Espere por favor...</h1>
                    </label>
                </div>
        </div>
        <div class="col-12 col-sm-3" v-for="(orden, index) in ordens" :key="index">
            <div class="blog-card blog-card-blog">
                <div class="blog-card-image">
                    <a href="javascript:void(0)">
                        <img :src="formatUrlImage(orden.food.image)" :alt="orden.food.description" />
                    </a>
                    <div class="ripple-cont"></div>
                </div>
                <div class="blog-table">
                    <h6 class="blog-category blog-text-success"><i class="far fa-newspaper"></i> ORDEN N°{{ orden.orden.id }} / MESA N° {{ orden.table.number }}</h6>
                    <h5 class="blog-card-caption">
                        <a href="javascript:void(0)"> </a>
                        <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                            <ul class="breadcrumb pt-0 mb-0">
                                <li class="breadcrumb-item"><a href="javascript:void(0)">CANTIDAD ({{orden.quantity}})</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0)">{{ orden.food.description }}</a></li>
                            </ul>
                        </nav>

                    </h5>
                    <!-- <p class="blog-card-description p-1 pb-0 mb-0">MOZO <i class="icofont-waiter-alt icofont-2x"></i> {{ orden.user }}</p> -->
                    <p class="blog-card-description p-1 pb-0 mb-0">
                        <span
                            class="font-weight-bold"
                            :class="orden.status_orden_id == 1 ? 'text-danger' : 'text-primary'">
                            {{ orden.status }}
                        </span>
                        <i class="far fa-clock"></i>  HORA {{ orden.time.split(":").splice(0, 2).join(":") }}
                    </p>
                    <div class="ftr">
                        <div class="author">
                            <span>
                                <el-button @click="ordenReady(orden.id)" type="success" icon="el-icon-check">
                                    Pedido listo
                                </el-button>
                            </span>
                            <span>
                                <el-button type="danger" icon="el-icon-delete" class="me-2" @click="ordenCancel(orden.id)">
                                    Cancelar pedido
                                </el-button>
                            </span>

                        </div>

                        <div class="stats w-100">
                            OBSERVACIÓN: {{ orden.observations }}
                        </div>
                    </div>
                </div>
            </div>
            <!--  -->
        </div>
    </div>

</div>
</template>

<script>
export default {
    props: ["configuration", "selectOption"],
    watch: {
        async selectOption(newTable, _) {
            if (newTable == "2") {
                await this.getOrdens();
            }
        },
    },
    data() {
        return {
            ordens: [],
            load: false,
            loading:false,
            audio: HTMLAudioElement
        };
    },
    mounted() {
        // Echo.channel("orden_ready").listen(
        //     `.order-${this.configuration.socket_channel}`,
        //     (e) => {
        //         let {
        //             order_item
        //         } = e;
        //         this.deleteOrden(order_item);
        //     }
        // );
        // Echo.channel("orden_delete").listen(
        //     `.order-delete-${this.configuration.socket_channel}`,
        //     (e) => {
        //         let {
        //             order_item
        //         } = e;
        //         this.deleteOrden(order_item);
        //     }
        // );
        // Echo.channel("orden_request").listen(
        //     `.order-request-${this.configuration.socket_channel}`,
        //     (e) => {
        //         this.ordens = [...this.ordens, e.order_item];

        //     }
        // );
    },
    async created() {
        //this.getOrdens();
        try {
            if (this.load == false) {
                const response = await this.$http.get("ordens-list");
                if (response.data.success == true) {
                    this.load = true
                }
                this.ordens = response.data.data;

            }
        } catch (e) {
            console.log(e);
        }
    },
    methods: {
        deleteOrden(id) {
            this.ordens = this.ordens.filter((o) => o.id != id);
        },
        formatUrlImage(url) {
            if (!url) return;
            let formated = "storage/uploads/items/" + url;
            return `/${formated}`;
        },
        async play() {
            //await this.audio.play();
            let audio = this.$refs.audio;
            audio.play();

        },
        async ordenReady(id) {
            this.loading = true;
            try {
                const response = await this.$http.get(`ordens-ready/${id}`);
                const {
                    success,
                    message
                } = response.data;

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
                    "Cancelar", {
                        confirmButtonText: "Ok",
                        cancelButtonText: "Cancelar",
                        type: "warning",
                    }
                );
                if (res) {
                    const response = await this.$http.delete(`delete-orden/${id}`);
                    if (response.status == 200) {
                        const {
                            message
                        } = response.data;
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
                const loading = this.$loading({
                    lock: true,
                    text: 'Espere por favor....',
                    spinner: 'el-icon-loading',
                   });

                     const response = await this.$http.get("ordens-list");
                    if (response.data.success == true) {
                          loading.close();
                    }
                    if (response.data.data.order_items) {
                        this.ordens = response.data.data.order_items.filter((o) => o.status_orden_id == 1);
                    }
             } catch (e) {
                console.log(e);
            }
        },
    },
};
</script>

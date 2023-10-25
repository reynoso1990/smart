<template>
<div class="container">
    <!-- Title and Top Buttons Start -->
    <div class="page-title-container mb-0">
        <div class="row">

            <div class="col-12 col-sm-2">
                <h1 class="mb-1 pb-0 display-4 user_online">
                    <div class="btn-group">
                        <div class="dropdown">
                            <a class="dropdown-toggle mb-1" href="javascript:void(0)" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="icofont-waiter icofont-2x"></i> {{user.name}}
                            </a>
                            <div class="dropdown-menu w-100" aria-labelledby="dropdownMenuLink">
                                <!-- <a class="dropdown-item" href="javascript:void(0)" @click="selectOption=1;tables_row_select=null;show ='tables';createOrden();"><i class="icofont-fast-food"></i> Nueva Orden</a> -->
                                <a class="dropdown-item" href="javascript:void(0)" @click="selectOption=1;tables_row_select=null;show ='tables'"><i class="icofont icofont-dining-table"></i> Visualizar Mesas</a>
                                <a class="dropdown-item" href="javascript:void(0)" @click="selectOption=2;setArea(0);show ='ordens';tables_row_select=null"><i class="icofont-prestashop"></i> Visualizar Pedidos Realizados</a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="icofont-power"></i> Cerrar Sessión</a>
                                <form id="logout-form" action="/logout" method="POST" style="display: none;">
                                 <input type="hidden" name="_token" :value="csrf">
                                </form>
                            </div>
                        </div>
                    </div>
                </h1>
                <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                    <ul class="breadcrumb pt-0">
                        <template v-if="!currentTable">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Seleccione una Mesa</a></li>
                        </template>
                        <template v-if="currentTable!=null">
                            <li class="breadcrumb-item"><a href="javascript:void(0)" class="text-danger font-weight-bold" @click="selectOption=1;tables_row_select=null;show ='tables'"> Mesa Seleccionada N°{{ currentTable.number }}</a></li>
                            <li class="breadcrumb-item"><a href="javascript:void(0)" class="text-info font-weight-bold" @click="createOrden()"> Nueva Orden </a></li>
                        </template>
                    </ul>
                </nav>
            </div>
            <div class="col-12 col-sm-4 pt-2 pb-2" v-if="show=='create'">
                <template>
                    <span slot="label"> <i class="fas fa-list"></i>
                    <template v-if="optionsSelected==0">
                        Buscar por Nombre del Producto
                    </template>
                    <template v-else>
                        Buscar por Codigo Interno Producto
                    </template>
                    </span>
                    <el-input v-model="item" @focus="clearinput()" @input="searchOrden()" ref="item" placeholder="Buscar Producto">
                        <i slot="prefix" class="el-input__icon el-icon-search"></i>
                        <el-button :class="optionsSelected == 0 ? 'bg-secondary text-white' : ''" slot="append" icon="el-icon-tickets" @click="selectSearch(0)"></el-button>
                        <el-button :class="optionsSelected == 1 ? 'bg-secondary text-white' : ''" slot="append" icon="el-icon-s-order" @click="selectSearch(1)"></el-button>
                    </el-input>
                </template>
            </div>
            <div class="col-12 col-sm-4 pt-2 pb-2" v-if="show=='create'">
                <template>
                    <span slot="label"> <i class="fas fa-list"></i> Categoria de Producto</span>
                    <el-select v-model="category" filterable placeholder="Selecionar aqui...." @change="select_category(category)">
                        <el-option v-for="item in categories" :key="item.id" :label="item.name" :value="item.id">
                        </el-option>
                    </el-select>
                </template>
            </div>

        </div>
    </div>
    <div class="row p-2" v-show="show == 'tables'">
        <div class="col-12 col-sm-12 col-lg-12 ">
            <div class="row   d-flex align-items-center justify-content-center">
                <div class="card_table" :class="[data.status_table.id == 1 ? 'table-free' : 'table-occupy', tables_row_select==data.id ? 'table-active' : '']" v-for="(data, index) in tables" :key="index" @click="selectedTable(data.id, data);">
                    <div class="card-circle">
                        <!-- <i class="icofont icofont-dining-table icofont-3x" :class="[data.status_table.id == 1 ? 'text-free' : 'text-occupy', tables_row_select==data.id ? 'table-active' : '']"></i> -->
                    </div>
                    <div class="text-content">
                        <span class="card-title" :class="[data.status_table.id == 1 ? 'text-free' : 'text-occupy', tables_row_select==data.id ? 'table-active' : '']"><strong>Mesa {{ data.number }}</strong></span>
                        <p class="mb-0 pb-0" :class="[data.status_table.id == 1 ? 'text-free' : 'text-occupy', tables_row_select==data.id ? 'table-active' : '']">{{ data.status_table.description }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- v-show="show == 'ordens'" -->
    <ListOrden :configuration="configuration" v-if="selectOption=='2'">
    </ListOrden>
    <template v-if="currentTable && show == 'create'">
        <detail-orden ref="detailRef"
            :configuration="configuration"
            :table.sync="currentTable"
            :categories="categories"
            :showMenu.sync="showMenu"
            :tables_row_select.sync="tables_row_select"
            :foods="foods" @addenfoque="focus"
            @add="tablesrowselect">
        </detail-orden>
    </template>
</div>
</template>

<style scoped>
.ttitle {
    font-size: 20px;
    font-weight: bold;
}

.el-tag--small {
    height: 50px;
    padding: 0 12px;
    line-height: 50px;
}

.font-bold {
    font-weight: bold !important;
    color: #1b4c43;
}

.font-normal {
    font-weight: normal;
}

.mr1 {
    margin-right: 5px !important;
}

.hooper-next,
.hooper-prev {
    padding: 0.2em;
}
</style>

<script>
import {
    Hooper,
    Slide,
    Navigation as HooperNavigation
} from "hooper";
import "hooper/dist/hooper.css";
import DetailOrden from "./detail_orden.vue";
import ViewTables from "./tables.vue";
import MenuTable from "./menu.vue";
import ListOrden from "./listar_ordens.vue";
export default {
    components: {
        ViewTables,
        MenuTable,
        DetailOrden,
        ListOrden,
        Hooper,
        Slide,
        HooperNavigation,
    },
    props: [
        "configuration",
        "user",
        "status_table",
        "areas",
        "tables_area",
        "tables_active",
        "categories",
        "foods",
     ],
    data() {
        return {
            show: "tables",
            selectArea: 0,
            selectOption: 1,
            selectedTables: 0,
            item: null,
            resource: "dashboard",
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            optionsSelected: this.configuration.search_type,
            showMenu: false,
            currentTable: null,
            loading: false,
            category: 0,
            tables: [],
            printerDefault: null,
            currentArea: null,
            allAreas: [],
            ordens: [],
            tables_row_select: null,
            hooperSettings: {
                centerMode: false,
                trimWhiteSpace: true,
                playSpeed: 3500,
                itemsToShow: 6,
                breakpoints: {
                    2400: {
                        itemsToShow: 4,
                    },
                    1800: {
                        itemsToShow: 4,
                    },
                    1500: {
                        itemsToShow: 3,
                    },
                    1100: {
                        itemsToShow: 2,
                    },
                    0: {
                        itemsToShow: 2,
                    },
                },
            },
        };
    },

    mounted() {
        // Echo.channel("orden_delete").listen(
        //     `.order-delete-${this.configuration.socket_channel}`,
        //     (e) => {
        //         let {
        //             order_item
        //         } = e;
        //         this.deleteOrden(order_item);
        //     }
        // );
        // Echo.channel("orden_paid").listen(
        //     `.order-paid-${this.configuration.socket_channel}`,
        //     (e) => {
        //         this.$eventHub.$emit("reloadData");
        //         this.$refs.detailRef.clean();
        //         // const { order } = e;
        //         // this.orders = [...this.orders, order];
        //     }
        // );
        //  Echo.channel("stock_orden").listen(`.stock-order-${this.configuration.socket_channel}`,
        //     (e) => {

        //         let ListFoods=this.foods
        //         for (let index = 0; index < e.data.order_item.length; index++) {
        //             let xFind = _.find(ListFoods, {id: e.data.order_item[index].food_id})
        //              let index_find = _.findIndex(ListFoods, {
        //                 id: xFind.id
        //             })
        //             if (index_find !== -1) {
        //                 let nSaldo=parseInt(ListFoods[index_find].item.stock) - e.data.order_item[index].quantity
        //                 ListFoods[index_find].item.stock=nSaldo
        //                 this.$emit('update:foods',ListFoods)
        //             }
        //         } 
        //     }
        // );
    },

    async created() {

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
        this.categories.unshift({
            id: 0,
            name: "TODOS LAS CATEGORIAS",
        });
        this.currentArea = this.user.area_id;
        this.allAreas = [
            ...this.areas.map((a) => {
                if (this.currentArea == a.id) {
                    a.selected = true;
                } else {
                    a.selected = false;
                }
                return a;
            }),
            {
                id: 0,
                description: "Ver Ordenes",
                selected: false,
            },
        ];
        let allAreas_all = _.orderBy(this.allAreas, ['id'], ['asc'])
        this.allAreas = allAreas_all;
        this.tables = this.tables_area
        //this.tables_row_select=this.tables_active
        await this.getTables();
        this.$eventHub.$on("reloadData", () => {
            this.getTables(true);
        });
         this.$notify({
          title: "Sistema de Punto de Venta",
          iconClass:"icofont-waiter icofont-3x",
           message: "Bienvenido "+this.user.name,
        });
    },
    methods: {
        deleteOrden(id) {
            this.tables = this.tables.map((t) => {
                if (t.orden.length > 0) {
                    let ordens = t.orden;
                    for (let i = 0; i < ordens.length; i++) {
                        let orden = ordens[i];
                        if (orden.orden_items.some((ot) => ot.id == id)) {
                            t.orden[i].orden_items = t.orden[i].orden_items.filter(
                                (o) => o.id != id
                            );
                            break;
                        }
                    }

                    // if (t.orden.orden_items.length == 0) {
                    //   t.status_table.id = 1;
                    //   t.status_table.description = "disponible";
                    // }
                }

                return t;
            });
        },
        async tablesrowselect(value) {
            this.tables_row_select = value;
              this.selectOption=1;
        },
        clearinput() {
            this.item = null
        },
        focus(){
             this.$nextTick(() => {
                let input = this.$refs.item;
                input.focus();
                return false;
            })
        },
        createOrden(){
             this.$refs.detailRef.createOrden();
             this.focus();
         },
        select_category(id) {
            this.focus();
            this.$refs.detailRef.filterCategory(id)
        },
        selectSearch(value) {
            this.optionsSelected = value
            this.focus();
        },
        searchOrden() {
            this.$refs.detailRef.filter_food(this.item.toLowerCase(), this.optionsSelected)
        },
        async printer() {
            // try {
            //         qz.websocket.connect()
            //             .then(qz.printers.getDefault)
            //             .then(function(printer) {
            //                 console.log("defaultPrinter",printer)
            //                 let config = qz.configs.create(printer, {scaleContent : false},{jobName	:"Pedidos"});
            //                  qz.websocket.connect(config);
            //                  let data = [
            //             {
            //                 type: 'pdf',
            //                 format: 'file',
            //                 data: "http://demo.facturadorpro3.oo/restaurant/worker/print-ticket?id=40"
            //             }
            //         ];
            //         qz.print(config, data).catch((e) => {
            //             this.$message.error(e.message);
            //         });
            //             })
            //             .catch(function(e) {
            //                 console.error(e);
            //         });
            //         this.$message.success("se esta imprimiendo el comprobante con exito");
            //     } catch (e) {
            //         this.$message.error(e.message);
            //     }
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
                if (success) {
                    this.ordens = this.ordens.filter((o) => o.id != id);
                }
            } catch (e) {
                console.log(e);
                this.$message.error("Ocurrió un error");
            }
            this.loading = false;
        },
        async ordenCancel(id) {},
        setArea(id) {
            this.currentArea = id;
            this.selectArea = id;
            if (id == 0) {
                this.show = "ordens";
                this.getOrdens();
            } else {
                this.getTables();
                this.show = "create";
            }
            this.allAreas = this.allAreas.map((a) => {
                if (a.id == id) {
                    a.selected = true;
                } else {
                    a.selected = false;
                }
                return a;
            });
        },
        calculeTotal(orden = null) {
            if (!orden) return;
            let items = orden.orden_items;
            let prices = items.map((o) => Number(o.food.price));
            if (prices.length == 0) return;
            let total = prices.reduce((a, b) => a + b);
            return total;
        },
        selectedTable(id = null, data = []) {

            this.tables_row_select = id;
            this.selectedTables = id;
            this.currentTable = data;
            this.show = "create";
            this.focus();
            this.allAreas = this.allAreas.map((a) => {
                a.selected = false;
                return a;
            });

        },
        async getOrdens() {
            this.loading = true;

            // try {
            //   const response = await this.$http.get("ordens-list");
            //   this.ordens = response.data.data;
            // } catch (e) {
            //   console.log(e);
            // }
            this.show = "ordens";
            this.loading = false;
        },
        async getTables(change = null) {
            this.loading = true;
            const response = await this.$http.get(`tables/records-area/${this.currentArea}`);
            if (response.status == 200) {
                const {
                    data
                } = response.data;
                this.tables = data;
                if (!change) {
                    this.show = "tables";
                }
                if (this.currentTable) {
                    let id = this.currentTable.id;
                    this.currentTable = this.tables.find((t) => t.id == id);
                }
            }
            this.loading = false;
        },
    },
};
</script>

<template>
 <div>
     <div class="row" v-if="ordens.length>0">
      <div class="col-12 p-1">
          <h2 class="small-title">Ordenes Realizados</h2>
         <hooper :settings="hooperSettings">
              <slide v-for="(o, index) in ordens" :key="index">
                <div class="col-md-12">
                    <div class="card_profile" :class="ordenSelectedId==o.id ? 'bg-active' : ''" @click="selectOrden(o.id);show_orders()">
                        <div class="card__avatar">
                                  <img src="https://grupopcsystems.xyz/storage/ordens.png" width="45px" alt="" />
                            <div class="badge"></div>
                        </div>
                        <div>
                            <h3>Orden  Nº{{ o.id }}</h3>
                            <h6 class="text-muted" >
                                <!-- <a href="#!" class="job-card_company"  :class="ordenSelectedId==o.id ? 'text-active-white' : ''">Mesa Nº {{o.mesa.number}} - <i class="icofont icofont-time"></i> Hora:{{o.orden_items[0].time}}</a> -->
                            </h6>
                        </div>
                    </div>

                </div>
               </slide>
              <hooper-navigation slot="hooper-addons"></hooper-navigation>
            </hooper>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 p-1">
        <list-food
        ref="list_foods"
          @insertOrden="insertOrden"
          :configuration="configuration"
          :table="table"
          :categories="categories"
          :showMenu.sync="showMenu"
          :foods="foods"
        ></list-food>
    </div>

    <current-orden
          ref="ordenRef"
          :tableId="table.id"
          :configuration.sync="configuration"
          :localOrden.sync="localOrden"
          :ordens.sync="ordensItems"
          :ordenSelectedId.sync="ordenSelectedId"
          @updateOrdens="updateOrdens"
          @deleteFood="deleteFood"
          @ordenDeleted="createOrden"
          @listtables="clearTables">
    </current-orden>
      </div>
  </div>
</template>
<script>

</script>
<style scoped>
.circle-order {
  width: 3vw;
  height: 3vw;
  border-radius: 50%;
  box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
}
</style>
<script>
import ListFood from "./list_food.vue";
 import CurrentOrden from "./current_orden.vue";
 import { Hooper, Slide, Navigation as HooperNavigation } from "hooper";
import "hooper/dist/hooper.css";
export default {
  components: { ListFood, CurrentOrden,Hooper,Slide,HooperNavigation,
},
  props: ["table", "showMenu", "categories", "foods", "configuration","tables_row_select"],
  created() {
    this.ordens = this.table.orden;
  },
  watch: {
    table(newTable, _) {
        console.log(newTable)
      this.ordens = newTable.orden;
    },
  },
  data() {
    return {
      ordenTitle: "Nueva Orden",
      activeName: "menu",
      localOrden: [],
      ordens: [],
      ordenSelectedId: null,
      ordensItems: [],
      item: null,
      hooperSettings: {
      centerMode: false,
      trimWhiteSpace: true,
      playSpeed: 3500,
      itemsToShow:6,
       breakpoints: {
        2400: {
          itemsToShow: 6,
        },
        1800: {
          itemsToShow: 6,
        },
        1500: {
          itemsToShow: 5,
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
  methods: {
    calculateOrden(tab) {
      if (tab.name == "orden") {
        this.$refs.ordenRef.calculateTotal();
      }
    },
    deleteFood(idx) {
      this.localOrden.splice(idx, 1);
    },
    clean() {
      if (this.selectOrden) {
        let exist = this.ordens.find((o) => o.id == this.selectOrden);
        if (!exist) {
          this.createOrden();
        }
      }
    },
    updateOrdens() {
      this.createOrden();
    },
    createOrden() {
      this.ordenTitle = "Nueva Orden";
      this.ordenSelectedId = null;
      this.ordensItems = [];
      this.$refs.ordenRef.open_orders()
    },
    seleccionar_mesa(){
      this.$emit('add',null)
    },

    insertOrden(orden,fodd_id) {
      let index_find=_.findIndex(this.localOrden,{id:fodd_id})
      if(index_find==-1){
        this.localOrden.push(orden);
      }else{
        this.localOrden[index_find].quantity=this.localOrden[index_find].quantity+1
      }
      this.$refs.ordenRef.calculateTotal();
    },
    show_orders(){
      this.$refs.ordenRef.view_orders();
    },
    clearTables(){
      this.$emit('add',null)
    },
    filterCategory(id){
        this.$refs.list_foods.setCategory(id)
    },
    filter_food(value,optionsSelected){
        this.$refs.list_foods.searchFood(value,optionsSelected)
    },
    selectOrden(id) {
      this.ordenTitle = `Orden #${id}`;
      this.ordenSelectedId = id;
      this.$emit('addenfoque',null)
        let orden = this.ordens.find((o) => o.id == id);
      if (orden.orden_items) {
        this.ordensItems = orden.orden_items;
      } else {
        this.ordensItems = [];
      }
      this.$refs.ordenRef.calculateTotal();
    },
  },
};
</script>

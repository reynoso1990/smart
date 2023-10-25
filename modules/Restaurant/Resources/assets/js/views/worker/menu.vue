<template>
  <el-dialog
    width="75%"
    :title="title"
    :visible="showMenu"
    @close="close"
    @open="open"
    key="menu"
  >
    <el-tabs v-if="table" v-model="activeName" @tab-click="calculateOrden">
      <el-tab-pane name="menu">
        <span slot="label"> <i class="fas fa-list"></i> Carta</span>
        <div class="row">
          <div class="col-12 col-md-8 col-sm-7 col-lg-9">
            <label class="control-label">Buscar plato / bebida</label>
            <el-input v-model="item" @input="searchFood"> <i slot="prefix" class="el-icon-edit-outline"></i></el-input>
          </div>
        </div>
        <div class="row mt-2">
          <div class="col mt-1 mt-lg-0 mt-md-0">
            <span
              role="button"
              class="category badge badge-pill b-primary text-white m-1"
              v-for="category in categories"
              :key="category.id"
              @click="setCategory(category.id)"
            >
              {{ category.description.toUpperCase() }}
            </span>
          </div>
        </div>
        <div class="container-foods">
          <div class="row mt-1" v-for="(food, index) in listFoods" :key="index">
            <div class="col d-flex justify-content-start">
              <button
                class="btn btn-sm btn-success mr-2"
                @click="viewImage(food.image)"
              >
                <i class="fas fa-eye"></i>
              </button>
              <span
                role="button"
                @click="selectFood(index)"
                :class="`${food.select ? 'text-primary font-weight-bold' : ''}`"
              >
                {{ food.description.toUpperCase() }}</span
              >
            </div>
          </div>
        </div>
        <div class="row mt-1">
          <div class="col-12">
            <label class="control-label"
              >Observacion
              {{ selectedFood && `(${selectedFood.description})` }}</label
            >
            <textarea
              :disabled="!selectedFood"
              class="form-control"
              rows="2"
              v-model="currentFood.observation"
            ></textarea>
          </div>
        </div>
        <div class="row mt-1 mr-2 d-flex justify-content-end">
          <button @click="addFood" class="btn btn-success mr-1">Añadir</button>
        </div>
        <view-image
          :image="currentImage"
          :showDialog.sync="showImage"
        ></view-image>
      </el-tab-pane>
      <el-tab-pane name="orden">
        <span slot="label"> <i class="fas fa-utensils"></i> Nueva Orden </span>

        <orden
          ref="ordenRef"
          @deleteFood="deleteFood"
          @closeDialog="close"
          :tableId="table.id"
          :ordenItems.sync="ordenItems"
          :orden.sync="orden"
          :ordenId="ordenId"
        ></orden>
      </el-tab-pane>
    </el-tabs>
  </el-dialog>
</template>
<style scoped>
.container-foods {
  height: 250px;
  overflow-y: scroll;
  overflow-x: hidden;
}
.category {
  font-size: 12.5px;
}
@media only screen and (max-width: 430px) {
  .total {
    font-size: 15px !important;
  }
}
.total {
  font-size: 22px;
}
.b-primary {
  background-color: rgb(96, 96, 238);
}
</style>
<script>
import Orden from "./orden.vue";
import ViewImage from "./image.vue";
export default {
  components: { ViewImage, Orden },
  props: ["table", "showMenu", "categories", "foods", "configuration"],
  data() {
    return {
      activeName: "menu",
      ordenItems: [],
      orden: [],
      currentFood: {
        food: null,
        observation: null,
      },
      item: null,
      currentImage: null,
      showImage: false,
      listFoods: [],
      selectedFood: null,
      allFalse: true,
      title: null,
      total: null,
      ordenId: null,
      generalOrdens: 1,
    };
  },
  mounted() {
    Echo.channel("orden_delete").listen(
      `.order-delete-${this.configuration.socket_channel}`,
      (e) => {
        let { order_item } = e;
        this.deleteOrden(order_item);
        // const { order } = e;
        // this.orders = [...this.orders, order];
      }
    );
  },
  created() {
    this.ordenItems = [];
    this.orden = [];
  },
  methods: {
    deleteOrden(id) {
      this.ordenItems = this.ordenItems.filter((o) => o.id != id);
    },
    calculateOrden() {
      if (this.activeName == "orden") {
        this.$refs.ordenRef.calculateTotal();
      }
    },
    deleteFood(idx) {
      this.orden.splice(idx, 1);
    },
    setCategory(id) {
      !this.allFalse && this.setFalse();
      if (id == 0) {
        this.listFoods = this.foods;
      } else {
        this.listFoods = this.foods.filter((f) => f.category.id == id);
      }
    },
    addFood() {
      if (!this.selectedFood) return;
      this.setFalse();
      this.currentFood.food = this.selectedFood;
      this.orden.push(this.currentFood);
      this.currentFood = {
        food: null,
        observation: null,
      };
      this.selectedFood = null;
      this.item = null;
    },
    setFalse() {
      this.listFoods = this.listFoods.map((f) => {
        f.select = false;
        return f;
      });
      this.allFalse = true;
    },
    selectFood(index) {
      if (this.listFoods[index].select) {
        this.listFoods[index].select = false;
        this.allFalse = true;
        this.selectedFood = null;
        return;
      }

      !this.allFalse && this.setFalse();
      this.listFoods[index].select = true;
      this.selectedFood = this.listFoods[index];
      this.allFalse = false;
    },
    searchFood() {
      !this.allFalse && this.setFalse();
      if (this.item) {
        this.listFoods = this.foods.filter((f) =>
          f.description.toLowerCase().includes(this.item.toLowerCase())
        );
      } else {
        this.listFoods = this.foods;
      }
    },
    viewImage(url) {
      this.currentImage = null;
      if (!url) return;
      let formated = url.replace("public", "storage");
      this.currentImage = `/${formated}`;

      this.showImage = true;
    },
    open() {
      this.ordenItems = [];
      this.orden = [];
      if (this.table.length != 0) {
        this.ordenItems = this.table.orden.orden_items;

        // this.activeName = "orden";
        this.ordenId = this.table.orden.id;
        setTimeout(() => this.calculateOrden(), 50);
      }
      if (!this.categories.some((c) => c.id == 0)) {
        this.categories.unshift({
          id: 0,
          description: "todos",
        });
      }
      this.listFoods = this.foods.map((f) => ({ ...f, select: false }));
      this.title = `Mesa N°${this.table.number}`;

      console.log(this.orden, this.ordenItems);
    },
    close() {
      this.ordenId = null;
      this.$emit("update:showMenu", false);
      this.$emit("update:currentTable", null);
    },
  },
};
</script>
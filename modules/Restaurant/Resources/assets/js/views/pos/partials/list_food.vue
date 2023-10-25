<template>
  <div>
    <div class="row p-2">
         <h2 class="small-title">Productos</h2>
        <template v-if="listFoods.length==0">
            <div class="col-12 text-center font-weight-bold">
                <label>No Hay Productos</label>
            </div>
        </template>

        <template v-else>
            <div class="col-sm-6 col-md-6 col-lg-4  col-xl-4 p-1" v-for="(data, index) in listFoods" :key="index" @click="selectFood(index)">
                   <!--  -->
                <div class="coupon rounded mb-3 d-flex justify-content-between p-1" id="card">
                <div class="kiri p-1">
                    <div class="icon-container ">
                        <div class="icon-container_box">
                              <template v-if="data.image=='imagen-no-disponible.jpg'">
                                    <img src="/images/imagen-no-disponible.jpg" alt="User Img" class="card-img card-img-horizontal h-100 thumbail">
                                </template>
                                <template v-else>
                                    <img :src="formatUrlImage(data.image)" class="card-img card-img-horizontal thumbail" />
                                </template>
                        </div>
                    </div>
                </div>
                <div class="tengah py-2 d-flex w-100 justify-content-start">
                    <div>
                        <h3 class="lead-font-weight-700"> {{ data.description.toUpperCase() }} </h3>
                        <p class="badge bg-foreground text-uppercase font-weight-light p-0">{{ data.category.name }}</p><br>
                         <p class="badge text-success text-uppercase font-weight-light p-0 lead-font-weight-500">Ubicación: {{ data.location }}</p>
                    </div>
                </div>
                <div class="kanan">
                    <div class="info m-1 d-flex align-items-center text-center">
                        <div class="w-100">
                            <div class="block mb-2">
                                <span class="time font-weight-light">
                                        <span class="text-muted lead-font-weight-700"> S/ {{ data.price }}</span>
                                        <!-- <template v-if="data.item.stock>0">
                                        <span class="badge rounded-pill bg-primary m-l-0">Stock
                                            {{ parseInt(data.item.stock) }}
                                        </span>
                                        </template>
                                        <template v-else>
                                        <span class="badge rounded-pill bg-danger m-l-0">
                                            Agotado
                                        </span>
                                        </template> -->

                                </span>
                            </div>
                            <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary" @click="addFood(index)">
                               <i class="fas fa-shopping-cart"></i> Agregar
                            </a>
                            <!-- <template v-if="configuration.sales_stock==true">
                            <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary" @click="addFood(index)">
                               <i class="fas fa-shopping-cart"></i> Agregar
                            </a>
                            </template>
                            <template v-else>
                            <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary" v-if="data.item.stock>0" @click="addFood(index)">
                               <i class="fas fa-shopping-cart"></i> Agregar
                            </a>
                            </template> -->

                        </div>
                    </div>
                </div>
            </div>
             </div>
            <!-- <div class="col-sm-6 col-md-6 col-lg-3  col-xl-3 p-1" v-for="(data, index) in listFoods" :key="index" @click="selectFood(index)">
                <div class="coupon rounded mb-3 d-flex justify-content-between" id="card">
                <div class="kiri">
                              <img class="img-product h-100" :src="formatUrlImage(data.image)" :alt="data.description.toUpperCase()" width="70"/>
                 </div>
                <div class="tengah py-3 d-flex w-100 justify-content-start">
                    <div>
                        <h5 class="lead card-title"> {{ data.description.toUpperCase() }} </h5>
                        <p class="text-muted mb-0 badge badge-success txt-white">{{ data.category.name }}</p>
                     </div>
                </div>
                <div class="kanan">
                    <div class="info m-3 d-flex align-items-center">
                        <div class="w-100">
                            <div class="block text-center">
                                <span class="time font-weight-light">
                                    <span> S/ {{ data.price }}</span>
                                </span>
                            </div>
                            <a href="javascript:void(0)"  class="btn btn-sm btn-secondary" @click="addFood(index)">
                               <i class="fas fa-shopping-cart"></i>
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        </template>
    </div>
   </div>
</template>

<script>
  export default {
  props: ["foods", "configuration"],
  data() {
    return {
      selectCategory:0,
      activeName: "menu",
      loading:true,
      ordenItems: [],
      orden: [],
      currentFood: {},
      item: null,
      search:'Buscar por Codigo',
      currentImage: null,
      showImage: false,
      listFoods: [],
      selectedFood: null,
      allFalse: true,
      title: null,
      total: null,
      ordenId: null,
      optionsSelected:0,
      generalOrdens: 1,
      settings: {
        "dots": false,
        "dotsClass": "slick-dots custom-dot-class",
        "edgeFriction": 0.35,
        "infinite": false,
          "speed": 500,
          "centerMode": true,
          "centerPadding": "10px",
          "focusOnSelect": true,
          "infinite": true,
          "slidesToShow":3,
          "slidesToScroll": 3,
          "swipeToSlide": true,
          "speed": 500,
          "responsive": [
            {
              "breakpoint": 1024,
              "settings": {
                "slidesToShow": 3,
                "slidesToScroll": 3,
                "infinite": true,
                "dots": true
              }
            },
            {
              "breakpoint": 600,
              "settings": {
                "slidesToShow": 2,
                "slidesToScroll": 2,
                "initialSlide": 2
              }
            },
            {
              "breakpoint": 480,
              "settings": {
                "slidesToShow": 1,
                "slidesToScroll": 1
              }
            }
          ]
        },
       settings_tables: {
        "dots": true,
        "dotsClass": "slick-dots custom-dot-class",
         "infinite": false,
          "speed": 500,
          "centerMode": true,
          "centerPadding": "10px",
          "focusOnSelect": true,
          "infinite": true,
          "slidesToShow":5,
          "slidesToScroll": 5,
          "swipeToSlide": true,
           "responsive": [
            {
              "breakpoint": 1024,
              "settings": {
                "slidesToShow": 2,
                "slidesToScroll": 2,
                "infinite": true,
                "dots": true
              }
            },
            {
              "breakpoint": 600,
              "settings": {
                "slidesToShow": 2,
                "slidesToScroll": 2,
                "initialSlide": 2
              }
            },
            {
              "breakpoint": 480,
              "settings": {
                "slidesToShow": 2,
                "slidesToScroll": 2
              }
            }
          ]
        },

    };
  },
 mounted() {
     if(this.foods.length>0){
         this.loading = false;
     }
 },
  created() {
    this.ordenItems = [];
    this.orden = [];
    // if (!this.categories.some((c) => c.id == 0)) {
    //   this.categories.unshift({
    //     id: 0,
    //     name: "todos",

    //   });
    // }
    this.listFoods = this.foods.map((f) => ({ ...f, select: false }));
    },

  methods: {
    deleteOrden(id) {
      this.ordenItems = this.ordenItems.filter((o) => o.id != id);
    },
    calculateOrden() {

       // this.$refs.ordenRef.calculateTotal();

    },
    selectSearch(value){
        this.optionsSelected=value
    },

    deleteFood(idx) {
      this.orden.splice(idx, 1);
    },
    setCategory(id) {
      this.selectCategory = id;
      !this.allFalse && this.setFalse();
      if (id == 0) {
        this.listFoods = this.foods;
      } else {
        this.listFoods=[]
        this.listFoods =_.filter(this.foods,{'category_food_id':id})
       }
    },
    addFood(index=0) {
    this.selectedFood = this.listFoods[index];
      if (!this.selectedFood) return;
      this.currentFood= {
        id:this.selectedFood.id,
        food: this.selectedFood,
        observation: null,
        quantity:1
      },
      this.$emit("insertOrden", this.currentFood,this.selectedFood.id);
        this.$notify({
          title: this.currentFood.food.description.toLowerCase(),
          duration	:800,
          iconClass:"el-icon-food",
           message: "Agregado con èxito",
            position: 'bottom-right'
         });

      this.currentFood = {
        food: null,
        observation: null,
        quantity:0
      };
      this.selectedFood = null;
      this.item = null;
      //this.setFalse();
      this.$emit("buscarnuevo");
      this.$emit("addItems");
      //this.$forceUpdate();
    },
    setFalse() {
      this.listFoods = this.listFoods.map((f) => {
        f.select = false;
        return f;
      });
      this.allFalse = true;
    },
    selectFood(index) {
     //  this.$refs.description.$el.getElementsByTagName("input")[0].focus();
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
    searchFoodCategories(value){
         if(value==0){
            this.listFoods =this.foods
        }else{
            this.listFoods = _.filter(this.foods,{"category_food_id":value})
        }
     },
   searchFood (value=null,optionsSelected='Descripcion') {
      !this.allFalse && this.setFalse();
      if (value) {
        if(optionsSelected=='Descripcion'){
            this.listFoods = this.foods.filter((f) =>
              f.description.toLowerCase().includes(value)
            );
        }else{
            //this.listFoods=_.filter(this.foods,{'code':value})
            console.log("this.foods",this.foods)
            this.listFoods = this.foods.filter((ff) =>
              ff.code.toLowerCase().includes(value)
            );
        }
      } else {
        this.listFoods = this.foods;
      }
    },
    formatUrlImage(url) {
       if (!url) return;
         let formated ="storage/uploads/items/"+url;
      return `/${formated}`;
    },
     open() {
      this.ordenItems = [];
      this.orden = [];
      if (this.table.orden.length != 0) {
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
    }
  },
};
</script>

<template>
<div>

     <!-- <flickity  class="flickity" :options="flickityOptions">
        <button :class="row.id == selectArea ? 'btn-danger' : 'btn-light'" class="btn  btn-sm mr1" v-for="(row,index) in table" 
            @click="openDialog(table.status_table.id )" :key="index"> 
              <span v-if="row.id == 0">
              <i class="fas fa-eye"></i>
             {{ row.status_table.description.toUpperCase() }}
            </span>
            <span v-if="row.id != 0">
             {{ row.status_table.description.toUpperCase() }}
            </span>
        </button> 
    </flickity> -->
      
          <button  :class="row.id == selectArea ? 'btn-danger' : 'btn-light'" class="btn  btn-sm mr1" v-for="(row,index) in table" 
           @click="openDialog(row.status_table.id )" :key="index"> 
             
               Mesa {{ row.number }} {{ row.status_table.description.toUpperCase() }}
         </button> 
    <!-- <flickity  class="flickity" ref="flickity_tables" :options="flickityOptions">
        <button  :class="row.id == selectArea ? 'btn-danger' : 'btn-light'" class="btn  btn-sm mr1" v-for="(row,index) in table" 
           @click="openDialog(table.status_table.id )" :key="index"> 
              <span v-if="row.id == 0">
              <i class="fas fa-eye"></i>
              {{ table.number }} {{ row.status_table.description.toUpperCase() }}
            </span>
            <span v-if="row.id != 0">
              {{ row.status_table.description.toUpperCase() }}
            </span>
        </button> 
    </flickity> -->
</div>
   <!-- <div class="card" @click="openDialog()">
      <div class="card-body">
        <div class="row d-flex justify-content-center">
          <div
            :class="`p-1
              circle-number
              d-flex
              flex-row
              justify-content-center
              align-items-center ${
                table.status_table.id == 1 ? 'table-free' : 'table-occupy'
              }`"
          >
            <h6
              :class="`text-table ${
                table.status_table.id == 1 ? 'text-free' : 'text-occupy'
              }`"
            >
              {{ table.number }}
            </h6>
          </div>
        </div>
        <div class="row mt-2 d-flex justify-content-center text-center">
          <h4
            :class="`text-table ${
              table.status_table.id == 1 ? 'text-free' : 'text-occupy'
            }`"
          >
            {{ table.status_table.description.toUpperCase() }}
          </h4>
        </div>
    
      </div>
    </div> -->
</template>
<style>

.table-occupy {
  background-color: #f76d6d;
}
.text-occupy {
  color: #882121 !important;
}
.table-free {
  background-color: #ddffcd;
  color:#000 !important
}
.text-free {
  color: #1aa853 !important;
}
.text-table {
  margin: 0;
  font-weight: bold;
  font-size: 20px;
}
@media screen and (max-width: 650px) {
  .text-table {
    font-size: 12.5px;
  }
}
.circle-number {
  width: 5vw;
  height: 5vw;
  border-radius: 50%;
  box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
}
</style>
<script>
import MenuTable from "./menu.vue";
 
export default {
  components: { MenuTable },
  props: ["table", "categories", "foods", "total"],
  data() {
    return {
      selectArea:1,
      showDialog: false,
 
    };
  },
  created() {
    console.log("table",this.table)
  },

  methods: {
    openDialog(id) {
      this.selectArea=id
      // this.showDialog = true;
      this.$emit("selected", this.table);
    },
  },
};
</script>

<style scoped>
.bg-free {
  background-color: rgb(160, 221, 100);
}
</style>


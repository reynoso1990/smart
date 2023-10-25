<template>
 <VueSlickCarousel v-bind="settings">
        <button  :class="area.id == selectArea ? 'btn-primary' : 'btn-light'" class="btn  btn-sm"
            @click="setArea(area.id)"  v-for="(area,index) in data" :key="index"> 
            <span v-if="area.id == 0">
            <i class="fas fa-eye"></i>
            {{ area.description }}
            </span>
            <span v-if="area.id != 0">
            {{ area.description}}
            </span>
        </button> 
 </VueSlickCarousel>
</template>
<script>
import VueSlickCarousel from 'vue-slick-carousel'
 export default {
   components: { VueSlickCarousel},
  props: [
    "opttions",
    "data"
  ],
  data() {
    return {
    }
  }
 }
</script>
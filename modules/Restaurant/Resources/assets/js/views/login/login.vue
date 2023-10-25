<template>
  <div class="col-md-12 text-center">
    <pad></pad>
  </div>
</template>
<script>
import Pad from "./pad.vue";
export default {
  components: { Pad },
  data() {
    return {
      user: "tesctiyo",
    };
  },
  created() {
    console.log("creado");
  },
};
</script>

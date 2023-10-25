<template>
  <el-dialog
    :visible="showDialog"
    @open="open"
    @close="close"
    :title="`Detalle Orden N° ${row.id}`"
  >
    <div class="row" v-for="(data, idx) in row.orden_items" :key="idx">
      <div class="col-2 text-center">
        {{ data.quantity }}
      </div>
      <div class="col-5 text-start">
        {{ data.food.description }}
      </div>
      <div class="col-2 text-end">
        {{ data.food.price }}
      </div>

      <div class="col-3 text-end">
        {{ (data.food.price*data.quantity).toFixed(2) }}
      </div>
    </div>
    <div class="row">
      <hr />
    </div>
    <div class="row">
      <div class="col-9"></div>
      <div class="col-3 text-end">S/ {{ this.total }}</div>
    </div>
  </el-dialog>
</template>

<script>
export default {
  props: ["showDialog", "row"],
  data() {
    return {
      total: 0.0,
    };
  },
  created() {
    this.getTotal();
  },
  methods: {
    open() {
      this.getTotal();
    },
    close() {
      this.$emit("update:showDialog", false);
    },
    getTotal() {
      if (this.row.document) {
        this.total = this.row.document.total;
      }
      if (this.row.sale_note) {
        this.total = this.row.sale_note.total;
      }
    },
  },
};
</script>

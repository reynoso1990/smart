<template>
     <list-orden :configuration="configuration" :area_id.sync="area_id"></list-orden>
 </template>

<script>
import ListOrden from "../worker/list_orden.vue";
export default {
  components: { ListOrden },
  props: [
    "configuration",
    "user",
    "status_table",
    "areas",
    "area_id",
     "categories",
    "foods",
  ],
  data() {
    return {
      showTest: false,
      loading: false,
      ordens: [],
    };
  },
  created() {
    console.log("id de area",this.area_id)
  },
  mounted() {

  },
  methods: {
    deleteOrden(id) {
      this.ordens = this.ordens.filter((o) => o.id != id);
      console.log(this.ordens);
    },
    async ordenReady(id) {
      this.loading = true;
      try {
        const response = await this.$http.get(`ordens-ready/${id}`);
        console.log(response);
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

        this.$message.error("Ocurrió un error");
      }
    },
    async getOrdens() {
      try {
        const response = await this.$http.get("ordens-items");
        this.ordens = response.data.data.filter((o) => o.status_orden_id == 1);
            this.$emit('update:area_id',response.data.area_id);
         //this.area_id= response.data.area_id;
      } catch (e) {
        console.log(e);
      }
    },
    openTest() {
      this.showTest = true;
    },
  },
};
</script>

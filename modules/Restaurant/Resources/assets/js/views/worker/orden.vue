<template>
  <div class="ml-3" v-loading="loading">
    <template v-if="orden.length != 0 || ordenItems.length != 0">
      <div class="row" v-if="orden.length != 0">
        <el-dialog
          @close="closeLocalObservationDialog"
          :visible="dialogLocalObservation"
          append-to-body
          title="Editando observación"
        >
          <label class="control-label"> Observación </label>
          <el-input v-model="localObservation">
            <i slot="prefix" class="el-icon-edit-outline"></i
          ></el-input>
          <div class="row mt-1 d-flex flex-row justify-content-end">
            <button
              class="btn btn-sm btn-primary"
              @click="changeLocalObservation"
            >
              Cambiar
            </button>
          </div>
        </el-dialog>
        <div
          class="w-100 d-flex flex-row align-items-center"
          v-for="(o, index) in orden"
          :key="index"
        >
          <button class="btn btn-danger btn-sm me-2" @click="deleteFood(index)">
            <i class="fas fa-times"></i>
          </button>
          <div class="d-flex flex-column">
            <span>{{ o.food.description.toUpperCase() }}</span>
            <label
              class="control-label text-danger"
              role="button"
              @click="openLocalObservationDialog(index)"
            >
              <em>{{ o.observation }}</em>
            </label>
          </div>
        </div>
      </div>
      <div class="row" v-if="orden.length != 0">
        <div class="col-12 d-flex justify-content-end">
          <button @click="submit" class="btn btn-success btn-sm">
            Enviar pedido
          </button>
        </div>
      </div>
      <div class="row">
        <hr />
      </div>
      <div class="row" v-if="ordenItems.length != 0">
        <el-dialog
          key="db"
          v-loading="loadingObservation"
          @close="closeLocalObservationDialog"
          :visible="dialogObservation"
          append-to-body
          title="Editando observación"
        >
          <label class="control-label"> Observación </label>
          <el-input v-model="observation">
            <i slot="prefix" class="el-icon-edit-outline"></i
          ></el-input>
          <div class="row mt-1 d-flex flex-row justify-content-end">
            <button class="btn btn-sm btn-primary" @click="changeObservation">
              Cambiar
            </button>
          </div>
        </el-dialog>
        <div class="col-12">
          <h6>Pedidos realizados </h6>
        </div>
        
        <div class="col-12" v-for="(ord, idx) in ordenItems" :key="idx">
          <div class="d-flex flex-row align-items-center">
            <el-tooltip
              v-if="ord.status_orden_id != 3"
              class="item"
              effect="dark"
              content="Pedido listo"
              placement="top-start"
            >
              <button
                class="btn btn-sm btn-success"
                @click="ordenReady(ord.id)"
              >
                <i class="fas fa-check"></i>
              </button>
            </el-tooltip>
            <el-tooltip
              class="item"
              effect="dark"
              content="Cancelar pedido"
              placement="top-start"
            >
              <button
                class="btn btn-sm btn-danger"
                @click="cancelOrden(ord.id)"
              >
                <i class="fas fa-times"></i>
              </button>
            </el-tooltip>

            <div class="d-flex flex-column">
              <span>
                {{ ord.food.description.toUpperCase() }} S/
                {{ ord.food.price }}</span
              >
              <label
                class="control-label text-danger"
                role="button"
                @click="openObservationDialog(ord.id, idx)"
              >
                <em>{{ ord.observations }}</em>
              </label>
            </div>
          </div>
        </div>
      </div>
      <div class="d-flex flex-column">
        <div v-show="orden.length > 0" class="row">
          <div class="col-9 col-lg-10 text-right font-weight-bold">
            POR ATENDER
          </div>
          <div class="col-3 col-lg-2">S/ {{ totalOrden.toFixed(2) }}</div>
        </div>
        <div v-show="ordenItems.length > 0" class="row">
          <div class="col-9 col-lg-10 text-right font-weight-bold">
            ATENDIDO
          </div>
          <div class="col-3 col-lg-2">S/ {{ totalOrdenItems.toFixed(2) }}</div>
        </div>
        <div class="row">
          <div class="col-9 col-lg-10 text-end font-weight-bold">TOTAL</div>
          <div class="col-3 col-lg-2">S/ {{ total.toFixed(2) }}</div>
        </div>
        <div class="row d-flex flex-row mt-2">
          <div class="col-4 col-md-5 col-lg-5"></div>
          <div
            v-show="ordenItems.length > 0"
            class="
              col-4
              d-flex
              justify-content-end
              align-items-center
              font-weight-bold
            "
          >
            ORDEN # {{ ordenItems.length > 0 ? ordenItems[0].orden_id : "" }}
          </div>
          <div class="col-4 col-md-3 col-lg-3">
            <button class="btn btn-sm btn-success">Terminar atención</button>
          </div>
        </div>
      </div>
    </template>
    <template v-if="orden.length == 0 && ordenItems.length == 0">
      <div class="alert alert-danger" role="alert">
        No se ha realizado ningún pedido.
      </div>
    </template>
  </div>
</template>

<script>
export default {
  props: ["orden", "ordenItems", "ordenId", "tableId", "configurations"],
  data() {
    return {
      loading: false,
      totalOrdenItems: 0.0,
      total: 0.0,
      totalOrden: 0.0,
      dialogLocalObservation: false,
      currentLocalOrden: null,
      localObservation: null,
      dialogObservation: false,
      observation: null,
      loadingObservation: false,
      currentOrden: null,
    };
  },

  created() {},
  methods: {
    async cancelOrden(id) {
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
            let newOrdenItems = [...this.ordenItems];
            newOrdenItems = newOrdenItems.filter((n) => n.id != id);
            this.$emit("update:ordenItems", newOrdenItems);
            this.$message.success(message);
          }
        }
      } catch (e) {
        //todo

        this.$message.error("Ocurrió un error");
      }
    },
    async ordenReady(id) {
      this.loading = true;
      try {
        const response = await this.$http.get(`ordens-ready/${id}`);

        const { success, message } = response.data;
        success ? this.$message.success(message) : this.$message.error(message);
        if (success) {
          let cloneOrdenItems = [...this.ordenItems];
          cloneOrdenItems = cloneOrdenItems.map((o) => {
            if (o.id == id) {
              o.status_orden_id = 3;
            }
            return o;
          });
          this.$emit("update:ordenItems", cloneOrdenItems);
        }
      } catch (e) {
        console.log(e);
        this.$message.error("Ocurrió un error");
      }
      this.loading = false;
    },
    async changeObservation() {
      //this.localObservation
      this.loadingObservation = true;
      const response = await this.$http.post("change-observation", {
        observation: this.observation,
        id: this.currentOrden,
      });
      if (response.status == 200) {
        this.$eventHub.$emit("reloadData");
        let newOrdenItems = [...this.ordenItems];
        newOrdenItems.find((n) => n.id == this.currentOrden).observations =
          this.observation;
      }
      this.loadingObservation = false;
      this.closeObservationDialog();
    },
    openObservationDialog(id, idx) {
      this.currentOrden = id;
      this.observation = this.ordenItems[idx].observations;
      this.dialogObservation = true;
    },
    closeObservationDialog() {
      this.dialogObservation = false;
      this.observation = null;
    },
    changeLocalObservation() {
      let ordenModified = [...this.orden];
      ordenModified[this.currentLocalOrden].observation = this.localObservation;
      this.$emit("update:orden", ordenModified);
      this.closeLocalObservationDialog();
    },
    openLocalObservationDialog(idx) {
      this.currentLocalOrden = idx;
      this.localObservation = this.orden[idx].observation;
      this.dialogLocalObservation = true;
    },
    closeLocalObservationDialog() {
      this.dialogLocalObservation = false;
      this.currentLocalOrden = null;
      this.localObservation = null;
    },
    deleteFood(idx) {
      this.$emit("deleteFood", idx);
    },
    calculateTotal() {
      this.totalOrdenItems = 0.0;
      this.total = 0.0;
      this.totalOrden = 0.0;
      if (this.orden.length != 0) {
        let prices = this.orden.map((o) => Number(o.food.price));
        this.totalOrden = prices.reduce((a, b) => a + b);
      }
      if (this.ordenItems.length != 0) {
        let prices = this.ordenItems.map((o) => Number(o.food.price));
        this.totalOrdenItems = prices.reduce((a, b) => a + b);
      }

      this.total = this.totalOrden + this.totalOrdenItems;
    },
    async submit() {
      let form = {
        id: this.ordenId,
        orden: {
          table_id: this.tableId,
          status_orden_id: 1,
        },
        items: this.orden,
      };
      const response = await this.$http.post("send-orden", form);
      if (response.status == 200) {
        const { message } = response.data;
        this.$message.success(message);
        this.$emit("closeDialog");
        this.$eventHub.$emit("reloadData");
        this.totalOrdenItems = 0.0;
        this.total = 0.0;
        this.totalOrden = 0.0;
      }
    },
    editLocalObservation() {},
  },
};
</script>
<template>
  <div class="row">
    <div
      class="text-center card col-md-3 "
      v-for="(table, index) in tables"
      :key="index"
      @click="toOrder(table)"
    >
      <div class="card-body">
        <h2>N°: {{ table.id }}</h2>
        <h6 :class="table.state == 1 ? 'text-success' : 'text-danger'">
          {{ table.state == 1 ? "Libre" : "Ocupado" }}
        </h6>
        <div
          :class="table.order == 1 ? 'bg-primary' : 'bg-success'"
          v-if="table.order"
        >
          <h6>
            {{ table.order == 1 ? "Pedido enviado." : "Pedido listo" }}
          </h6>
        </div>
      </div>
    </div>
    <el-dialog :visible="showOrder" @close="showOrder = false" width="30%">
      <template v-if="currentTable">
        <div class="row pl-3">
          <div class="text-left">Mesa N°: {{ currentTable.id }}</div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <el-select v-model="food" placeholder="Select">
              <el-option
                v-for="item in foods"
                :key="item.id"
                :label="item.description"
                :value="item.id"
              >
              </el-option>
            </el-select>
          </div>
          <div class="col-md-6">
            <button class="btn btn-success" @click="send(food)">Enviar</button>
          </div>
        </div>
      </template>
    </el-dialog>
  </div>
</template>

<script>
export default {
  props: ["configuration"],
  data() {
    return {
      food: null,
      foods: [
        { id: 1, description: "Food 1" },
        { id: 2, description: "Food 2" },
        { id: 3, description: "Food 3" },
        { id: 4, description: "Food 4" },
        { id: 5, description: "Food 5" },
      ],
      currentTable: null,
      showOrder: false,
      tables: [
        { id: 1, state: 1, order: null },
        { id: 2, state: 1, order: null },
        { id: 3, state: 2, order: null },
        { id: 4, state: 1, order: null },
        { id: 5, state: 1, order: null },
        { id: 6, state: 2, order: null },
      ],
      orders: [],
      order: {},
    };
  },
  methods: {
    async send(idFood) {
      const food = this.foods.find((f) => f.id == idFood);
      const response = await this.$http.post("send-order", {
        table: this.currentTable.id,
        ...food,
      });
      this.currentTable.order = 1;
      this.showOrder = false;
    },
    toOrder(table) {
      this.currentTable = table;
      this.showOrder = true;
    },
  },
  mounted() {
    Echo.channel("receive_order").listen(
      `.receive-${this.configuration.socket_channel}`,
      (e) => {
        const {
          order: { table },
          order: { state },
        } = e;
        this.tables = this.tables.map((t) => {
          if (t.id == table) {
            t.order = state;
            return t;
          }
          return t;
        });
      }
    );
  },
};
</script>
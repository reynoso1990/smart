<template>
  <div class="row">
    <div
      class="text-center card col-md-3 "
      v-for="(order, index) in orders"
      :key="index"
      @click="complete(order)"
    >
      <div class="card-body">
        <h2>N° de mesa: {{ order.table }}</h2>
        <h6>
          {{ order.description }}
        </h6>
        <div class="row">
          <div class="col-md-6">
            <button class="btn btn-success">
              <i class="fas fa-check"></i>
            </button>
          </div>
          <div class="col-md-6">
            <button class="btn btn-danger"><i class="fas fa-times"></i></button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["configuration"],
  data() {
    return {
      foods: [],
      currentTable: null,
      showOrder: false,

      orders: [],
    };
  },
  methods: {
    async complete(order) {
      const response = await this.$http.post("receive-order", {
        table: order.table,
        state: 2,
      });
      if (response.status == 200) {
        console.log(response);
      }
    },
  },
  mounted() {
    Echo.channel("send_order").listen(
      `.order-${this.configuration.socket_channel}`,
      (e) => {
        const { order } = e;
        this.orders = [...this.orders, order];
      }
    );
  },
};
</script>
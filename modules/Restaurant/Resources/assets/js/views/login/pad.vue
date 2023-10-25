<template>
  <div class="mt-5">
    <div class="row d-flex justify-content-center mb-3">
      <div class="col-md-4">
        <input
          v-model="maskPin"
          class="form-control"
          type="text"
          size="2"
          name="listeanzahl"
          value="10"
          style="text-align: center; letter-spacing: 1rem"
        />
      </div>
    </div>
    <div class="container d-flex justify-content-center">
      <div class="row justify-content-center">
        <div class="col-4 col-sm-4 col-md-4 p-1">
          <button class="btn btn-primary btn-block" @click="getOption(1)">
            1
          </button>
        </div>
        <div class="col-4 col-sm-4 col-md-4 p-1">
          <button class="btn btn-primary btn-block" @click="getOption(2)">
            2
          </button>
        </div>
        <div class="col-4 col-sm-4 col-md-4 p-1">
          <button class="btn btn-primary btn-block" @click="getOption(3)">
            3
          </button>
        </div>

        <div class="w-100"></div>

        <div class="col-4 col-sm-4 col-md-4 p-1">
          <button class="btn btn-primary btn-block" @click="getOption(4)">
            4
          </button>
        </div>
        <div class="col-4 col-sm-4 col-md-4 p-1">
          <button class="btn btn-primary btn-block" @click="getOption(5)">
            5
          </button>
        </div>
        <div class="col-4 col-sm-4 col-md-4 p-1">
          <button class="btn btn-primary btn-block" @click="getOption(6)">
            6
          </button>
        </div>

        <div class="w-100"></div>

        <div class="col-4 col-sm-4 col-md-4 p-1">
          <button class="btn btn-primary btn-block" @click="getOption(7)">
            7
          </button>
        </div>
        <div class="col-4 col-sm-4 col-md-4 p-1">
          <button class="btn btn-primary btn-block" @click="getOption(8)">
            8
          </button>
        </div>
        <div class="col-4 col-sm-4 col-md-4 p-1">
          <button class="btn btn-primary btn-block" @click="getOption(9)">
            9
          </button>
        </div>
        <div class="w-100"></div>
        <div class="col-4 col-sm-4 col-md-4 p-1">
          <button class="btn btn-primary btn-block" @click="getOption(0)">
            0
          </button>
        </div>
        <div class="col-4 col-sm-4 col-md-4 p-1">
          <button class="btn btn-danger btn-block" @click="getOption('delete')">
            Eliminar
          </button>
        </div>
        <div class="col-4 col-sm-4 col-md-4 p-1">
          <button class="btn btn-success btn-block" @click="enter">
            Ingresar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      maskPin: null,
      pinHide: "",
      person: null,
      persons: ["Administrador", "Mozo", "Cocinero"],
    };
  },
  methods: {
    getOption(option) {
      if (option == "delete") {
        this.pinHide = "";
        this.maskPin = null;
        return;
      }
      if (this.pinHide && this.pinHide.length == 4) {
        return;
      }
      this.pinHide += option.toString();

      this.maskPin = "*".repeat(this.pinHide.length);
    },
    async enter() {
      const response = await this.$http.post("login", { pin: this.pinHide });
      const {
        data: { success, kitchen, pos },
      } = response;

      if (success) {
        this.$message.success("Sesión exitosa. Bienvenido/a .");
        if (kitchen) {
          window.location.href = "worker/dashboard-kitchen";
        } else if (pos) {
          window.location.href = "worker/dashboard-pos";
        } else {
          window.location.href = "worker/dashboard";
        }
      } else {
        this.$message.error("El PIN no existe. Vuelva a intentarlo.");
        this.pinHide = "";
        this.maskPin = null;
      }
    },
  },
};
</script>

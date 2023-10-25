<template>
  <div>
  <div class="container-fluid p-l-0 p-r-0">
        <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
            <h6><span>Lista de Usuario</span></h6>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active"><span class="text-muted">Lista de Usuarios</span></li>
            </ol>
            </div>
             <div class="col-12 col-md-6 d-flex align-items-start justify-content-end">
                <!-- Contact Button Start -->
                <button type="button" class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto" @click.prevent="clickCreate()">
                <i class="icofont-plus-circle"></i>
                <span>Nuevo</span>
                </button>
                <!-- Contact Button End -->
            </div>
        </div>
        </div>
    </div>
     <div class="container-fluid p-l-0 p-r-0">
    <div class="card">
      <div class="card-header bg-primary">
        <h6 class="my-0 text-white">Lista de Usuarios</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Nombre</th>

                <th class="text-center">Tipo</th>
                <th class="text-center">Área</th>
                <th class="text-center">PIN</th>

                <th class="text-center">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(row, index) in records" :key="index">
                <td class="text-center">{{ index + 1 }}</td>
                <td class="text-center">
                  {{ row.name }}
                </td>

                <td class="text-center">
                  {{ row.type}}
                </td>
                <td class="text-center">
                  {{ row.area }}
                </td>
                <td class="text-center">
                  {{ row.visible ? row.pin : "****" }}

                  <button
                    class="btn ml-1 btn-sm btn-success"
                    @click="visiblePin(index)"
                  >
                    <i class="fas fa-eye"></i>
                  </button>
                </td>
                <td class="text-center">
                  <button
                    type="button"
                    class="btn waves-effect waves-light btn-sm btn-info"
                    @click.prevent="clickCreate(row.id)"
                  >
                    Editar
                  </button>
                  <button
                    type="button"
                    class="btn waves-effect waves-light btn-sm btn-danger"
                    @click.prevent="clickDelete(row.id)"
                  >
                    {{ !!row.active ? "Desactivar" : "Activar" }}
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <create-form
        :showDialog.sync="showDialog"
        :areas="areas"
        :recordId="recordId"
        :workersType="workersType"
      ></create-form>
    </div>
    </div>
  </div>
</template>

<script>
import CreateForm from "./form.vue";
  import {deletable} from '../../../../../../../resources/js/mixins/deletable'
export default {
  props: ["title"],
  mixins: [deletable],
  components: { CreateForm },
  data() {
    return {
      showDialog: false,
      resource: "workers",
      recordId: null,
      areas: [],
      workersType: [],
      records: [],
    };
  },
  created() {
    this.$eventHub.$on("reloadData", () => {
      this.getData();
    });

    this.getTables();

    this.getData();
  },
  methods: {
    visiblePin(idx) {
      this.records[idx].visible = !this.records[idx].visible;
    },
    async getTables() {
      let response = await this.$http.get(`areas/actives`);
      this.areas = response.data.data;
      response = await this.$http.get(`workers-type/actives`);
      this.workersType = response.data.data;
    },
    async getData() {
      const response = await this.$http.get(`${this.resource}/records`);
      this.records = response.data.data.map((d) => {
        d.visible = false;
        return d;
      });
      console.log(this.records);
    },
    clickCreate(recordId = null) {
      this.recordId = recordId;
      this.showDialog = true;
    },
    async clickDelete(id) {
       const response = await this.$http.get(`${this.resource}/${id}`);
      if (response.status == 200) {
        const { message } = response.data;
        this.$message.success(message);
        this.$eventHub.$emit("reloadData");
      }
    },
  },
};
</script>

<template>
  <div>
    <div class="container-fluid p-l-0 p-r-0">
      <div class="page-header">
        <div class="row">
          <div class="col-sm-6">
            <h6><span>Lista de platos y bebidas</span></h6>
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="/dashboard">Dashboard</a>
              </li>
              <li class="breadcrumb-item active">
                <span class="text-muted">Lista de platos y bebidas</span>
              </li>
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
      <div class="card" v-loading="loading">
        <div class="card-header bg-primary">
          <h6 class="my-0 text-white">Lista de platos y bebidas</h6>
        </div>
        <div class="card-body">
          <div class="col-md-12 col-lg-12 col-xl-12 mb-2">
            <div class="row">
              <div class="col-md-4 col col-lg-4">
                <label class="control-label">Buscar por</label>
                <el-select @change="reset" v-model="search.column" :key="1">
                  <el-option
                    v-for="(data, index) in columns"
                    :key="index"
                    :label="data.label"
                    :value="data.value"
                  ></el-option>
                </el-select>
              </div>
              <div
                v-if="search.column !== 'category_food_id'"
                class="col-md-4 col col-lg-4 d-flex align-items-end"
              >
                <el-input
                  placeholder="Arroz con pollo..."
                  v-model="search.value"
                >
                  <i slot="prefix" class="el-icon-edit-outline"></i
                ></el-input>
              </div>
              <div
                v-if="search.column == 'category_food_id'"
                class="col-md-4 col col-lg-4 d-flex align-items-end"
              >
                <el-select v-model="search.value">
                  <el-option
                    v-for="(data, index) in categories"
                    :key="index"
                    :label="data.description"
                    :value="data.id"
                  >
                  </el-option>
                </el-select>
              </div>
              <div class="col-md-4 col col-lg-4 d-flex align-items-end">
                <button @click="filterData" class="btn btn-primary">
                  Buscar
                </button>
              </div>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th class="text-center">Descripción</th>

                  <th class="text-center">Código</th>
                  <th class="text-center">Categoría</th>
                  <th class="text-center">Imagen</th>
                  <th class="text-center">Activo</th>
                  <th class="text-center">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(row, index) in records" :key="index">
                  <td class="text-center">{{ index + 1 }}</td>
                  <td class="text-center">
                    {{ row.description }}
                  </td>

                  <td class="text-center">
                    {{ row.code }}
                  </td>
                  <td class="text-center">
                    {{ row.category.description }}
                  </td>
                  <td class="text-center">
                    <button
                      v-if="!row.image"
                      class="btn btn-sm btn-primary"
                      @click="uploadImage(row)"
                    >
                      <el-tooltip
                        class="item"
                        effect="dark"
                        content="Subir imagen"
                        placement="top-start"
                      >
                        <i class="fas fa-file-upload"></i>
                      </el-tooltip>
                    </button>
                    <button
                      v-if="row.image"
                      @click="viewImage(row)"
                      class="btn btn-sm btn-success"
                    >
                      <el-tooltip
                        class="item"
                        effect="dark"
                        content="Ver imagen"
                        placement="top-start"
                      >
                        <i class="fas fa-eye"></i>
                      </el-tooltip>
                    </button>
                    <button
                      v-if="row.image"
                      @click="uploadImage(row)"
                      class="btn btn-sm btn-primary"
                    >
                      <el-tooltip
                        class="item"
                        effect="dark"
                        content="Cambiar imagen"
                        placement="top-start"
                      >
                        <i class="fas fa-sync"></i>
                      </el-tooltip>
                    </button>
                    <button
                      v-if="row.image"
                      @click="deleteImage(row)"
                      class="btn btn-sm btn-danger"
                    >
                      <el-tooltip
                        class="item"
                        effect="dark"
                        content="Eliminar imagen"
                        placement="top-start"
                      >
                        <i class="fas fa-times"></i>
                      </el-tooltip>
                    </button>
                    <input
                      @change="onFileChange"
                      type="file"
                      name="img[]"
                      class="file1"
                      accept="image/*"
                    />
                  </td>
                  <td class="text-center">
                    {{ row.active ? "Activado" : "Desactivado" }}
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
                      {{ row.active ? "Desactivar" : "Activar" }}
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <create-form
          :showDialog.sync="showDialog"
          :categories="categories"
          :recordId="recordId"
          :configurations="configurations"
        ></create-form>
        <el-dialog
          :title="currentRecord.description"
          v-if="currentRecord"
          :visible="showImage"
          @close="showImage = false"
        >
          <div v-if="currentImage" class="col container">
            <img class="img-responsive" :src="currentImage" alt="Plato" />
          </div>
        </el-dialog>
      </div>
    </div>
  </div>
</template>
<style scoped>
.file1 {
  visibility: hidden;
  position: absolute;
}
</style>
<script>
import CreateForm from "./form.vue";
export default {
  components: { CreateForm },
  props: ["foods", "categories", "configurations"],
  data() {
    return {
      allRecords: [],
      columns: [
        {
          id: 1,
          value: "description",
          label: "Descripción",
        },
        {
          id: 2,
          value: "category_food_id",
          label: "Categoría",
        },
        {
          id: 3,
          value: "code",
          label: "Código",
        },
      ],
      search: { column: null, value: null },
      loading: false,
      showDialog: false,
      resource: "food-list",
      recordId: null,
      showImage: false,
      currentRecord: null,
      currentImage: null,
      areas: [],
      statusTable: [],
      records: [],
    };
  },
  created() {
    this.$eventHub.$on("reloadData", () => {
      this.getData();
    });

    this.getData();
  },
  methods: {
    reset() {
      this.search.value = "";
    },
    filterData() {
      if (!this.search.column || !this.search.value) return;
      let value = this.search.value;
      switch (this.search.column) {
        case "description":
          this.records = this.allRecords.filter((r) =>
            r.description.includes(value)
          );
          break;
        case "code":
          this.records = this.allRecords.filter((r) => r.code.includes(value));
          break;
        default:
          this.records = this.allRecords.filter((r) => r.category.id == value);
          break;
      }
    },
    async uploadImage(record) {
      this.currentRecord = record;
      let file = document.querySelector(".file1");
      file.click();
    },
    async onFileChange(e) {
      this.loading = true;
      this.file = e.target.files[0];
      if (!this.file) return;
      let formData = new FormData();
      formData.append("file", this.file);
      formData.append("id", this.currentRecord.id);
      const response = await this.$http.post(
        `${this.resource}/upload-image`,
        formData
      );
      if (response.status == 200) {
        const { message } = response.data;
        this.$message.success(message);
      }
      this.$eventHub.$emit("reloadData");
      this.loading = false;
    },
    async deleteImage(record) {
      this.loading = true;
      this.currentRecord = record;
      if (!this.currentRecord) return;
      const response = await this.$http.get(
        `${this.resource}/delete-image/${this.currentRecord.id}`
      );
      if (response.status == 200) {
        const { message } = response.data;
        this.$message.success(message);

        this.$eventHub.$emit("reloadData");
      }
      this.loading = false;
    },

    viewImage(record) {
      this.currentRecord = record;
      this.currentImage = null;
      this.showImage = true;
      let url = record.image;
      if (!url) return;
      let formated = url.replace("public", "storage");
      this.currentImage = `/${formated}`;
    },

    async getData() {
      const response = await this.$http.get(`${this.resource}/records`);
      this.allRecords = response.data.data;
      this.records = this.allRecords;
      console.log(this.records);
    },
    clickCreate(recordId = null) {
      this.recordId = recordId;
      this.showDialog = true;
    },
    clickDelete(id) {
      this.destroy(`/${this.resource}/${id}`).then(() =>
        this.$eventHub.$emit("reloadData")
      );
    },
  },
};
</script>
<template>
  <el-dialog
    :title="titleDialog"
    :visible="showDialog"
    @close="close"
    @open="create"
    width="80%"
  >
    <div class="row">
      <div class="col-md-6 col-lg-6">
        <div class="row">
          <div class="col">
            <input
              @change="onFileChange"
              type="file"
              name="img[]"
              class="file"
              accept="image/*"
            />

            <div class="input-group my-3 h-50">
              <input
                type="text"
                class="form-control"
                disabled
                placeholder="Elija una imagen"
                v-model="fileName"
              />

              <div class="input-group-append">
                <button
                  type="button"
                  class="browse btn btn-primary"
                  @click="uploadImage"
                >
                  Subir imagen
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col text-center">
            <img
              src="http://placehold.jp/400x400.png"
              id="preview"
              class="img-thumbnail img-food"
            />
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-6">
        <div class="row">
          <div class="col">
            <div
              class="form-group"
              :class="{ 'has-danger': errors.description }"
            >
              <label class="control-label">Descripción</label>
              <el-input v-model="form.description">
                <i slot="prefix" class="el-icon-edit-outline"></i
              ></el-input>
              <small
                class="text-danger"
                v-if="errors.description"
                v-text="errors.description[0]"
              ></small>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col col-md-6 col-lg-6">
            <div class="form-group" :class="{ 'has-danger': errors.price }">
              <label class="control-label">Precio</label>
              <el-input-number
                class="w-100"
                :precision="2"
                :step="0.1"
                :min="0"
                v-model="form.price"
              >
              </el-input-number>
              <small
                class="text-danger"
                v-if="errors.price"
                v-text="errors.price[0]"
              ></small>
            </div>
          </div>
          <div class="col col-md-6 col-lg-6">
            <div class="form-group" :class="{ 'has-danger': errors.code }">
              <label class="control-label">Código</label>
              <el-input v-model="form.code">
                <i slot="prefix" class="el-icon-edit-outline"></i
              ></el-input>
              <small
                class="text-danger"
                v-if="errors.code"
                v-text="errors.code[0]"
              ></small>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col">
            <div
              class="form-group"
              :class="{ 'has-danger': errors.category_food_id }"
            >
              <label class="control-label">Categoría</label>
              <el-select v-model="form.category_food_id">
                <el-option
                  v-for="(data, index) in categories"
                  :key="index"
                  :label="data.description"
                  :value="data.id"
                ></el-option>
              </el-select>
              <small
                class="text-danger"
                v-if="errors.category_food_id"
                v-text="errors.category_food_id[0]"
              ></small>
            </div>
          </div>
          <div class="col">
            <div
              class="form-group"
              :class="{ 'has-danger': errors.sale_affectation_igv_type_id }"
            >
              <label class="control-label">Tipo de afectación (Venta)</label>
              <el-select
                v-model="form.sale_affectation_igv_type_id"
                @change="changeAffectationIgvType"
              >
                <el-option
                  v-for="option in affectation_igv_types"
                  :key="option.id"
                  :value="option.id"
                  :label="option.description"
                ></el-option>
              </el-select>
              <small
                class="text-danger"
                v-if="errors.sale_affectation_igv_type_id"
                v-text="errors.sale_affectation_igv_type_id[0]"
              ></small>
            </div>
          </div>

        <div class="col-md-12">
            <div
              class="form-group"
              :class="{ 'has-danger': errors.sale_affectation_igv_type_id }"
            >
              <label class="control-label">Lugar de Preparación</label>
              <el-select v-model="form.area_id">
                <el-option
                  v-for="option in areas"
                  :key="option.id"
                  :value="option.id"
                  :label="option.description"
                ></el-option>
              </el-select>
              <small
                class="text-danger"
                v-if="errors.sale_affectation_igv_type_id"
                v-text="errors.sale_affectation_igv_type_id[0]"
              ></small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="form-actions text-end pt-2 pb-2">
      <el-button @click.prevent="close()">Cancelar</el-button>
      <el-button type="primary" @click="submit" :loading="loading_submit"
        >Guardar</el-button
      >
    </div>
  </el-dialog>
</template>

<script>
export default {
  props: ["showDialog", "recordId", "categories", "configurations"],
  data() {
    return {
      loading_submit: false,
      titleDialog: null,
      resource: "food-list",
      errors: {},
      form: {},
      options: [],
      areas:[],
      file: null,
      fileName: null,
      affectation_igv_types: [],
      show_has_igv: false,
    };
  },
  async created() {
    this.initForm();
    await this.$http.get(`/items/tables`).then((response) => {
      this.areas=response.data.areas;
      this.affectation_igv_types = response.data.affectation_igv_types;
    });
    this.form.sale_affectation_igv_type_id =
      this.configurations.affectation_igv_type_id || "10";
  },
  methods: {
    uploadImage() {
      var file = document.querySelector(".file");
      file.click();
    },
    onFileChange(e) {
      this.file = e.target.files[0];
      console.log(this.file);
      this.fileName = this.file.name;
      var reader = new FileReader();
      reader.onload = function (e) {
        document.getElementById("preview").src = e.target.result;
      };

      reader.readAsDataURL(this.file);
    },
    initForm() {
      this.errors = {};
      this.form = {
        id: null,
        description: null,
        active: 1,
        has_igv: false,
        area_id:null
      };
    },
    async create() {
      this.titleDialog = this.recordId ? "Editar " : "Nuevo ";

      this.form.sale_affectation_igv_type_id =
        this.configurations.affectation_igv_type_id || "10";

      //  this.form.sale_affectation_igv_type_id = (this.affectation_igv_types.length > 0)?this.affectation_igv_types[0].id:null
      if (this.recordId) {
        this.$http
          .get(`${this.resource}/record/${this.recordId}`)
          .then((response) => {
            this.form = response.data.data;
            if (this.form.image) {
              document.getElementById(
                "preview"
              ).src = `/${response.data.data.image.replace(
                "public",
                "storage"
              )}`;
            }
          });
      }
    },
    changeAffectationIgvType() {
      let affectation_igv_type_exonerated = [
        20, 21, 30, 31, 32, 33, 34, 35, 36, 37,
      ];
      let is_exonerated = affectation_igv_type_exonerated.includes(
        parseInt(this.form.sale_affectation_igv_type_id)
      );

      if (is_exonerated) {
        this.show_has_igv = false;
        this.form.has_igv = true;
      } else {
        this.show_has_igv = true;
      }
    },
    async submit() {
      this.loading_submit = true;
      let formData = new FormData();
      formData.append("file", this.file);
      formData.append("id", this.recordId);
      formData.append("description", this.form.description);
      formData.append("price", this.form.price);
      formData.append("code", this.form.code);
      formData.append("area_id", this.form.area_id);

      formData.append(
        "sale_affectation_igv_type_id",
        this.form.sale_affectation_igv_type_id
      );
      formData.append("category_food_id", this.form.category_food_id);

      try {
        const response = await this.$http.post(`${this.resource}`, formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        });

        if (response.data.success) {
          this.$message.success(response.data.message);
          this.$eventHub.$emit("reloadData");
          this.close();
        } else {
          this.$message.error(response.data.message);
        }
      } catch (error) {
        if (error.response.status === 422) {
          this.errors = error.response.data;
        } else {
          console.log(error);
        }
      }

      this.loading_submit = false;
    },
    close() {
      this.$emit("update:showDialog", false);
      document.getElementById("preview").src =
        "http://placehold.jp/200x200.png";
      this.fileName = null;
      this.initForm();
    },
  },
};
</script>
<style scoped>
.img-food {
  max-height: 250px;
}
.file {
  visibility: hidden;
  position: absolute;
}
</style>

<template>
  <el-dialog
    :title="titleDialog"
    :visible="showDialog"
    @close="close"
    @open="create"
  >
    <form autocomplete="off" @submit.prevent="submit">
      <div class="form-body">
        <div class="row">
          <div v-if="type !== 'tables'" class="col-md-12">
            <div
              class="form-group"
              :class="{ 'has-danger': errors.description }"
            >
              <label class="control-label">Descripción</label>
              <el-input v-model="form.description"></el-input>
              <small
                class="text-danger"
                v-if="errors.description"
                v-text="errors.description[0]"
              ></small>
            </div>
          </div>
          <div v-if="type == 'areas' && configurations.multiple_boxes===1" class="col-md-6">
            <div
              class="form-group"
              :class="{ 'has-danger': errors.printer }"
            >
              <label class="control-label">Impresora</label>
              <el-input v-model="form.printer"> </el-input>
              <small
                class="text-danger"
                v-if="errors.printer"
                v-text="errors.printer[0]"
              ></small>
            </div>
          </div>
            <div v-if="type == 'areas' && configurations.multiple_boxes===1" class="col-md-6">
            <div
              class="form-group"
              :class="{ 'has-danger': errors.printer }"
            >
              <label class="control-label">Nº COPIAS</label>
              <el-input v-model="form.copies"> </el-input>
              <small
                class="text-danger"
                v-if="errors.copies"
                v-text="errors.copies[0]"
              ></small>
            </div>
          </div>
          <template v-if="type == 'tables'">
            <div class="col-md-12">
              <div class="form-group" :class="{ 'has-danger': errors.number }">
                <label class="control-label w-100 ">Crear Varias mesas</label>
                <el-switch v-model="form.multiple" active-text="Si" inactive-text="No" @change="submit"></el-switch>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group" :class="{ 'has-danger': errors.number }">
                <label class="control-label">Número de mesa</label>
                <el-input v-model="form.number"></el-input>
                <small
                  class="text-danger"
                  v-if="errors.number"
                  v-text="errors.number[0]"
                ></small>
              </div>
            </div>
            <div class="col-md-4">
              <div
                class="form-group"
                :class="{ 'has-danger': errors.status_table_id }"
              >
                <label class="control-label">Estado de mesa</label>
                <el-select v-model="form.status_table_id">
                  <el-option
                    v-for="(data, index) in statusTable"
                    :key="index"
                    :label="data.description"
                    :value="data.id"
                  ></el-option>
                </el-select>
                <small
                  class="text-danger"
                  v-if="errors.status_table_id"
                  v-text="errors.status_table_id[0]"
                ></small>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group" :class="{ 'has-danger': errors.area_id }">
                <label class="control-label">Área de mesa</label>
                <el-select v-model="form.area_id">
                  <el-option
                    v-for="(data, index) in areas"
                    :key="index"
                    :label="data.description"
                    :value="data.id"
                  ></el-option>
                </el-select>
                <small
                  class="text-danger"
                  v-if="errors.area_id"
                  v-text="errors.area_id[0]"
                ></small>
              </div>
            </div>
          </template>
        </div>
      </div>
      <div class="form-actions text-end pt-2 pb-2">
        <el-button @click.prevent="close()">Cancelar</el-button>
        <el-button type="primary" native-type="submit" :loading="loading_submit"
          >Guardar</el-button
        >
      </div>
    </form>
  </el-dialog>
</template>

<script>
export default {
  props: ["showDialog", "recordId", "type", "areas", "statusTable","configurations"],
  data() {
    return {
      loading_submit: false,
      titleDialog: null,
      resource: this.type,
      errors: {},
      form: {},
      options: [],
    };
  },
  created() {
    this.initForm();

  },
  methods: {
    initForm() {
      this.errors = {};
      this.form = {
        id: null,
        description: null,
        printer: null,
        copies:null,
        active: 1,
        multiple:true
      };
    },
    create() {
      this.titleDialog = this.recordId ? "Modificar Registro"  : "Nuevo Registro";
      console.log("this.recordId",this.recordId)
      this.initForm();
      if (this.recordId) {
        this.$http
          .get(`${this.resource}/record/${this.recordId}`)
          .then((response) => {
            this.form = response.data.data;
          });
      }
    },
    submit() {
      this.loading_submit = true;
      this.$http
        .post(`${this.resource}`, this.form)
        .then((response) => {
          if (response.data.success) {
            this.$message.success(response.data.message);
            this.$eventHub.$emit("reloadData");
            this.close();
          } else {
            this.$message.error(response.data.message);
          }
        })
        .catch((error) => {
          if (error.response.status === 422) {
            this.errors = error.response.data;
          } else {
            console.log(error);
          }
        })
        .then(() => {
          this.loading_submit = false;
        });
    },
    close() {
      this.$emit("update:showDialog", false);
      this.initForm();
    },
  },
};
</script>

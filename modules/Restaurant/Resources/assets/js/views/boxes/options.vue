<template>
  <div>
    <el-dialog
      :title="titleDialog"
      :visible="showDialog"
      :close-on-click-modal="false"
      :close-on-press-escape="false"
      :show-close="false"
      width="30%"
    >
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 text-left">
          <el-radio-group v-model="options2" size="mini">
            <el-radio-button label="Ventas Efectivo"></el-radio-button>
            <el-radio-button
              label="Depositos Bancario - Transferencia"
            ></el-radio-button>
          </el-radio-group>
        </div>

        <div
          class="col-lg-12 col-md-12 col-sm-12 text-left"
          v-if="options2 == 'Ventas Efectivo'"
        >
          <el-radio-group v-model="options" size="mini">
            <el-radio-button label="Detallado"></el-radio-button>
            <el-radio-button label="Resumen"></el-radio-button>
            <el-radio-button label="Categoria"></el-radio-button>
          </el-radio-group>
        </div>
      </div>
      <span slot="footer" class="dialog-footer row">
        <div class="col-md-12">
          <el-button @click="clickClose">Cerrar</el-button>
          <el-button type="primary" @click="clickGenerar">Aceptar</el-button>
        </div>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import queryString from "query-string";

export default {
  props: ["showDialog", "ruta", "restaurant"],
  data() {
    return {
      titleDialog: "Exportar Reporte de Arqueo de Caja",
      loading: false,
      resource: "sale-notes",
      resource_documents: "documents",
      errors: {},

      document: {},
      document_types: [],
      all_series: [],
      series: [],
      loading_submit: false,
      showDialogOptions: false,
      documentNewId: null,
      activeName: "first",
      options: "Detallado",
      options2: "Ventas Efectivo",
    };
  },
  created() {},

  methods: {
    async tipo() {
      if ((this.options2 = "Depositos Bancario - Transferencia")) {
        this.options = null;
      } else {
        this.options = "Ventas Efectivo";
      }
    },
    async clickGenerar() {
      let query = queryString.stringify({
        ...this.ruta,
      });
      this.titleDialog = this.titleDialog;
      if (this.options2 == "Ventas Efectivo") {
        if (this.options == "Detallado") {
          let link = `/restaurant/report-boxes/reports_type?${query}`;
          window.open(`${link}`, "_blank");
        } else if (this.options == "Resumen") {
          let link = `/restaurant/report-boxes/reports_resumen_type?${query}`;
          window.open(`${link}`, "_blank");
        } else {
          let link = `/restaurant/report-boxes/reports_categoria_type?${query}`;
          window.open(`${link}`, "_blank");
        }
      } else {
        let link = `/restaurant/report-boxes/reports_bancario_type?${query}`;
        window.open(`${link}`, "_blank");
      }
    },

    clickClose() {
      this.$emit("update:showDialog", false);
    },
  },
};
</script>

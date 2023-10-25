<template>
  <div class="row g-2 justify-content-md-center">
    <div class="col-6 col-md-4 col-lg-2"  v-if="company.certificate_due">
      <div class="card h-100 hover-scale-up cursor-pointer">
        <div class="card-body d-flex flex-column align-items-center">
          <div
            class="sw-6 sh-6 rounded-xl d-flex justify-content-center align-items-center border border-primary mb-4"
          >
            <i data-cs-icon="dollar" class="text-primary"></i>
          </div>
          <div
            class="mb-1 d-flex align-items-center text-alternate text-small lh-1-25 text-center"
          >
          Certificado
          </div>
          <div class="text-primary cta-4">
            {{ company.certificate_due }}
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
      <div class="card h-100 hover-scale-up cursor-pointer">
        <div class="card-body d-flex flex-column align-items-center">
          <div
            class="sw-6 sh-6 rounded-xl d-flex justify-content-center align-items-center border border-primary mb-4"
          >
          <i data-cs-icon="note" class="text-primary"></i>
          </div>
          <div
            class="mb-1 d-flex align-items-center text-alternate text-small lh-1-25 text-center"
          >
          CPE Emitidos
          </div>
          <div class="text-primary cta-4">
            {{ total_cpe }}
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
      <div class="card h-100 hover-scale-up cursor-pointer">
        <div class="card-body d-flex flex-column align-items-center">
          <div
            class="sw-6 sh-6 rounded-xl d-flex justify-content-center align-items-center border border-primary mb-4"
          >
          <i data-cs-icon="dollar" class="text-primary"></i>
          </div>
          <div
            class="mb-1 d-flex align-items-center text-alternate text-small lh-1-25 text-center"
          >
          Monto total <br />comprobantes
          </div>
          <div class="text-primary cta-4">
            {{ document_total_global }}
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
      <div class="card h-100 hover-scale-up cursor-pointer">
        <div class="card-body d-flex flex-column align-items-center">
          <div
            class="sw-6 sh-6 rounded-xl d-flex justify-content-center align-items-center border border-primary mb-4"
          >
           <i data-cs-icon="dollar" class="text-primary"></i>
          </div>
          <div class="mb-1 d-flex align-items-center text-alternate text-small lh-1-25 text-center">
          Monto total notas <br />de ventas
          </div>
          <div class="text-primary cta-4">
            {{ sale_note_total_global }}
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
      <div class="card h-100 hover-scale-up cursor-pointer">
        <div class="card-body d-flex flex-column align-items-center">
          <div class="sw-6 sh-6 rounded-xl d-flex justify-content-center align-items-center border border-primary mb-4">
          <i data-cs-icon="chart-2" class="text-primary"></i>
          </div>
          <div
            class="mb-1 d-flex align-items-center text-alternate text-small lh-1-25 text-center"
          >
          Utilidad <br />neta
          </div>
          <div class="text-primary cta-4">
            {{ utilities.totals.utility }}
          </div>
        </div>
      </div>
    </div>
     
  </div>
</template>

<script>
import moment from "moment";

export default {
  props: ["company", "utilities"],
  data() {
    return {
      document_total_global: 0,
      total_cpe: 0,
      sale_note_total_global: 0,
      total: 0,
    };
  },
  mounted() {
    this.onFetchData();
  },
  computed: {
    isDueWarning() {
      if (this.company.certificate_due) {
        const dueDate = moment(this.company.certificate_due);

        const now = moment();
        const diffInDays = dueDate.diff(now, "days");
        return diffInDays <= 15;
      }
      return false;
    },
  },
  methods: {
    onFetchData() {
      this.$http.get("/dashboard/global-data").then((response) => {
        const data = response.data;
        this.document_total_global = data.document_total_global;
        this.total_cpe = data.total_cpe;
        this.sale_note_total_global = data.sale_note_total_global;
        this.total =
          parseFloat(this.document_total_global) +
          parseFloat(this.sale_note_total_global);
      });
    },
  },
};
</script>
<style>
.card-green {
  background-color: green;
  color: white;
}
.is-due-warning {
  background-color: red;
}
.card-green .card-title {
  color: white;
}
</style>

<template>
  <div class="card mb-0 pt-2 pt-md-0">
    <div class="card-header bg-primary">
      <h6 class="my-0 text-white">Reporte de Arqueo de Caja</h6>
    </div>
    <div class="tab-content p-3">
      <form autocomplete="off" @submit.prevent="submit">
        <div class="form-body">
          <div class="row">
            <div class="col-md-3">
              <label class="control-label">Periodo</label>
              <el-select v-model="form.period" @change="changePeriod">
                <el-option
                  key="month"
                  value="month"
                  label="Por mes"
                ></el-option>
                <el-option
                  key="between_months"
                  value="between_months"
                  label="Entre meses"
                ></el-option>
                <el-option
                  key="date"
                  value="date"
                  label="Por fecha"
                ></el-option>
                <el-option
                  key="between_dates"
                  value="between_dates"
                  label="Entre fechas"
                ></el-option>
              </el-select>
            </div>
            <template
              v-if="form.period === 'month' || form.period === 'between_months'"
            >
              <div class="col-md-3">
                <label class="control-label w-100">Mes de</label>
                <el-date-picker
                  v-model="form.month_start"
                  type="month"
                  @change="changeDisabledMonths"
                  value-format="yyyy-MM"
                  format="MM/yyyy"
                  :clearable="false"
                ></el-date-picker>
              </div>
            </template>
            <template v-if="form.period === 'between_months'">
              <div class="col-md-3">
                <label class="control-label w-100">Mes al</label>
                <el-date-picker
                  v-model="form.month_end"
                  type="month"
                  :picker-options="pickerOptionsMonths"
                  value-format="yyyy-MM"
                  format="MM/yyyy"
                  :clearable="false"
                ></el-date-picker>
              </div>
            </template>
            <template
              v-if="form.period === 'date' || form.period === 'between_dates'"
            >
              <div class="col-md-3">
                <label class="control-label w-100">Fecha del</label>
                <el-date-picker
                  v-model="form.date_start"
                  type="date"
                  @change="changeDisabledDates"
                  value-format="yyyy-MM-dd"
                  format="dd/MM/yyyy"
                  :clearable="false"
                ></el-date-picker>
              </div>
            </template>
            <template v-if="form.period === 'between_dates'">
              <div class="col-md-3">
                <label class="control-label w-100">Fecha al</label>
                <el-date-picker
                  v-model="form.date_end"
                  type="date"
                  :picker-options="pickerOptionsDates"
                  value-format="yyyy-MM-dd"
                  format="dd/MM/yyyy"
                  :clearable="false"
                ></el-date-picker>
              </div>
            </template>
            <template v-if="restaurant">
              <div class="col-md-3">
                <label class="control-label w-100   ">Usuario</label>
                <el-select v-model="form.user_id" clearable>
                  <el-option
                    v-for="(data, idx) in users"
                    :key="idx"
                    :label="data.name"
                    :value="data.id"
                  ></el-option>
                </el-select>
              </div>
            </template>
            <template>
              <div class="col-md-3">
                <label class="control-label">Movimiento</label>
                <el-select
                  v-model="form.type_box"
                  clearable
                >
                  <el-option value="1" label="Ingresos - Ventas"></el-option>
                  <el-option value="2" label="Egresos - Gastos"></el-option>
                </el-select>
              </div>
            </template>

            <div
              class="col-lg-7 col-md-7 col-md-7 col-sm-12"
              style="margin-top: 29px"
            >
              <el-button
                class="submit"
                type="primary"
                @click.prevent="getRecords"
                :loading="loading_submit"
                icon="el-icon-search"
                >Buscar</el-button
              >

              <template v-if="records.length > 0">
                <el-button
                  class="submit"
                  type="danger"
                  icon="el-icon-tickets"
                  @click.prevent="clickDownload('pdf')"
                  >Exportar PDF</el-button
                >

                <el-button
                  class="submit"
                  type="success"
                  @click.prevent="clickDownload('excel')"
                  ><i class="fa fa-file-excel"></i> Exportal Excel</el-button
                >
              </template>
            </div>
          </div>
          <div class="row" v-if="records.length > 0">
            <div class="col-md-12">
              <div class="table-responsive mt-2">
                <table class="table table-striped" width="100%">
                  <tr slot="heading">
                    <th class="">#</th>
                    <th class="">Fecha</th>
                    <th class="">Operacion</th>
                    <th class="">Cliente</th>
                    <th class="">Concepto</th>
                    <th class="">Usuario</th>
                    <th class="">Monto</th>
                  </tr>

                  <tr v-for="(row, index) in records" :key="index">
                    <td class="">{{ index }}</td>
                    <td class="">{{ row.date }}</td>
                    <td class="">{{ row.type }}</td>
                    <td class="">{{ row.cliente }}</td>
                    <td class="">{{ row.description }}</td>
                    <td class="">{{ row.user }}</td>

                    <td class="">{{ row.amount }}</td>
                  </tr>
                  <tr>
                    <td class="text-end" colspan="6">Total Gastos</td>
                    <td class="text-center">{{ totals_egresos }}</td>
                  </tr>
                  <tr>
                    <td class="text-end" colspan="6">Total Ingreso</td>
                    <td class="text-center">{{ totals_ingresos }}</td>
                  </tr>
                  <tr>
                    <td class="text-end" colspan="6">Total Efectivo</td>
                    <td class="text-center">{{ totals_efectivos }}</td>
                  </tr>
                  <tr>
                    <td class="text-end" colspan="6">
                      Total Depositos - Transferencias
                    </td>
                    <td class="text-center">{{ totals_depositos }}</td>
                  </tr>

                  <tr>
                    <td class="text-end" colspan="6">Total S/</td>
                    <td class="text-center">
                      {{ totals_depositos + totals_efectivos }}
                    </td>
                  </tr>
                </table>
                <div>
                  <el-pagination
                    @current-change="getRecords"
                    layout="total, prev, pager, next"
                    :total="pagination.total"
                    :current-page.sync="pagination.current_page"
                    :page-size="pagination.per_page"
                  >
                  </el-pagination>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
    <BoxModal
      :showDialog.sync="modaltype"
      :ruta="ruta"
      :restaurant="restaurant"
    ></BoxModal>
  </div>
</template>

<script>
import moment from "moment";
import queryString from "query-string";
import BoxModal from "./options.vue";
export default {
  props: ["showDialog_report", "restaurant", "users"],
  components: { BoxModal },
  data() {
    return {
      loading_submit: false,
      titleDialog: null,
      resource: "restaurant/report-boxes",
      form: {},
      array_subcategorias: [],
      array_categorias: [],
      array_group: [],
      register_group: false,
      register_category: false,
      register_subcategory: false,
      form_group: [],
      form_category: [],
      form_subcategory: [],
      pagination: {},
      search: {},
      pagination: {},
      records: [],
      totals_ingresos: 0,
      totals_egresos: 0,
      totals_efectivos: 0,
      totals_depositos: 0,
      modaltype: false,
      ruta: null,
      pickerOptionsDates: {
        disabledDate: (time) => {
          time = moment(time).format("YYYY-MM-DD");
          return this.form.date_start > time;
        },
      },
      pickerOptionsMonths: {
        disabledDate: (time) => {
          time = moment(time).format("YYYY-MM");
          return this.form.month_start > time;
        },
      },
    };
  },
  created() {
    this.initForm();

    this.$http.get(`/${this.resource}/tables`).then((response) => {
      this.array_group = response.data.gruop;
      this.array_categorias = response.data.category;
      this.array_subcategorias = response.data.subcategory;
    });
    //            await this.$http.get(`/${this.resource}/record`)
    //                .then(response => {
    //                    if (response.data !== '') {
    //                        this.form = response.data.data
    //                    }
    //                })
  },
  methods: {
    initForm() {
      this.errors = {};
      this.form = {
        id: null,
        user_id: null,
        group_id: null,
        category_id: null,
        subcategory_id: null,
        type: "pdf",
        type_box: null,
        period: "month",
        date_start: moment().startOf("month").format("YYYY-MM-DD"),
        date_end: moment().endOf("month").format("YYYY-MM-DD"),
        month_start: moment().format("YYYY-MM"),
        month_end: moment().format("YYYY-MM"),
      };
    },

    create() {
      this.titleDialog = "Reporte de Arqueo de Caja";
      if (this.recordId) {
        this.$http
          .get(`/${this.resource}/record/${this.recordId}`)
          .then((response) => {
            this.form = response.data.data;
          });
      }
    },
    async getRecordsByFilter() {
      this.loading_submit =  true;
      await this.getRecords();
      this.loading_submit =  false;
    },
    getRecords() {
      // window.open(`/expensesbox/reports?${this.getQueryParameters()}`, '_blank');
       this.loading_submit =  true;
      this.$http
        .get(`/restaurant/report-boxes/reports?${this.getQueryParameters()}`)
        .then((response) => {
          this.records = response.data.data;
          this.pagination = response.data.meta;
          this.pagination.per_page = parseInt(response.data.meta.per_page);
          this.loading_submit = false;
          this.Totals();
        });
    },
    Totals() {
      let letIngresos = _.filter(this.records, { type: "Efectivo - Ingreso" });
      let depositos_total = _.filter(this.records, {
        type: "Transferencia - Ingreso",
      });
      let letEgresos = _.filter(this.records, { type: "Egreso" });
      this.totals_depositos = _.sumBy(depositos_total, (it) =>
        parseFloat(it.amount)
      );
      this.totals_ingresos = _.sumBy(letIngresos, (it) =>
        parseFloat(it.amount)
      );
      this.totals_egresos = _.sumBy(letEgresos, (it) => parseFloat(it.amount));
      this.totals_efectivos = _.round(
        this.totals_ingresos - this.totals_egresos,
        2
      );
    },
    getQueryParameters() {
      return queryString.stringify({
        page: this.pagination.current_page,
        limit: this.limit,
        ...this.form,
      });
    },
    changeDisabledDates() {
      if (this.form.date_end < this.form.date_start) {
        this.form.date_end = this.form.date_start;
      }
      // this.loadAll();
    },
    changeDisabledMonths() {
      if (this.form.month_end < this.form.month_start) {
        this.form.month_end = this.form.month_start;
      }
      // this.loadAll();
    },
    changePeriod() {
      if (this.form.period === "month") {
        this.form.month_start = moment().format("YYYY-MM");
        this.form.month_end = moment().format("YYYY-MM");
      }
      if (this.form.period === "between_months") {
        this.form.month_start = moment().startOf("year").format("YYYY-MM"); //'2019-01';
        this.form.month_end = moment().endOf("year").format("YYYY-MM");
      }
      if (this.form.period === "date") {
        this.form.date_start = moment().format("YYYY-MM-DD");
        this.form.date_end = moment().format("YYYY-MM-DD");
      }
      if (this.form.period === "between_dates") {
        this.form.date_start = moment().startOf("month").format("YYYY-MM-DD");
        this.form.date_end = moment().endOf("month").format("YYYY-MM-DD");
      }
      // this.loadAll();
    },
    clickDownload(type) {
      this.modaltype = true;
      this.form.type = type;
      let form_data = this.form;
      this.ruta = form_data;
      //   window.open(`boxes/reports_type?${query}`, '_blank');

    },
    close() {
      this.$emit("update:showDialog_report", false);
      this.initForm();
    },
  },
};
</script>

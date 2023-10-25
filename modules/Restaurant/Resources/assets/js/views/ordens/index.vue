<template>
  <div>
    <div class="container-fluid p-l-0 p-r-0">
      <div class="page-header">
        <div class="row">
          <div class="col-sm-6">
            <h6><span>Lista de Ordenes atentidas</span></h6>
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="/dashboard">Dashboard</a>
              </li>
              <li class="breadcrumb-item active">
                <span class="text-muted">Lista de Ordenes atentidas</span>
              </li>
            </ol>
          </div>
           <!-- <div class="col-12 col-md-6 d-flex align-items-start justify-content-end">
                 <button type="button" class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto" @click.prevent="clickCreate()">
                <i class="icofont-plus-circle"></i>
                <span>Nuevo</span>
                </button>
             </div> -->
        </div>
      </div>
    </div>
    <div class="container-fluid p-l-0 p-r-0">
      <div class="card" v-loading="loading">
        <div class="card-header bg-primary">
          <h6 class="my-0 text-white">Lista de Ordenes atentidas</h6>
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
                v-if="search.column !== 'date'"
                class="col-md-4 col col-lg-4 d-flex align-items-end"
              >
                <el-input
                  :placeholder="`${
                    search.column == 'orden'
                      ? 'N° de orden'
                      : 'Nombre / N° documento del cliente'
                  }`"
                  v-model="search.value"
                >
                  <i slot="prefix" class="el-icon-edit-outline"></i
                ></el-input>
              </div>
              <div
                v-if="search.column == 'date'"
                class="col-md-4 col col-lg-4 d-flex align-items-end"
              >
                <el-date-picker
                  class="w-100"
                  format="d/MM/yy"
                  value-format="yyyy-MM-dd"
                  v-model="search.value"
                   type="daterange"
                  @change="search_value()"
                >
                </el-date-picker>
              </div>
              <div class="col-md-4 col col-lg-4 d-flex align-items-end"> 
                <button @click="getData" class="btn btn-primary btn-sm mr-5" :loading="loading"><i class="icofont-search"></i> Buscar</button>
                <button @click="DataFoods" class="btn btn-success btn-sm" :loading="loading"><i class="icofont-restaurant"></i> Platos atentidos</button>
              </div>
            </div>
          </div>
          <div class="table-responsive">
             <div>
                <el-pagination
                        @current-change="getData"
                        layout="total, prev, pager, next"
                        :total="pagination.total"
                        :current-page.sync="pagination.current_page"
                        :page-size="pagination.per_page">
                </el-pagination>
            </div>
            <table class="table"  v-loading="loading">
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th class="text-center">N° de Comanda</th>
                  <th class="text-center">Cliente</th>
                  <th class="text-center">Detalle</th>
                  <th class="text-center">Documento</th>
                  <th class="text-center">Estado</th>
                  <th class="text-center">Acciones</th>
                  <th class="text-center">Total Pagado</th>

                </tr>
              </thead>
              <tbody>
                <tr v-for="(row, index) in records" :key="index" :class="{'bg bg-dark': (row.status_orden_id === '1'),
                           'bg bg-secondary': (row.status_orden_id === '2'),
                           'bg bg-warning': (row.status_orden_id === '3'),
                           'bg-success': (row.status_orden_id === '4'),
                           'bg bg-danger': (row.status_orden_id === '5')
                           }">
                  <td class="text-center">{{ index + 1 }}</td>
                  <td class="text-center">
                    {{ row.id }}
                  </td>

                  <td class="text-center">
                    {{ row.customer }}
                  </td>

                  <td class="text-center" >
                    <button
                      class="btn btn-sm btn-primary"
                      @click="viewItems(row)">
                      <i class="fas fa-list"></i>
                    </button>
                  </td>

                  <td class="text-center">
                    {{row.document_type}}<br>
                    <span class="badge rounded-pill  text-dark"> {{row.document_number}}</span><br>

                    <button
                      class="btn btn-sm btn-success"
                      @click="viewFile(row)"
                      v-if="row.status_orden_id==4">
                      <i class="fas fa-file"></i>
                        Descargar
                    </button>
                  </td>

                   <td class="text-center">
                        {{row.status}}
                  </td>

                  <td class="text-center">
                        <el-button type="danger" @click="anular_command(row.id)"> Anular</el-button>
                  </td>
                  <td class="text-center">
                    {{ total(row) }}
                  </td>
                </tr>
              </tbody>
              <tr>
                <td colspan="7" class="text-end">Total de Nota de Venta </td>
                <td class="text-center">{{totals_notas.toFixed(2)}}</td>
              </tr>
              <tr>
                <td colspan="7" class="text-end">Total de Boletas - Facturas </td>
                <td class="text-center">{{totals_facturas.toFixed(2)}}</td>
              </tr>
 <tr>
                <td colspan="7" class="text-end">Total </td>
                <td class="text-center">{{totals_efectivos.toFixed(2)}}</td>
              </tr>
            </table>
            <div>
                <el-pagination
                        @current-change="getData"
                        layout="total, prev, pager, next"
                        :total="pagination.total"
                        :current-page.sync="pagination.current_page"
                        :page-size="pagination.per_page">
                </el-pagination>
            </div>
          </div>
        </div>
        <view-items
          v-if="currentRow"
          :row="currentRow"
          :showDialog.sync="showDialog"
        >
        </view-items>
        <ListFood :showDialogFoods.sync="showDialogFoods" :listFoods.sync="listFoods"></ListFood>
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
import queryString from "query-string";

import ListFood from './listFoods.vue'
import {deletable} from '../../../../../../../resources/js/mixins/deletable'
import ViewItems from "./items.vue";
import moment from "moment";
export default {
  components: { ViewItems,ListFood },
  mixins: [deletable],
  props: ["configuration"],
  data() {
    return {
      allRecords: [],
      listFoods:{},
      columns: [
        {
          id: 1,
          value: "id",
          label: "Número de orden",
        },
        {
          id: 2,
          value: "date",
          label: "Fecha",
        },
        {
          id: 3,
          value: "client",
          label: "Cliente (DNI / RUC)",
        },
      ],
      search: { column: "date", value: moment().format("YYYY-MM-DD") },
      date_start:  moment().format("YYYY-MM-DD"),
      loading: false,
      showDialog: false,
      resource: "ordens",
      currentRow: null,
      showImage: false,
      currentRecord: null,
      currentImage: null,
      areas: [],
      pagination: {},
      statusTable: [],
      records: [],
      totals_notas:0,
      totals_facturas:0,
      totals_efectivos:0,
      showDialogFoods:false
    };
  },
  created() {
    this.$eventHub.$on("reloadData", () => {
      this.getData();
    });

    this.getData();
  },
  methods: {
    viewItems(row) {
      this.currentRow = row;
      this.showDialog = true;
    },
    search_value() {
      console.log(this.search.value)
    },
    anular_command(id){
         this.anular_comanda(`/restaurant/worker/destroyorden/${id}`,id).then(() =>
                    this.$eventHub.$emit('reloadData')
        )
    },
    async DataFoods(){
        const response = await this.$http.get(`${this.resource}/listfoods/${this.search.value}`)
        this.listFoods=response.data
        this.showDialogFoods = true
    },
    viewFile(row) {
      let url = "";
      if (row.document) {
        let external_id = row.document.external_id;
        url = `/downloads/document/pdf/${external_id}`;
      }
      if (row.sale_note) {
        let external_id = row.sale_note.external_id;
        url = `/sale-notes/print/${external_id}/a4`;
      }
      window.open(url, "_blank");
    },
    total(row) {
      if (row.document) {
        return row.document.total;
      }
      if (row.sale_note) {
        return row.sale_note.total;
      }
      return 0.0;
    },
    getQueryParameters() {
      let desde = this.search.value[0];
      let hasta = this.search.value[1];
      if (this.search.column == "date") {
        return "column=date&page="+this.pagination.current_page+"&desde="+desde+"&hasta="+hasta;        
      } else {
        return queryString.stringify({
                page: this.pagination.current_page,
                limit: this.limit,
                ...this.search
        })  
      }
            
    },
    reset() {
      this.search.value = "";
    },
    Totals() {
      let facturas = _.filter(this.records, { 'document_type_id': "01" });

      let notas_venta = _.filter(this.records, {'document_type_id': "80"});
      console.log("notas_venta",notas_venta)
      console.log("facturas",facturas)
      console.log("this.records",this.records)
      this.totals_facturas = _.sumBy(facturas, (it) => parseFloat(it.total));
      this.totals_notas = _.sumBy(notas_venta, (it) => parseFloat(it.total));
      this.totals_efectivos = _.round(
        this.totals_facturas + this.totals_notas,
        2
      );
    },
    async getData() {
      this.loading=true;
      //let query = queryString.stringify(this.search);
      const response = await this.$http.get(`${this.resource}/records?${this.getQueryParameters()}`
      );
      this.pagination = response.data.meta
      this.pagination.per_page = parseInt(response.data.meta.per_page)
      this.records = response.data.data;
      this.loading=false;
      this.Totals()
    },

    clickDelete(id) {
      this.destroy(`/${this.resource}/${id}`).then(() =>
        this.$eventHub.$emit("reloadData")
      );
    },
  },
};
</script>

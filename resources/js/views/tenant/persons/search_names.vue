<template>
    <el-dialog :close-on-click-modal="false" :title="titleDialog" :visible="showDialogName" :append-to-body="true"  
        @close="close" @open="create" width="80%" top="15vh">
        <form autocomplete="off">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <div :class="{ 'has-danger': errors.nombres }" class="form-group">
                        <label class="control-label">Nombre
                            <span class="text-danger">*</span></label>
                        <el-input v-model="form.nombres" dusk="apellido_paterno"></el-input>
                    </div>
                </div>
              
                <div class="col-md-3">
                    <div :class="{ 'has-danger': errors.apellido_paterno }" class="form-group">
                        <label class="control-label">Apellidos Paterno
                            <span class="text-danger">*</span></label>
                        <el-input v-model="form.apellido_paterno" dusk="apellido_paterno"></el-input>
                    </div>
                </div>
                <div class="col-md-3">
                    <div :class="{ 'has-danger': errors.apellido_materno }" class="form-group">
                        <label class="control-label">Apellidos Materno
                            <span class="text-danger">*</span></label>
                        <el-input v-model="form.apellido_materno" dusk="apellido_materno"></el-input>
                    </div>
                </div>
                <div class="col-md-3">
                    <span slot="footer" class="dialog-footer form-actions text-end pt-2">
                         <el-button @click.prevent="close()">Cerrar</el-button>
                        <el-button @click.prevent="search_name()" type="primary" :loading="loading_data">Buscar</el-button>
                    </span>   
                </div>
            </div>    
            <div class="row" v-loading="loading_data">
                <div class="col-md-12">
                    <div class="block">
                        <el-pagination
                            @current-change="search_name"
                            layout="total, prev, pager, next"
                            :total="pagination.total"
                            :current-page.sync="pagination.current_page"
                            :page-size="pagination.per_page"
                        >
                        </el-pagination>
                    </div>
                    <table class="table table-hover">
                        <thead >
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col" @click="sortColumn('numero')">N° DNI <i class="fa fa-sort"></i></th>
                                <th scope="col" @click="sortColumn('nombres')">Nombre <i class="fa fa-sort"></i></th>
                                <th scope="col" @click="sortColumn('apellido_paterno')">Apellido Paterno <i class="fa fa-sort"></i></th>
                                <th scope="col" @click="sortColumn('apellido_materno')">Apellido Materno <i class="fa fa-sort"></i></th>
                                <th scope="col" @click="sortColumn('domicilio')">Direccion  <i class="fa fa-sort"></i></th>
                                <th scope="col">Ubigeo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in data_reniec" :key="index" :class="{ selected_number: selectedIndex === index }"  @dblclick="showRow(index)" @click="selectRow(index)">
                            <td>{{ index+1 }}</td>
                            <td>{{ item.numero }}</td>
                            <td>{{ item.nombres }}</td>
                             <td>{{ item.apellido_paterno }}</td>
                             <td>{{ item.apellido_materno }}</td>
                             <td>{{ item.domicilio }}</td>
                             <td>{{ item.ubigeo_sunat }}</td>
                            </tr>
                        </tbody>
                    </table>
                 </div>
            </div>
            <div class="row justify-content-end">
                <div class="col-md-4 text-end">
               
                </div>
            </div>         
        </form>
    
    </el-dialog>    
</template>

<script>
import queryString from 'query-string'
export default {
    props: [
        "showDialogName",
        "api_token"
    ],
    data() {
        return {
            loading_data:false,
            loading_submit:false,
            titleDialog:"Buscar DNI por Nombres y Apellidos",
            errors:{},
            data_reniec:[],
             form: {},
            selectedIndex: null,
            pageSize: 5, // Número de elementos por página
            currentPage: 1,
            sortKey: "", // Columna por la que se está ordenando
            sortOrder: 1, // 1 para ascendente, -1 para descendente
            selectRows:null,
            pagination: {},
        };
    },
    async created() {
      
        await this.initForm();
        
    },
    computed: {
    sortedData() {
      const data = [...this.data_reniec];
      if (this.sortKey) {
        data.sort((a, b) =>
          a[this.sortKey] > b[this.sortKey]
            ? this.sortOrder
            : -this.sortOrder
        );
      }
      return data;
    },
    paginatedData() {
      const startIndex = (this.currentPage - 1) * this.pageSize;
      const endIndex = startIndex + this.pageSize;
      return this.sortedData.slice(startIndex, endIndex);
    },
    pageCount() {
      return Math.ceil(this.sortedData.length / this.pageSize);
    },
  },
    methods: {
        // sortBy(column) {
        //  this.sortByColumn = column;
        //  if (this.sortByColumn === column) {
        //     this.sortDirection *= -1;
        // } else {
        //     this.sortByColumn = column;
        //     this.sortDirection = 1;
        // }        
        //},
        sortColumn(key) {
        if (key === this.sortKey) {
            this.sortOrder *= -1;
        } else {
            this.sortKey = key;
            this.sortOrder = 1;
        }
        this.currentPage = 1; // Reiniciar a la primera página después de ordenar
        },
        changePage(page) {
        this.currentPage = page;
        },
        initForm() {
            this.errors = {};
            this.form = {
                nombres: null,
                apellido_paterno: null,
                apellido_materno: null,
                precise_search:true, 
            };
            this.pagination= {}
            this.data_reniec=[]
        },
        selectRow(index) {
          this.selectedIndex = index;
        },
        showRow(index) {
            this.selectRows = this.data_reniec[index];
            this.$emit("selectRows", this.selectRows);
            this.close(); 
        },
        async create(){
            this.initForm();
        },
        async search_name(){
            if(this.form.nombres==null){
                return    this.$message.error("Ingrese el nombres a buscar...");
            }
            if(this.form.apellido_paterno==null){
                return    this.$message.error("Ingrese el Apellido Paterno a buscar...");
            }
            if(this.form.apellido_materno==null){
                return    this.$message.error("Ingrese el Apellido Materno a buscar...");
            }
             const config = {
                headers: {
                    'Authorization': `Bearer ${this.api_token}`
                }
            };
            this.loading_data = true;
            await this.$http.post(`https://apiperu.net/api/dni/searchbyname?${this.getQueryParameters()}`,this.form,config)
            .then(response => {
                this.loading_data = false;
                this.data_reniec = response.data.data;
                this.pagination = response.data.meta;
                    this.pagination.per_page = parseInt(
                        response.data.meta.per_page
                    );
            })
            .finally(() => {
                this.loading_data = false;
            });
        },
        getQueryParameters() {
 
            return queryString.stringify({
                page: this.pagination.current_page,
                limit: this.limit
            });
        },
         close() {
             this.$emit("update:showDialogName", false);
            this.initForm();
        },
  
    
    }
};
</script>
<style>
.selected_number {
  background-color: #a0c4ff;
  color: white  !important;
}
</style>
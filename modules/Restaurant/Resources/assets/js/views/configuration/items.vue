<template>
    <div>
        <div class="page-title-container mb-0">
            <div class="row">
                 <div class="col-12 col-md-7">
                    <h1 class="mb-0 pb-0 display-4" id="title">{{ title }}</h1>
                    <nav
                        class="breadcrumb-container d-inline-block"
                        aria-label="breadcrumb"
                    >
                        <ul class="breadcrumb pt-0">
                            <li class="breadcrumb-item">
                                <a href="/dashboard">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">
                                <span class="text-muted">{{ title }}</span>
                            </li>
                        </ul>
                    </nav>
                </div>

                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                    <button
                        type="button"
                        class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto add-datatable"
                        @click.prevent="clickCreate()"
                    >
                        <i data-cs-icon="plus"></i>
                        <span>Nuevo</span>
                    </button>
                 </div>
             </div>
        </div>

        <!-- Content Start -->
        <div class="data-table-rows slim">
            <!-- Controls Start -->
            <div class="row">
                <!-- Search Start -->
                <div
                    class="col-sm-12 col-md-5 col-lg-3 col-xxl-2 mb-1"
                    v-show="
                        search.column == 'description' ||
                            search.column == 'number'
                    "
                >
                    <h2 class="small-title">Buscar</h2>
                    <div
                        class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 shadow bg-foreground"
                    >
                        <input
                            class="form-control datatable-search"
                            placeholder="Buscar"
                            id="enter_buscardor"
                            v-model="search.value"
                            @input="getData"
                        />
                        <span class="search-magnifier-icon">
                            <i data-cs-icon="search"></i>
                        </span>
                        <span class="search-delete-icon d-none">
                            <i data-cs-icon="close"></i>
                        </span>
                    </div>
                </div>
                <div
                    class="col-sm-12 col-md-5 col-lg-3 col-xxl-2 mb-1"
                    v-show="search.column == 'active'"
                >
                    <h2 class="small-title">Buscar</h2>
                    <!-- <Select2 v-model="search.value" :options="myOptions"  :settings="{ width: '100%' }"
                          @change="myChangeEvent($event)"
                          @select="mySelectEvent($event)" /> -->
                    <select class="form-control">
                        <option value="html">HTML</option>
                        <option value="css">CSS</option>
                        <option value="sass">SASS</option>
                        <option value="javascript">Javascript</option>
                        <option value="jquery" selected>jQuery</option>
                    </select>
                </div>
                <!-- Search End -->

                <div
                    class="col-sm-12 col-md-7 col-lg-9 col-xxl-10 text-end mb-1"
                >
                    <div
                        class="d-inline-block me-0 me-sm-3 float-start float-md-none"
                    >
                        <!-- Edit Button Start -->
                        <button
                            class="btn btn-icon btn-icon-only btn-foreground-alternate shadow edit-datatable"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Modificar"
                            type="button"
                            data-bs-delay="0"
                            @click.prevent="clickCreate('edit')"
                        >
                            <i data-cs-icon="edit"></i>
                        </button>
                        <!-- Edit Button End -->

                        <!-- Delete Button Start -->
                        <button
                            @click="clickDelete()"
                            class="btn btn-icon btn-icon-only btn-foreground-alternate shadow delete-datatable"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Eliminar"
                            type="button"
                            data-bs-delay="0"
                        >
                            <i data-cs-icon="bin"></i>
                        </button>
                        <!-- Delete Button End -->
                    </div>
                    <div class="d-inline-block">
                        <!-- Print Button Start -->
                        <!-- <button
                        class="btn btn-icon btn-icon-only btn-foreground-alternate shadow datatable-print"
                        data-datatable="#datatableRows"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        data-bs-delay="0"
                        title="Print"
                        type="button"
                      >
                        <i data-cs-icon="print"></i>
                      </button> -->
                        <!-- Print Button End -->

                        <!-- Export Dropdown Start -->
                        <!-- <div class="d-inline-block datatable-export" data-datatable="#datatableRows">
                        <button class="btn p-0" data-bs-toggle="dropdown" type="button" data-bs-offset="0,3">
                          <span
                            class="btn btn-icon btn-icon-only btn-foreground-alternate shadow dropdown"
                            data-bs-delay="0"
                            data-bs-placement="top"
                            data-bs-toggle="tooltip"
                            title="Export"
                          >
                            <i data-cs-icon="download"></i>
                          </span>
                        </button>
                        <div class="dropdown-menu shadow dropdown-menu-end">
                          <button class="dropdown-item export-copy" type="button">Copy</button>
                          <button class="dropdown-item export-excel" type="button">Excel</button>
                          <button class="dropdown-item export-cvs" type="button">Cvs</button>
                        </div>
                      </div> -->
                        <!-- Export Dropdown End -->

                        <!-- Length Start -->
                        <div
                            class="dropdown-as-select d-inline-block datatable-length"
                            data-datatable="#datatableRows"
                            data-childSelector="span"
                        >
                            <button
                                class="btn p-0 shadow"
                                type="button"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                                data-bs-offset="0,3"
                            >
                                <span
                                    class="btn btn-foreground-alternate dropdown-toggle"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    data-bs-delay="0"
                                    title="Opciones de Busqueda"
                                >
                                    {{ active_column }}
                                </span>
                            </button>
                            <div class="dropdown-menu shadow dropdown-menu-end">
                                <template v-for="(label, key) in columns">
                                    <a
                                        class="dropdown-item"
                                        :class="
                                            search.column == key ? 'active' : ''
                                        "
                                        href="javascript:void(0)"
                                        :key="key"
                                        @click="changeClearInput(key, label)"
                                    >
                                        {{ label }}
                                    </a>
                                </template>
                            </div>
                        </div>
                        <!-- Length End -->
                    </div>
                </div>
            </div>
            <!-- Controls End -->

            <!-- Table Start -->
            <div class="table-responsive" v-loading="loading_data">
                <table
                    id="datatableRows"
                    class="data-table nowrap hover dataTable no-footer w-100"
                    role="grid"
                >
                    <thead>
                        <tr>
                            <th
                                class="text-muted text-small text-uppercase text-center"
                            >
                                #
                            </th>
                            <th
                                v-if="type != 'tables'"
                                class="text-muted text-small text-uppercase"
                            >
                                Descripción
                            </th>
                            <th
                                v-if="type == 'tables'"
                                class="text-muted text-small text-uppercase"
                            >
                                Número
                            </th>
                            <th
                                v-if="type == 'tables'"
                                class="text-muted text-small text-uppercase"
                            >
                                Área
                            </th>
                            <th class="text-muted text-small text-uppercase">
                                Activo
                            </th>
                            <th
                                class="text-muted text-small text-uppercase"
                                v-if="type == 'areas'"
                            >
                                Impresora
                            </th>
                            <th
                                class="text-muted text-small text-uppercase text-right"
                            >
                                Acciones
                            </th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(row, index) in records"
                            :key="index"
                            class="odd"
                            :class="{ selected: row.selected === true }"
                            @click="selected(index)"
                        >
                            <td class="text-center">{{ index + 1 }}</td>
                            <td v-if="type != 'tables'">
                                {{ row.description }}
                            </td>
                            <td v-if="type == 'tables'">
                                {{ row.number }}
                            </td>
                            <td v-if="type == 'tables'">
                                {{ row.area.description }}
                            </td>
                          <template  v-if="type == 'tables'">
                            <td>
                                {{ row.status_table }}
                            </td>
                          </template>
                          <template  v-else>
                            <td>
                                {{ !!row.active ? "Activado" : "Desactivado" }}
                            </td>
                          </template>
                            <td v-if="type == 'areas'">
                                {{ row.printer }}
                            </td>
                            <td>
                                <div class="form-check float-end mt-1">
                                    <input
                                        type="checkbox"
                                        v-model="row.selected"
                                        class="form-check-input"
                                    />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- Paginator -->
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <template v-if="pagination.current_page != 1">
                            <li class="page-item" @click="getData(1)">
                                <a
                                    class="page-link"
                                    href="#"
                                    aria-label="First"
                                >
                                    <i class="icofont-curved-double-left"></i>
                                </a>
                            </li>
                        </template>
                        <template v-else>
                            <li class="page-item disabled">
                                <a
                                    class="page-link text-secondary"
                                    href="#"
                                    aria-label="First"
                                >
                                    <i class="icofont-curved-double-left"></i>
                                </a>
                            </li>
                        </template>
                        <template v-for="(page, index) in pagination.links">
                            <template v-if="page.label === '&laquo; Anterior'">
                                <template
                                    v-if="
                                        page.label === '&laquo; Anterior' &&
                                            pagination.current_page != 1
                                    "
                                >
                                    <li class="page-item" :key="index">
                                        <a
                                            class="page-link"
                                            href="javascript:void(0)"
                                            @click="
                                                getData(
                                                    pagination.current_page - 1
                                                )
                                            "
                                        >
                                            <i
                                                class="icofont-thin-left font-weight-bold"
                                            ></i>
                                        </a>
                                    </li>
                                </template>
                                <template v-else>
                                    <li class="page-item disabled" :key="index">
                                        <a
                                            class="page-link"
                                            href="javascript:void(0)"
                                        >
                                            <i
                                                class="icofont-thin-left font-weight-bold"
                                            ></i>
                                        </a>
                                    </li>
                                </template>
                            </template>
                            <li
                                class="page-item"
                                :class="{ active: page.active === true }"
                                :key="index"
                                v-if="
                                    page.label != 'Siguiente &raquo;' &&
                                        page.label != '&laquo; Anterior'
                                "
                            >
                                <a
                                    class="page-link"
                                    href="javascript:void(0)"
                                    @click="getData(page.label)"
                                >
                                    {{ page.label }}
                                </a>
                            </li>

                            <template v-if="page.label === 'Siguiente &raquo;'">
                                <template
                                    v-if="
                                        page.label === 'Siguiente &raquo;' &&
                                            pagination.current_page !=
                                                pagination.last_page
                                    "
                                >
                                    <li class="page-item" :key="index">
                                        <a
                                            class="page-link"
                                            href="javascript:void(0)"
                                            @click="
                                                getData(
                                                    pagination.current_page + 1
                                                )
                                            "
                                        >
                                            <i class="icofont-thin-right"></i>
                                        </a>
                                    </li>
                                </template>
                                <template v-else>
                                    <li class="page-item disabled" :key="index">
                                        <a
                                            class="page-link"
                                            href="javascript:void(0)"
                                        >
                                            <i class="icofont-thin-right"></i>
                                        </a>
                                    </li>
                                </template>
                            </template>
                        </template>
                        <li
                            class="page-item"
                            @click="getData(pagination.last_page)"
                        >
                            <a class="page-link" href="#" aria-label="Last">
                                <i class="icofont-curved-double-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- End Paginator -->
            </div>
            <!-- Table End -->
        </div>
        <!-- Content End -->
        <create-form
            :showDialog.sync="showDialog"
            :areas="areas"
            :type="type"
            :configurations.sync="configurations"
            :recordId.sync="recordId"
            :statusTable="statusTable"
        ></create-form>
    </div>
</template>

<style>
.arrow_down {
    background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB8AAAAaCAYAAABPY4eKAAAAAXNSR0IArs4c6QAAAvlJREFUSA29Vk1PGlEUHQaiiewslpUJiyYs2yb9AyRuJGm7c0VJoFXSX9A0sSZN04ULF12YEBQDhMCuSZOm1FhTiLY2Rky0QPlQBLRUsICoIN/0PCsGyox26NC3eTNn3r3n3TvnvvsE1PkwGo3yUqkkEQqFgw2Mz7lWqwng7ztN06mxsTEv8U0Aam5u7r5EInkplUol/f391wAJCc7nEAgE9Uwmkzo4OPiJMa1Wq6cFs7Ozt0H6RqlUDmJXfPIx+qrX69Ti4mIyHA5r6Wq1egND+j+IyW6QAUoul18XiUTDNHaSyGazKcZtdgk8wqhUKh9o/OMvsVgsfHJy0iWqVrcQNRUMBnd6enqc9MjISAmRP3e73T9al3XnbWNjIw2+KY1Gc3imsNHR0YV4PP5+d3e32h3K316TySQFoX2WyWR2glzIO5fLTSD6IElLNwbqnFpbWyO/96lCoai0cZjN5kfYQAYi5H34fL6cxWIZbya9iJyAhULBHAqFVlMpfsV/fHxMeb3er+Vy+VUzeduzwWC45XA4dlD/vEXvdDrj8DvURsYEWK3WF4FA4JQP9mg0WrHZbEYmnpa0NxYgPVObm5teiLABdTQT8a6vrwdRWhOcHMzMzCiXlpb2/yV6qDttMpkeshEzRk4Wo/bfoe4X9vb2amzGl+HoXNT29vZqsVi0sK1jJScG+Xx+HGkL4Tew2TPi5zUdQQt9otPpuBk3e0TaHmMDh1zS7/f780S0zX6Yni+NnBj09fUZUfvudDrNZN+GkQbl8Xi8RLRtHzsB9Hr9nfn5+SjSeWUCXC7XPq5kw53wsNogjZNohYXL2EljstvtrAL70/mVaW8Y4OidRO1/gwgbUMvcqGmcDc9aPvD1gnTeQ+0nmaInokRj0nHh+uvIiVOtVvt2a2vLv7Ky0tL3cRTXIcpPAwMDpq6R4/JXE4vFQ5FI5CN+QTaRSFCYc8vLy1l0rge4ARe5kJ/d27kYkLXoy2Jo4C7K8CZOsEBvb+9rlUp1xNXPL7v3IDwxvPD6AAAAAElFTkSuQmCC");
}
.arrow_up {
    background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAaCAYAAACgoey0AAAAAXNSR0IArs4c6QAAAwpJREFUSA21Vt1PUmEYP4dvkQ8JFMwtBRocWAkDbiqXrUWXzU1rrTt0bdVqXbb1tbW16C9IBUSmm27cODdneoXjputa6069qwuW6IIBIdLvdaF4OAcOiGeDc87zPs/vd57P96WpFq7p6enbGo1mjKZpeTabjU1MTCRagGnOZHFxcXxtbe1XKpUq7+zslJeXl//Mz8+Hy+Uy3RxSE9qTk5M3otFooVQqgef4Wl9f343FYoEmoISrxuNxFX5f9vb2jhn/PxUKhfLS0tIPfFifUESRUMV8Pv/M6XReRm5rTGQyGeXxeGxYe1ezeBpBOBx2rKysbO7v79d4Wy3Y2Nj4GQqFbgnhaugxwiuGJx99Pp9FLBbXxYTXvTqd7v3MzIy6riIWGxJnMpl7AwMD14xGYyMsSq1WUyQdUqn0eSPlusQIsbGrq+vl4OCgvhFQZd1utyv1en0gEolcqsi47nWJlUrlG5fLZVcoFFy2nDKSDpIWlUoVTCQSEk4lCHmJMZ2GTCbTiMVikfIZ88l7enoos9l8dXt7+z6fDicxSJUokqDX6xXcl2wCROoc0vQCWL3sNfLOSdzR0fHY4XC4tVotl40gmVwup9xuN4OQv+UyqCFGH9rg7SOGYVRcBs3IEG4J0nVnamrqOtvuBDGGgQg9+wHFcVEi4a0LNkbdd6TrPKo8ODc311mteIIYjT/a398/jK+s1jnVM0kXoufCFvq0GuiIGEVgQIhfoygM1QrteEa9dAL7ITiYCt4RMabOK5AyKKzKWtvupLcRciu8D5J0EuDDPyT/Snd39yh6VtY2NhYQSR9G79Ds7OxdskRjEyAufvb7/cPoO5Z6e1+xtVKrq6vfcFzyi/A3ZrPZ3GdNSlwgo5ekE4X2RIQGf2C1WlufFE0GBeGWYQ8YERWLxQtnUVB830MKLZfL9RHir8lkssCn2G751tZWEWe03zTKm15YWPiEiXXTYDB0Ig/t7yd8PRws4EicwWHxO4jHD8/C5HiTTqd1BwcHFozKU89origB+y/kmzgYpgOBQP4fGmUiZmJ+WNgAAAAASUVORK5CYII=");
}
.arrow {
    float: right;
    width: 12px;
    height: 15px;
    background-repeat: no-repeat;
    background-size: contain;
    background-position-y: bottom;
}
table th {
    cursor: pointer;
}
</style>
<script>
import CreateForm from "./form.vue";
import queryString from "query-string";
 export default {
    props: ["type", "title", "configurations"],
    components: { CreateForm },
    data() {
        return {
            showDialog: false,
            resource: this.type,
            recordId: null,
            areas: [],
            statusTable: [],
            ascending: false,
            sortColumn: "",
            search: {
                column: null,
                value: null
            },
            active_column: "",
            columns: [],
            records: [],
            pagination: {},
            loading_data: false,
            disabled_next: false,
            disabled_previos: false,
            myOptions: ["op1", "op2", "op3"]
        };
    },
    created() {
        // this.$eventHub.$on("reloadData", () => {
        //   this.getData();
        // });
        if (this.type == "tables") {
            this.getTables();
        }
        // this.getData();
        this.$eventHub.$on("reloadData", () => {
            this.getData();
            //   this.recordId =null
        });
    },
    async mounted() {
        let column_resource = this.resource; // _.split(this.resource, '/')
        await this.$http
            .get(`/restaurant/${this.resource}/columns`)
            .then(response => {
                this.columns = response.data;
                this.search.column = _.head(Object.keys(this.columns));
                this.active_column = _.head(Object.values(this.columns));
            });
        await this.getData();
    },
    methods: {
        sortTable: function sortTable(col) {
            if (this.sortColumn === col) {
                this.ascending = !this.ascending;
            } else {
                this.ascending = true;
                this.sortColumn = col;
            }

            var ascending = this.ascending;

            this.records.sort(function(a, b) {
                if (a[col] > b[col]) {
                    return ascending ? 1 : -1;
                } else if (a[col] < b[col]) {
                    return ascending ? -1 : 1;
                }
                return 0;
            });
        },
        myChangeEvent(val) {
            console.log(val);
        },
        mySelectEvent({ id, text }) {
            console.log({ id, text });
        },
        async getTables() {
            let response = await this.$http.get(
                `areas/records?column=description&page=1&value`
            );
            this.areas = response.data.data;
            response = await this.$http.get(
                `status-tables/records?column=description&page=1&value`
            );
            this.statusTable = response.data.data;
        },
        selected(index) {
            this.records[index].selected = !this.records[index].selected;
        },
        async getData(page = 1) {
            this.pagination.current_page = parseInt(page);
            this.pagination.per_page = parseInt(page);
            this.loading_data = true;
            return this.$http
                .get(
                    `/restaurant/${
                        this.resource
                    }/records?${this.getQueryParameters()}`
                )
                .then(response => {
                    this.records = response.data.data;
                    this.pagination = response.data.meta;
                    this.pagination.per_page = parseInt(
                        response.data.meta.per_page
                    );
                    this.loading_data = false;
                });
            //}
        },
        clickCreate(actions = null) {
            this.recordId = null;
            if (actions != null) {
                let nSelected = false;
                for (let index = 0; index < this.records.length; index++) {
                    if (this.records[index].selected == true) {
                        this.recordId = this.records[index].id;
                        nSelected = true;
                        break;
                    }
                }
                if (nSelected == false && this.recordId == "edit") {
                    this.$message.success(
                        "Debe seleccionar un registro para modificar"
                    );
                }
            } else {
                for (let index = 0; index < this.records.length; index++) {
                    this.records[index].selected = false;
                }
            }
            this.showDialog = true;
        },
        getQueryParameters() {
            return queryString.stringify({
                page: this.pagination.current_page,
                limit: this.limit,
                ...this.search
            });
        },
        changeClearInput(key, value) {
            this.search.column = key;
            this.active_column = value;
            this.search.value = "";
            document.getElementById("enter_buscardor").focus();
            // this.$refs.enter_buscardor.$el.getElementsByTagName("input")[0].focus();

            this.getData();
        },
        async clickDelete() {
            this.$confirm(
                "¿Desea eliminar los registros seleccionados ?",
                "Eliminar",
                {
                    confirmButtonText: "Eliminar",
                    cancelButtonText: "Cancelar",
                    type: "warning"
                }
            )
                .then(() => {
                    for (let index = 0; index < this.records.length; index++) {
                        if (this.records[index].selected == true) {
                            this.$http
                                .delete(
                                    `${this.resource}/${this.records[index].id}`
                                )
                                .then(res => {
                                    if (res.data.success) {
                                        this.$message.success(res.data.message);
                                        this.$eventHub.$emit("reloadData");
                                        //resolve()
                                    } else {
                                        this.$message.error(res.data.message);
                                        //resolve()
                                    }
                                })
                                .catch(error => {
                                    if (error.response.status === 500) {
                                        this.$message.error(
                                            "Error al intentar eliminar"
                                        );
                                    } else {
                                        console.log(
                                            error.response.data.message
                                        );
                                    }
                                });
                        }
                    }
                })
                .catch(() => {});
        }
    },

    computed: {
        // "columns": function columns() {
        //   if (this.records.length == 0) {
        //     return [];
        //   }
        //   return Object.keys(this.records[0])
        // }
    }
};
</script>

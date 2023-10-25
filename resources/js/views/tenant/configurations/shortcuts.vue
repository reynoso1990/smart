<template>
    <div>
        <div class="page-header pr-0">
            <h2>
                <a href="#"><i class="fas fa-cogs"></i></a>
            </h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Configuración</span></li>
                <li><span class="text-muted">Accesos directos</span></li>
            </ol>
        </div>
        <div v-loading="loading_submit" class="card card-dashboard border">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-center">
                    <!-- <div
                        v-for="(shortcut, idx) in shortcuts"
                        :key="idx"
                        class="col-md-3 m-2"
                    >
                        <label for="">Acceso directo {{ shortcut }}</label>
                        <el-select
                            @change="submit"
                            v-model="form[shortcuts[shortcut]]"
                        >
                            <el-option
                                v-for="(path, idx) in paths"
                                :key="idx"
                                :label="path.name"
                                :value="path.path"
                            ></el-option>
                        </el-select>
                    </div> -->
                    <div class="col-md-3 m-2">
                        <label for="">Acceso directo 1</label>
                        <el-select @change="submit" v-model="form[1]">
                            <el-option
                                v-for="(path, idx) in paths"
                                :key="idx"
                                :label="path.name"
                                :value="path.path"
                            ></el-option>
                        </el-select>
                    </div>
                    <div class="col-md-3 m-2">
                        <label for="">Acceso directo 2</label>
                        <el-select @change="submit" v-model="form[2]">
                            <el-option
                                v-for="(path, idx) in paths"
                                :key="idx"
                                :label="path.name"
                                :value="path.path"
                            ></el-option>
                        </el-select>
                    </div>
                    <div class="col-md-3 m-2">
                        <label for="">Acceso directo 3</label>
                        <el-select @change="submit" v-model="form[3]">
                            <el-option
                                v-for="(path, idx) in paths"
                                :key="idx"
                                :label="path.name"
                                :value="path.path"
                            ></el-option>
                        </el-select>
                    </div>
                    <div class="col-md-3 m-2">
                        <label for="">Acceso directo 4</label>
                        <el-select @change="submit" v-model="form[4]">
                            <el-option
                                v-for="(path, idx) in paths"
                                :key="idx"
                                :label="path.name"
                                :value="path.path"
                            ></el-option>
                        </el-select>
                    </div>
                    <div class="col-md-3 m-2">
                        <label for="">Acceso directo 5</label>
                        <el-select @change="submit" v-model="form[5]">
                            <el-option
                                v-for="(path, idx) in paths"
                                :key="idx"
                                :label="path.name"
                                :value="path.path"
                            ></el-option>
                        </el-select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapActions, mapState } from "vuex";

export default {
    props: ["configuration", "typeUser"],
    data() {
        return {
            paths: [
                { path: "/purchases/create", name: "Crear compra" },
                { path: "/list-settings", name: "Configuracion" },
                { path: "/quotations", name: "Cotizaciones" },
                { path: "/reports/sales", name: "Reporte documentos" },
                { name: "Crear documentos", path: "/documents/create" },
                { name: "POS", path: "/pos" },
                { name: "Nota de ventas", path: "/sale-notes" },
                { name: "Clientes", path: "/persons/customers" },
                { name: "Productos", path: "/items" }
            ],
            loading_submit: false,
            resource: "configurations",
            errors: {},
            form: {},
            activeName: "first",
            shortcuts: {}
        };
    },

    computed: {
        ...mapState(["config"])
    },

    created() {
        this.$store.commit("setConfiguration", this.configuration);
        this.loadConfiguration();

        let { shortcuts } = this.configuration;
        this.shortcuts = shortcuts;
        this.form = shortcuts || {};
    },

    methods: {
        ...mapActions(["loadConfiguration"]),

        async submit() {
            try {
                this.loading_submit = true;
                const response = await this.$http.post(
                    "/configurations/shortcuts",
                    {
                        shortcuts: this.form
                    }
                );
                if (response.status == 200) {
                    window.location.reload();
                }
            } catch (e) {
                console.log(e);
                this.$message({
                    type: "error",
                    message: "Error al actualizar los accesos directos"
                });
            } finally {
                this.loading_submit = false;
            }
        }
    }
};
</script>

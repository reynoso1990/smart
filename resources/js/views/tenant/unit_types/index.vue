<template>
    <div class="card">
        <div class="card-header">
            <h3 class="my-0">
                {{ pdf ? "Listado de unidades" : "Mostrar unidades en PDF" }}
            </h3>
        </div>
        <div class="card-body">
            <div class="row" v-if="!pdf">
                <div class="col">
                    <button
                        type="button"
                        class="btn btn-custom btn-sm  mt-2 mr-2"
                        @click.prevent="clickCreate()"
                    >
                        <i class="fa fa-plus-circle"></i> Nuevo
                    </button>
                </div>
            </div>
            <div v-loading="loading" class="table-responsive">
                <table class="table">
                    <thead>
                        <tr v-if="!pdf">
                            <th>#</th>
                            <th>Código</th>
                            <th>Descripción</th>
                            <th>Símbolo</th>
                            <th class="text-center">Activo</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                        <tr v-else>
                            <th>#</th>
                            <th>Descripción</th>
                            <th>Código</th>
                            <th>
                                Mostrar en PDF
                            </th>
                            <th>Símbolo</th>
                            <th class="text-center">Activo</th>
                        </tr>
                    </thead>
                    <tbody v-if="!pdf">
                        <tr v-for="(row, index) in records" :key="index">
                            <td>{{ index + 1 }}</td>
                            <td>{{ row.id }}</td>
                            <td>{{ row.description }}</td>
                            <td>{{ row.symbol }}</td>
                            <td class="text-center">{{ row.active }}</td>
                            <td class="text-end">
                                <button
                                    type="button"
                                    class="btn waves-effect waves-light btn-sm btn-info"
                                    @click.prevent="clickCreate(row.id)"
                                >
                                    Editar
                                </button>

                                <template v-if="typeUser === 'admin'">
                                    <button
                                        type="button"
                                        class="btn waves-effect waves-light btn-sm btn-danger"
                                        @click.prevent="clickDelete(row.id)"
                                    >
                                        Eliminar
                                    </button>
                                </template>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr v-for="(row, index) in records" :key="index">
                            <td>{{ index + 1 }}</td>
                            <td>{{ row.description }}</td>
                            <td>{{ row.id }}</td>
                            <td>
                                <el-switch
                                    :disabled="!row.symbol"
                                    v-model="row.show_symbol"
                                    active-text="Simbolo"
                                    inactive-text="Código"
                                    @change="submit(row.id)"
                                ></el-switch>
                            </td>
                            <td>{{ row.symbol }}</td>
                            <td class="text-center">{{ row.active }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- <div class="row">
                <div class="col">
                    <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>
                </div>
            </div> -->
        </div>
        <unit-types-form
            :showDialog.sync="showDialog"
            :recordId="recordId"
        ></unit-types-form>
    </div>
</template>

<script>
import UnitTypesForm from "./form.vue";
import { deletable } from "../../../mixins/deletable";

export default {
    mixins: [deletable],
    props: ["typeUser", "pdf"],
    components: { UnitTypesForm },
    data() {
        return {
            showDialog: false,
            resource: "unit_types",
            recordId: null,
            records: [],
            loading: false
        };
    },
    created() {
        this.$eventHub.$on("reloadData", () => {
            this.getData();
        });
        this.getData();
    },
    methods: {
        getData() {
            this.$http.get(`/${this.resource}/records`).then(response => {
                this.records = response.data.data;
            });
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
        async submit(id) {
            try {
                this.loading = true;
                const response = await this.$http.get(
                    `/${this.resource}/show_symbol/${id}`
                );
                if (response.status == 200) {
                    this.getData();
                    this.$message.success("Actualizado correctamente");
                }
            } catch (e) {
                console.log(e);
                this.$message.error("Error al actualizar");
            } finally {
                this.loading = false;
            }
        }
    }
};
</script>

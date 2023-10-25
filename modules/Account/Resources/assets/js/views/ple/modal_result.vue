<template>
    <el-dialog
        :visible="showDialog"
        @open="open"
        @close="close"
        v-loading="loading"
        width="400px"
        :title="title"
    >
        <div
            class=" d-flex flex-column justify-content-center align-items-center"
            v-if="data.content && data.content.length > 15"
        >
            <div class="row">
                <div class="table">
                    <tbody>
                        <tr v-if="data.ft > 0">
                            <td class="font-weight-bold">
                                FACTURAS
                            </td>
                            <td style="text-align:right">{{ data.ft }}</td>
                        </tr>
                        <tr v-if="data.bv > 0">
                            <td class="font-weight-bold">BOLETAS</td>
                            <td style="text-align:right">{{ data.bv }}</td>
                        </tr>
                        <tr v-if="data.nc > 0">
                            <td class="font-weight-bold">N. CRÉDITO</td>
                            <td style="text-align:right">{{ data.nc }}</td>
                        </tr>
                        <tr v-if="data.nd > 0">
                            <td class="font-weight-bold">N. DÉBITO</td>
                            <td style="text-align:right">{{ data.nd }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">TOTAL</td>
                            <td style="text-align:right">
                                {{ data.total.toFixed(2) }}
                            </td>
                        </tr>
                    </tbody>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <button
                        @click="download"
                        type="button"
                        class="btn btn-primary"
                    >
                        {{ data.name }}
                    </button>
                </div>
            </div>
        </div>
        <div class="row" v-else>
            <div class="col-12">
                MES SIN MOVIMIENTO
            </div>
        </div>
    </el-dialog>
</template>

<script>
import queryString from "query-string";

export default {
    props: ["showDialog", "form"],
    data() {
        return {
            resource: "/account/ple",
            loading: false,
            data: {},
            title: "Libro electrónico"
        };
    },
    methods: {
        download() {
            const blob = new Blob([this.data.content], {
                type: "text/plain;charset=utf-8"
            });
            const link = document.createElement("a");
            link.download = this.data.name;
            link.href = URL.createObjectURL(blob);
            link.click();

            this.close();
        },
        async open() {
            this.title =
                this.form.type == "sale"
                    ? "Libro de venta 14.1"
                    : "Libro de compra 8.1";
            let query = queryString.stringify({
                ...this.form
            });
            try {
                this.loading = true;
                const response = await this.$http(
                    `${this.resource}/generate?${query}`
                );
                this.data = response.data;

                console.log(response);
            } catch (e) {
            } finally {
                this.loading = false;
            }
        },
        close() {
            this.data = {};
            this.$emit("update:showDialog", false);
        }
    }
};
</script>

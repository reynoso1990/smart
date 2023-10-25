<template>
    <div class="card">
        <div class="card-header">
            <h3 class="my-0">
                Denominaciones opcionales suscripción
            </h3>
        </div>
        <div class="row card-body" v-loading="loading">
            <div class="col-md-3">
                <label for="sale_note">Padres</label>
                <el-input v-model="form.parents"></el-input>
            </div>
            <div class="col-md-3">
                <label for="quotation">Hijos</label>
                <el-input v-model="form.children"></el-input>
            </div>
            <div class="col-md-3">
                <label for="order_sale">Grados</label>
                <el-input v-model="form.grades"></el-input>
            </div>
            <div class="col-md-3">
                <label for="sale_opportunity">Secciones</label>
                <el-input v-model="form.sections"></el-input>
            </div>
        </div>
        <span slot="footer" class="dialog-footer d-flex justify-content-end">
            <el-button @click="submit" type="primary">Guardar</el-button>
        </span>
    </div>
</template>

<script>
export default {
    data() {
        return {
            loading: false,
            form: {},
            pdf: false,
            typeUser: ""
        };
    },
    created() {
        this.getRecord();
    },
    methods: {
        formatValues() {
            for (const key in this.form) {
                if (typeof this.form[key] === "string") {
                    this.form[key] =
                        this.form[key].charAt(0).toUpperCase() +
                        this.form[key].slice(1);
                }
            }
        },
        async submit() {
            try {
                this.loading = true;
                this.formatValues();
                const response = await this.$http.post(
                    `/suscription/client/suscription_name`,
                    this.form
                );
                if (response.status == 200) {
                    this.$message.success(response.data.message);

                    window.location.reload();
                }
            } catch (e) {
                console.log(e);
            } finally {
                this.loading = false;
            }
        },
        async getRecord() {
            try {
                this.loading = true;
                const response = await this.$http.get(
                    `/suscription/client/suscription_name/names`
                );
                console.log(response);
                if (response.status == 200) {
                    if (response.data.data) {
                        this.form = response.data.data;
                    } else {
                        this.form = { id: 1 };
                    }
                }
            } catch (e) {
                console.log(e);
            } finally {
                this.loading = false;
            }
        }
    },
    mounted() {}
};
</script>

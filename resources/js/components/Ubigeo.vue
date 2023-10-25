<template>
    <el-dialog
        @open="open"
        @close="close"
        :visible="showDialog"
        append-to-body
        title="Nuevo ubigeo"
        width="450px"
    >
        <div class="col-12">
            <label for="department">Departamento</label>
            <el-select
                filterable
                v-model="form.department"
                placeholder="Departamento"
            >
                <el-option
                    v-for="(department, idx) in departments"
                    :key="idx"
                    :label="department.description"
                    :value="department.id"
                ></el-option>
            </el-select>
        </div>
        <div class="col-12">
            <label for="province">Provincia</label>
            <el-select
                :disabled="!form.department"
                filterable
                v-model="form.province"
                placeholder="Provincia"
            >
                <el-option
                    v-for="(province, idx) in filteredProvinces"
                    :key="idx"
                    :label="province.description"
                    :value="province.id"
                ></el-option>
            </el-select>
        </div>
        <div class="col-12">
            <label for="district">Nombre de distrito</label>
            <el-input
                :disabled="!form.province"
                v-model="form.district"
                placeholder="Nombre de provincia"
            ></el-input>
        </div>
        <div class="col-12">
            <label for="district">Código de ubigeo</label>
            <el-input :disabled="!form.province" v-model="form.code" maxlength="2">
                <template slot="prepend">{{ form.province || "-" }}</template>
            </el-input>
        </div>
        <div class="col-12 mt-2 d-flex justify-content-center">
            <el-button type="primary" @click="submit">Guardar</el-button>
            <el-button @click="close">Cancelar</el-button>
        </div>
    </el-dialog>
</template>

<script>
export default {
    props: ["showDialog", "departments", "provinces", "districts"],
    data() {
        return {
            form: {},
            provincesFiltered: []
        };
    },
    computed: {
        filteredProvinces() {
            if (!this.form.department) return this.provinces;
            return this.provinces.filter(province => {
                return province.department_id === this.form.department;
            });
        }
    },
    methods: {
        initForm() {
            this.form = {
                department: null,
                province: null,
                district: null,
                code: null
            };
        },
        formatId(inputString) {
            const resultArray = [];

            for (let i = 2; i <= inputString.length; i += 2) {
                const substring = inputString.substring(0, i);
                resultArray.push(substring);
            }
            return resultArray;
        },
        validate(){
            let {district,code} = this.form;
            return district && code && code.length === 2 && !isNaN(code);

        },
        async submit() {
            if(!this.validate()){
                this.$message.error("Nombre de distrito y código (númerico) son requeridos");
                return;
            }
            let id = `${this.form.province}${this.form.code}`;
            let province_id = this.form.province;
            let description = this.form.district;
            let payload = {
                id,
                province_id,
                description
            };

            const response = await this.$http.post("/ubigeo", payload);
            if (response.status === 200) {
                let { success, message, data, locations } = response.data;
                if (success) {

                    id = this.formatId(id);

                    this.$emit("update:showDialog", false);
                    this.$emit("locations", { locations, id });
                    this.$message.success(message);
                } else {
                    this.$message.error(message);
                }
            }
        },
        open() {
            this.initForm();
        },
        close() {
            this.$emit("update:showDialog", false);
        }
    }
};
</script>

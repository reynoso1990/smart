<template>
    <el-dialog
        title="Periodo"
        @close="close"
        :visible="showDialog"
        append-to-body
        @open="open"
        v-loading="loading"
    >
        <div class="row">
            <div class=" col-12">
                <label class="control-label font-weight-bold text-info">
                    Hijo/a
                </label>
                <el-select
                    clearable
                    v-model="form.child_id"
                    :loading="loading_search"
                    :remote-method="searchRemoteChildren"
                    filterable
                    placeholder="Escriba el nombre o número de documento del hijo/a"
                    popper-class="el-select-customers"
                    remote
                >
                    <el-option
                        v-for="(option, idx) in children"
                        :key="idx"
                        :label="option.description"
                        :value="option.id"
                    ></el-option>
                </el-select>
            </div>
            <div class="col-md-6 col-lg-6 col-12">
                <div class="d-flex flex-column">
                    <div class="block">
                        <span class="demonstration"
                            >Año {{ yearCollegeName }}</span
                        >
                        <el-slider
                            :format-tooltip="formatTooltip"
                            v-model="collegeYear"
                            :step="20"
                            show-stops
                            @change="setMonths"
                        >
                        </el-slider>
                    </div>
                    <div class="d-flex flex-wrap justify-content-center">
                        <el-checkbox-group v-model="monthCollege" size="mini">
                            <el-checkbox-button
                                @change="setMonth(m)"
                                v-for="(m, idx) in monthsCollege"
                                :key="idx"
                                :label="m.label"
                                >{{ m.label }}</el-checkbox-button
                            >
                        </el-checkbox-group>
                    </div>
                </div>
            </div>
            <div
                class="col-md-6 col-lg-6 col-12"
                v-if="monthsSelected.length != 0"
            >
                <label
                    >Periodos
                    <el-tag
                        style="cursor: pointer;
                                        margin-left: 10px;
                                        "
                        @click="clearMonthsSelected"
                        type="warning"
                    >
                        Limpiar
                    </el-tag>
                </label>
                <div class="d-flex flex-wrap">
                    <el-tag
                        class="m-2"
                        v-for="(m, idx) in monthsSelected"
                        :key="idx"
                    >
                        {{ formatMonth(m) }}
                    </el-tag>
                </div>
            </div>
        </div>
        <span slot="footer" class="dialog-footer">
            <el-button @click="close">Cerrar</el-button>
            <el-button type="primary" @click="submit">Enviar</el-button>
        </span>
    </el-dialog>
</template>

<script>
export default {
    props: ["showDialog", "document"],
    data() {
        return {
            loading: false,
            collegeYear: null,
            loading_search: false,
            form: {},
            children: [],
            monthCollege: [],
            monthsSelected: [],
            monthsCollege: [
                { value: 1, label: "Ene" },
                { value: 2, label: "Feb" },
                { value: 3, label: "Mar" },
                { value: 4, label: "Abr" },
                { value: 5, label: "May" },
                { value: 6, label: "Jun" },
                { value: 7, label: "Jul" },
                { value: 8, label: "Ago" },
                { value: 9, label: "Set" },
                { value: 10, label: "Oct" },
                { value: 11, label: "Nov" },
                { value: 12, label: "Dic" }
            ],
            yearCollegeName: null
        };
    },

    methods: {
        formatRecords(records) {
            let result = records.map(record => {
                let { period } = record;
                let date = moment(period);
                let month = Number(date.format("M"));
                let year = date.format("YYYY");
                let monthName = this.monthsCollege.find(m => m.value == month)
                    .label;

                return {
                    month: monthName,
                    value: month,
                    year: year
                };
            });
            if (result.length > 0) {
                this.yearCollegeName = result[0].year;
            }
            return result;
        },
        async getRecord() {
            try {
                this.loading = true;
                const response = await this.$http.get(
                    `/suscription/periods/${this.document.document_type_id}/${
                        this.document.id
                    }`
                );

                if (response.status == 200) {
                    const { periods, document_child } = response.data;
                    if (document_child) {
                        await this.searchRemoteChildren(document_child);
                        if (this.children.length > 0) {
                            this.form.child_id = this.children[0].id;
                        }
                        this.monthsSelected = this.formatRecords(periods);
                        this.setMonths();
                    }
                }
            } catch (error) {
                console.log(error);
            } finally {
                this.loading = false;
            }
        },
        async submit() {
            try {
                this.loading = true;
                const response = await this.$http.post(`/suscription/periods`, {
                    child_id: this.form.child_id,
                    months: this.monthsSelected,
                    document_type_id: this.document.document_type_id,
                    document_id: this.document.id
                });

                if (response.status == 200) {
                    this.$message({
                        type: "success",
                        message: "Periodos registrados correctamente"
                    });
                    this.close();
                }
            } catch (error) {
                console.log(error);
            } finally {
                this.loading = false;
            }
        },
        clearMonthsSelected() {
            this.monthsSelected = [];
            this.monthCollege = [];
        },
        formatMonth(month) {
            return `${month.month}-${month.year.substring(2, 4)}`;
        },
        setMonths() {
            let months = this.monthsSelected.filter(month => {
                return month.year == this.yearCollegeName;
            });
            this.monthCollege = months.map(month => {
                return month.month;
            });
        },
        setMonth(month) {
            let monthSelected = {
                month: month.label,
                value: month.value,
                year: this.yearCollegeName
            };
            let exist = this.monthsSelected.findIndex(
                m =>
                    m.month == monthSelected.month &&
                    m.year == monthSelected.year
            );
            if (exist >= 0) {
                this.monthsSelected.splice(exist, 1);
            } else {
                this.monthsSelected.push(monthSelected);
                this.sortMonthsSelected();
            }
        },
        sortMonthsSelected() {
            this.monthsSelected.sort((a, b) => {
                if (a.year > b.year) {
                    return 1;
                }
                if (a.year < b.year) {
                    return -1;
                }
                if (a.year == b.year) {
                    if (a.value > b.value) {
                        return 1;
                    }
                    if (a.value < b.value) {
                        return -1;
                    }
                }
                return 0;
            });
        },
        formatTooltip(value) {
            let idx = value / 20;
            let currentYear = moment().format("YYYY");
            let currentYearInt = parseInt(currentYear);
            let result = currentYear;
            switch (idx) {
                case 0:
                    result = `${currentYearInt - 1}`;
                    break;
                case 1:
                    result = `${currentYearInt}`;
                    break;
                case 2:
                    result = `${currentYearInt + 1}`;
                    break;
                case 3:
                    result = `${currentYearInt + 2}`;
                    break;
                case 4:
                    result = `${currentYearInt + 3}`;
                    break;

                default:
                    result = `${currentYearInt + 4}`;
                    break;
            }
            this.yearCollegeName = result;
            return result;
        },
        async searchRemoteChildren(input) {
            if (input.length > 2) {
                this.loading_search = true;

                let parameters = {
                    column: "name",
                    users: "children",
                    isPharmacy: false,
                    value: input
                };

                const response = await this.$http.post(
                    `/suscription/client/records`,
                    { ...parameters }
                );

                const { data } = response;
                this.children = data.data;
                this.loading_search = false;
            } else {
                this.filterCustomers();
                this.input_person.number = null;
            }
        },
        initForm() {
            this.form = {
                child_id: null
            };
            this.children = [];
            this.monthsSelected = [];
            this.monthCollege = [];
            this.yearCollegeName = null;
            this.collegeYear = 0;
        },
        close() {
            this.$emit("update:showDialog", false);
        },
        open() {
            this.initForm();
            this.getRecord();
        }
    }
};
</script>

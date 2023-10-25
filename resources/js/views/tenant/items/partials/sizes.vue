<template>
    <el-dialog
        :title="titleDialog"
        width="40%"
        :visible="showDialog"
        @open="create"
        :close-on-click-modal="false"
        :close-on-press-escape="false"
        append-to-body
        :show-close="false"
    >
        <div class="form-body">
            <div class="row">Stock: {{ copyStock }}</div>
            <div class="row" v-if="stock != 0">
                <div class="col-lg-12 col-md-12">
                    <table width="100%">
                        <thead>
                            <tr width="100%">
                                <th>Talla</th>
                                <th>Stock</th>
                                <th width="15%">
                                    <a
                                        href="#"
                                        @click.prevent="clickAddSize"
                                        class="text-center font-weight-bold text-info"
                                        >[+ Agregar]</a
                                    >
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(row, index) in sizes" :key="index">
                                <td>
                                    <el-input v-model="row.size"> </el-input>
                                </td>
                                <td>
                                    <el-input v-model="row.stock"> </el-input>
                                </td>
                                <td>
                                    <button
                                        type="button"
                                        class="btn waves-effect waves-light btn-sm btn-danger"
                                        @click.prevent="clickCancel(index)"
                                    >
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row" v-else>
                <div class="col-lg-12 col-md-12">
                    <div class="alert alert-warning" role="alert">
                        No hay stock para agregar tallas
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions text-end pt-2">
            <el-button @click.prevent="clickCancelSubmit()">Cancelar</el-button>
            <el-button type="primary" @click="submit">Guardar</el-button>
        </div>
    </el-dialog>
</template>

<script>
export default {
    props: ["showDialog", "sizes", "stock", "recordId"],
    data() {
        return {
            copyStock: 0,
            titleDialog: "Tallas",
            loading: false,
            errors: {},
            form: {},
            states: ["Activo", "Inactivo", "Desactivado", "Voz", "M2m"],
        };
    },
    async created() {
        // await this.$http.get(`/pos/payment_tables`)
        //     .then(response => {
        //         this.payment_method_types = response.data.payment_method_types
        //         this.cards_brand = response.data.cards_brand
        //         this.clickAddSize()
        //     })
    },
    methods: {
        async duplicateSerie(data, index) {
            let duplicates = await _.filter(this.sizes, { series: data });
            if (duplicates.length > 1) {
                this.sizes[index].series = "";
            }
        },
        create() {
            this.copyStock = this.stock;
            if (!this.recordId) {
                if (this.sizes.length == 0) {
                    // this.addMoreLots(this.stock)
                    this.clickAddSize();
                } else {
                    // let quantity = this.stock - this.sizes.length
                    // if(quantity > 0){
                    //     this.addMoreLots(quantity)
                    // }
                    // else{
                    //     this.deleteMoreLots(quantity)
                    // }
                }
            }
        },
        addMoreLots(number) {
            // for (let i = 0; i < number; i++) {
            //     this.clickAddSize()
            // }
        },
        deleteMoreLots(number) {
            for (let i = 0; i < number; i++) {
                this.sizes.pop();
                this.$emit("addRowLot", this.sizes);
            }
        },
        async validateLots() {
            let error = 0;

            await this.sizes.forEach((element) => {
                if (element.series == null) {
                    error++;
                }
            });

            if (error > 0)
                return {
                    success: false,
                    message: "El campo serie es obligatorio",
                };

            return { success: true };
        },
        async submit() {
            let val_lots = await this.validateLots();
            if (!val_lots.success) return this.$message.error(val_lots.message);

            await this.$emit("addRowLot", this.sizes);
            await this.$emit("update:showDialog", false);
        },
        clickAddSize() {
            // if(!this.recordId){
            //     if(this.sizes.length >= this.stock)
            //         return this.$message.error('La cantidad de registros es superior al stock o cantidad');
            // }
            let stock = 0;
            if(this.sizes.length == 0){
                stock = this.copyStock;
            }
            if(this.sizes.length == 1){
                let [size] = this.sizes;
                let {stock} = size;
                let isFull = stock == this.copyStock;
                if(isFull){
                    let split = Math.round(this.copyStock / 2);
                    let rest = this.copyStock - split;
                    this.sizes[0].stock = rest;
                    this.copyStock = split;
                    stock = split;
                    console.log("🚀 ~ file: sizes.vue:168 ~ clickAddSize ~ stock:", stock)

                    
                }
            }

             this.sizes.push({
                id: null,
                item_id: null,
                size: null,
                stock,
            });

            // this.$emit('addRowLot', this.sizes);
        },

        close() {
            this.$emit("update:showDialog", false);
            this.$emit("addRowLot", this.sizes);
        },
        clickCancel(index) {
            this.sizes.splice(index, 1);
            // item.deleted = true
            this.$emit("addRowLot", this.sizes);
        },

        async clickCancelSubmit() {
            this.$emit("addRowLot", []);
            await this.$emit("update:showDialog", false);
        },
        close() {
            this.$emit("update:showDialog", false);
        },
    },
};
</script>

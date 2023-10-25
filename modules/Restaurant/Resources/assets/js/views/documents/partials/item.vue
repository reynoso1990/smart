<template>
    <el-dialog
        :title="titleDialog"
        :visible="showDialog"
        @open="create"
        @close="close"
        top="7vh"
        :close-on-click-modal="false"
    >
        <form autocomplete="off">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-7 col-lg-7 col-xl-7 col-sm-7">
                        <div
                            class="form-group"
                            id="custom-select"
                            :class="{ 'has-danger': errors.item_id }"
                        >
                            <label class="control-label">
                                Producto/Servicio
                                <a
                                    class="text-primary"
                                    v-if="typeUser != 'seller'"
                                    href="#"
                                    @click.prevent="showDialogNewItem = true"
                                    >[+ Nuevo]</a
                                >
                            </label>

                            <template
                                v-if="!search_item_by_barcode"
                                id="select-append"
                            >
                                <div
                                    class="el-input el-input-group el-input-group--append"
                                >
                                    <el-select
                                        :disabled="recordItem != null"
                                        @focus="$event.target.select()"
                                        ref="producto"
                                        v-model="form.item_id"
                                        @change="changeItem"
                                        filterable
                                        remote
                                        placeholder="Buscar......"
                                        popper-class="el-select-items"
                                        @visible-change="focusTotalItem"
                                        slot="prepend"
                                        id="select-width"
                                        :remote-method="searchRemoteItems"
                                        :loading="loading_search"
                                    >
                                        <el-option
                                            v-for="option in items"
                                            :key="option.id"
                                            :value="option.id"
                                            :label="option.full_description"
                                        ></el-option>
                                    </el-select>
                                    <el-tooltip
                                        slot="append"
                                        class="item"
                                        effect="dark"
                                        content="Ver Stock del Producto"
                                        placement="bottom"
                                        :disabled="recordItem != null"
                                    >
                                        <div class="el-input-group__append">
                                            <el-button
                                                :disabled="isEditItemNote"
                                                @click.prevent="
                                                    clickWarehouseDetail()
                                                "
                                                ><i class="fa fa-search"></i
                                            ></el-button>
                                        </div>
                                    </el-tooltip>
                                </div>
                            </template>
                            <template v-else>
                                <el-input
                                    placeholder="Buscar productos por Codigo"
                                    v-model="input_item"
                                    @input="searchItemsBarcode"
                                    class="m-bottom"
                                    ref="ref_search_items"
                                >
                                    <el-tooltip
                                        slot="append"
                                        class="item"
                                        effect="dark"
                                        content="Ver Stock del Producto"
                                        placement="bottom"
                                        :disabled="recordItem != null"
                                    >
                                        <el-button
                                            :disabled="isEditItemNote"
                                            @click.prevent="
                                                clickWarehouseDetail()
                                            "
                                            ><i class="fa fa-search"></i
                                        ></el-button>
                                    </el-tooltip>
                                </el-input>
                            </template>
                            <small
                                class="badge text-primary w-100"
                                v-if="form.item_id != null"
                                >Ubicacion: {{ form.item.location }}<br
                            /></small>
                            <template v-if="!is_client">
                                <el-checkbox
                                    class="m-t-10"
                                    v-model="search_item_by_barcode"
                                    :disabled="recordItem != null"
                                    @change="changeSearchItemBarcode"
                                    >Buscar por código de barras</el-checkbox
                                ><br />
                            </template>
                            <!-- <el-checkbox v-model="form.has_plastic_bag_taxes" :disabled="isEditItemNote" >Impuesto a la Bolsa Plástica</el-checkbox> -->
                            <small
                                class="txt-danger"
                                v-if="errors.item_id"
                                v-text="errors.item_id[0]"
                            ></small>
                        </div>
                    </div>
                    <div class="col-md-2 col-lg-2 col-xl-2 col-sm-2">
                        <div class="form-group">
                            <label class="control-label text-left">Stock </label
                            ><br />
                            <b
                                ><h6 class="text-center" style="font-size:14px">
                                    {{ form.stock_disp }}
                                </h6></b
                            >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div
                            class="form-group"
                            :class="{
                                'has-danger': errors.affectation_igv_type_id
                            }"
                        >
                            <label class="control-label">Afectación Igv</label>
                            <el-select
                                v-model="form.affectation_igv_type_id"
                                :disabled="!change_affectation_igv_type_id"
                                filterable
                            >
                                <el-option
                                    v-for="option in affectation_igv_types"
                                    :key="option.id"
                                    :value="option.id"
                                    :label="option.description"
                                ></el-option>
                            </el-select>
                            <el-checkbox
                                :disabled="recordItem != null"
                                v-model="change_affectation_igv_type_id"
                                >Editar</el-checkbox
                            >
                            <small
                                class="txt-danger"
                                v-if="errors.affectation_igv_type_id"
                                v-text="errors.affectation_igv_type_id[0]"
                            ></small>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div
                            class="form-group"
                            :class="{ 'has-danger': errors.quantity }"
                        >
                            <label class="control-label">Cantidad</label>

                            <el-input-number
                                @focus="$event.target.select()"
                                ref="cantidad"
                                v-model="form.quantity"
                                :min="0.01"
                                :precision="4"
                                @keyup.enter.native="
                                    focusPrecio();
                                    calculateQuantity();
                                    updateprice();
                                "
                                @input="calculateQuantity()"
                            ></el-input-number>
                            <small
                                class="txt-danger"
                                v-if="errors.quantity"
                                v-text="errors.quantity[0]"
                            ></small>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div
                            class="form-group"
                            :class="{ 'has-danger': errors.unit_price_value }"
                        >
                            <label class="control-label">Precio Unitario</label>
                            <el-input
                                ref="precio"
                                v-model="form.unit_price_value"
                                @input="calculateQuantity()"
                                @focus="$event.target.select()"
                                @keyup.enter.native="
                                    clickAddItem();
                                    focusProducto();
                                "
                                :readonly="typeUser === ''"
                            >
                                <template slot="prepend">{{
                                    form.item.currency_type_symbol
                                }}</template>
                            </el-input>
                            <small
                                class="txt-danger"
                                v-if="errors.unit_price_value"
                                v-text="errors.unit_price[0]"
                            ></small>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div
                            class="form-group"
                            :class="{ 'has-danger': errors.amount }"
                        >
                            <label class="control-label">Importe Total</label>
                            <el-input
                                v-model="form.amount"
                                :readonly="typeUser === ''"
                            >
                                <template slot="prepend">{{
                                    form.item.currency_type_symbol
                                }}</template>
                            </el-input>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3" v-if="form.item_id != null">
                        <div class="form-group">
                            <label class="control-label">Descontar stock</label
                            ><br />
                            <el-radio-group
                                v-model="form.stock"
                                size="mini"
                                @change="stock()"
                            >
                                <el-radio-button label="Si"></el-radio-button>
                                <el-radio-button label="No"></el-radio-button>
                            </el-radio-group>
                        </div>
                    </div>

                    <!--<div class="col-md-3 col-sm-3" v-if="form.item.lots_enabled && form.lots_group.length > 0">
                        <div class="form-group" >
                             <label class="control-label">
                                Seleccione el lote
                            </label>
                            <el-button style="margin-top:2%;" type="primary" icon="el-icon-edit-outline"  @click.prevent="clickLotGroup"></el-button>
                        </div>
                    </div>-->

                    <div
                        style="padding-top: 1%;"
                        class="col-md-2 col-sm-2"
                        v-if="
                            form.item_id &&
                                form.item.lots_enabled &&
                                form.lots_group.length > 0
                        "
                    >
                        <a
                            href="#"
                            class="text-center font-weight-bold text-info"
                            @click.prevent="clickLotGroup"
                            >[&#10004; Seleccionar lote]</a
                        >
                    </div>

                    <div
                        style="padding-top: 1%;"
                        class="col-md-3 col-sm-3"
                        v-if="form.item_id && form.item.series_enabled"
                    >
                        <!-- <el-button type="primary" native-type="submit" icon="el-icon-check">Elegir serie</el-button> -->
                        <a
                            href="#"
                            class="text-center font-weight-bold text-info"
                            @click.prevent="clickSelectLots"
                            >[&#10004; Seleccionar series]</a
                        >
                    </div>

                    <div
                        v-if="configuration.edit_name_product"
                        class="col-md-12 col-sm-12"
                    >
                        <div class="form-group">
                            <label class="control-label"
                                >Nombre producto en PDF</label
                            >
                            <!-- <el-input type="textarea" v-model="form.name_product_pdf"> <i slot="prefix" class="el-icon-edit-outline"></i></el-input> -->
                            <vue-ckeditor
                                v-model="form.name_product_pdf"
                                :editors="editors"
                                type="classic"
                            >
                            </vue-ckeditor>
                        </div>
                    </div>
                    <template v-if="!is_client">
                        <div
                            class="col-md-12"
                            v-if="form.item_unit_types.length > 0"
                        >
                            <div style="margin:3px" class="table-responsive">
                                <h6 class="separator-title">
                                    Lista de Precios
                                    <el-tooltip
                                        class="item"
                                        effect="dark"
                                        content="Aplica para realizar compra/venta en presentacion de diferentes precios y/o cantidades"
                                        placement="top"
                                    >
                                        <i class="fa fa-info-circle"></i>
                                    </el-tooltip>
                                </h6>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Unidad</th>
                                            <th class="text-center">
                                                Descripción
                                            </th>
                                            <th class="text-center">Factor</th>
                                            <th class="text-center">
                                                Precio 1
                                            </th>
                                            <th class="text-center">
                                                Precio 2
                                            </th>
                                            <th class="text-center">
                                                Precio 3
                                            </th>
                                            <th class="text-center">
                                                Precio Default
                                            </th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(row,
                                            index) in form.item_unit_types"
                                        >
                                            <td class="text-center">
                                                {{ row.unit_type_id }}
                                            </td>
                                            <td class="text-center">
                                                {{ row.description }}
                                            </td>
                                            <td class="text-center">
                                                {{ row.quantity_unit }}
                                            </td>
                                            <td class="text-center">
                                                {{ row.price1 }}
                                            </td>
                                            <td class="text-center">
                                                {{ row.price2 }}
                                            </td>
                                            <td class="text-center">
                                                {{ row.price3 }}
                                            </td>
                                            <td class="text-center">
                                                Precio {{ row.price_default }}
                                            </td>
                                            <td
                                                class="series-table-actions text-end"
                                            >
                                                <button
                                                    type="button"
                                                    class="btn waves-effect waves-light btn-xs btn-success"
                                                    @click.prevent="
                                                        selectedPrice(row)
                                                    "
                                                >
                                                    <i
                                                        class="el-icon-check"
                                                    ></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-12 mt-2">
                            <el-collapse v-model="activePanel">
                                <!-- <el-collapse-item :disabled="recordItem != null" title="Información adicional atributos UBL 2.1" name="1"> -->
                                <el-collapse-item
                                    :disabled="recordItem != null"
                                    title="+ Agregar Descuentos/Cargos/Atributos especiales"
                                    name="1"
                                >
                                    <!--<div>-->
                                    <!--<div class="row">-->
                                    <div v-if="discount_types.length > 0">
                                        <label class="control-label">
                                            Descuentos
                                            <a
                                                href="#"
                                                @click.prevent="
                                                    clickAddDiscount
                                                "
                                                >[+ Agregar]</a
                                            >
                                        </label>
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Tipo</th>
                                                    <th>Descripción</th>
                                                    <th>Porcentaje</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    v-for="(row,
                                                    index) in form.discounts"
                                                >
                                                    <td>
                                                        <el-select
                                                            v-model="
                                                                row.discount_type_id
                                                            "
                                                            @change="
                                                                changeDiscountType(
                                                                    index
                                                                )
                                                            "
                                                        >
                                                            <el-option
                                                                v-for="option in discount_types"
                                                                :key="option.id"
                                                                :value="
                                                                    option.id
                                                                "
                                                                :label="
                                                                    option.description
                                                                "
                                                            ></el-option>
                                                        </el-select>
                                                    </td>
                                                    <td>
                                                        <el-input
                                                            v-model="
                                                                row.description
                                                            "
                                                        >
                                                            <i
                                                                slot="prefix"
                                                                class="el-icon-edit-outline"
                                                            ></i
                                                        ></el-input>
                                                    </td>
                                                    <td>
                                                        <el-checkbox
                                                            v-model="
                                                                row.is_amount
                                                            "
                                                            >Ingresar monto
                                                            fijo</el-checkbox
                                                        ><br />
                                                        <el-input
                                                            v-model="
                                                                row.percentage
                                                            "
                                                        >
                                                            <i
                                                                slot="prefix"
                                                                class="el-icon-edit-outline"
                                                            ></i
                                                        ></el-input>
                                                    </td>
                                                    <td>
                                                        <button
                                                            type="button"
                                                            class="btn btn-danger"
                                                            @click.prevent="
                                                                clickRemoveDiscount(
                                                                    index
                                                                )
                                                            "
                                                        >
                                                            x
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div v-if="charge_types.length > 0">
                                        <label class="control-label">
                                            Cargos
                                            <a
                                                href="#"
                                                @click.prevent="clickAddCharge"
                                                >[+ Agregar]</a
                                            >
                                        </label>
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Tipo</th>
                                                    <th>Descripción</th>
                                                    <th>Porcentaje</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    v-for="(row,
                                                    index) in form.charges"
                                                >
                                                    <td>
                                                        <el-select
                                                            v-model="
                                                                row.charge_type_id
                                                            "
                                                            @change="
                                                                changeChargeType(
                                                                    index
                                                                )
                                                            "
                                                        >
                                                            <el-option
                                                                v-for="option in charge_types"
                                                                :key="option.id"
                                                                :value="
                                                                    option.id
                                                                "
                                                                :label="
                                                                    option.description
                                                                "
                                                            ></el-option>
                                                        </el-select>
                                                    </td>
                                                    <td>
                                                        <el-input
                                                            v-model="
                                                                row.description
                                                            "
                                                        >
                                                            <i
                                                                slot="prefix"
                                                                class="el-icon-edit-outline"
                                                            ></i
                                                        ></el-input>
                                                    </td>
                                                    <td>
                                                        <el-input
                                                            v-model="
                                                                row.percentage
                                                            "
                                                        >
                                                            <i
                                                                slot="prefix"
                                                                class="el-icon-edit-outline"
                                                            ></i
                                                        ></el-input>
                                                    </td>
                                                    <td>
                                                        <button
                                                            type="button"
                                                            class="btn btn-danger"
                                                            @click.prevent="
                                                                clickRemoveCharge(
                                                                    index
                                                                )
                                                            "
                                                        >
                                                            x
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div v-if="attribute_types.length > 0">
                                        <label class="control-label">
                                            Atributos
                                            <a
                                                href="#"
                                                @click.prevent="
                                                    clickAddAttribute
                                                "
                                                >[+ Agregar]</a
                                            >
                                        </label>
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Tipo</th>
                                                    <th>Descripción</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    v-for="(row,
                                                    index) in form.attributes"
                                                >
                                                    <td>
                                                        <el-select
                                                            v-model="
                                                                row.attribute_type_id
                                                            "
                                                            filterable
                                                            @change="
                                                                changeAttributeType(
                                                                    index
                                                                )
                                                            "
                                                        >
                                                            <el-option
                                                                v-for="option in attribute_types"
                                                                :key="option.id"
                                                                :value="
                                                                    option.id
                                                                "
                                                                :label="
                                                                    option.description
                                                                "
                                                            ></el-option>
                                                        </el-select>
                                                    </td>
                                                    <td>
                                                        <el-input
                                                            v-model="row.value"
                                                            @input="
                                                                inputAttribute(
                                                                    index
                                                                )
                                                            "
                                                        >
                                                            <i
                                                                slot="prefix"
                                                                class="el-icon-edit-outline"
                                                            ></i
                                                        ></el-input>
                                                    </td>
                                                    <td>
                                                        <button
                                                            type="button"
                                                            class="btn btn-danger"
                                                            @click.prevent="
                                                                clickRemoveAttribute(
                                                                    index
                                                                )
                                                            "
                                                        >
                                                            x
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!--</div>-->
                                </el-collapse-item>
                            </el-collapse>
                        </div>
                    </template>
                </div>
            </div>
            <div class="form-actions text-end pt-2 pb-2">
                <el-button @click.prevent="close()">Cerrar</el-button>
                <el-button
                    class="add"
                    type="primary"
                    @click="clickAddItem()"
                    v-if="agregar_item"
                    >{{ titleAction }}</el-button
                >
            </div>
        </form>
        <item-form
            :showDialog.sync="showDialogNewItem"
            :external="true"
            @add="addNewItem"
        ></item-form>

        <warehouses-detail
            :showDialog.sync="showWarehousesDetail"
            :isUpdateWarehouseId="isUpdateWarehouseId"
            :warehouses="warehousesDetail"
        >
        </warehouses-detail>

        <lots-group
            :quantity="form.quantity"
            :showDialog.sync="showDialogLots"
            :lots_group="form.lots_group"
            @addRowLotGroup="addRowLotGroup"
        >
        </lots-group>

        <select-lots-form
            :showDialog.sync="showDialogSelectLots"
            :lots="lots"
            @addRowSelectLot="addRowSelectLot"
        >
        </select-lots-form>
    </el-dialog>
</template>
<style>
.el-select-dropdown {
    max-width: 80% !important;
    margin-right: 5% !important;
}
</style>

<script>
import ItemForm from "../../../../../../../resources/js/views/items/form.vue";
import LotsGroup from "./lots_group.vue";
import { calculateRowItem } from "../../../../../../../resources/js/helpers/functions";
///helpers/functions"
//'../../../../helpers/functions'
import WarehousesDetail from "./select_warehouses.vue";
import SelectLotsForm from "./lots.vue";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import VueCkeditor from "vue-ckeditor5";

export default {
    props: [
        "recordItem",
        "showDialog",
        "operationTypeId",
        "currencyTypeIdActive",
        "percentage_igv",
        "exchangeRateSale",
        "typeUser",
        "isEditItemNote",
        "configuration",
        "restringir_stock"
    ],
    components: {
        ItemForm,
        WarehousesDetail,
        LotsGroup,
        SelectLotsForm,
        "vue-ckeditor": VueCkeditor.component
    },
    data() {
        return {
            loading_search: false,
            titleAction: "",
            is_client: false,
            titleDialog: "",
            resource: "documents",
            showDialogNewItem: false,
            has_list_prices: false,
            errors: {},
            form: {},
            input_item: "",
            all_items: [],
            items: [],
            items_select: [],
            operation_types: [],
            all_affectation_igv_types: [],
            affectation_igv_types: [],
            system_isc_types: [],
            discount_types: [],
            charge_types: [],
            attribute_types: [],
            use_price: 1,
            change_affectation_igv_type_id: false,
            activePanel: 0,
            total_item: 0,
            item_unit_types: [],
            showWarehousesDetail: false,
            warehousesDetail: [],
            showListStock: false,
            search_item_by_barcode: false,
            isUpdateWarehouseId: null,
            showDialogLots: false,
            showDialogSelectLots: false,
            lots: [],

            item_select: null,
            agregar_item: false,
            form_control: {},
            editors: {
                classic: ClassicEditor
            }
            //item_unit_type: {}
        };
    },
    async created() {
        this.initForm();
        this.$http.get(`/${this.resource}/item/tables`).then(response => {
            this.all_items = response.data.items;
            this.operation_types = response.data.operation_types;
            this.all_affectation_igv_types =
                response.data.affectation_igv_types;
            this.affectation_igv_types = response.data.affectation_igv_types;
            this.system_isc_types = response.data.system_isc_types;
            this.discount_types = response.data.discount_types;
            this.charge_types = response.data.charge_types;
            this.attribute_types = response.data.attribute_types;
            this.is_client = response.data.is_client;
            //  this.restringir_stock= response.data.restringir_stock;
            this.filterItems();
        });

        this.$eventHub.$on("reloadDataItems", item_id => {
            this.reloadDataItems(item_id);
        });

        this.$eventHub.$on("selectWarehouseId", warehouse_id => {
            // console.log(warehouse_id)
            this.form.warehouse_id = warehouse_id;
        });
    },
    methods: {
        async searchRemoteItems(input) {
            if (input.length > 2) {
                this.loading_search = true;
                let parameters = `input=${input}`;
                await this.$http
                    .get(`/${this.resource}/search-items/?${parameters}`)
                    .then(response => {
                        this.items_select = response.data.items[0];

                        this.items = response.data.items;
                        this.loading_search = false;

                        this.enabledSearchItemsBarcode();

                        if (this.items.length == 0) {
                            this.filterItems();
                        }
                    });
            } else {
                await this.filterItems();
            }
        },
        keyupTabCustomer(e) {
            // console.log(e.keyCode)
            if (e.keyCode === 9) {
                this.$refs.select_person.$el
                    .getElementsByTagName("input")[0]
                    .focus();
            }
        },

        focusProducto() {
            this.calculateQuantity();
            this.$nextTick(function() {
                this.$refs.ref_search_items.$el.querySelector("input").focus();
            });
            this.input_item = "";
        },
        addNewItem(data) {
            this.form.item_id = data.id;
            this.items.push(data);
            this.searchRemoteItems(data.description);
            this.form.item = _.find(this.items, { id: data.id });
            let precios = _.filter(this.items, { id: this.form.item_id })[0];
            this.form.unit_price_value = this.form.item.sale_unit_price;
            ///////////////////////////////////////////////////////////////////////////
            this.lots = this.form.item.lots;
            this.form.stock_disp = this.form.item.stock;
            this.form.has_igv = this.form.item.has_igv;
            this.form.affectation_igv_type_id = this.form.item.sale_affectation_igv_type_id;
            this.form.quantity = 1;
            ///////////////////////////////////////////////////////////////////////////
            this.cleanTotalItem();
            this.showListStock = true;

            this.calculateQuantity();
            //this.changeItem()
        },
        focusPrecio() {
            //   this.$refs.precio.$el.getElementsByTagName('input')[0].focus()
            //  this.$refs.precio.$el.querySelector('input').focus();
            this.calculateQuantity();
            this.$nextTick(function() {
                this.$refs.precio.$el.querySelector("input").focus();
            });
        },
        stock() {
            if (this.form.stock == "Si") {
                this.form_control.stock_control = true;
            } else {
                this.form_control.stock_control = false;
            }
            this.$http
                .post("/inventories/configuration", this.form_control)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    } else {
                        console.log(error);
                    }
                })
                .then(() => {
                    //this.loading_submit = false;
                });
        },
        filterItems() {
            this.items = this.all_items;
        },
        enabledSearchItemsBarcode() {
            if (this.search_item_by_barcode) {
                if (this.items.length == 1) {
                    this.form.item_id = this.items[0].id;
                    this.changeItem();
                }
            }
        },
        filterMethod(query) {
            let item = _.find(this.items, { internal_id: query });

            if (item) {
                this.form.item_id = item.id;
                this.changeItem();
            }
            // console.log(item)
        },
        clickWarehouseDetail() {
            if (!this.form.item_id) {
                return this.$message.error("Seleccione un item");
            }
            let item = _.find(this.items, { id: this.form.item_id });
            this.warehousesDetail = item.warehouses;
            this.showWarehousesDetail = true;
        },
        filterItems() {
            this.items = this.all_items;
        },
        async searchItemsBarcode() {
            if (this.input_item.length > 1) {
                this.loading_search = true;
                let parameters = `input=${this.input_item}`;
                await this.$http
                    .get(`/${this.resource}/search-items?${parameters}`)
                    .then(response => {
                        this.items_select = response.data.items[0];
                        console.log(
                            "response.data.items[0]",
                            response.data.items[0]
                        );
                        this.items = response.data.items;
                        this.form.item_id = response.data.items[0].id;
                        this.loading_search = false;
                        this.input_item =
                            response.data.items[0].full_description;
                        this.focusTotalItem();
                        this.changeItem();
                        //this.enabledSearchItemsBarcode()
                        if (this.items.length == 0) {
                            this.filterItems();
                        }
                    });
            } else {
                await this.filterItems();
            }

            // if (this.input_item.length > 1) {
            //     this.loading = true;
            //     let parameters = `input_item=${this.input_item}`;
            //     await this.$http
            //         .get(`/${this.resource}/search_items?${parameters}`)
            //         .then(response => {
            //              this.items = response.data.items;
            //              this.enabledSearchItemsBarcode();
            //             this.loading = false;
            //             if (this.items.length == 0) {
            //                 this.filterItems();
            //             }
            //         });
            // } else {
            //     await this.filterItems();
            // }
        },
        enabledSearchItemsBarcode() {
            if (this.search_item_by_barcode) {
                // if (this.items.length == 1) {
                //     // console.log(this.items)
                //     this.clickAddItem(this.items[0], 0);
                //     this.filterItems();
                // }

                this.cleanInput();
            }
        },
        changeSearchItemBarcode() {
            this.cleanInput();
            this.$nextTick(function() {
                this.$refs.ref_search_items.$el.querySelector("input").focus();
            });
        },
        cleanInput() {
            this.input_item = null;
        },

        initForm() {
            this.errors = {};

            this.form = {
                // category_id: [1],
                // edit: false,
                item_id: null,
                item: {},
                affectation_igv_type_id: null,
                affectation_igv_type: {},
                has_isc: false,
                system_isc_type_id: null,
                percentage_isc: 0,
                suggested_price: 0,
                quantity: 1,
                unit_price_value: 0,
                input_unit_price_value: 0,
                unit_price: 0,
                charges: [],
                discounts: [],
                attributes: [],
                has_igv: null,
                item_unit_types: [],
                has_plastic_bag_taxes: false,
                warehouse_id: null,
                lots_group: [],
                IdLoteSelected: null,
                stock: "Si",
                stock_disp: null,
                name_product_pdf: null
            };

            this.activePanel = 0;
            this.total_item = 0;
            this.item_unit_type = {};
            this.has_list_prices = false;
        },
        // initializeFields() {
        //     this.form.affectation_igv_type_id = this.affectation_igv_types[0].id
        // },
        async create() {
            this.titleDialog = this.recordItem
                ? " Modificar Producto o Servicio"
                : " Agregar Producto o Servicio";
            this.titleAction = this.recordItem ? " Modificar" : "Agregar";
            let operation_type = await _.find(this.operation_types, {
                id: this.operationTypeId
            });
            //  this. =affectation_igv_types await _.filter(this.all_affectation_igv_types, {exportation: operation_type.exportation})

            if (this.recordItem) {
                this.form.item_id = await this.recordItem.item_id;
                this.form.unit_price = this.recordItem.unit_price;
                await this.searchRemoteItems(this.recordItem.item.description);
                await this.changeItem();
                this.form.quantity = this.recordItem.quantity;
                this.form.name_product_pdf = this.recordItem.name_product_pdf;
                this.form.unit_price_value = this.recordItem.unit_price;
                this.form.amount = _.round(
                    this.form.quantity * this.form.unit_price_value,
                    4
                );
                this.form.stock = this.recordItem.item.is_stock;
                this.form.has_plastic_bag_taxes =
                    this.recordItem.total_plastic_bag_taxes > 0 ? true : false;
                this.form.warehouse_id = this.recordItem.warehouse_id;
                this.isUpdateWarehouseId = this.recordItem.warehouse_id;
                this.item_select = this.recordItem;
                if (this.isEditItemNote) {
                    this.form.item.currency_type_id = this.currencyTypeIdActive;
                    this.form.item.currency_type_symbol =
                        this.currencyTypeIdActive == "PEN" ? "S/" : "$";
                }

                this.calculateQuantity();
            } else {
                this.isUpdateWarehouseId = null;
            }
        },
        clickAddDiscount() {
            this.form.discounts.push({
                discount_type_id: null,
                discount_type: null,
                description: null,
                percentage: 0,
                factor: 0,
                amount: 0,
                base: 0,
                is_amount: false
            });
        },
        clickRemoveDiscount(index) {
            this.form.discounts.splice(index, 1);
        },
        changeDiscountType(index) {
            let discount_type_id = this.form.discounts[index].discount_type_id;
            this.form.discounts[index].discount_type = _.find(
                this.discount_types,
                { id: discount_type_id }
            );
        },
        clickAddCharge() {
            this.form.charges.push({
                charge_type_id: null,
                charge_type: null,
                description: null,
                percentage: 0,
                factor: 0,
                amount: 0,
                base: 0
            });
        },
        clickRemoveCharge(index) {
            this.form.charges.splice(index, 1);
        },
        changeChargeType(index) {
            let charge_type_id = this.form.charges[index].charge_type_id;
            this.form.charges[index].charge_type = _.find(this.charge_types, {
                id: charge_type_id
            });
        },
        clickAddAttribute() {
            this.form.attributes.push({
                attribute_type_id: null,
                description: null,
                value: null,
                start_date: null,
                end_date: null,
                duration: null
            });
        },
        clickRemoveAttribute(index) {
            this.form.attributes.splice(index, 1);
        },
        changeAttributeType(index) {
            let attribute_type_id = this.form.attributes[index]
                .attribute_type_id;
            let attribute_type = _.find(this.attribute_types, {
                id: attribute_type_id
            });
            this.form.attributes[index].description =
                attribute_type.description;
            this.inputAttribute(index);
        },
        inputAttribute(index) {
            let value = this.form.attributes[index].value;
            let hotelAttributes = ["4003", "4004"];

            this.form.attributes[index].start_date = hotelAttributes.includes(
                this.form.attributes[index].attribute_type_id
            )
                ? value
                : null;
        },
        close() {
            this.initForm();
            this.$emit("update:showDialog", false);
        },
        async changeItem() {
            if (this.recordItem) {
                this.form.item = this.items_select;
            } else {
                this.form.item = _.find(this.items, { id: this.form.item_id });
            }
            let precios = _.filter(this.items, { id: this.form.item_id })[0];
            this.form.item_unit_types = precios.item_unit_types;
            this.form.unit_price_value = this.form.item.sale_unit_price;
            this.lots = this.form.item.lots;
            this.form.stock_disp = this.form.item.stock;
            this.form.has_igv = this.form.item.has_igv;
            this.form.affectation_igv_type_id = this.form.item.sale_affectation_igv_type_id;
            this.form.quantity = 1;
            this.cleanTotalItem();
            this.showListStock = true;

            if (this.form.item.attributes.length > 0) {
                const contex = this;
                this.form.item.attributes.forEach(row => {
                    contex.form.attributes.push({
                        attribute_type_id: row.attribute_type_id,
                        description: row.description,
                        value: row.value,
                        start_date: row.start_date,
                        end_date: row.end_date,
                        duration: row.duration
                    });
                });
            }

            this.form.lots_group = this.form.item.lots_group;
            if (
                this.form.item.name_product_pdf &&
                this.config.item_name_pdf_description
            ) {
                this.form.name_product_pdf = this.form.item.name_product_pdf;
            }

            this.calculateQuantity();
        },
        focusTotalItem(change) {
            //  if(!change && this.form.item.calculate_quantity) {
            this.$refs.cantidad.$el.getElementsByTagName("input")[0].focus();
            //this.total_item = this.form.unit_price_value
            // }
        },
        calculateQuantity() {
            if (this.restringir_stock === true && this.form.item_id !== null) {
                if (
                    this.form.stock_disp < this.form.quantity &&
                    this.form.stock == "Si" &&
                    this.form.item_id != null
                ) {
                    this.agregar_item = false;
                } else {
                    this.agregar_item = true;
                }
            } else {
                this.agregar_item = true;
            }

            if (this.form.item.calculate_quantity) {
                this.form.quantity = _.round(
                    this.total_item / this.form.unit_price_value,
                    4
                );
            }
            // this.form.amount=_.round(this.form.quantity*this.form.unit_price_value, 4)
            this.form.amount = _.round(
                Math.round(
                    parseFloat(this.form.quantity) *
                        parseFloat(this.form.unit_price_value) *
                        10
                ) / 10,
                4
            );
        },
        updateprice() {
            if (this.configuration.refresh_price_sales == true) {
                let form_price_sales = {
                    id: this.form.item_id,
                    sale_unit_price: this.form.unit_price_value
                };
                this.$http
                    .post(`/items/updateprice`, form_price_sales)
                    .then(response => {
                        if (response.data.success == true) {
                            this.$message.success(response.data.message);
                        } else {
                            this.$message.error(
                                "Ocurrio un error al actualizar el precio de venta"
                            );
                        }
                    });
            }
        },
        cleanTotalItem() {
            this.total_item = null;
        },
        async clickAddItem() {
            this.form.item.is_stock = this.form.stock;
            if (this.form.item.lots_enabled) {
                if (!this.form.IdLoteSelected)
                    return this.$message.error("Debe seleccionar un lote.");
            }
            if (this.validateTotalItem().total_item) return;

            let unit_price = this.form.has_igv
                ? this.form.unit_price_value
                : this.form.unit_price_value * (1 + this.percentage_igv / 100);

            this.form.input_unit_price_value = this.form.unit_price_value;

            this.form.unit_price = unit_price;
            this.form.item.unit_price = unit_price;
            this.form.item.presentation = this.item_unit_type;
            this.form.affectation_igv_type = _.find(
                this.affectation_igv_types,
                { id: this.form.affectation_igv_type_id }
            );

            let IdLoteSelected = this.form.IdLoteSelected;

            // console.log(this.form)
            // return
            // console.log
            this.row = calculateRowItem(
                this.form,
                this.currencyTypeIdActive,
                this.exchangeRateSale
            );

            let select_lots = await _.filter(this.row.item.lots, {
                has_sale: true
            });
            let un_select_lots = await _.filter(this.row.item.lots, {
                has_sale: false
            });

            if (this.form.item.series_enabled) {
                if (select_lots.length != this.form.quantity)
                    return this.$message.error(
                        "La cantidad de series seleccionadas son diferentes a la cantidad a vender"
                    );
            }

            // this.row.edit = false;
            this.initForm();
            //this.initializeFields()
            if (this.recordItem) {
                this.row.indexi = this.recordItem.indexi;
            }
            this.row.IdLoteSelected = IdLoteSelected;
            this.$emit("add", this.row);

            this.agregar_item = false;
            if (this.recordItem) {
                this.close();
            }
        },
        validateTotalItem() {
            this.errors = {};

            if (this.form.item.calculate_quantity) {
                if (this.total_item < 0.01)
                    this.$set(this.errors, "total_item", [
                        "total venta item debe ser mayor a 0.01"
                    ]);
            }

            return this.errors;
        },
        async reloadDataItems(item_id) {
            if (!item_id) {
                this.$http
                    .get(`/${this.resource}/table/items`)
                    .then(response => {
                        this.items = response.data;
                        this.form.item_id = item_id;
                        // if(item_id) this.changeItem()
                        // this.filterItems()
                    });
            } else {
                const resp = await this.$http.get(
                    `/${this.resource}/search/item/${item_id}`
                );
                if (resp.data.items.length > 0) {
                    let data = resp.data.items[0];
                    this.items = resp.data.items[0];
                    this.form.item_id = item_id;

                    // this.form.item.stock=resp.data.items[0].stock
                    // this.form.unit_price_value=resp.data.items[0].sale_unit_price
                    // this.searchRemoteItems(resp.data.items[0].description);
                    // this.form.item.stock=resp.data.items[0].stock
                    // this.$refs.cantidad.$el.getElementsByTagName('input')[0].focus()
                    // this.changeItem()
                }
                //.then((response) => {

                //  })
            }
        },
        changePresentation() {
            let price = 0;

            this.item_unit_type = _.find(this.form.item.item_unit_types, {
                id: this.form.item_unit_type_id
            });

            switch (this.item_unit_type.price_default) {
                case 1:
                    price = this.item_unit_type.price1;
                    break;
                case 2:
                    price = this.item_unit_type.price2;
                    break;
                case 3:
                    price = this.item_unit_type.price3;
                    break;
            }

            this.form.unit_price_value = price;
            this.form.item.unit_type_id = this.item_unit_type.unit_type_id;
        },
        selectedPrice(row) {
            let valor = 0;
            switch (row.price_default) {
                case 1:
                    valor = row.price1;
                    break;
                case 2:
                    valor = row.price2;
                    break;
                case 3:
                    valor = row.price3;
                    break;
            }
            this.form.item_unit_type_id = row.id;
            this.item_unit_type = row;

            this.form.unit_price_value = valor;
            this.form.item.unit_type_id = row.unit_type_id;
            this.calculateQuantity();
        },
        addRow(row) {
            // this.searchRemoteItems(row.description)
        },
        addRowLotGroup(id) {
            this.form.IdLoteSelected = id;
        },
        clickLotGroup() {
            this.showDialogLots = true;
        },
        async clickSelectLots() {
            this.showDialogSelectLots = true;
        },
        addRowSelectLot(lots) {
            this.lots = lots;
        }
    }
};
</script>

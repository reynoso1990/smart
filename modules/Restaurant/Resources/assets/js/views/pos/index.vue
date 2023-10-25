<template>
<div>
    <div class="page-title-container mb-1">
        <div class="row">
            <!-- Title Start -->
            <div class="col-12 col-md-1 p-2">
                <h3 class="mb-0 pb-0 display-4 small-title mb-2" id="title">Caja</h3>
                <!-- <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                    <ul class="breadcrumb pt-0">
                        <li class="breadcrumb-item small-title" :class="selectOption ==0 ? 'text-danger' : ''"><a href="javascript:void(0)" @click="selectOption=0;list_tables()"><i class="icofont icofont-dining-table"></i> Ver Mesas</a></li>
                        <li class="breadcrumb-item small-title" :class="selectOption ==1 ? 'text-danger' : ''"><a href="javascript:void(0)" @click="selectOption=1;"><i class="icofont-search-restaurant"></i> Buscar Orden</a></li>
                     </ul>
                </nav> -->
            </div>
            <!-- Title End -->
            <div class="col-12 col-md-4 p-2">
                <h2 class="text-muted text-small">Buscar</h2>
                  <template v-if="selectOption == 4">
                <el-input prefix-icon="el-icon-search" ref="input_items" size="small"
                         placeholder="Buscar el plato"
                        v-model="input_item"
                        @input="search()"
                        @focus="clear_input()" autofocus>
                    <template slot="prepend" class="bg-primary txt-white">{{type_search}}</template>
                    <el-button slot="append" icon="el-icon-search" @click="search"></el-button>
                </el-input>
                </template>

                <template v-else>
                <el-input prefix-icon="el-icon-search" ref="input_item" size="small"
                        v-model="input_item"
                        @keyup.enter.native="search()"
                        @focus="clear_input()" autofocus>
                    <template slot="prepend" class="bg-primary txt-white">{{type_search}}</template>
                    <el-button slot="append" icon="el-icon-search" @click="search"></el-button>

                 </el-input>
                </template>

            </div>
             <div class="col-12 col-md-2 p-2">
                <h2 class="text-muted text-small">Opciones de Busqueda</h2>
                <el-radio-group v-model="type_search" size="mini" class="flex-wrap">
                    <el-radio-button label="Descripcion"></el-radio-button>
                    <el-radio-button label="Codigo interno"></el-radio-button>
                </el-radio-group>
             </div>
            <div class="col-12 col-md-3 p-2">
                <h2 class="text-muted text-small">Cliente</h2>
                <template>
                    <div class="el-input el-input-group el-input-group--append">
                        <el-select ref="select_person" v-model="value" filterable size="small" placeholder="Cliente"
                            @change="changeCustomer"
                            @keyup.native="keyupCustomer"
                            @keyup.enter.native="keyupEnterCustomer();keyupCustomer();">
                            <el-option v-for="option in all_customers" :key="option.id" :label="option.description" :value="option.id">
                            </el-option>
                        </el-select>
                        <div class="el-input-group__append">
                            <el-button @click="showDialogNewPerson = true">
                                <i class="icofont-search-user"></i>
                            </el-button>

                        </div>
                    </div>
                </template>
            </div>

            <!-- Top Buttons Start -->
            <div class="col-12 col-md-2 d-flex align-items-center justify-content-end p-2">
                 <!-- Contact Button Start -->
                <button type="button" class="btn btn-primary w-100 text-white" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight2" aria-controls="offcanvasRight2" v-if="selectOption ==4 && localOrden.length>0">
                    <i class="icofont-money icofont-1x"></i> S/ {{total_sales_pos}}
                </button>
                <!-- Contact Button End -->
                <!-- Dropdown Button Start -->
                <div class="ms-1">
                    <button type="button" class="btn btn-outline-primary btn-icon btn-icon-only" data-bs-offset="0,3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-submenu="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="cs-icon cs-icon-more-horizontal">
                            <path d="M9 10C9 9.44772 9.44772 9 10 9V9C10.5523 9 11 9.44772 11 10V10C11 10.5523 10.5523 11 10 11V11C9.44772 11 9 10.5523 9 10V10zM2 10C2 9.44772 2.44772 9 3 9V9C3.55228 9 4 9.44772 4 10V10C4 10.5523 3.55228 11 3 11V11C2.44772 11 2 10.5523 2 10V10zM16 10C16 9.44772 16.4477 9 17 9V9C17.5523 9 18 9.44772 18 10V10C18 10.5523 17.5523 11 17 11V11C16.4477 11 16 10.5523 16 10V10z"></path>
                        </svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" style="">
                        <button class="dropdown-item" :class="selectOption ==4 ? 'text-danger' : ''" type="button" @click="selectOption=4;typesearch()"><i class="icofont-bag"></i> Venta Directa</button>
                        <button class="dropdown-item" :class="selectOption ==1 ? 'text-danger' : ''" type="button" @click="selectOption=1;disableCantidad=true;typesearch()"><i class="icofont-search-restaurant"></i> Buscar Orden Pedido</button>
                        <button class="dropdown-item" :class="selectOption ==2 ? 'text-danger' : ''" type="button" @click="selectOption=2;typesearch();consumir()"> <i class="icofont-pay"></i>Por Consumo</button>
                        <button class="dropdown-item" :class="selectOption ==3 ? 'text-danger' : ''" type="button" @click="view_modal()"><i class="icofont-bag"></i> Venta Acumulada</button>
                        <button class="dropdown-item" type="button" @click="clickPayment()" v-if="form.total"> <i class="fas fa-money-bill-wave"></i> Cobrar</button>
                    </div>
                </div>
                <!-- Dropdown Button End -->
            </div>
            <!-- Top Buttons End -->
        </div>
    </div>
    <div class="row mb-2">
        <div class="card pb-2 ">
            <div class="col-12 col-xl-12 col-xxl-12" v-loading.fullscreen="loading" element-loading-text="Espere...">
                <div class="card-body p-2">
                    <div class="row" v-if="selectOption==4 || selectOption==3">
                        <div class="col-md-12 p-1">
                                 <h2 class="small-title mb-2">Categorias</h2>
                                  <div class="btn-group flex-wrap btn-group-lg" role="group" aria-label="First group">
                                    <button
                                        type="button"
                                        class="btn btn-secondary btn-sm" style="margin-left:5px !important;margin-top:5px !important;"
                                        v-for="(row, index) in categories" :class="category_selected == row.id ? 'btn btn-success' : ''"
                                        @click="filterCategorie(row.id)" :key="index" >{{row.name}}</button>
                                 </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="card mb-2">
            <div class="col-12 col-xl-12 col-xxl-12" v-loading.fullscreen="loading" element-loading-text="Espere...">
                <div class="card-body p-2">
                    <div class="row" v-if="selectOption==4 || selectOption==3">
                        <div class="col-md-12 p-1">

                            <ListFood ref="list_foods" @insertOrden="insertOrden" :configuration="configuration" :categories="categories" :foods="allFoods" @buscarnuevo="buscarnuevo"></ListFood>
                        </div>
                    </div>

                    <!-- Customers List Start -->
                    <!--   -->
                    <div class="row" v-if="selectOption ==1 || selectOption ==2">
                        <div class="col-12 mb-1">
                            <div class="card mb-2 bg-transparent no-shadow d-none d-lg-block">
                                <div class="row g-0 sh-3">
                                    <div class="col">
                                        <div class="card-body pt-0 pb-0 h-100">
                                            <div class="row g-0 h-100 align-content-center">
                                                <div class="col-6 col-lg-2 d-flex align-items-center justify-content-start text-alternate text-medium text-muted text-small">CANTIDAD</div>
                                                <div class="col-6 col-lg-5 d-flex align-items-center justify-content-start text-alternate text-medium text-muted text-small">DESCRIPCION</div>
                                                <div class="col-6 col-lg-2 d-flex align-items-center justify-content-center text-alternate text-medium text-muted text-small">PRECIO</div>
                                                <div class="col-6 col-lg-2 d-flex align-items-center justify-content-center text-alternate text-medium text-muted text-small">IMPORTE</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="checkboxTable">
                                <div class="card mb-2 hover-border-secondary border" v-for="(row, index) in ordens" :key="index">
                                    <div class="card-body p-2">
                                        <div class="row g-0 h-100 align-content-center">

                                            <div class="col-6 col-lg-2 d-flex flex-column justify-content-start mb-2 mb-lg-0 order-lg-2">
                                                <div class="text-muted text-small d-lg-none">CANTIDAD</div>
                                                <div class="text-alternate">
                                                    <template>
                                                    <el-input-number :disabled="disableCantidad" :min="1" size="mini" v-model="row.food.item.quantity" controls-position="right" @change="calculateItem(index,row.food.item.quantity,row.food.price)">
                                                    </el-input-number>
                                                    </template>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-5 d-flex flex-column justify-content-start mb-2 mb-lg-0 order-lg-3">
                                                <div class="text-muted text-small d-lg-none">DESCRIPCION</div>

                                                <template v-if="row.food.item.name_product_pdf!=null">
                                                    {{ row.food.item.name_product_pdf }}
                                                </template>
                                                <template v-else>
                                                    {{ row.food.item.description }}
                                                </template>

                                            </div>
                                            <div class="col-6 col-lg-2 d-flex flex-column justify-content-center mb-2 mb-lg-0 order-lg-4">
                                                <div class="text-muted text-small d-lg-none">PRECIO</div>
                                                <div class="text-alternate">
                                                    <span>
                                                        <el-input v-model="row.food.price" :disabled="disableCantidad" size="mini" @input="calculateItem(index,row.food.item.quantity,row.food.price)">
                                                            <template slot="prepend">S/</template>
                                                        </el-input>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="col-6 col-lg-2 d-flex flex-column justify-content-center mb-2 mb-lg-0 order-lg-5 text-center">
                                                <div class="text-muted text-small d-lg-none mb-1">IMPORTE</div>
                                                    S/
                                                    {{ (parseFloat(row.food.price)*parseFloat(row.food.item.quantity)).toFixed(2)}}
                                            </div>
                                            <div class="col-12 col-lg-1 d-flex flex-column justify-content-center align-items-lg-end mb-2 mb-lg-0 text-end order-lg-last pr-2">
                                                <button type="button" class="btn waves-effect waves-light btn-sm btn-danger" @click="removeFood(index,row.id)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Customers List End -->
                    <!--  -->
                    <template v-if="form.total > 0">
                        <div class="row justify-content-end">
                            <div v-if="form.total_taxed > 0.0" class="col-md-12 text-right pb-2 pt-2 border-bottom">
                                SUBTOTAL {{ currency_type.symbol }} {{ form.total_taxed }}
                            </div>
                            <div v-if="form.total_exonerated > 0.0" class="col-md-12 text-right pb-2 pt-2 border-bottom">
                                EXONERADO {{ currency_type.symbol }} {{ form.total_exonerated }}
                            </div>
                            <div v-if="form.total_igv > 0.0" class="col-md-12 text-right pb-2 pt-2 border-bottom">
                                IGV {{ currency_type.symbol }} {{ form.total_igv }}
                            </div>
                            <div class="col-md-12 text-right pb-2 pt-2 border-bottom">
                                TOTAL {{ currency_type.symbol }} {{ form.total }}
                            </div>
                            <div class="col-md-12 pb-2 pt-2  d-flex align-items-start justify-content-end">
                                <button class="btn btn-default bg-primary text-white btn-block" @click="clickPayment()">
                                    <i class="fas fa-money-bill-wave"></i> Cobrar
                                    {{ currency_type.symbol }} {{ form.total }}
                                </button>
                            </div>
                        </div>
                    </template>
                    <div class="row" v-loading="loading" v-if="selectOption==0">
                        <div class="col-12 col-lg-6 col-xxl-2 mb-2" v-for="(row,index) in listar_tables" :key="index">
                            <div class="card hover-border-secondary" :class="selecttables == row.id ? 'border-secondary' : ''" @click="selectTable(row,index)" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                                <div class="h-100 row g-0 card-body align-items-center">
                                    <div class="col-auto">
                                        <div class="bg-gradient-2 sw-6 sh-6 rounded-md d-flex justify-content-center align-items-center">
                                            <i class="icofont icofont-dining-table icofont-2x text-white"></i>
                                        </div>
                                    </div>
                                    <div class="col sh-6 ps-3 d-flex flex-column justify-content-center">
                                        <div class="heading mb-0 d-flex align-items-center lh-1-25">Mesa {{row.number}}</div>
                                        <div class="row g-0">
                                            <div class="col-auto">
                                                <div class="cta-2 text-primary">
                                                    <template v-if="row.ordens.length==1">
                                                        {{ ("00" + row.ordens.length).slice(-2)}}
                                                    </template>
                                                    <template v-else>
                                                        <template v-if="row.ordens.length+1<=9">
                                                            0{{row.ordens.length+1}} Ordenes
                                                        </template>
                                                        <template v-else>
                                                            {{row.ordens.length+1}} Ordenes
                                                        </template>
                                                    </template>
                                                </div>
                                            </div>
                                            <!-- <div class="col text-success d-flex align-items-center ps-3">
                                         <span class="text-medium">
                                           Ordenes
                                        </span>
                                    </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                        <div class="offcanvas-header">
                            <h5 id="offcanvasRightLabel">
                                Mesa Nº {{tableSelect.number}} /
                                {{ ("00" + OrdenLength).slice(-2)}}
                                Ordenes
                            </h5>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            <section class="scroll-section" id="checkboxes">
                                <div class="d-flex justify-content-between">
                                    <h2 class="small-title">Ordenes de Pedido</h2>
                                    <div class="btn-group check-all-container mt-n1">
                                        <div class="btn btn-sm btn-outline-primary btn-custom-control" id="checkAllforCheckboxTable" data-target="#checkboxTable" @click="selectAllCats">
                                            <span class="form-check mb-0 pe-1">
                                                <input type="checkbox" class="form-check-input" id="checkAll" v-model="isAllSelected">
                                            </span>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle dropdown-toggle-split" data-bs-offset="0,3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                            <a class="dropdown-item" href="javascript:void(0)" @click="status_orden_id(0)">Cancelar Ordenes</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="javascript:void(0)" @click="status_orden_id(3)">Orden de Pedidos Listo</a>
                                            <a class="dropdown-item" href="javascript:void(0)" @click="facturar_orden()">Facturar</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="scroll-out">
                                    <div class="scroll-by-count os-host os-theme-dark os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-transition" data-count="4" id="checkboxTable" style="height: calc(100vh - 8rem);">
                                        <div class="os-resize-observer-host observed">
                                            <div class="os-resize-observer" style="left: 0px; right: auto;">
                                            </div>
                                        </div>
                                        <div class="os-size-auto-observer observed" style="height: calc(100% + 1px); float: left;">
                                            <div class="os-resize-observer">
                                            </div>
                                        </div>
                                        <div class="os-content-glue" style="margin: 0px -15px;">
                                        </div>
                                        <div class="os-padding">
                                            <div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll;">
                                                <div class="os-content" style="padding: 0px 15px; height: 100%; width: 100%;">

                                                    <div v-for="(data, index) in tableSelect.ordens" :key="index">
                                                        <!-- <div class="card mb-2 mt-2 bg-primary">
                                <div class="card-body p-2 h-100"> -->
                                                        <div class="row g-0 h-100 align-content-center" v-if="data.status_id==1" :class="data.status_id == 0 ? 'animate__animated animate__backOutUp animate__delay-2s' : ''">
                                                            <div class="col-12 d-flex align-items-center mb-2 mb-md-0 p-2 font-weight-bold">
                                                                ORDEN Nº {{data.id}}
                                                                <!-- </div>
                                </div>-->
                                                            </div>
                                                        </div>

                                                        <div v-for="(ordersItem,indexx) in data.orden_items" :key="indexx">
                                                            <div class="card mb-1 pt-2 pb-2 border" :class="data.status_orden_id == 3 ? 'animate__animated animate__backOutUp animate__delay-2s' : ''" v-if="ordersItem.status_orden_id==1">
                                                                <div class="card-body pt-0 pb-0 h-100">
                                                                    <div class="row g-0 h-100 align-content-center">
                                                                        <div class="col-11 col-md-1 d-flex flex-column justify-content-center mb-1 mb-md-0">
                                                                            <div class="text-muted text-small d-md-none">Cantidad</div>
                                                                            {{ordersItem.quantity}}
                                                                        </div>
                                                                        <div class="col-11 col-md-7 d-flex flex-column justify-content-center mb-1 mb-md-0">
                                                                            <div class="text-muted text-small d-md-none">Detalle</div>
                                                                            {{ordersItem.food.description}}
                                                                        </div>

                                                                        <div class="col-6 col-md-3 d-flex flex-column justify-content-center align-items-md-center mb-1 mb-md-0">
                                                                            <div class="text-muted text-small d-md-none">Precio</div>
                                                                            <div class="text-alternate">

                                                                                {{ordersItem.food.price}}

                                                                            </div>
                                                                        </div>

                                                                        <div class="col-1 col-md-1  d-flex align-items-center justify-content-center text-alternate text-medium justify-content-center">
                                                                            <div class="text-alternate">
                                                                                <input type="checkbox" class="form-check-input" :value="ordersItem.id" v-model="selectedCatIds" @click="select">
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <DrawerOrdens ref="ordenRef"
        :configuration.sync="configuration"
        :date_opencash.sync="date_opencash"
        :localOrden.sync="localOrden"
        :ordens.sync="ordensItems"
        :percentage_igv.sync="percentage_igv"
        @items_document="items_document"
        @total_sales="total_sales"
        @updateOrdens="updateOrdens"
        @paymentsOrden="paymentsOrden"
        @deletedFood="deletedFood"
        @ordenDeleted="createOrden">
    </DrawerOrdens>

    <template>
        <payment-form v-if="is_payment == true"
            :is_payment.sync="is_payment"
            :date_opencash.sync="date_opencash"
            :form="form"
            :company.sync="company"
            :idOrden="idOrden"
            :all_series.sync="all_series"
            :payments.sync="payments"
            :direct_printing="direct_printing"
            :currency-type-id-active="form.currency_type_id"
            :currency-type-active="currency_type"
            :exchange-rate-sale="form.exchange_rate_sale"
            :customer="customer"
            :auth_login="auth_login"
            :configuration="configuration"
            :desarrollador.sync="desarrollador"
            :documents_data.sync="documents_data"
            @limpiarForm="limpiarForm">
        </payment-form>
    </template>
    <person-form
        :worker="worker"
        :showDialog.sync="showDialogNewPerson"
        type="customers"
        :input_person="input_person"
        :external="true"
        :user_id.sync="form.user_id"
        :document_type_id="form.document_type_id"
        @add_customer="add_customer">
    </person-form>

    <item-form :worker="worker" :showDialog.sync="showDialogNewItem" :external="true"></item-form>

    <warehouses-detail :showDialog.sync="showWarehousesDetail" :warehouses="warehousesDetail" :unit_type="unittypeDetail">
    </warehouses-detail>
</div>
</template>

<script>
import _ from "lodash";
import DrawerOrdens from "./partials/drawer.vue";
import ListFood from "./partials/list_food.vue";
import {
    functions,
    exchangeRate,
} from "../../../../../../../resources/js/mixins/functions";
import {
    calculateRowItem
} from "../../../../../../../resources/js/helpers/functions";
// calculateRowItem,
import PaymentForm from "./partials/payment.vue";
import ItemForm from "./partials/form.vue";

import PersonForm from "../../../../../../../resources/js/views/persons/form.vue"
import WarehousesDetail from "../../../../../../../resources/js/views/items/partials/warehouses.vue";
const options = {
    text: "Loading ...",
    customClass: 'login_loading',
    spinner: 'el-icon-loading',
    lock: true,
};
export default {
    props: ["worker", "configuration", "establishments","auth_login","desarrollador","company","date_opencash"],
    components: {
        PaymentForm,
        ItemForm,
        PersonForm,
        WarehousesDetail,
        DrawerOrdens,
        ListFood
    },
    mixins: [functions, exchangeRate],

    data() {
        return {
            allSelected: false,
            selected: [],
            allFoods: [],
            listFoods: [],
            idOrden:null,
            listar_tables: [],
            ordensItems: [],
            newFood: null,
            paraLlevar: false,
            editProd: false,
             localOrden: [],
            type_search: "Descripcion",
            selectOption: null,
            tableSelect: {},
            OrdenLength: 0,
            selecttables: 0,
            categories:[],
            ordenId: null,
            name_product_pdf: null,
            ordens: [],
            listtables: [],
            value:null,
            payments: [],
            slickOptions: {
                slidesToShow: 3,
                // Any other options that can be got from plugin documentation
            },
            category_selected:0,
            history_item_id: null,
            date_last: null,
            search_item_by_barcode: false,
            warehousesDetail: [],
            unittypeDetail: [],
            input_person: {},
            showDialogHistoryPurchases: false,
            showDialogHistorySales: false,
            showDialogNewPerson: false,
            showDialogNewItem: false,
            loading: true,
            colors: ["#1cb973", "#bf7ae6", "#fc6304", "#9b4db4", "#77c1f3"],
            buscar_por: 1,
            userId: null,
            place: false,
            is_payment: false,
            // is_payment: true,//aq
            showWarehousesDetail: false,
            resource: "pos",
            recordId: null,
            input_item: "",
            items: [],
            all_items: [],
            all_series:[],
            customers: [],
            affectation_igv_types: [],
            all_customers: [],
            establishment: null,
            currency_type: {},
            form_item: {},
            customer: {},
            row: {},
            user: {},
            form: {},
            document_type_id: null,
            last_date: null,
            direct_printing: 0,
            customer_default: {},
            isAllSelected: false,
            selectedCatIds: [],
            foodItem: 0,
            disableCantidad:false,
            total_sales_pos:0,
            percentage_igv:0,
            documents_data:[]
        };
    },
     async created() {
        this.loading = true;
        await this.getTables();
        await this.initForm(this.customer_default.id)
        await this.getFoods();
        await this.filterCategorie(0,false);
        await this.changeCustomer()
        this.loading = false;
        this.$eventHub.$on("reloadDataPersons", (customer_id) => {
            this.reloadDataCustomers(customer_id);
        });
        let form_data={
                    establishment_id:this.establishment.id,
                    date: moment().format('YYYY-MM-DD'),
                }
            const response= await this.$http.post('/get_igv',form_data)
            this.percentage_igv= response.data
            console.log("this.percentage_igv",this.percentage_igv)
             qz.security.setCertificatePromise((resolve, reject) => {
                this.$http.get('/api/qz/crt/override', {
                    responseType: 'text'
                }).then(response => {
                    resolve(response.data);
                }).catch(error => {
                    reject(error.data);
                });
            });
            qz.security.setSignaturePromise((toSign) => {
                return (resolve, reject) => {
                    this.$http.post('/api/qz/signing', {
                            request: toSign
                        })
                        .then(response => {
                            resolve(response.data);
                        }).catch(error => {
                            reject(error.data);
                        });
                };
            });
            console.log("company company company",this.company)
     },

    computed: {},
    methods: {
        selectAllCats() {
            if (this.isAllSelected) {
                this.selectedCatIds = []
                this.isAllSelected = false
            } else {
                this.selectedCatIds = []
                for (let cat = 0; cat < this.tableSelect.ordens.length; cat++) {
                    for (let index = 0; index < this.tableSelect.ordens[cat].orden_items.length; index++) {
                        this.selectedCatIds.push(this.tableSelect.ordens[cat].orden_items[index].id)
                        this.foodItem = this.foodItem + 1
                    }

                }
                this.isAllSelected = true
            }
        },
        buscarnuevo(){
             this.$refs.input_items.$el.getElementsByTagName("input")[0].focus();
         },
         items_document(value){
           this.documents_data.items=value;
         },
        async add_customer(value){
            await this.$http.get(`/${this.resource}/tables`).then((response) => {
                this.all_customers = response.data.customers;
                this.value=value
                this.form.customer_id=this.value
                let customer = _.find(this.all_customers,{'id' : this.value})
                this.documents_data.datos_del_cliente_o_receptor.codigo_tipo_documento_identidad = customer.identity_document_type_id
                this.documents_data.datos_del_cliente_o_receptor.numero_documento = customer.number
                this.documents_data.datos_del_cliente_o_receptor.apellidos_y_nombres_o_razon_social = customer.name
                this.documents_data.datos_del_cliente_o_receptor.codigo_pais = "PE"
                this.documents_data.datos_del_cliente_o_receptor.ubigeo = customer.district_id
                this.documents_data.datos_del_cliente_o_receptor.direccion = customer.address != null ? customer.address  : ""
                this.documents_data.datos_del_cliente_o_receptor.correo_electronico = customer.email != null ? customer.email  : ""
                this.documents_data.datos_del_cliente_o_receptor.telefono = customer.telephone != null ? customer.telephone  :""
                console.log("this.documents_data",this.documents_data)
            });

        },
        async paymentsOrden(idOrden) {
            //this.idOrden=idOrden
            this.selectOption = 1
            let parameters = `input_item=${idOrden}`;
            const response = await this.$http.get(`search_orden_document?${parameters}`);
            if (response.status == 200) {
                if (response.data.success == true) {
                    this.ordens = response.data.ordens.orden_items;
                    for (let index = 0; index < this.ordens.length; index++) {
                        this.ordens[index].food.item.quantity = this.ordens[index].quantity
                        this.ordens[index].food.item.sale_unit_price = this.ordens[index].price
                        this.ordens[index].food.price = this.ordens[index].price
                    }
                }
            }
            this.form.orden_id=idOrden

            this.form.items = this.ordens.map((o) => o.food.item);
            this.formatItems();
            this.calculateTotal();
            this.form.enter_amount = this.form.total;
            this.form.difference = 0;
            let flag = 0;
             this.form.establishment_id = this.establishment.id;
            if (!this.form.customer_id) {
                this.is_payment = false;
                return this.$message.error("Seleccione un cliente");
            }else{
                this.is_payment = true;
            }


        },
        select(id) {
            if (this.selectedCatIds.length + 1 == this.foodItem) {
                this.isAllSelected = true
            } else {
                this.isAllSelected = false
            }
        },
        deletedFood(idx) {
            this.localOrden.splice(idx, 1);
               this.calculateTotal();
        },
        insertOrden(orden, fodd_id) {
            let index_find = _.findIndex(this.localOrden, {
                id: fodd_id
            })
            if (index_find == -1) {
                this.localOrden.push(orden);
            } else {
                this.localOrden[index_find].quantity = this.localOrden[index_find].quantity + 1
            }
            this.$refs.ordenRef.calculateTotal();
        },
        total_sales(val){
            console.log("val",val)
            this.total_sales_pos=val
        },
        async ordenCancel(id) {
            try {
                let res = await this.$confirm(
                    "Desea cancelar este pedido?",
                    "Cancelar", {
                        confirmButtonText: "Ok",
                        cancelButtonText: "Cancelar",
                        type: "warning",
                    }
                );
                if (res) {
                    const response = await this.$http.delete(`delete-orden/${id}`);
                    if (response.status == 200) {
                        const {
                            message
                        } = response.data;
                        this.$message.success(message);
                    }
                }
            } catch (e) {
                //todo

                if (e != "cancel") {
                    this.$message.error("Ocurrió un error");
                }
            }
        },

        async status_orden_id(status) {
            try {
                if (status == 0) {

                    let res = await this.$confirm(
                        "Desea cancelar los pedidos seleccionados?",
                        "Cancelar", {
                            confirmButtonText: "Aceptar",
                            cancelButtonText: "Cancelar",
                            type: "warning",
                        }
                    );
                    if (res) {

                        for (let index = 0; index < this.selectedCatIds.length; index++) {
                            const response = await this.$http.delete(`worker/delete-orden/${this.selectedCatIds[index]}`);
                            if (response.status == 200) {
                                const {
                                    message
                                } = response.data;
                                this.$message.success(message);

                            }
                        }

                        for (let cat = 0; cat < this.tableSelect.ordens.length; cat++) {
                            for (let indexxx = 0; indexxx < this.tableSelect.ordens[cat].orden_items.length; indexxx++) {
                                let idOrden = this.tableSelect.ordens[cat].orden_items[indexxx].id
                                for (let index = 0; index < this.selectedCatIds.length; index++) {
                                    if (this.selectedCatIds[index] == idOrden) {
                                        this.selectedCatIds.splice(index, 1)
                                        this.tableSelect.ordens[cat].orden_items[indexxx].status_orden_id = status
                                    }
                                }

                            }
                        }
                        const response = await this.$http.get(`/restaurant/worker/ordens-status`)
                        if (response.status == 200) {
                            let Ordens = response.data.ordens
                            for (let index = 0; index < this.tableSelect.ordens.length; index++) {
                                let Ord = _.find(Ordens, {
                                    "id": this.tableSelect.ordens[index].id
                                })
                                if (Ord == undefined) {
                                    this.tableSelect.ordens[index].status_id = 0
                                }
                            }
                        }

                    }

                    //todo

                    if (e != "cancel") {
                        this.$message.error("Ocurrió un error");
                    }

                } else {
                    if (status == 3) {
                        for (let index = 0; index < this.selectedCatIds.length; index++) {
                            const response = await this.$http.get(`/restaurant/worker/ordens-ready/` + this.selectedCatIds[index])
                            if (response.data.success == true) {
                                this.$message.success(response.data.message);
                            }
                        }
                        for (let cat = 0; cat < this.tableSelect.ordens.length; cat++) {
                            for (let indexxx = 0; indexxx < this.tableSelect.ordens[cat].orden_items.length; indexxx++) {
                                let idOrden = this.tableSelect.ordens[cat].orden_items[indexxx].id
                                for (let index = 0; index < this.selectedCatIds.length; index++) {
                                    if (this.selectedCatIds[index] == idOrden) {
                                        this.selectedCatIds.splice(index, 1)
                                        this.tableSelect.ordens[cat].orden_items[indexxx].status_orden_id = status
                                    }
                                }

                            }
                        }
                        const response = await this.$http.get(`/restaurant/worker/ordens-status`)
                        if (response.status == 200) {
                            let Ordens = response.data.ordens
                            for (let index = 0; index < this.tableSelect.ordens.length; index++) {
                                let Ord = _.find(Ordens, {
                                    "id": this.tableSelect.ordens[index].id
                                })
                                if (Ord == undefined) {
                                    this.tableSelect.ordens[index].status_id = 0
                                }
                            }
                        }

                    }
                }
            } catch (e) {
                console.log(e)
            }
        },
        reloadDataCustomers(customer_id) {
            this.$http.get(`/document/search/customer/${customer_id}`).then((response) => {
                this.all_customers = response.data.customers;
                this.form.customer_id = customer_id;
            });
        },

        updateOrdens() {
            this.createOrden();
        },
        createOrden() {
            this.ordensItems = [];
        },
        facturar_orden() {
            this.ordens = []
            for (let cat = 0; cat < this.tableSelect.ordens.length; cat++) {
                for (let indexxx = 0; indexxx < this.tableSelect.ordens[cat].orden_items.length; indexxx++) {
                    let idOrden = this.tableSelect.ordens[cat].orden_items[indexxx].id
                    for (let index = 0; index < this.selectedCatIds.length; index++) {
                        if (this.selectedCatIds[index] == idOrden) {
                            this.addNewFood(this.tableSelect.ordens[cat].orden_items[indexxx].food_id)
                        }
                    }

                }
            }
            this.selectOption = 1
        },
        selectTable(row, index) {
            this.tableSelect = []
            this.selecttables = row.id;
            this.tableSelect = this.listar_tables[index]
            this.OrdenLength = this.tableSelect.ordens.length
            if (this.tableSelect.ordens.length == 1) {
                this.OrdenLength = this.tableSelect.ordens.length + 1
            } else {
                this.OrdenLength = this.tableSelect.ordens.length
            }
            for (let cat = 0; cat < this.tableSelect.ordens.length; cat++) {
                for (let index = 0; index < this.tableSelect.ordens[cat].orden_items.length; index++) {
                    this.foodItem++
                }
            }
        },
        consumir() {
            this.ordens = [];
            this.form.total_exonerated = 0;
            this.form.total_taxed = 0;
            this.form.total_igv = 0;
            this.form.total = 0;
            this.$nextTick(() => {
                this.$refs.input_item.focus();
             })
            this.calculateTotal();
        },
        removeFood(index,id) {
            this.$http.get(`/pos/orden_items/${id}`).then((response) => {
                let item = this.ordens
                let index_find = _.findIndex(this.form.items, {
                    "id": this.ordens[index].food.id
                })
                this.ordens.splice(index, 1);
                this.form.items.splice(index_find, 1)
                this.calculateTotal();
            });


        },
        addNewFood(newFood = null) {
            let f = this.allFoods.find((ai) => ai.id == newFood);

            if (f) {
                f.llevar = true;
                f.item.quantity = 1;
                f.name_product_pdf = null;
                this.ordens = [...this.ordens, {
                    food: f
                }];
            }
            this.newFood = null;
            if (this.selectOption == 2) {
                this.ordens[0].food.item.name_product_pdf = "POR CONSUMO"
            }
            this.calculateTotal();
        },

        paLlevar() {
            this.paraLlevar = !this.paraLlevar;
        },
        next() {
            this.$refs.slick.next();
        },
        editarProd() {
            this.editProd = !this.editProd;
        },
        add_product_pdf(index) {
            this.ordens[index].food.item.name_product_pdf = this.name_product_pdf

            this.editProd = !this.editProd;
        },
        prev() {
            this.$refs.slick.prev();
        },
        async date_of_issue() {
            let form_date_of_issue = {
                document_type_id: this.form.document_type_id,
            };

            const response_date = await this.$http.post(
                `/${this.resource}/date_of_issue`,
                form_date_of_issue
            );

            this.last_date = response_date.data.date_last;
        },
        async view_modal(){
            this.loading = true;
               const response = await this.$http.get(`/restaurant/worker/totales_sales`);
               this.$alert('<h1><strong> S/ '+response.data.total_sales+'</strong></h1>', 'Venta Acumulada', {
                dangerouslyUseHTMLString: true
                });
            this.loading = false;
        },
        async list_tables() {
            this.loading = true;
            const response = await this.$http.get(`/restaurant/worker/${this.resource}/listtables`);
            this.listtables = response.data
            this.listar_tables = response.data
            this.loading = false;
        },
        reInit() {
            // Helpful if you have to deal with v-for to update dynamic lists
            this.$nextTick(() => {
                this.$refs.slick.reSlick();
            });
        },
        async clickPrintPos(printerName, formatoPdf) {
            try {
                let config = qz.configs.create(printerName, {
                    size: {
                        width: 148,
                        height: 210
                    },
                    units: "mm",
                    colorType: "grayscale",
                    copies: 2,
                });
                if (!qz.websocket.isActive()) {
                    await qz.websocket.connect(config);
                }
                let data = [{
                    type: "pdf",
                    format: "file",
                    data: formatoPdf,
                }, ];
                qz.print(config, data).catch((e) => {
                    this.$message.error(e.message);
                });
                // this.clickNewSaleNote();
            } catch (e) {
                this.$message.error(e.message);
            }
        },
        clear_input() {
           // this.input_item = "";
        },
        selectFocus(input_) {
            this.$refs[input_][0].select();
        },
        teclasInit() {
            document.onkeydown = (e) => {
                const key = e.key;
                if (key == "F3") {
                    this.$nextTick(() => {
                        this.$refs.input_item.focus();
                    })
                }
                if (key == "F4") {
                    //Agregar cliente
                    this.clickPayment();
                }
                if (key == "F6") {
                    //Agregar Producto
                    //this.clickAddItemInvoice()
                    //return false;
                }
                if (key == "F7" && this.form.items.length > 0) {
                    //Guardar
                    // this.submit()
                }
                if (key == "F8") {
                    //Cancelar
                    // this.close()
                }
            };
        },

        leftarrow(input, index) {
            let split = input.split("_");

            if (split[1] != this.form.items.length) {
                this.$nextTick(this.$refs[index][0].focus());
            }
        },
        rightarrow(index) {
            let split = index.split("_");
            if (split[1] != this.form.items.length) {
                this.$nextTick(this.$refs[index][0].focus());
            }
        },
        upDown(index, next = false) {
            let split = index.split("_");
            if (index != 0) {
                if (split[1] != this.form.items.length) {
                    this.$nextTick(this.$refs[index][0].focus());
                }
                if (split[1] != this.form.items.length) {
                    this.$nextTick(this.$refs[index][0].focus());
                }
            }
        },
        arrowDown(index, next = false) {
            let split = index.split("_");
            if (split[1] != this.form.items.length) {
                this.$nextTick(this.$refs[index][0].focus());
            }
        },
        select_cantidad(index, next = false) {
            let split = index.split("_");
            if (next == false) {
                if (split[1] == this.form.items.length - 1) {} else {
                    this.$nextTick(this.$refs[index][0].focus());
                }
            } else {
                if (split[1] != this.form.items.length) {
                    this.$nextTick(this.$refs[index][0].focus());
                }
            }
        },
        clickClose: function () {
            this.$confirm("¿Desea Salir del Punto de Venta?", "Advertencia", {
                confirmButtonText: "Aceptar",
                cancelButtonText: "Cerrar",
                type: "warning",
            }).then(() => {
                location.href = `/dashboard`;
            });
        },
        async nueva_venta() {
            this.initForm(this.customer_default.id);
            this.events();
            await this.getFormPosLocalStorage();
            await this.initCurrencyType();

            this.customer = await this.getLocalStorageIndex("customer");
        },
        filterCategorie(id, mod = false) {
            this.category_selected=id
            this.$refs.list_foods.searchFoodCategories(id);
           //this.$refs.input_items.$el.getElementsByTagName("input")[0].focus()
           this.$nextTick(() => {
                this.$refs.input_items.focus();
            })
        },

        initCurrencyType() {
            this.currency_type = _.find(this.currency_types, {
                id: this.form.currency_type_id,
            });
        },

        getFormPosLocalStorage() {
            let form_pos = localStorage.getItem("form_pos");

            form_pos = JSON.parse(form_pos);
            if (form_pos) {
                this.form = form_pos;

                this.calculateTotal();
            }
        },
        deleteFormPosLocalStorage() {
            localStorage.setItem("form_pos", JSON.stringify(this.form));
        },
        setFormPosLocalStorage(form_param = null) {
            /*if (form_param) {
                      localStorage.setItem("form_pos", JSON.stringify(form_param));
                  } else {
                      localStorage.setItem("form_pos", JSON.stringify(this.form));
                  }
                  */
        },
        cancelFormPosLocalStorage() {
            localStorage.setItem("form_pos", JSON.stringify(null));
            this.setLocalStorageIndex("customer", null);
        },
        clickOpenInputEditUP(index) {
            this.items[index].edit_unit_price = true;
        },
        clickEditUnitPriceItem(index) {
            let item_search = this.items[index];
            this.items[index].sale_unit_price =
                this.items[index].edit_sale_unit_price;
            this.items[index].edit_unit_price = false;
        },
        clickCancelUnitPriceItem(index) {
            this.items[index].edit_unit_price = false;
        },
        clickWarehouseDetail(item) {
            this.unittypeDetail = item.unit_type;
            this.warehousesDetail = item.warehouses;
            this.showWarehousesDetail = true;
        },
        clickHistoryPurchases(item_id) {
            this.history_item_id = item_id;
            this.showDialogHistoryPurchases = true;
        },
        clickHistorySales(item_id) {
            if (!this.form.customer_id)
                return this.$message.error("Debe seleccionar el cliente");
            this.history_item_id = item_id;
            this.showDialogHistorySales = true;
        },
        keyupEnterCustomer() {
            if (this.input_person.number) {
                if (!isNaN(parseInt(this.input_person.number))) {
                    switch (this.input_person.number.length) {
                        case 8:
                            this.input_person.identity_document_type_id = "1";
                            this.showDialogNewPerson = true;
                            break;

                        case 11:
                            this.input_person.identity_document_type_id = "6";
                            this.showDialogNewPerson = true;
                            break;
                        default:
                            this.input_person.identity_document_type_id = "6";
                            this.showDialogNewPerson = true;
                            break;
                    }
                }
            }
        },
        keyupCustomer(e) {
            //if(e.key !== "Enter"){
            this.input_person.number =
                this.$refs.select_person.$el.getElementsByTagName("input")[0].value;
            let exist_persons = this.all_customers.filter((customer) => {
                let pos = customer.description.search(this.input_person.number);
                return pos > -1;
            });
            this.input_person.number =
                exist_persons.length == 0 ? this.input_person.number : null;
            //}
        },
        calculateQuantity(index) {
            if (this.form.items[index].item.calculate_quantity) {
                let quantity = _.round(
                    parseFloat(this.form.items[index].total) /
                    parseFloat(this.form.items[index].unit_price),
                    4
                );

                if (quantity) {
                    this.form.items[index].quantity = quantity;
                    this.form.items[index].item.aux_quantity = quantity;
                } else {
                    this.form.items[index].quantity = 0;
                    this.form.items[index].item.aux_quantity = 0;
                }
                // this.calculateTotal()
            }

            //  this.clickAddItem(this.form.items[index],index, true)
        },
        blurCalculateQuantity(index) {
            this.row = calculateRowItem(
                this.form.items[index],
                this.form.currency_type_id,
                1
            );
            this.form.items[index] = this.row;
            this.calculateTotal();
            this.setFormPosLocalStorage();
        },
        blurCalculateQuantity2(index) {
            this.row = calculateRowItem(
                this.form.items[index],
                this.form.currency_type_id,
                1
            );
            this.form.items[index] = this.row;
            this.calculateTotal();
        },
        changeCustomer() {
            this.form.customer_id=this.value
            let customer = _.find(this.all_customers, {
                id: this.form.customer_id
            });
            console.log("customer.identity_document_type_id",customer.identity_document_type_id)
            this.documents_data.datos_del_cliente_o_receptor.codigo_tipo_documento_identidad = customer.identity_document_type_id
            this.documents_data.datos_del_cliente_o_receptor.numero_documento = customer.number
            this.documents_data.datos_del_cliente_o_receptor.apellidos_y_nombres_o_razon_social = customer.name
            this.documents_data.datos_del_cliente_o_receptor.codigo_pais = "PE"
            this.documents_data.datos_del_cliente_o_receptor.ubigeo = customer.district_id
            this.documents_data.datos_del_cliente_o_receptor.direccion = customer.address != null ? customer.address  : ""
            this.documents_data.datos_del_cliente_o_receptor.correo_electronico = customer.email != null ? customer.email  : ""
            this.documents_data.datos_del_cliente_o_receptor.telefono = customer.telephone != null ? customer.telephone  :""
            this.customer = customer;
             this.form.document_type_id =customer.identity_document_type_id == "1" ? "03" : "01";
            this.setLocalStorageIndex("customer", this.customer);
        },

        getLocalStorageIndex(key, re_default = null) {
            let ls_obj = localStorage.getItem(key);
            ls_obj = JSON.parse(ls_obj);

            if (ls_obj) {
                return ls_obj;
            }

            return re_default;
        },
        setLocalStorageIndex(key, obj) {
            localStorage.setItem(key, JSON.stringify(obj));
        },
        async events() {
            await this.$eventHub.$on("initInputPerson", () => {
                this.initInputPerson();
            });

            await this.$eventHub.$on("eventSetFormPosLocalStorage", (form_param) => {
                this.setFormPosLocalStorage(form_param);
            });

            await this.$eventHub.$on("cancelSale", () => {
                this.is_payment = false;
                this.getTables();
                this.initForm(this.customer_default.id);
                this.changeExchangeRate();
                this.ordenId = null;
                this.ordens = [];
                //  this.cancelFormPosLocalStorage()
            });

            await this.$eventHub.$on("reloadDataPersons", (customer_id) => {
                this.reloadDataCustomers(customer_id);
                this.setFormPosLocalStorage();
            });

            await this.$eventHub.$on("reloadDataItems", (item_id) => {
                this.reloadDataItems(item_id);
            });

            await this.$eventHub.$on("saleSuccess", () => {
                //  this.is_payment = false;
                this.getTables();
                this.initForm(this.customer_default.id);
                this.setFormPosLocalStorage();
                this.ordenId = null;
                this.ordens = [];

            });
        },
        async initForm(customer_default = null) {
            this.form = {
                afectar_caja: true,
                orden_id:null,
                customer_telephone:null,
                restaurant: true,
                total_rounded: 0.0,
                total_payment: 0.0,
                establishment_id: null,
                document_type_id: this.establishments.document_default,
                series_id: null,
                prefix: null,
                user_id: this.user.id,
                number: "#",
                date_of_issue:this.date_opencash,
                time_of_issue: moment().format("HH:mm:ss"),
                currency_type_id: "PEN",
                purchase_order: null,
                exchange_rate_sale: 1,
                total_prepayment: 0,
                total_charge: 0,
                total_discount: 0,
                total_exportation: 0,
                total_free: 0,
                total_taxed: 0,
                total_unaffected: 0,
                total_exonerated: 0,
                total_igv: 0,
                total_base_isc: 0,
                total_isc: 0,
                total_base_other_taxes: 0,
                total_other_taxes: 0,
                total_taxes: 0,
                total_value: 0,
                total: 0,
                operation_type_id: "0101",
                date_of_due: this.date_opencash,
                items: [],
                charges: [],
                discounts: [],
                attributes: [],
                guides: [],
                payments: [],
                hotel: {},
                additional_information: null,
                payment_condition_id: "01",
                printerOn:false,
                actions: {
                    format_pdf: "a4",
                },
                difference: 0.0,
                enter_amount: 0.0,
                method_pay:'Efectivo',
                commands_fisico:null,
                to_carry:false
             }
             this.form.customer_id=this.value
            let customer = _.find(this.all_customers, {
                id: this.customer_default.id
            });

            this.value=customer_default
            this.form.customer_id = customer_default;
            this.initFormItem();
            this.changeDateOfIssue();
            this.initInputPerson();
            //  this.changeCustomer();
            this.name_product_pdf = null
            this.documents_data = {
                force_create_if_not_exist : true,
                serie_documento:"NV01",
                numero_documento: "#",
                fecha_de_emision: moment().format('YYYY-MM-DD'),
                hora_de_emision: moment().format("HH:mm:ss"),
                codigo_tipo_operacion: "0101",
                codigo_tipo_documento:"01",
                codigo_tipo_moneda: "PEN",
                fecha_de_vencimiento: moment().format('YYYY-MM-DD'),
                numero_orden_de_compra: "",
                datos_del_cliente_o_receptor:{
                    codigo_tipo_documento_identidad: "1",
                    numero_documento:customer.number,
                    apellidos_y_nombres_o_razon_social: customer.name,
                    codigo_pais: "PE",
                    ubigeo: customer.district_id != null ? customer.district_id : "150101",
                    direccion: customer.address != null ? customer.address : "",
                    correo_electronico: customer.email != null ? customer.email : "",
                    telefono: customer.telephone != null ? customer.telephone : "",
                },
                totales: {
                    total_exportacion: 0.00,
                    total_operaciones_gravadas: 0.00,
                    total_operaciones_inafectas: 0.00,
                    total_operaciones_exoneradas: 0.00,
                    total_operaciones_gratuitas: 0.00,
                    total_igv_operaciones_gratuitas:0,
                    total_base_isc:0,
                    total_isc:0,
                    total_otros_impuestos:0,
                    total_impuestos_bolsa_plastica:0,

                    total_igv: 0.00,
                    total_impuestos: 0.00,
                    total_valor: 100,
                    total_venta: 0
                },
                items:[]
        }
                this.clickAddPayment();
        },
        clickAddPayment() {
            this.form.payments.push({
                id: null,
                document_id: null,
                date_of_payment: moment().format("YYYY-MM-DD"),
                payment_method_type_id: "01",
                reference: null,
                payment_destination_id: this.getPaymentDestinationId(),
                payment: 0,
                filename: null,
                temp_path: null,
                file_list: []
            });

            this.setTotalDefaultPayment();
        },
        setTotalDefaultPayment() {
            if (this.form.payments.length > 0) {
                this.form.payments[0].payment = this.form.total;
            }
        },
        initInputPerson() {
            this.input_person = {
                number: "",
                identity_document_type_id: "",
            };
        },
        initFormItem() {
            this.form_item = {
                item_id: null,
                item: {},
                affectation_igv_type_id: null,
                affectation_igv_type: {},
                has_isc: false,
                system_isc_type_id: null,
                calculate_quantity: false,
                percentage_isc: 0,
                suggested_price: 0,
                quantity: 1,
                aux_quantity: 1,
                unit_price_value: 0,
                unit_price: 0,
                charges: [],
                discounts: [],
                attributes: [],
                has_igv: false,
            };
        },
        async clickPayment() {

            if(this.selectOption==1){
                this.form.orden_id=this.input_item
               // this.idOrden=this.input_item
            }
            this.idOrden=this.input_item
            console.log("input_item idOrden",this.idOrden)
            this.form.items = this.ordens.map((o) => o.food.item);
            this.formatItems();
            this.form.enter_amount = this.form.total;
            this.form.difference = 0;
            let flag = 0;

            if (this.ordens[0].food.price == 0 && this.selectOption == 2) {
                return this.$message.error("Ingrese el Precio por consumo");
            }
            if (!this.form.customer_id)
                return this.$message.error("Seleccione un cliente");
            this.form.establishment_id = this.establishment.id;
            this.is_payment = true;


        },
        getLocalPrinter(key) {
            let ls_obj = localStorage.getItem(key);
            return ls_obj;
        },
        sleep(ms) {
            return new Promise((resolve) => setTimeout(resolve, ms));
        },
        clickDeleteCustomer() {
            this.form.customer_id = null;
            this.setFormPosLocalStorage();
        },
        formatItems() {

            this.form.items = this.form.items.map((i) => {
                return {
                    ...i,
                    item: i,
                    item_id: i.id,
                    unit_value: (i.sale_affectation_igv_type_id == 10) ? (i.sale_unit_price) / (1+(this.percentage_igv/100)) : i.sale_unit_price,
                    quantity: i.quantity,
                    aux_quantity: i.quantity,
                    total_base_igv: (i.sale_affectation_igv_type_id == 10) ? (i.sale_unit_price*i.quantity) / (1+(this.percentage_igv/100)) : i.sale_unit_price*i.quantity,
                    percentage_igv: this.percentage_igv,
                    total_igv: (i.sale_affectation_igv_type_id == 10) ? ((i.sale_unit_price*i.quantity) / (1+(this.percentage_igv/100))) * (this.percentage_igv/100) : 0,
                    total_base_isc: 0.0,
                    percentage_isc: 0.0,
                    total_isc: 0.0,
                    total_base_other_taxes: 0.0,
                    percentage_other_taxes: 0.0,
                    total_other_taxes: 0.0,
                    total_taxes: (i.sale_affectation_igv_type_id == 10) ? (((i.sale_unit_price *i.quantity) / (1+(this.percentage_igv/100))) * (this.percentage_igv/100)) : 0,
                    total_value: (i.sale_affectation_igv_type_id == 10) ? (i.sale_unit_price*i.quantity) / (1+(this.percentage_igv/100)) : i.quantity * i.sale_unit_price,
                    total_charge: 0.0,
                    total_discount: 0.0,
                    total: i.sale_unit_price *i.quantity,
                    price_type_id: "01",
                    unit_price: i.sale_unit_price,
                    unit_price_value: i.sale_unit_price,
                    has_igv: i.has_igv,
                    affectation_igv_type_id: i.sale_affectation_igv_type_id,
                    unit_price: i.sale_unit_price,
                    presentation: null,
                    charges: [],
                    discounts: [],
                    attributes: [],
                    affectation_igv_type: i.sale_affectation_igv_type_id,
                };
            });
              this.calculateTotal()
        },
        async clickAddItem(
            item,
            index,
            input = false,
            price_sale = 0,
            focus = false
        ) {
            //this.loading = true;
            let exchangeRateSale = this.form.exchange_rate_sale;
            let exist_item = _.find(this.form.items, {
                item_id: item.item_id
            });
            let pos = this.form.items.indexOf(exist_item);
            let response = null;
            if (exist_item) {
                if (input) {
                    response = await this.getStatusStock(
                        item.item_id,
                        exist_item.item.aux_quantity
                    );
                    if (!response.success) {
                        item.item.aux_quantity = item.quantity;
                        this.loading = false;
                        return this.$message.error(response.message);
                    }
                    exist_item.quantity = exist_item.item.aux_quantity;
                } else {
                    response = await this.getStatusStock(
                        item.item_id,
                        parseFloat(exist_item.item.aux_quantity) + 1
                    );
                    if (!response.success) {
                        // this.loading = false;
                        return this.$message.error(response.message);
                    }

                    exist_item.quantity++;
                    exist_item.item.aux_quantity++;
                }

                let search_item_bd = await _.find(this.items, {
                    item_id: item.item_id,
                });
                if (search_item_bd) {
                    exist_item.item.unit_price = parseFloat(
                        search_item_bd.sale_unit_price
                    );
                }
                if (price_sale > 0) {
                    exist_item.item.sale_unit_price = price_sale;
                }

                let unit_price = exist_item.item.has_igv ?
                    exist_item.item.sale_unit_price :
                    exist_item.item.sale_unit_price * (1+(this.percentage_igv/100));
                // exist_item.unit_price = unit_price
                exist_item.item.unit_price = unit_price;
                this.row = calculateRowItem(
                    exist_item,
                    this.form.currency_type_id,
                    exchangeRateSale
                );
                this.form.items[pos] = this.row;
            } else {
                response = await this.getStatusStock(item.item_id, 1);
                if (!response.success) {
                    // this.loading = false;
                    return this.$message.error(response.message);
                }

                this.form_item.item = item;
                this.form_item.unit_price_value = this.form_item.item.sale_unit_price;
                this.form_item.has_igv = this.form_item.item.has_igv;
                this.form_item.affectation_igv_type_id =
                this.form_item.item.sale_affectation_igv_type_id;
                this.form_item.quantity = 1;
                this.form_item.aux_quantity = 1;

                let unit_price = this.form_item.has_igv ?
                this.form_item.unit_price_value :
                this.form_item.unit_price_value * (1+(this.percentage_igv/100));

                this.form_item.unit_price = unit_price;
                this.form_item.item.unit_price = unit_price;
                this.form_item.item.presentation = null;

                this.form_item.charges = [];
                this.form_item.discounts = [];
                this.form_item.attributes = [];
                this.form_item.affectation_igv_type = _.find(
                    this.affectation_igv_types, {
                        id: this.form_item.affectation_igv_type_id
                    }
                );

                this.row = calculateRowItem(
                    this.form_item,
                    this.form.currency_type_id,
                    exchangeRateSale
                );
                this.form.items.push(this.row);
                item.aux_quantity = 1;
            }

            this.$notify({
                title: "",
                message: "Producto añadido!",
                type: "success",
                duration: 700,
                position: 'bottom-right'
            });
            await this.calculateTotal();
            this.loading = false;
            await this.setFormPosLocalStorage();
            if (focus == true) {
                let indexx = this.form.items.length - 1;
                this.$nextTick(this.$refs["input_" + indexx][0].focus());
                this.$nextTick(this.$refs["input_" + indexx][0].select());
            }

            localStorage.setItem("form_pos", JSON.stringify(this.form));
        },
        async focus() {
            await this.sleep(200);
            let indexx = this.form.items.length - 1;
        },
        async Printer(Printer, linkpdf, copies,auth=null,multiple_boxes=false,typeuser=null,printing=true) {


            if(multiple_boxes==true && typeuser!='admin'){
                if(this.auth_login==auth){
                    let config = qz.configs.create(Printer, {
                        scaleContent: false
                    });
                    if (!qz.websocket.isActive()) {
                        await qz.websocket.connect(config);
                    }
                    let data = [{
                        type: 'pdf',
                        format: 'file',
                        data: linkpdf
                    }];

                    qz.print(config, data).catch((e) => {
                      this.$message.error(e.message);
                    });
                    for (let index = 0; index < copies; index++) {
                        qz.print(config, data).catch((e) => {
                            this.$message.error(e.message);
                        });
                    }
                }
            }
             if(multiple_boxes==true && typeuser=='admin'){

                let config = qz.configs.create(Printer, {
                    scaleContent: false
                });
                if (!qz.websocket.isActive()) {
                    await qz.websocket.connect(config);
                }
                let data = [{
                    type: 'pdf',
                    format: 'file',
                    data: linkpdf
                }];
                qz.print(config, data).catch((e) => {
                this.$message.error(e.message);
               });
                for (let index = 0; index < copies; index++) {
                    qz.print(config, data).catch((e) => {
                        this.$message.error(e.message);
                    });
                }

            }
                if(multiple_boxes==false){

                    let config = qz.configs.create(Printer, {
                        scaleContent: false
                    });
                    if (!qz.websocket.isActive()) {
                        await qz.websocket.connect(config);
                    }
                    let data = [{
                        type: 'pdf',
                        format: 'file',
                        data: linkpdf
                    }];
                    console.log("Printer",Printer)
                    qz.print(config, data).catch((e) => {
                        this.$message.error(e.message);
                    });

                    for (let index = 0; index < copies; index++) {
                        qz.print(config, data).catch((e) => {
                            this.$message.error(e.message);
                        });
                    }
                }
        },
        async getStatusStock(item_id, quantity) {
            let data = {};
            if (!quantity) quantity = 0;
            await this.$http
                .get(`/${this.resource}/validate_stock/${item_id}/${quantity}`)
                .then((response) => {
                    data = response.data;
                });
            return data;
        },
        async clickDeleteItem(index) {
            this.form.items.splice(index, 1);
            this.calculateTotal();
            this.deleteFormPosLocalStorage();
        },
        calculateItem(index, quantity = 0, price = 0) {
            this.ordens[index].food.quantity = quantity
            this.ordens[index].food.price = price
            this.form.items[index].quantity = quantity
            let total_venta = _.round(Math.round(parseFloat(quantity) * parseFloat(price) * 10) / 10, 2)
            this.form.items[index].total = total_venta
            if (this.form.items[index].affectation_igv_type_id == "10") {
                this.form.items[index].total_value = (this.form.items[index].total / (1+(this.percentage_igv/100))).toFixed(2)
                this.form.items[index].total_taxes = (((quantity * price) * (this.percentage_igv/100)) / (1+(this.percentage_igv/100))).toFixed(2)
                this.form.items[index].total_base_igv = _.round(this.form.items[index].total / (1+(this.percentage_igv/100)), 2)
                this.form.items[index].unit_value = (price / (1+(this.percentage_igv/100))).toFixed(6)
                this.form.items[index].total_igv = _.round(((this.form.items[index].total / (1+(this.percentage_igv/100)))) * (this.percentage_igv/100), 2)
                this.form.items[index].total_base_igv = _.round(this.form.items[index].total / (1+(this.percentage_igv/100)), 2)
            } else {
                this.form.items[index].total_value = Math.round(parseFloat(quantity) * parseFloat(price) * 10) / 10
                this.form.items[index].total_taxes = 0.00
                this.form.items[index].total_base_igv = _.round(Math.round(parseFloat(quantity) * parseFloat(price) * 10) / 10, 2)
                this.form.items[index].unit_value = price
                this.form.items[index].total_igv = 0
                this.form.items[index].total_base_igv = _.round(Math.round(parseFloat(quantity) * parseFloat(price) * 10) / 10, 2)
            }

            this.calculateTotal()
        },
        calculateTotal(sale_unit_price = 0) {
            let total_discount = 0;
            let total_charge = 0;
            let total_exportation = 0;
            let total_taxed = 0;
            let total_taxes = 0;
            let total_exonerated = 0;
            let total_unaffected = 0;
            let total_free = 0;
            let total_igv = 0;
            let total_value = 0;
            let total = 0;
            this.ordens.forEach((orden) => {
                total += _.round(orden.food.item.quantity * orden.food.price, 2);
            });

            //  total_igv = _.round((total / (1+(this.percentage_igv/100))) * (this.percentage_igv/100), 2);

            this.form.items.forEach((row) => {

                total_discount += parseFloat(row.total_discount);
                total_charge += parseFloat(row.total_charge);

                total_taxes += parseFloat(row.total_taxes);
                 if (row.sale_affectation_igv_type_id === "10") {
                    total_igv += _.round(parseFloat(row.total_value) * (this.percentage_igv/100), 2);
                    total_value += _.round(row.total_value, 2);
                     total_taxed += parseFloat(row.total_value);
                }
                if (row.sale_affectation_igv_type_id === "20") {
                    total_exonerated += parseFloat(row.total);
                    total_value += _.round(row.total_value, 2);
                 }
                if (row.sale_affectation_igv_type_id === "30") {
                    total_unaffected += parseFloat(row.total_value);
                    total_value += _.round(row.total_value, 2);
                 }
                if (row.sale_affectation_igv_type_id === "40") {
                    total_exportation += parseFloat(row.total_value);
                    total_value += _.round(row.total_value, 2);
                }
                if (["10", "20", "30", "40"].indexOf(row.affectation_igv_type_id) < 0) {
                    total_free += parseFloat(row.total_value);
                }
            });

            this.form.total_exportation = _.round(total_exportation, 2);
            this.form.total_taxed = _.round(total_taxed, 2);
            this.form.total_taxes = _.round(total_taxes, 2);
            this.form.total_exonerated = _.round(total_exonerated, 2);
            this.form.total_unaffsected = _.round(total_unaffected, 2);
            this.form.total_free = _.round(total_free, 2);
            this.form.total_igv = _.round(total_igv, 2);
             this.form.total_value = _.round(total_value, 2);
            this.form.total = _.round(total, 2);
            if (this.ordens.length > 0) {
                if (this.selectOption == 2) {
                    this.ordens[0].food.item.sale_unit_price = sale_unit_price
                }
            }
            this.setTotalDefaultPayment();
        },
        changeDateOfIssue() {
            // this.searchExchangeRateByDate(this.form.date_of_issue).then(response => {
            //     this.form.exchange_rate_sale = response
            // })
        },
        changeExchangeRate() {
            // this.searchExchangeRateByDate(this.form.date_of_issue).then(
            //   (response) => {
            //     this.form.exchange_rate_sale = response;
            //   }
            // );
        },
        async getFoods() {
            const response = await this.$http.get(`${this.resource}/foods`);
            if (response.status == 200) {
                this.allFoods = response.data.foods;
                this.listFoods = response.data.foods;
                this.selectOption = 4
                //   this.typesearch()
            }
        },
        async getTables() {
             await this.$http.get(`/${this.resource}/tables`).then((response) => {
                this.all_items = response.data.items;
                this.categories = response.data.categories;
                this.payments = response.data.method_payment;
                this.date_last = response.data.date_last;
                this.affectation_igv_types = response.data.affectation_igv_types;
                this.all_customers = response.data.customers;
                let defaultClient = this.all_customers.find((c) =>
                    c.name.toLowerCase().includes("varios")
                );
                if (defaultClient) {
                    this.form.customer_id = defaultClient.id;
                }
                this.establishment = response.data.establishment;
                this.currency_types = response.data.currency_types;
                this.customer_default = response.data.customers_default || response.data.customers[0];
                this.series_default = response.data.series_default
                this.user = response.data.user;
                 this.currency_types.length > 0 ? this.currency_types[0].id : null;


            });


        this.$http.get(`/pos/payment_tables`).then((response) => {
            this.all_series = response.data.series;
        });

        },

        async limpiarForm() {
            this.selectOption = 4
            if(this.configuration.sales_quick==1 || this.configuration.sales_quick==true){
                this.ordens = [];
                this.localOrden = [];
                this.initFormItem();
                await this.initForm(this.customer_default.id)
            }
            await this.getFoods();
            await this.calculateTotal()
            this.$nextTick(() => {
                this.$refs.input_items.focus();
            })
            this.total_sales_pos = 0;
        },
        typesearch() {
            this.$nextTick(() => {
                this.$refs.input_item.focus();
            })
            this.ordens = [];
            this.localOrden = [];
            this.initForm(this.customer_default.id)
            this.initFormItem();
            if (this.selectOption == 0) {
                this.type_search = "Buscar Mesa"
            } else if (this.selectOption == 1) {
                this.type_search = "Buscar Orden"
            } else if (this.selectOption == 2) {
                this.type_search = "Buscar Orden"
            } else if (this.selectOption == 3) {
                this.type_search = "Buscar Producto"
            } else if (this.selectOption == 4) {
                this.type_search = "Buscar Producto"
            }
        },
        search_tables() {
            this.listar_tables = this.listtables.filter((f) =>
                f.number.toLowerCase().includes(this.input_item.toLowerCase())
            );
        },
        async search_orden() {
            this.loading = true;
            if (this.input_item.length > 0) {
                this.loading = true;
                let parameters = `input_item=${this.input_item}`;
                const response = await this.$http.get(`${this.resource}/search_orden?${parameters}`);
                let {
                    ordens,
                    success,
                    message
                } = response.data;
                if (!success) {
                    this.$message.error(message);
                    this.loading = false;
                    return;
                }
                if (success) {

                    this.ordenId = ordens.id;
                    console.log("ordenId ordenId",this.ordenId)
                    this.loading = false;
                    if (this.selectOption == 2) {
                        let f = this.allFoods[0];
                        if (f) {
                            f.llevar = true;
                            if (this.selectOption == 2) {
                                f.item.name_product_pdf = "POR CONSUMO";
                                f.item.quantity = 1;
                            }

                            this.ordens = [...this.ordens, {
                                food: f
                            }];
                        }
                        if (this.selectOption == 2) {
                            this.ordens[0].food.price = 0.00
                        }

                        this.calculateTotal();
                    } else {

                        this.ordens = ordens.orden_items;
                        for (let index = 0; index < this.ordens.length; index++) {
                            this.ordens[index].food.item.quantity = this.ordens[index].quantity
                        }

                    }
                    this.form.items = this.ordens.map((o) => o.food.item);
                    let listseries = _.find(this.all_series, {
                        document_type_id: this.establishments.document_default,
                    });
                    this.documents_data.codigo_tipo_documento = listseries.document_type_id
                    console.log("listseries listseries",listseries)

                    this.documents_data.items =[]
                    this.ordens.forEach((item, index, ) => {
                        this.documents_data.items.push({
                            codigo_interno: item.food.id,
                            descripcion:item.food.description,
                            codigo_producto_sunat: "",
                            unidad_de_medida: item.food.item.unit_type_id,
                            cantidad: item.quantity,
                            valor_unitario: _.round(item.food.item.sale_unit_price/(1+(this.percentage_igv/100)),2),
                            codigo_tipo_precio: "01",
                            precio_unitario: item.food.item.sale_unit_price,
                            codigo_tipo_afectacion_igv: item.food.item.sale_affectation_igv_type_id,
                            total_base_igv:_.round((item.food.item.sale_unit_price*item.quantity)/ (1+(this.percentage_igv/100)),2),
                            porcentaje_igv: this.percentage_igv,
                            total_igv: _.round((_.round(item.food.item.sale_unit_price*item.quantity,2)/(1+(this.percentage_igv/100)))*(this.percentage_igv/100),2),
                            total_impuestos: _.round((_.round(item.food.item.sale_unit_price*item.quantity,2)/(1+(this.percentage_igv/100)))*(this.percentage_igv/100),2),
                            total_valor_item: _.round(_.round(item.food.item.sale_unit_price/(1+(this.percentage_igv/100)),2)*item.quantity,2),
                            total_item: _.round(item.food.item.sale_unit_price*item.quantity,2)
                        })
                    })

                    let total_discount = 0;
                    let total_charge = 0;
                    let total_exportation = 0;
                    let total_taxed = 0;
                    let total_taxes = 0;
                    let total_exonerated = 0;
                    let total_unaffected = 0;
                    let total_free = 0;
                    let total_igv = 0;
                    let total_value = 0;
                    let total = 0;
                    this.ordens.forEach((orden) => {
                        total += _.round(orden.food.item.quantity * orden.food.price, 2);
                    });

                    this.documents_data.items.forEach((row) => {

                        total_discount += parseFloat(row.total_discount);
                        total_charge += parseFloat(row.total_charge);
                        total_taxes += parseFloat(row.total_igv);
                        if (row.codigo_tipo_afectacion_igv === "10") {
                              console.log("roooooooooooo",row)
                            total_igv += _.round(parseFloat(row.total_valor_item) * (this.percentage_igv/100), 2);
                            total_value += _.round(row.total_valor_item, 2);
                            total_taxed += parseFloat(row.total_valor_item);
                        }
                        if (row.codigo_tipo_afectacion_igv === "20") {
                            total_exonerated += parseFloat(row.cantidad * row.precio_unitario);
                            total_value += _.round(row.total_valor_item, 2);
                        }
                        if (row.codigo_tipo_afectacion_igv === "30") {
                            total_unaffected += parseFloat(row.precio_unitario);
                            total_value += _.round(row.precio_unitario, 2);
                        }
                        if (row.codigo_tipo_afectacion_igv === "40") {
                            total_exportation += parseFloat(row.precio_unitario);
                            total_value += _.round(row.precio_unitario, 2);
                        }
                        if (["10", "20", "30", "40"].indexOf(row.codigo_tipo_afectacion_igv) < 0) {
                            total_free += parseFloat(row.precio_unitario);
                        }
                    });

                    this.documents_data.totales.total_exportacion =  _.round(total_exonerated, 2)
                    this.documents_data.totales.total_operaciones_gravadas = _.round(total_value, 2)
                    this.documents_data.totales.total_operaciones_inafectas = _.round(total_unaffected, 2)
                    this.documents_data.totales.total_operaciones_exoneradas = _.round(total_exonerated, 2)
                    this.documents_data.totales.total_operaciones_gratuitas = _.round(total_free, 2)
                    this.documents_data.totales.total_igv = _.round(total_igv, 2)
                    this.documents_data.totales.total_impuestos =_.round(total_igv, 2)
                    this.documents_data.totales.total_valor = _.round(total_value, 2)
                    this.documents_data.totales.total_venta =  _.round(total, 2)

                    if (this.ordens.length > 0) {
                        if (this.selectOption == 2) {
                            this.ordens[0].food.item.sale_unit_price = sale_unit_price
                        }
                    }

                    this.formatItems();
                    this.calculateTotal();
                    this.loading = false;
                }

            } else {
                this.filterItems();
            }
            this.newFood = null;
            this.name_product_pdf = null
            this.loading = false;
        },
        search_items() {
            let inputitem = this.input_item.toLowerCase()
             this.$refs.list_foods.searchFood(inputitem,this.type_search);
            //
            //  this.listFoods = this.allFoods.filter((f) =>
            //   f.description.toLowerCase().includes(inputitem)
            // );
        },
        async search() {

            if (this.selectOption == 0) {
                this.search_tables()
            } else if (this.selectOption == 1) {
                this.search_orden()
            } else if (this.selectOption == 2) {
                this.search_orden()
            } else if (this.selectOption == 3) {
                this.search_items()
            } else if (this.selectOption == 4) {
                this.search_items()
            }
        },
        async searchItemsBarcode() {

            if (this.input_item.length > 1) {
                this.loading = true;
                let parameters = `input_item=${this.input_item}`;

                await this.$http
                    .get(`/${this.resource}/search_items?${parameters}`)
                    .then((response) => {
                        this.items = response.data.items;
                        this.enabledSearchItemsBarcode();
                        this.loading = false;
                        if (this.items.length == 0) {
                            this.filterItems();
                        }
                    });
            } else {
                await this.filterItems();
            }
        },
        enabledSearchItemsBarcode() {
            if (this.search_item_by_barcode) {
                if (this.items.length == 1) {
                    this.clickAddItem(this.items[0], 0);
                    this.filterItems();
                }

                this.cleanInput();
            }
        },
        changeSearchItemBarcode() {
            this.cleanInput();
        },
        cleanInput() {
            this.input_item = null;
        },
        filterItems() {
            this.items = this.all_items;
        },
        reloadDataCustomers(customer_id) {
            this.$http.get(`/${this.resource}/table/customers`).then((response) => {
                this.all_customers = response.data;
                this.form.customer_id = customer_id;
                this.changeCustomer();
            });
        },
        reloadDataItems(item_id) {
            this.$http.get(`/${this.resource}/table/items`).then((response) => {
                this.all_items = response.data;
                let array_temp = _.filter(this.all_items, {
                    id: item_id
                });
                this.input_item = array_temp[0].description;
                this.filterItems();
                this.searchItems();
                this.clickAddItem(array_temp[0], 0, false);
            });
        },
        selectCurrencyType() {
            this.form.currency_type_id =
                this.form.currency_type_id === "PEN" ? "USD" : "PEN";
            this.changeCurrencyType();
        },
        async changeCurrencyType() {
            this.currency_type = await _.find(this.currency_types, {
                id: this.form.currency_type_id,
            });
            let items = [];
            this.form.items.forEach((row) => {
                items.push(
                    calculateRowItem(
                        row,
                        this.form.currency_type_id,
                        this.form.exchange_rate_sale
                    )
                );
            });
            this.form.items = items;
            this.calculateTotal();

            await this.setFormPosLocalStorage();
        },
        openFullWindow() {
            location.href = `/${this.resource}/pos_full`;
        },
        back(valor) {
            if (valor == 2) {
                this.place = true;
            } else {
                this.place = false;
                this.$refs.input_item.$el.getElementsByTagName("input")[0].focus();
            }
        },
        setView() {
            this.place = "cat2";
        },
        nameSets(id) {
            let row = this.items.find((x) => x.item_id == id);
            if (row) {
                if (row.sets.length > 0) {
                    return row.sets.join("-");
                } else {
                    return "";
                }
            }
        },
    },
     mounted() {
        this.teclasInit();
        Echo.channel("stock_orden").listen(`.stock-order-${this.configuration.socket_channel}`,
            (e) => {
                for (let index = 0; index < e.data.order_item.length; index++) {
                    let xFind = _.find(this.listFoods, {id: e.data.order_item[index].food_id})
                     let index_find = _.findIndex(this.listFoods, {
                        id: xFind.id
                    })
                    if (index_find !== -1) {
                        let nSaldo=parseInt(this.listFoods[index_find].item.stock) - e.data.order_item[index].quantity
                        this.listFoods[index_find].item.stock=nSaldo
                    }
                }
            }
        );

        Echo.channel("print_orden").listen(`.print-order-${this.configuration.socket_channel}`,
            (e) => {
                console.log("imprimiendo",e)
                 if (e.data.direct_printing == true) {
                     if(e.data.printing== true){
                         this.Printer(e.data.printer, e.data.print, e.data.copies, e.data.user_id,e.data.multiple_boxes,e.data.typeuser,e.data.printing);
                    }
               }
            }
        );
    },
}
</script>

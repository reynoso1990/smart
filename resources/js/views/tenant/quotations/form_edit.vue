<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="tab-content" v-if="loading_form">
            <div class="invoice">
                <header class="clearfix">
                    <div class="row">
                        <div class="col-sm-2 text-center mt-3 mb-0">
                            <logo
                                url="/"
                                :path_logo="
                                    company.logo != null
                                        ? `/storage/uploads/logos/${company.logo}`
                                        : ''
                                "
                            ></logo>
                        </div>
                        <div class="col-sm-6 text-left mt-3 mb-0">
                            <address class="ib mr-2">
                                <template
                                    v-if="
                                        form.quotations_optional != null &&
                                        form.quotations_optional_value != null
                                    "
                                >
                                    <span class="font-weight-bold d-block">
                                        {{ form.quotations_optional }}
                                    </span>
                                    <span class="font-weight-bold d-block">
                                        {{ form.quotations_optional_value }}
                                    </span>
                                </template>

                                <span class="font-weight-bold d-block"
                                    >COTIZACIÓN</span
                                >
                                <span class="font-weight-bold d-block"
                                    >{{form.prefix}}-XXX</span
                                >
                                <span class="font-weight-bold">{{
                                    company.name
                                }}</span>
                                <br />
                                <div v-if="establishment.address != '-'">
                                    {{ establishment.address }},
                                </div>
                                {{ establishment.district.description }},
                                {{ establishment.province.description }},
                                {{ establishment.department.description }} -
                                {{ establishment.country.description }}
                                <br />
                                {{ establishment.email }} -
                                <span v-if="establishment.telephone != '-'">{{
                                    establishment.telephone
                                }}</span>
                            </address>
                        </div>
                    </div>
                </header>
                <form autocomplete="off" @submit.prevent="submit">
                    <div class="form-body">
                        <div class="row mt-1">
                            <div class="col-lg-4 pb-2" v-if="isProject">
                                <div
                                    class="form-group"
                                    :class="{
                                        'has-danger': errors.project_name,
                                    }"
                                >
                                    <label
                                        class="control-label font-weight-bold text-info"
                                    >
                                        Proyecto
                                    </label>
                                    <el-input v-model="form.project_name">
                                    </el-input>
                                    <small
                                        class="text-danger"
                                        v-if="errors.project_name"
                                        v-text="errors.project_name[0]"
                                    ></small>
                                </div>
                                <div
                                    v-if="customer_addresses.length > 0"
                                    class="form-group"
                                >
                                    <label
                                        class="control-label font-weight-bold text-info"
                                        >Dirección</label
                                    >
                                    <el-select
                                        v-model="form.customer_address_id"
                                    >
                                        <el-option
                                            v-for="(
                                                option, idx
                                            ) in customer_addresses"
                                            :key="idx"
                                            :value="option.id"
                                            :label="option.address"
                                        ></el-option>
                                    </el-select>
                                </div>
                            </div>
                            <div class="col-lg-4 pb-2">
                                <div
                                    class="form-group"
                                    :class="{
                                        'has-danger': errors.customer_id,
                                    }"
                                >
                                    <label
                                        class="control-label font-weight-bold text-info"
                                    >
                                        Cliente
                                        <a
                                            href="#"
                                            @click.prevent="
                                                showDialogNewPerson = true
                                            "
                                            >[+ Nuevo]</a
                                        >
                                    </label>
                                    <el-select
                                        v-model="form.customer_id"
                                        filterable
                                        remote
                                        popper-class="el-select-customers"
                                        dusk="customer_id"
                                        placeholder="Escriba el nombre o número de documento del cliente"
                                        :remote-method="searchRemoteCustomers"
                                        :loading="loading_search"
                                        @change="changeCustomer"
                                        @keyup.enter.native="keyupCustomer"
                                    >
                                        <el-option
                                            v-for="option in customers"
                                            :key="option.id"
                                            :value="option.id"
                                            :label="option.description"
                                        ></el-option>
                                    </el-select>
                                    <small
                                        class="text-danger"
                                        v-if="errors.customer_id"
                                        v-text="errors.customer_id[0]"
                                    ></small>
                                </div>
                                <div
                                    v-if="customer_addresses.length > 0"
                                    class="form-group"
                                >
                                    <label
                                        class="control-label font-weight-bold text-info"
                                        >Dirección</label
                                    >
                                    <el-select
                                        v-model="form.customer_address_id"
                                    >
                                        <el-option
                                            v-for="option in customer_addresses"
                                            :key="option.id"
                                            :value="option.id"
                                            :label="option.address"
                                        ></el-option>
                                    </el-select>
                                </div>
                            </div>
                            <div class="col-lg-4 pb-2" v-if="isProject">
                                <div
                                    class="form-group"
                                    :class="{
                                        'has-danger': errors.atention,
                                    }"
                                >
                                    <label
                                        class="control-label font-weight-bold text-info"
                                    >
                                        Atención
                                    </label>
                                    <el-input v-model="form.atention">
                                    </el-input>
                                    <small
                                        class="text-danger"
                                        v-if="errors.atention"
                                        v-text="errors.atention[0]"
                                    ></small>
                                </div>
                                <div
                                    v-if="customer_addresses.length > 0"
                                    class="form-group"
                                >
                                    <label
                                        class="control-label font-weight-bold text-info"
                                        >Dirección</label
                                    >
                                    <el-select
                                        v-model="form.customer_address_id"
                                    >
                                        <el-option
                                            v-for="(
                                                option, idx
                                            ) in customer_addresses"
                                            :key="idx"
                                            :value="option.id"
                                            :label="option.address"
                                        ></el-option>
                                    </el-select>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div
                                    class="form-group"
                                    :class="{
                                        'has-danger': errors.date_of_issue,
                                    }"
                                >
                                    <label class="control-label"
                                        >Fec. Emisión</label
                                    >
                                    <el-date-picker
                                        v-model="form.date_of_issue"
                                        type="date"
                                        value-format="yyyy-MM-dd"
                                        :clearable="false"
                                        @change="changeDateOfIssue"
                                    ></el-date-picker>
                                    <small
                                        class="text-danger"
                                        v-if="errors.date_of_issue"
                                        v-text="errors.date_of_issue[0]"
                                    ></small>
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div
                                    class="form-group"
                                    :class="{
                                        'has-danger': errors.date_of_due,
                                    }"
                                >
                                    <label
                                        class="control-label"
                                        v-if="!isProject"
                                        >Tiempo de Validez</label
                                    >
                                    <label class="control-label" v-else
                                        >Fecha de vencimiento</label
                                    >
                                    <el-input
                                        v-model="form.date_of_due"
                                    ></el-input>
                                    <small
                                        class="text-danger"
                                        v-if="errors.date_of_due"
                                        v-text="errors.date_of_due[0]"
                                    ></small>
                                </div>
                            </div>
                            <div class="col-lg-2" v-if="!isProject">
                                <div
                                    class="form-group"
                                    :class="{
                                        'has-danger': errors.delivery_date,
                                    }"
                                >
                                    <label class="control-label"
                                        >Tiempo de Entrega</label
                                    >
                                    <el-input
                                        v-model="form.delivery_date"
                                    ></el-input>
                                    <small
                                        class="text-danger"
                                        v-if="errors.delivery_date"
                                        v-text="errors.delivery_date[0]"
                                    ></small>
                                </div>
                            </div>
                            <div class="col-lg-4" v-if="!isProject">
                                <div class="form-group">
                                    <label for="" class="control-label">
                                        <template>Dirección de envío</template>
                                    </label>
                                    <el-input
                                        v-model="form.shipping_address"
                                    ></el-input>
                                    <small
                                        class="text-danger"
                                        v-if="errors.shipping_address"
                                        v-text="errors.shipping_address[0]"
                                    ></small>
                                </div>
                            </div>
                            <div class="col-lg-4" v-else>
                                <div class="form-group">
                                    <label for="" class="control-label">
                                        <template>Dirección</template>
                                    </label>
                                    <el-input
                                        v-model="form.direction"
                                    ></el-input>
                                    <small
                                        class="text-danger"
                                        v-if="errors.direction"
                                        v-text="errors.direction[0]"
                                    ></small>
                                </div>
                            </div>
                            <div class="col-lg-4" v-if="isProject">
                                <div class="form-group">
                                    <label class="control-label">
                                        Teléfono
                                    </label>
                                    <el-input
                                        v-model="form.telephone"
                                    ></el-input>
                                    <small
                                        class="text-danger"
                                        v-if="errors.telephone"
                                        v-text="errors.telephone[0]"
                                    ></small>
                                </div>
                            </div>
                            <div class="col-lg-4" v-if="isProject">
                                <div class="form-group">
                                    <label class="control-label"> Email </label>
                                    <el-input v-model="form.email"></el-input>
                                    <small
                                        class="text-danger"
                                        v-if="errors.email"
                                        v-text="errors.email[0]"
                                    ></small>
                                </div>
                            </div>
                            <div class="col-lg-4" v-if="isProject">
                                <div class="form-group">
                                    <label class="control-label">
                                        Porcentaje a abonar
                                    </label>
                                    <el-input
                                        v-model="form.percentage"
                                    ></el-input>
                                    <small
                                        class="text-danger"
                                        v-if="errors.percentage"
                                        v-text="errors.percentage[0]"
                                    ></small>
                                </div>
                            </div>
                            <div class="col-lg-4" v-if="isProject">
                                <div class="form-group">
                                    <label class="control-label">
                                        Plazo de entrega
                                    </label>
                                    <el-input
                                        v-model="form.limit_date"
                                    ></el-input>
                                    <small
                                        class="text-danger"
                                        v-if="errors.limit_date"
                                        v-text="errors.limit_date[0]"
                                    ></small>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div
                                    class="form-group"
                                    :class="{
                                        'has-danger':
                                            errors.payment_method_type_id,
                                    }"
                                >
                                    <label class="control-label">
                                        Término de pago
                                    </label>
                                    <el-select
                                        v-model="form.payment_method_type_id"
                                        filterable
                                        @change="changePaymentMethodType"
                                    >
                                        <el-option
                                            v-for="option in payment_method_types"
                                            :key="option.id"
                                            :value="option.id"
                                            :label="option.description"
                                        ></el-option>
                                    </el-select>
                                    <small
                                        class="text-danger"
                                        v-if="errors.payment_method_type_id"
                                        v-text="
                                            errors.payment_method_type_id[0]
                                        "
                                    ></small>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="control-label"
                                        >Número de cuenta
                                    </label>
                                    <el-input
                                        v-model="form.account_number"
                                    ></el-input>
                                    <small
                                        class="text-danger"
                                        v-if="errors.account_number"
                                        v-text="errors.account_number[0]"
                                    ></small>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div
                                    class="form-group"
                                    :class="{
                                        'has-danger': errors.currency_type_id,
                                    }"
                                >
                                    <label class="control-label">Moneda</label>
                                    <el-select
                                        v-model="form.currency_type_id"
                                        @change="changeCurrencyType"
                                    >
                                        <el-option
                                            v-for="option in currency_types"
                                            :key="option.id"
                                            :value="option.id"
                                            :label="option.description"
                                        ></el-option>
                                    </el-select>
                                    <small
                                        class="text-danger"
                                        v-if="errors.currency_type_id"
                                        v-text="errors.currency_type_id[0]"
                                    ></small>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div
                                    class="form-group"
                                    :class="{
                                        'has-danger': errors.exchange_rate_sale,
                                    }"
                                >
                                    <label class="control-label"
                                        >Tipo de cambio
                                        <el-tooltip
                                            class="item"
                                            effect="dark"
                                            content="Tipo de cambio del día, extraído de SUNAT"
                                            placement="top-end"
                                        >
                                            <i class="fa fa-info-circle"></i>
                                        </el-tooltip>
                                    </label>
                                    <el-input
                                        v-model="form.exchange_rate_sale"
                                    ></el-input>
                                    <small
                                        class="text-danger"
                                        v-if="errors.exchange_rate_sale"
                                        v-text="errors.exchange_rate_sale[0]"
                                    ></small>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="row">
                                    <div class="form-group col-6 col-md-2">
                                        <label>Vendedor</label>
                                        <el-select
                                            v-model="form.seller_id"
                                            clearable
                                        >
                                            <el-option
                                                v-for="sel in sellers"
                                                :key="sel.id"
                                                :value="sel.id"
                                                :label="sel.name"
                                                >{{ sel.name }}
                                            </el-option>
                                        </el-select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-8 mt-2">
                                <label>Pagos</label>
                                <table>
                                    <thead>
                                        <tr width="100%">
                                            <th
                                                v-if="form.payments.length > 0"
                                                class="pb-2"
                                            >
                                                Método de pago
                                            </th>
                                            <th
                                                v-if="form.payments.length > 0"
                                                class="pb-2"
                                            >
                                                Destino
                                                <el-tooltip
                                                    class="item"
                                                    effect="dark"
                                                    content="Aperture caja o cuentas bancarias"
                                                    placement="top-start"
                                                >
                                                    <i
                                                        class="fa fa-info-circle"
                                                    ></i>
                                                </el-tooltip>
                                            </th>
                                            <th
                                                v-if="form.payments.length > 0"
                                                class="pb-2"
                                            >
                                                Referencia
                                            </th>
                                            <th
                                                v-if="form.payments.length > 0"
                                                class="pb-2"
                                            >
                                                Monto
                                            </th>
                                            <th width="15%">
                                                <a
                                                    href="#"
                                                    @click.prevent="
                                                        clickAddPayment
                                                    "
                                                    class="text-center font-weight-bold text-info"
                                                    >[+ Agregar]</a
                                                >
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(
                                                row, index
                                            ) in form.payments"
                                            :key="index"
                                        >
                                            <td>
                                                <div
                                                    class="form-group mb-2 mr-2"
                                                >
                                                    <el-select
                                                        v-model="
                                                            row.payment_method_type_id
                                                        "
                                                    >
                                                        <el-option
                                                            v-for="option in payment_method_types"
                                                            :key="option.id"
                                                            :value="option.id"
                                                            :label="
                                                                option.description
                                                            "
                                                        ></el-option>
                                                    </el-select>
                                                </div>
                                            </td>
                                            <td>
                                                <div
                                                    class="form-group mb-2 mr-2"
                                                >
                                                    <el-select
                                                        v-model="
                                                            row.payment_destination_id
                                                        "
                                                        filterable
                                                    >
                                                        <el-option
                                                            v-for="option in payment_destinations"
                                                            :key="option.id"
                                                            :value="option.id"
                                                            :label="
                                                                option.description
                                                            "
                                                        ></el-option>
                                                    </el-select>
                                                </div>
                                            </td>
                                            <td>
                                                <div
                                                    class="form-group mb-2 mr-2"
                                                >
                                                    <el-input
                                                        v-model="row.reference"
                                                    ></el-input>
                                                </div>
                                            </td>
                                            <td>
                                                <div
                                                    class="form-group mb-2 mr-2"
                                                >
                                                    <el-input
                                                        v-model="row.payment"
                                                    ></el-input>
                                                </div>
                                            </td>
                                            <td
                                                class="series-table-actions text-center"
                                            >
                                                <button
                                                    type="button"
                                                    class="btn waves-effect waves-light btn-sm btn-danger"
                                                    @click.prevent="
                                                        clickCancel(index)
                                                    "
                                                >
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                            <br />
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12">
                                <el-collapse v-model="activePanel" accordion>
                                    <el-collapse-item name="1">
                                        <template slot="title">
                                            <i class="fa fa-plus text-info"></i>
                                            &nbsp; Información Adicional<i
                                                class="header-icon el-icon-information"
                                            ></i>
                                        </template>
                                        <div class="row mt-2">
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label"
                                                        >Contacto
                                                    </label>
                                                    <el-input
                                                        v-model="form.contact"
                                                    ></el-input>
                                                    <small
                                                        class="text-danger"
                                                        v-if="
                                                            errors.account_number
                                                        "
                                                        v-text="
                                                            errors
                                                                .account_number[0]
                                                        "
                                                    ></small>
                                                </div>
                                            </div>

                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <label class="control-label"
                                                        >Teléfono
                                                    </label>
                                                    <el-input
                                                        v-model="form.phone"
                                                    ></el-input>
                                                    <small
                                                        class="text-danger"
                                                        v-if="
                                                            errors.account_number
                                                        "
                                                        v-text="
                                                            errors
                                                                .account_number[0]
                                                        "
                                                    ></small>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div
                                                    class="form-group"
                                                    :class="{
                                                        'has-danger':
                                                            errors.exchange_rate_sale,
                                                    }"
                                                >
                                                    <label class="control-label"
                                                        >Observación
                                                    </label>
                                                    <el-input
                                                        type="textarea"
                                                        :rows="3"
                                                        v-model="
                                                            form.description
                                                        "
                                                        maxlength="1000"
                                                        show-word-limit
                                                    ></el-input>
                                                    <small
                                                        class="text-danger"
                                                        v-if="
                                                            errors.description
                                                        "
                                                        v-text="
                                                            errors
                                                                .description[0]
                                                        "
                                                    ></small>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="control-label"
                                                        >Información
                                                        referencial</label
                                                    >
                                                    <el-input
                                                        v-model="
                                                            form.referential_information
                                                        "
                                                    ></el-input>
                                                    <small
                                                        class="text-danger"
                                                        v-if="
                                                            errors.referential_information
                                                        "
                                                        v-text="
                                                            errors
                                                                .referential_information[0]
                                                        "
                                                    ></small>
                                                </div>
                                            </div>
                                        </div>
                                    </el-collapse-item>
                                </el-collapse>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 mt-2" v-if="isProject">
                            <div class="form-group">
                                <label class="control-label"
                                    >Observaciones que iran en el pdf</label
                                >
                                <vue-ckeditor
                                    v-model="form.observations"
                                    :editors="editors"
                                    type="classic"
                                ></vue-ckeditor>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th v-if="isProject">
                                                    Cabecera
                                                </th>
                                                <th width="5%">#</th>
                                                <th
                                                    class="font-weight-bold"
                                                    width="30%"
                                                >
                                                    Descripción
                                                </th>
                                                <th
                                                    width="8%"
                                                    class="text-center font-weight-bold"
                                                >
                                                    Unidad
                                                </th>
                                                <th
                                                    width="8%"
                                                    class="text-center font-weight-bold"
                                                >
                                                    Cantidad
                                                </th>
                                                <th
                                                    class="text-center font-weight-bold"
                                                >
                                                    Valor U.
                                                </th>
                                                <th
                                                    class="text-center font-weight-bold"
                                                >
                                                    Precio U.
                                                </th>
                                                <th
                                                    class="text-center font-weight-bold"
                                                >
                                                    Subtotal
                                                </th>
                                                <th
                                                    class="text-center font-weight-bold"
                                                >
                                                    Total
                                                </th>
                                                <th width="8%"></th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="form.items.length > 0">
                                            <tr
                                                v-for="(
                                                    row, index
                                                ) in form.items"
                                                :key="index"
                                            >
                                                <td v-if="isProject">
                                                    <template
                                                        v-if="
                                                            checkHeader(
                                                                row,
                                                                index
                                                            ).header &&
                                                            checkHeader(
                                                                row,
                                                                index
                                                            ).header != '-'
                                                        "
                                                    >
                                                        <small
                                                            class="text-info font-weight-bold"
                                                        >
                                                            {{
                                                                checkHeader(
                                                                    row,
                                                                    index
                                                                ).header
                                                            }}
                                                        </small>
                                                    </template>
                                                </td>
                                                <td>{{ index + 1 }}</td>
                                                <td>
                                                    {{
                                                        setDescriptionOfItem(
                                                            row.item
                                                        )
                                                    }}
                                                    {{
                                                        row.item.presentation.hasOwnProperty(
                                                            "description"
                                                        )
                                                            ? row.item
                                                                  .presentation
                                                                  .description
                                                            : ""
                                                    }}

                                                    <template v-if="isProject">
                                                        <template
                                                            v-if="
                                                                row.item
                                                                    .brand &&
                                                                row.item
                                                                    .brand != ''
                                                            "
                                                        >
                                                            <br />
                                                            <small
                                                                >Marca:
                                                                {{
                                                                    row.item
                                                                        .brand
                                                                }}</small
                                                            >
                                                        </template>
                                                        <template
                                                            v-if="
                                                                row.item
                                                                    .model &&
                                                                row.item
                                                                    .model != ''
                                                            "
                                                        >
                                                            <br />
                                                            <small
                                                                >Modelo:
                                                                {{
                                                                    row.item
                                                                        .model
                                                                }}</small
                                                            >
                                                        </template>
                                                        <template
                                                            v-if="
                                                                Object.values(
                                                                    row.attributes
                                                                ).filter(
                                                                    (a) =>
                                                                        a.description ==
                                                                        'Color'
                                                                ).length > 0
                                                            "
                                                        >
                                                            <br />
                                                            <small>
                                                                Color:
                                                                {{
                                                                    Object.values(
                                                                        row.attributes
                                                                    ).filter(
                                                                        (a) =>
                                                                            a.description ==
                                                                            "Color"
                                                                    )[0].value
                                                                }}
                                                            </small>
                                                        </template>
                                                    </template>
                                                    <br /><small>{{
                                                        row.affectation_igv_type
                                                            .description
                                                    }}</small>
                                                </td>
                                                <td class="text-center">
                                                    {{ row.item.unit_type_id }}
                                                </td>
                                                <td class="text-center">
                                                    {{ row.quantity }}
                                                    <template v-if="isProject">
                                                        <br />
                                                        <el-tag
                                                            size="mini"
                                                            type="primary"
                                                        >
                                                            {{
                                                                row.disponibilidad ==
                                                                    null ||
                                                                row.disponibilidad ==
                                                                    undefined ||
                                                                row.disponibilidad ==
                                                                    ""
                                                                    ? row.disponibility
                                                                    : row.disponibilidad
                                                            }}
                                                        </el-tag>
                                                      
                                                    </template>
                                                </td>
                                                <td class="text-center">
                                                    {{ currency_type.symbol }}
                                                    {{
                                                        getFormatUnitPriceRow(
                                                            row.unit_value
                                                        )
                                                    }}
                                                </td>
                                                <td class="text-end">
                                                    {{ currency_type.symbol }}
                                                    {{
                                                        getFormatUnitPriceRow(
                                                            row.unit_price
                                                        )
                                                    }}
                                                </td>

                                                <td class="text-center">
                                                    {{ currency_type.symbol }}
                                                    {{ row.total_value }}
                                                </td>
                                                <td class="text-center">
                                                    {{ currency_type.symbol }}
                                                    {{ row.total }}
                                                </td>
                                                <td class="text-center">
                                                    <button
                                                        type="button"
                                                        class="btn waves-effect waves-light btn-sm btn-info"
                                                        @click="
                                                            ediItem(row, index)
                                                        "
                                                    >
                                                        <span
                                                            style="
                                                                font-size: 10px;
                                                            "
                                                            >&#9998;</span
                                                        >
                                                    </button>
                                                    <button
                                                        type="button"
                                                        class="btn waves-effect waves-light btn-sm btn-danger"
                                                        @click.prevent="
                                                            clickRemoveItem(
                                                                index
                                                            )
                                                        "
                                                    >
                                                        x
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="9"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div
                                class="col-lg-12 col-md-6 d-flex align-items-end"
                            >
                                <div class="form-group">
                                    <button
                                        type="button"
                                        class="btn waves-effect waves-light btn-primary"
                                        @click="clickAddItem"
                                    >
                                        + Agregar Producto
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-8 mt-3"></div>

                            <div class="col-md-4">
                                <div
                                    class="row"
                                    v-if="isProject && form.total > 0"
                                >
                                    <div class="col-lg-8 text-end">
                                        <label class="text-end control-label">
                                            DESCUENTO % :
                                        </label>
                                    </div>

                                    <div class="col-lg-4 text-end">
                                        <el-input-number
                                            v-model="total_global_discount"
                                            :min="0"
                                            class="w-100"
                                            controls-position="right"
                                            @change="changeTotalGlobalDiscount"
                                        ></el-input-number>
                                    </div>
                                </div>
                                <p
                                    class="text-end"
                                    v-if="form.total_exportation > 0"
                                >
                                    OP.EXPORTACIÓN: {{ currency_type.symbol }}
                                    {{ form.total_exportation }}
                                </p>
                                <p class="text-end" v-if="form.total_free > 0">
                                    OP.GRATUITAS: {{ currency_type.symbol }}
                                    {{ form.total_free }}
                                </p>
                                <p
                                    class="text-end"
                                    v-if="form.total_unaffected > 0"
                                >
                                    OP.INAFECTAS: {{ currency_type.symbol }}
                                    {{ form.total_unaffected }}
                                </p>
                                <p
                                    class="text-end"
                                    v-if="form.total_exonerated > 0"
                                >
                                    OP.EXONERADAS: {{ currency_type.symbol }}
                                    {{ form.total_exonerated }}
                                </p>
                                <p class="text-end" v-if="form.total_taxed > 0">
                                    OP.GRAVADA: {{ currency_type.symbol }}
                                    {{ form.total_taxed }}
                                </p>
                                <p class="text-end" v-if="form.total_igv > 0">
                                    IGV: {{ currency_type.symbol }}
                                    {{ form.total_igv }}
                                </p>
                                <p
                                    class="text-end"
                                    v-if="
                                        form.total_discount > 0 &&
                                        this.isProject
                                    "
                                >
                                    DESCUENTOS TOTALES:
                                    {{ currency_type.symbol }}
                                    {{
                                        form.total_discount.toFixed(
                                            decimalQuantity
                                        )
                                    }}
                                </p>
                                <h3 class="text-end" v-if="form.total > 0">
                                    <b>TOTAL A PAGAR: </b
                                    >{{ currency_type.symbol }} {{ form.total }}
                                </h3>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions text-end mt-4">
                        <el-button @click.prevent="close()">Cancelar</el-button>
                        <el-button
                            class="submit"
                            type="primary"
                            native-type="submit"
                            :loading="loading_submit"
                            v-if="form.items.length > 0"
                            >Guardar cambios
                        </el-button>
                    </div>
                </form>
            </div>
        </div>

        <quotation-form-item
            :showDialog.sync="showDialogAddItem"
            :currency-type-id-active="form.currency_type_id"
            :exchange-rate-sale="form.exchange_rate_sale"
            :recordItem="recordItem"
            :configuration="config"
            :typeUser="typeUser"
            :customer-id="form.customer_id"
            :percentage-igv="percentage_igv"
            :currency-types="currency_types"
            :show-option-change-currency="true"
            @add="addRow"
            :headers.sync="headers"
        ></quotation-form-item>

        <person-form
            :showDialog.sync="showDialogNewPerson"
            type="customers"
            :external="true"
            :document_type_id="form.document_type_id"
        ></person-form>

        <quotation-options
            :type="type"
            :showDialog.sync="showDialogOptions"
            :recordId="quotationNewId"
            :showGenerate="false"
            :typeUser="typeUser"
            :showClose="false"
        ></quotation-options>

        <terms-condition
            :showDialog.sync="showDialogTermsCondition"
            :form="form"
            :showClose="false"
        ></terms-condition>
    </div>
</template>
<style>
.ck.ck-editor {
    border: 1px solid #e7e3e3;
}
/* Ocultar el botón de Insertar imagen */
.ck-file-dialog-button {
    display: none;
}

/* Ocultar el botón de Insertar media */
.ck.ck-dropdown {
    display: none;
}
</style>
<script>
import TermsCondition from "./partials/terms_condition.vue";
import QuotationFormItem from "./partials/item.vue";
import PersonForm from "../persons/form.vue";
import QuotationOptions from "../quotations/partials/options.vue";
import { exchangeRate, functions } from "../../../mixins/functions";
import {
    calculateRowItem,
    showNamePdfOfDescription,
    sumAmountDiscountsNoBaseByItem,
} from "../../../helpers/functions";
import Logo from "../companies/logo.vue";
import { mapActions, mapState } from "vuex/dist/vuex.mjs";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import VueCkeditor from "vue-ckeditor5";
export default {
    components: {
        QuotationFormItem,
        PersonForm,
        QuotationOptions,
        Logo,
        TermsCondition,
        "vue-ckeditor": VueCkeditor.component,
    },
    props: {
        resourceId: {
            required: true,

            default: 0,
        },
        typeUser: {
            required: true,
        },
        configuration: {
            required: true,
        },
        quotations_optional: {
            required: true,
        },
        quotations_optional_value: {
            required: true,
        },
    },
    mixins: [functions, exchangeRate],
    data() {
        return {
            editors: {
                classic: ClassicEditor,
            },
            decimalQuantity: 2,
            headers: [],
            total_global_discount: 0,
            showDialogTermsCondition: false,
            type: "edit",
            resource: "quotations",
            showDialogAddItem: false,
            showDialogNewPerson: false,
            showDialogOptions: false,
            loading_submit: false,
            loading_form: false,
            errors: {},
            form: {},
            currency_types: [],
            discount_types: [],
            charges_types: [],
            all_customers: [],
            customers: [],
            company: null,
            establishments: [],
            establishment: null,
            currency_type: {},
            customer_addresses: [],
            quotationNewId: null,
            payment_method_types: [],
            activePanel: 0,
            payment_destinations: [],
            /* configuration: {}, */
            loading_search: false,
            recordItem: null,
            sellers: [],
            total_discount_no_base: 0,
            isProject: false,
        };
    },
    async created() {
        this.loadConfiguration();
        this.$store.commit("setConfiguration", this.configuration);
 
        await this.initForm();
        await this.$http.get(`/${this.resource}/tables`).then((response) => {
            this.currency_types = response.data.currency_types;
            this.establishments = response.data.establishments;
            this.all_customers = response.data.customers;
            this.discount_types = response.data.discount_types;
            this.charges_types = response.data.charges_types;
            this.company = response.data.company;
            this.form.currency_type_id =
                this.currency_types.length > 0
                    ? this.currency_types[0].id
                    : null;
            this.form.establishment_id =
                this.establishments.length > 0
                    ? this.establishments[0].id
                    : null;
            this.payment_method_types = response.data.payment_method_types;
            this.payment_destinations = response.data.payment_destinations;
            /* this.configuration = response.data.configuration */
            this.sellers = response.data.sellers;

            this.changeEstablishment();
            this.changeDateOfIssue();
            this.changeCurrencyType();
            this.allCustomers();
            this.initRecord();
            this.selectDestinationSale();
        });
        await this.getPercentageIgv();
        this.loading_form = true;
        this.$eventHub.$on("reloadDataPersons", (customer_id) => {
            this.reloadDataCustomers(customer_id);
        });

        this.$eventHub.$on("initInputPerson", () => {
            this.initInputPerson();
        });
    },
    computed: {
        ...mapState(["config"]),
    },
    methods: {
        setHeaders() {
            let items = _.orderBy(this.form.items, ["header"], ["asc"]);
           
            let itemsSC = _.filter(items, { header: "S/C" });
            let itemsNotSC = _.filter(items, (item) => {
                return item.header != "S/C";
            });
            this.form.items = [...itemsNotSC, ...itemsSC];
        },
        changeTotalGlobalDiscount() {
            this.calculateTotal();
        },
        checkHeader(row, index) {
            if (!row.header) {
                return {
                    header: "-",
                };
            }
            if (index == 0 && row.header) {
                return {
                    header: row.header,
                };
            }
            if (index != 0 && row.header) {
                let same = this.form.items[index - 1].header == row.header;
                if (same) {
                    return {
                        header: null,
                    };
                } else {
                    let indexHeader = _.findIndex(this.headers, {
                        header: row.header,
                    });
                    if (indexHeader == -1) {
                        indexHeader = this.headers.length;
                    }
                    return {
                        header: row.header,
                    };
                }
            }
        },
        ...mapActions(["loadConfiguration"]),
        clickAddItem() {
            this.recordItem = null;
            this.showDialogAddItem = true;
        },
        ediItem(row, index) {
            row.indexi = index;
            this.recordItem = row;
            this.showDialogAddItem = true;
        },
        changeCustomer() {
            this.customer_addresses = [];
            let customer = _.find(this.customers, {
                id: this.form.customer_id,
            });
            if (customer === undefined) {
                let parameters = `customer_id=${this.form.customer_id}`;
                let obj = this;
                this.$http
                    .get(`/${this.resource}/search/customers?${parameters}`)
                    .then((response) => {
                        response.data.customers.forEach((row) => {
                            this.customers.push(row);
                        });
                    })
                    .then(() => {
                        customer = _.find(this.customers, {
                            id: this.form.customer_id,
                        });
                        this.setCustomerAddress(customer);
                    });
            } else {
                this.setCustomerAddress(customer);
            }
        },
        setCustomerAddress(customer) {
            this.customer_addresses = customer.addresses;
            if (customer.address) {
                if (_.find(this.customer_addresses, { id: null })) return;
                this.customer_addresses.unshift({
                    id: null,
                    address: customer.address,
                });
            }
        },
        selectDestinationSale() {
            if (
                this.configuration.destination_sale &&
                this.payment_destinations.length > 0
            ) {
                let cash = _.find(this.payment_destinations, { id: "cash" });
                this.form.payments[0].payment_destination_id = cash
                    ? cash.id
                    : this.payment_destinations[0].id;
            }
        },
        getPaymentDestinationId() {
            if (
                this.configuration.destination_sale &&
                this.payment_destinations.length > 0
            ) {
                let cash = _.find(this.payment_destinations, { id: "cash" });

                return cash ? cash.id : this.payment_destinations[0].id;
            }

            return null;
        },
        setTotalDefaultPayment() {
            if (this.form.payments.length > 0) {
                this.form.payments[0].payment = this.form.total;
            }
        },
        changeTermsCondition() {
            if (this.form.active_terms_condition) {
                this.showDialogTermsCondition = true;
            } else {
                this.form.terms_condition = null;
            }
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
            });

            this.setTotalDefaultPayment();
        },
        clickCancel(index) {
            this.form.payments.splice(index, 1);
        },
        getFormatUnitPriceRow(unit_price) {
            return _.round(unit_price, 6);
            // return unit_price.toFixed(6)
        },
        async changePaymentMethodType(flag_submit = true) {
            // let payment_method_type = await _.find(this.payment_method_types, {'id':this.form.payment_method_type_id})
            // if(payment_method_type){
            //     if(payment_method_type.number_days){
            //         this.form.date_of_issue =  moment().add(payment_method_type.number_days,'days').format('YYYY-MM-DD');
            //         this.changeDateOfIssue()
            //     }
            // else{
            //     if(flag_submit){
            //         this.form.date_of_issue = moment().format('YYYY-MM-DD')
            //         this.changeDateOfIssue()
            //     }
            // }
            // }
        },
        initRecord() {
            this.$http
                .get(`/${this.resource}/record/${this.resourceId}`)
                .then((response) => {
                    let dato = response.data.data.quotation;

                    this.form.id = dato.id;
                    this.form.customer_id = dato.customer_id;
                    this.form.currency_type_id = dato.currency_type_id;
                    this.form.payment_method_type_id =
                        dato.payment_method_type_id;
                        this.form.prefix = dato.prefix;
                    this.form.date_of_due = dato.date_of_due;
                    this.form.date_of_issue = dato.date_of_issue;
                    this.form.delivery_date = dato.delivery_date;
                    this.form.exchange_rate_sale = dato.exchange_rate_sale;
                    this.form.description = dato.description;
                    this.form.shipping_address = dato.shipping_address;
                    this.form.account_number = dato.account_number;
                    this.form.terms_condition = dato.terms_condition;
                    this.form.seller_id = dato.seller_id;
                    this.form.active_terms_condition = dato.terms_condition
                        ? true
                        : false;
                    this.form.items = this.onPrepareItems(dato.items);

                    // this.form.items = dato.items
                    this.form.payments = dato.payments;
                    this.form.referential_information =
                        dato.referential_information;
                    this.changeCustomer();
                    this.form.customer_address_id = dato.customer.address_id;
                    if (dato.project) {
                        this.setHeaders();
                        if (dato.discounts) {
                            let { discounts } = dato;
                       
                            discounts = Object.values(discounts);
                            let [discount] = discounts;
                            if (discount) {
                                this.total_global_discount =
                                    discount.factor * 100;
                                this.form.discounts = discounts;
                            }
                        }
                        this.isProject = true;
                        delete dato.project.id;
                        this.form = {
                            ...this.form,
                            ...dato.project,
                        };

                        if (!this.form.observations)
                            this.form.observations = "";
                         
                    }
                    console.log(this.form.items);
                    this.calculateTotal();
                    //console.log(response.data)
                });
        },
        onPrepareItems(items) {
            return items.map((item) => {
                item.discounts = item.discounts
                    ? Object.values(item.discounts)
                    : [];
                if (item.project_item) {
                    item = {
                        ...item,
                        ...item.project_item,
                    };
                        console.log("🚀 ~ file: form_edit.vue:1531 ~ returnitems.map ~ item.project_item:", item.project_item)
                }
                return item;
            });
        },

        searchRemoteCustomers(input) {
            if (input.length > 0) {
                this.loading_search = true;
                let parameters = `input=${input}`;

                this.$http
                    .get(`/${this.resource}/search/customers?${parameters}`)
                    .then((response) => {
                        this.customers = response.data.customers;
                        this.loading_search = false;
                        /* if(this.customers.length == 0){this.allCustomers()} */
                        this.input_person.number =
                            this.customers.length == 0 ? input : null;
                    });
            } else {
                this.allCustomers();
                this.input_person.number = null;
            }
        },
        initForm() {
            this.errors = {};
            this.form = {
                id: 0,
                description: "",
                prefix: "COT",
                establishment_id: null,
                date_of_issue: moment().format("YYYY-MM-DD"),
                time_of_issue: moment().format("HH:mm:ss"),
                customer_id: null,
                currency_type_id: null,
                customer_address_id: null,
                purchase_order: null,
                exchange_rate_sale: 0,
                total_prepayment: 0,
                total_charge: 0,
                total_discount: 0,
                total_exportation: 0,
                total_free: 0,
                total_taxed: 0,
                total_unaffected: 0,
                total_exonerated: 0,
                total_igv_free: 0,
                total_igv: 0,
                total_base_isc: 0,
                total_isc: 0,
                total_base_other_taxes: 0,
                total_other_taxes: 0,
                total_taxes: 0,
                total_value: 0,
                subtotal: 0,
                total: 0,
                payment_method_type_id: null,
                operation_type_id: null,
                date_of_due: moment().format("YYYY-MM-DD"),
                delivery_date: null,
                items: [],
                charges: [],
                discounts: [],
                attributes: [],
                guides: [],
                shipping_address: null,
                additional_information: null,
                account_number: null,
                terms_condition: null,
                active_terms_condition: false,
                referential_information: "",
                payments: [],
                actions: {
                    format_pdf: "a4",
                },
                contact: null,
                phone: null,
                seller_id: null,
                quotations_optional: this.quotations_optional,
                quotations_optional_value: this.quotations_optional_value,
            };

            this.total_discount_no_base = 0;

            this.clickAddPayment();
            this.initInputPerson();
        },
        resetForm() {
            this.activePanel = 0;
            this.initForm();
            this.form.currency_type_id =
                this.currency_types.length > 0
                    ? this.currency_types[0].id
                    : null;
            this.form.establishment_id =
                this.establishments.length > 0
                    ? this.establishments[0].id
                    : null;
            this.changeEstablishment();
            this.changeDateOfIssue();
            this.changeCurrencyType();
            this.allCustomers();
            this.customer_addresses = [];
        },
        changeEstablishment() {
            this.establishment = _.find(this.establishments, {
                id: this.form.establishment_id,
            });
        },
        cleanCustomer() {
            this.form.customer_id = null;
        },
        async changeDateOfIssue() {
            this.form.date_of_due = this.form.date_of_issue;
            await this.searchExchangeRateByDate(this.form.date_of_issue).then(
                (response) => {
                    this.form.exchange_rate_sale = response;
                }
            );
            await this.getPercentageIgv();
            this.changeCurrencyType();
        },
        allCustomers() {
            this.customers = this.all_customers;
        },
        addRow(row) {
            if (this.recordItem) {
                this.form.items[this.recordItem.indexi] = row;
                this.recordItem = null;
            } else {
                this.form.items.push(JSON.parse(JSON.stringify(row)));
            }

            this.calculateTotal();
            if (this.isProject) {
                this.setHeaders();
            }
        },
        clickRemoveItem(index) {
            this.form.items.splice(index, 1);
            this.calculateTotal();
        },
        changeCurrencyType() {
            this.currency_type = _.find(this.currency_types, {
                id: this.form.currency_type_id,
            });
            let items = [];
            this.form.items.forEach((row) => {
                let newItem =
                    calculateRowItem(
                        row,
                        this.form.currency_type_id,
                        this.form.exchange_rate_sale,
                        this.percentage_igv
                    )
                if(this.isProject){
                newItem.header = row.header;
                newItem.disponibility = row.disponibility;
                }
                items.push(newItem);

            });
            this.form.items = items;
            this.calculateTotal();
        },
        calculateTotal() {
            let total_discount = 0;
            let total_charge = 0;
            let total_exportation = 0;
            let total_taxed = 0;
            let total_exonerated = 0;
            let total_unaffected = 0;
            let total_free = 0;
            let total_igv = 0;
            let total_value = 0;
            let total = 0;
            let total_igv_free = 0;
            this.total_discount_no_base = 0;

            this.form.items.forEach((row) => {
                total_discount += parseFloat(row.total_discount);
                total_charge += parseFloat(row.total_charge);

                if (row.affectation_igv_type_id === "10") {
                    total_taxed += parseFloat(row.total_value);
                }
                if (row.affectation_igv_type_id === "20") {
                    total_exonerated += parseFloat(row.total_value);
                }
                if (row.affectation_igv_type_id === "30") {
                    total_unaffected += parseFloat(row.total_value);
                }
                if (row.affectation_igv_type_id === "40") {
                    total_exportation += parseFloat(row.total_value);
                }
                if (
                    ["10", "20", "30", "40"].indexOf(
                        row.affectation_igv_type_id
                    ) < 0
                ) {
                    total_free += parseFloat(row.total_value);
                }
                if (
                    ["10", "20", "30", "40"].indexOf(
                        row.affectation_igv_type_id
                    ) > -1
                ) {
                    total_igv += parseFloat(row.total_igv);
                    total += parseFloat(row.total);
                }
                total_value += parseFloat(row.total_value);

                if (
                    ["11", "12", "13", "14", "15", "16"].includes(
                        row.affectation_igv_type_id
                    )
                ) {
                    let unit_value = row.total_value / row.quantity;
                    let total_value_partial = unit_value * row.quantity;
                    row.total_taxes = row.total_value - total_value_partial;
                    row.total_igv =
                        total_value_partial * (row.percentage_igv / 100);
                    row.total_base_igv = total_value_partial;
                    total_value -= row.total_value;
                    total_igv_free += row.total_igv;
                }

                this.total_discount_no_base +=
                    sumAmountDiscountsNoBaseByItem(row);
            });

            this.form.total_igv_free = _.round(total_igv_free, 2);
            this.form.total_discount = _.round(total_discount, 2);
            this.form.total_exportation = _.round(total_exportation, 2);
            this.form.total_taxed = _.round(total_taxed, 2);
            this.form.total_exonerated = _.round(total_exonerated, 2);
            this.form.total_unaffected = _.round(total_unaffected, 2);
            this.form.total_free = _.round(total_free, 2);
            this.form.total_igv = _.round(total_igv, 2);
            this.form.total_value = _.round(total_value, 2);
            this.form.total_taxes = _.round(total_igv, 2);

            this.form.subtotal = _.round(total, 2);
            this.form.total = _.round(total - this.total_discount_no_base, 2);
            if (this.isProject) {
                this.discountGlobal();
            }
            // this.form.total = _.round(total, 2)

            this.setTotalDefaultPayment();
        },
        discountGlobal() {
            this.deleteDiscountGlobal();

            //input donde se ingresa monto o porcentaje
            let input_global_discount = parseFloat(this.total_global_discount);

            if (input_global_discount > 0) {
                let base = parseFloat(this.form.total);
                let amount = 0;
                let factor = 0;

                factor = _.round(input_global_discount / 100, 5);
                amount = factor * base;

                this.form.total_discount = _.round(amount, 2);

                this.form.total = _.round(this.form.total - amount, 2);

                this.setGlobalDiscount(factor, _.round(amount, 2), base);
            }
        },
        deleteDiscountGlobal() {
            let discount = _.find(this.form.discounts, {
                discount_type_id: this.config.global_discount_type_id,
            });
            let index = this.form.discounts.indexOf(discount);

            if (index > -1) {
                this.form.discounts.splice(index, 1);
                this.form.total_discount = 0;
            }
        },
        setGlobalDiscount(factor, amount, base) {
            this.form.discounts.push({
                discount_type_id: "03",
                description:
                    "Descuentos globales que no afectan la base imponible del IGV/IVAP",
                factor: factor,
                amount: amount,
                base: base,
                is_amount: false,
            });
        },
        validate_payments() {
            //eliminando items de pagos
            for (let index = 0; index < this.form.payments.length; index++) {
                if (parseFloat(this.form.payments[index].payment) === 0)
                    this.form.payments.splice(index, 1);
            }

            let error_by_item = 0;
            let acum_total = 0;

            this.form.payments.forEach((item) => {
                acum_total += parseFloat(item.payment);
                if (item.payment <= 0 || item.payment == null) error_by_item++;
            });

            return {
                error_by_item: error_by_item,
                acum_total: acum_total,
            };
        },
        validatePaymentDestination() {
            let error_by_item = 0;

            this.form.payments.forEach((item) => {
                if (item.payment_destination_id == null) error_by_item++;
            });

            return {
                error_by_item: error_by_item,
            };
        },
        async submit() {
            // await this.changePaymentMethodType(false)

            let validate = await this.validate_payments();
            if (
                validate.acum_total > parseFloat(this.form.total) ||
                validate.error_by_item > 0
            ) {
                return this.$message.error(
                    "Los montos ingresados superan al monto a pagar o son incorrectos"
                );
            }

            let validate_payment_destination =
                await this.validatePaymentDestination();

            if (validate_payment_destination.error_by_item > 0) {
                return this.$message.error(
                    "El destino del pago es obligatorio"
                );
            }
            // if(this.form.date_of_issue > this.form.date_of_due)
            //     return this.$message.error('La fecha de emisión no puede ser posterior a la de vencimiento');

            // if(this.form.date_of_issue > this.form.delivery_date)
            //     return this.$message.error('La fecha de emisión no puede ser posterior a la de entrega');

            this.loading_submit = true;
            await this.$http
                .post(`/${this.resource}/update`, this.form)
                .then((response) => {
                    if (response.data.success) {
                        this.resetForm();
                        this.quotationNewId = response.data.data.id;
                        this.$message.success(
                            "Se guardaron los cambios correctamente."
                        );
                        this.showDialogOptions = true;
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch((error) => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data;
                    } else {
                        this.$message.error(error.response.data.message);
                    }
                })
                .then(() => {
                    this.loading_submit = false;
                });
        },
        close() {
            location.href = "/quotations";
        },
        reloadDataCustomers(customer_id) {
            this.$http
                .get(`/${this.resource}/search/customer/${customer_id}`)
                .then((response) => {
                    this.customers = response.data.customers;
                    this.form.customer_id = customer_id;
                });
        },
        setDescriptionOfItem(item) {
            return showNamePdfOfDescription(item, this.config.show_pdf_name);
        },
        keyupCustomer() {
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
        initInputPerson() {
            this.input_person = {
                number: null,
                identity_document_type_id: null,
            };
        },
    },
};
</script>

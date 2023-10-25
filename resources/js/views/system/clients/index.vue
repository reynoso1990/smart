<template>
    <div>
        <!-- <header class="page-header">
            <h2>
                <a href="/dashboard">
                    <i class="fa fa-list-alt"></i>
                </a>
            </h2>
            <ol class="breadcrumbs">
                <li class="active">
                    <span>Dashboard</span>
                </li>
            </ol>
        </header> -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div
                                    class="chart-data-selector ready pl-3 pr-4 pt-4"
                                >
                                    <div class="chart-data-selector-items">
                                        <chart-line
                                            v-if="loaded"
                                            :data="dataChartLine"
                                        ></chart-line>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row px-4 mt-2 pb-3">
                            <div class="col-2 font-weight-bold text-primary">
                                {{ year }}
                            </div>
                            <div class="col-10 font-weight-semibold text-end">
                                Comprobantes generados por mes
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="row">
                    <div class="col-md-12 mb-5">
                        <section class="card card-horizontal p-0">
                            <header class="card-header bg-success">
                                <div class="card-header-icon text-center">
                                    <i class="fas fa-users"></i>
                                </div>
                            </header>
                            <div class="card-body p-4 text-center">
                                <p class="font-weight-semibold mb-0 mx-4">
                                    Total Clientes
                                </p>
                                <h2 class="font-weight-semibold mt-0">
                                    {{
                                        records.filter((r) => r.active == false)
                                            .length
                                    }}
                                </h2>
                                <div class="summary-footer">
                                    <a
                                        class="text-muted text-uppercase"
                                        href="#client-list"
                                        >Ver todos</a
                                    >
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="col-md-12 mb-0">
                        <section class="card card-horizontal p-0">
                            <header class="card-header bg-info">
                                <div class="card-header-icon text-center">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                            </header>
                            <div class="card-body p-4 text-center">
                                <p class="font-weight-semibold mb-0 mt-3">
                                    Total Comprobantes
                                </p>
                                <h2 class="font-weight-semibold mt-0 mb-3">
                                    {{ total_documents }}
                                </h2>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-3">
                <section
                    class="card card-featured-left card-featured-primary mb-4"
                >
                    <div class="card-body">
                        <div class="widget-summary widget-summary-md">
                            <div
                                class="widget-summary-col widget-summary-col-icon"
                            >
                                <div class="summary-icon text-secondary">
                                    <div
                                        :data-value="discUsed"
                                        class="progress1 mx-auto"
                                    >
                                        <span class="progress1-left">
                                            <span
                                                class="progress1-bar border-primary"
                                            ></span>
                                        </span>
                                        <span class="progress1-right">
                                            <span
                                                class="progress1-bar border-primary"
                                            ></span>
                                        </span>
                                        <div
                                            class="progress1-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center"
                                        >
                                            <div
                                                class="font-weight-bold text-center"
                                            >
                                                {{ discUsed
                                                }}<small class="small"></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-summary-col">
                                <div class="summary">
                                    <h4 class="title">
                                        <!-- Disco <br> Duro -->
                                    </h4>
                                    <div class="info">
                                        <strong class="amount"
                                            >Disco Duro</strong
                                        ><br />
                                        <!-- <span class="text-warning" v-if="discUsed == 0">no se pudo obtener</span> -->
                                    </div>
                                </div>
                                <div class="summary-footer d-block">
                                    <a
                                        class="text-muted text-uppercase"
                                        href="https://docs.google.com/document/d/1hpEQUs9OFha_35yyLb1cMKeluD-dEku5lQsQ3TJFib8/edit"
                                        target="BLANK"
                                        >Incrementar</a
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="col-md-3">
                <section
                    class="card card-featured-left card-featured-primary mb-4"
                >
                    <div class="card-body">
                        <div class="widget-summary widget-summary-md">
                            <div
                                class="widget-summary-col widget-summary-col-icon"
                            >
                                <div class="summary-icon text-secondary">
                                    <div
                                        :data-value="iUsed"
                                        class="progress1 mx-auto"
                                    >
                                        <span class="progress1-left">
                                            <span
                                                class="progress1-bar border-primary"
                                            ></span>
                                        </span>
                                        <span class="progress1-right">
                                            <span
                                                class="progress1-bar border-primary"
                                            ></span>
                                        </span>
                                        <div
                                            class="progress1-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center"
                                        >
                                            <div class="font-weight-bold">
                                                {{ iUsed
                                                }}<small class="small"></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-summary-col">
                                <div class="summary text-center">
                                    <h4 class="title">
                                        <!-- Disco <br> Duro -->
                                    </h4>
                                    <div class="info">
                                        <strong class="amount">Inodes</strong>
                                        <!-- <span class="text-primary">(14 unread)</span> -->
                                    </div>
                                </div>
                                <div class="summary-footer d-block text-center">
                                    <a
                                        class="text-muted text-uppercase"
                                        href="https://drive.google.com/open?id=1foPKDI3V3Z9uKTjRc2SPSoztVSOBevPAluT2BqFbfxA"
                                        target="BLANK"
                                        >Limpiar</a
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="col-md-3">
                <section
                    class="card card-featured-left card-featured-primary mb-4"
                >
                    <div class="card-body">
                        <div class="widget-summary widget-summary-md">
                            <div
                                class="widget-summary-col widget-summary-col-icon"
                            >
                                <div class="summary-icon text-secondary">
                                    <div
                                        class="progress1 mx-auto"
                                        data-value="100"
                                    >
                                        <span class="progress1-left">
                                            <span
                                                class="progress1-bar border-tertiary"
                                            ></span>
                                        </span>
                                        <span class="progress1-right">
                                            <span
                                                class="progress1-bar border-tertiary"
                                            ></span>
                                        </span>
                                        <div
                                            class="progress1-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center"
                                        >
                                            <div class="font-weight-bold">
                                                {{ storageSize
                                                }}<small class="small"></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-summary-col">
                                <div class="summary text-center">
                                    <h4 class="title">
                                        <!-- Disco <br> Duro -->
                                    </h4>
                                    <div class="info">
                                        <strong class="amount"
                                            >Archivos <br />
                                            Generados</strong
                                        >
                                        <!-- <span class="text-primary">(14 unread)</span> -->
                                    </div>
                                </div>
                                <div class="summary-footer">
                                    <!-- <a class="text-muted text-uppercase">(view all)</a> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="col-md-3">
                <section
                    class="card card-featured-left card-featured-primary mb-4"
                >
                    <div class="card-body">
                        <div class="widget-summary widget-summary-md">
                            <div
                                class="widget-summary-col widget-summary-col-icon"
                            >
                                <div
                                    class="summary-icon text-center"
                                    style="background-color: rgb(41, 41, 97)"
                                >
                                    <i class="fab fa-gitlab"></i>
                                </div>
                            </div>
                            <div class="widget-summary-col">
                                <div class="summary text-center">
                                    <h4 class="title">
                                        <!-- Disco <br> Duro -->
                                    </h4>
                                    <div class="info">
                                        <strong class="amount">Versión</strong
                                        ><br /><br />
                                        <span class="text-primary">{{
                                            version
                                        }}</span>
                                    </div>
                                </div>
                                <div class="summary-footer">
                                    <!-- <a class="text-muted text-uppercase">(view all)</a> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <div id="client-list" class="card mt-5">
            <div class="card-header">Listado de Clientes</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <el-button
                            type="primary"
                            @click.prevent="clickCreate()"
                        >
                            <i class="fa fa-plus-circle"></i> Nuevo
                        </el-button>
                        <el-button
                            type="primary"
                            @click.prevent="clickOpenMail"
                        >
                            <i class="fa fa-plus-circle"></i> Nuevo Correo
                        </el-button>
                        <el-button
                            type="primary"
                            @click.prevent="clickOpenUser"
                        >
                            <i class="fa fa-plus-circle"></i> Nuevo
                            administrador
                        </el-button>
                    </div>
                    <div class="col-6 d-flex justify-content-end">
                        <div class="data-table-visible-columns">
                            <el-dropdown :hide-on-click="false">
                                <el-button type="primary">
                                    Mostrar/Ocultar columnas<i
                                        class="el-icon-arrow-down el-icon--right"
                                    ></i>
                                </el-button>
                                <el-dropdown-menu slot="dropdown">
                                    <el-dropdown-item
                                        v-for="(column, index) in columns"
                                        :key="index"
                                    >
                                        <el-checkbox
                                            @change="setColumns"
                                            v-if="
                                                column.title !== undefined &&
                                                column.visible !== undefined
                                            "
                                            v-model="column.visible"
                                            >{{ column.title }}
                                        </el-checkbox>
                                    </el-dropdown-item>
                                </el-dropdown-menu>
                            </el-dropdown>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>

                                <th v-if="columns.hostname.visible" scope="col">
                                    Hostname
                                </th>
                                <th v-if="columns.name.visible" scope="col">
                                    Nombre
                                </th>
                                <th v-if="columns.number.visible" scope="col">
                                    RUC
                                </th>
                                <th v-if="columns.plan.visible" scope="col">
                                    Plan
                                </th>
                                <th v-if="columns.email.visible" scope="col">
                                    Correo
                                </th>
                                <th
                                    v-if="columns.soap_type.visible"
                                    scope="col"
                                >
                                    Entorno
                                </th>
                                <th
                                    v-if="columns.count_doc.visible"
                                    scope="col"
                                    class="text-center"
                                >
                                    Total de Comprobantes
                                </th>
                                <th
                                    v-if="columns.notifications.visible"
                                    scope="col"
                                    class="text-center"
                                >
                                    Notificaciones
                                </th>
                                <th
                                    v-if="columns.start_billing_cycle.visible"
                                    scope="col"
                                    class="text-end"
                                >
                                    Inicio Ciclo Facturacion
                                </th>
                                <th
                                    v-if="columns.count_doc_month.visible"
                                    scope="col"
                                    class="text-center"
                                >
                                    Facturacion
                                </th>
                                <th
                                    v-if="columns.count_item.visible"
                                    scope="col"
                                    class="text-center"
                                >
                                    Productos
                                </th>
                                <th
                                    v-if="columns.count_user.visible"
                                    scope="col"
                                    class="text-center"
                                >
                                    Usuarios
                                </th>

                                <th
                                    v-if="columns.count_establishments.visible"
                                    scope="col"
                                    class="text-center"
                                >
                                    Establecimientos
                                </th>

                                <th
                                    v-if="columns.monthly_sales_total.visible"
                                    scope="col"
                                    class="text-center"
                                >
                                    Ventas (Mes)
                                </th>

                                <th
                                    v-if="columns.created_at.visible"
                                    scope="col"
                                    class="text-center"
                                >
                                    F.Creación
                                </th>
                                <th
                                    v-if="columns.queries_to_apiperu.visible"
                                    scope="col"
                                    class="text-center"
                                >
                                    API Peru (mes)
                                </th>

                                <th
                                    v-if="columns.count_sales_notes.visible"
                                    scope="col"
                                    class="text-center"
                                >
                                    Cant.Notas de venta
                                </th>
                                <th
                                    v-if="columns.total_doc.visible"
                                    scope="col"
                                    class="text-center"
                                >
                                    Total (Comprobantes)
                                </th>
                                <th
                                    v-if="columns.active.visible"
                                    scope="col"
                                    class="text-center"
                                >
                                    Baja
                                </th>
                                <th
                                    v-if="columns.locked.visible"
                                    scope="col"
                                    class="text-center"
                                >
                                    Bloquear cuenta
                                </th>
                                <!-- <th
                                    v-if="columns.config_system_env.visible"
                                    scope="col"
                                    class="text-center"
                                >
                                    Bloquear Entorno demo
                                </th> -->
                                <th
                                    v-if="columns.max_items.visible"
                                    scope="col"
                                    class="text-end"
                                >
                                    Limitar Prod.
                                </th>
                                <th
                                    v-if="columns.max_documents.visible"
                                    scope="col"
                                    class="text-end"
                                >
                                    Limitar Doc.
                                </th>
                                <th
                                    v-if="columns.max_users.visible"
                                    scope="col"
                                    class="text-center"
                                >
                                    Limitar Usuarios
                                </th>

                                <th
                                    v-if="columns.max_establishments.visible"
                                    scope="col"
                                    class="text-center"
                                >
                                    Limitar Establecimientos
                                </th>

                                <th
                                    v-if="columns.max_sales_limit.visible"
                                    scope="col"
                                    class="text-center"
                                >
                                    <el-tooltip
                                        class="item"
                                        content="Límite de ventas mensual asociado al ciclo de facturación"
                                        effect="dark"
                                        placement="top"
                                    >
                                        <label>Limitar Ventas (Mes)</label>
                                    </el-tooltip>
                                </th>
                                <th v-if="columns.user.visible" scope="col">
                                    Usuario
                                </th>
                                <th v-if="columns.password.visible" scope="col">
                                    Clave
                                </th>
                                <th
                                    v-if="columns.password_cdt.visible"
                                    scope="col"
                                >
                                    Clave CDT
                                </th>
                                <th scope="col" class="text-end">Acciones</th>
                                <th scope="col" class="text-end">Pagos</th>
                                <th scope="col" class="text-end">E. Cuenta</th>
                                <th scope="col" class="text-end">Editar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(row, index) in records" :key="index">
                                <th scope="row">{{ index + 1 }}</th>
                                <td v-if="columns.hostname.visible">
                                    <a
                                        :href="`http://${row.hostname}`"
                                        style="color: black"
                                        target="_blank"
                                    >
                                        {{ row.hostname }}</a
                                    >
                                </td>
                                <td v-if="columns.name.visible">
                                    {{ row.name }}
                                </td>
                                <td v-if="columns.number.visible">
                                    {{ row.number }}
                                </td>
                                <td v-if="columns.plan.visible">
                                    {{ row.plan }}
                                </td>
                                <td v-if="columns.email.visible">
                                    {{ row.email }}
                                </td>
                                <td v-if="columns.soap_type.visible">
                                    <span
                                        v-if="row.soap_type == '01'"
                                        class="text-black badge badge-default"
                                        >Demo</span
                                    >
                                    <span
                                        v-if="row.soap_type == '02'"
                                        class="text-black badge badge-success"
                                        >Producción</span
                                    >
                                    <span
                                        v-if="row.soap_type == '03'"
                                        class="text-black badge badge-info"
                                        >Interno</span
                                    >
                                </td>
                                <td
                                    v-if="columns.count_doc.visible"
                                    class="text-center"
                                >
                                    <label>
                                        <strong>{{ row.count_doc }}</strong>
                                    </label>
                                </td>

                                <td
                                    class="text-center"
                                    v-if="columns.notifications.visible"
                                >
                                    <div class="row justify-content-between">
                                        <div class="col-4">
                                            <template
                                                v-if="row.soap_type == '02'"
                                            >
                                                <el-tooltip
                                                    class="item"
                                                    content="Comprobantes enviados / por enviar"
                                                    effect="dark"
                                                    placement="top-start"
                                                >
                                                    <el-badge
                                                        :value="
                                                            row.document_not_sent
                                                        "
                                                        class="item"
                                                        :type="
                                                            row.document_not_sent ==
                                                            0
                                                                ? 'primary'
                                                                : 'danger'
                                                        "
                                                    >
                                                        <i
                                                            class="far fa-bell text-secondary"
                                                        ></i>
                                                    </el-badge>
                                                </el-tooltip>
                                            </template>
                                            <template v-else>
                                                <el-tooltip
                                                    class="item"
                                                    content="Comprobantes enviados / por enviar"
                                                    effect="dark"
                                                    placement="top-start"
                                                >
                                                    <el-badge
                                                        :value="0"
                                                        class="item"
                                                        type="primary"
                                                    >
                                                        <i
                                                            class="far fa-bell text-secondary"
                                                        ></i>
                                                    </el-badge>
                                                </el-tooltip>
                                            </template>
                                        </div>
                                        <div class="col-4">
                                            <template
                                                v-if="row.soap_type == '02'"
                                            >
                                                <el-tooltip
                                                    class="item"
                                                    content="Comprobantes pendientes de rectificación"
                                                    effect="dark"
                                                    placement="top-start"
                                                >
                                                    <el-badge
                                                        :value="
                                                            row.document_regularize_shipping
                                                        "
                                                        class="item ml-4"
                                                        :type="
                                                            row.document_regularize_shipping ==
                                                            0
                                                                ? 'primary'
                                                                : 'danger'
                                                        "
                                                    >
                                                        <i
                                                            class="fas fa-exclamation-triangle text-secondary"
                                                        ></i>
                                                    </el-badge>
                                                </el-tooltip>
                                            </template>
                                            <template v-else>
                                                <el-tooltip
                                                    class="item"
                                                    content="Comprobantes pendientes de rectificación"
                                                    effect="dark"
                                                    placement="top-start"
                                                >
                                                    <el-badge
                                                        :value="0"
                                                        class="item ml-4"
                                                        type="primary"
                                                    >
                                                        <i
                                                            class="fas fa-exclamation-triangle text-secondary"
                                                        ></i>
                                                    </el-badge>
                                                </el-tooltip>
                                            </template>
                                        </div>
                                        <div class="col-4">
                                            <template
                                                v-if="row.soap_type == '02'"
                                            >
                                                <el-tooltip
                                                    class="item"
                                                    content="Comprobantes por anular"
                                                    effect="dark"
                                                    placement="top-start"
                                                >
                                                    <el-badge
                                                        :value="
                                                            row.document_to_be_canceled
                                                        "
                                                        class="item ml-4"
                                                        :type="
                                                            row.document_to_be_canceled ==
                                                            0
                                                                ? 'primary'
                                                                : 'danger'
                                                        "
                                                    >
                                                        <i
                                                            class="fas fa-exclamation-circle text-secondary"
                                                        ></i>
                                                    </el-badge>
                                                </el-tooltip>
                                            </template>
                                            <template v-else>
                                                <el-tooltip
                                                    class="item"
                                                    content="Comprobantes por anular"
                                                    effect="dark"
                                                    placement="top-start"
                                                >
                                                    <el-badge
                                                        :value="0"
                                                        class="item ml-4"
                                                        type="primary"
                                                    >
                                                        <i
                                                            class="fas fa-exclamation-circle text-secondary"
                                                        ></i>
                                                    </el-badge>
                                                </el-tooltip>
                                            </template>
                                        </div>
                                    </div>
                                </td>

                                <td v-if="columns.start_billing_cycle.visible">
                                    <template v-if="row.start_billing_cycle">
                                        <span></span>
                                        <span>{{
                                            row.start_billing_cycle
                                        }}</span>
                                    </template>
                                    <template v-else>
                                        <el-date-picker
                                            v-model="row.select_date_billing"
                                            placeholder="..."
                                            type="date"
                                            value-format="yyyy-MM-dd"
                                            @change="
                                                setStartBillingCycle(
                                                    $event,
                                                    row.id
                                                )
                                            "
                                        ></el-date-picker>
                                    </template>
                                </td>
                                <td
                                    v-if="columns.count_doc_month.visible"
                                    class="text-center"
                                >
                                    <strong>
                                        {{
                                            row.count_doc_month
                                                ? row.count_doc_month
                                                : 0
                                        }}
                                        /
                                        <template v-if="row.max_documents == 0">
                                            <i class="fas fa-infinity"></i>
                                        </template>
                                        <template v-else>
                                            <strong>{{
                                                row.max_documents
                                            }}</strong>
                                        </template>
                                    </strong>
                                </td>
                                <td
                                    v-if="columns.count_item.visible"
                                    class="text-center"
                                >
                                    <template
                                        v-if="
                                            row.max_items !== 0 &&
                                            row.count_item > row.max_items
                                        "
                                    >
                                        <el-popover
                                            :content="text_limit_item"
                                            placement="top-start"
                                            trigger="hover"
                                            width="220"
                                        >
                                            <label
                                                slot="reference"
                                                class="text-danger"
                                            >
                                                <strong>{{
                                                    row.count_item
                                                }}</strong>
                                            </label>
                                        </el-popover>
                                    </template>
                                    <template v-else>
                                        <label>
                                            <strong>{{
                                                row.count_item
                                            }}</strong>
                                        </label>
                                    </template>
                                    /
                                    <template v-if="row.max_items == 0">
                                        <i class="fas fa-infinity"></i>
                                    </template>
                                    <template v-else>
                                        <strong>{{ row.max_items }}</strong>
                                    </template>
                                </td>
                                <td
                                    v-if="columns.count_user.visible"
                                    class="text-center"
                                >
                                    <template
                                        v-if="
                                            row.max_users !== 0 &&
                                            row.count_user > row.max_users
                                        "
                                    >
                                        <el-popover
                                            :content="text_limit_users"
                                            placement="top-start"
                                            trigger="hover"
                                            width="220"
                                        >
                                            <label
                                                slot="reference"
                                                class="text-danger"
                                            >
                                                <strong>{{
                                                    row.count_user
                                                }}</strong>
                                            </label>
                                        </el-popover>
                                    </template>
                                    <template v-else>
                                        <label>
                                            <strong>{{
                                                row.count_user
                                            }}</strong>
                                        </label>
                                    </template>
                                    /
                                    <template v-if="row.max_users == 0">
                                        <i class="fas fa-infinity"></i>
                                    </template>
                                    <template v-else>
                                        <strong>{{ row.max_users }}</strong>
                                    </template>
                                </td>

                                <td
                                    v-if="columns.count_establishments.visible"
                                    class="text-center"
                                >
                                    <data-limit-notification
                                        entity_description="establecimientos"
                                        :unlimited="
                                            row.establishments_unlimited
                                        "
                                        :quantity="row.quantity_establishments"
                                        :max_quantity="
                                            row.max_quantity_establishments
                                        "
                                    >
                                    </data-limit-notification>
                                </td>

                                <td
                                    v-if="columns.monthly_sales_total.visible"
                                    class="text-center"
                                >
                                    <data-limit-notification
                                        entity_description="ventas"
                                        style_div="width: 150px !important"
                                        :unlimited="row.sales_unlimited"
                                        :quantity="row.monthly_sales_total"
                                        :max_quantity="row.max_sales_limit"
                                    >
                                    </data-limit-notification>
                                </td>

                                <td
                                    v-if="columns.created_at.visible"
                                    class="text-center"
                                >
                                    {{ row.created_at }}
                                </td>
                                <td v-if="columns.queries_to_apiperu.visible">
                                    {{ row.queries_to_apiperu }}
                                </td>

                                <td
                                    v-if="columns.count_sales_notes.visible"
                                    class="text-center"
                                >
                                    <strong>{{ row.count_sales_notes }}</strong>
                                </td>
                                <td
                                    v-if="columns.total_doc.visible"
                                    class="text-center"
                                >
                                    <strong>{{
                                        row.count_doc_month +
                                        row.count_sales_notes_month
                                    }}</strong>
                                </td>

                                <td
                                    v-if="columns.active.visible"
                                    class="text-center"
                                >
                                    <el-switch
                                        v-model="row.active"
                                        style="display: block"
                                        @change="changeActiveTenant(row)"
                                    ></el-switch>
                                </td>
                                <td
                                    v-if="columns.locked.visible"
                                    class="text-center"
                                >
                                    <template v-if="!row.locked">
                                        <el-switch
                                            v-model="row.locked_tenant"
                                            style="display: block"
                                            @change="changeLockedTenant(row)"
                                        ></el-switch>
                                    </template>
                                </td>
                                <!-- <td
                                    v-if="columns.config_system_env.visible"
                                    class="text-center"
                                >
                                    <template v-if="!row.config_system_env">
                                        <el-switch
                                            v-model="row.config_system_env_tenant"
                                            style="display: block"
                                            @change="changeconfig_system_env_tenant(row)"
                                        ></el-switch>
                                    </template>
                                </td> -->
                                   <td
                                    v-if="columns.max_items.visible"
                                    class="text-center"
                                >
                                    <el-switch
                                        v-model="row.locked_items"
                                        style="display: block"
                                        @change="changeLockedItem(row)"
                                    ></el-switch>
                                </td>
                                <td
                                    v-if="columns.max_documents.visible"
                                    class="text-center"
                                >
                                    <el-switch
                                        v-model="row.locked_emission"
                                        style="display: block"
                                        @change="changeLockedEmission(row)"
                                    ></el-switch>
                                </td>

                                <td
                                    v-if="columns.max_users.visible"
                                    class="text-center"
                                >
                                    <el-switch
                                        v-model="row.locked_users"
                                        style="display: block"
                                        @change="changeLockedUser(row)"
                                    ></el-switch>
                                </td>

                                <td
                                    v-if="columns.max_establishments.visible"
                                    class="text-center"
                                >
                                    <el-switch
                                        v-model="
                                            row.locked_create_establishments
                                        "
                                        style="display: block"
                                        @change="
                                            changeLockedByColumn(
                                                row,
                                                'locked_create_establishments'
                                            )
                                        "
                                    ></el-switch>
                                </td>

                                <td
                                    v-if="columns.max_sales_limit.visible"
                                    class="text-center"
                                >
                                    <el-switch
                                        v-model="row.restrict_sales_limit"
                                        style="display: block"
                                        @change="
                                            changeLockedByColumn(
                                                row,
                                                'restrict_sales_limit'
                                            )
                                        "
                                    ></el-switch>
                                </td>
                                <td
                                    v-if="columns.user.visible"
                                    class="text-center"
                                >
                                    {{ row.users }}
                                </td>
                                <td
                                    v-if="columns.password.visible"
                                    class="text-center"
                                >
                                    {{ row.password }}
                                </td>
                                <td
                                    v-if="columns.password_cdt.visible"
                                    class="text-center"
                                >
                                    {{ row.password_cdt }}
                                </td>
                                <td class="text-center">
                                    <template v-if="!row.locked">
                                        <el-tooltip
                                            content="Se ingresa con el RUC"
                                            placement="top"
                                        >
                                            <button
                                                class="btn waves-effect waves-light btn-sm btn-info m-1__2"
                                                type="button"
                                                @click.prevent="
                                                    clickPassword(row.id)
                                                "
                                            >
                                                Resetear clave
                                            </button>
                                        </el-tooltip>
                                        <button
                                            v-if="deletePermission == true"
                                            class="btn waves-effect waves-light btn-sm btn-danger m-1__2"
                                            type="button"
                                            @click.prevent="clickDelete(row)"
                                        >
                                            Eliminar
                                        </button>
                                    </template>
                                </td>
                                <td class="text-end">
                                    <button
                                        class="btn waves-effect waves-light btn-sm btn-warning m-1__2"
                                        type="button"
                                        @click.prevent="clickPayments(row.id)"
                                    >
                                        Pagos
                                    </button>
                                </td>
                                <td class="text-end">
                                    <button
                                        :class="`${
                                            row.has_debt
                                                ? 'btn-danger'
                                                : 'btn-success'
                                        }`"
                                        class="btn waves-effect waves-light btn-sm m-1__2"
                                        type="button"
                                        @click.prevent="
                                            clickAccountStatus(row.id)
                                        "
                                    >
                                        E. Cuenta
                                    </button>
                                </td>
                                <td class="text-end">
                                    <button
                                        class="btn waves-effect waves-light btn-sm btn-primary m-1__2"
                                        type="button"
                                        @click.prevent="clickEdit(row.id)"
                                    >
                                        Editar
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <system-clients-form
            :recordId="recordId"
            :showDialog.sync="showDialog"
        ></system-clients-form>

        <!--<system-clients-form-edit :showDialog.sync="showDialogEdit"
        :recordId="recordId"></system-clients-form-edit>-->

        <client-payments
            :clientId="recordId"
            :showDialog.sync="showDialogPayments"
        ></client-payments>

        <account-status
            :clientId="recordId"
            :showDialog.sync="showDialogAccountStatus"
        ></account-status>

        <client-delete
            :record="record"
            :showDialog.sync="showDialogDelete"
        ></client-delete>

        <mail-modal :showDialog.sync="showFormMail"></mail-modal>

        <el-dialog
            v-loading="loading"
            title="Nuevo administrador"
            :visible.sync="showNewAdmin"
            @close="close"
        >
            <template v-if="!logout">
                <div class="row m-1">
                    <div class="col-12">
                        <span>
                            <i class="fas fa-info-circle"></i>
                            <strong
                                >La sesión se cerrará al crear el usuario, y se
                                eliminará al usuario actual</strong
                            >
                        </span>
                    </div>
                    <div class="col-md-6">
                        <label for="email"> Correo </label>
                        <el-input v-model="form.email"> </el-input>
                    </div>
                    <div class="col-md-6">
                        <label for="password"> Contraseña </label>
                        <el-input show-password v-model="form.password">
                        </el-input>
                    </div>
                </div>
                <span slot="footer" class="dialog-footer">
                    <el-button @click="showNewAdmin = false">Cerrar</el-button>
                    <el-button @click="submit" type="primary">Crear</el-button>
                </span>
            </template>
            <template v-else>
                <div class="row m-2">
                    <div class="col-md-12">
                        <span> Necesita cerrar sesión. </span>
                        <el-button>
                            <a
                                href="/logout"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            >
                                Cerrar sesión
                            </a>
                        </el-button>
                    </div>
                </div>
            </template>
        </el-dialog>
    </div>
</template>

<script>
import CompaniesForm from "./form.vue";
//   import CompaniesFormEdit from './form_edit.vue'
import { deletable } from "../../../mixins/deletable";
import { changeable } from "../../../mixins/changeable";
import ChartLine from "./charts/Line";
import ClientPayments from "./partials/payments.vue";
import AccountStatus from "./partials/account_status.vue";
import ClientDelete from "./partials/delete.vue";
import DataLimitNotification from "./partials/DataLimitNotification.vue";
import MailModal from "./partials/mail_modal.vue";
export default {
    mixins: [deletable, changeable],
    props: ["deletePermission", "discUsed", "iUsed", "storageSize", "version"],
    components: {
        CompaniesForm,
        ChartLine,
        ClientPayments,
        AccountStatus,
        ClientDelete,
        DataLimitNotification,
        MailModal,
    },
    data() {
        return {
            columns: {
                active: {
                    title: "Baja",
                    visible: true,
                },
                hostname: {
                    title: "Host",
                    visible: true,
                },
                name: {
                    title: "Nombre",
                    visible: true,
                },
                number: {
                    title: "RUC",
                    visible: true,
                },
                plan: {
                    title: "Plan",
                    visible: false,
                },
                email: {
                    title: "Correo",
                    visible: true,
                },
                soap_type: {
                    title: "Entorno",
                    visible: true,
                },
                count_doc: {
                    title: "Total Comprobantes",
                    visible: false,
                },
                config_system_env: {
                    title: "Bloquear entorno demo",
                    visible: true,
                },
                notifications: {
                    title: "Notificaciones",
                    visible: true,
                },
                start_billing_cycle: {
                    title: "Inicio Ciclo Fact.",
                    visible: false,
                },
                count_doc_month: {
                    title: "Facturación",
                    visible: false,
                },
                count_user: {
                    title: "Usuarios",
                    visible: false,
                },
                count_item: {
                    title: "Productos",
                    visible: true,
                },
                count_establishments: {
                    title: "Establecimientos",
                    visible: false,
                },
                monthly_sales_total: {
                    title: "Ventas (Mes)",
                    visible: false,
                },
                created_at: {
                    title: "Fecha de creación",
                    visible: false,
                },
                queries_to_apiperu: {
                    title: "API Peru (mes)",
                    visible: false,
                },
                count_sales_notes: {
                    title: "Cant.Notas de venta",
                    visible: false,
                },
                total_doc: {
                    title: "Total (Comprobantes)",
                    visible: false,
                },
                locked: {
                    title: "Bloquear Cuenta",
                    visible: true,
                },
                max_documents: {
                    title: "Limitar Doc.",
                    visible: true,
                },
                max_users: {
                    title: "Limitar Usuarios",
                    visible: true,
                },
                max_items: {
                    title: "Limitar Productos",
                    visible: true,
                },
                max_establishments: {
                    title: "Limitar Establecimientos",
                    visible: true,
                },
                max_sales_limit: {
                    title: "Limitar Ventas (Mes)",
                    visible: false,
                },
                user: {
                    title: "Usuario",
                    visible: false,
                },
                password: {
                    title: "Clave",
                    visible: false,
                },
                password_cdt: {
                    title: "Clave CDT",
                    visible: false,
                },
            },
            logout: false,
            form: {
                email: null,
                password: null,
            },
            showNewAdmin: false,
            showFormMail: false,
            selectBillingDate: "",
            showDialogEdit: false,
            showDialog: false,
            showDialogPayments: false,
            showDialogAccountStatus: false,
            resource: "clients",
            recordId: null,
            records: [],
            text_limit_doc: null,
            text_limit_item: null,
            text_limit_users: null,
            loaded: false,
            year: moment().format("YYYY"),
            total_documents: 0,
            dataChartLine: {
                labels: null,
                datasets: [
                    {
                        // label: 'Data One',
                        // backgroundColor: '#f87979',
                        data: null,
                    },
                ],
            },
            showDialogDelete: false,
            record: {},
            loading: false,
        };
    },
    async mounted() {
        this.loaded = false;
        await this.charts();
        this.loaded = true;
    },
    created() {
        this.$eventHub.$on("reloadData", () => {
            this.getData();
            this.charts();
        });
        this.getData();
        this.getColumns();
        this.text_limit_doc = "El límite de comprobantes fue superado";
        this.text_limit_users = "El límite de usuarios fue superado";
        this.text_limit_item = "El límite de productos fue superado";
    },
    methods: {
        async charts() {
            await this.$http
                .get(`/${this.resource}/charts`)
                .then((response) => {
                    let line = response.data.line;
                    this.dataChartLine.labels = line.labels;
                    this.dataChartLine.datasets[0].data = line.data;
                    this.total_documents = response.data.total_documents;
                });
        },
        close(login = false) {
            this.showNewAdmin = false;
            //redicrect to login
            if (login) {
                window.location.href = "/login";
            }
        },
        async setColumns() {
            const response = await this.$http.post(`/users/columns`, {
                columns: this.columns,
            });
        },
        async getColumns() {
            const response = await this.$http.get(`/users/columns`);
            const data = response.data;
            if (response.status == 200) {
                if (data.columns) {
                    let columns = JSON.parse(data.columns);
                    //compara "columns" con "this.columns" y si hay alguna propiedad que no exista en "columns" copiala
                    for (let key in this.columns) {
                        if (!(key in columns)) {
                            columns[key] = this.columns[key];
                        }
                    }
                    this.columns = columns;
                    console.log(this.columns);
                }
            }
        },
        //function validate a corect email
        validateEmail(email) {
            var re = /\S+@\S+\.\S+/;
            return re.test(email);
        },
        validatePassword(password) {
            var re = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{6,}$/;
            return re.test(password);
        },
        async submit() {
            if (!this.validatePassword(this.form.password)) {
                this.$message.error(
                    "La contraseña debe tener al menos 6 caracteres, una letra mayúscula, una minúscula y un número."
                );
                return;
            }
            if (!this.validateEmail(this.form.email)) {
                this.$message.error("El correo no es válido.");
                return;
            }
            try {
                this.loading = true;
                const response = await this.$http.post(
                    "/users/create_admin",
                    this.form
                );
                if (response.data.success) {
                    this.$message.success(response.data.message);
                    this.close(true);
                } else {
                    this.$message.error("No se ha creado el  administrador.");
                }
            } catch (error) {
                console.log(error);
            } finally {
                this.loading = false;
            }
        },
        clickOpenUser() {
            this.showNewAdmin = true;
        },
        clickOpenMail() {
            this.showFormMail = true;
        },
        changeActiveTenant(row) {
            this.$http
                .post(`${this.resource}/active_tenant`, row)
                .then((response) => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                        this.$eventHub.$emit("reloadData");
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch((error) => {
                    if (error.response.status === 500) {
                        this.$message.error(error.response.data.message);
                    } else {
                        console.log(error.response);
                    }
                })
                .then(() => {});
        },
        changeconfig_system_env_tenant(row) {
            console.log("row.config_system_env", row.config_system_env);
            this.$http
                .post(`${this.resource}/config_system_env`, row)
                .then((response) => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                        this.$eventHub.$emit("reloadData");
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch((error) => {
                    if (error.response.status === 500) {
                        this.$message.error(error.response.data.message);
                    } else {
                        console.log(error.response);
                    }
                })
                .then(() => {});
        },
        changeLockedTenant(row) {
            this.$http
                .post(`${this.resource}/locked_tenant`, row)
                .then((response) => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                        this.$eventHub.$emit("reloadData");
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch((error) => {
                    if (error.response.status === 500) {
                        this.$message.error(error.response.data.message);
                    } else {
                        console.log(error.response);
                    }
                })
                .then(() => {});
        },
    changeLockedItem(row) {
            this.$http
                .post(`${this.resource}/locked_item`, row)
                .then((response) => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                        this.$eventHub.$emit("reloadData");
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch((error) => {
                    if (error.response.status === 500) {
                        this.$message.error(error.response.data.message);
                    } else {
                        console.log(error.response);
                    }
                })
                .then(() => {});
        },
        changeLockedUser(row) {
            this.$http
                .post(`${this.resource}/locked_user`, row)
                .then((response) => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                        this.$eventHub.$emit("reloadData");
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch((error) => {
                    if (error.response.status === 500) {
                        this.$message.error(error.response.data.message);
                    } else {
                        console.log(error.response);
                    }
                })
                .then(() => {});
        },
        changeLockedByColumn(row, column) {
            const params = { ...row };
            params.column = column;

            this.$http
                .post(`${this.resource}/locked-by-column`, params)
                .then((response) => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                        this.$eventHub.$emit("reloadData");
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch((error) => {
                    if (error.response.status === 500) {
                        this.$message.error(error.response.data.message);
                    } else {
                        console.log(error.response);
                    }
                })
                .then(() => {});
        },
        setEndBillingCycle(event, id) {
            this.$http
                .post(`${this.resource}/set_billing_cycle`, {
                    id: id,
                    end_billing_cycle: event,
                })
                .then((response) => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch((error) => {
                    if (error.response.status === 500) {
                        this.$message.error(error.response.data.message);
                    } else {
                        console.log(error.response);
                    }
                })
                .then(() => {
                    this.$eventHub.$emit("reloadData");
                });
        },
        setStartBillingCycle(event, id) {
            this.$http
                .post(`${this.resource}/set_billing_cycle`, {
                    id: id,
                    start_billing_cycle: event,
                })
                .then((response) => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch((error) => {
                    if (error.response.status === 500) {
                        this.$message.error(error.response.data.message);
                    } else {
                        console.log(error.response);
                    }
                })
                .then(() => {
                    this.$eventHub.$emit("reloadData");
                });
        },
        changeLockedEmission(row) {
            this.$http
                .post(`${this.resource}/locked_emission`, row)
                .then((response) => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                        this.$eventHub.$emit("reloadData");
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch((error) => {
                    if (error.response.status === 500) {
                        this.$message.error(error.response.data.message);
                    } else {
                        console.log(error.response);
                    }
                })
                .then(() => {});
        },
        getData() {
            this.$http.get(`/${this.resource}/records`).then((response) => {
                this.records = response.data.data;
            });
        },
        clickCreate(recordId = null) {
            this.recordId = recordId;
            this.showDialog = true;
        },
        clickPayments(recordId = null) {
            this.recordId = recordId;
            this.showDialogPayments = true;
        },
        clickAccountStatus(recordId = null) {
            this.recordId = recordId;
            this.showDialogAccountStatus = true;
        },
        clickPassword(id) {
            this.change(`/${this.resource}/password/${id}`);
        },
        clickDelete(record) {
            this.record = record;
            this.showDialogDelete = true;
            // this.destroy(`/${this.resource}/${id}`).then(() =>
            //     this.$eventHub.$emit("reloadData")
            // );
        },
        clickEdit(recordId) {
            this.recordId = recordId;
            this.showDialog = true;
        },
    },
};
</script>

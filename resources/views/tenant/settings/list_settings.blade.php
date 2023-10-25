@extends('tenant.layouts.app')

@section('content')
    <?php
    use App\Models\Tenant\Configuration;
    $configuration = Configuration::first();
    ?>
    <div class="page-header pr-0">
        <h2>
            <a href="/dashboard">
                <i class="fas fa-home"></i>
            </a>
        </h2>
        <ol class="breadcrumbs">
            <li class="active">
                <span>Dashboard</span>
            </li>
            <li>
                <span class="text-muted">Configuración</span>
            </li>
        </ol>
    </div>

    <div class="row">
        <div class="col-6 col-md-4 mb-4">
            <div class="card card-dashboard card-reports">
                <div class="card-body">
                    <h6 class="card-title">General</h6>
                    <ul class="card-report-links">
                        @if ($user->type != 'integrator')
                            <li>
                                <a href="{{ url('list-banks') }}">
                                    <i data-cs-icon="file-text" class="icon" data-cs-size="18"></i>
                                    Listado de bancos</a>
                            </li>
                            <li>
                                <a href="{{ url('list-bank-accounts') }}">
                                    <i data-cs-icon="file-text" class="icon" data-cs-size="18"></i>
                                    Listado de cuentas bancarias</a>
                            </li>
                            <li>
                                <a href="{{ url('list-currencies') }}">
                                    <i data-cs-icon="file-text" class="icon" data-cs-size="18"></i> Lista de monedas</a>
                            </li>
                            <li>
                                <a href="{{ url('list-cards') }}">
                                    <i data-cs-icon="file-text" class="icon" data-cs-size="18"></i> Listado de
                                    tarjetas</a>
                            </li>
                            <li>

                                <a href="{{ url('warehouses') }}">
                                    <i data-cs-icon="file-text" class="icon" data-cs-size="18"></i>
                                    Lista de almacenes</a>
                            </li>
                            <li>

                                <a href="{{ url('list-platforms') }}">
                                    <i data-cs-icon="web" class="icon" data-cs-size="18"></i>
                                    Plataformas</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        @if (!empty($companyMenu))
            <div class="col-6 col-md-4 mb-4">
                <div class="card card-dashboard card-reports">
                    <div class="card-body">
                        <h6 class="card-title">Empresa</h6>
                        <ul class="card-report-links">
                            <li>
                                <a href="{{ route('tenant.companies.create') }}">
                                    <i data-cs-icon="home" class="icon" data-cs-size="18"></i>
                                    Empresa</a>
                            </li>
                            <li>
                                <a href="{{ route('tenant.bussiness_turns.index') }}">
                                    <i data-cs-icon="home-garage" class="icon" data-cs-size="18"></i>
                                    Giro de negocio</a>
                            </li>
                            <li>
                                <a href="{{ route('tenant.advanced.index') }}">
                                    <i data-cs-icon="gear" class="icon" data-cs-size="18"></i>
                                    Avanzado</a>
                            </li>
                            <li>
                                <a href="{{ route('tenant.shortcuts.index') }}">
                                    <i data-cs-icon="shortcut" class="icon" data-cs-size="18"></i>
                                    Accesos directos</a>
                            </li>
                            <li>
                                <a href="{{ route('tenant.suscription_names.index') }}">
                                    <i data-cs-icon="file-text" class="icon" data-cs-size="18"></i>
                                    Denominación suscripción</a>
                            </li>
                            <li>
                                <a href="{{ route('tenant.dashboard.sales') }}">
                                    <i data-cs-icon="dollar" class="icon" data-cs-size="18"></i>
                                    Dashboard - Ventas - Compras</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        @endif
        <div class="col-6 col-md-4 mb-4">
            <div class="card card-dashboard card-reports">
                <div class="card-body">
                    <h6 class="card-title">SUNAT</h6>
                    <ul class="card-report-links">
                        @if ($user->type != 'integrator')
                            <li>
                                <a href="{{ url('list-attributes') }}">
                                    <i data-cs-icon="file-text" class="icon" data-cs-size="18"></i>
                                    Listado de Atributos</a>
                            </li>
                            <li>
                                <a href="{{ url('list-detractions') }}">
                                    <i data-cs-icon="file-text" class="icon" data-cs-size="18"></i>
                                    Listado de tipos de detracciones</a>
                            </li>
                            <li>
                                <a href="{{ url('list-units') }}">
                                    <i data-cs-icon="file-text" class="icon" data-cs-size="18"></i>
                                    Listado de unidades</a>
                            </li>
                            <li>
                                <a href="{{ url('list-transfer-reason-types') }}">
                                    <i data-cs-icon="send" class="icon" data-cs-size="18"></i>
                                    Tipos de motivos de transferencias</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 mb-4">
            <div class="card card-dashboard card-reports">
                <div class="card-body">
                    <h6 class="card-title">Ingresos/Egresos</h6>
                    <ul class="card-report-links">
                        @if ($user->type != 'integrator')
                            <li>
                                <a href="{{ url('list-payment-methods') }}">
                                    <i data-cs-icon="money" class="icon" data-cs-size="18"></i>
                                    Métodos de pago - ingreso / gastos</a>
                            </li>
                            <li>
                                <a href="{{ url('list-incomes') }}">
                                    <i data-cs-icon="dollar" class="icon" data-cs-size="18"></i>
                                    Motivos de ingresos / Gastos</a>
                            </li>
                            <li>
                                <a href="{{ url('list-payments') }}">
                                    <i data-cs-icon="file-text" class="icon" data-cs-size="18"></i>
                                    Listado de métodos de pago</a>
                            </li>
                        @endif
                        @if ($user->type != 'integrator')
                            <li>
                                <a href="{{ url('list-vouchers-type') }}">
                                    <i data-cs-icon="dollar" class="icon" data-cs-size="18"></i>
                                    comprobantes Ingreso / Gastos</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 mb-4">
            <div class="card card-dashboard card-reports">
                <div class="card-body">
                    <h6 class="card-title">Plantillas PDF</h6>
                    <ul class="card-report-links">
                        <li>
                            <a href="{{ route('tenant.advanced.pdf_templates') }}">
                                <i data-cs-icon="file-data" class="icon" data-cs-size="18"></i>
                                PDF</a>
                        </li>
                        <li>
                            <a href="{{ route('tenant.advanced.pdf_ticket_templates') }}">
                                <i data-cs-icon="file-data" class="icon" data-cs-size="18"></i>
                                PDF - Ticket</a>
                        </li>
                        <li>
                            <a href="{{ route('tenant.advanced.pdf_preprinted_templates') }}">
                                <i data-cs-icon="file-data" class="icon" data-cs-size="18"></i>
                                Pre Impresos</a>
                        </li>
                        <li>
                            <a href="{{ url('list-units/pdf') }}">
                                <i data-cs-icon="file-data" class="icon" data-cs-size="18"></i>
                                PDF - Unidades de medida</a>
                        </li>
                        <li>
                            <a href="{{ route('tenant.document_names.index') }}">
                                <i data-cs-icon="file-data" class="icon" data-cs-size="18"></i>
                                PDF - Denominación</a>
                        </li>
                        <li>
                            <a href="{{ route('tenant.document_quotations.index') }}">
                                <i data-cs-icon="file-data" class="icon" data-cs-size="18"></i>
                                PDF - Cotizaciones - Facturas</a>
                        </li>
                        <li>
                            <a href="{{ route('tenant.yape_plin_qr.index') }}">
                                <i data-cs-icon="file-data" class="icon" data-cs-size="18"></i>
                                PDF - QR - Yape/Plin</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        @if (!empty($advanceMenu))
            <div class="col-6 col-md-4 mb-4">
                <div class="card card-dashboard card-reports">
                    <div class="card-body">
                        <h6 class="card-title">Avanzado</h6>
                        <ul class="card-report-links">
                            @if ($user->type != 'integrator' && $vc_company->soap_type_id != '03')
                                <li>
                                    <a href="{{ route('tenant.tasks.index') }}">
                                        <i data-cs-icon="alarm" class="icon" data-cs-size="18"></i>
                                        Tareas programadas</a>
                                </li>
                            @endif
                            @if ($vc_company->soap_type_id != '03')
                                <li>
                                    <a href="{{ route('tenant.series_configurations.index') }}">
                                        <i data-cs-icon="align-center" class="icon" data-cs-size="18"></i>
                                        Numeración de facturación</a>
                                </li>
                            @endif
                            <li>
                                <a href="{{ route('tenant.company_accounts.create') }}">
                                    <i data-cs-icon="gear" class="icon" data-cs-size="18"></i>
                                    Avanzado - Contable</a>
                            </li>
                            @if ($user->type != 'integrator' && $vc_company->soap_type_id != '03')
                                <li>
                                    <a href="{{ route('tenant.inventories.configuration.index') }}">
                                        <i data-cs-icon="boxes" class="icon" data-cs-size="18"></i>
                                        Inventarios</a>
                                </li>
                            @endif
                            @if ($user->type === 'admin')
                                <li>
                                    <a href="{{ route('tenant.sale_notes.configuration') }}">
                                        <i data-cs-icon="note" class="icon" data-cs-size="18"></i>
                                        Nota de ventas</a>
                                </li>
                            @endif
                            @if ($configuration->isMiTiendaPe() == true)
                                <li>
                                    <a href="{{ route('tenant.mi_tienda_pe.configuration.index') }}">
                                        MiTienda.PE
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection

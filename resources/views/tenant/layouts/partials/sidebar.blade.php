<?php

use App\Models\Tenant\Configuration;

$path = explode('/', request()->path());
$configuration = Configuration::first();
$firstLevel = $path[0] ?? null;
$secondLevel = $path[1] ?? null;
$thridLevel = $path[2] ?? null;

?>
<div class="menu-container flex-grow-1">
    <ul id="menu" class="menu">
        <li>
            <a href="{{ route('tenant.dashboard.index') }}" class="{{ $firstLevel === 'dashboard' ? 'active' : '' }}">
                <i data-cs-icon="home" class="icon" data-cs-size="18"></i>
                <span class="label">Dashboard</span>
            </a>
        </li>
        @if ($configuration->view_tutorials)
            <li>
                <a href="{{ route('shortcuts.index') }}" class="{{ $firstLevel === 'shortcuts' ? 'active' : '' }}">
                    <i data-cs-icon="shortcut" class="icon" data-cs-size="18"></i>
                    <span class="label">Acceso Rapido Login</span>
                </a>
            </li>
        @endif
        @if ($configuration->chatboot)
            <li class="mega">
                <a href="#whatsapp" data-bs-toggle="collapse" data-role="button"
                    aria-expanded="{{ $firstLevel === 'whatsapp' ? true : false }}{{ $firstLevel === 'questions' ? true : false }}{{ $firstLevel === 'answers' ? true : false }}"
                    class="{{ $firstLevel === 'whatsapp' ? 'active' : '' }}{{ $firstLevel === 'questions' ? 'active' : '' }}{{ $firstLevel === 'answers' ? 'active' : '' }}"
                    data-clicked="{{ $firstLevel === 'whatsapp' ? true : false }}{{ $firstLevel === 'questions' ? true : false }}{{ $firstLevel === 'answers' ? true : false }}">
                    <i class="bi bi-whatsapp"></i>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-whatsapp" viewBox="0 0 16 16">
                        <path
                            d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z" />
                    </svg>
                    <span class="label">Whatsapp</span>
                </a>
                <ul id="whatsapp">
                    <li>
                        <a href="{{ route('tenant.account.whatsapp') }}"
                            class="{{ $path[0] === 'whatsapp' ? 'active' : '' }}">
                            <span class="label">Cuenta de Whatsapp</span>
                        </a>
                    </li>
                    <li class="mega">
                        <a href="#chatboot" data-bs-toggle="collapse" data-role="button"
                            aria-expanded="{{ $firstLevel === 'questions' ? true : false }}{{ $firstLevel === 'answers' ? true : false }}"
                            class="{{ $firstLevel === 'questions' ? 'active' : '' }}{{ $firstLevel === 'answers' ? 'active' : '' }}"
                            data-clicked="{{ $firstLevel === 'questions' ? true : false }}{{ $firstLevel === 'answers' ? true : false }}">

                            <span class="label"> ChatBoot Whatsapp</span>
                        </a>
                        <ul id="chatboot"
                            class="collapse {{ $firstLevel === 'questions' ? 'show' : '' }}{{ $firstLevel === 'answers' ? 'show' : '' }} ">
                            <li>
                                <a href="{{ route('tenant.questions') }}"
                                    class="{{ $path[0] === 'questions' ? 'active' : '' }}">
                                    <span class="label">Preguntas</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('tenant.answers') }}"
                                    class="{{ $path[0] === 'answers' ? 'active' : '' }}">
                                    <span class="label">Respuestas</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        @endif
        @if (in_array('documents', $vc_modules))
            <li class="mega">
                <a href="#ventas" data-bs-toggle="collapse" data-role="button"
                    aria-expanded="{{ $firstLevel === 'documents' ? true : false }}{{ $firstLevel === 'summaries' ? true : false }}{{ $firstLevel === 'voided' ? true : false }}{{ $firstLevel === 'quotations' ? true : false }}{{ $firstLevel === 'sale-notes' ? true : false }}{{ $firstLevel === 'contingencies' ? true : false }}{{ $firstLevel === 'incentives' ? true : false }}{{ $firstLevel === 'order-notes' ? true : false }}{{ $firstLevel === 'sale-opportunities' ? true : false }}{{ $firstLevel === 'contracts' ? true : false }}{{ $firstLevel === 'production-orders' ? true : false }}{{ $firstLevel === 'technical-services' ? true : false }}{{ $firstLevel === 'user-commissions' ? true : false }}{{ $firstLevel === 'regularize-shipping' ? true : false }}"
                    class="{{ $firstLevel === 'documents' ? 'active' : '' }}{{ $firstLevel === 'summaries' ? 'active' : '' }}{{ $firstLevel === 'voided' ? 'active' : '' }}{{ $firstLevel === 'quotations' ? 'active' : '' }}{{ $firstLevel === 'sale-notes' ? 'active' : '' }}{{ $firstLevel === 'contingencies' ? 'active' : '' }}{{ $firstLevel === 'incentives' ? 'active' : '' }}{{ $firstLevel === 'order-notes' ? 'active' : '' }}{{ $firstLevel === 'sale-opportunities' ? 'active' : '' }}{{ $firstLevel === 'contracts' ? 'active' : '' }}{{ $firstLevel === 'production-orders' ? 'active' : '' }}{{ $firstLevel === 'technical-services' ? 'active' : '' }}{{ $firstLevel === 'user-commissions' ? 'active' : '' }}{{ $firstLevel === 'regularize-shipping' ? 'active' : '' }}"
                    data-clicked="{{ $firstLevel === 'documents' ? true : false }}{{ $firstLevel === 'summaries' ? true : false }}{{ $firstLevel === 'voided' ? true : false }}{{ $firstLevel === 'quotations' ? true : false }}{{ $firstLevel === 'sale-notes' ? true : false }}{{ $firstLevel === 'contingencies' ? true : false }}{{ $firstLevel === 'incentives' ? true : false }}{{ $firstLevel === 'order-notes' ? true : false }}{{ $firstLevel === 'sale-opportunities' ? true : false }}{{ $firstLevel === 'contracts' ? true : false }}{{ $firstLevel === 'production-orders' ? true : false }}{{ $firstLevel === 'technical-services' ? true : false }}{{ $firstLevel === 'user-commissions' ? true : false }}{{ $firstLevel === 'regularize-shipping' ? true : false }}">
                    <i data-cs-icon="file-text" class="icon" data-cs-size="18"></i>
                    <span class="label">Ventas</span>
                </a>
                <ul id="ventas"
                    class="collapse {{ $firstLevel === 'documents' ? 'show' : '' }}{{ $firstLevel === 'summaries' ? 'show' : '' }}{{ $firstLevel === 'voided' ? 'show' : '' }}{{ $firstLevel === 'quotations' ? 'show' : '' }}{{ $firstLevel === 'sale-notes' ? 'show' : '' }}{{ $firstLevel === 'contingencies' ? 'show' : '' }}{{ $firstLevel === 'incentives' ? 'show' : '' }}{{ $firstLevel === 'order-notes' ? 'show' : '' }}{{ $firstLevel === 'sale-opportunities' ? 'show' : '' }}{{ $firstLevel === 'contracts' ? 'show' : '' }}{{ $firstLevel === 'production-orders' ? 'show' : '' }}{{ $firstLevel === 'technical-services' ? 'show' : '' }}{{ $firstLevel === 'user-commissions' ? 'show' : '' }}{{ $firstLevel === 'regularize-shipping' ? 'show' : '' }}">
                    @if (auth()->user()->type != 'integrator' && $vc_company->soap_type_id != '03')
                        @if (in_array('documents', $vc_modules))
                            @if (in_array('new_document', $vc_module_levels))
                                <li>
                                    <a class="{{ $firstLevel === 'documents' && $secondLevel === 'create' ? 'active' : '' }}"
                                        href="{{ route('tenant.documents.create') }}">Nuevo</a>
                                </li>
                            @endif
                        @endif
                    @endif



                    @if (in_array('documents', $vc_modules) && $vc_company->soap_type_id != '03')
                        @if (in_array('list_document', $vc_module_levels))
                            <li>
                                <a class="{{ $firstLevel === 'documents' && $secondLevel != 'create' && $secondLevel != 'not-sent' && $secondLevel != 'regularize-shipping' ? 'active' : '' }}"
                                    href="{{ route('tenant.documents.index') }}">Listado de comprobantes</a>
                            </li>
                        @endif
                    @endif

                    @if (in_array('sale_notes', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'sale-notes' ? 'active' : '' }}"
                                href="{{ route('tenant.sale_notes.index') }}">Notas de Venta</a>
                        </li>
                    @endif
                   @if($configuration->package_handlers)
                   <li>
                    <a class="{{ $firstLevel === 'package-handler' ? 'active' : '' }}"
                        href="{{ route('tenant.package_handler.index') }}">Tickets de encomienda</a>
                </li>
                   @endif
                    @if (in_array('documents', $vc_modules) && $vc_company->soap_type_id != '03')

                        @if (in_array('document_not_sent', $vc_module_levels))
                            <li>
                                <a class="{{ $firstLevel === 'documents' && $secondLevel === 'not-sent' ? 'active' : '' }}"
                                    href="{{ route('tenant.documents.not_sent') }}">
                                    Comprobantes no enviados
                                </a>
                            </li>
                        @endif
                        @if (in_array('regularize_shipping', $vc_module_levels))
                            <li>
                                <a class="{{ $firstLevel === 'documents' && $secondLevel === 'regularize-shipping' ? 'active' : '' }}"
                                    href="{{ route('tenant.documents.regularize_shipping') }}">
                                    CPE pendientes de rectificación
                                </a>
                            </li>
                        @endif
                    @endif

                    @if (auth()->user()->type != 'integrator' && in_array('documents', $vc_modules))

                        @if (auth()->user()->type != 'integrator' &&
                                in_array('document_contingengy', $vc_module_levels) &&
                                $vc_company->soap_type_id != '03')
                            <li>
                                <a class="{{ $firstLevel === 'contingencies' ? 'active' : '' }}"
                                    href="{{ route('tenant.contingencies.index') }}">
                                    Comprobante de contingencia
                                </a>
                            </li>
                        @endif
                    @endif
                    @if (in_array('quotations', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel == 'quotations' && $secondLevel == '' ? 'active' : '' }}"
                                href="{{ route('tenant.quotations.index') }}">
                                Cotizaciones
                            </a>
                        </li>
                    @endif
                    <li>
                        <a class="{{ $firstLevel === 'summaries' ? 'active' : '' }}"
                            href="{{ route('tenant.summaries.index') }}">
                            Resúmenes
                        </a>
                    </li>
                    <li>
                        <a class="{{ $firstLevel === 'voided' ? 'active' : '' }}"
                            href="{{ route('tenant.voided.index') }}">
                            Anulaciones
                        </a>
                    </li>

                    @if (in_array('sale-opportunity', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'sale-opportunities' ? 'active' : '' }}"
                                href="{{ route('tenant.sale_opportunities.index') }}">
                                Oportunidad de venta
                            </a>
                        </li>
                    @endif


                    @if (in_array('order-note', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'order-notes' ? 'active' : '' }}"
                                href="{{ route('tenant.order_notes.index') }}">
                                Pedidos
                            </a>
                        </li>
                    @endif

                    @if (in_array('technical-service', $vc_module_levels))
                        <li class="{{ $firstLevel === 'technical-services' ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('tenant.technical_services.index') }}">
                                Servicio de soporte técnico
                            </a>
                        </li>
                    @endif
                    <li class="mega">
                        <a href="#contrato" data-bs-toggle="collapse" data-role="button"
                            aria-expanded="{{ $firstLevel === 'contracts' ? true : false }}{{ $firstLevel === 'production-orders' ? true : false }}"
                            class="{{ $firstLevel === 'contracts' ? 'active' : '' }}{{ $firstLevel === 'production-orders' ? 'active' : '' }}"
                            data-clicked="{{ $firstLevel === 'contracts' ? true : false }}{{ $firstLevel === 'production-orders' ? true : false }}">
                            <span class="label"> Contratos</span>
                        </a>
                        <ul id="contrato"
                            class="collapse {{ $firstLevel === 'contracts' ? 'show' : '' }}{{ $firstLevel === 'production-orders' ? 'show' : '' }} ">
                            <li>
                                <a class="{{ $firstLevel === 'contracts' ? 'active' : '' }}"
                                    href="{{ route('tenant.contracts.index') }}">
                                    Listado
                                </a>
                            </li>
                            <li>
                                <a class="{{ $firstLevel === 'production-orders' ? 'active' : '' }}"
                                    href="{{ route('tenant.production_orders.index') }}">
                                    Ordenes de Producción
                                </a>
                            </li>
                        </ul>
                    </li>
                    @if (in_array('incentives', $vc_module_levels))
                        <li class="mega">
                            <a href="#incentives" data-bs-toggle="collapse" data-role="button"
                                aria-expanded="{{ $firstLevel === 'user-commissions' ? true : false }}{{ $firstLevel === 'incentives' ? true : false }}"
                                class="{{ $firstLevel === 'user-commissions' ? 'active' : '' }}{{ $firstLevel === 'incentives' ? 'active' : '' }}"
                                data-clicked="{{ $firstLevel === 'user-commissions' ? true : false }}{{ $firstLevel === 'incentives' ? true : false }}">
                                <span class="label">Comisiones</span>
                            </a>
                            <ul id="incentives"
                                class="collapse {{ $firstLevel === 'incentives' ? 'show' : '' }}{{ $firstLevel === 'incentives' ? 'show' : '' }} ">
                                <li>
                                    <a class="{{ $firstLevel === 'user-commissions' ? 'active' : '' }}"
                                        href="{{ route('tenant.user_commissions.index') }}">
                                        Vendedores
                                    </a>
                                </li>
                                <li>
                                    <a class="{{ $firstLevel === 'incentives' ? 'active' : '' }}"
                                        href="{{ route('tenant.incentives.index') }}">Productos</a>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        @if (auth()->user()->type != 'integrator')
            @if (in_array('pos', $vc_modules))
                <li class="mega">
                    <a href="#pos" data-bs-toggle="collapse" data-role="button"
                        aria-expanded="{{ $firstLevel === 'pos' ? true : false }}{{ $firstLevel === 'cash' ? true : false }}"
                        class="{{ $firstLevel === 'pos' ? 'active' : '' }}{{ $firstLevel === 'cash' ? 'active' : '' }}"
                        data-clicked="{{ $firstLevel === 'pos' ? true : false }}{{ $firstLevel === 'cash' ? true : false }}">
                        <i data-cs-icon="cart" class="icon" data-cs-size="18"></i>
                        <span class="label">POS</span>
                    </a>
                    <ul id="pos"
                        class="collapse {{ $firstLevel === 'pos' ? 'show' : '' }}{{ $firstLevel === 'cash' ? 'show' : '' }}{{ $secondLevel === 'garage' ? 'show' : '' }}">
                        @if (in_array('pos', $vc_module_levels))
                            <li>
                                <a class="{{ $firstLevel === 'pos' && $secondLevel === null ? 'active' : '' }}"
                                    href="{{ route('tenant.pos.index') }}">Punto de venta</a>
                            </li>
                        @endif
                        {{-- <li>
                            <a class="{{ $firstLevel === 'pos' && $secondLevel === null ? 'active' : '' }}"
                                href="{{ route('tenant.pos.garage') }}">Punto de Venta 2.0 </a>
                        </li> --}}


                        @if (in_array('pos_garage', $vc_module_levels))
                            <li>
                                <a class="{{ $secondLevel === 'garage' ? 'active' : '' }}"
                                    href="{{ route('tenant.pos.garage') }}">Venta rápida <span
                                        style="font-size:.65rem;">(Grifos y
                                        Markets)</span></a>
                            </li>
                        @endif
                        @if (in_array('cash', $vc_module_levels))
                            <li>
                                <a class="{{ $firstLevel === 'cash' ? 'active' : '' }}"
                                    href="{{ route('tenant.cash.index') }}">Caja
                                    chica POS</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            {{-- Tienda virtual --}}

        @endif
        @if (in_array('ecommerce', $vc_modules))
            <li class="mega">
                <a href="#tienda" data-bs-toggle="collapse" data-role="button"
                    aria-expanded="{{ in_array($firstLevel, ['ecommerce', 'items_ecommerce', 'tags', 'promotions', 'orders', 'configuration']) ? true : false }}"
                    class="{{ in_array($firstLevel, ['ecommerce', 'items_ecommerce', 'tags', 'promotions', 'orders', 'configuration']) ? 'active' : '' }}"
                    data-clicked="{{ in_array($firstLevel, ['ecommerce', 'items_ecommerce', 'tags', 'promotions', 'orders', 'configuration']) ? true : false }}">
                    <i data-cs-icon="shop" class="icon" data-cs-size="18"></i>
                    <span class="label">Tienda Virtual</span>
                </a>
                <ul id="tienda"
                    class="collapse {{ in_array($firstLevel, ['ecommerce', 'items_ecommerce', 'tags', 'promotions', 'orders', 'configuration']) ? 'show' : '' }}">
                    @if (in_array('ecommerce', $vc_module_levels))
                        <li>
                            <a class="nav-link" href="{{ route('tenant.ecommerce.index') }}" target="_blank"> Ir a
                                Tienda </a>
                        </li>
                    @endif
                    @if (in_array('ecommerce_orders', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'orders' ? 'active' : '' }}"
                                class="{{ $firstLevel === 'pos' && $secondLevel === null ? 'active' : '' }}"
                                href="{{ route('tenant_orders_index') }}">Pedidos</a>
                        </li>
                    @endif
                    @if (in_array('ecommerce_items', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'items_ecommerce' ? 'active' : '' }}"
                                href="{{ route('tenant.items_ecommerce.index') }}">Productos Tienda Virtual</a>
                        </li>
                    @endif

                    <li>
                        <a class="{{ $secondLevel === 'item-sets' ? 'active' : '' }}"
                            href="{{ route('tenant.item_sets.index') }}">Conjuntos/Packs/Promociones</a>
                    </li>

                    @if (in_array('ecommerce_tags', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'tags' ? 'active' : '' }}"
                                href="{{ route('tenant.tags.index') }}">Tags -
                                Categorias(Etiquetas)</a>
                        </li>
                    @endif
                    @if (in_array('ecommerce_promotions', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'promotions' ? 'active' : '' }}"
                                href="{{ route('tenant.promotion.index') }}">Promociones(Banners)</a>
                        </li>
                    @endif
                    @if (in_array('ecommerce_settings', $vc_module_levels))
                        <li>
                            <a class="{{ $secondLevel === 'configuration' ? 'active' : '' }}"
                                href="{{ route('tenant_ecommerce_configuration') }}">Configuración</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        @if (in_array('proyectos', $vc_modules))
            <li class="mega">
                <a href="#proyect" data-bs-toggle="collapse" data-role="button"
                    aria-expanded="{{ in_array($firstLevel, ['projects']) ? true : false }} {{ in_array($secondLevel, ['list']) ? true : false }}"
                    class="{{ in_array($firstLevel, ['projects']) ? true : false }} {{ in_array($secondLevel, ['list']) ? 'active' : '' }}"
                    data-clicked="{{ in_array($firstLevel, ['projects']) ? true : false }} {{ in_array($secondLevel, ['list']) ? true : false }}">
                    <i data-cs-icon="shop" class="icon" data-cs-size="18"></i>
                    <span
                        class="label">{{ $configuration->name_module_project ? $configuration->name_module_project : 'Proyectos' }}</span>
                </a>
                <ul id="proyect"
                    class="collapse {{ in_array($firstLevel, ['projects']) ? 'show' : '' }}{{ in_array($secondLevel, ['list']) ? 'show' : '' }}">
                    <li>
                        <a class="{{ $firstLevel === 'projects' ? 'active' : '' }}"
                            href=" {{ route('tenant.projects.index') }} ">Crear</a>
                    </li>
                    <li>
                        <a class="{{ $firstLevel === 'projects' && $secondLevel === 'list' ? 'active' : '' }}"
                            href=" {{ route('tenant.projects.list') }} ">Lista</a>
                    </li>

                </ul>
            </li>
        @endif

        @if (in_array('items', $vc_modules))
            <li class="mega">
                <a href="#items" data-bs-toggle="collapse" data-role="button"
                    aria-expanded="{{ in_array($firstLevel, ['items', 'services', 'categories', 'brands', 'item-lots', 'item-sets']) ? true : false }}"
                    class="{{ in_array($firstLevel, ['items', 'services', 'categories', 'brands', 'item-lots', 'item-sets']) ? true : false }} {{ in_array($secondLevel, ['list']) ? 'active' : '' }}"
                    data-clicked="{{ in_array($firstLevel, ['items', 'services', 'categories', 'brands', 'item-lots', 'item-sets']) ? true : false }} {{ in_array($secondLevel, ['list']) ? true : false }}">
                    <i data-cs-icon="boxes" class="icon" data-cs-size="18"></i>
                    <span class="label">Productos/Servicios</span>
                </a>
                <ul id="items"
                    class="collapse {{ in_array($firstLevel, ['items', 'services', 'categories', 'brands', 'item-lots', 'item-sets']) ? 'show' : '' }}">
                    @if (in_array('items', $vc_module_levels))
                        <li class="{{ $firstLevel === 'items' ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('tenant.items.index') }}">
                                <span class="label">Productos</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('items_packs', $vc_module_levels))
                        <li class="{{ $firstLevel === 'item-sets' ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('tenant.item_sets.index') }}">
                                <span class="label">Conjuntos/Packs/Promociones</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('items_services', $vc_module_levels))
                        <li class="{{ $firstLevel === 'services' ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('tenant.services') }}">
                                <span class="label">Servicios</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('items_categories', $vc_module_levels))
                        <li class="{{ $firstLevel === 'categories' ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('tenant.categories.index') }}">
                                <span class="label">Categorías</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('items_brands', $vc_module_levels))
                        <li class="{{ $firstLevel === 'brands' ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('tenant.brands.index') }}">
                                <span class="label">Marcas</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('items_lots', $vc_module_levels))
                        <li class="{{ $firstLevel === 'item-lots' ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('tenant.item-lots.index') }}">
                                <span class="label">Series</span>
                            </a>
                        </li>
                    @endif
                    <li class="{{ $firstLevel === 'item-lots-group' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('tenant.item-lots-group.index') }}">
                            <span class="label">Lotes</span>
                        </a>
                    </li>

                </ul>
            </li>
        @endif
        @if (in_array('persons', $vc_modules))
            <li class="mega">
                <a href="#customers" data-bs-toggle="collapse" data-role="button"
                    aria-expanded="{{ in_array($firstLevel, ['persons', 'person-types']) ? true : false }}"
                    class="{{ in_array($firstLevel, ['persons', 'person-types']) ? true : false }} {{ in_array($secondLevel, ['list']) ? 'active' : '' }}"
                    data-clicked="{{ in_array($firstLevel, ['persons', 'person-types']) ? true : false }} {{ in_array($secondLevel, ['list']) ? true : false }}">
                    <i data-cs-icon="boxes" class="icon" data-cs-size="18"></i>
                    <span class="label">Clientes</span>
                </a>
                <ul id="customers"
                    class="collapse {{ in_array($firstLevel, ['persons', 'person-types']) ? 'show' : '' }}">
                    @if (in_array('clients', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'persons' && $secondLevel === 'customers' ? 'active' : '' }}"
                                href="{{ route('tenant.persons.index', ['type' => 'customers']) }}">
                                <span class="label"> Nuevo </span>
                            </a>
                        </li>
                    @endif
                    @if ($configuration->package_handlers)
                    <li>
                        <a class="{{ $firstLevel === 'persons' && $secondLevel === 'customers' ? 'active' : '' }}"
                            href="{{ route('tenant.persons_drivers.index') }}">
                            <span class="label"> Conductores </span>
                        </a>
                    </li>
                @endif
                    @if (in_array('clients_types', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'person-types' ? 'active' : '' }}"
                                href="{{ route('tenant.person_types.index') }}">
                                <span class="label"> Tipos de clientes</span>
                            </a>
                        </li>
                    @endif


                </ul>
            </li>
        @endif
        <li class="mega">
            <a class="{{ $firstLevel === 'persons' && $secondLevel === 'suppliers' ? 'active' : '' }}"
                href="{{ route('tenant.persons.index', ['type' => 'suppliers']) }}">
                <i data-cs-icon="user" class="icon" data-cs-size="18"></i>
                <span class="label">Proveedores</span>
            </a>
        </li>

        @if (auth()->user()->type != 'integrator')
            @if (in_array('purchases', $vc_modules))
                <li class="mega">
                    <a href="#purchases" data-toggle="collapse" data-role="button"
                        data-active="{{ $firstLevel === 'purchases' || ('persons' && $secondLevel === 'suppliers') || $firstLevel === 'expenses' || $firstLevel === 'purchase-quotations' || $secondLevel === 'purchase-orders' || $firstLevel === 'fixed-asset' ? true : false }}"
                        aria-expanded="{{ $firstLevel === 'purchases' || ('persons' && $secondLevel === 'suppliers') || $firstLevel === 'expenses' || $firstLevel === 'purchase-quotations' || $secondLevel === 'purchase-orders' || $firstLevel === 'fixed-asset' ? true : false }}"
                        class="{{ $firstLevel === 'purchases' || ('persons' && $secondLevel === 'suppliers') || $firstLevel === 'expenses' || $firstLevel === 'purchase-quotations' || $secondLevel === 'purchase-orders' || $firstLevel === 'fixed-asset' ? 'active' : '' }}"
                        data-clicked="{{ $firstLevel === 'purchases' || ('persons' && $secondLevel === 'suppliers') || $firstLevel === 'expenses' || $firstLevel === 'purchase-quotations' || $secondLevel === 'purchase-orders' || $firstLevel === 'fixed-asset' ? true : false }}">
                        <i data-cs-icon="suitcase" class="icon" data-cs-size="18"></i>
                        <span class="label">Compras</span>
                    </a>
                    <ul id="purchases"
                        class="collapse {{ $firstLevel === 'purchases' || ('persons' && $secondLevel === 'suppliers') || $firstLevel === 'purchase-quotations' || $secondLevel === 'purchase-orders' || $firstLevel === 'fixed-asset' ? 'show' : '' }}"
                        id="purchases" data-parent="#accordionExample">
                        @if (in_array('purchases_create', $vc_module_levels))
                            <li>
                                <a class="{{ $firstLevel === 'purchases' && $secondLevel === 'create' ? 'active' : '' }}"
                                    href="{{ route('tenant.purchases.create') }}">
                                    <span class="label">Nuevo</span>
                                </a>
                            </li>
                        @endif
                        @if (in_array('purchases_list', $vc_module_levels))
                            <li>
                                <a class="{{ $firstLevel === 'purchases' || $secondLevel === '' ? 'active' : '' }}"
                                    href="{{ route('tenant.purchases.index') }}">
                                    <span class="label">Listado</span>
                                </a>
                            </li>
                        @endif
                        <li>
                            <a class="{{ $firstLevel === 'purchase-settlements' ? 'active' : '' }}"
                                href="{{ route('tenant.purchase-settlements.index') }}">Liquidación de compra</a>
                        </li>
                        @if (in_array('purchases_quotations', $vc_module_levels))
                            <li>
                                <a class="{{ $firstLevel === 'purchase-quotations' ? 'active' : '' }}"
                                    href="{{ route('tenant.purchase-quotations.index') }}">
                                    <span class="label">Solicitar Cotización</span>
                                </a>
                            </li>
                        @endif
                        @if (in_array('purchases_orders', $vc_module_levels))
                            <li>
                                <a class="{{ $firstLevel === 'purchase-orders' ? 'active' : '' }}"
                                    href="{{ route('tenant.purchase-orders.index') }}">
                                    <span class="label">Ordenes de compra</span>
                                </a>
                            </li>
                        @endif

                        @if (in_array('purchases_expenses', $vc_module_levels))
                            <li>
                                <a class="{{ $firstLevel === 'expenses' ? 'active' : '' }}"
                                    href="{{ route('tenant.expenses.index') }}">
                                    <span class="label">Gastos diversos</span>
                                </a>
                            </li>
                        @endif

                        @if (in_array('purchases_fixed_assets_purchases', $vc_module_levels) ||
                                in_array('purchases_fixed_assets_items', $vc_module_levels))
                            <li>
                                <a href="#activos" class="{{ $firstLevel === 'fixed-asset' ? 'active' : '' }}"
                                    data-toggle="collapse"
                                    aria-expanded="{{ $firstLevel === 'fixed-asset' ? true : false }}">
                                    Activos fijos
                                </a>
                                <ul class="collapse {{ $firstLevel === 'fixed-asset' ? 'show' : '' }}{{ $firstLevel === 'fixed-asset' ? 'show' : '' }}"
                                    id="activos" data-parent="#purchases">
                                    @if (in_array('purchases_fixed_assets_items', $vc_module_levels))
                                        <li>
                                            <a class="{{ $firstLevel === 'fixed-asset' && $secondLevel === 'items' ? 'active' : '' }}"
                                                href="{{ route('tenant.fixed_asset_items.index') }}">
                                                <span class="label">Ítems</span>
                                            </a>
                                        </li>
                                    @endif
                                    @if (in_array('purchases_fixed_assets_purchases', $vc_module_levels))
                                        <li>
                                            <a class="{{ $firstLevel === 'fixed-asset' && $secondLevel === 'purchases' ? 'active' : '' }}"
                                                href="{{ route('tenant.fixed_asset_purchases.index') }}">
                                                <span class="label">Compras</span>
                                            </a>
                                        </li>
                                    @endif


                                </ul>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        @endif
        @if (in_array('inventory', $vc_modules))
            <li class="mega">
                <a href="#inventario" data-toggle="collapse"
                    class="{{ in_array($firstLevel, ['inventory', 'moves', 'transfers', 'devolutions']) || ($firstLevel === 'reports' && in_array($secondLevel, ['kardex', 'inventory', 'valued-kardex'])) ? 'active' : '' }}"
                    aria-expanded="{{ in_array($firstLevel, ['inventory', 'moves', 'transfers', 'devolutions']) | ($firstLevel === 'reports' && in_array($secondLevel, ['kardex', 'inventory', 'valued-kardex'])) ? 'active' : '' }}">
                    <i data-cs-icon="book-open" class="icon" data-cs-size="18"></i>
                    <span class="label">Inventario</span>

                </a>
                <ul id="inventario"
                    class="collapse {{ in_array($firstLevel, ['inventory', 'moves', 'transfers', 'devolutions']) || ($firstLevel === 'reports' && in_array($secondLevel, ['kardex', 'inventory', 'valued-kardex', 'reports'])) ? 'show' : '' }}"
                    id="inventory" data-parent="#accordionExample">

                    @if (in_array('inventory', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'inventory' ? 'active' : '' }}"
                                href="{{ route('inventory.index') }}">
                                <span class="label">Movimientos</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('inventory_transfers', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'transfers' ? 'active' : '' }}"
                                href="{{ route('transfers.index') }}">
                                <span class="label">Traslados</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('inventory_devolutions', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'devolutions' ? 'active' : '' }}"
                                href="{{ route('devolutions.index') }}">
                                <span class="label">Devoluciones</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('inventory_report_kardex', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'reports' && $secondLevel === 'kardex' ? 'active' : '' }}"
                                href="{{ route('reports.kardex.index') }}">
                                <span class="label">Reporte Kardex</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('inventory_report', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'reports' && $secondLevel == 'inventory' ? 'active' : '' }}"
                                href="{{ route('reports.inventory.index') }}">
                                <span class="label">Reporte Inventario</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('inventory_report_kardex', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'reports' && $secondLevel === 'valued-kardex' ? 'active' : '' }}"
                                href="{{ route('reports.valued_kardex.index') }}">
                                <span class="label">Kardex valorizado</span>
                            </a>
                        </li>
                    @endif
                    {{-- @if ($inventory_configuration->inventory_review) --}}
                    <li class="{{ $firstLevel === 'inventory-review' ? 'nav-active' : '' }}">
                        <a class="nav-link" href="{{ route('tenant.inventory-review.index') }}">
                            <span class="label">Revisión de
                                inventario</span></a>
                    </li>
                    {{-- @endif --}}

                    <li>
                        <a class="{{ $firstLevel === 'reports' && $secondLevel === 'stock_date' ? 'active' : '' }}"
                            href="{{ route('tenant.stock-date.index') }}">
                            <span class="label">Stock Histórico </span>
                        </a>
                    </li>

                    <li>
                        <a class="{{ $firstLevel === 'reports' && $secondLevel === 'average-cost' ? 'active' : '' }}"
                            href="{{ route('reports.kardexaverage.index') }}">
                            <span class="label">Kardex costo promedio</span>
                        </a>
                    </li>

                </ul>
            </li>
        @endif
        @if (in_array('establishments', $vc_modules))
            <li class="mega">
                <a href="#establishments" data-bs-toggle="collapse" data-role="button"
                    aria-expanded="{{ $firstLevel === 'users' ? true : false }}{{ $firstLevel === 'establishments' ? true : false }}"
                    class="{{ $firstLevel === 'users' ? 'active' : '' }}{{ $firstLevel === 'establishments' ? 'active' : '' }}"
                    data-clicked="{{ $firstLevel === 'user' ? true : false }}{{ $firstLevel === 'establishments' ? true : false }}">
                    <i data-cs-icon="user" class="icon" data-cs-size="18"></i>
                    <span class="label">Usuarios/Locales & Series</span>
                </a>
                <ul id="establishments"
                    class="collapse{{ in_array($firstLevel, ['users', 'establishments']) ? 'show' : '' }}"
                    id="establishments" data-parent="#accordionExample">
                    @if (in_array('users', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'users' ? 'active' : '' }}"
                                href="{{ route('tenant.users.index') }}">
                                <span class="label">Usuarios</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('users_establishments', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'establishments' ? 'active' : '' }}"
                                href="{{ route('tenant.establishments.index') }}">
                                <span class="label">Establecimientos </span>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        @if (in_array('advanced', $vc_modules) && $vc_company->soap_type_id != '03')
            <li class="mega">
                <a href="#advanced"
                    data-active="{{ $firstLevel === 'retentions' || $firstLevel === 'dispatches' || $firstLevel === 'perceptions' || $firstLevel === 'drivers' || $firstLevel === 'dispatchers' || $firstLevel === 'dispatchers' || $firstLevel === 'order-forms' || $firstLevel === 'dispatches' || $firstLevel === 'dispatch_carrier' || $firstLevel === 'dispatchers' || $firstLevel === 'drivers' || $firstLevel === 'transports' || $firstLevel === 'origin_addresses' ? true : false }}"
                    aria-expanded="{{ $firstLevel === 'retentions' || $firstLevel === 'dispatches' || $firstLevel === 'perceptions' || $firstLevel === 'drivers' || $firstLevel === 'dispatchers' || $firstLevel === 'dispatchers' || $firstLevel === 'order-forms' || $firstLevel === 'dispatches' || $firstLevel === 'dispatch_carrier' || $firstLevel === 'dispatchers' || $firstLevel === 'drivers' || $firstLevel === 'transports' || $firstLevel === 'origin_addresses' ? true : false }}"
                    data-clicked="{{ $firstLevel === 'retentions' || $firstLevel === 'dispatches' || $firstLevel === 'perceptions' || $firstLevel === 'drivers' || $firstLevel === 'dispatchers' || $firstLevel === 'dispatchers' || $firstLevel === 'order-forms' || $firstLevel === 'dispatches' || $firstLevel === 'dispatch_carrier' || $firstLevel === 'dispatchers' || $firstLevel === 'drivers' || $firstLevel === 'transports' || $firstLevel === 'origin_addresses' ? true : false }}"
                    class="{{ $firstLevel === 'retentions' || $firstLevel === 'dispatches' || $firstLevel === 'perceptions' || $firstLevel === 'drivers' || $firstLevel === 'dispatchers' || $firstLevel === 'dispatchers' || $firstLevel === 'order-forms' || $firstLevel === 'dispatches' || $firstLevel === 'dispatch_carrier' || $firstLevel === 'dispatchers' || $firstLevel === 'drivers' || $firstLevel === 'transports' || $firstLevel === 'origin_addresses' ? 'active' : '' }}">
                    <div class="">
                        <i data-cs-icon="file-text" class="icon" data-cs-size="18"></i>
                        <span class="label">Comprobantes avanzados</span>
                    </div>

                </a>
                <ul id="advanced"
                    class="collapse submenu list-unstyled {{ $firstLevel === 'retentions' || $firstLevel === 'dispatches' || $firstLevel === 'perceptions' || $firstLevel === 'drivers' || $firstLevel === 'dispatchers' || $firstLevel === 'dispatchers' || $firstLevel === 'order-forms' ? 'show' : '' }}"
                    id="advanced" data-parent="#accordionExample">
                    @if (in_array('advanced_retentions', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'retentions' ? 'active' : '' }}"
                                href="{{ route('tenant.retentions.index') }}">
                                <span class="label">Retenciones</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('advanced_perceptions', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'perceptions' ? 'active' : '' }}"
                                href="{{ route('tenant.perceptions.index') }}">
                                <span class="label">Percepciones</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('advanced_order_forms', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'order-forms' ? 'active' : '' }}"
                                href="{{ route('tenant.order_forms.index') }}">
                                <span class="label">Ordenes de Pedido</span>
                            </a>
                        </li>
                    @endif
                    <li class="mega">
                        <a href="#dispatch" data-bs-toggle="collapse" data-role="button"
                            aria-expanded="{{ $firstLevel === 'dispatches' ? true : false }}{{ $firstLevel === 'dispatch_carrier' ? true : false }} {{ $firstLevel === 'dispatchers' ? true : false }} {{ $firstLevel === 'drivers' ? true : false }} {{ $firstLevel === 'transports' ? true : false }} {{ $firstLevel === 'origin_addresses' ? true : false }}"
                            class="{{ $firstLevel === 'dispatches' ? 'active' : '' }}{{ $firstLevel === 'dispatch_carrier' ? 'active' : '' }}{{ $firstLevel === 'dispatchers' ? 'active' : '' }} {{ $firstLevel === 'drivers' ? 'active' : '' }} {{ $firstLevel === 'transports' ? 'active' : '' }} {{ $firstLevel === 'origin_addresses' ? 'active' : '' }}"
                            data-clicked="{{ $firstLevel === 'dispatches' ? true : false }}{{ $firstLevel === 'dispatch_carrier' ? true : false }}{{ $firstLevel === 'dispatchers' ? true : false }} {{ $firstLevel === 'drivers' ? true : false }} {{ $firstLevel === 'transports' ? true : false }} {{ $firstLevel === 'origin_addresses' ? true : false }}">
                            <span class="label"> Guías de remisión</span>
                        </a>
                        <ul id="dispatch"
                            class="collapse {{ $firstLevel === 'dispatches' ? 'show' : '' }}{{ $firstLevel === 'dispatch_carrier' ? 'show' : '' }}{{ $firstLevel === 'dispatchers' ? 'show' : '' }} {{ $firstLevel === 'drivers' ? 'show' : '' }} {{ $firstLevel === 'transports' ? 'show' : '' }} {{ $firstLevel === 'origin_addresses' ? 'show' : '' }}">
                            <li>
                                <a class="{{ $firstLevel === 'dispatches' ? 'active' : '' }}"
                                    href="{{ route('tenant.dispatches.index') }}">
                                    <span class="label">G.R. Remitente</span>
                                </a>
                            </li>
                            <li>
                                <a class="{{ $firstLevel === 'dispatch_carrier' ? 'active' : '' }}"
                                    href="{{ route('tenant.dispatch_carrier.index') }}">
                                    <span class="label">G.R. Transportista</span>
                                </a>
                            </li>
                            <li>
                                <a class="{{ $firstLevel === 'dispatchers' ? 'active' : '' }}"
                                    href="{{ route('tenant.dispatchers.index') }}">
                                    <span class="label">Transportistas</span>
                                </a>
                            </li>
                            <li>
                                <a class="{{ $firstLevel === 'drivers' ? 'active' : '' }}"
                                    href="{{ route('tenant.drivers.index') }}">
                                    <span class="label">Conductores</span>
                                </a>
                            </li>
                            <li>
                                <a class="{{ $firstLevel === 'transports' ? 'active' : '' }}"
                                    href="{{ route('tenant.transports.index') }}">
                                    <span class="label">Vehículos</span>
                                </a>
                            </li>
                            <li>
                                <a class="{{ $firstLevel === 'origin_addresses' ? 'active' : '' }}"
                                    href="{{ route('tenant.origin_addresses.index') }}">
                                    <span class="label"> Direcciones de Partida</span>
                                </a>
                            </li>

                        </ul>
                    </li>
                </ul>
            </li>
        @endif
        @if (in_array('reports', $vc_modules))
            <li>
                <a href="{{ url('list-reports') }}"
                    class="{{ $firstLevel === 'reports' || in_array($firstLevel, ['purchases', 'search', 'sales', 'customers', 'items', 'general-items', 'consistency-documents', 'quotations', 'sale-notes', 'cash', 'commissions', 'document-hotels', 'validate-documents', 'document-detractions', 'commercial-analysis', 'order-notes-consolidated', 'order-notes-general', 'sales-consolidated', 'user-commissions', 'fixed-asset-purchases', 'massive-downloads']) ? 'active' : '' }}">
                    <i data-cs-icon="file-chart" class="icon" data-cs-size="18"></i>
                    <span class="label">Reportes</span>
                </a>
            </li>
        @endif
        @if (in_array('accounting', $vc_modules))
            <li class="mega">
                {{-- <a href="#accounting" data-toggle="collapse"
                    data-clicked="{{ $firstLevel === 'account' ? 'true' : false }}"
                    data-active="{{ $firstLevel === 'account' ? 'true' : false }}"
                    aria-expanded="{{ $firstLevel === 'account' ? 'true' : false }}"
                    class="{{ $firstLevel === 'account' ? 'active' : '' }}">
                    <i data-cs-icon="board-2" class="icon" data-cs-size="18"></i>
                    <span class="label">Contabilidad</span>
                </a> --}}
                <a href="#accounting" data-bs-toggle="collapse" data-role="button"
                    aria-expanded="{{ in_array($firstLevel, ['account']) ? true : false }}{{ in_array($secondLevel, ['format', 'ple']) ? true : false }}"
                    class="{{ in_array($firstLevel, ['account']) ? true : false }}{{ in_array($secondLevel, ['format', 'ple']) ? true : false }}"
                    data-clicked="{{ in_array($firstLevel, ['account']) ? true : false }}{{ in_array($secondLevel, ['format', 'ple']) ? true : false }}">
                    <i data-cs-icon="board-2" class="icon" data-cs-size="18"></i>
                    <span class="label">Contabilidad</span>
                </a>
                <ul class="collapse {{ $firstLevel === 'account' ? 'show' : '' }}" id="accounting">
                    @if (in_array('account_report', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'account' && $secondLevel === 'format' ? 'active' : '' }}"
                                href="{{ route('tenant.account_format.index') }}">
                                <span class="label">Libros en Excel</span>
                            </a>
                        </li>
                    @endif

                    <li>
                        <a class="{{ $firstLevel === 'account' && $secondLevel === 'ple' ? 'active' : '' }}"
                            href="{{ route('tenant.account_ple.index') }}">
                            <span class="label">Libros Electrónicos</span>
                        </a>
                    </li>

                    @if (in_array('account_formats', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'account' && $secondLevel == '' ? 'active' : '' }}"
                                href="{{ route('tenant.account.index') }}">
                                <span class="label">Sistemas Contables</span></a>
                        </li>
                    @endif
                    @if (in_array('account_summary', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'account' && $secondLevel == 'summary-report' ? 'active' : '' }}"
                                href="{{ route('tenant.account_summary_report.index') }}">.
                                <span class="label">Resumen de Venta</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('account_summary', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'account' && $secondLevel == 'tax_return' ? 'active' : '' }}"
                                href="{{ route('tenant.tax_return.index') }}">
                                <span class="label">Declaración Mensual
                                    SUNAT</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
            <li class="mega">

                <a href="#sire" data-bs-toggle="collapse" data-role="button"
                    aria-expanded="{{ in_array($firstLevel, ['sire']) ? true : false }}{{ in_array($secondLevel, ['appendix']) ? true : false }}"
                    class="{{ in_array($firstLevel, ['sire']) ? true : false }}{{ in_array($secondLevel, ['appendix']) ? true : false }}"
                    data-clicked="{{ in_array($firstLevel, ['sire']) ? true : false }}{{ in_array($secondLevel, ['appendix']) ? true : false }}">
                    <i data-cs-icon="board-2" class="icon" data-cs-size="18"></i>
                    <span class="label">SIRE</span>
                </a>
                <ul class="collapse {{ $firstLevel === 'sire' ? 'show' : '' }}" id="sire">
                    <li>
                        <a class="{{ $firstLevel === 'sire' && $secondLevel === 'appendix' ? 'active' : '' }}"
                            href="{{ url('/sire/appendix') }}">
                            <span class="label">Anexos </span>
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        @if (in_array('finance', $vc_modules))

            <li class="mega">
                <a href="#finance" data-toggle="collapse"
                    data-active="{{ $firstLevel === 'finances' && in_array($secondLevel, ['global-payments', 'balance', 'payment-method-types', 'unpaid', 'to-pay', 'income', 'movements']) ? 'true' : 'false' }}"
                    aria-expanded="{{ $firstLevel === 'finances' && in_array($secondLevel, ['global-payments', 'balance', 'payment-method-types', 'unpaid', 'to-pay', 'income', 'movements']) ? 'true' : 'false' }}"
                    class="dropdown-toggle">
                    <i data-cs-icon="dollar" class="icon" data-cs-size="18"></i>
                    <span class="label">Finanzas</span>
                </a>
                <ul class="collapse submenu list-unstyled {{ $firstLevel === 'finances' && in_array($secondLevel, ['global-payments', 'balance', 'payment-method-types', 'unpaid', 'to-pay', 'income', 'movements']) ? 'show' : '' }}"
                    id="finance" data-parent="#accordionExample">
                    <li class="{{ $firstLevel === 'bill-of-exchange' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('tenant.bill_of_exchange.index') }}">
                            <span class="label">Letras de cambio</span>
                        </a>
                    </li>
                    @if (in_array('finances_movements', $vc_module_levels))
                        <li class="{{ $firstLevel === 'finances' && $secondLevel == 'movements' ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('tenant.finances.movements.index') }}">
                                <span class="label">Movimientos</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('finances_incomes', $vc_module_levels))
                        <li class="{{ $firstLevel === 'finances' && $secondLevel == 'income' ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('tenant.finances.income.index') }}">
                                <span class="label">Ingresos</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('finances_unpaid', $vc_module_levels))
                        <li class="{{ $firstLevel === 'finances' && $secondLevel == 'unpaid' ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('tenant.finances.unpaid.index') }}">Cuentas por
                                cobrar</a>
                        </li>
                    @endif
                    @if (in_array('finances_to_pay', $vc_module_levels))
                        <li class="{{ $firstLevel === 'finances' && $secondLevel == 'to-pay' ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('tenant.finances.to_pay.index') }}">Cuentas por
                                pagar</a>
                        </li>
                    @endif
                    @if (in_array('finances_payments', $vc_module_levels))
                        <li
                            class="{{ $firstLevel === 'finances' && $secondLevel == 'global-payments' ? 'active' : '' }}">
                            <a class="nav-link"
                                href="{{ route('tenant.finances.global_payments.index') }}">Pagos</a>
                        </li>
                    @endif
                    @if (in_array('finances_balance', $vc_module_levels))
                        <li class="{{ $firstLevel === 'finances' && $secondLevel == 'balance' ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('tenant.finances.balance.index') }}">Balance</a>
                        </li>
                    @endif
                    @if (in_array('finances_payment_method_types', $vc_module_levels))
                        <li
                            class="{{ $firstLevel === 'finances' && $secondLevel == 'payment-method-types' ? 'active' : '' }}">
                            <a class="nav-link"
                                href="{{ route('tenant.finances.payment_method_types.index') }}">Ingresos
                                y Egresos - M. Pago</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (in_array('woocommerce', $vc_modules))
            <li>
                <a href="{{ url('list-settings') }}"
                    data-active="{{ in_array($firstLevel, ['list-platforms', 'list-cards', 'list-currencies', 'list-bank-accounts', 'list-banks', 'list-attributes', 'list-detractions', 'list-units', 'list-payment-methods', 'list-incomes', 'list-payments', 'company_accounts', 'list-vouchers-type', 'companies', 'advanced', 'tasks', 'inventories', 'bussiness_turns', 'offline-configurations', 'series-configurations', 'configurations', 'login-page', 'list-settings']) ? true : false }}"
                    aria-expanded="{{ in_array($firstLevel, ['list-platforms', 'list-cards', 'list-currencies', 'list-bank-accounts', 'list-banks', 'list-attributes', 'list-detractions', 'list-units', 'list-payment-methods', 'list-incomes', 'list-payments', 'company_accounts', 'list-vouchers-type', 'companies', 'advanced', 'tasks', 'inventories', 'bussiness_turns', 'offline-configurations', 'series-configurations', 'configurations', 'login-page', 'list-settings']) ? true : false }}"
                    class="{{ in_array($firstLevel, ['list-platforms', 'list-cards', 'list-currencies', 'list-bank-accounts', 'list-banks', 'list-attributes', 'list-detractions', 'list-units', 'list-payment-methods', 'list-incomes', 'list-payments', 'company_accounts', 'list-vouchers-type', 'companies', 'advanced', 'tasks', 'inventories', 'bussiness_turns', 'offline-configurations', 'series-configurations', 'configurations', 'login-page', 'list-settings']) ? 'active' : '' }}">
                    <i data-cs-icon="tool" class="icon" data-cs-size="18"></i>
                    <span class="label">Configuración</span>
                </a>
            </li>
        @endif
        @if (in_array('cuenta', $vc_modules))
            <li class="mega">
                <a href="#cuenta" data-bs-toggle="collapse" data-role="button"
                    aria-expanded="{{ $firstLevel === 'cuenta' ? true : false }}"
                    class="{{ $firstLevel === 'cuenta' ? 'active' : '' }}"
                    data-clicked="{{ $firstLevel === 'cuenta' ? true : false }}">
                    <i data-cs-icon="money" class="icon" data-cs-size="18"></i>
                    <span class="label">Mis Pagos</span>

                </a>
                <ul id="cuenta"
                    class="collapse {{ $firstLevel === 'cuenta' && $secondLevel === 'payment_index' ? 'show' : '' }}">
                    @if (in_array('account_users_settings', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'cuenta' && $secondLevel === 'configuration' ? 'active' : '' }}"
                                href="{{ route('tenant.configuration.index') }}">Configuracion</a>
                        </li>
                    @endif
                    @if (in_array('account_users_list', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'cuenta' && $secondLevel === 'payment_index' ? 'active' : '' }}"
                                href="{{ route('tenant.payment.index') }}">Lista
                                de
                                Pagos</a>
                        </li>
                    @endif

                </ul>
            </li>
        @endif
        @if (in_array('configuration', $vc_modules))
            <li
                class="{{ in_array($firstLevel, ['list-platforms', 'list-cards', 'list-currencies', 'list-bank-accounts', 'list-banks', 'list-attributes', 'list-detractions', 'list-units', 'list-payment-methods', 'list-incomes', 'list-payments', 'company_accounts', 'list-vouchers-type', 'companies', 'advanced', 'tasks', 'inventories', 'bussiness_turns', 'offline-configurations', 'series-configurations', 'configurations', 'login-page', 'list-settings']) ? 'nav-active' : '' }}">
                <a class="nav-link" href="{{ url('list-settings') }}">
                    <i data-cs-icon="gear" class="icon" data-cs-size="18"></i>
                    <span class="label">Configuración</span>
                </a>
            </li>
        @endif

        @if (in_array('hotels', $vc_modules))
            <li class="mega">
                <a href="#hotels" data-toggle="collapse"
                    data-active="{{ $firstLevel === 'hotels' ? true : false }}"
                    aria-expanded="{{ $firstLevel === 'hotels' ? true : false }}"
                    class="{{ $firstLevel === 'hotels' ? 'active' : '' }}">
                    <i data-cs-icon="building" class="icon" data-cs-size="18"></i>
                    <span class="label">Hoteles</span>
                </a>
                <ul id="hotels"
                    class="collapse submenu list-unstyled {{ $firstLevel === 'hotels' ? 'show' : '' }}"
                    id="hotels" data-parent="#accordionExample">
                    @if (in_array('hotels_reception', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'hotels' && $secondLevel === 'reception' ? 'active' : '' }}"
                                href="{{ url('hotels/reception') }}">Recepción</a>
                        </li>
                    @endif
                    @if (in_array('hotels_rates', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'hotels' && $secondLevel === 'rates' ? 'active' : '' }}"
                                href="{{ url('hotels/rates') }}">Tarifas</a>
                        </li>
                    @endif
                    @if (in_array('hotels_floors', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'hotels' && $secondLevel === 'floors' ? 'active' : '' }}"
                                href="{{ url('hotels/floors') }}">Pisos</a>
                        </li>
                    @endif
                    @if (in_array('hotels_cats', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'hotels' && $secondLevel === 'categories' ? 'active' : '' }}"
                                href="{{ url('hotels/categories') }}">Categorías</a>
                        </li>
                    @endif
                    @if (in_array('hotels_rooms', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'hotels' && $secondLevel === 'rooms' ? 'active' : '' }}"
                                href="{{ url('hotels/rooms') }}">Habitaciones</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        @if (in_array('documentary-procedure', $vc_modules))
            <li class="mega">
                <a href="#documentary-procedure" data-toggle="collapse"
                    data-active="{{ $firstLevel === 'documentary-procedure' ? true : false }}"
                    aria-expanded="{{ $firstLevel === 'documentary-procedure' ? true : false }}"
                    class="{{ $firstLevel === 'documentary-procedure' ? 'active' : '' }}">
                    <i data-cs-icon="building-large" class="icon" data-cs-size="18"></i>
                    <span class="label">Trámite documentario</span>
                </a>
                <ul class="collapse submenu list-unstyled {{ $firstLevel === 'documentary-procedure' ? 'show' : '' }}"
                    id="documentary-procedure" data-parent="#accordionExample">
                    @if (in_array('documentary_offices', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'documentary-procedure' && $secondLevel === 'offices' ? 'active' : '' }}"
                                href="{{ route('documentary.offices') }}">Listado
                                de Etapas</a>
                        </li>
                    @endif
                    @if (in_array('documentary_requirements', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'documentary-procedure' && $secondLevel === 'requirements' ? 'active' : '' }}"
                                href="{{ route('documentary.requirements') }}">Listado de
                                requerimientos</a>
                        </li>
                    @endif
                    @if (in_array('documentary_process', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'documentary-procedure' && $secondLevel === 'processes' ? 'active' : '' }}"
                                href="{{ route('documentary.processes') }}">Tipos
                                de tramites</a>
                        </li>
                    @endif

                    @if (in_array('documentary_files', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'documentary-procedure' && $secondLevel === 'files' ? 'active' : '' }}"
                                href="{{ route('documentary.files') }}">Listado
                                de tramites</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (in_array('digemid', $vc_modules) && $configuration->isPharmacy())
            <li class="mega">
                <a data-bs-toggle="collapse" data-role="button"
                    aria-expanded="{{ $firstLevel === 'digemid' ? true : false }}"
                    class="{{ $firstLevel === 'digemid' ? 'active' : '' }}"
                    data-clicked="{{ $firstLevel === 'digemid' ? true : false }}" href="#digemid">
                    <i class="fa fas fa-calendar-check" aria-hidden="true"></i>
                    <span class="label">
                        Farmácia
                    </span>
                </a>
                <ul id="digemid" class="collapse {{ $firstLevel === 'digemid' ? 'show' : '' }}">
                    @if (in_array('digemid', $vc_module_levels))
                        <li>
                            <a class="{{ $firstLevel === 'digemid' ? 'active' : '' }}"
                                href="{{ route('tenant.digemid.index') }}">Productos</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (in_array('full_suscription_app', $vc_modules))
            <li class="mega">
                <a data-bs-toggle="collapse" data-role="button"
                    aria-expanded="{{ $firstLevel === 'full_suscription' ? true : false }}"
                    class="{{ $firstLevel === 'full_suscription' ? 'active' : '' }}"
                    data-clicked="{{ $firstLevel === 'full_suscription' ? true : false }}" href="#saas">
                    <i class="fa fas fa-calendar-check" aria-hidden="true"></i>
                    <span class="label">
                        Suscripción Servicios SAAS
                    </span>
                </a>
                <ul id="saas" class="collapse {{ $firstLevel === 'full_suscription' ? 'show' : '' }}">
                    <li>
                        <a class=" {{ $firstLevel === 'full_suscription' && $secondLevel === 'client' ? 'active' : '' }}"
                            href="{{ route('tenant.fullsuscription.client.index') }}">
                            Clientes
                        </a>
                    </li>
                    <li>
                        <a class="{{ $firstLevel === 'full_suscription' && $secondLevel === 'plans' ? 'active' : '' }}"
                            href="{{ route('tenant.fullsuscription.plans.index') }}">
                            Planes
                        </a>
                    </li>
                    <li>
                        <a class="{{ $firstLevel === 'full_suscription' && $secondLevel === 'payments' ? 'active' : '' }}"
                            href="{{ route('tenant.fullsuscription.payments.index') }}">
                            Suscripciones
                        </a>
                    </li>
                    <li>
                        <a cclass="{{ $firstLevel === 'full_suscription' && $secondLevel === 'payment_receipt' ? 'active' : '' }}"
                            href="{{ route('tenant.fullsuscription.payment_receipt.index') }}">
                            Recibos de pago
                        </a>
                    </li>
                </ul>
            </li>
        @endif
        @if (in_array('suscription_app', $vc_modules))
            <li class="mega">
                <a data-bs-toggle="collapse" data-role="button"
                    aria-expanded="{{ $firstLevel === 'suscription' ? true : false }}"
                    class="{{ $firstLevel === 'suscription' ? 'active' : '' }}"
                    data-clicked="{{ $firstLevel === 'suscription' ? true : false }}" href="#suscription_app">
                    <i class="fa fas fa-calendar-check" aria-hidden="true"></i>
                    <span class="label">Suscripción Escolar</span>
                </a>
                <ul id="suscription_app" class="collapse {{ $firstLevel === 'suscription' ? 'show' : '' }}">
                    {{-- <li class="mega">
                        <a href="#clientes" data-bs-toggle="collapse" data-role="button"
                            aria-expanded="{{ $firstLevel === 'summaries' ? true : false }}{{ $firstLevel === 'voided' ? true : false }}"
                            class="{{ $firstLevel === 'summaries' ? 'active' : '' }}{{ $firstLevel === 'voided' ? 'active' : '' }}"
                            data-clicked="{{ $firstLevel === 'summaries' ? true : false }}{{ $firstLevel === 'voided' ? true : false }}">
                            <span class="label"> Clientes</span>
                        </a>
                        <ul id="clientes"
                            class="collapse {{ $firstLevel === 'suscription' && $secondLevel === 'client' ? 'show' : '' }}">
                         
                            <li>
                         
                        </ul>
                    </li> --}}
                    <li>
                        <a class="{{ $firstLevel === 'suscription' && $secondLevel === 'client' && $thridLevel !== 'childrens' ? 'active' : '' }}"
                            href="{{ route('tenant.suscription.client.index') }}">

                            @if (isset($vc_suscription_name) && isset($vc_suscription_name->parents))
                                {{ $vc_suscription_name->parents }}
                            @else
                                Padres
                            @endif
                        </a>
                    </li>
                    <li>
                        <a class="{{ $firstLevel === 'suscription' && $secondLevel === 'client' && $thridLevel === 'childrens' ? 'active' : '' }}"
                            href="{{ route('tenant.suscription.client_children.index') }}">
                            @if (isset($vc_suscription_name) && isset($vc_suscription_name->children))
                                {{ $vc_suscription_name->children }}
                            @else
                                Hijos
                            @endif

                        </a>
                    </li>
                    <li>
                        <a class="{{ $firstLevel === 'documents' && $secondLevel === 'create' ? 'active' : '' }}"
                            href="{{ route('tenant.documents.create') }}">Nuevo</a>
                    </li>
                    <li>
                        <a class="{{ $firstLevel === 'suscription' && $secondLevel === 'plans' ? 'active' : '' }}"
                            href="{{ route('tenant.suscription.plans.index') }}">
                            Planes
                        </a>
                    </li>

                    <li>
                        <a class="{{ $firstLevel === 'suscription' && $secondLevel === 'payments' ? 'active' : '' }}"
                            href="{{ route('tenant.suscription.payments.index') }}">
                            Matrículas
                        </a>
                    </li>
                    <li>
                        <a class="{{ $firstLevel === 'suscription' && $secondLevel === 'payment_receipt' ? 'active' : '' }}"
                            href="{{ route('tenant.suscription.payment_receipt.index') }}">
                            Recibos de pago
                        </a>
                    </li>


                    <li>
                        <a class="{{ $firstLevel === 'suscription' && $secondLevel === 'grade_section' ? 'active' : '' }}"
                            href="{{ route('tenant.suscription.grade_section.index') }}">
                            @php
                                $name = null;
                                if (isset($vc_suscription_name) && isset($vc_suscription_name->grades)) {
                                    $name = $vc_suscription_name->grades;
                                }
                                if (isset($vc_suscription_name) && isset($vc_suscription_name->sections)) {
                                    $name = $name ? $name . ' y ' . $vc_suscription_name->sections : $vc_suscription_name->sections;
                                }
                            @endphp
                            @if ($name)
                                {{ $name }}
                            @else
                                Grados y Secciones
                            @endif

                        </a>
                    </li>
                </ul>
            </li>
        @endif
        @if (in_array('production_app', $vc_modules))
            <li class="mega">
                <a data-bs-toggle="collapse" data-role="button"
                    aria-expanded="{{ $firstLevel === 'production' || $firstLevel === 'mill-production' || $firstLevel === 'machine-type-production' || $firstLevel === 'machine-production' || $firstLevel === 'packaging' || $firstLevel === 'workers' ? true : false }}"
                    class="{{ $firstLevel === 'production' || $firstLevel === 'mill-production' || $firstLevel === 'machine-type-production' || $firstLevel === 'machine-production' || $firstLevel === 'packaging' || $firstLevel === 'workers' ? 'active' : '' }}"
                    data-clicked="{{ $firstLevel === 'production' || $firstLevel === 'mill-production' || $firstLevel === 'machine-type-production' || $firstLevel === 'machine-production' || $firstLevel === 'packaging' || $firstLevel === 'workers' ? true : false }}"
                    href="#production">
                    <i class="fa fas fa-calendar-check" aria-hidden="true"></i>
                    <span class="label">Producción</span>
                </a>
                <ul id="production"
                    class="collapse {{ $firstLevel === 'production' || $firstLevel === 'mill-production' || $firstLevel === 'machine-type-production' || $firstLevel === 'machine-production' || $firstLevel === 'packaging' || $firstLevel === 'workers' ? 'show' : '' }}">

                    <li>
                        <a class="{{ $firstLevel === 'production' ? 'active' : '' }}"
                            href="{{ route('tenant.production.index') }}">Productos Fabricados</a>
                    </li>
                    <li>
                        <a class="{{ $firstLevel === 'mill-production' ? 'active' : '' }}"
                            href="{{ route('tenant.mill_production.index') }}">
                            Ingresos de Insumos
                        </a>
                    </li>

                    <li>
                        <a class="{{ $firstLevel === 'machine-type-production' ? 'active' : '' }}"
                            href="{{ route('tenant.machine_type_production.index') }}">
                            Tipos de maquinarias
                        </a>
                    </li>
                    <li>
                        <a class="{{ $firstLevel === 'machine-production' ? 'active' : '' }}"
                            href="{{ route('tenant.machine_production.index') }}">
                            Maquinarias
                        </a>
                    </li>
                    <li>
                        <a class="{{ $firstLevel === 'packaging' ? 'active' : '' }}"
                            href="{{ route('tenant.packaging.index') }}">
                            Zona de embalaje
                        </a>
                    </li>
                    <li>
                        <a class="{{ $firstLevel === 'workers' ? 'active' : '' }}"
                            href="{{ route('tenant.workers.index') }}">
                            Empleados
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        @if (in_array('app_2_generator', $vc_modules))
            <li>
                <a class="{{ $firstLevel === 'live-app' ? 'active' : '' }}"
                    href="{{ route('tenant.liveapp.configuration') }}">
                    <i class="fas fa-puzzle-piece"></i>
                    <span class="label">Generador APP 2.0</span>
                </a>
            </li>
        @endif
        @if (in_array('generate_link_app', $vc_modules))
            <li>
                <a class="{{ $firstLevel === 'live-app' ? 'active' : '' }}"
                    href="{{ route('tenant.payment.generate.index') }}">
                    <i class="fas fa-puzzle-piece"></i>
                    <span class="label">Generador link de Pago</span>
                </a>
            </li>
        @endif
        {{-- APP --}}
        @if (in_array('apps', $vc_modules))
            <li>
                <a class="{{ $firstLevel === 'list-extras' ? 'active' : '' }}" href="{{ url('list-extras') }}">
                    <i class="fas fa-cube"></i>
                    <span class="label">Apps</span>
                </a>
            </li>
        @endif

    </ul>
</div>
<script>
    // Maintain Scroll Position
    if (typeof localStorage !== 'undefined') {
        if (localStorage.getItem('sidebar-left-position') !== null) {
            var initialPosition = localStorage.getItem('sidebar-left-position'),
                sidebarLeft = document.querySelector('#sidebar-left .nano-content');
            sidebarLeft.scrollTop = initialPosition;
        }
    }
</script>

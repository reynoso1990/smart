<?php
use Illuminate\Support\Facades\Route;
Route::get('generate_token', 'Tenant\Api\AppController@getSeries');
$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);
if ($hostname) {
    Route::domain($hostname->fqdn)->group(function () {

        Route::post('login', 'Tenant\Api\AppController@login');

            // Route::prefix('clinica')->group(function () {
                //para sistema de clinica
                // Route::get('document/metodos_filtro', 'Tenant\Api\ClinicaController@metodos_filtro');
                // Route::get('report/format/download', 'Tenant\Api\ClinicaController@download_report');
                // Route::post('documents/status', 'Tenant\Api\ClinicaController@documentStatus');
                // Route::post('document/emailLS', 'Tenant\Api\ClinicaController@document_email');
                // Route::get('document/tipo_doc', 'Tenant\Api\ClinicaController@getTypeDoc');
            // });
              //app loreto inventario
              Route::prefix('inventory')->group(function () {
                Route::get('search_items', '\modules\Inventory\Http\Controllers\InventoryController@searchItems');
                });

        //reportes caja
        Route::get('cash/report/products/{cash}', 'Tenant\Api\AppController@report_products');
        Route::get('cash/report/report-ticket/{cash}', 'Tenant\Api\AppController@reportTicket');
        Route::get('cash/report/report-a4/{cash}', 'Tenant\Api\AppController@reportA4');
        Route::get('cash/report/income-summary/{cash}', 'Tenant\Api\AppController@pdf');
        Route::get('cash/report/products/{cash}/ticket', 'Tenant\Api\AppController@report_products_ticket');
        Route::post('items/uploads', 'Tenant\ItemController@upload');

        Route::middleware(['auth:api', 'locked.tenant'])->group(function () {

            Route::prefix('restaurant')->group(function () {

                Route::get('search_orden_document',[Modules\Restaurant\Http\Controllers\PosController::class, 'search_orden_document']);
                Route::post('printevent',[Modules\Restaurant\Http\Controllers\OrdenController::class, 'printevent']);
                Route::get('pos/listtables',[Modules\Restaurant\Http\Controllers\PosController::class, 'listtables']);
                Route::get('totales_sales',[Modules\Restaurant\Http\Controllers\PosController::class, 'total_sales']);
                Route::get('ordens-items', '\Modules\Restaurant\Http\Controllers\OrdenItemController@records');
                Route::get('tables/records-area/{area_id}', '\Modules\Restaurant\Http\Controllers\TableController@recordsByArea');
                Route::get('data_tables/{area_id}/{pin}', '\Modules\Restaurant\Http\Controllers\DashboardController@data_tables');
                Route::post('loginping','Tenant\AuthenticateController@loginping');
                Route::post('authenticate','Tenant\AuthenticateController@authenticate');
                Route::post('change-observation', '\Modules\Restaurant\Http\Controllers\OrdenItemController@updateObservation');
                Route::post('worker/send-orden', '\Modules\Restaurant\Http\Controllers\OrdenController@store');
                Route::get('ordens-ready/{id}', '\Modules\Restaurant\Http\Controllers\OrdenItemController@ordenReady');
                Route::post('cancel-orden', '\Modules\Restaurant\Http\Controllers\OrdenController@cancelOrden');
                Route::delete('delete-orden/{id}', '\Modules\Restaurant\Http\Controllers\OrdenItemController@ordenDelete');
                //Person
                Route::get('persons/columns', 'Tenant\PersonController@columns');
                Route::get('persons/tables', 'Tenant\PersonController@tables');
                Route::get('persons/{type}', 'Tenant\PersonController@index')->name('tenant.persons.index');
                Route::get('persons/{type}/records', 'Tenant\PersonController@records');
                Route::get('persons/record/{person}', 'Tenant\PersonController@record');
                Route::post('persons/', 'Tenant\PersonController@store');
                Route::delete('persons/{person}', 'Tenant\PersonController@destroy');
                Route::post('persons/import', 'Tenant\PersonController@import');
                Route::get('persons/enabled/{type}/{person}', 'Tenant\PersonController@enabled');
                Route::get('persons/{type}/exportation', 'Tenant\PersonController@export')->name('tenant.persons.export');
                Route::get('persons/export/barcode/print', 'Tenant\PersonController@printBarCode')->name('tenant.persons.export.barcode.print');
                Route::get('persons/barcode/{item}', 'Tenant\PersonController@generateBarcode');
                Route::get('persons/search/{barcode}', 'Tenant\PersonController@getPersonByBarcode');
                Route::get('persons/accumulated-points/{id}', 'Tenant\PersonController@getAccumulatedPoints');

              //configurations
              Route::get('configurations/record', 'Tenant\ConfigurationController@record');
             //Items
            Route::get('items/columns', 'Tenant\ItemController@columns');
            Route::get('items/records', 'Tenant\ItemController@records_restaurant');
            Route::get('items/tables', 'Tenant\ItemController@tables');
            Route::get('items/record/{item}', 'Tenant\ItemController@record');
            Route::post('items', 'Tenant\ItemController@store');
            Route::delete('items/{item}', 'Tenant\@destroy');
            Route::post('items/import', 'Tenant\ItemController@import');
            Route::post('items/categories', 'Tenant\Api\CategoryController@store');
            Route::get('items/categories/columns', 'Tenant\Api\CategoryController@columns');
            Route::get('items/categories/records', 'Tenant\Api\CategoryController@records');
            Route::get('items/categories/record/{id}', 'Tenant\Api\CategoryController@record');
            Route::delete('items/categories/{id}', 'Tenant\Api\CategoryController@destroy');

            Route::post('brands', 'BrandController@store');
            Route::post('items/catalog', 'Tenant\ItemController@catalog');
            Route::get('items/import/tables', 'Tenant\ItemController@tablesImport');
            Route::post('items/upload', 'Tenant\ItemController@upload');
            Route::post('items/visible_store', 'Tenant\ItemController@visibleStore');
            Route::post('items/duplicate', 'Tenant\ItemController@duplicate');
            Route::get('items/disable/{item}', 'Tenant\ItemController@disable');
            Route::get('items/enable/{item}', 'Tenant\ItemController@enable');
            Route::get('items/images/{item}', 'Tenant\ItemController@images');
            Route::get('items/images/delete/{id}', 'Tenant\ItemController@delete_images');
            Route::get('items/export', 'Tenant\ItemController@export')->name('tenant.items.export');
            Route::get('items/export/wp', 'Tenant\ItemController@exportWp')->name('tenant.items.export.wp');
            Route::get('items/export/digemid', 'Tenant\ItemController@exportDigemid');
            Route::get('items/search-items', 'Tenant\ItemController@searchItems');
            Route::get('items/search/item/{item}', 'Tenant\ItemController@searchItemById');
            Route::get('items/item/tables', 'Tenant\ItemController@item_tables');
            Route::get('items/export/barcode', 'Tenant\ItemController@exportBarCode')->name('tenant.items.export.barcode');
            Route::get('items/export/extra_atrributes/PDF', 'Tenant\ItemController@downloadExtraDataPdf');
            Route::get('items/export/extra_atrributes/XLSX', 'Tenant\ItemController@downloadExtraDataItemsExcel');
            Route::get('items/export/barcode_full', 'Tenant\ItemController@exportBarCodeFull');
            Route::get('items/export/barcode/print', 'Tenant\ItemController@printBarCode')->name('tenant.items.export.barcode.print');
            Route::get('items/export/barcode/print_x', 'Tenant\ItemController@printBarCodeX')->name('tenant.items.export.barcode.print.x');
            Route::get('items/export/barcode/last', 'Tenant\ItemController@itemLast')->name('tenant.items.last');

              //Establishments
              Route::get('establishments/create', 'Tenant\EstablishmentController@create');
              Route::get('establishments/tables', 'Tenant\EstablishmentController@tables');
              Route::get('establishments/record/{establishment}', 'Tenant\EstablishmentController@record');
              Route::post('establishments', 'Tenant\EstablishmentController@store');
              Route::get('establishments/records', 'Tenant\EstablishmentController@records');
              Route::delete('establishments/{establishment}', 'Tenant\EstablishmentController@destroy');

              Route::get('ordens/records', 'Tenant\Api\OrdenController@records');
              Route::get('food-list/records', 'Tenant\Api\FoodController@records');
              Route::get('food-list/record/{id}', 'Tenant\Api\FoodController@record');
              Route::get('food-list/delete-image/{id}', 'Tenant\Api\FoodController@deleteImage');
              Route::get('food-list/{id}', 'Tenant\Api\FoodController@active');
              Route::post('food-list/upload-image', 'Tenant\Api\FoodController@uploadImage');
              Route::post('food-list', 'Tenant\Api\FoodController@store');

              Route::get('workers-type/columns', 'Tenant\Api\WorkersTypeController@columns');
              Route::get('workers-type/records', 'Tenant\Api\WorkersTypeController@records');
              Route::get('workers-type/actives', 'Tenant\Api\WorkersTypeController@actives');
              Route::get('workers-type/record/{id}', 'Tenant\Api\WorkersTypeController@record');
              Route::get('workers-type/{id}', 'Tenant\Api\WorkersTypeController@active');
              Route::post('workers-type', 'Tenant\Api\WorkersTypeController@store');
              Route::delete('workers-type/{id}', 'Tenant\Api\WorkersTypeController@destroy');

              Route::get('areas/columns', '\Modules\Restaurant\Http\Controllers\AreaController@columns');
              Route::get('areas/records', '\Modules\Restaurant\Http\Controllers\AreaController@records');
              Route::get('areas/actives', '\Modules\Restaurant\Http\Controllers\AreaController@actives');
              Route::get('areas/record/{id}', '\Modules\Restaurant\Http\Controllers\AreaController@record');
              Route::delete('areas/{id}', '\Modules\Restaurant\Http\Controllers\AreaController@destroy');
              Route::post('areas', '\Modules\Restaurant\Http\Controllers\AreaController@store');

              Route::get('status-tables/records', '\Modules\Restaurant\Http\Controllers\StatusTableController@records');
              Route::get('status-tables/columns', '\Modules\Restaurant\Http\Controllers\StatusTableController@columns');
              Route::get('status-tables/{id}', '\Modules\Restaurant\Http\Controllers\StatusTableController@active');
              Route::get('status-tables/record/{id}', '\Modules\Restaurant\Http\Controllers\StatusTableController@record');
              Route::post('status-tables', '\Modules\Restaurant\Http\Controllers\StatusTableController@store');

              Route::get('status-orden/records', 'Tenant\Api\StatusOrdenController@records');
              Route::get('status-orden/columns', 'Tenant\Api\StatusOrdenController@columns');
              Route::get('status-orden/{id}', 'Tenant\Api\StatusOrdenController@active');
              Route::get('status-orden/record/{id}', 'Tenant\Api\StatusOrdenController@record');
              Route::post('status-orden', 'Tenant\Api\StatusOrdenController@store');

              Route::get('tables/columns', '\Modules\Restaurant\Http\Controllers\TableController@columns');
              Route::get('tables/records', '\Modules\Restaurant\Http\Controllers\TableController@records');
              Route::get('tables/record/{id}', '\Modules\Restaurant\Http\Controllers\TableController@record');
              Route::post('tables', '\Modules\Restaurant\Http\Controllers\TableController@store');
              Route::delete('tables/{id}', '\Modules\Restaurant\Http\Controllers\TableController@destroy');

              Route::get('workers/records', 'Tenant\Api\WorkerController@records');
              Route::get('workers/{id}', 'Tenant\Api\WorkerController@active');
              Route::get('workers/record/{id}', 'Tenant\Api\WorkerController@record');
              Route::post('workers', 'Tenant\Api\WorkerController@store');
              Route::post('destroy', 'Tenant\Api\WorkerController@destroy');

              Route::get('pos/search_orden', 'Tenant\Api\PosController@search');
              Route::post('pos/orden_payment', 'Tenant\Api\PosController@payment');
              Route::get('pos/foods', 'Tenant\Api\PosController@foods');
              Route::get('pos/tables', 'Tenant\PosController@tables');
              Route::get('pos/payment_tables','Tenant\PosController@payment_tables');
            });
            //reorte general
            Route::get('report/format/download', 'Tenant\Api\ClinicaController@download_report');
            // Route::get('report/format/download', 'Modules\Account\Http\Controllers\FormatController@download');
            //categorias
            Route::get('category/details/{id}', 'Tenant\Api\AppController@category_detail');
            Route::delete('category/delete/{id}', 'Tenant\Api\AppController@category_destroy');
            Route::get('categories', 'Tenant\Api\AppController@categories');
            Route::post('categories', 'Tenant\Api\AppController@category');

            //guias
            Route::get('dispatch/list', 'Tenant\Api\AppController@dispatches_list');
            Route::post('dispatch/create', 'Tenant\Api\AppController@dispatches_create');
            Route::post('dispatch/email', 'Tenant\Api\AppController@dispatches_email');
            Route::get('dispatch/series', 'Tenant\Api\AppController@dispatches_series');
            Route::get('dispatch/data', 'Tenant\Api\AppController@dispatches_data');
            Route::get('dispatch/send/{external_id}', 'Tenant\Api\AppController@sendDispatch');
            Route::get('dispatch/{id}', 'Tenant\Api\AppController@dispatches_id');

            //transportistas dispatchers
            Route::get('dispatcher/search', 'Tenant\Api\AppController@searchDispatcher');
            Route::post('dispatcher/create', 'Tenant\Api\AppController@storeDispatcher');
            Route::delete('dispatcher/delete/{item}', 'Tenant\Api\AppController@destroyDispatcher');

            //transportistas drivers
            Route::get('driver/search', 'Tenant\Api\AppController@searchDriver');
            Route::post('driver/create', 'Tenant\Api\AppController@storeDriver');
            Route::delete('driver/delete/{item}', 'Tenant\Api\AppController@destroyDriver');

            //vehículos transports
            Route::get('transport/search', 'Tenant\Api\AppController@searchTransport');
            Route::post('transport/create', 'Tenant\Api\AppController@storeTransport');
            Route::delete('transport/delete/{item}', 'Tenant\Api\AppController@destroyTransport');

            //direcciones de partidas  origin_address
            Route::get('origin_address/search', 'Tenant\Api\AppController@searchOriginAddress');
            Route::post('origin_address/create', 'Tenant\Api\AppController@storeOriginAddress');
            Route::delete('origin_address/delete/{item}', 'Tenant\Api\AppController@destroyOriginAddress');


            //envios de cpe manualmente
            Route::get('documents/send/{document}', 'Tenant\Api\AppController@send');

            //conteo de documentos
            Route::get('document/documents_count', 'Tenant\Api\AppController@documents_count');

            //buscador de documentos
            Route::get('document/search/{id}', 'Tenant\Api\AppController@search_document');
            Route::get('documents/light', 'Tenant\Api\AppController@document_light');

            //buscador de notas de venta
            Route::get('sale-note/search/{id}', 'Tenant\Api\AppController@search_notesale');

            //listar vendedores
            Route::get('sellers/list', 'Tenant\Api\AppController@sellers');

            //detalles de clientes
            Route::get('document/customers/{id}', 'Tenant\Api\AppController@customers_details');

            //ubigeos departamentos, provincias y distritos
            Route::get('ubigeos', 'Tenant\Api\AppController@ubigeos');

            //extraer unidades de medidas
            Route::get('unitTypes', 'Tenant\Api\AppController@unitTypes');

            Route::get('getTablesGr', 'Tenant\Api\AppController@getTablesGr');

            //detalles de productos
            Route::get('items/details/{id}', 'Tenant\Api\AppController@item_details');


            //pagos documentos
            Route::get('document_payments/records/{document_id}', 'Tenant\Api\AppController@recordsPayments');
            Route::get('document_payments/change/{payment_type_id}/{payment_id}', 'Tenant\Api\AppController@changePayment');

            //pagos nota de venta
            Route::get('sale_note_payments/records/{document_id}', 'Tenant\Api\AppController@recordsNoteSalePayments');
            Route::get('sale_note_payments/change/{payment_type_id}/{payment_id}', 'Tenant\Api\AppController@changeNoteSalePayment');


                // caja
            Route::get('cash/open/{value}', 'Tenant\Api\AppController@opencash');
            Route::get('cash/check', 'Tenant\Api\AppController@opening_cash_check');
            Route::get('cash/records/loretosoft', 'Tenant\Api\AppController@records');
            Route::get('cash/records', 'Tenant\Api\AppController@records');
            Route::get('cash/email', 'Tenant\Api\AppController@cashemail');
            Route::get('cash/close/{cash}', 'Tenant\Api\AppController@close');

            //anular / eliminar productos
            Route::delete('item/delete/{item}', 'Tenant\Api\AppController@destroy_item');
            Route::get('item/disable/{item}', 'Tenant\Api\AppController@disable');
            Route::get('item/enable/{item}', 'Tenant\Api\AppController@enable');
            Route::get('items', 'Tenant\Api\AppController@items');
            Route::get('items/search-items', 'Tenant\Api\AppController@ItemsSearch');

            //anular / eliminar clientes
            Route::get('customers', 'Tenant\Api\AppController@customersAdmin');
            Route::get('customer/search', 'Tenant\Api\AppController@CustomersSearch');
            Route::get('customer/enabled/{type}/{person}', 'Tenant\Api\AppController@CustomerEnable');
            Route::delete('customer/delete/{person}', 'Tenant\Api\AppController@destroy_customer');

            Route::get('documents/type_status', 'Tenant\Api\AppController@typeStatus');
            Route::get('documents/filter/{state}/{type_doc}', 'Tenant\Api\AppController@filterCPE');
            Route::post('cash/report/email', 'Tenant\Api\AppController@email');

            //validador de cpe nuevo loretosoft
            Route::post('services/validate_cpe_loretosoft', 'Tenant\Api\AppController@validateCpe_2');

            //rporte por mes y año
            Route::get('report/{year}/{month}/{day}/{method}/{type_user}/{user_id}', 'Tenant\Api\AppController@report');

            //detalles de productos para woocomerce
            Route::get('items/woocomerce', 'Tenant\Api\WoocomerceController@items');
            Route::get('items/listproduct', 'Tenant\Api\WoocomerceController@listproduct');

            //tipo de documentos
            Route::get('document/tipo_doc', 'Tenant\Api\AppController@getTypeDoc');
            Route::get('document/metodos_filtro', 'Tenant\Api\AppController@metodos_filtro');

            //MOBILE
            Route::get('document/series', 'Tenant\Api\AppController@getSeries');
            Route::get('document/paymentmethod', 'Tenant\Api\AppController@getPaymentmethod');
            Route::get('document/tables', 'Tenant\Api\AppController@tables');
            Route::get('document/customers', 'Tenant\Api\AppController@customers');
            Route::post('document/emailLS', 'Tenant\Api\AppController@document_email');
            Route::post('sale-note', 'Tenant\Api\SaleNoteController@store');
            Route::post('sale-notes', 'Tenant\Api\SaleNoteController@sale_note');

            Route::get('sale-note/series', 'Tenant\Api\SaleNoteController@series');
            Route::get('sale-note/lists', 'Tenant\Api\SaleNoteController@lists');
            Route::post('item', 'Tenant\Api\AppController@item');
            Route::post('items/{id}/update', 'Tenant\Api\AppController@updateItem');
            Route::post('item/upload', 'Tenant\Api\AppController@upload');
            Route::post('person', 'Tenant\Api\AppController@person');
            Route::get('document/search-items', 'Tenant\Api\AppController@searchItems');
            Route::get('document/search-customers', 'Tenant\Api\AppController@searchCustomers');
            Route::post('sale-note/emailLS', 'Tenant\Api\AppController@saleNote_email');
            Route::post('sale-note/{id}/generate-cpe', 'Tenant\Api\SaleNoteController@generateCPE');
            Route::get('sale-notes/anulate/{id}', 'Tenant\Api\AppController@anulateNote');

            Route::get('report', 'Tenant\Api\AppController@report');

            Route::post('documents', 'Tenant\Api\DocumentController@store');
            Route::get('documents/tables', 'Tenant\DocumentController@tables');
            Route::get('documents/lists', 'Tenant\Api\DocumentController@lists');
            Route::get('documents/lists/{startDate}/{endDate}', 'Tenant\Api\DocumentController@lists');
            Route::post('documents/updatedocumentstatus', 'Tenant\Api\DocumentController@updatestatus');
            Route::post('summaries', 'Tenant\Api\SummaryController@store');
            Route::post('voided', 'Tenant\Api\VoidedController@store');
            Route::post('retentions', 'Tenant\Api\RetentionController@store');
            Route::post('dispatches', 'Tenant\Api\DispatchController@store');
            Route::post('documents/send', 'Tenant\Api\DocumentController@send');
            Route::post('summaries/status', 'Tenant\Api\SummaryController@status');
            Route::post('voided/status', 'Tenant\Api\VoidedController@status');
            Route::get('services/ruc/{number}', 'Tenant\Api\ServiceController@ruc');
            Route::get('services/dni/{number}', 'Tenant\Api\ServiceController@dni');
            Route::post('services/consult_cdr_status', 'Tenant\Api\ServiceController@consultCdrStatus');
            Route::post('services/validate_cpe', 'Tenant\Api\ServiceController@validateCpe');
            Route::post('perceptions', 'Tenant\Api\PerceptionController@store');

            Route::post('dispatches/send', 'Tenant\Api\DispatchController@send');
            Route::get('dispatches/status_ticketLS/{external_id}', 'Tenant\Api\DispatchController@statusTicketLS');
            Route::post('dispatches/status_ticket', 'Tenant\Api\DispatchController@statusTicket');

            Route::post('documents_server', 'Tenant\Api\DocumentController@storeServer');
            Route::get('document_check_server/{external_id}', 'Tenant\Api\DocumentController@documentCheckServer');

            //liquidacion de compra
            Route::post('purchase-settlements', 'Tenant\Api\PurchaseSettlementController@store');

            //Pedidos
            Route::get('orders', 'Tenant\Api\OrderController@records');
            Route::post('orders', 'Tenant\Api\OrderController@store');

            //Company
            Route::get('company', 'Tenant\Api\CompanyController@record');

            // Cotizaciones
            Route::get('quotations/list', 'Tenant\Api\QuotationController@list');
            Route::post('quotations', 'Tenant\Api\QuotationController@store');
            Route::post('quotations/email', 'Tenant\Api\QuotationController@email');

            //Caja
            Route::post('cash/restaurant', 'Tenant\Api\CashController@storeRestaurant');

        });

        Route::get('documents/search/customers', 'Tenant\DocumentController@searchCustomers');

        // Route::post('services/consult_status', 'Tenant\Api\ServiceController@consultStatus');
        Route::post('documents/status', 'Tenant\Api\ServiceController@documentStatus');

        Route::get('sendserver/{document_id}/{query?}', 'Tenant\DocumentController@sendServer');
        Route::post('configurations/generateDispatch', 'Tenant\ConfigurationController@generateDispatch');
    });
} else {
    Route::domain(env('APP_URL_BASE'))->group(function () {


        Route::middleware(['auth:system_api'])->group(function () {

            //reseller
            Route::post('reseller/detail', 'System\Api\ResellerController@resellerDetail');
            // Route::post('reseller/lockedAdmin', 'System\Api\ResellerController@lockedAdmin');
            // Route::post('reseller/lockedTenant', 'System\Api\ResellerController@lockedTenant');

            Route::get('restaurant/partner/list', 'System\Api\RestaurantPartnerController@list');
            Route::post('restaurant/partner/store', 'System\Api\RestaurantPartnerController@store');
            Route::post('restaurant/partner/search', 'System\Api\RestaurantPartnerController@search');

        });
    });

}

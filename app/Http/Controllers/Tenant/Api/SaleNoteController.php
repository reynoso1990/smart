<?php

namespace App\Http\Controllers\Tenant\Api;

use Exception;
use Mpdf\Mpdf;
use Carbon\Carbon;
use Mpdf\HTMLParserMode;
use App\Models\Tenant\Cash;
use App\Models\Tenant\Item;
use Illuminate\Support\Str;
use App\Traits\OfflineTrait;
use Illuminate\Http\Request;
use App\Models\Tenant\Person;
use App\Models\Tenant\Series;
use App\Models\Tenant\Company;
use Mpdf\Config\FontVariables;
use App\CoreFacturalo\Template;
use App\Models\Tenant\SaleNote;
// use App\Models\Tenant\Warehouse;
use App\CoreFacturalo\Facturalo;
use App\Models\Tenant\StateType;
use Modules\Item\Models\ItemLot;
use Mpdf\Config\ConfigVariables;
use App\Mail\Tenant\SaleNoteEmail;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\CashDocument;
use App\Models\Tenant\SaleNoteItem;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\Establishment;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use Modules\Document\Traits\SearchTrait;
use Modules\Finance\Traits\FinanceTrait;
use App\Http\Requests\Tenant\CashRequest;
use App\Http\Resources\Tenant\CashResource;
use App\Models\Tenant\Person as PersonModel;
use Modules\Finance\Traits\FilePaymentTrait;
use Modules\Inventory\Traits\InventoryTrait;
use App\Http\Requests\Tenant\SaleNoteRequest;
use App\Http\Resources\Tenant\CashCollection;
use App\CoreFacturalo\Requests\Inputs\Functions;
use App\Http\Controllers\Tenant\EmailController;
use App\Http\Resources\Tenant\SaleNoteCollection;
use App\CoreFacturalo\Helpers\Number\NumberLetter;
use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\CoreFacturalo\Requests\Inputs\Common\PersonInput;
use App\Models\Tenant\Establishment as EstablishmentModel;
use App\CoreFacturalo\Requests\Inputs\Common\EstablishmentInput;
use App\CoreFacturalo\Requests\Api\Transform\Common\PersonTransform;

class SaleNoteController extends Controller
{
    use StorageDocument;
    use StorageDocument;
    use FinanceTrait;
    use InventoryTrait;
    use SearchTrait;
    use StorageDocument;
    use OfflineTrait;
    use FilePaymentTrait;
    protected $sale_note;

    protected $company;
    protected $apply_change;
    public function lists(Request $request)
    {

        $record = SaleNote::where(function ($q) use ($request) {
            $q->where('series', 'like', "%{$request->input}%")
                ->orWhere('number', 'like', "%{$request->input}%");
        })
            ->latest()
            ->take(config('tenant.items_per_page'))
            ->get();

        $records = new SaleNoteCollection($record);

        return $records;
    }
    public function formatear($inputs){

        $totals = $inputs['totales'];
        $company =Company::first();

        $customer = Person::where('number',$inputs['datos_del_cliente_o_receptor']['numero_documento'])->first();
        $data_numbers = SaleNote::select(DB::raw('Max(number) as number'))->first();


        $inputs_transform = [
            'user_id' => auth()->user()->id,
            'external_id' => Str::uuid()->toString(),
            'soap_type_id' => $company->soap_type_id,
            'establishment_id' => auth()->user()->establishment_id,
            'establishment' =>  json_decode(Establishment::where('id',auth()->user()->establishment_id)->first(),true),
             'customer_id' => $customer->id,
            'state_type_id' => "01",
            'prefix' => "NV",
            'series' => Functions::valueKeyInArray($inputs, 'serie_documento'),
            'number' => $data_numbers->number != null ? $data_numbers->number+1 : 1,
             'date_of_issue' => Functions::valueKeyInArray($inputs, 'fecha_de_emision'),
            'time_of_issue' => Functions::valueKeyInArray($inputs, 'hora_de_emision'),
            'document_type_id' => Functions::valueKeyInArray($inputs, 'codigo_tipo_documento'),
            'currency_type_id' => Functions::valueKeyInArray($inputs, 'codigo_tipo_moneda'),
            'exchange_rate_sale' => Functions::valueKeyInArray($inputs, 'factor_tipo_de_cambio', 1),
            'purchase_order' => Functions::valueKeyInArray($inputs, 'numero_orden_de_compra'),
            'folio' => Functions::valueKeyInArray($inputs, 'folio'),
            'customer' => PersonTransform::transform_customer($customer),
            'total_prepayment' => Functions::valueKeyInArray($totals, 'total_anticipos'),
            'total_discount' => Functions::valueKeyInArray($totals, 'total_descuentos'),
            'total_charge' => Functions::valueKeyInArray($totals, 'total_cargos'),
            'total_exportation' => Functions::valueKeyInArray($totals, 'total_exportacion'),
            'total_free' => Functions::valueKeyInArray($totals, 'total_operaciones_gratuitas'),
            'total_prepayment' => 0.00,
            'total_discount'   => 0.00,
            'total_charge'     => 0.00,

            'total_taxed' => Functions::valueKeyInArray($totals, 'total_operaciones_gravadas'),
            'total_unaffected' => Functions::valueKeyInArray($totals, 'total_operaciones_inafectas'),
            'total_exonerated' => Functions::valueKeyInArray($totals, 'total_operaciones_exoneradas'),
            'total_igv' => Functions::valueKeyInArray($totals, 'total_igv'),
            'total_igv_free' => Functions::valueKeyInArray($totals, 'total_igv_operaciones_gratuitas'),
            'total_base_isc' => Functions::valueKeyInArray($totals, 'total_base_isc'),
            'total_isc' => Functions::valueKeyInArray($totals, 'total_isc'),
            'total_base_other_taxes' => Functions::valueKeyInArray($totals, 'total_base_otros_impuestos'),
            'total_other_taxes' => Functions::valueKeyInArray($totals, 'total_otros_impuestos'),
            'total_plastic_bag_taxes' => Functions::valueKeyInArray($totals, 'total_impuestos_bolsa_plastica'),
            'total_taxes' => Functions::valueKeyInArray($totals, 'total_impuestos'),
            'total_value' => Functions::valueKeyInArray($totals, 'total_valor'),
            'subtotal' => (Functions::valueKeyInArray($totals, 'subtotal_venta')) ? $totals['subtotal_venta'] : $totals['total_venta'],
            'total' => Functions::valueKeyInArray($totals, 'total_venta'),
            'total_pending_payment' => Functions::valueKeyInArray($totals, 'total_pendiente_pago'),
            // 'pending_amount_detraction' => Functions::valueKeyInArray($totals, 'total_pendiente_detraccion'),
            'has_prepayment' => Functions::valueKeyInArray($inputs, 'pago_anticipado',0),
            'items' => self::items($inputs),
            'additional_information' => Functions::valueKeyInArray($inputs, 'informacion_adicional'),
            'additional_data' => Functions::valueKeyInArray($inputs, 'dato_adicional'),
            'terms_condition' => Functions::valueKeyInArray($inputs, 'terminos_condiciones'),
            //'actions' => ActionTransform::transform($inputs),
            'hotel' => Functions::valueKeyInArray($inputs, 'hotel',[]),
            'transport' => Functions::valueKeyInArray($inputs, 'transport',[]),
            'payments' => self::payments($inputs),
            'payment_condition_id' => Functions::valueKeyInArray($inputs, 'codigo_condicion_de_pago', '01'),
            'sale_note_id' => Functions::valueKeyInArray($inputs, 'codigo_nota_venta'),
          //  'payments' => Functions::valueKeyInArray($inputs, 'payments'),
            'total_detraction' => Functions::valueKeyInArray($inputs, 'total_detraccion', 0),
        ];
    

         return $inputs_transform;


    }

    private static function payments($inputs)
    {
        if(in_array($inputs['codigo_tipo_documento'], ['01', '03'])) {
            $payments = [];
            if(key_exists('pagos', $inputs)) {
                foreach ($inputs['pagos'] as $row) {
                    $payments[] = [
                        'date_of_payment' => Functions::valueKeyInArray($inputs, 'fecha_de_emision'),
                        'payment_method_type_id' => $row['codigo_metodo_pago'],
                        'payment_destination_id' => $row['codigo_destino_pago'],
                        'reference' => Functions::valueKeyInArray($row, 'referencia'),
                        'change' => Functions::valueKeyInArray($row, 'cambio'),
                        'payment' => Functions::valueKeyInArray($row, 'monto', 0),
                        'payment_received' => Functions::valueKeyInArray($row, 'pago_recibido'),
                    ];
                }

            }
    
     return $payments;

        }

        return [];
    }
    private static function items($inputs)
    {
        if(key_exists('items', $inputs)) {
            $items = [];
       
            foreach ($inputs['items'] as $row) {
                //$item = Item::where('id',$row['codigo_interno'])->first();
                $item = Item::where('description',$row['descripcion'])
                ->get()
                ->transform(function ($row) {
                    /** @var Person $row */
                    return $row->getCollectionData();
                });
                 $items[] = [
                    'item_id' => isset($row['codigo_interno']) ? $item[0]['id'] : '',
                    'item' =>  $item[0],//self::item($row['codigo_interno']),
                    'internal_id' => isset($row['codigo_interno']) ? $row['codigo_interno']:'',
                    'description' => $row['descripcion'],
                    'name' => Functions::valueKeyInArray($row, 'nombre'),
                    'second_name' => Functions::valueKeyInArray($row, 'nombre_secundario'),
                    'item_type_id' => Functions::valueKeyInArray($row, 'codigo_tipo_item', '01'),
                    'item_code' => Functions::valueKeyInArray($row, 'codigo_producto_sunat'),
                    'item_code_gs1' => Functions::valueKeyInArray($row, 'codigo_producto_gsl'),
                    'unit_type_id' => strtoupper($row['unidad_de_medida']),
                    'currency_type_id' => $inputs['codigo_tipo_moneda'],

                    'quantity' => Functions::valueKeyInArray($row, 'cantidad'),
                    'unit_value' => Functions::valueKeyInArray($row, 'valor_unitario'),
                    'price_type_id' => Functions::valueKeyInArray($row, 'codigo_tipo_precio'),
                    'unit_price' => Functions::valueKeyInArray($row, 'precio_unitario'),

                    'affectation_igv_type_id' => Functions::valueKeyInArray($row, 'codigo_tipo_afectacion_igv'),
                    'total_base_igv' => Functions::valueKeyInArray($row, 'total_base_igv'),
                    'percentage_igv' => Functions::valueKeyInArray($row, 'porcentaje_igv'),
                    'total_igv' => Functions::valueKeyInArray($row, 'total_igv'),

                    'system_isc_type_id' => Functions::valueKeyInArray($row, 'codigo_tipo_sistema_isc'),
                    'total_base_isc' => Functions::valueKeyInArray($row, 'total_base_isc'),
                    'percentage_isc' => Functions::valueKeyInArray($row, 'porcentaje_isc'),
                    'total_isc' => Functions::valueKeyInArray($row, 'total_isc'),

                    'total_base_other_taxes' => Functions::valueKeyInArray($row, 'total_base_otros_impuestos'),
                    'percentage_other_taxes' => Functions::valueKeyInArray($row, 'porcentaje_otros_impuestos'),
                    'total_other_taxes' => Functions::valueKeyInArray($row, 'total_otros_impuestos'),
                    'total_plastic_bag_taxes' => Functions::valueKeyInArray($row, 'total_impuestos_bolsa_plastica'),

                    'total_taxes' => Functions::valueKeyInArray($row, 'total_impuestos'),
                    'total_value' => Functions::valueKeyInArray($row, 'total_valor_item'),
                    'total_charge' => Functions::valueKeyInArray($row, 'total_cargos'),
                    'total_discount' => Functions::valueKeyInArray($row, 'total_descuentos'),
                    'total' => Functions::valueKeyInArray($row, 'total_item'),

                    // 'attributes' => self::attributes($row),
                    // 'discounts' => self::discounts($row),
                    // 'charges' => self::charges($row),
                    'additional_information' => Functions::valueKeyInArray($row, 'informacion_adicional'),
                    'lots' => Functions::valueKeyInArray($row, 'lots', []),
                    'update_description' => Functions::valueKeyInArray($row, 'actualizar_descripcion', true), //variable para determinar si se actualiza la descripcion del item cuando se envia desde api
                    'name_product_pdf' => Functions::valueKeyInArray($row, 'nombre_producto_pdf'),
                    'name_product_xml' => Functions::valueKeyInArray($row, 'nombre_producto_xml'),
                    'additional_data' => Functions::valueKeyInArray($row, 'dato_adicional'),
                    'quantity_factor' => Functions::valueKeyInArray($row, 'cantidad_factor', 1),
                    'presentation_description' => Functions::valueKeyInArray($row, 'presentation_description', null),
                    'presentation_unit_type_id' => Functions::valueKeyInArray($row, 'presentation_unit_type_id', null),
                ];
            }
            return $items;
        }
        return null;
    }
    public function sale_note(Request $request){

       
        DB::connection('tenant')->beginTransaction();
        try {
            if (!isset($inputs['id'])) {
                $inputs['id'] = false;
            }
            $data = $this->formatear($request->all());
          
            $this->sale_note =  SaleNote::query()->updateOrCreate(['id' => $inputs['id']], $data);
            $this->deleteAllPayments($this->sale_note->payments);
            $this->deleteAllItems($this->sale_note->items);
            $configuration = Configuration::first();

            foreach ($data['items'] as $row) {
                // $item_id = isset($row['id']) ? $row['id'] : null;
                $item_id = isset($row['record_id']) ? $row['record_id'] : null;
                $sale_note_item = SaleNoteItem::query()->firstOrNew(['id' => $item_id]);
                $sale_note_item->fill($row);
                $sale_note_item->sale_note_id = $this->sale_note->id;
                $sale_note_item->save();
            }
            //pagos
            $this->savePayments($this->sale_note, $data['payments']);
            $this->setFilename();
            $this->createPdf($this->sale_note, "a4", $this->sale_note->filename);
            $this->regularizePayments($data['payments']);
            DB::connection('tenant')->commit();
            return [
                'success' => true,
                'data' => [
                    'id' => $this->sale_note->id,
                    'number_full' => $this->sale_note->number_full,
                ],
            ];
        } catch (Exception $e) {
            $this->generalWriteErrorLog($e);
            DB::connection('tenant')->rollBack();
            return [
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
    public function savePayments($sale_note, $payments)
    {

        $total = $sale_note->total;
        $balance = $total - collect($payments)->sum('payment');

        $search_cash = ($balance < 0) ? collect($payments)->firstWhere('payment_method_type_id', '01') : null;

        $this->apply_change = false;

        if ($balance < 0 && $search_cash) {

            $payments = collect($payments)->map(function ($row) use ($balance) {

                $change = null;
                $payment = $row['payment'];

                if ($row['payment_method_type_id'] == '01' && !$this->apply_change) {

                    $change = abs($balance);
                    $payment = $row['payment'] - abs($balance);
                    $this->apply_change = true;
                }

                return [
                    "id" => null,
                    "document_id" => null,
                    "sale_note_id" => null,
                    "date_of_payment" => $row['date_of_payment'],
                    "payment_method_type_id" => $row['payment_method_type_id'],
                    "reference" => $row['reference'],
                    "payment_destination_id" => isset($row['payment_destination_id']) ? $row['payment_destination_id'] : null,
                    "payment_filename" => isset($row['payment_filename']) ? $row['payment_filename'] : null,
                    "change" => $change,
                    "payment" => $payment
                ];
            });
        }

        // dd($payments, $balance, $this->apply_change);

        foreach ($payments as $row) {

            if ($balance < 0 && !$this->apply_change) {
                $row['change'] = abs($balance);
                $row['payment'] = $row['payment'] - abs($balance);
                $this->apply_change = true;
            }

            $record_payment = $sale_note->payments()->create($row);

            if (isset($row['payment_destination_id'])) {
                $this->createGlobalPayment($record_payment, $row);
            }

            if (isset($row['payment_filename'])) {
                $record_payment->payment_file()->create([
                    'filename' => $row['payment_filename']
                ]);
            }

            // para carga de voucher
            $this->saveFilesFromPayments($row, $record_payment, 'sale_notes');
        }
    }
    private function regularizePayments($payments)
    {
        $total_payments = collect($payments)->sum('payment');
        $balance = $this->sale_note->total - $total_payments;
        if ($balance <= 0) {
            $this->sale_note->total_canceled = true;
            $this->sale_note->save();
        } else {
            $this->sale_note->total_canceled = false;
            $this->sale_note->save();
        }
    }

    public function store(Request $request)
    {
        $request['establishment_id'] = $request['establishment_id'] ? $request['establishment_id'] : auth()->user()->establishment_id;
        $force_create_if_not_exist = isset($request['force_create_if_not_exist']) ? (bool)$request['force_create_if_not_exist'] : false;
        $request['force_create_if_not_exist'] = $force_create_if_not_exist;

        $data = [];
        if ($request['force_create_if_not_exist']) {
            // Se saca de tenant, para que pueda guardar el item correctamente.
            self::ExtraLog(__FILE__ . "::" . __LINE__ . "   " . __FUNCTION__ . "  \n Entra por crear " . __FUNCTION__ . " \n" . var_export($request->all(), true) . "\n\n\n\n");
            $data = $this->mergeData($request);
        }

         DB::connection('tenant')->transaction(function () use ($request, $data) {
            if (!$request['force_create_if_not_exist']) {
                $data = $this->mergeData($request);
            }
            $this->sale_note = SaleNote::updateOrCreate(
                ['id' => $request->input('id')],
                $data
            );

            $this->sale_note->payments()->delete();

            foreach ($data['items'] as $row) {
                $item_id = isset($row['id']) ? $row['id'] : null;
                $sale_note_item = SaleNoteItem::firstOrNew(['id' => $item_id]);

                if (isset($row['item']['lots'])) {
                    $row['item']['lots'] = isset($row['lots']) ? $row['lots'] : $row['item']['lots'];
                }

                $sale_note_item->fill($row);
                $sale_note_item->sale_note_id = $this->sale_note->id;
                $sale_note_item->save();

                if (isset($row['lots'])) {
                    foreach ($row['lots'] as $lot) {
                        $record_lot = ItemLot::findOrFail($lot['id']);
                        $record_lot->has_sale = true;
                        $record_lot->update();
                    }
                }
            }
            //pagos
            /*foreach ($data['payments'] as $row) {
                $this->sale_note->payments()->create($row);
            }*/
            if(key_exists('payments', $data)) {
                $payments = new \App\Http\Controllers\Tenant\SaleNoteController;
                $payments->savePayments($this->sale_note, $data['payments']);
            }

            $this->setFilename();
            $this->createPdf($this->sale_note, 'a4', $this->sale_note->filename);

        $cash = Cash::where([['user_id', auth()->user()->id],['state', true],])->first();
        // dd($cash);
        if ($cash!=null) {
                $cash->cash_documents()->updateOrCreate(['id' => $cash->id, 'sale_note_id' => $this->sale_note->id]);
        }

        });

        return [
            'success' => true,
            'data' => [
                'id' => $this->sale_note->id,
                'number' => $this->sale_note->number_full,
                'external_id' => $this->sale_note->external_id,
                'filename' => $this->sale_note->filename,
                'print_ticket' => $this->sale_note->getUrlPrintPdf('ticket'),
            ],
        ];
    }

    public function mergeData($inputs)
    {
        $this->company = Company::active();
        // self::ExtraLog(__FILE__."::".__LINE__."  \n Campos ".__FUNCTION__." \n". json_encode($inputs) ."\n\n\n\n");

        // agregado por loretosoft para terminos y condiciones
        $configuration = Configuration::first();
        if ($configuration->terms_condition_sale!=null || $configuration->terms_condition_sale != '') {
            $inputs['terms_condition'] = $configuration->terms_condition_sale;
        }
        //termina aqui

        $type_period = $inputs['type_period'];
        $quantity_period = $inputs['quantity_period'];
        $force_create_if_not_exist = isset($inputs['force_create_if_not_exist']) ? (bool)$inputs['force_create_if_not_exist'] : false;
        $d_of_issue = new Carbon($inputs['date_of_issue']);
        $automatic_date_of_issue = null;

        if ($type_period && $quantity_period > 0) {
            $add_period_date = ($type_period == 'month') ? $d_of_issue->addMonths($quantity_period) : $d_of_issue->addYears($quantity_period);
            $automatic_date_of_issue = $add_period_date->format('Y-m-d');
        }
        if ($force_create_if_not_exist === true) {
            // busca la persona por id
            $person = PersonModel::find($inputs['customer_id']);
            $client_data = $inputs['datos_del_cliente_o_receptor'];
            $client_number = isset($client_data['numero_documento']) ? $client_data['numero_documento'] : null;
            // compara el numero con el id del cliente, Si es diferente, deberia crear el cliente
            if ($person !== null && $client_number !== $person->number) {
                $person = null;
            }
            if ($person === null) {
                $person = PersonModel::where('number', $client_number)->first();
            }
            if ($person === null && !empty($client_number)) {
                $data_person = [
                    'number' => $client_number,
                    'identity_document_type_id' => $client_data['codigo_tipo_documento_identidad'] ?? '6',
                    'name' => $client_data['apellidos_y_nombres_o_razon_social'] ?? '',
                    'country_id' => $client_data['codigo_pais'] ?? 'PE',
                    'district_id' => $client_data['ubigeo'] ?? '',
                    'address' => $client_data['direccion'] ?? '',
                    'email' => $client_data['correo_electronico'] ?? '',
                    'telephone' => $client_data['telefono'] ?? '',
                ];
                $person = new PersonModel($data_person);
                $person->push();
            }
            $inputs['customer_id'] = $person->id;
            $items = $inputs['items'];
            self::ExtraLog(__FILE__ . "::" . __LINE__ . "   " . __FUNCTION__ . "  \n Buscando Items " . var_export($items, true) . "\n\n\n\n");

            foreach ($items as $key => $item) {
                if(key_exists('full_item', $item)) {
                    $item_in = $item['full_item'];
                    self::ExtraLog('Item Antes \n\n\n\n\n' . var_export($item_in, true) . "\n<<<<<<<<<<<<<<<<<<<<<<<<");
                    unset(
                        $item_in['item_id'],
                        $item_in['internal_id'],
                        $item_in['id'],
                        $item_in['barcode'],
                        $item_in['tags'],
                        $item_in['unit_type'],
                        $item_in['item_type'],
                        $item_in['currency_type'],
                        $item_in['warehouses'],
                        $item_in['item_unit_types']
                    );
                    foreach ($item_in as $k => $v) {
                        if (empty($v)) {
                            unset($item_in[$k]);
                        }
                    }
                    self::ExtraLog('Item Despues \n\n\n\n\n' . var_export($item_in, true) . "\n<<<<<<<<<<<<<<<<<<<<<<<<");
                    $identicalItem = Item::where($item_in)->first();
                    if ($identicalItem === null) {
                        $identicalItem = new Item($item_in);
                        $identicalItem->stock = 1;
                        $identicalItem->stock_min = 1;
                        $identicalItem->push();

                    }
                } else {
                    $item_in = $item;
                    $item_in['sale_unit_price'] = $item_in['unit_price'];
                    $item_in['sale_affectation_igv_type_id'] = $item_in['affectation_igv_type_id'];
                    $item_in['purchase_affectation_igv_type_id'] = $item_in['affectation_igv_type_id'];
                   // $item_in['is_set'] = isset($item_in['is_set']) ? (bool)$item_in['is_set'] : false;
                    $identicalItem = Item::query()
                        ->where('internal_id', $item_in['internal_id'])->first();
                    if ($identicalItem === null) {
                        $identicalItem = new Item($item_in);
                        $identicalItem->stock = 1;
                        $identicalItem->stock_min = 1;
                        $identicalItem->push();
                    }
                }

                $items[$key]['id'] = $identicalItem->id;
                $items[$key]['attributes'] = $identicalItem->attributes;
                $items[$key]['item_id'] = $identicalItem->id;
                $items[$key]['barcode'] = $identicalItem->barcode;
                $items[$key]['item']['barcode'] = $identicalItem->barcode;
                $items[$key]['item']['id'] = $identicalItem->id;
                $items[$key]['item']['item_id'] = $identicalItem->id;
                $items[$key]['item']['is_set'] = $identicalItem->is_set;
                $items[$key]['item']['unit_type_id'] = $identicalItem->unit_type_id;
                $items[$key]['item']['description'] = $identicalItem->description;
            }

            $inputs['items'] = $items;

            if (!isset($inputs['establishment_id']) || empty($inputs['establishment_id'])) {
                $inputs['establishment_id'] = $inputs['establishment_id'] ?: auth()->user()->establishment_id;
            }
        }

        $data_series = $this->getDataSeries($inputs['series_id'], $inputs['id'], $inputs['number']);
        $customer = PersonInput::set($inputs['customer_id']);

        $values = [
            'automatic_date_of_issue' => $automatic_date_of_issue,
            'user_id' => auth()->id(),
            'external_id' => Str::uuid()->toString(),
            'customer' => $customer,
            'establishment' => EstablishmentInput::set($inputs['establishment_id']),
            'soap_type_id' => $this->company->soap_type_id,
            'state_type_id' => '01',
            'series' => $data_series['series'],
            'number' => $data_series['number']
        ];

        $inputs->merge($values);

        return $inputs->all();
    }

    private function getDataSeries($series_id, $id, $number)
    {
        $series = Series::find($series_id)->number;

        if (!$id) {
            $sale_note = SaleNote::select('number')->where('soap_type_id', $this->company->soap_type_id)
                ->where('series', $series)
                ->orderBy('number', 'desc')
                ->first();

            $number = ($sale_note) ? $sale_note->number + 1 : 1;
        }

        return [
            'series' => $series,
            'number' => $number,
        ];
    }

    private function setFilename()
    {
        $name = [$this->sale_note->series, $this->sale_note->number, date('Ymd')];
        // $name = [$this->sale_note->prefix, $this->sale_note->id, date('Ymd')];
        $this->sale_note->filename = join('-', $name);
        $this->sale_note->unique_filename = $this->sale_note->filename; //campo único para evitar duplicados
        $this->sale_note->save();
    }

    public function toPrint($external_id, $format)
    {
        $sale_note = SaleNote::where('external_id', $external_id)->first();

        if (!$sale_note) {
            throw new Exception("El código {$external_id} es inválido, no se encontro la nota de venta relacionada");
        }

        $this->reloadPDF($sale_note, $format, $sale_note->filename);
        $temp = tempnam(sys_get_temp_dir(), 'sale_note');

        file_put_contents($temp, $this->getStorage($sale_note->filename, 'sale_note'));

        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$sale_note->filename.'"'
        ];

        return response()->file($temp, $headers);
    }

    private function reloadPDF($sale_note, $format, $filename)
    {
        $this->createPdf($sale_note, $format, $filename);
    }

    public function createPdf($sale_note = null, $format_pdf = null, $filename = null, $output = 'pdf')
    {
        ini_set("pcre.backtrack_limit", "50000000");
        $template = new Template();
        $pdf = new Mpdf();

        $this->company = ($this->company != null) ? $this->company : Company::active();
        $this->document = ($sale_note != null) ? $sale_note : $this->sale_note;

        $base_template = config('tenant.pdf_template');

        $html = $template->pdf($base_template, 'sale_note', $this->company, $this->document, $format_pdf);

        if (($format_pdf === 'ticket') or ($format_pdf === 'ticket_58')) {
            $width = ($format_pdf === 'ticket_58') ? 56 : 78;
            if (config('tenant.enabled_template_ticket_80')) {
                $width = 76;
            }

            $company_logo = ($this->company->logo) ? 40 : 0;
            $company_name = (strlen($this->company->name) / 20) * 10;
            $company_address = (strlen($this->document->establishment->address) / 30) * 10;
            $company_number = $this->document->establishment->telephone != '' ? '10' : '0';
            $customer_name = strlen($this->document->customer->name) > '25' ? '10' : '0';
            $customer_address = (strlen($this->document->customer->address) / 200) * 10;
            $p_order = $this->document->purchase_order != '' ? '10' : '0';

            $total_exportation = $this->document->total_exportation != '' ? '10' : '0';
            $total_free = $this->document->total_free != '' ? '10' : '0';
            $total_unaffected = $this->document->total_unaffected != '' ? '10' : '0';
            $total_exonerated = $this->document->total_exonerated != '' ? '10' : '0';
            $total_taxed = $this->document->total_taxed != '' ? '10' : '0';
            $quantity_rows = count($this->document->items);
            $payments = $this->document->payments()->count() * 2;

            $extra_by_item_description = 0;
            $discount_global = 0;
            foreach ($this->document->items as $it) {
                if (strlen($it->item->description) > 100) {
                    $extra_by_item_description += 24;
                }
                if ($it->discounts) {
                    $discount_global = $discount_global + 1;
                }
            }
            $legends = $this->document->legends != '' ? '10' : '0';

            $pdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => [
                    $width,
                    40 +
                    (($quantity_rows * 8) + $extra_by_item_description) +
                    ($discount_global * 3) +
                    $company_logo +
                    $payments +
                    $company_name +
                    $company_address +
                    $company_number +
                    $customer_name +
                    $customer_address +
                    $p_order +
                    $legends +
                    $total_exportation +
                    $total_free +
                    $total_unaffected +
                    $total_exonerated +
                    $total_taxed],
                'margin_top' => 0,
                'margin_right' => 2,
                'margin_bottom' => 0,
                'margin_left' => 2
            ]);
        } elseif ($format_pdf === 'a5') {
            $company_name = (strlen($this->company->name) / 20) * 10;
            $company_address = (strlen($this->document->establishment->address) / 30) * 10;
            $company_number = $this->document->establishment->telephone != '' ? '10' : '0';
            $customer_name = strlen($this->document->customer->name) > '25' ? '10' : '0';
            $customer_address = (strlen($this->document->customer->address) / 200) * 10;
            $p_order = $this->document->purchase_order != '' ? '10' : '0';

            $total_exportation = $this->document->total_exportation != '' ? '10' : '0';
            $total_free = $this->document->total_free != '' ? '10' : '0';
            $total_unaffected = $this->document->total_unaffected != '' ? '10' : '0';
            $total_exonerated = $this->document->total_exonerated != '' ? '10' : '0';
            $total_taxed = $this->document->total_taxed != '' ? '10' : '0';
            $quantity_rows = count($this->document->items);
            $discount_global = 0;
            foreach ($this->document->items as $it) {
                if ($it->discounts) {
                    $discount_global = $discount_global + 1;
                }
            }
            $legends = $this->document->legends != '' ? '10' : '0';

            $alto = ($quantity_rows * 8) +
                ($discount_global * 3) +
                $company_name +
                $company_address +
                $company_number +
                $customer_name +
                $customer_address +
                $p_order +
                $legends +
                $total_exportation +
                $total_free +
                $total_unaffected +
                $total_exonerated +
                $total_taxed;
            $diferencia = 148 - (float)$alto;

            $pdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => [
                    210,
                    $diferencia + $alto
                ],
                'margin_top' => 2,
                'margin_right' => 5,
                'margin_bottom' => 0,
                'margin_left' => 5
            ]);
        } else {
            $pdf_font_regular = config('tenant.pdf_name_regular');
            $pdf_font_bold = config('tenant.pdf_name_bold');

            if ($pdf_font_regular != false) {
                $defaultConfig = (new ConfigVariables())->getDefaults();
                $fontDirs = $defaultConfig['fontDir'];

                $defaultFontConfig = (new FontVariables())->getDefaults();
                $fontData = $defaultFontConfig['fontdata'];

                $pdf = new Mpdf([
                    'fontDir' => array_merge($fontDirs, [
                        app_path('CoreFacturalo' . DIRECTORY_SEPARATOR . 'Templates' .
                            DIRECTORY_SEPARATOR . 'pdf' .
                            DIRECTORY_SEPARATOR . $base_template .
                            DIRECTORY_SEPARATOR . 'font')
                    ]),
                    'fontdata' => $fontData + [
                            'custom_bold' => [
                                'R' => $pdf_font_bold . '.ttf',
                            ],
                            'custom_regular' => [
                                'R' => $pdf_font_regular . '.ttf',
                            ],
                        ]
                ]);
            }
        }

        $path_css = app_path('CoreFacturalo' . DIRECTORY_SEPARATOR . 'Templates' .
            DIRECTORY_SEPARATOR . 'pdf' .
            DIRECTORY_SEPARATOR . $base_template .
            DIRECTORY_SEPARATOR . 'style.css');

        $stylesheet = file_get_contents($path_css);


        // retornar html del pdf para impresion directa
        if ($output === 'html') {
            $path_html = app_path('CoreFacturalo' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR . 'ticket_html.css');
            $ticket_html = file_get_contents($path_html);
            $pdf->WriteHTML($ticket_html, HTMLParserMode::HEADER_CSS);
            $pdf->WriteHTML($html, HTMLParserMode::HTML_BODY);

            return "<style>" . $ticket_html . $stylesheet . "</style>" . $html;
        }


        $pdf->WriteHTML($stylesheet, HTMLParserMode::HEADER_CSS);
        $pdf->WriteHTML($html, HTMLParserMode::HTML_BODY);

        if (config('tenant.pdf_template_footer')) {
            $html_footer = $template->pdfFooter($base_template, $this->document);
            $pdf->SetHTMLFooter($html_footer);
        }

        $this->uploadFile($this->document->filename, $pdf->output('', 'S'), 'sale_note');
    }

    public function uploadFile($filename, $file_content, $file_type)
    {
        $this->uploadStorage($filename, $file_content, $file_type);
    }

    public function series()
    {
        return Series::where('establishment_id', auth()->user()->establishment_id)
            ->where('document_type_id', '80')
            ->get()
            ->transform(function ($row) {
                return $row->getApiRowResource();
            });
    }

    public function email(Request $request)
    {
        $company = Company::active();
        $record = SaleNote::find($request->input('id'));
        $customer_email = $request->input('email');

        $email = $customer_email;
        $mailable = new SaleNoteEmail($company, $record);
        $id = $request->id;
        $sendIt = EmailController::SendMail($email, $mailable, $id, 2);
        /*
        Configuration::setConfigSmtpMail();
        $array_email = explode(',', $customer_email);
        if (count($array_email) > 1) {
            foreach ($array_email as $email_to) {
                $email_to = trim($email_to);
                if(!empty($email_to)) {
                    Mail::to($email_to)->send(new SaleNoteEmail($company, $record));
                }
            }
        } else {
            Mail::to($customer_email)->send(new SaleNoteEmail($company, $record));
        }
        */

        return [
            'success' => true,
            'message' => 'Email enviado correctamente.'
        ];
    }

    public function generateCPE(Request $request, $saleNoteId)
    {
        /**
         * codigo_tipo_documento => 01 = Factura || 03 = Factura
         * serie_documento
         * numero_documento
         * fecha_de_emision
         * hora_de_emision
         * fecha_de_vencimiento
         * codigo_condicion_de_pago
         **/
        $user = auth()->guard('api')->user();
        $saleNote = SaleNote::where('id', $saleNoteId)->first();
        if (!$saleNote) {
            return response()->json([
                'success' => false,
                'message' => 'La nota de venta asociada no existe.'
            ], 500);
        }
        $saleNote->items = $saleNote->items
            ->each(function ($item) {
                $itemBD = Item::without(['item_type', 'unit_type', 'currency_type', 'warehouses', 'item_unit_types', 'tags'])
                    ->findOrFail($item->item_id);

                $itemToArray = json_decode(json_encode($item->item), true);
                $itemToArray['internal_id'] = $itemBD->internal_id ?? '';
                $itemToArray['item_code'] = $itemBD->item_code ?? '';
                $itemToArray['item_code_gs1'] = $itemBD->item_code_gs1 ?? '';
                $itemToArray['IdLoteSelected'] = null;

                $item->item = $itemToArray;
                return (object)$item;
            });
        $saleNote = $saleNote->toArray();

        $data = [
            'type' => 'invoice',
            'group_id' => '01',
            'user_id' => $user->id,
            'external_id' => $saleNote['external_id'],
            'establishment_id' => $saleNote['establishment_id'],
            'establishment' => $saleNote['establishment'],
            "soap_type_id" => $saleNote['soap_type_id'],
            "state_type_id" => $saleNote['state_type_id'],
            "ubl_version" => "2.1",
            "filename" => "",
            "document_type_id" => $request->codigo_tipo_documento,
            "series" => $request->serie_documento,
            "number" => $request->numero_documento,
            "date_of_issue" => $request->fecha_de_emision,
            "time_of_issue" => $request->hora_de_emision,
            "customer_id" => $saleNote['customer_id'],
            "seller_id" => null,
            "customer" => $saleNote['customer'],
            "currency_type_id" => $saleNote['currency_type_id'],
            "purchase_order" => $saleNote['purchase_order'],
            "quotation_id" => $saleNote['quotation_id'],
            "order_note_id" => $saleNote['order_note_id'],
            "exchange_rate_sale" => $saleNote['exchange_rate_sale'],
            "total_prepayment" => $saleNote['total_prepayment'],
            "total_discount" => $saleNote['total_discount'],
            "total_charge" => $saleNote['total_charge'],
            "total_exportation" => $saleNote['total_exportation'],
            "total_free" => $saleNote['total_free'],
            "total_taxed" => $saleNote['total_taxed'],
            "total_unaffected" => $saleNote['total_unaffected'],
            "total_exonerated" => $saleNote['total_exonerated'],
            "total_igv" => $saleNote['total_igv'],
            "total_base_isc" => $saleNote['total_base_isc'],
            "total_isc" => $saleNote['total_isc'],
            "total_base_other_taxes" => $saleNote['total_base_other_taxes'],
            "total_other_taxes" => $saleNote['total_other_taxes'],
            "total_plastic_bag_taxes" => 0,
            "total_taxes" => $saleNote['total_taxes'],
            "total_value" => $saleNote['total_value'],
            "total" => $saleNote['total'],
            "has_prepayment" => 0,
            "affectation_type_prepayment" => null,
            "was_deducted_prepayment" => 0,
            "pending_amount_prepayment" => 0,
            "items" => $saleNote['items'],
            "charges" => $saleNote['charges'],
            "discounts" => $saleNote['discounts'],
            "prepayments" => $saleNote['prepayments'],
            "guides" => $saleNote['guides'],
            "related" => $saleNote['related'],
            "perception" => $saleNote['perception'],
            "detraction" => $saleNote['detraction'],
            "invoice" => [
                'operation_type_id' => "0101",
                'date_of_due' => $request->fecha_de_vencimiento,
            ],
            "hotel" => [],
            "transport" => [],
            "plate_number" => $saleNote['plate_number'],
            "legends" => [
                ['code' => 1000, 'value' => NumberLetter::convertToLetter($saleNote['total'])]
            ],
            "actions" => [
                "send_email" => false,
                "send_xml_signed" => false,
                "format_pdf" => "a4",
            ],
            "payments" => [],
            "send_server" => 0,
            "payment_method_type_id" => $saleNote['payment_method_type_id'],
            "reference_data" => $saleNote['reference_data'],
            "fee" => [],
            'sale_note_id' => $saleNoteId,
            'payment_condition_id' => $request->codigo_condicion_de_pago,
        ];


        $dataToRequest = new Request($data);

        $fact =  DB::connection('tenant')->transaction(function () use ($dataToRequest) {
            $facturalo = new Facturalo();
            $facturalo->save($dataToRequest->all());
            $facturalo->createXmlUnsigned();
            $facturalo->signXmlUnsigned();
            $facturalo->updateHash();
            $facturalo->updateQr();
            $facturalo->createPdf();
            $facturalo->sendEmail();
            $facturalo->senderXmlSignedBill();

            return $facturalo;
        });

        $document = $fact->getDocument();
        $response = $fact->getResponse();

        return [
            'success' => true,
            'data' => [
                'number' => $document->number_full,
                'filename' => $document->filename,
                'external_id' => $document->external_id,
                'state_type_id' => $document->state_type_id,
                'state_type_description' => $this->getStateTypeDescription($document->state_type_id),
                'number_to_letter' => $document->number_to_letter,
                'hash' => $document->hash,
                'qr' => $document->qr,
            ],
            'links' => [
                'xml' => $document->download_external_xml,
                'pdf' => $document->download_external_pdf,
                'cdr' => ($response['sent']) ? $document->download_external_cdr : '',
            ],
            'response' => ($response['sent']) ? array_except($response, 'sent') : [],
        ];
    }

    private function getStateTypeDescription($id)
    {
        return StateType::find($id)->description;
    }
}

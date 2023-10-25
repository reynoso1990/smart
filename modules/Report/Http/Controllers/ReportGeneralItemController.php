<?php

namespace Modules\Report\Http\Controllers;

use App\Models\Tenant\Catalogs\DocumentType;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Report\Exports\GeneralItemExport;
use Illuminate\Http\Request;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Document;
use App\Models\Tenant\PurchaseItem;
use App\Models\Tenant\DocumentItem;
use App\Models\Tenant\SaleNoteItem;
use App\Models\Tenant\Company;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\Quotation;
use App\Models\Tenant\QuotationItem;
use App\Models\Tenant\SaleNote;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Report\Http\Resources\GeneralItemCollection;
use Modules\Report\Http\Resources\GeneralItemTotalCollection;
use Modules\Report\Traits\ReportTrait;


class ReportGeneralItemController extends Controller
{
    use ReportTrait;

    public function __construct()
    {
    }

    public function filter()
    {

        $customers = $this->getPersons('customers');
        $suppliers = $this->getPersons('suppliers');
        $items = $this->getItems('items');
        $brands = $this->getBrands();
        $web_platforms = $this->getWebPlatforms();
        $document_types = DocumentType::whereIn('id', ['01', '03', '07', '80','COT'])->get();
        $categories = $this->getCategories();
        $users = $this->getUsers();

        return compact('document_types', 'suppliers', 'customers', 'items', 'web_platforms', 'brands', 'categories', 'users');
    }


    public function index(Request $request)
    {
        $apply_conversion_to_pen = $this->applyConversiontoPen($request);

        return view('report::general_items.index', compact('apply_conversion_to_pen'));
    }


    public function records(Request $request)
    {

        $records = $this->getRecordsItems($request->all())->latest('id');
        // $quantity = $records->sum("utility_item");
        return new GeneralItemCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function recordsTotal(Request $request)
    {

        $records = $this->getRecordsItems($request->all())->get();
        $total = new GeneralItemTotalCollection($records);
        $total->toArray($request);
        $totals = [

            "quantity" => $total->sum('quantity'),
            "total" => $total->sum('total'),
            "total_item_purchase" => $total->sum('total_item_purchase'),
            "utility_item" => $total->sum('utility_item'),
        ];
        return compact('totals');
    }

    //GeneralItemTotalCollection
    public function getRecordsItems($request)
    {
        $data_of_period = $this->getDataOfPeriod($request);
        $data_type = $this->getDataType($request);

        $document_type_id = $request['document_type_id'];
        $d_start = $data_of_period['d_start'];
        $d_end = $data_of_period['d_end'];

        $person_id = $request['person_id'];
        $type_person = $request['type_person'];
        $item_id = $request['item_id'];
        $brand_id = $request['brand_id'];
        $category_id = $request['category_id'];

        $user_id = $request['user_id'];
        $user_type = $request['user_type'] != null ? $request['user_type'] : 'VENDEDOR';
        $web_platform_id = $request['web_platform_id'];

        $records = $this->dataItems($d_start, $d_end, $document_type_id, $data_type, $person_id, $type_person, $item_id, $web_platform_id, $brand_id, $category_id, $user_id, $user_type);

        return $records;
    }


    /**
     * @param $date_start
     * @param $date_end
     * @param $document_type_id
     * @param $data_type
     * @param $person_id
     * @param $type_person
     * @param $item_id
     * @param $web_platform_id
     * @param $brand_id
     * @param $category_id
     * @param $user_id
     * @param $user_type
     *
     * @return \App\Models\Tenant\SaleNoteItem|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    private function dataItems($date_start, $date_end, $document_type_id, $data_type, $person_id, $type_person, $item_id, $web_platform_id, $brand_id, $category_id, $user_id, $user_type)
    {
        $configuration = Configuration::first();
        /* columna state_type_id */
        $documents_excluded = [
            '11', // Documentos anulados
            '09' // Documentos rechazados
        ];
        if ($document_type_id && $document_type_id == '80') {
            $relation = 'sale_note';

            $data = SaleNoteItem::whereHas('sale_note', function ($query) use ($date_start, $date_end, $user_id, $documents_excluded) {
                $query
                    ->whereBetween('date_of_issue', [$date_start, $date_end])
                    ->latest()
                    ->whereTypeUser();
                if (!empty($user_id)) {
                    $query->where('user_id', $user_id);
                }

                $query->whereNotIn('state_type_id', $documents_excluded);
            });
            if ($configuration->multi_sellers) {
                $data->whereHas('sellers', function ($query) use ($user_id) {
                    $query->where('seller_id', $user_id);
                });
            }
        } else if ($document_type_id == null && $data_type['model'] == 'all') {
            $document_types = ['01', '03'];
            $documents = DocumentItem::select(
                'id',
                DB::raw('NULL as quotation_id'), // Columna ficticia para document_id
                DB::raw('NULL as sale_note_id'), // Columna ficticia para document_id
                'document_id',
                'item_id',
                'item',
                'quantity',
                'unit_value',
                'total',
                'number',
            )->whereHas('document', function ($query) use ($date_start, $date_end, $document_types, $documents_excluded) {
                $query
                    ->whereBetween('date_of_issue', [$date_start, $date_end])
                    ->whereIn('document_type_id', $document_types)
                    ->latest()
                    ->whereTypeUser();
                $query->whereNotIn('state_type_id', $documents_excluded);
            });
            if ($user_id && $user_type === 'CREADOR') {
                $documents = $documents->whereHas('document' . '.user', function ($query) use ($user_id) {
                    $query->where('user_id', $user_id);
                });
            }
            if ($user_id && $user_type === 'VENDEDOR') {

                if ($configuration->multi_sellers) {
                    $documents = $documents->whereHas('sellers', function ($query) use ($user_id) {

                        $query->where('seller_id', $user_id);
                    });
                } else {
                    $documents = $documents->whereHas('document' . '.seller', function ($query) use ($user_id) {
                        $query->where('seller_id', $user_id);
                    });
                }
            }
            if ($person_id && $type_person) {

                $column = ($type_person == 'customers') ? 'customer_id' : 'supplier_id';

                $data =  $documents->whereHas($documents, function ($query) use ($column, $person_id) {
                    $query->where($column, $person_id);
                });
            }

            if ($item_id) {
                $documents =  $documents->where('item_id', $item_id);
            }

            if ($web_platform_id || $brand_id || $category_id) {
                $documents = $documents->whereHas('relation_item', function ($q) use ($web_platform_id, $brand_id, $category_id) {
                    if ($web_platform_id) {
                        $q->where('web_platform_id', $web_platform_id);
                    }
                    if ($brand_id) {
                        $q->where('brand_id', $brand_id);
                    }
                    if ($category_id) {
                        $q->where('category_id', $category_id);
                    }
                });
            }
            $documents = $documents->with(['relation_item','quotation', 'sale_note','document']);
            $sale_notes = SaleNoteItem::select(
                'id',
                DB::raw('NULL as quotation_id'), // Columna ficticia para document_id
                'sale_note_id',
                DB::raw('NULL as document_id'), // Columna ficticia para document_id
                'item_id',
                'item',
                'quantity',
                'unit_value',
                'total',
                'number'
            )->whereHas('sale_note', function ($query) use ($date_start, $date_end, $user_id, $documents_excluded) {
                $query
                    ->whereBetween('date_of_issue', [$date_start, $date_end])
                    ->latest()
                    ->whereTypeUser();
                if (!empty($user_id)) {
                    $query->where('user_id', $user_id);
                }

                $query->whereNotIn('state_type_id', $documents_excluded);
            });
            if ($configuration->multi_sellers) {
                $sale_notes->whereHas('sellers', function ($query) use ($user_id) {
                    $query->where('seller_id', $user_id);
                });
            }
            if ($person_id && $type_person) {

                $column = ($type_person == 'customers') ? 'customer_id' : 'supplier_id';

                $sale_notes =  $sale_notes->whereHas('sale_note', function ($query) use ($column, $person_id) {
                    $query->where($column, $person_id);
                });
            }

            if ($item_id) {
                $sale_notes =  $sale_notes->where('item_id', $item_id);
            }

            if ($web_platform_id || $brand_id || $category_id) {
                $sale_notes = $sale_notes->whereHas('relation_item', function ($q) use ($web_platform_id, $brand_id, $category_id) {
                    if ($web_platform_id) {
                        $q->where('web_platform_id', $web_platform_id);
                    }
                    if ($brand_id) {
                        $q->where('brand_id', $brand_id);
                    }
                    if ($category_id) {
                        $q->where('category_id', $category_id);
                    }
                });
            }
            $sale_notes = $sale_notes->with(['relation_item','quotation', 'sale_note','document']);
            $quotations = QuotationItem::select(
                'id',
                'quotation_id',
                DB::raw('NULL as sale_note_id'), // Columna ficticia para document_id
                DB::raw('NULL as document_id'), // Columna ficticia para document_id
                'item_id',
                'item',
                'quantity',
                'unit_value',
                'total',
                'number',
            )->whereHas('quotation', function ($query) use ($date_start, $date_end, $user_id, $documents_excluded) {
                $query
                ->where('changed', 0)
                    ->whereBetween('date_of_issue', [$date_start, $date_end])
                    ->latest()
                    ->whereTypeUser();
                if (!empty($user_id)) {
                    $query->where('user_id', $user_id);
                }

                $query->whereNotIn('state_type_id', $documents_excluded);
            });
            if ($configuration->multi_sellers) {
                $quotations->whereHas('sellers', function ($query) use ($user_id) {
                    $query->where('seller_id', $user_id);
                });
            }
            if ($person_id && $type_person) {

                $column = ($type_person == 'customers') ? 'customer_id' : 'supplier_id';

                $quotations =  $quotations->whereHas('quotation', function ($query) use ($column, $person_id) {
                    $query->where($column, $person_id);
                });
            }

            if ($item_id) {
                $quotations =  $quotations->where('item_id', $item_id);
            }

            if ($web_platform_id || $brand_id || $category_id) {
                $quotations = $quotations->whereHas('relation_item', function ($q) use ($web_platform_id, $brand_id, $category_id) {
                    if ($web_platform_id) {
                        $q->where('web_platform_id', $web_platform_id);
                    }
                    if ($brand_id) {
                        $q->where('brand_id', $brand_id);
                    }
                    if ($category_id) {
                        $q->where('category_id', $category_id);
                    }
                });
            }
            $quotations = $quotations->with(['relation_item','quotation', 'sale_note','document']);
            $data = $documents->union($sale_notes)->union($quotations);

            return $data;
        } 
        else if($document_type_id && $document_type_id == 'COT'){
            $relation = 'quotation';

            $data = QuotationItem::whereHas('quotation', function ($query) use ($date_start, $date_end, $user_id, $documents_excluded) {
                $query
                    ->where('changed', 0)
                    ->whereBetween('date_of_issue', [$date_start, $date_end])
                    ->latest()
                    ->whereTypeUser();
                if (!empty($user_id)) {
                    $query->where('user_id', $user_id);
                }

                $query->whereNotIn('state_type_id', $documents_excluded);
            });
            if ($configuration->multi_sellers) {
                $data->whereHas('sellers', function ($query) use ($user_id) {
                    $query->where('seller_id', $user_id);
                });
            }
        }
        
        else {

            $model = $data_type['model'];

            $relation = $data_type['relation'];
            $document_types = $document_type_id ? [$document_type_id] : ['01', '03'];

            $data = $model::whereHas($relation, function ($query) use ($date_start, $date_end, $document_types, $model, $documents_excluded) {
                $query
                    ->whereBetween('date_of_issue', [$date_start, $date_end])
                    ->whereIn('document_type_id', $document_types)
                    ->latest()
                    ->whereTypeUser();
                if ($model == 'App\Models\Tenant\DocumentItem') {
                    $query->whereNotIn('state_type_id', $documents_excluded);
                }
            });
            if ($user_id && $user_type === 'CREADOR') {
                $data = $data->whereHas($relation . '.user', function ($query) use ($user_id) {
                    $query->where('user_id', $user_id);
                });
            }
            if ($user_id && $user_type === 'VENDEDOR') {

                if ($configuration->multi_sellers && $model == DocumentItem::class) {
                    $data = $data->whereHas('sellers', function ($query) use ($user_id) {

                        $query->where('seller_id', $user_id);
                    });
                } else {
                    $data = $data->whereHas($relation . '.seller', function ($query) use ($user_id) {
                        $query->where('seller_id', $user_id);
                    });
                }
            }
        }


        if ($person_id && $type_person) {

            $column = ($type_person == 'customers') ? 'customer_id' : 'supplier_id';

            $data =  $data->whereHas($relation, function ($query) use ($column, $person_id) {
                $query->where($column, $person_id);
            });
        }

        if ($item_id) {
            $data =  $data->where('item_id', $item_id);
        }

        if ($web_platform_id || $brand_id || $category_id) {
            $data = $data->whereHas('relation_item', function ($q) use ($web_platform_id, $brand_id, $category_id) {
                if ($web_platform_id) {
                    $q->where('web_platform_id', $web_platform_id);
                }
                if ($brand_id) {
                    $q->where('brand_id', $brand_id);
                }
                if ($category_id) {
                    $q->where('category_id', $category_id);
                }
            });
        }

        return $data;
    }


    private function getDataType($request)
    {

        if ($request['type'] == 'sale') {
            if ($request['document_type_id'] == '80') {
                $data['model'] = SaleNoteItem::class;
                $data['relation'] = 'sale_note';
            } else if ($request['document_type_id'] == '01' || $request['document_type_id'] == '03') {
                $data['model'] = DocumentItem::class;
                $data['relation'] = 'document';
            } 
            
            else if($request['document_type_id'] == 'COT'){
                $data['model'] = QuotationItem::class;
                $data['relation'] = 'quotation';
            }
            else {
                $data['model'] = "all";
                $data['relation'] = 'all';
            }
            // $data['model'] = DocumentItem::class;
            // $data['relation'] = 'document';
        } else {

            $data['model'] = PurchaseItem::class;
            $data['relation'] = 'purchase';
        }

        return $data;
    }


    public function pdf(Request $request)
    {
        ini_set('memory_limit', '4026M');
        ini_set("pcre.backtrack_limit", "5000000");
        $records = $this->getRecordsItems($request->all())->latest('id')->get();
        $type_name = ($request->type == 'sale') ? 'Ventas_' : 'Compras_';
        $type = $request->type;
        $document_type_id = $request['document_type_id'];
        $request_apply_conversion_to_pen = $request['apply_conversion_to_pen'];

        $pdf = PDF::loadView('report::general_items.report_pdf', compact("records", "type", "document_type_id", "request_apply_conversion_to_pen"))->setPaper('a4', 'landscape');

        $filename = 'Reporte_General_Productos_' . $type_name . Carbon::now();

        return $pdf->download($filename . '.pdf');
    }


    public function excel(Request $request)
    {
        ini_set('memory_limit', '4026M');
        ini_set("pcre.backtrack_limit", "5000000");
        $records = $this->getRecordsItems($request->all())->latest('id')->get();
        $type = ($request->type == 'sale') ? 'Ventas_' : 'Compras_';
        $document_type_id = $request['document_type_id'];
        $request_apply_conversion_to_pen = $request['apply_conversion_to_pen'];

        $generalItemExport = new GeneralItemExport();
        $generalItemExport
            ->records($records)
            ->type($request->type)
            ->document_type_id($document_type_id)
            ->request_apply_conversion_to_pen($request_apply_conversion_to_pen);

        return $generalItemExport->download('Reporte_General_Productos_' . $type . Carbon::now() . '.xlsx');
    }
}

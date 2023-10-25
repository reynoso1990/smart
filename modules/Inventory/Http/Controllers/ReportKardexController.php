<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\Tenant\InitStockCollection;
use App\Models\Tenant\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Inventory\Exports\KardexExport;
use Illuminate\Http\Request;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Company;
use App\Models\Tenant\Kardex;
use App\Models\Tenant\Item;
use Carbon\Carbon;
use Modules\Inventory\Models\Guide;
use Modules\Inventory\Models\InventoryKardex;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Http\Resources\ReportKardexCollection;
use Modules\Inventory\Http\Resources\ReportKardexLotsCollection;

use Modules\Inventory\Models\ItemWarehouse;
use Modules\Item\Models\ItemLotsGroup;
use Modules\Item\Models\ItemLot;

use Modules\Inventory\Http\Resources\ReportKardexLotsGroupCollection;
use Modules\Inventory\Http\Resources\ReportKardexItemLotCollection;
use Modules\Inventory\Models\Devolution;
use App\Models\Tenant\Dispatch;
use App\Models\Tenant\InitStock;
use App\Models\Tenant\Purchase;
use Illuminate\Support\Facades\DB;

class ReportKardexController extends Controller
{
    protected $models = [
        "App\Models\Tenant\Document",
        "App\Models\Tenant\Purchase",
        "App\Models\Tenant\PurchaseSettlement",
        "App\Models\Tenant\SaleNote",
        "Modules\Inventory\Models\Inventory",
        "Modules\Order\Models\OrderNote",
        Devolution::class,
        Dispatch::class
    ];



    public function avg()
    {
        $limit_date = '2022-12-31';
        //
        $item_id = 23;
        $init_cost = InventoryKardex::where('inventory_kardexable_type', 'Modules\Inventory\Models\Inventory')
            ->where('item_id', $item_id)
            ->where('date_of_issue', '<=', $limit_date)
            ->select('quantity')->first();
        $cost = 0;
        if ($init_cost->quantity != 0) {
            $item = Item::find($item_id);
            $cost = $item->purchase_unit_price;
        }
        $results = [];
        $last_date = null;
        $last_stock = 0;
        Purchase::without(['user', 'soap_type', 'state_type', 'document_type', 'currency_type', 'group', 'items', 'purchase_payments'])->join('purchase_items', 'purchases.id', '=', 'purchase_items.purchase_id')
            ->where('purchase_items.item_id', '=', $item_id)
            ->where('date_of_issue', '<=', $limit_date)
            ->select('purchases.date_of_issue', 'purchases.id', 'purchase_items.quantity', 'purchase_items.unit_price')
            ->chunk(50, function ($purchases) use ($item_id, &$results, $cost, $last_date, &$last_stock) {
                foreach ($purchases as $purchase) {
                    $stock = InventoryKardex::where('item_id', $item_id);
                    if ($last_date) {
                        $stock = $stock->where('date_of_issue', '>', $purchase->date_of_issue);
                    } else {

                        $stock = $stock->whereBetween('date_of_issue', [$last_date, $purchase->date_of_issue]);
                    }
                    $stock = $stock->where(function ($query) {
                        $query->where(function ($query) {
                            $query->where('inventory_kardexable_type', '<>', 'App\Models\Tenant\Document');
                        })
                            ->orWhere(function ($query) {
                                $query->where('inventory_kardexable_type', 'App\Models\Tenant\Document')
                                    ->whereIn('inventory_kardexable_id', function ($query) {
                                        $query->select('id')
                                            ->from('documents')
                                            ->where(function ($query) {
                                                $query->whereNull('sale_note_id')
                                                    ->orWhere('sale_note_id', '=', '')
                                                    ->orWhere(function ($query) {
                                                        $query->whereNull('order_note_id')
                                                            ->orWhere('order_note_id', '=', '');
                                                    });
                                            });
                                    });
                            })->orWhere(function ($query) {
                                $query->where('inventory_kardexable_type', 'App\Models\Tenant\SaleNote')
                                    ->whereIn('inventory_kardexable_id', function ($query) {
                                        $query->select('id')
                                            ->from('sale_notes')
                                            ->where(function ($query) {
                                                $query->whereNull('order_note_id')
                                                    ->orWhere('order_note_id', '=', '');
                                            });
                                    });
                            });
                    })
                        ->sum("quantity");

                    $last_stock += $stock;
                    $last_date = Carbon::parse($purchase->date_of_issue)->format("Y-m-d");
                    $total_cost = $purchase->quantity * $purchase->unit_price + $cost;

                    $results[] = [
                        "stock" => $last_stock,
                        "last_date" => $last_date,
                        "total_cost" => $total_cost,
                        "id_purchase" => $purchase->id,
                    ];
                }
            });






        // Obtener todos los items con su stock y costo a la fecha límite
        // $items = DB::connection('tenant')
        //     ->table('purchase_items')
        //     ->select('purchase_items.item_id', DB::raw('SUM(inventory_kardex.quantity) AS stock'), DB::raw('SUM(inventory_kardex.quantity * purchase_items.unit_price) AS costo_total'))
        //     ->leftJoin('inventory_kardex', 'purchase_items.item_id', '=', 'inventory_kardex.item_id')
        //     ->where('inventory_kardex.date_of_issue', '<=', $limit_date)
        //     ->groupBy('purchase_items.item_id')
        //     ->get();



        return compact("init_cost", "results");
    }
    public function index()
    {
        return view('inventory::reports.kardex.index');
    }
    function get_all_dates($init_date = null, $addMonth = false)
    {
        $fechaInicio = $init_date ?? "2021-01-01";
        $hoy = now();
        $fecha = Carbon::createFromFormat('Y-m-d', $fechaInicio);
        $primerosDias = [];
        while ($fecha <= $hoy) {

            $fecha = $fecha->firstOfMonth();
            if ($addMonth) {
                $fecha = $fecha
                    ->addMonth();
            }
            $primerosDias[] = $fecha->format('Y-m-d');
            $fecha->addMonthNoOverflow();
        }

        return $primerosDias;
    }
    function get_last_day($date)
    {

        $carbonFecha = Carbon::createFromFormat('Y-m-d', $date);

        $ultimoDiaMes = $carbonFecha->endOfMonth();

        return $ultimoDiaMes->format('Y-m-d'); // muestra '20
    }
    //item_adjustment
    public function item_adjustment(Request $request)
    {
        $item_id = $request->item_id;
        $warehouse_id = $request->warehouse_id;

        $warehouse_item = ItemWarehouse::where(['item_id' => $item_id, "warehouse_id" => $warehouse_id])
            ->first();
        $init_stock = InitStock::where(['item_id' => $item_id, "warehouse_id" => $warehouse_id])->latest('init_date')->first();
        $count = InitStock::where(['item_id' => $item_id, "warehouse_id" => $warehouse_id])->count();
        $one_register = false;
        if ($count == 1) {
            $one_register = true;
        }
        if ($warehouse_item && $init_stock) {
            $current_stock = $warehouse_item->stock;
            $month_stock = (float) $init_stock->stock;
            $today = Carbon::now()->format('Y-m-d');

            $sum = InventoryKardex::where(['item_id' => $item_id, "warehouse_id" => $warehouse_id])->whereBetween('date_of_issue', [$init_stock->init_date, $today])
                ->where(function ($query) {
                    $query->where(function ($query) {
                        $query->where('inventory_kardexable_type', '<>', 'App\Models\Tenant\Document');
                    })
                        ->orWhere(function ($query) {
                            $query->where('inventory_kardexable_type', 'App\Models\Tenant\Document')
                                ->whereIn('inventory_kardexable_id', function ($query) {
                                    $query->select('id')
                                        ->from('documents')
                                        ->where(function ($query) {
                                            $query->whereNull('sale_note_id')
                                                ->orWhere('sale_note_id', '=', '')
                                                ->orWhere(function ($query) {
                                                    $query->whereNull('order_note_id')
                                                        ->orWhere('order_note_id', '=', '');
                                                });
                                        });
                                });
                        })->orWhere(function ($query) {
                            $query->where('inventory_kardexable_type', 'App\Models\Tenant\SaleNote')
                                ->whereIn('inventory_kardexable_id', function ($query) {
                                    $query->select('id')
                                        ->from('sale_notes')
                                        ->where(function ($query) {
                                            $query->whereNull('order_note_id')
                                                ->orWhere('order_note_id', '=', '');
                                        });
                                });
                        });
                })
                ->sum("quantity");
                
              
                
            if($month_stock < 0 && $one_register){
              
                $month_stock = 0;
            }
            else if($one_register){
                $month_stock = 0;
            }
            $stock = $month_stock + $sum;

            return [
                "success" => $stock == $current_stock,
                "warehouse_description" => $warehouse_item->warehouse->description,
                "stock" => $warehouse_item->stock,
                "correct_stock" => $stock,

            ];
        }

        return ["success" => false];
    }
    public function stock_adjustment(Request $request)
    {
        $item_id = $request->item_id;
        $warehouse_id = $request->warehouse_id;
        $correct_stock = $request->correct_stock;
        $last_stock = InitStock::where('item_id', $item_id)
            ->where('warehouse_id', $warehouse_id)
            ->latest('init_date')->first();

        if (!$last_stock) {
            return ["success" => false, "message" => "Sin stock inicial guardado"];
        }
        $stock_warehouse = $correct_stock;

        ItemWarehouse::where('item_id', $item_id)
            ->where('warehouse_id', $warehouse_id)
            ->update(['stock' => $stock_warehouse]);
        $sum = ItemWarehouse::where('item_id', $item_id)
            ->sum('stock');
        Item::where('id', $item_id)->update(["stock" => $sum]);

        return ["success" => true, "message" => "Stock ajustado"];
    }

    public function get_all_init_stock_by_month(Request $request)
    {
        $page = $request->page;
        $item_id = $request->item_id;
        $warehouse_id = $request->warehouse_id ?? null;
        if ($page == null || $page == 0 || $page == 1) {
            $this->get_init_stock($request);
        }
        $records = InitStock::where('item_id', $item_id)->where('warehouse_id', $warehouse_id)
            ->orderBy('init_date', 'DESC');


        return new InitStockCollection($records->paginate(20));
    }
    function get_init_stock(Request $request)
    {
        $item_id = $request->item_id;
        $warehouse_id = $request->warehouse_id ?? null;
        $lastStock = InitStock::where('item_id', $item_id);

        if ($warehouse_id) {
            $lastStock = $lastStock->where('warehouse_id', $warehouse_id);
        } else {
            $lastStock = $lastStock->whereNull($warehouse_id);
        }
        $lastStock =   $lastStock->latest('init_date')->first();
        $lastDate = null;
        if ($lastStock) {
            $lastDate = $lastStock->init_date;
        }
        $stocks = [];



        if (!isset($lastDate)) {
            $init_date = InventoryKardex::where('item_id', $item_id);
            if ($warehouse_id && $warehouse_id != "all") {
                $init_date = $init_date->where('warehouse_id', $warehouse_id);
            }
            $init_date = $init_date->first();



            if (!isset($init_date)) {
                return ["success" => false, "msg" => "Error"];
            }
            $date = $init_date->date_of_issue;
            $init_stock = $init_date->quantity;

            $result = $this->get_all_dates($date);
            if (count($result) != 0) {
                foreach ($result as $key => $date) {
                    $last_date = $this->get_last_day($date);
                    $sum = InventoryKardex::where('item_id', $item_id);
                    if ($warehouse_id && $warehouse_id != 'all') {
                        $sum = $sum->where('warehouse_id', $warehouse_id);
                    }

                    $sum = $sum->whereBetween('date_of_issue', [$date, $last_date])
                        ->where(function ($query) use ($date) {
                            $query->where(function ($query) {
                                $query->where('inventory_kardexable_type', '<>', 'App\Models\Tenant\Document');
                            })
                                ->orWhere(function ($query) {
                                    $query->where('inventory_kardexable_type', 'App\Models\Tenant\Document')
                                        ->whereIn('inventory_kardexable_id', function ($query) {
                                            $query->select('id')
                                                ->from('documents')
                                                ->where(function ($query) {
                                                    $query->whereNull('sale_note_id')
                                                        ->orWhere('sale_note_id', '=', '')
                                                        ->where(function ($query) {
                                                            $query->whereNull('order_note_id')
                                                                ->orWhere('order_note_id', '=', '');
                                                        });
                                                });
                                        });
                                })->orWhere(function ($query) {
                                    $query->where('inventory_kardexable_type', 'App\Models\Tenant\SaleNote')
                                        ->whereIn('inventory_kardexable_id', function ($query) {
                                            $query->select('id')
                                                ->from('sale_notes')
                                                ->where(function ($query) {
                                                    $query->whereNull('order_note_id')
                                                        ->orWhere('order_note_id', '=', '');
                                                });
                                        });
                                });
                        })
                        ->sum("quantity");
                    if ($key != 0) {
                        $stocks[$date] = $stocks[$result[$key - 1]] + $sum;
                        $initStock = new InitStock([
                            'item_id' => $item_id,
                            'warehouse_id' => $warehouse_id,
                            'init_date' => $date,
                            'stock' => floatval($stocks[$result[$key - 1]]),
                        ]);
                        $initStock->save();
                    } else {
                        $stocks[$date] = floatval($sum);
                        $initStock = new InitStock([
                            'item_id' => $item_id,
                            'warehouse_id' => $warehouse_id,
                            'init_date' => $date,
                            'stock' => floatval($init_stock),
                        ]);
                        $initStock->save();
                    }
                }
            }
        } else {
            $result = $this->get_all_dates($lastDate->format("Y-m-d"));
            if (count($result) != 0) {
                foreach ($result as $key => $date) {
                    $last_date = $this->get_last_day($date);
                    $sum = InventoryKardex::where('item_id', $item_id);
                    if ($warehouse_id && $warehouse_id != 'all') {
                        $sum = $sum->where('warehouse_id', $warehouse_id);
                    }

                    $sum = $sum->whereBetween('date_of_issue', [$date, $last_date])
                        ->where(function ($query) use ($date) {
                            $query->where(function ($query) {
                                $query->where('inventory_kardexable_type', '<>', 'App\Models\Tenant\Document');
                            })
                                ->orWhere(function ($query) {
                                    $query->where('inventory_kardexable_type', 'App\Models\Tenant\Document')
                                        ->whereIn('inventory_kardexable_id', function ($query) {
                                            $query->select('id')
                                                ->from('documents')
                                                ->where(function ($query) {
                                                    $query->whereNull('sale_note_id')
                                                        ->orWhere('sale_note_id', '=', '')
                                                        ->where(function ($query) {
                                                            $query->whereNull('order_note_id')
                                                                ->orWhere('order_note_id', '=', '');
                                                        });
                                                });
                                        });
                                })->orWhere(function ($query) {
                                    $query->where('inventory_kardexable_type', 'App\Models\Tenant\SaleNote')
                                        ->whereIn('inventory_kardexable_id', function ($query) {
                                            $query->select('id')
                                                ->from('sale_notes')
                                                ->where(function ($query) {
                                                    $query->whereNull('order_note_id')
                                                        ->orWhere('order_note_id', '=', '');
                                                });
                                        });
                                });
                        })
                        ->sum("quantity");
                    if ($key != 0) {
                        $stocks[$date] = $stocks[$result[$key - 1]] + $sum;
                        $initStock = new InitStock([
                            'item_id' => $item_id,
                            'warehouse_id' => $warehouse_id,
                            'init_date' => $date,
                            'stock' => floatval($stocks[$result[$key - 1]]),
                        ]);
                        $initStock->save();
                    } else {
                        $stocks[$date] = floatval($sum) + floatval($lastStock->stock);
                        // $initStock = new InitStock([
                        //     'item_id' => $item_id,
                        //     'warehouse_id' => $warehouse_id,
                        //     'init_date' => $date,
                        //     'stock' => $stocks[$date],
                        // ]);
                        // $initStock->save();
                    }
                }
            }
        }
    }
    public function filter()
    {
        $warehouses = [];
        $user = User::query()->find(auth()->id());
        if ($user->type === 'admin') {
            $warehouses[] = [
                'id' => 'all',
                'name' => 'Todos'
            ];
            $records = Warehouse::query()
                ->get();
        } else {
            $records = Warehouse::query()
                ->where('establishment_id', $user->establishment_id)
                ->get();
        }

        foreach ($records as $record) {
            $warehouses[] = [
                'id' => $record->id,
                'name' => $record->description,
            ];
        }

        return [
            'warehouses' => $warehouses
        ];
    }

    public function filterByWarehouse($warehouse_id)
    {
        $query = Item::query()->whereNotIsSet()
            ->with('warehouses')
            ->where([['item_type_id', '01'], ['unit_type_id', '!=', 'ZZ']]);

        if ($warehouse_id !== 'all') {
            $query->whereHas('warehouses', function ($query) use ($warehouse_id) {
                return $query->where('warehouse_id', $warehouse_id);
            });
        }

        $items = $query->latest()
            ->get()
            ->transform(function ($row) {
                $full_description = $this->getFullDescription($row);
                return [
                    'id' => $row->id,
                    'full_description' => $full_description,
                    'internal_id' => $row->internal_id,
                    'description' => $row->description,
                    'warehouses' => $row->warehouses
                ];
            });

        return [
            'items' => $items
        ];
    }

    public function records(Request $request)
    {
        $records = $this->getRecords($request->all());

        return new ReportKardexCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function records_lots()
    {
        $records = ItemWarehouse::with(['item'])->whereHas('item', function ($q) {
            $q->where([['item_type_id', '01'], ['unit_type_id', '!=', 'ZZ'], ['lot_code', '!=', null]]);
            $q->whereNotIsSet();
        });

        return new ReportKardexLotsCollection($records->paginate(config('tenant.items_per_page')));
    }


    /**
     * @param $request
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|InventoryKardex
     */
    public function getRecords($request)
    {
        $warehouse_id = $request['warehouse_id'];
        $item_id = $request['item_id'];
        $date_start = $request['date_start'];
        $date_end = $request['date_end'];

        $records = $this->data($item_id, $warehouse_id, $date_start, $date_end);

        return $records;
    }


    /**
     * @param $item_id
     * @param $date_start
     * @param $date_end
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|InventoryKardex
     */
    private function data($item_id, $warehouse_id, $date_start, $date_end)
    {
        //$warehouse = Warehouse::where('establishment_id', auth()->user()->establishment_id)->first();

        $data = InventoryKardex::with(['inventory_kardexable']);
        if ($warehouse_id !== 'all') {
            $data->where('warehouse_id', $warehouse_id);
        }
        if ($date_start) {
            $data->where('date_of_issue', '>=', $date_start);
        }
        if ($date_end) {
            $data->where('date_of_issue', '<=', $date_end);
        }
        if ($item_id) {
            $data->where('item_id', $item_id);
        }

        $data
            ->orderBy('item_id')
            ->orderBy('id')
            ->get()->transform(function ($row) {
                return $row->getCollectionData();
            });

        // dd($data->first());
        return $data;
    }


    public function getFullDescription($row)
    {
        $desc = ($row->internal_id) ? $row->internal_id . ' - ' . $row->description : $row->description;
        $category = ($row->category) ? " - {$row->category->name}" : "";
        $brand = ($row->brand) ? " - {$row->brand->name}" : "";

        $desc = "{$desc} {$category} {$brand}";

        return $desc;
    }


    private function getData($request)
    {
        $company = Company::query()->first();
        $warehouse_id = $request->warehouse_id;
        if ($warehouse_id && $warehouse_id != 'all') {
            $establishment =  Establishment::find($request->warehouse_id);
        } else {
            $establishment =  Establishment::query()->find(auth()->user()->establishment_id);
        }
        $date_start = $request->input('date_start');
        $date_end = $request->input('date_end');
        $item_id = $request->input('item_id');
        $item = Item::query()->findOrFail($request->input('item_id'));

        $warehouse = Warehouse::query()
            ->where('establishment_id', $establishment->id)
            ->first();

        $query = InventoryKardex::query()
            ->with(['inventory_kardexable']);
        if ($warehouse_id && $warehouse_id != 'all') {
            $query = $query->where('warehouse_id', $warehouse->id);
        }

        if ($date_start && $date_end) {
            $query->whereBetween('date_of_issue', [$date_start, $date_end])
                ->orderBy('item_id')->orderBy('id');
        }

        if ($item_id) {
            $query->where('item_id', $item_id);
        }

        $records = $query->orderBy('item_id')
            ->orderBy('id')
            ->get();

        return [
            'company' => $company,
            'establishment' => $establishment,
            'warehouse' => $warehouse,
            'item_id' => $item_id,
            'item' => $item,
            'models' => $this->models,
            'date_start' => $date_start,
            'date_end' => $date_end,
            'records' => $records,
            'balance' => 0,
        ];
    }

    /**
     * PDF
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function pdf(Request $request)
    {
        $data = $this->getData($request);

        $pdf = PDF::loadView('inventory::reports.kardex.report_pdf', $data);
        $filename = 'Reporte_Kardex' . date('YmdHis');

        return $pdf->download($filename . '.pdf');
    }

    /**
     * Excel
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function excel(Request $request)
    {

        $date_start = Carbon::parse($request->date_start)->startOfMonth()->format('Y-m-d');
        $init_balance = 0;
        $init_stock = InitStock::where('item_id', $request->item_id)
            ->where('warehouse_id', $request->warehouse_id)
            ->where('init_date', $date_start)->first();
        if ($init_stock) {
            if ($date_start == $request->date_start) {
                $init_balance = $init_stock->stock;
            } else {
                $sum = InventoryKardex::where('item_id', $request->item_id)
                    ->where('warehouse_id', $request->warehouse_id)
                    ->whereBetween('date_of_issue', [$date_start, Carbon::parse($request->date_start)->subDay()->format('Y-m-d')])
                    ->where(function ($query) {
                        $query->where(function ($query) {
                            $query->where('inventory_kardexable_type', '<>', 'App\Models\Tenant\Document');
                        })
                            ->orWhere(function ($query) {
                                $query->where('inventory_kardexable_type', 'App\Models\Tenant\Document')
                                    ->whereIn('inventory_kardexable_id', function ($query) {
                                        $query->select('id')
                                            ->from('documents')
                                            ->where(function ($query) {
                                                $query->whereNull('sale_note_id')
                                                    ->orWhere('sale_note_id', '=', '')
                                                    ->orWhere(function ($query) {
                                                        $query->whereNull('order_note_id')
                                                            ->orWhere('order_note_id', '=', '');
                                                    });
                                            });
                                    });
                            })->orWhere(function ($query) {
                                $query->where('inventory_kardexable_type', 'App\Models\Tenant\SaleNote')
                                    ->whereIn('inventory_kardexable_id', function ($query) {
                                        $query->select('id')
                                            ->from('sale_notes')
                                            ->where(function ($query) {
                                                $query->whereNull('order_note_id')
                                                    ->orWhere('order_note_id', '=', '');
                                            });
                                    });
                            });
                    })
                    ->sum("quantity");

                $init_balance = $init_stock->stock + $sum;
            }
        }
        $data = $this->getData($request);
        $kardexExport = new KardexExport();
        $kardexExport
            ->init_balance($init_balance)
            ->balance($data['balance'])
            ->item_id($data['item_id'])
            ->records($data['records'])
            ->models($data['models'])
            ->company($data['company'])
            ->establishment($data['establishment'])
            ->item($data['item']);

        return $kardexExport->download('ReporteKar' . Carbon::now() . '.xlsx');
    }

    public function getRecords2($request)
    {

        $item_id = $request['item_id'];
        $date_start = $request['date_start'];
        $date_end = $request['date_end'];

        $records = $this->data2($item_id, $date_start, $date_end);

        return $records;
    }

    private function data2($item_id, $date_start, $date_end)
    {

        // $warehouse = Warehouse::where('establishment_id', auth()->user()->establishment_id)->first();

        if ($date_start && $date_end) {

            $data = ItemLotsGroup::whereBetween('date_of_due', [$date_start, $date_end])
                ->orderBy('item_id')->orderBy('id');
        } else {

            $data = ItemLotsGroup::orderBy('item_id')->orderBy('id');
        }

        if ($item_id) {
            $data = $data->where('item_id', $item_id);
        }


        return $data;
    }

    public function records_lots_kardex(Request $request)
    {
        $records = $this->getRecords2($request->all());

        return new ReportKardexLotsGroupCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function getRecords3($request)
    {

        $item_id = $request['item_id'];
        $date_start = $request['date_start'];
        $date_end = $request['date_end'];

        $records = $this->data3($item_id, $date_start, $date_end);

        return $records;
    }


    private function data3($item_id, $date_start, $date_end)
    {

        // $warehouse = Warehouse::where('establishment_id', auth()->user()->establishment_id)->first();

        if ($date_start && $date_end) {

            $data = ItemLot::whereBetween('date', [$date_start, $date_end])
                ->orderBy('item_id')->orderBy('id');
        } else {

            $data = ItemLot::orderBy('item_id')->orderBy('id');
        }

        if ($item_id) {
            $data = $data->where('item_id', $item_id);
        }


        return $data;
    }

    public function records_series_kardex(Request $request)
    {

        $records = $this->getRecords3($request->all());

        return new ReportKardexItemLotCollection($records->paginate(config('tenant.items_per_page')));

        /*$records = [];

        if($item)
        {
            $records  =  ItemLot::where('item_id', $item)->get();

        }
        else{
            $records  = ItemLot::all();
        }

       // $records  =  ItemLot::all();
        return new ReportKardexItemLotCollection($records);*/
    }




    // public function search(Request $request) {
    //     //return $request->item_selected;
    //     $balance = 0;
    //     $d = $request->d;
    //     $a = $request->a;
    //     $item_selected = $request->item_selected;

    //     $items = Item::query()->whereNotIsSet()
    //         ->where([['item_type_id', '01'], ['unit_type_id', '!=','ZZ']])
    //         ->latest()
    //         ->get();

    //     $warehouse = Warehouse::where('establishment_id', auth()->user()->establishment_id)->first();

    //     if($d && $a){

    //         $reports = InventoryKardex::with(['inventory_kardexable'])
    //                     ->where([['item_id', $request->item_selected],['warehouse_id', $warehouse->id]])
    //                     ->whereBetween('date_of_issue', [$d, $a])
    //                     ->orderBy('id')
    //                     ->paginate(config('tenant.items_per_page'));

    //     }else{

    //         $reports = InventoryKardex::with(['inventory_kardexable'])
    //                     ->where([['item_id', $request->item_selected],['warehouse_id', $warehouse->id]])
    //                     ->orderBy('id')
    //                     ->paginate(config('tenant.items_per_page'));

    //     }

    //     //return json_encode($reports);

    //     $models = $this->models;

    //     return view('inventory::reports.kardex.index', compact('items', 'reports', 'balance','models', 'a', 'd','item_selected'));
    // }
    public function getPdfGuide($guide_id)
    {
        $company = Company::query()->first();

        $record = Guide::query()
            ->with('inventory_transaction', 'warehouse', 'document_type', 'items', 'items.item')
            ->find($guide_id);

        // dd($record);

        $items = [];
        foreach ($record->items as $item) {
            $lot = ItemLotsGroup::where('item_id', $item->item_id)->where('created_at', $record->created_at)->first();
            $items[] = [
                'item_internal_id' => $item->item->internal_id,
                'item_name' => $item->item_name,
                'unit_type_id' => $item->item->unit_type_id,
                'quantity' => $item->quantity,
                'lot' => $lot ? $lot->code : null,
            ];
        }

        // dd($items);

        $data = [
            'company_number' => $company->number,
            'document_type_name' => $record->document_type->description,
            'document_number' => $record->series . '-' . $record->number,
            'document_date_of_issue' => $record->date_of_issue->format('d/m/Y'),
            'warehouse_name' => $record->warehouse->description,
            'transaction_name' => $record->inventory_transaction->name,
            'items' => $items
        ];

        $pdf = PDF::loadView('inventory::reports.kardex.guide', $data);
        $pdf->setPaper('A4', 'portrait');
        // $pdf->setPaper('A4', 'landscape');
        $filename = 'Guia_' . date('YmdHis');

        return $pdf->stream($filename . '.pdf');
    }
}

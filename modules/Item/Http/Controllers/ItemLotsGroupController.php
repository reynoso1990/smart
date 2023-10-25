<?php

namespace Modules\Item\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Item\Models\ItemLotsGroup;
use App\Models\Tenant\Item;
use Carbon\Carbon;
use Modules\Item\Http\Resources\ItemLotGroupCollection;
use Modules\Item\Models\ItemLotsGroupState;

class ItemLotsGroupController extends Controller
{


    public function index()
    {
        return view('item::item-lots-group.index');
    }


    public function columns()
    {
        return [
            'code' => 'Lote',
            'date_of_due' => 'Fecha',
            'item_description' => 'Producto',
        ];
    }
    public function update_state(Request $request)
    {
        $lot_id = $request->lot_id;
        $state_id = $request->state_id;

        ItemLotsGroup::where('id', $lot_id)->update(["state_id" => $state_id]);

        return ["success" => true];
    }
    public function tables(Request $request)
    {

        $states = ItemLotsGroupState::all();

        return compact('states');
    }

    public function records(Request $request)
    {

        $records = $this->getRecords($request);

        return new ItemLotGroupCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function getRecords($request)
    {

        if ($request->column == 'item_description') {

            $records = ItemLotsGroup::whereHas('item', function ($query) use ($request) {
                $query->where('description', 'like', "%{$request->value}%")->latest();
            });
        } else {
            $records = ItemLotsGroup::where($request->column, 'like', "%{$request->value}%")->latest();
        }

        return $records;
    }


    public function record($id)
    {
        $record = ItemLotsGroup::findOrFail($id);

        return $record;
    }


    public function store(Request $request)
    {

        $id = $request->input('id');
        $record = ItemLotsGroup::findOrFail($id);
        $record->code = $request->code;
        $record->state_id = $request->state_id;
        $record->date_of_due = Carbon::parse($request->date_of_due)->format("Y-m-d");
        $record->save();

        return [
            'success' => true,
            'message' => 'Lote editado con éxito',
        ];
    }

    public function getAvailableItemLotsGroup($item_id)
    {
        return ItemLotsGroup::where('item_id', $item_id)
            ->get()
            ->transform(function ($row) {
                return $row->getRowResourceSale();
            });
    }
}

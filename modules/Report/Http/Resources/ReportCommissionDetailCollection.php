<?php

namespace Modules\Report\Http\Resources;

use App\Models\Tenant\Item;
use App\Models\Tenant\ItemUnitType;
use Modules\Report\Helpers\UserCommissionHelper;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ReportCommissionDetailCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->transform(function ($row, $key) {
            //dd(request()->input('item_id'),request()->input('date_end'));
            $type_document = '';
            $quantity = $row->quantity;
            $unit_price = $row->unit_price;
            $presentation_name = null;
            $relation = $row->document_id ? $row->document : $row->sale_note;
            if ($row->document_id) {
                $user = $row->document->user->name;
                $user_id=$row->document->user->id;
                $type_document =  $row->document->document_type_id == '01' ? 'FACTURA' : 'BOLETA';
                if (isset($row->item->presentation->id)) {
                    $presentation = $row->item->presentation;
                    $presentation_name = $presentation->description;
                    $quantity = $presentation->quantity_unit;
                    $unit_price = number_format(floatval($unit_price) , 2, ".", "");
                    // $unit_price = 
                }
             } else if ($row->sale_note_id) {
                $user_id =$row->sale_note->user->id;
                $user = $row->sale_note->user->name;

                $type_document = 'NOTA DE VENTA';
            }
            $data_items_purchase_unit_price =0;
            $items = Item::find($row->item_id); 
            if(request()->input('item_id')!==null && request()->input('unit_type_id')!==null){
                $data_items = ItemUnitType::where('item_id',request()->input('item_id'))->where('unit_type_id',request()->input('unit_type_id'))->first();
                $data_items_purchase_unit_price =  $data_items!=null ? $data_items->purchase_unit_price : 0.00;
                $purchase_unit_price = $items->purchase_unit_price*$data_items->factor_default;
            }else{
                $purchase_unit_price = $items->purchase_unit_price;
            }
           
            return [
                'id' => $row->id,
                'user_id' => $user_id,
                'user' => $user,
                'date_of_issue' => $relation->date_of_issue->format('Y-m-d'),
                'type_document' => $type_document,
                'serie' => $relation->number_full,
                'customer_number' => $relation->customer->number,
                'customer_name' => $relation->customer->name,
                'name' => $row->relation_item->description,
                'quantity' => $quantity,
                'presentation_name' => $presentation_name,
                'purchase_unit_price' => number_format($purchase_unit_price,2),
                'unit_price' => $unit_price,
                'unit_gain' => ( $unit_price -   $purchase_unit_price),
                'overall_profit' => (( $unit_price * $quantity) - (number_format($purchase_unit_price,2) * $quantity)),
            ];
        });
    }
}

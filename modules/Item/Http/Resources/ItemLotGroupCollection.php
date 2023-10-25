<?php

namespace Modules\Item\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ItemLotGroupCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toArray($request)
    {
        return $this->collection->transform(function ($row, $key) {



            $status = $row->state;


            return [
                'id' => $row->id,
                'code' => $row->code,
                'item_description' => $row->item->description,
                'date' => $row->date_of_due,
                'state_id' => optional($status)->id,
                'item_id' => $row->item_id,
                'stock' => $row->item->stock,
                // 'lot_code' => ($row->item_loteable_type) ? (isset($row->item_loteable->lot_code) ? $row->item_loteable->lot_code:null):null
            ];
        });
    }
}

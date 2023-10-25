<?php

namespace Modules\Restaurant\Models;

use App\Models\Tenant\ModelTenant;

class OrdenItem extends ModelTenant
{

    public $timestamps = false;
    protected $table = "orden_item";
    protected $with = ['food','status'];

    protected $fillable = [
        'date',
        'time',
        'observations',
        'orden_id',
        'item_id',
        'status_orden_id',
        'area_id',
        'quantity',
        'price',
     ];

    public function food()
    {
        return  $this->belongsTo(Food::class);
    }
    public function status()
    {
        return  $this->belongsTo(StatusOrden::class);
    }

}

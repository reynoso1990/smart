<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Catalogs\PriceType;
use App\Models\Tenant\Catalogs\SystemIscType;

class PackageHandlerItem extends ModelTenant
{
    protected $with = ['affectation_igv_type', 'system_isc_type', 'price_type'];
    protected $casts =[
        'total_base_igv' => 'float',
        'total_igv' => 'float',
        'total_base_isc' => 'float',
        'total_isc' => 'float',
        'total_base_other_taxes' => 'float',
        'total_other_taxes' => 'float',
        'total_taxes' => 'float',
        'total_value' => 'float',
        'total' => 'float',
        'unit_value' => 'float',
        'unit_price' => 'float',
        'total_charge' => 'float',
        'total_discount' => 'float',
        
    ];
    protected $fillable = [
        "id",
        "item_id",
        "item",
        "quantity",
        "unit_value",
        "affectation_igv_type_id",
        "total_base_igv",
        "percentage_igv",
        "total_igv",
        "system_isc_type_id",
        "total_base_isc",
        "percentage_isc",
        "total_isc",
        "total_base_other_taxes",
        "percentage_other_taxes",
        "total_other_taxes",
        "total_taxes",
        "price_type_id",
        "unit_price",
        "total_value",
        "total_charge",
        "total_discount",
        "total",
        "attributes",
        "package_handler_id",
    ];

    public function package_handler()
    {
        return $this->belongsTo(PackageHandler::class);
    }

    public function relation_item(){

        return $this->belongsTo(Item::class,'item_id');
    }
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function affectation_igv_type()
    {
        return $this->belongsTo(AffectationIgvType::class, 'affectation_igv_type_id');
    }
    
    public function system_isc_type()
    {
        return $this->belongsTo(SystemIscType::class, 'system_isc_type_id');
    }

    public function price_type()
    {
        return $this->belongsTo(PriceType::class, 'price_type_id');
    }

    public function getItemAttribute($value)
    {
        return (is_null($value)) ? null : (object)json_decode($value);
    }
    public function setItemAttribute($value)
    {
        $this->attributes['item'] = (is_null($value)) ? null : json_encode($value);
    }

    public function getattributesAttribute($value)
    {
        return (is_null($value)) ? null : (object)json_decode($value);
    }
    public function setattributesAttribute($value)
    {
        $this->attributes['attributes'] = (is_null($value)) ? null : json_encode($value);
    }
  
}

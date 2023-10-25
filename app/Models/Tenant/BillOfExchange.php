<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\CurrencyType;
use Modules\Finance\Models\PaymentFile;

/**
 * Class DocumentFee
 *
 * @package App\Models\Tenant
 */
class BillOfExchange extends ModelTenant
{
    public $timestamps = false;
    protected $table = 'bills_of_exchange';

    protected $fillable = [
        'id',
        'series',
        'number',
        'date_of_due',
        'total',
        'establishment_id',
        'customer_id',
        'user_id',
    ];

    protected $casts = [
        'date_of_due' => 'date',
        'total' => 'float',
    ];

    public function establishment()
    {
        return $this->belongsTo(Establishment::class, 'establishment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

  

    public function items(){
        return $this->hasMany(BillOfExchangeDocument::class);
    }

    public function customer()
    {
        return $this->belongsTo(Person::class, 'customer_id');
    }

    public function payments()
    {
        return $this->hasMany(BillOfExchangePayment::class);
    }
   
}

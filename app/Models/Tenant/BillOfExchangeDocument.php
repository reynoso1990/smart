<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\CurrencyType;

/**
 * Class DocumentFee
 *
 * @package App\Models\Tenant
 */
class BillOfExchangeDocument extends ModelTenant
{
    public $timestamps = false;
    protected $table = 'bills_of_exchange_documents';

    protected $fillable = [
        'id',
        'bill_of_exchange_id',
        'document_id',
        'total',
    ];

 
    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function bill_of_exchange()
    {
        return $this->belongsTo(BillOfExchange::class, 'bill_of_exchange_id');
    }
}

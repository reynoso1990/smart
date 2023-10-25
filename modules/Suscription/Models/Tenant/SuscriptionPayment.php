<?php


namespace Modules\Suscription\Models\Tenant;

use App\Models\Tenant\Document;
use App\Models\Tenant\ModelTenant;
use App\Models\Tenant\SaleNote;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class SuscriptionPayment extends ModelTenant
{

    use UsesTenantConnection;
    protected $with = ['document', 'sale_note'];
    protected $table = 'suscription_payment_periods';
    // public $timestamps = false;

    protected $fillable = [
        'document_id',
        'sale_note_id',
        'client_id',
        'child_id',
        'period',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
    public function sale_note()
    {
        return $this->belongsTo(SaleNote::class);
    }
}

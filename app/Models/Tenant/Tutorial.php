<?php

namespace App\Models\Tenant;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutorial extends ModelTenant
{
    use UsesTenantConnection;
    public $timestamps = false;
    protected $table = 'tutorials';
    protected $fillable = [
        'title',
        'description',
        'image',
        'link',
        'type',
        'location'
    ];
}

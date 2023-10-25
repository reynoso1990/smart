<?php

namespace App\Http\ViewComposers\Tenant;

use App\Models\Tenant\Order;
use App\Models\Tenant\Company;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Tutorial;

class CompanyViewComposer
{
    public function compose($view)
    {
        $view->vc_company = Company::first();
        $establishments = Establishment::query();
        $view->vc_config = Configuration::first();
        $view->vc_shortcuts_right = Tutorial::where('type',0)->where('location','Derecha')->get();
        $view->vc_shortcuts_left = Tutorial::where('type',0)->where('location','Izquierda')->get();

        $view->vc_videos = Tutorial::where('type',1)->first();
 
        $view->vc_logotipo = $establishments->count() > 1 ?  $establishments->first()->logo : 'storage/uploads/logos/' . $view->vc_company->logo;

        $view->vc_orders = Order::where('status_order_id', 1)
            ->count();
    }
}

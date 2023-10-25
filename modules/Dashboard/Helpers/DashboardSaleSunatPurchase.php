<?php

namespace Modules\Dashboard\Helpers;

use App\Models\Tenant\Document;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\SaleNote;
use App\Models\Tenant\SunatPurchaseSale;
use Modules\Expense\Models\Expense;

class DashboardSaleSunatPurchase
{

    public function data($year)
    {
        //create a array with all months with the year of parameter and day 1
        $months = array();
        for ($i = 1; $i <= 12; $i++) {
            $months[] = [
                'month' => $i,
                'date' => date('Y-m-d', strtotime($year . '-' . $i . '-01')),
                'internal_sale' => 0,
                'purchase_expense' => 0,
                'sunat_sale' => 0,
            ];
        }

        foreach ($months as $key => $m) {
            $month = $m['date'];
            $register = SunatPurchaseSale::where('period', $month)->first();
            if ($register && $register->show == true) {
                $months[$key]['internal_sale'] = $register->internal_sale;
                $months[$key]['purchase_expense'] = $register->purchase_expense;
                $months[$key]['sunat_sale'] = $register->sunat_sale;
            } else {
                $purchases = Purchase::where('state_type_id', '01')->whereBetween('date_of_issue', [$month, date('Y-m-t', strtotime($month))])->sum('total');
                $expenses = Expense::whereBetween('date_of_issue', [$month, date('Y-m-t', strtotime($month))])->sum('total');
                $invoices = Document::where('state_type_id', '05')
                    ->whereIn('document_type_id', ['01', '03', '08'])
                    ->whereBetween('date_of_issue', [$month, date('Y-m-t', strtotime($month))])->sum('total');
                $credist = Document::where('state_type_id', '05')
                    ->whereIn('document_type_id', ['07'])
                    ->whereBetween('date_of_issue', [$month, date('Y-m-t', strtotime($month))])->sum('total');
                $sale_notes = SaleNote::where('state_type_id', '01')->whereBetween('date_of_issue', [$month, date('Y-m-t', strtotime($month))])->sum('total');
                $months[$key]['internal_sale'] = $sale_notes;
                $months[$key]['purchase_expense'] = $purchases + $expenses;
                $months[$key]['sunat_sale'] = $invoices - $credist;
            }
        }

        return $months;
    }
}

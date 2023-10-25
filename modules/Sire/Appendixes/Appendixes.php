<?php

namespace Modules\Sire\Appendixes;

use App\Models\Tenant\Company;
use App\Models\Tenant\Document;

class Appendixes
{
    protected $ruc;
    protected $company_name;
    protected $period;
    protected $car_sunat;
    protected $date_of_issue;
    protected $date_of_due;
    protected $document_type_id;
    protected $series;
    protected $number;
    protected $number_last_range;
    protected $document_customer_id;
    protected $number_customer;
    protected $customer_name;
    protected $exportation = null;
    protected $value;
    protected $discount;
    protected $igv;
    protected $discount_igv;
    protected $exonerated;
    protected $inaffected;
    protected $isc;
    protected $value_arroz_pilado = null;
    protected $ivap_arroz_pilado = null;
    protected $icbper = 0.00;
    protected $others_taxes = null;
    protected $total = 0;
    protected $currency_id = 'PEN';
    protected $exchange_rate_sale = 1;
    protected $date_of_issue_modification = null;
    protected $document_type_id_modification = null;
    protected $series_modification = null;
    protected $number_modification = null;
    protected $proyect_indentification = null;
    protected $clu = null;
    protected $idx = null;
    protected $ajustment_period = null;
    protected $cuo = null;
    protected $correlative = null;
    protected $appendix = '2';
    protected $error_type_1 = null;
    protected $cp_canceled_payment = '1';
    protected $state = '1';

    const DIVIDER = '|';
    public function __construct(Document $document, $idx, $appendix = '2')
    {
        $company = Company::active();
        $this->appendix = $appendix;
        $this->idx = $idx;
        $this->ruc = $company->number;
        $this->company_name = $company->name;
        $this->period($document->date_of_issue);
        if($this->appendix != '5') {
            $this->date_of_issue = $document->date_of_issue->format('Y-m-d');
        }else{
            $this->date_of_issue = $document->date_of_issue->format('d/m/Y');

        }
        $this->date_of_due = $document->date_of_due ? $document->date_of_due->format('Y-m-d') : null;
        $this->document_type_id = $document->document_type_id;
        $this->series = $document->series;
        $this->number = $document->number;
        $this->document_customer_id = $document->customer->identity_document_type_id;
        $this->number_customer = $document->customer->number;
        $this->customer_name = $document->customer->name;
        $this->value = $document->total_value;
        $this->discount = $document->total_discount;
        $this->igv = $document->total_igv;
        $this->exonerated = $document->total_exonerated;
        $this->inaffected = $document->total_unaffected;
        $this->isc = $document->total_isc;
        $this->icbper = $document->total_plastic_bag_taxes;
        $this->total = $document->total;
        $this->currency_id = $document->currency_type_id;
        $this->exchange_rate_sale = $document->exchange_rate_sale;
        $this->ajustment_period = $document->date_of_issue->format('Ym') . '00';
        $this->cuo_generate();
    }

    function cuo_generate()
    {
        $this->cuo = '02'    . str_pad($this->idx, 4, "0", STR_PAD_LEFT);
        $this->correlative = 'M' . $this->cuo;
    }

    function generate_txt()
    {
        $txt = '';
        if ($this->appendix != '5') {
            $txt .= $this->ruc . self::DIVIDER;
            $txt .= $this->company_name . self::DIVIDER;
            $txt .= $this->period . self::DIVIDER;
            $txt .= $this->car_sunat . self::DIVIDER;
        } else {
            $txt .= $this->ajustment_period . self::DIVIDER;
            $txt .= $this->cuo . self::DIVIDER;
            $txt .= $this->correlative . self::DIVIDER;
        }
        $txt .= $this->date_of_issue . self::DIVIDER;
        $txt .= $this->date_of_due . self::DIVIDER;
        $txt .= $this->document_type_id . self::DIVIDER;
        $txt .= $this->series . self::DIVIDER;
        $txt .= $this->number . self::DIVIDER;
        $txt .= $this->number_last_range . self::DIVIDER;
        $txt .= $this->document_customer_id . self::DIVIDER;
        $txt .= $this->number_customer . self::DIVIDER;
        $txt .= $this->customer_name . self::DIVIDER;
        $txt .= $this->exportation . self::DIVIDER;
        $txt .= $this->value . self::DIVIDER;
        $txt .= $this->discount . self::DIVIDER;
        $txt .= $this->igv . self::DIVIDER;
        $txt .= $this->discount_igv . self::DIVIDER;
        $txt .= $this->exonerated . self::DIVIDER;
        $txt .= $this->inaffected . self::DIVIDER;
        $txt .= $this->isc . self::DIVIDER;
        $txt .= $this->value_arroz_pilado . self::DIVIDER;
        $txt .= $this->ivap_arroz_pilado . self::DIVIDER;
        $txt .= $this->icbper . self::DIVIDER;
        $txt .= $this->others_taxes . self::DIVIDER;
        $txt .= $this->total . self::DIVIDER;
        $txt .= $this->currency_id . self::DIVIDER;
        $txt .= $this->exchange_rate_sale . self::DIVIDER;
        $txt .= $this->date_of_issue_modification . self::DIVIDER;
        $txt .= $this->document_type_id_modification . self::DIVIDER;
        $txt .= $this->series_modification . self::DIVIDER;
        $txt .= $this->number_modification . self::DIVIDER;
        $txt .= $this->proyect_indentification . self::DIVIDER;
        if ($this->appendix == '5') {
            $txt .= $this->error_type_1 . self::DIVIDER;
            $txt .= $this->cp_canceled_payment . self::DIVIDER;
            $txt .= $this->state . self::DIVIDER;
        }
        $txt .= $this->clu . self::DIVIDER;

        return $txt;
    }


    function period($date_of_issue)
    {
        $this->period = date('Ym', strtotime($date_of_issue));
    }
}

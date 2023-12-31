@php
    if ($document != null) {
        $establishment = $document->establishment;
        $logo = "storage/uploads/logos/{$company->logo}";
        if ($establishment->logo) {
            $logo = "{$establishment->logo}";
        }
        $customer = $document->customer;
        $invoice = $document->invoice;
        $document_base = $document->note ? $document->note : null;
    
        //$path_style = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'style.css');
        $document_number = $document->series . '-' . str_pad($document->number, 8, '0', STR_PAD_LEFT);
    
        if ($document_base) {
            $affected_document_number = $document_base->affected_document ? $document_base->affected_document->series . '-' . str_pad($document_base->affected_document->number, 8, '0', STR_PAD_LEFT) : $document_base->data_affected_document->series . '-' . str_pad($document_base->data_affected_document->number, 8, '0', STR_PAD_LEFT);
        } else {
            $affected_document_number = null;
        }
    
        $payments = $document->payments;
    
        // $document->load('reference_guides');
    
        if ($document->payments) {
            $total_payment = $document->payments->sum('payment');
            $balance = $document->total - $total_payment - $document->payments->sum('change');
        }
    }
    
    $accounts = \App\Models\Tenant\BankAccount::all();
    
    $path_style = app_path('CoreFacturalo' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR . 'style.css');
@endphp

<head>
    <link href="{{ $path_style }}" rel="stylesheet" />
</head>

<body>
    <table class="full-width">
        @php
            $company = \App\Models\Tenant\Company::first();
        @endphp
        @if ($company->footer_logo)
            @php
                $footer_logo = "storage/uploads/logos/{$company->footer_logo}";
            @endphp
            <tr>
                <td class="text-center pt-5">
                    <img style="max-height: 40px;"
                        src="data:{{ mime_content_type(public_path("{$footer_logo}")) }};base64, {{ base64_encode(file_get_contents(public_path("{$footer_logo}"))) }}"
                        alt="{{ $company->name }}">
                </td>
            </tr>
        @endif
        @if ($company->footer_text_template)
            <tr>
                <td class="text-center desc pt-5">
                    {!! func_str_find_url($company->footer_text_template) !!}
                </td>
            </tr>
        @endif
        <tr>
            <td class="text-center desc font-bold">Para consultar el comprobante ingresar a {!! url('/buscar') !!}</td>
        </tr>
    </table>
</body>

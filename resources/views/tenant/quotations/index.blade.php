@extends('tenant.layouts.app')

@section('content')

    <tenant-quotations-index
    	:type-user="{{json_encode(Auth::user()->type)}}"
    	:soap-company="{{ json_encode($soap_company) }}"
        :quotations_optional ="{{ json_encode($quotations_optional) }}"
        :quotations_optional_value ="{{ json_encode($quotations_optional_value) }}"
    	:generate-order-note-from-quotation="{{ json_encode($generate_order_note_from_quotation) }}">
    </tenant-quotations-index>

@endsection

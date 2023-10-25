@extends('tenant.layouts.app')

@section('content')
    <tenant-persons-index 
        :type-user="{{ json_encode(Auth::user()->type) }}" 
        :type="{{ json_encode($type) }}"
        :driver="{{ json_encode($driver) }}"
        :suscriptionames="{{ json_encode($suscriptionames) }}"
        :configuration="{{ \App\Models\Tenant\Configuration::getPublicConfig() }}">
    </tenant-persons-index>
@endsection

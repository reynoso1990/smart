@extends('tenant.layouts.app')

@section('content')
    <tenant-dispatches-create
       :series_default="{{json_encode($series_default)}}"
        :configuration="{{\App\Models\Tenant\Configuration::getPublicConfig()}}"
        :auth-user="{{json_encode(Auth::user()->getDataOnlyAuthUser())}}"
    ></tenant-dispatches-create>
@endsection

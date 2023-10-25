@extends('tenant.layouts.pos')


@section('content')
    <restaurant-ordens-index :configuration="{{ $configuration }}" :user="{{ json_encode(Auth::user()) }}">
    </restaurant-ordens-index>
@endsection

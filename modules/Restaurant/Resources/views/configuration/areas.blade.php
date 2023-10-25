<?php
    $configurations= App\Models\Configuration::first();
?>
@extends('tenant.layouts.app')
@section('content')
    <tenant-restaurant-items
    type='areas'
    :configurations="{{ json_encode($configurations) }}"
    title='Listado de Áreas'>
</tenant-restaurant-items>
@endsection

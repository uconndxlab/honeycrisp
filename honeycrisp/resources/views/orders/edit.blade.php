@extends('layouts.app')
@section('title', 'Edit Order')

@section('content')
@include('orders.parts.order-meta-form')

@include('orders.parts.order-items')

@endsection


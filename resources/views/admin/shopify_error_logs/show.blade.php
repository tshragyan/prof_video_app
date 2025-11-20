@extends('admin.layouts.layout')

@section('title', 'Error Log')

@section('content')
    @foreach(\App\Models\ShopifyErrorLog::$columns as $column)
        <ul class="list-group list-group-horizontal">
            <li class="list-group-item list-group-item-light" style="width:50%"><b>{{ $column }}</b></li>
            <li class="list-group-item" style="width:50%">{{$shopifyErrorLog->$column}}</li>
        </ul>
    @endforeach
@endsection

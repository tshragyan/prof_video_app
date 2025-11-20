@extends('admin.layouts.layout')

@section('title', 'Users List')

@section('content')
    @foreach(\App\Models\User::$columns as $column)
        <ul class="list-group list-group-horizontal">

            <li class="list-group-item list-group-item-light" style="width:50%"><b>{{ $column }}</b></li>
            @if($column == 'status')
                <li class="list-group-item" style="width:50%">{{\App\Models\User::STATUS_MAP[$user->$column]}}</li>

            @else
                <li class="list-group-item" style="width:50%">{{$user->$column}}</li>
            @endif
        </ul>
    @endforeach

    <ul class="list-group list-group-horizontal">
        <li class="list-group-item list-group-item-light" style="width:50%"><b>Products Count</b></li>
        <li class="list-group-item" style="width:50%">
            <a href="{{route('admin.products.index', ['user_id' => $user->id])}}">
            {{$user->products()->count()}}</li>
            </a>
    </ul>
@endsection

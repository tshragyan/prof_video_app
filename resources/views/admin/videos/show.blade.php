@extends('admin.layouts.layout')

@section('title', 'Users List')

@section('content')
    @foreach(\App\Models\Video::$columns as $column)

        <ul class="list-group list-group-horizontal">
            @if($column == 'product_id')
                <li class="list-group-item list-group-item-light" style="width:50%"><b>{{ $column }}</b></li>
                <li class="list-group-item" style="width:50%">
                    <a class="nav-link" aria-current="page"
                       href="{{route('admin.products.show', ['product' => $video->$column])}}">
                        {{$video->product->title}}
                    </a>
                </li>
            @elseif($column == 'user_id')
                <li class="list-group-item list-group-item-light" style="width:50%"><b>{{ $column }}</b></li>
                <li class="list-group-item" style="width:50%">
                    <a
                       href="{{route('admin.users.show', ['user' => $video->$column])}}">
                        {{$video->user->name}}
                    </a>
                </li>
            @else
                <li class="list-group-item list-group-item-light" style="width:50%"><b>{{ $column }}</b></li>
                <li class="list-group-item" style="width:50%">{{$video->$column}}</li>
            @endif
        </ul>

    @endforeach
    <h2>User</h2>
    @foreach(\App\Models\User::$columns as $column)
        <ul class="list-group list-group-horizontal">
            <li class="list-group-item list-group-item-light" style="width:50%"><b>{{ $column }}</b></li>
             @if($column == 'status')
                <li class="list-group-item" style="width:50%">{{\App\Models\User::STATUS_MAP[$video->user->$column]}}</li>

            @else
                <li class="list-group-item" style="width:50%">{{$video->user->$column}}</li>

            @endif
        </ul>
    @endforeach
@endsection

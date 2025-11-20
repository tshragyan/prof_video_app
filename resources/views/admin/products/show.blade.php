@extends('admin.layouts.layout')

@section('title', 'Product')

@section('content')
    <div class="container mt-5">
        @foreach(\App\Models\Product::$columns as $column)

            <ul class="list-group list-group-horizontal">
                @if($column == 'user_id')
                    <li class="list-group-item list-group-item-light" style="width:50%"><b>{{ $column }}</b></li>
                    <li class="list-group-item" style="width:50%"><a
                            href="{{route('admin.users.show', ['user' => $product->user_id])}}">{{$product->user->name}}</a>
                    </li>
                @else
                    <li class="list-group-item list-group-item-light" style="width:50%"><b>{{ $column }}</b></li>
                    <li class="list-group-item" style="width:50%">{{$product->$column}}</li>
                @endif
            </ul>
        @endforeach

        <h2>User</h2>
        @foreach(\App\Models\User::$columns as $column)
            <ul class="list-group list-group-horizontal">
                <li class="list-group-item list-group-item-light" style="width:50%"><b>{{ $column }}</b></li>
                <li class="list-group-item" style="width:50%">{{$product->user->$column}}</li>
            </ul>
        @endforeach
    </div>
@endsection

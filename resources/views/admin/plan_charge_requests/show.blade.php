@extends('admin.layouts.layout')

@section('title', 'Shopify Error Log')

@section('content')
    <div class="container mt-5">
        @foreach(\App\Models\PlanChargeRequest::$columns as $column)

            <ul class="list-group list-group-horizontal">
                @if($column == 'user_id')
                    <li class="list-group-item list-group-item-light" style="width:50%"><b>{{ $column }}</b></li>
                    <li class="list-group-item" style="width:50%"><a
                            href="{{route('admin.users.show', ['user' => $planChargeRequest->user_id])}}">{{$planChargeRequest->user->name}}</a>
                    </li>
                @elseif($column == 'status')
                    <li class="list-group-item list-group-item-light" style="width:50%"><b>{{ $column }}</b></li>
                    <li class="list-group-item"
                        style="width:50%">{{\App\Models\PlanChargeRequest::STATUS_MAPPINGS[$planChargeRequest->$column]}}
                    </li>
                @elseif($column == 'type')
                    <li class="list-group-item list-group-item-light" style="width:50%"><b>{{ $column }}</b></li>
                    <li class="list-group-item"
                        style="width:50%">{{\App\Models\PlanChargeRequest::TYPES_MAPPING[$planChargeRequest->$column]}}
                    </li>
                @else
                    <li class="list-group-item list-group-item-light" style="width:50%"><b>{{ $column }}</b></li>
                    <li class="list-group-item" style="width:50%">{{$planChargeRequest->$column}}</li>
                @endif
            </ul>
        @endforeach
        <h2>User</h2>
        @foreach(\App\Models\User::$columns as $column)
            <ul class="list-group list-group-horizontal">
                <li class="list-group-item list-group-item-light" style="width:50%"><b>{{ $column }}</b></li>
                <li class="list-group-item" style="width:50%">{{$planChargeRequest->user->$column}}</li>
            </ul>
        @endforeach
    </div>
@endsection

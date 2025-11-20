@extends('admin.layouts.layout')

@section('title', 'Plan ')

@section('content')
    <a href="{{route('admin.plans.edit', ['plan' => $plan->id])}}" class="btn btn-success">Edit Plan</a>
    <a href="{{route('admin.plans.destroy', ['plan' => $plan->id])}}" class="btn btn-danger">Delete Plan</a>
    <div class="container mt-5">
        @foreach(\App\Models\Plan::$columns as $column)
            <ul class="list-group list-group-horizontal">
                <li class="list-group-item list-group-item-light" style="width:50%"><b>{{ $column }}</b></li>
                <li class="list-group-item" style="width:50%">{{$plan->$column}} </li>
            </ul>
        @endforeach
        <ul class="list-group list-group-horizontal">
            <li class="list-group-item list-group-item-light" style="width:50%"><b>Users Count</b></li>
            <li class="list-group-item" style="width:50%">{{$plan->users()->count()}}</li>
        </ul>

    </div>
@endsection

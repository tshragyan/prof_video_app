<?php

/** @var \App\Models\PlanChargeRequest[] $planChargeRequests */
?>

@extends('admin.layouts.layout')
@section('title', 'Plan Charge Requests')
@section('content')
    <div class="container">
        <table class="table">
            <thead>
            <tr>
                @foreach(\App\Models\PlanChargeRequest::$columns as $column)
                    <th scope="col">{{ $column }}</th>
                @endforeach
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($planChargeRequests as $planChargeRequest)
                <tr>
                    @foreach(\App\Models\PlanChargeRequest::$columns as $column)
                        @if($column == 'plan_id')
                            <td scope="row">
                                <a class="nav-link" aria-current="page"
                                   href="{{route('admin.plans.show', ['plan' => $planChargeRequest->$column])}}">
                                    {{$planChargeRequest->plan->name}}
                                </a>
                            </td>
                        @elseif($column == 'user_id')
                            <td scope="row">
                                <a class="nav-link" aria-current="page"
                                   href="{{route('admin.users.show', ['user' => $planChargeRequest->$column])}}">
                                    {{$planChargeRequest->user->name}}
                                </a>
                            </td>
                        @elseif($column == 'status')
                            <td scope="row">
                                {{ \App\Models\PlanChargeRequest::STATUS_MAPPINGS[$planChargeRequest->$column] }}
                            </td>
                        @elseif($column == 'type')
                            <td scope="row">
                                {{ \App\Models\PlanChargeRequest::TYPES_MAPPING[$planChargeRequest->$column] }}
                            </td>
                        @elseif($column == 'activated_at')
                            <td scope="row">
                                {{ $planChargeRequest->$column ? \Carbon\Carbon::createFromTimestamp($planChargeRequest->$column)->format('y/m/d') : '-' }}
                            </td>
                        @elseif($column == 'canceled_at')
                            <td scope="row">
                                {{ $planChargeRequest->$column ? \Carbon\Carbon::createFromTimestamp($planChargeRequest->$column)->format('y/m/d') : '-' }}
                            </td>
                        @else
                            <td scope="row">{{$planChargeRequest->$column}}</td>
                        @endif
                    @endforeach

                    <td scope="row">
                        <a class="icon-link" href="{{route('admin.plan_charge_requests.show', ['planChargeRequest' => $planChargeRequest->id])}}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                 class="bi bi-eye" viewBox="0 0 16 16">
                                <path
                                    d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                <path
                                    d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                            </svg>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{$planChargeRequests->links()}}
    </div>
@endsection

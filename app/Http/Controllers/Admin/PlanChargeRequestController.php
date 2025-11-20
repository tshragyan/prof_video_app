<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlanChargeRequest;
use Illuminate\Http\Request;

class PlanChargeRequestController extends Controller
{
    public function index(Request $request)
    {
        $planChargeRequests = PlanChargeRequest::query()->paginate(20);
        return view('admin.plan_charge_requests.index', compact('planChargeRequests'));
    }

    public function show(PlanChargeRequest $planChargeRequest)
    {
        return view('admin.plan_charge_requests.show', compact('planChargeRequest'));
    }
}

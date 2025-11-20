<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlanCreateRequest;
use App\Models\Plan;
use App\Models\Video;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plans = Plan::query()->paginate(20);
        return view('admin.plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.plans.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PlanCreateRequest $request)
    {
        $data = $request->validated();
        Plan::query()->create($data);
        return redirect()->route('admin.plans.index')->with('success', 'Plan created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Plan $plan)
    {
        return view('admin.plans.show', compact('plan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Plan $plan)
    {
        return view('admin.plans.update', compact('plan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PlanCreateRequest $request, Plan $plan)
    {
        $data = $request->validated();

        foreach ($data as $key => $value) {
            $plan->$key = $value;
        }

        $plan->save();
        return view('admin.plans.show', compact('plan'))->with(['success' => 'Plan Updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plan $plan)
    {
        $plan->delete();
        return back()->with('success', 'Plan Removed!');
    }
}

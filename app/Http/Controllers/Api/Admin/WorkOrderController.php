<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkOrderController extends Controller
{
    public function index()
    {
        $workOrders = WorkOrder::with(['complaint', 'assignee:id,name'])->latest()->paginate(10);
        return response()->json($workOrders);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'complaint_id' => ['required', 'integer', 'exists:complaints,id'],
            'assignee_id' => ['required', 'integer', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $workOrder = WorkOrder::create($validator->validated());

        return response()->json([
            'message' => 'Work order created successfully',
            'work_order' => $workOrder->load(['complaint', 'assignee:id,name'])
        ], 201);
    }

    // ... method lainnya akan kita lengkapi nanti ...
}
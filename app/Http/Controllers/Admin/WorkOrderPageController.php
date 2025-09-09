<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Models\Pengaduan;
use App\Models\User;
use Illuminate\Http\Request;
// PERBAIKAN: Impor class Notification dan WorkOrderAssigned dari namespace yang benar
use App\Notifications\WorkOrderAssigned;
use Illuminate\Support\Facades\Notification;

class WorkOrderPageController extends Controller
{
    public function index()
    {
        $workOrders = WorkOrder::with(['pengaduan', 'assignee'])->latest()->paginate(10);
        return view('admin.penugasan.index', compact('workOrders'));
    }

    public function create()
    {
        $pengaduans = Pengaduan::orderBy('judul')->get();
        $assignees = User::role('Operator')->orderBy('name')->get();
        return view('admin.penugasan.create', compact('pengaduans', 'assignees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'complaint_id' => 'required|exists:pengaduans,id',
            'assignee_id' => 'required|exists:users,id',
            'status' => 'required|in:Pending,In Progress,Completed,Cancelled',
            'due_date' => 'nullable|date',
        ]);

        WorkOrder::create($request->all());

        return redirect()->route('admin.penugasan.index')
                         ->with('success', 'Work Order berhasil dibuat.');
    }

    public function show(WorkOrder $penugasan)
    {
        // Di Laravel, Anda bisa langsung menggunakan relasi dinamis
        $penugasan->load(['pengaduan', 'assignee']);
        return view('admin.penugasan.show', ['workOrder' => $penugasan]);
    }

    public function edit(WorkOrder $penugasan)
    {
        $pengaduans = Pengaduan::orderBy('judul')->get();
        $assignees = User::role('Operator')->orderBy('name')->get();
        return view('admin.penugasan.edit', compact('penugasan', 'pengaduans', 'assignees'));
    }

    public function update(Request $request, WorkOrder $penugasan)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'complaint_id' => 'required|exists:pengaduans,id',
            'assignee_id' => 'required|exists:users,id',
            'status' => 'required|in:Pending,In Progress,Completed,Cancelled',
            'due_date' => 'nullable|date',
        ]);

        $penugasan->update($request->all());

        return redirect()->route('admin.penugasan.index')
                         ->with('success', 'Work Order berhasil diperbarui.');
    }

    public function destroy(WorkOrder $penugasan)
    {
        $penugasan->delete();
        return redirect()->route('admin.penugasan.index')
                         ->with('success', 'Work Order berhasil dihapus.');
    }

    public function sendNotification(WorkOrder $penugasan)
    {
        $assignee = $penugasan->assignee;
        Notification::send($assignee, new WorkOrderAssigned($penugasan));

        // (Opsional) Ganti 'atasan@disperkim.test' dengan email atasan
        $manager = User::where('email', 'atasan@disperkim.test')->first();
        if ($manager) {
            Notification::send($manager, new WorkOrderAssigned($penugasan));
        }

        return back()->with('success', 'Notifikasi untuk "' . $penugasan->title . '" telah berhasil dikirim.');
    }
}
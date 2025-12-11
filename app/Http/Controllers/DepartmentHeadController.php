<?php

namespace App\Http\Controllers;

use App\Models\DepartmentHead;
use Illuminate\Http\Request;

class DepartmentHeadController extends Controller
{
    /**
     * Display a listing of department heads
     */
    public function index()
    {
        $departments = DepartmentHead::orderBy('department_name')->get();
        return view('settings.departments', compact('departments'));
    }

    /**
     * Store a new department head
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_name' => 'required|string|max:255|unique:department_heads',
            'head_name' => 'required|string|max:255',
            'head_email' => 'required|email|max:255',
            'cc_emails' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $department = DepartmentHead::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Department added successfully',
            'department' => $department
        ]);
    }

    /**
     * Update department head information
     */
    public function update(Request $request, DepartmentHead $departmentHead)
    {
        $validated = $request->validate([
            'head_name' => 'required|string|max:255',
            'head_email' => 'required|email|max:255',
            'cc_emails' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $departmentHead->update($validated);

        return response()->json([
            'message' => 'Department updated successfully',
            'department' => $departmentHead
        ]);
    }
}

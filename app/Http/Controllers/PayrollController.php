<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Salary;
use App\Models\Employee;
use App\Models\User;
use App\Services\PayrollCalculationService;
use App\Actions\Payroll\GeneratePayrollAction;
use App\Actions\Payroll\UpdatePayrollAction;
use App\Actions\Payroll\DeletePayrollAction;
use App\Http\Requests\Payroll\StorePayrollRequest;
use App\Http\Requests\Payroll\UpdatePayrollRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    // ─── Index ───────────────────────────────────────────────────────────────

    public function index()
    {
        return view('payroll.index');
    }

    // ─── Create form ─────────────────────────────────────────────────────────

    public function create()
    {
        $employees = User::with('employee')
            ->whereHas('employee')
            ->orderBy('first_name')
            ->get();

        return view('payroll.create', compact('employees'));
    }

    // ─── AJAX: calculate preview ─────────────────────────────────────────────

    public function preview(Request $request, PayrollCalculationService $service): JsonResponse
    {
        $request->validate([
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'start_date'  => ['required', 'date'],
            'end_date'    => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        try {
            $employee  = Employee::findOrFail($request->employee_id);
            $startDate = Carbon::parse($request->start_date);
            $endDate   = Carbon::parse($request->end_date);

            $calc = $service->calculate($employee, $startDate, $endDate);

            return response()->json([
                'success' => true,
                'data'    => [
                    'employee_name' => optional($employee->user)->full_name,
                    'salary_type'   => $calc['is_hourly'] ? 'Hourly' : 'Monthly',
                    'base_salary'   => $employee->current_salary,
                    'is_hourly'     => $calc['is_hourly'],
                    'total_hours'   => $calc['total_hours'],
                    'hourly_rate'   => $calc['hourly_rate'],
                    'working_days'  => $calc['working_days'],
                    'present_days'  => $calc['present_days'],
                    'leave_days'    => $calc['leave_days'],
                    'per_day_salary'=> $calc['per_day_salary'],
                    'earned_salary' => $calc['earned_salary'],
                    'final_salary'  => $calc['final_salary'],
                    'breakdown'     => $calc['_breakdown'],
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // ─── Store ────────────────────────────────────────────────────────────────

    public function store(StorePayrollRequest $request, GeneratePayrollAction $action)
    {
        try {
            $salary = $action->handle($request->validated());

            return redirect()
                ->route('payroll.show', $salary)
                ->with('success', 'Payroll generated successfully.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    // ─── Show / payslip ──────────────────────────────────────────────────────

    public function show(Salary $salary)
    {
        // Scope: own payroll OR global view permission
        if (! has_permission('payroll.view') && has_permission('payroll.view.own')) {
            $employeeId = auth_user()->employee?->id;
            abort_if($salary->employee_id !== $employeeId, 403);
        }

        $salary->load(['employee.user', 'employee.department', 'employee.designation', 'processedBy']);

        return view('payroll.show', compact('salary'));
    }

    // ─── Edit ─────────────────────────────────────────────────────────────────

    public function edit(Salary $salary)
    {
        $salary->load(['employee.user']);

        return view('payroll.edit', compact('salary'));
    }

    // ─── Update ──────────────────────────────────────────────────────────────

    public function update(UpdatePayrollRequest $request, Salary $salary, UpdatePayrollAction $action)
    {
        try {
            $action->handle($salary, $request->validated());

            return redirect()
                ->route('payroll.show', $salary)
                ->with('success', 'Payroll updated successfully.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    // ─── Destroy ─────────────────────────────────────────────────────────────

    public function destroy(Salary $salary, DeletePayrollAction $action): JsonResponse
    {
        try {
            $action->handle($salary);

            return response()->json([
                'success' => true,
                'message' => 'Payroll record deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // ─── Mark as Paid ────────────────────────────────────────────────────────

    public function markPaid(Salary $salary, UpdatePayrollAction $action): JsonResponse
    {
        try {
            $action->handle($salary, ['status' => 'paid']);

            return response()->json([
                'success' => true,
                'message' => 'Payroll marked as paid.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

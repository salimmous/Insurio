<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Landlord\PlatformExpense;
use App\Models\Landlord\PlatformActivityLog;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = PlatformExpense::orderBy('expense_date', 'desc')->get();

        // 1. Expected Monthly Revenue: Sum of rent_amount from active/trial tenants
        $activeTenants = Tenant::where('status', '!=', 'suspended')->get();
        
        // Filter out expired ones to be precise
        $monthlyRevenue = 0;
        foreach ($activeTenants as $t) {
            if (!$t->isExpired()) {
                $monthlyRevenue += (float) ($t->rent_amount ?? 0);
            }
        }

        // 2. Expenses this month
        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();

        $monthlyExpenses = PlatformExpense::whereBetween('expense_date', [$startOfMonth, $endOfMonth])->sum('amount');

        // 3. Net income this month
        $netIncome = $monthlyRevenue - $monthlyExpenses;

        // 4. All time expenses
        $totalExpensesAllTime = PlatformExpense::sum('amount');

        return view('platform.expenses.index', compact(
            'expenses',
            'monthlyRevenue',
            'monthlyExpenses',
            'netIncome',
            'totalExpensesAllTime'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'expense_date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ]);

        $expense = PlatformExpense::create($validated);

        // Log activity
        PlatformActivityLog::create([
            'platform_admin_id' => Auth::guard('platform')->id(),
            'action' => 'create_expense',
            'description' => "Enregistrement d'une charge: {$validated['title']} ({$validated['amount']} DH)",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('platform.expenses.index')->with('success', 'Charge enregistrée avec succès.');
    }

    public function destroy(Request $request, $id)
    {
        $expense = PlatformExpense::findOrFail($id);
        $title = $expense->title;
        $amount = $expense->amount;

        $expense->delete();

        // Log activity
        PlatformActivityLog::create([
            'platform_admin_id' => Auth::guard('platform')->id(),
            'action' => 'delete_expense',
            'description' => "Suppression d'une charge: {$title} ({$amount} DH)",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('platform.expenses.index')->with('success', 'Charge supprimée avec succès.');
    }
}

<?php

namespace App\Http\Controllers\Dashboard\Property;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\BuildingExpense;
use Illuminate\Http\Request;

class BuildingExpenseController extends Controller
{
    /**
     * Display building expenses.
     */
    public function index(Request $request)
    {
        $buildings = Building::query()
            ->orderBy('name')
            ->get();

        $query = BuildingExpense::query()
            ->with('building');

        /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    */

        $this->applyFilters($query, $request);


        /*
    |--------------------------------------------------------------------------
    | Expenses
    |--------------------------------------------------------------------------
    */

        $expenses = $query
            ->latest('expense_date')
            ->paginate(15)
            ->withQueryString();


        /*
    |--------------------------------------------------------------------------
    | Summary
    |--------------------------------------------------------------------------
    */

        $totalExpenses = (clone $query)
            ->sum('amount');

        $expenseEntries = (clone $query)
            ->count();


        /*
    |--------------------------------------------------------------------------
    | This Month
    |--------------------------------------------------------------------------
    */

        $thisMonthQuery = clone $query;

        $thisMonthQuery
            ->whereMonth(
                'expense_date',
                now()->month
            )
            ->whereYear(
                'expense_date',
                now()->year
            );

        $thisMonth = $thisMonthQuery
            ->sum('amount');


        /*
    |--------------------------------------------------------------------------
    | AJAX Response
    |--------------------------------------------------------------------------
    */

        if ($request->ajax()) {

            return response()->json([
                'success' => true,

                'html' => view(
                    'dashboard.property.buildings-expense.partials.table',
                    compact('expenses')
                )->render(),

                'summary' => [
                    'total_expenses' => number_format(
                        $totalExpenses,
                        2
                    ),

                    'this_month' => number_format(
                        $thisMonth,
                        2
                    ),

                    'expense_entries' => $expenseEntries,
                ],
            ]);
        }


        /*
    |--------------------------------------------------------------------------
    | Normal Page
    |--------------------------------------------------------------------------
    */

        return view(
            'dashboard.property.buildings-expense.index',
            compact(
                'buildings',
                'expenses',
                'totalExpenses',
                'thisMonth',
                'expenseEntries'
            )
        );
    }


    /**
     * Show create form.
     */
    public function create()
    {
        $buildings = Building::query()
            ->orderBy('name')
            ->get();

        return view(
            'dashboard.property.buildings-expense.form',
            compact('buildings')
        );
    }


    /**
     * Store building expense.
     */
    public function store(Request $request)
    {
        $validated = $this->validateExpense($request);

        $expense = BuildingExpense::create(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | AJAX Response
        |--------------------------------------------------------------------------
        */

        if ($request->ajax()) {

            return response()->json([
                'success' => true,

                'message' =>
                'Building expense added successfully.',

                'expense' =>
                $expense->load('building'),
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Normal Response
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'dashboard.property.building-expenses'
            )
            ->with(
                'success',
                'Building expense added successfully.'
            );
    }


    /**
     * Show edit form.
     */
    public function edit(
        BuildingExpense $buildingExpense
    ) {
        $buildings = Building::query()
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Load Building
        |--------------------------------------------------------------------------
        */

        $buildingExpense->load('building');


        return view(
            'dashboard.property.buildings-expense.form',
            compact(
                'buildings',
                'buildingExpense'
            )
        );
    }


    /**
     * Update building expense.
     */
    public function update(
        Request $request,
        BuildingExpense $buildingExpense
    ) {
        $validated = $this->validateExpense($request);

        $buildingExpense->update(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | AJAX Response
        |--------------------------------------------------------------------------
        */

        if ($request->ajax()) {

            return response()->json([
                'success' => true,

                'message' =>
                'Building expense updated successfully.',

                'expense' =>
                $buildingExpense
                    ->fresh()
                    ->load('building'),
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Normal Response
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'dashboard.property.building-expenses'
            )
            ->with(
                'success',
                'Building expense updated successfully.'
            );
    }


    /**
     * Delete building expense.
     */
    public function destroy(
        Request $request,
        BuildingExpense $buildingExpense
    ) {
        $buildingExpense->delete();


        /*
        |--------------------------------------------------------------------------
        | AJAX Response
        |--------------------------------------------------------------------------
        */

        if ($request->ajax()) {

            return response()->json([
                'success' => true,

                'message' =>
                'Building expense deleted successfully.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Normal Response
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'dashboard.property.building-expenses'
            )
            ->with(
                'success',
                'Building expense deleted successfully.'
            );
    }


    /**
     * Validate building expense.
     */
    private function validateExpense(
        Request $request
    ): array {
        return $request->validate([

            'building_id' => [
                'required',
                'exists:buildings,id',
            ],

            'expense_date' => [
                'required',
                'date',
            ],

            'category' => [
                'required',
                'string',
                'max:100',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'description' => [
                'nullable',
                'string',
            ],

        ]);
    }


    /**
     * Apply filters.
     */
    private function applyFilters(
        $query,
        Request $request
    ): void {

        /*
    |--------------------------------------------------------------------------
    | Building
    |--------------------------------------------------------------------------
    */

        if ($request->filled('building')) {

            $query->where(
                'building_id',
                $request->building
            );
        }


        /*
    |--------------------------------------------------------------------------
    | From Date
    |--------------------------------------------------------------------------
    */

        if ($request->filled('from_date')) {

            $query->whereDate(
                'expense_date',
                '>=',
                $request->from_date
            );
        }


        /*
    |--------------------------------------------------------------------------
    | To Date
    |--------------------------------------------------------------------------
    */

        if ($request->filled('to_date')) {

            $query->whereDate(
                'expense_date',
                '<=',
                $request->to_date
            );
        }
    }
}
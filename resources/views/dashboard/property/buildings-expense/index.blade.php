@extends('dashboard.base')

@section('title', 'Building Expenses')

@section('content')

    <div class="space-y-6">

        {{-- =========================================================
            Header
        ========================================================== --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            <div>

                <h1 class="text-2xl font-bold text-zinc-800">
                    Building Expenses
                </h1>

                <p class="text-sm text-zinc-500 mt-1">
                    Manage and track expenses for your buildings.
                </p>

            </div>


            <a
                href="{{ route('dashboard.property.building-expenses.create') }}"
                class="inline-flex items-center justify-center gap-2
                       px-4 py-2.5 rounded-xl
                       bg-amber-500 text-white
                       text-sm font-semibold
                       hover:bg-amber-600 transition">

                <i class="fa-solid fa-plus"></i>

                Add Expense

            </a>

        </div>


        {{-- =========================================================
            Summary Cards
        ========================================================== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">


            {{-- Total Expenses --}}
            <div class="bg-white border border-zinc-200 rounded-2xl p-5">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-zinc-500">
                            Total Expenses
                        </p>

                        <h3
                            id="total-expenses"
                            class="text-2xl font-bold text-zinc-800 mt-1">

                            ₹{{ number_format($totalExpenses ?? 0, 2) }}

                        </h3>

                    </div>


                    <div
                        class="w-11 h-11 rounded-xl
                               bg-red-50 text-red-600
                               flex items-center justify-center">

                        <i class="fa-solid fa-money-bill-wave text-lg"></i>

                    </div>

                </div>

            </div>


            {{-- This Month --}}
            <div class="bg-white border border-zinc-200 rounded-2xl p-5">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-zinc-500">
                            This Month
                        </p>

                        <h3
                            id="this-month"
                            class="text-2xl font-bold text-zinc-800 mt-1">

                            ₹{{ number_format($thisMonth ?? 0, 2) }}

                        </h3>

                    </div>


                    <div
                        class="w-11 h-11 rounded-xl
                               bg-blue-50 text-blue-600
                               flex items-center justify-center">

                        <i class="fa-solid fa-calendar-days text-lg"></i>

                    </div>

                </div>

            </div>


            {{-- Buildings --}}
            <div class="bg-white border border-zinc-200 rounded-2xl p-5">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-zinc-500">
                            Buildings
                        </p>

                        <h3
                            class="text-2xl font-bold text-zinc-800 mt-1">

                            {{ $buildings->count() ?? 0 }}

                        </h3>

                    </div>


                    <div
                        class="w-11 h-11 rounded-xl
                               bg-blue-50 text-blue-600
                               flex items-center justify-center">

                        <i class="fa-solid fa-building text-lg"></i>

                    </div>

                </div>

            </div>


            {{-- Expense Entries --}}
            <div class="bg-white border border-zinc-200 rounded-2xl p-5">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-zinc-500">
                            Expense Entries
                        </p>

                        <h3
                            id="expense-entries"
                            class="text-2xl font-bold text-zinc-800 mt-1">

                            {{ $expenseEntries ?? 0 }}

                        </h3>

                    </div>


                    <div
                        class="w-11 h-11 rounded-xl
                               bg-amber-50 text-amber-600
                               flex items-center justify-center">

                        <i class="fa-solid fa-receipt text-lg"></i>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
            Filters
        ========================================================== --}}
        <div class="bg-white border border-zinc-200 rounded-2xl p-5">

            <div class="flex items-center gap-2 mb-4">

                <i class="fa-solid fa-filter text-zinc-500"></i>

                <h2 class="font-semibold text-zinc-800">
                    Filter Expenses
                </h2>

            </div>


            <form
                method="GET"
                action="{{ route('dashboard.property.building-expenses') }}"
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4"
            >

                {{-- =================================================
                    Building
                ================================================== --}}
                <div>

                    <label
                        for="building"
                        class="block text-sm font-medium text-zinc-700 mb-1.5">

                        Building

                    </label>


                    <select
                        id="building"
                        name="building"
                        class="w-full rounded-xl border border-zinc-300
                               px-3 py-2.5
                               bg-white
                               text-sm text-zinc-700
                               focus:border-amber-500
                               focus:ring-amber-500">

                        <option value="">
                            All Buildings
                        </option>


                        @foreach ($buildings ?? [] as $building)

                            <option
                                value="{{ $building->id }}"
                                {{ request('building') == $building->id ? 'selected' : '' }}>

                                {{ $building->name }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- =================================================
                    From Date
                ================================================== --}}
                <div>

                    <label
                        for="from_date"
                        class="block text-sm font-medium text-zinc-700 mb-1.5">

                        From Date

                    </label>


                    <input
                        id="from_date"
                        type="date"
                        name="from_date"
                        value="{{ request('from_date') }}"
                        class="w-full rounded-xl border border-zinc-300
                               px-3 py-2.5
                               bg-white
                               text-sm text-zinc-700
                               focus:border-amber-500
                               focus:ring-amber-500">

                </div>


                {{-- =================================================
                    To Date
                ================================================== --}}
                <div>

                    <label
                        for="to_date"
                        class="block text-sm font-medium text-zinc-700 mb-1.5">

                        To Date

                    </label>


                    <input
                        id="to_date"
                        type="date"
                        name="to_date"
                        value="{{ request('to_date') }}"
                        class="w-full rounded-xl border border-zinc-300
                               px-3 py-2.5
                               bg-white
                               text-sm text-zinc-700
                               focus:border-amber-500
                               focus:ring-amber-500">

                </div>


                {{-- =================================================
                    Buttons
                ================================================== --}}
                <div
                    class="flex items-end justify-start
                           lg:justify-end gap-2">

                    <a
                        href="{{ route('dashboard.property.building-expenses') }}"
                        class="inline-flex items-center justify-center
                               gap-1.5
                               px-4 py-2.5 rounded-xl
                               border border-zinc-300
                               text-sm font-medium text-zinc-700
                               hover:bg-zinc-50 transition">

                        <i class="fa-solid fa-rotate-left"></i>

                        Reset

                    </a>


                    <button
                        type="submit"
                        class="inline-flex items-center justify-center
                               gap-1.5
                               px-4 py-2.5 rounded-xl
                               bg-zinc-800 text-white
                               text-sm font-semibold
                               hover:bg-zinc-900 transition">

                        <i class="fa-solid fa-magnifying-glass"></i>

                        Apply

                    </button>

                </div>

            </form>

        </div>


        {{-- =========================================================
            Expenses Table
        ========================================================== --}}
        <div
            class="bg-white border border-zinc-200
                   rounded-2xl overflow-hidden">


            {{-- Table Header --}}
            <div
                class="px-5 py-4 border-b border-zinc-200
                       flex flex-col sm:flex-row
                       sm:items-center sm:justify-between gap-3">

                <div>

                    <h2 class="font-semibold text-zinc-800">
                        Expense Records
                    </h2>

                    <p class="text-xs text-zinc-500 mt-1">
                        List of building expenses
                    </p>

                </div>


                @if (request('building') || request('from_date') || request('to_date'))

                    <div
                        class="inline-flex items-center gap-2
                               px-3 py-1.5 rounded-lg
                               bg-amber-50 text-amber-700
                               text-xs font-medium">

                        <i class="fa-solid fa-filter"></i>

                        Filters Applied

                    </div>

                @endif

            </div>


            {{-- =====================================================
                Table
            ====================================================== --}}
            <div
                id="expense-table"
                class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead
                        class="bg-zinc-50 border-b border-zinc-200">

                        <tr>

                            <th
                                class="px-5 py-3 text-left
                                       font-semibold text-zinc-600">

                                Date

                            </th>


                            <th
                                class="px-5 py-3 text-left
                                       font-semibold text-zinc-600">

                                Building

                            </th>


                            <th
                                class="px-5 py-3 text-left
                                       font-semibold text-zinc-600">

                                Category

                            </th>


                            <th
                                class="px-5 py-3 text-left
                                       font-semibold text-zinc-600">

                                Description

                            </th>


                            <th
                                class="px-5 py-3 text-right
                                       font-semibold text-zinc-600">

                                Amount

                            </th>


                            <th
                                class="px-5 py-3 text-center
                                       font-semibold text-zinc-600">

                                Actions

                            </th>

                        </tr>

                    </thead>


                    <tbody
                        class="divide-y divide-zinc-100">

                        @forelse ($expenses ?? [] as $expense)

                            <tr
                                class="hover:bg-zinc-50 transition">


                                {{-- Date --}}
                                <td
                                    class="px-5 py-3
                                           text-zinc-700">

                                    {{ $expense->expense_date?->format('d M Y') ?? '-' }}

                                </td>


                                {{-- Building --}}
                                <td
                                    class="px-5 py-3
                                           font-medium text-zinc-800">

                                    <div class="flex items-center gap-2">

                                        <div
                                            class="w-8 h-8 rounded-lg
                                                   bg-blue-50 text-blue-600
                                                   flex items-center justify-center">

                                            <i
                                                class="fa-solid fa-building
                                                       text-xs">
                                            </i>

                                        </div>

                                        <span>
                                            {{ $expense->building->name ?? '-' }}
                                        </span>

                                    </div>

                                </td>


                                {{-- Category --}}
                                <td class="px-5 py-3">

                                    <span
                                        class="inline-flex
                                               px-2.5 py-1
                                               rounded-lg
                                               bg-amber-50
                                               text-amber-700
                                               text-xs font-medium">

                                        {{ ucfirst($expense->category ?? '-') }}

                                    </span>

                                </td>


                                {{-- Description --}}
                                <td
                                    class="px-5 py-3
                                           text-zinc-600">

                                    {{ $expense->description ?? '-' }}

                                </td>


                                {{-- Amount --}}
                                <td
                                    class="px-5 py-3
                                           text-right
                                           font-semibold
                                           text-zinc-800">

                                    ₹{{ number_format($expense->amount ?? 0, 2) }}

                                </td>


                                {{-- Actions --}}
                                <td class="px-5 py-3">

                                    <div
                                        class="flex items-center
                                               justify-center gap-2">


                                        {{-- Edit --}}
                                        <a
                                            href="{{ route(
                                                'dashboard.property.building-expenses.edit',
                                                $expense
                                            ) }}"
                                            class="w-8 h-8 rounded-lg
                                                   bg-blue-50 text-blue-600
                                                   flex items-center
                                                   justify-center
                                                   hover:bg-blue-100
                                                   transition"
                                            title="Edit">

                                            <i
                                                class="fa-solid
                                                       fa-pen-to-square">
                                            </i>

                                        </a>


                                        {{-- Delete --}}
                                        <form
                                            action="{{ route(
                                                'dashboard.property.building-expenses.destroy',
                                                $expense
                                            ) }}"
                                            method="POST"
                                            class="delete-expense-form">

                                            @csrf

                                            @method('DELETE')


                                            <button
                                                type="submit"
                                                class="w-8 h-8 rounded-lg
                                                       bg-red-50 text-red-600
                                                       flex items-center
                                                       justify-center
                                                       hover:bg-red-100
                                                       transition"
                                                title="Delete">

                                                <i
                                                    class="fa-solid fa-trash">
                                                </i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="px-5 py-12 text-center">

                                    <div
                                        class="flex flex-col
                                               items-center">

                                        <div
                                            class="w-14 h-14 rounded-2xl
                                                   bg-zinc-100
                                                   flex items-center
                                                   justify-center mb-3">

                                            <i
                                                class="fa-solid fa-receipt
                                                       text-xl text-zinc-400">
                                            </i>

                                        </div>


                                        <h3
                                            class="font-semibold
                                                   text-zinc-700">

                                            No expenses found

                                        </h3>


                                        <p
                                            class="text-sm text-zinc-500
                                                   mt-1">

                                            @if (
                                                request('building') ||
                                                request('from_date') ||
                                                request('to_date')
                                            )

                                                No expenses match
                                                the selected filters.

                                            @else

                                                Start by adding a
                                                building expense.

                                            @endif

                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- =====================================================
                Pagination
            ====================================================== --}}
            @if (
                isset($expenses) &&
                method_exists($expenses, 'links')
            )

                <div
                    class="px-5 py-4
                           border-t border-zinc-200">

                    {{ $expenses->links() }}

                </div>

            @endif

        </div>

    </div>

@endsection


@push('scripts')

    <script
        src="{{ asset(
            'js/dashboard/property/buildings-expense/building-expense.js'
        ) }}">
    </script>

@endpush
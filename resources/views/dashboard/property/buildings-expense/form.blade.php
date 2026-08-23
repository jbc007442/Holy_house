@extends('dashboard.base')

@section('title', isset($buildingExpense) ? 'Edit Building Expense' : 'Add Building Expense')

@section('content')

    @php
        $isEdit = isset($buildingExpense);
    @endphp

    <div class="max-w-3xl mx-auto space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            <div>
                <h1 class="text-2xl font-bold text-zinc-800">
                    {{ $isEdit ? 'Edit Building Expense' : 'Add Building Expense' }}
                </h1>

                <p class="text-sm text-zinc-500 mt-1">
                    {{ $isEdit ? 'Update the building expense details.' : 'Add a new expense for a building.' }}
                </p>
            </div>

            <a href="{{ route('dashboard.property.building-expenses') }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl
                       border border-zinc-300 text-sm font-medium text-zinc-700
                       hover:bg-zinc-50 transition">

                <i class="fa-solid fa-arrow-left"></i>

                Back

            </a>

        </div>


        {{-- Validation Errors --}}
        @if ($errors->any())

            <div class="rounded-xl border border-red-200 bg-red-50 p-4">

                <div class="flex items-start gap-3">

                    <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5"></i>

                    <div>

                        <h3 class="text-sm font-semibold text-red-700">
                            Please correct the following errors:
                        </h3>

                        <ul class="mt-2 text-sm text-red-600 list-disc list-inside space-y-1">

                            @foreach ($errors->all() as $error)
                                <li>
                                    {{ $error }}
                                </li>
                            @endforeach

                        </ul>

                    </div>

                </div>

            </div>

        @endif


        {{-- Form Card --}}
        <div class="bg-white border border-zinc-200 rounded-2xl shadow-sm">

            <div class="p-6">

                <form id="building-expense-form"
                    action="{{ $isEdit
                        ? route('dashboard.property.building-expenses.update', $buildingExpense)
                        : route('dashboard.property.building-expenses.store') }}"
                    method="POST" data-redirect-url="{{ route('dashboard.property.building-expenses') }}"
                    class="space-y-6">

                    @csrf

                    @if ($isEdit)
                        @method('PUT')
                    @endif


                    {{-- Building --}}
                    <div class="relative">

                        <label for="building_search" class="block text-sm font-medium text-zinc-700 mb-1.5">

                            Building

                            <span class="text-red-500">*</span>

                        </label>

                        {{-- Search/Input --}}
                        <input id="building_search" type="text"
                            value="{{ old('building_name', $buildingExpense->building->name ?? '') }}"
                            placeholder="Search building..." autocomplete="off" required
                            class="w-full rounded-xl border border-zinc-300
               px-3 py-2.5 bg-white
               text-sm text-zinc-700
               placeholder:text-zinc-400
               focus:border-amber-500
               focus:ring-amber-500
               @error('building_id') border-red-400 @enderror">

                        {{-- Actual ID submitted to controller --}}
                        <input type="hidden" id="building_id" name="building_id"
                            value="{{ old('building_id', $buildingExpense->building_id ?? '') }}">

                        {{-- Search Results --}}
                        <div id="building-results"
                            class="hidden absolute z-50 left-0 right-0 mt-1
               bg-white border border-zinc-200
               rounded-xl shadow-lg overflow-hidden">

                            @foreach ($buildings as $building)
                                <button type="button"
                                    class="building-option w-full text-left px-4 py-3
                       hover:bg-zinc-50 transition
                       border-b border-zinc-100 last:border-b-0"
                                    data-id="{{ $building->id }}" data-name="{{ $building->name }}">

                                    <div class="flex items-center gap-3">

                                        <div
                                            class="w-9 h-9 rounded-lg bg-blue-50
                               text-blue-600 flex items-center justify-center">

                                            <i class="fa-solid fa-building"></i>

                                        </div>

                                        <div>

                                            <p class="text-sm font-medium text-zinc-800">
                                                {{ $building->name }}
                                            </p>

                                            @if (!empty($building->address))
                                                <p class="text-xs text-zinc-500">
                                                    {{ $building->address }}
                                                </p>
                                            @endif

                                        </div>

                                    </div>

                                </button>
                            @endforeach

                        </div>

                        @error('building_id')
                            <p class="text-xs text-red-500 mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Date + Category --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- Expense Date --}}
                        <div>

                            <label for="expense_date" class="block text-sm font-medium text-zinc-700 mb-1.5">

                                Expense Date

                                <span class="text-red-500">*</span>

                            </label>

                            <input id="expense_date" type="date" name="expense_date"
                                value="{{ old(
                                    'expense_date',
                                    isset($buildingExpense) ? $buildingExpense->expense_date?->format('Y-m-d') : now()->format('Y-m-d'),
                                ) }}"
                                required
                                class="w-full rounded-xl border border-zinc-300
                                       px-3 py-2.5 bg-white
                                       text-sm text-zinc-700
                                       focus:border-amber-500
                                       focus:ring-amber-500
                                       @error('expense_date') border-red-400 @enderror">

                            @error('expense_date')
                                <p class="text-xs text-red-500 mt-1">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Category --}}
                        <div>

                            <label for="category" class="block text-sm font-medium text-zinc-700 mb-1.5">

                                Expense Category

                                <span class="text-red-500">*</span>

                            </label>

                            <input id="category" type="text" name="category"
                                value="{{ old('category', $buildingExpense->category ?? '') }}"
                                placeholder="e.g. Electricity, Maintenance" required autocomplete="off"
                                class="w-full rounded-xl border border-zinc-300
                                       px-3 py-2.5 bg-white
                                       text-sm text-zinc-700
                                       placeholder:text-zinc-400
                                       focus:border-amber-500
                                       focus:ring-amber-500
                                       @error('category') border-red-400 @enderror">

                            @error('category')
                                <p class="text-xs text-red-500 mt-1">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>


                    {{-- Amount --}}
                    <div>

                        <label for="amount" class="block text-sm font-medium text-zinc-700 mb-1.5">

                            Amount

                            <span class="text-red-500">*</span>

                        </label>

                        <div class="relative">

                            <div
                                class="absolute inset-y-0 left-0 flex items-center pl-3
                   pointer-events-none">

                                <i class="fa-solid fa-indian-rupee-sign text-zinc-500 text-sm"></i>

                            </div>

                            <input id="amount" type="number" name="amount" step="0.01" min="0"
                                value="{{ old('amount', $buildingExpense->amount ?? '') }}" placeholder="0.00" required
                                class="w-full rounded-xl border border-zinc-300
                   pl-9 pr-3 py-2.5
                   bg-white text-sm text-zinc-700
                   placeholder:text-zinc-400
                   focus:border-amber-500
                   focus:ring-amber-500
                   @error('amount') border-red-400 @enderror">

                        </div>

                        @error('amount')
                            <p class="text-xs text-red-500 mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Description --}}
                    <div>

                        <label for="description" class="block text-sm font-medium text-zinc-700 mb-1.5">

                            Description

                        </label>

                        <textarea id="description" name="description" rows="4" placeholder="Enter expense details..."
                            class="w-full rounded-xl border border-zinc-300
                                   px-3 py-2.5 bg-white
                                   text-sm text-zinc-700
                                   placeholder:text-zinc-400
                                   focus:border-amber-500
                                   focus:ring-amber-500
                                   @error('description') border-red-400 @enderror">{{ old('description', $buildingExpense->description ?? '') }}</textarea>

                        @error('description')
                            <p class="text-xs text-red-500 mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Buttons --}}
                    <div
                        class="flex flex-col-reverse sm:flex-row
                               items-stretch sm:items-center
                               justify-end gap-3
                               pt-5 border-t border-zinc-200">

                        <a href="{{ route('dashboard.property.building-expenses') }}"
                            class="inline-flex items-center justify-center
                                   px-5 py-2.5 rounded-xl
                                   border border-zinc-300
                                   text-sm font-medium text-zinc-700
                                   hover:bg-zinc-50 transition">

                            Cancel

                        </a>

                        <button id="submit-expense-btn" type="submit"
                            class="inline-flex items-center justify-center
                                   px-5 py-2.5 rounded-xl
                                   bg-amber-500 text-white
                                   text-sm font-semibold
                                   hover:bg-amber-600
                                   disabled:opacity-60
                                   disabled:cursor-not-allowed
                                   transition">

                            <i id="submit-expense-icon" class="fa-solid {{ $isEdit ? 'fa-save' : 'fa-plus' }} mr-2">
                            </i>

                            <span id="submit-expense-text">
                                {{ $isEdit ? 'Update Expense' : 'Add Expense' }}
                            </span>

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection


@push('scripts')
    <script src="{{ asset('js/dashboard/property/buildings-expense/building-expense-form.js') }}"></script>
@endpush

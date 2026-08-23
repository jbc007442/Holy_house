<div class="overflow-x-auto">

    <table class="w-full text-sm">

        <thead class="bg-zinc-50 border-b border-zinc-200">

            <tr>

                <th class="px-5 py-3 text-left font-semibold text-zinc-600">
                    Date
                </th>

                <th class="px-5 py-3 text-left font-semibold text-zinc-600">
                    Building
                </th>

                <th class="px-5 py-3 text-left font-semibold text-zinc-600">
                    Category
                </th>

                <th class="px-5 py-3 text-left font-semibold text-zinc-600">
                    Description
                </th>

                <th class="px-5 py-3 text-right font-semibold text-zinc-600">
                    Amount
                </th>

                <th class="px-5 py-3 text-center font-semibold text-zinc-600">
                    Actions
                </th>

            </tr>

        </thead>


        <tbody class="divide-y divide-zinc-100">

            @forelse ($expenses as $expense)

                <tr class="hover:bg-zinc-50 transition">

                    <td class="px-5 py-3 text-zinc-700">
                        {{ $expense->expense_date?->format('d M Y') ?? '-' }}
                    </td>


                    <td class="px-5 py-3 font-medium text-zinc-800">
                        {{ $expense->building->name ?? '-' }}
                    </td>


                    <td class="px-5 py-3">

                        <span
                            class="inline-flex px-2.5 py-1 rounded-lg
                                   bg-amber-50 text-amber-700
                                   text-xs font-medium">

                            {{ ucfirst($expense->category ?? '-') }}

                        </span>

                    </td>


                    <td class="px-5 py-3 text-zinc-600">
                        {{ $expense->description ?? '-' }}
                    </td>


                    <td class="px-5 py-3 text-right font-semibold text-zinc-800">
                        ₹{{ number_format($expense->amount ?? 0, 2) }}
                    </td>


                    <td class="px-5 py-3">

                        <div class="flex items-center justify-center gap-2">

                            {{-- Edit --}}
                            <a
                                href="{{ route('dashboard.property.building-expenses.edit', $expense) }}"
                                class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600
                                       flex items-center justify-center
                                       hover:bg-blue-100 transition"
                                title="Edit">

                                <i class="fa-solid fa-pen-to-square"></i>

                            </a>


                            {{-- Delete --}}
                            <button
                                type="button"
                                class="delete-expense w-8 h-8 rounded-lg
                                       bg-red-50 text-red-600
                                       flex items-center justify-center
                                       hover:bg-red-100 transition"
                                data-url="{{ route('dashboard.property.building-expenses.destroy', $expense) }}"
                                title="Delete">

                                <i class="fa-solid fa-trash"></i>

                            </button>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="6" class="px-5 py-12 text-center">

                        <div class="flex flex-col items-center">

                            <div
                                class="w-14 h-14 rounded-2xl bg-zinc-100
                                       flex items-center justify-center mb-3">

                                <i
                                    class="fa-solid fa-receipt
                                           text-xl text-zinc-400">
                                </i>

                            </div>

                            <h3 class="font-semibold text-zinc-700">
                                No expenses found
                            </h3>

                            <p class="text-sm text-zinc-500 mt-1">
                                Start by adding a building expense.
                            </p>

                        </div>

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</div>


{{-- Pagination --}}
@if ($expenses->hasPages())

    <div class="px-5 py-4 border-t border-zinc-200">

        {{ $expenses->links() }}

    </div>

@endif
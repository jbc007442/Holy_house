@forelse($users as $user)
    <tr class="border-b hover:bg-zinc-50">

        <td class="px-4 py-3">
            {{ $user->name }}
        </td>

        <td class="px-4 py-3">
            {{ $user->email }}
        </td>

        <td class="px-4 py-3 text-center">
            {{ ucfirst($user->role) }}
        </td>

        <td class="px-4 py-3 text-center">

            @if ($user->status == 'active')
                <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">
                    Active
                </span>
            @else
                <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-700">
                    Inactive
                </span>
            @endif

        </td>

        <td class="px-4 py-3">

    <div class="flex items-center justify-center gap-2">

        <!-- View -->
        <a href="{{ route('dashboard.users.show', $user) }}"
            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition"
            title="View">
            <i class="fa-solid fa-eye text-sm"></i>
        </a>

        <!-- Edit -->
        <a href="{{ route('dashboard.users.edit', $user) }}"
            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition"
            title="Edit">
            <i class="fa-solid fa-pen-to-square text-sm"></i>
        </a>

        <!-- Active / Inactive -->
        <form action="{{ route('dashboard.users.toggle-status', $user) }}"
            method="POST"
            class="inline">

            @csrf
            @method('PATCH')

            @if($user->status == 'active')

                <button type="submit"
                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-50 text-green-600 hover:bg-green-100 transition"
                    title="Deactivate User">

                    <i class="fa-solid fa-toggle-on"></i>

                </button>

            @else

                <button type="submit"
                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-zinc-100 text-zinc-600 hover:bg-zinc-200 transition"
                    title="Activate User">

                    <i class="fa-solid fa-toggle-off"></i>

                </button>

            @endif

        </form>

        <!-- Delete -->
        <form action="{{ route('dashboard.users.destroy', $user) }}"
            method="POST"
            class="delete-form inline">

            @csrf
            @method('DELETE')

            <button type="submit"
                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition"
                title="Delete">

                <i class="fa-solid fa-trash text-sm"></i>

            </button>

        </form>

    </div>

</td>

    </tr>

@empty

    <tr>

        <td colspan="5" class="text-center py-8 text-zinc-500">
            No users found.
        </td>

    </tr>
@endforelse

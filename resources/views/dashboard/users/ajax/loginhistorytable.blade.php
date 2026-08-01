@forelse($histories as $history)

<tr class="border-b hover:bg-zinc-50">

    <td class="px-4 py-3">
        <div class="font-medium text-zinc-800">
            {{ $history->user->name }}
        </div>

        <div class="text-xs text-zinc-500">
            {{ $history->user->email }}
        </div>
    </td>

    <td class="px-4 py-3">
        {{ $history->ip_address ?? '-' }}
    </td>

    <td class="px-4 py-3">
        {{ $history->browser ?? '-' }}
    </td>

    <td class="px-4 py-3">
        {{ $history->platform ?? '-' }}
    </td>

    <td class="px-4 py-3 text-center">
        {{ $history->device ?? '-' }}
    </td>

    <td class="px-4 py-3 text-center">
        {{ optional($history->login_at)->format('d M Y h:i A') }}
    </td>

    <td class="px-4 py-3 text-center">
        {{ $history->logout_at ? $history->logout_at->format('d M Y h:i A') : '-' }}
    </td>

    <td class="px-4 py-3 text-center">

        @if($history->status === 'login')

            <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                Logged In
            </span>

        @else

            <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                Logged Out
            </span>

        @endif

    </td>

</tr>

@empty

<tr>

    <td colspan="8" class="py-8 text-center text-zinc-500">
        No login history found.
    </td>

</tr>

@endforelse
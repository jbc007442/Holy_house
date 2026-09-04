<?php

namespace App\Http\Controllers\Dashboard\Users;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Available user roles.
     */
    private const ROLES = [
        'superadmin',
        'admin',
        'receptionist',
        'housekeeping',
        'storemanager',
        'user',
    ];

    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $users = User::query();

        if ($request->filled('search')) {
            $users->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $users
            ->with('buildings')
            ->latest()
            ->get();

        if ($request->ajax()) {
            return view('dashboard.users.ajax.table', compact('users'));
        }

        return view('dashboard.users.index');
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $buildings = Building::query()
            ->orderBy('name')
            ->get();

        return view('dashboard.users.create', compact('buildings'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
            'role'     => ['required', Rule::in(self::ROLES)],
            'status'   => ['required', Rule::in(['active', 'inactive'])],

            'building_ids' => ['nullable', 'array'],
            'building_ids.*' => [
                'integer',
                'exists:buildings,id',
            ],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
            'status'   => $validated['status'],
        ]);

        /*
         * Assign buildings to the user.
         */
        $user->buildings()->sync(
            $validated['building_ids'] ?? []
        );

        return redirect()
            ->route('dashboard.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load('buildings');

        return view('dashboard.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $buildings = Building::query()
            ->orderBy('name')
            ->get();

        $user->load('buildings');

        return view(
            'dashboard.users.edit',
            compact('user', 'buildings')
        );
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'role'     => ['required', Rule::in(self::ROLES)],
            'status'   => ['required', Rule::in(['active', 'inactive'])],
            'password' => ['nullable', 'confirmed', 'min:8'],

            'building_ids' => ['nullable', 'array'],
            'building_ids.*' => [
                'integer',
                'exists:buildings,id',
            ],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->status = $validated['status'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        /*
         * Update building assignments.
         *
         * sync() will:
         * - add newly selected buildings
         * - remove unselected buildings
         * - keep existing selected buildings
         */
        $user->buildings()->sync(
            $validated['building_ids'] ?? []
        );

        return redirect()
            ->route('dashboard.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if (auth()->id() === $user->id) {
            return back()->with(
                'error',
                'You cannot delete your own account.'
            );
        }

        $user->delete();

        return redirect()
            ->route('dashboard.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Toggle user status.
     */
    public function toggleStatus(User $user)
    {
        $user->status = $user->status === 'active'
            ? 'inactive'
            : 'active';

        $user->save();

        return back()->with(
            'success',
            'User status updated successfully.'
        );
    }
}
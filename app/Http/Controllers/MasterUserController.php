<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MasterUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                    ->orWhere('email', 'ilike', "%{$search}%")
                    ->orWhere('nip', 'ilike', "%{$search}%");
            });
        }

        // Filter Role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('master.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => 'required|string|max:20|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'role' => 'required|in:admin,staff,viewer',
            'divisi' => 'nullable|string|max:255',
        ]);

        // Generate random password
        $password = Str::random(8);
        $validated['password'] = Hash::make($password);
        $validated['status'] = 'aktif';

        User::create($validated);

        return redirect()->route('master.users.index')
            ->with('success', "User berhasil ditambahkan. Password sementara: <strong>{$password}</strong> (Harap dicatat!)");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('master.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nip' => 'required|string|max:20|unique:users,nip,' . $user->id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,staff,viewer',
            'divisi' => 'nullable|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        // Restriction: Admin cannot change their own role or status to nonaktif
        if ($user->id === auth()->id()) {
            if ($validated['role'] !== $user->role) {
                return back()->with('error', 'Anda tidak dapat mengubah role Anda sendiri.');
            }
            if ($validated['status'] === 'nonaktif') {
                return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
            }
        }

        $user->update($validated);

        return redirect()->route('master.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Restriction: Admin cannot deactivate themselves via delete
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->update(['status' => 'nonaktif']);

        return redirect()->route('master.users.index')
            ->with('success', 'User berhasil dinonaktifkan.');
    }
}

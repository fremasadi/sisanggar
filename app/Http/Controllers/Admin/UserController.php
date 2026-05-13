<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
{
    $query = User::query();

    // 🔍 Filter berdasarkan role
    if ($request->filled('role')) {
        $query->where('role', $request->role);
    }

    // 🔍 Filter berdasarkan status aktif
    if ($request->filled('status_aktif')) {
        $query->where('status_aktif', $request->status_aktif);
    }

    // 🔍 Pencarian nama/email
    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('name', 'like', "%{$request->search}%")
              ->orWhere('email', 'like', "%{$request->search}%");
        });
    }

    $users = $query->latest()->paginate(10);

    return view('admin.users.index', compact('users'));
}


    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'no_hp' => 'nullable|string|max:20',
            'role' => 'required|in:admin,pelatih,peserta,pengunjung',
            'password' => 'required|string|min:6|confirmed',
            'status_aktif' => 'boolean'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['status_aktif'] = $request->boolean('status_aktif');

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email,' . $user->id,
            'no_hp' => 'nullable|string|max:20',
            'role' => 'required|in:admin,pelatih,peserta,pengunjung',
            'password' => 'nullable|string|min:6|confirmed',
            'status_aktif' => 'boolean'
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $validated['status_aktif'] = $request->boolean('status_aktif');

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}

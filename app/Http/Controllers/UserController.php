<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = Pengguna::latest()->paginate(10);
        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:pengguna,email',
            'password' => 'required|string|min:6|confirmed',
            'peran' => ['required', Rule::in(['ADMIN', 'KASIR', 'DAPUR'])],
        ]);

        Pengguna::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'kata_sandi' => Hash::make($validated['password']),
            'peran' => $validated['peran'],
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Pengguna::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Pengguna::findOrFail($id);
        
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => ['required', 'email', Rule::unique('pengguna')->ignore($user->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'peran' => ['required', Rule::in(['ADMIN', 'KASIR', 'DAPUR'])],
        ]);

        $dataToUpdate = [
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'peran' => $validated['peran'],
        ];

        if ($request->filled('password')) {
            $dataToUpdate['kata_sandi'] = Hash::make($validated['password']);
        }

        $user->update($dataToUpdate);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Pengguna::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}

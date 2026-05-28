<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kota;
use App\Models\Perdin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            abort(403);
        }

        $users = User::orderBy('name', 'asc')->get();
        $kotaList = Kota::orderBy('nama', 'asc')->get();
        $perdinList = Perdin::with(['user', 'kotaAsal', 'kotaTujuan'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.dashboard', compact('users', 'kotaList', 'perdinList'));
    }

    public function updateRole(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            abort(403);
        }

        $targetUser = User::findOrFail($id);

        $request->validate([
            'role' => ['required', 'in:PEGAWAI,DIVISI-SDM,ADMIN'],
        ]);

        if ($targetUser->id === $user->id) {
            return back()->with('error', 'Anda tidak dapat mengubah role Anda sendiri.');
        }

        $targetUser->role = $request->role;
        $targetUser->save();

        return back()->with('success', "Role untuk {$targetUser->name} berhasil diperbarui.");
    }

    public function storeUser(Request $request)
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:PEGAWAI,DIVISI-SDM,ADMIN'],
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return back()->with('success', "User {$request->name} berhasil ditambahkan.");
    }

    public function deleteUser($id)
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            abort(403);
        }

        $targetUser = User::findOrFail($id);
        if ($targetUser->id === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $targetUser->delete();
        return back()->with('success', "User {$targetUser->name} berhasil dihapus.");
    }

    public function storeKota(Request $request)
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'provinsi' => ['nullable', 'string', 'max:255'],
            'pulau' => ['nullable', 'string', 'max:255'],
            'is_overseas' => ['required', 'boolean'],
        ]);

        Kota::create($request->only(['nama', 'latitude', 'longitude', 'provinsi', 'pulau', 'is_overseas']));
        return back()->with('success', "Kota {$request->nama} berhasil ditambahkan.");
    }

    public function updateKota(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            abort(403);
        }

        $kota = Kota::findOrFail($id);

        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'provinsi' => ['nullable', 'string', 'max:255'],
            'pulau' => ['nullable', 'string', 'max:255'],
            'is_overseas' => ['required', 'boolean'],
        ]);

        $kota->update($request->only(['nama', 'latitude', 'longitude', 'provinsi', 'pulau', 'is_overseas']));
        return back()->with('success', "Kota {$kota->nama} berhasil diperbarui.");
    }

    public function deleteKota($id)
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            abort(403);
        }

        $kota = Kota::findOrFail($id);

        $perdinCount = Perdin::where('kota_asal_id', $id)->orWhere('kota_tujuan_id', $id)->count();
        if ($perdinCount > 0) {
            return back()->with('error', "Kota {$kota->nama} tidak dapat dihapus karena masih digunakan oleh {$perdinCount} data perdin.");
        }

        $kota->delete();
        return back()->with('success', "Kota {$kota->nama} berhasil dihapus.");
    }

    public function deletePerdin($id)
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            abort(403);
        }

        $perdin = Perdin::findOrFail($id);
        $perdin->delete();
        return back()->with('success', 'Data perdin berhasil dihapus.');
    }

    public function updatePerdinStatus(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'status' => ['required', 'in:PENDING,APPROVED,REJECTED'],
        ]);

        $perdin = Perdin::findOrFail($id);
        $perdin->status = $request->status;
        $perdin->save();

        return back()->with('success', 'Status perdin berhasil diperbarui.');
    }
}

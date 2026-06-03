<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Perdin;
use App\Models\Kota;

class PerdinController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        if ($user->isPegawai()) {
            $perdinList = Perdin::where('user_id', $user->id)
                ->with(['kotaAsal', 'kotaTujuan'])
                ->orderBy('created_at', 'desc')
                ->get();
            $kotaList = Kota::all();
            return view('pegawai.dashboard', compact('perdinList', 'kotaList'));
        }

        if ($user->isSdm()) {
            $pendingList = Perdin::where('status', 'PENDING')
                ->with(['user', 'kotaAsal', 'kotaTujuan'])
                ->orderBy('created_at', 'asc')
                ->get();
            $processedList = Perdin::whereIn('status', ['APPROVED', 'REJECTED'])
                ->with(['user', 'kotaAsal', 'kotaTujuan'])
                ->orderBy('updated_at', 'desc')
                ->get();
            return view('sdm.dashboard', compact('pendingList', 'processedList'));
        }

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        abort(403);
    }

    public function tambahForm()
    {
        return redirect()->route('dashboard');
    }

    public function tambahStore(Request $request)
    {
        $user = Auth::user();
        if (!$user->isPegawai()) {
            abort(403);
        }

        $request->validate([
            'kota_asal_id' => ['required', 'exists:kota,id'],
            'kota_tujuan_id' => ['required', 'exists:kota,id'],
            'tanggal_berangkat' => ['required', 'date'],
            'tanggal_pulang' => ['required', 'date', 'after_or_equal:tanggal_berangkat'],
            'keterangan' => ['required', 'string', 'max:1000'],
        ], [
            'tanggal_pulang.after_or_equal' => 'Tanggal pulang tidak boleh sebelum tanggal berangkat.',
        ]);
        $overlap = Perdin::where('user_id', $user->id)
            ->whereIn('status', ['APPROVED', 'PENDING'])
            ->where(function ($query) use ($request) {
                $query->where('tanggal_berangkat', '<=', $request->tanggal_pulang)
                      ->where('tanggal_pulang', '>=', $request->tanggal_berangkat);
            })
            ->exists();

        if ($overlap) {
            return back()->withErrors([
                'tanggal_berangkat' => 'Tanggal yang Anda pilih bertabrakan dengan perjalanan dinas yang sudah diajukan atau disetujui sebelumnya.',
            ])->withInput();
        }

        Perdin::create([
            'user_id' => $user->id,
            'kota_asal_id' => $request->kota_asal_id,
            'kota_tujuan_id' => $request->kota_tujuan_id,
            'tanggal_berangkat' => $request->tanggal_berangkat,
            'tanggal_pulang' => $request->tanggal_pulang,
            'keterangan' => $request->keterangan,
            'status' => 'PENDING',
        ]);

        return redirect()->route('dashboard')->with('success', 'Perjalanan dinas berhasil diajukan.');
    }

    public function approve($id)
    {
        $user = Auth::user();
        if (!$user->isSdm() && !$user->isAdmin()) {
            abort(403);
        }

        $perdin = Perdin::findOrFail($id);
        if ($perdin->status !== 'PENDING') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $overlapApproved = Perdin::where('user_id', $perdin->user_id)
            ->where('id', '!=', $perdin->id)
            ->where('status', 'APPROVED')
            ->where(function ($query) use ($perdin) {
                $query->where('tanggal_berangkat', '<=', $perdin->tanggal_pulang)
                      ->where('tanggal_pulang', '>=', $perdin->tanggal_berangkat);
            })
            ->exists();

        if ($overlapApproved) {
            return back()->with('error', 'Tidak dapat menyetujui. Pegawai ini sudah memiliki perjalanan dinas yang disetujui pada tanggal yang sama.');
        }

        $perdin->status = 'APPROVED';
        $perdin->save();

        return redirect()->route('dashboard')->with('success', 'Pengajuan perjalanan dinas berhasil disetujui.');
    }

    public function reject($id)
    {
        $user = Auth::user();
        if (!$user->isSdm() && !$user->isAdmin()) {
            abort(403);
        }

        $perdin = Perdin::findOrFail($id);
        if ($perdin->status !== 'PENDING') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $perdin->status = 'REJECTED';
        $perdin->save();

        return redirect()->route('dashboard')->with('success', 'Pengajuan perjalanan dinas berhasil ditolak.');
    }
}

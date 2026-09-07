<?php

namespace App\Http\Controllers;

use App\Models\Gobog;
use App\Models\PenjualanTenan;
use App\Services\GobogCipher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenjualanTenanController extends Controller
{
    public function __construct(private GobogCipher $cipher) {}

    public function index(): View
    {
        return view('penjual.scan.index');
    }

    public function scan(Request $request): JsonResponse
    {
        $request->validate(['qr_data' => ['required', 'string']]);

        $kodeUnik = $this->cipher->decrypt($request->qr_data);

        if ($kodeUnik === false) {
            return response()->json([
                'valid' => false,
                'message' => 'GOBOG PALSU: Enkripsi tidak valid.',
            ]);
        }

        $gobog = Gobog::where('kode_unik', $kodeUnik)->first();

        if (! $gobog) {
            return response()->json([
                'valid' => false,
                'message' => 'GOBOG PALSU: Kode tidak ditemukan di database.',
            ]);
        }

        // Koin berstatus 'tersedia' = sudah dikembalikan ke admin / belum didistribusi.
        // Tidak boleh diterima sebagai pembayaran.
        if ($gobog->status === 'tersedia') {
            return response()->json([
                'valid' => false,
                'message' => 'GOBOG TIDAK VALID: Koin ini belum didistribusikan atau sudah dikembalikan ke admin.',
            ]);
        }

        return response()->json([
            'valid' => true,
            'gobog_id' => $gobog->id,
            'nilai' => $gobog->nilai,
            'status' => $gobog->status,
            'message' => 'GOBOG ASLI — Koin valid, siap diterima.',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'gobogs_id' => ['required', 'exists:gobogs,id'],
            'valid_status' => ['required', 'in:0,1'],
        ]);

        // Double-check status koin saat konfirmasi (antisipasi race condition)
        if ($request->valid_status == 1) {
            $gobog = Gobog::findOrFail($request->gobogs_id);

            if ($gobog->status === 'tersedia') {
                return redirect()->route('penjual.scan.index')
                    ->with('error', 'Transaksi gagal: Koin sudah dikembalikan ke admin dan tidak bisa diterima.');
            }
        }

        PenjualanTenan::create([
            'users_id' => auth()->id(),
            'gobogs_id' => $request->gobogs_id,
            'valid_status' => $request->valid_status,
        ]);

        $pesan = $request->valid_status == 1
            ? 'Pembayaran diterima. Transaksi dicatat.'
            : 'Transaksi ditolak. Koin palsu dicatat.';

        return redirect()->route('penjual.scan.index')->with('success', $pesan);
    }
}

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

    // ── Cek Keaslian (hanya validasi, tidak dicatat) ─────────────────────────

    public function cekIndex(): View
    {
        return view('penjual.cek-keaslian.index');
    }

    public function cekScan(Request $request): JsonResponse
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

        return response()->json([
            'valid' => true,
            'nilai' => $gobog->nilai,
            'status' => $gobog->status,
            'message' => $gobog->status === 'tersedia'
                ? 'GOBOG ASLI — Koin ini sedang di stok admin (belum beredar).'
                : 'GOBOG ASLI — Koin ini sedang beredar.',
        ]);
    }

    // ── Scan Penjualan (validasi + dicatat ke laporan) ────────────────────────

    public function index(): View
    {
        return view('penjual.scan-penjualan.index');
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

        if ($gobog->status === 'tersedia') {
            return response()->json([
                'valid' => false,
                'message' => 'GOBOG TIDAK VALID: Koin ini belum didistribusikan ke pengunjung.',
            ]);
        }

        return response()->json([
            'valid' => true,
            'gobog_id' => $gobog->id,
            'nilai' => $gobog->nilai,
            'status' => $gobog->status,
            'message' => 'GOBOG ASLI — Siap diterima sebagai pembayaran.',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'gobogs_id' => ['required', 'exists:gobogs,id'],
            'valid_status' => ['required', 'in:0,1'],
        ]);

        if ($request->valid_status == 1) {
            $gobog = Gobog::findOrFail($request->gobogs_id);

            if ($gobog->status === 'tersedia') {
                return redirect()->route('penjual.scan-penjualan.index')
                    ->with('error', 'Transaksi gagal: Koin belum didistribusikan ke pengunjung.');
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

        return redirect()->route('penjual.scan-penjualan.index')->with('success', $pesan);
    }
}

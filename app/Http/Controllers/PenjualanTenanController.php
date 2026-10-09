<?php

namespace App\Http\Controllers;

use App\Models\Gobog;
use App\Models\PenjualanTenan;
use App\Services\GobogCipher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PenjualanTenanController extends Controller
{
    public function __construct(private GobogCipher $cipher) {}

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
                'gobog_id' => $gobog->id,
                'message' => 'GOBOG TIDAK VALID: Koin ini belum didistribusikan ke pengunjung.',
            ]);
        }

        return response()->json([
            'valid' => true,
            'gobog_id' => $gobog->id,
            'kode_unik' => $gobog->kode_unik,
            'nilai' => $gobog->nilai,
            'status' => $gobog->status,
            'message' => 'GOBOG ASLI — Siap diterima sebagai pembayaran.',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if ($request->has('gobog_ids')) {
            $request->validate([
                'gobog_ids' => ['required', 'array', 'min:1'],
                'gobog_ids.*' => ['required', 'exists:gobogs,id'],
            ]);

            $ids = collect($request->gobog_ids)->unique();
            $diterima = 0;
            $dilewati = 0;

            DB::transaction(function () use ($ids, &$diterima, &$dilewati) {
                foreach ($ids as $id) {
                    $gobog = Gobog::lockForUpdate()->find($id);

                    if (! $gobog || $gobog->status === 'tersedia') {
                        $dilewati++;

                        continue;
                    }

                    PenjualanTenan::create([
                        'users_id' => auth()->id(),
                        'gobogs_id' => $gobog->id,
                        'valid_status' => 1,
                    ]);

                    $diterima++;
                }
            });

            if ($diterima === 0) {
                return redirect()->route('penjual.scan-penjualan.index')
                    ->with('error', 'Tidak ada koin yang valid untuk diterima sebagai pembayaran.');
            }

            $pesan = $diterima.' koin berhasil diterima. Transaksi belanja dicatat.';
            if ($dilewati > 0) {
                $pesan .= ' ('.$dilewati.' koin dilewati karena status belum beredar).';
            }

            return redirect()->route('penjual.scan-penjualan.index')->with('success', $pesan);
        }

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

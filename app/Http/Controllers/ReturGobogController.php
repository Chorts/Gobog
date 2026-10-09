<?php

namespace App\Http\Controllers;

use App\Models\Gobog;
use App\Models\ReturnGobog;
use App\Services\GobogCipher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReturGobogController extends Controller
{
    public function __construct(private GobogCipher $cipher) {}

    public function index(): View
    {
        return view('admin.rekap-pengembalian.index');
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
                'message' => 'RETUR DITOLAK: Koin ini sudah berada di admin (belum didistribusikan atau sudah diretur sebelumnya).',
            ]);
        }

        return response()->json([
            'valid' => true,
            'gobog_id' => $gobog->id,
            'nilai' => $gobog->nilai,
            'status' => $gobog->status,
            'kode_unik' => $gobog->kode_unik,
            'message' => 'GOBOG ASLI — Koin valid, siap diretur.',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'gobog_ids' => ['required', 'array', 'min:1'],
            'gobog_ids.*' => ['required', 'exists:gobogs,id'],
        ]);

        $ids = collect($request->gobog_ids)->unique();
        $diproses = 0;
        $dilewati = 0;

        DB::transaction(function () use ($ids, &$diproses, &$dilewati) {
            foreach ($ids as $id) {
                $gobog = Gobog::lockForUpdate()->find($id);

                if (! $gobog || $gobog->status === 'tersedia') {
                    $dilewati++;

                    continue;
                }

                ReturnGobog::create([
                    'users_id' => auth()->id(),
                    'gobogs_id' => $gobog->id,
                    'valid_status' => 1,
                ]);

                $gobog->update(['status' => 'tersedia']);
                $diproses++;
            }
        });

        if ($diproses === 0) {
            return redirect()->route('admin.rekap-pengembalian.index')
                ->with('error', 'Tidak ada koin yang berhasil diproses untuk pengembalian.');
        }

        $pesan = $diproses.' koin berhasil dikembalikan ke kas admin.';
        if ($dilewati > 0) {
            $pesan .= ' ('.$dilewati.' koin dilewati karena sudah berstatus tersedia).';
        }

        return redirect()->route('admin.rekap-pengembalian.index')->with('success', $pesan);
    }
}

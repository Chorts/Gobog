<?php

namespace App\Http\Controllers;

use App\Models\Gobog;
use App\Models\ReturnGobog;
use App\Services\GobogCipher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReturGobogController extends Controller
{
    public function __construct(private GobogCipher $cipher) {}

    public function index(): View
    {
        return view('admin.retur.index');
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

        return response()->json([
            'valid' => true,
            'gobog_id' => $gobog->id,
            'nilai' => $gobog->nilai,
            'status' => $gobog->status,
            'kode_unik' => $gobog->kode_unik,
            'message' => 'GOBOG ASLI',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'gobogs_id' => ['required'],
            'valid_status' => ['required', 'in:0,1'],
        ]);

        ReturnGobog::create([
            'users_id' => auth()->id(),
            'gobogs_id' => $request->gobogs_id,
            'valid_status' => $request->valid_status,
        ]);

        if ($request->valid_status == 1) {
            Gobog::where('id', $request->gobogs_id)->update(['status' => 'tersedia']);
        }

        $pesan = $request->valid_status == 1
            ? 'Retur berhasil diproses. Koin dikembalikan ke stok.'
            : 'Koin palsu dicatat.';

        return redirect()->route('admin.retur.index')->with('success', $pesan);
    }
}

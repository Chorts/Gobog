<?php

namespace App\Http\Controllers;

use App\Models\Gobog;
use App\Models\PenjualanAdmin;
use App\Models\Setting;
use App\Services\GobogCipher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PenjualanAdminController extends Controller
{
    public function __construct(private GobogCipher $cipher) {}

    public function index(): View
    {
        $harga = Setting::get('harga_gobog', 0);

        return view('admin.rekap-penjualan.index', compact('harga'));
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

        if ($gobog->status !== 'tersedia') {
            return response()->json([
                'valid' => false,
                'message' => 'KOIN TIDAK TERSEDIA: Koin ini sudah didistribusikan sebelumnya.',
            ]);
        }

        return response()->json([
            'valid' => true,
            'gobog_id' => $gobog->id,
            'kode_unik' => $gobog->kode_unik,
            'nilai' => $gobog->nilai,
            'message' => 'Koin valid, ditambahkan ke nota.',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'gobog_ids' => ['required', 'array', 'min:1'],
            'gobog_ids.*' => ['required', 'exists:gobogs,id'],
        ]);

        $ids = collect($request->gobog_ids)->unique();
        $terjual = 0;

        DB::transaction(function () use ($ids, &$terjual) {
            foreach ($ids as $id) {
                $gobog = Gobog::lockForUpdate()->find($id);

                if (! $gobog || $gobog->status !== 'tersedia') {
                    continue;
                }

                PenjualanAdmin::create([
                    'users_id' => auth()->id(),
                    'gobogs_id' => $gobog->id,
                ]);

                $gobog->update(['status' => 'tidak tersedia']);
                $terjual++;
            }
        });

        return redirect()->route('admin.rekap-penjualan.index')
            ->with('success', $terjual.' koin berhasil dijual / didistribusikan.');
    }
}

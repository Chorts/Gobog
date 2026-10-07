<?php

namespace App\Http\Controllers;

use App\Models\HargaHistory;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengaturanController extends Controller
{
    public function index(): View
    {
        $harga = Setting::get('harga_gobog', 0);
        $riwayat = HargaHistory::latest()->get();

        return view('admin.pengaturan.index', compact('harga', 'riwayat'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'harga_gobog' => ['required', 'numeric', 'min:0'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $hargaLama = (float) Setting::get('harga_gobog', 0);
        $hargaBaru = (float) $request->harga_gobog;

        if ($hargaBaru !== $hargaLama) {
            HargaHistory::create([
                'harga' => $hargaBaru,
                'keterangan' => $request->keterangan ?? 'Diperbarui oleh admin',
            ]);
        }

        Setting::set('harga_gobog', (string) $hargaBaru);

        return redirect()->route('admin.pengaturan.index')
            ->with('success', 'Pengaturan berhasil disimpan. Harga koin Gobog diperbarui menjadi Rp '.number_format($hargaBaru, 0, ',', '.'));
    }
}

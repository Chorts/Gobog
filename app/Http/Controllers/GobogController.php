<?php

namespace App\Http\Controllers;

use App\Models\Gobog;
use App\Models\Setting;
use App\Services\GobogCipher;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GobogController extends Controller
{
    public function __construct(private GobogCipher $cipher) {}

    public function index(): View
    {
        $gobogs = Gobog::withCount([
            'penjualanTenans as terjual_count' => fn ($q) => $q->where('valid_status', 1),
        ])->latest()->paginate(20);

        return view('admin.gobog.index', compact('gobogs'));
    }

    public function create(): View
    {
        $harga = Setting::get('harga_gobog', 0);

        return view('admin.gobog.create', compact('harga'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'jumlah' => ['required', 'integer', 'min:1', 'max:500'],
        ]);

        $harga = (float) Setting::get('harga_gobog', 0);
        $qrDir = public_path('qrcodes');

        if (! file_exists($qrDir)) {
            mkdir($qrDir, 0755, true);
        }

        for ($i = 0; $i < $request->jumlah; $i++) {
            $kodeUnik = (string) Str::uuid();
            $kodeEnkripsi = $this->cipher->encrypt($kodeUnik);

            $qrResult = (new Builder(
                writer: new SvgWriter,
                data: $kodeEnkripsi,
                encoding: new Encoding('UTF-8'),
            ))->build();

            $qrFilename = 'qr_'.$kodeUnik.'.svg';
            $qrResult->saveToFile($qrDir.DIRECTORY_SEPARATOR.$qrFilename);

            Gobog::create([
                'kode_unik' => $kodeUnik,
                'nilai' => $harga,
                'foto' => null,
                'qr_code' => 'qrcodes/'.$qrFilename,
                'kode_enkripsi' => $kodeEnkripsi,
                'status' => 'tersedia',
            ]);
        }

        return redirect()->route('admin.gobog.index')
            ->with('success', $request->jumlah.' koin Gobog berhasil dicetak.');
    }

    public function show(Gobog $gobog): View
    {
        $gobog->loadCount([
            'penjualanTenans as terjual_count' => fn ($q) => $q->where('valid_status', 1),
        ]);

        return view('admin.gobog.show', compact('gobog'));
    }

    public function edit(Gobog $gobog): View
    {
        return view('admin.gobog.edit', compact('gobog'));
    }

    public function update(Request $request, Gobog $gobog): RedirectResponse
    {
        $request->validate([
            'foto' => ['nullable', 'image', 'max:2048'],
        ]);

        $data = [];

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('fotos', 'public');
        }

        if (! empty($data)) {
            $gobog->update($data);
        }

        return redirect()->route('admin.gobog.show', $gobog)
            ->with('success', 'Foto koin berhasil diperbarui.');
    }

    public function destroy(Gobog $gobog): RedirectResponse
    {
        $gobog->delete();

        return redirect()->route('admin.gobog.index')
            ->with('success', 'Koin Gobog berhasil dihapus.');
    }
}

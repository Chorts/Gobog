<?php

namespace App\Http\Controllers;

use App\Models\Gobog;
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
        $gobogs = Gobog::latest()->paginate(20);

        return view('admin.gobog.index', compact('gobogs'));
    }

    public function create(): View
    {
        return view('admin.gobog.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nilai' => ['required', 'numeric', 'min:0'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ]);

        $kodeUnik = (string) Str::uuid();
        $kodeEnkripsi = $this->cipher->encrypt($kodeUnik);

        $qrResult = (new Builder(
            writer: new SvgWriter,
            data: $kodeEnkripsi,
            encoding: new Encoding('UTF-8'),
        ))->build();

        $qrDir = public_path('qrcodes');
        if (! file_exists($qrDir)) {
            mkdir($qrDir, 0755, true);
        }

        $qrFilename = 'qr_'.$kodeUnik.'.svg';
        $qrResult->saveToFile($qrDir.DIRECTORY_SEPARATOR.$qrFilename);
        $qrPath = 'qrcodes/'.$qrFilename;

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('fotos', 'public');
        }

        Gobog::create([
            'kode_unik' => $kodeUnik,
            'nilai' => $request->nilai,
            'foto' => $fotoPath,
            'qr_code' => $qrPath,
            'kode_enkripsi' => $kodeEnkripsi,
            'status' => 'tersedia',
        ]);

        return redirect()->route('admin.gobog.index')->with('success', 'Koin Gobog berhasil ditambahkan.');
    }

    public function show(Gobog $gobog): View
    {
        return view('admin.gobog.show', compact('gobog'));
    }

    public function edit(Gobog $gobog): View
    {
        return view('admin.gobog.edit', compact('gobog'));
    }

    public function update(Request $request, Gobog $gobog): RedirectResponse
    {
        $request->validate([
            'nilai' => ['required', 'numeric', 'min:0'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ]);

        $data = ['nilai' => $request->nilai];

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('fotos', 'public');
        }

        $gobog->update($data);

        return redirect()->route('admin.gobog.index')->with('success', 'Koin Gobog berhasil diperbarui.');
    }

    public function destroy(Gobog $gobog): RedirectResponse
    {
        $gobog->delete();

        return redirect()->route('admin.gobog.index')->with('success', 'Koin Gobog berhasil dihapus.');
    }
}

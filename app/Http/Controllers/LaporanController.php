<?php

namespace App\Http\Controllers;

use App\Models\Gobog;
use App\Models\PenjualanAdmin;
use App\Models\PenjualanTenan;
use App\Models\ReturnGobog;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        [$penjualanAdmins, $penjualanTenans, $returnGobogs] = $this->queryData($request);

        $gobogBeredar = Gobog::where('status', 'tidak tersedia')->get();
        $totalBeredar = $gobogBeredar->sum('nilai');
        $harga = Setting::get('harga_gobog', 0);

        return view('admin.laporan.index', compact(
            'penjualanAdmins',
            'penjualanTenans',
            'returnGobogs',
            'gobogBeredar',
            'totalBeredar',
            'harga',
        ));
    }

    public function exportPdf(Request $request): Response
    {
        [$penjualanAdmins, $penjualanTenans, $returnGobogs] = $this->queryData($request);

        $gobogBeredar = Gobog::where('status', 'tidak tersedia')->get();
        $totalBeredar = $gobogBeredar->sum('nilai');

        $pdf = Pdf::loadView('admin.laporan.pdf', compact(
            'penjualanAdmins',
            'penjualanTenans',
            'returnGobogs',
            'gobogBeredar',
            'totalBeredar',
        ));

        return $pdf->download('laporan-gobog-'.now()->format('Ymd').'.pdf');
    }

    /**
     * @return array{0: Collection, 1: Collection, 2: Collection}
     */
    private function queryData(Request $request): array
    {
        $filter = $request->input('filter', 'harian');
        $tanggal = $request->input('tanggal', now()->toDateString());
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        $applyFilter = function ($query) use ($filter, $tanggal, $bulan, $tahun) {
            return match ($filter) {
                'harian' => $query->whereDate('created_at', $tanggal),
                'mingguan' => $query->whereBetween('created_at', [
                    now()->parse($tanggal)->startOfWeek(),
                    now()->parse($tanggal)->endOfWeek(),
                ]),
                'bulanan' => $query->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun),
                'tahunan' => $query->whereYear('created_at', $tahun),
                default => $query->whereDate('created_at', $tanggal),
            };
        };

        $penjualanAdmins = $applyFilter(PenjualanAdmin::with(['user', 'gobog']))->get();
        $penjualanTenans = $applyFilter(PenjualanTenan::with(['user', 'gobog']))->get();
        $returnGobogs = $applyFilter(ReturnGobog::with(['user', 'gobog']))->get();

        return [$penjualanAdmins, $penjualanTenans, $returnGobogs];
    }
}

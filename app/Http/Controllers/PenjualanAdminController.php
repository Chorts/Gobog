<?php

namespace App\Http\Controllers;

use App\Models\Gobog;
use App\Models\PenjualanAdmin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenjualanAdminController extends Controller
{
    public function index(): View
    {
        $gobogs = Gobog::where('status', 'tersedia')->latest()->get();

        return view('admin.distribusi.index', compact('gobogs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'gobogs_id' => ['required', 'exists:gobogs,id'],
        ]);

        $gobog = Gobog::findOrFail($request->gobogs_id);

        if ($gobog->status !== 'tersedia') {
            return back()->with('error', 'Koin ini sudah tidak tersedia.');
        }

        PenjualanAdmin::create([
            'users_id' => auth()->id(),
            'gobogs_id' => $gobog->id,
        ]);

        $gobog->update(['status' => 'tidak tersedia']);

        return redirect()->route('admin.distribusi.index')->with('success', 'Koin berhasil didistribusikan ke pengunjung.');
    }
}

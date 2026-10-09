<?php

namespace App\Http\Controllers;

use App\Models\Tenan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with('tenan')->latest()->paginate(15);
        $tenans = Tenan::all();

        return view('admin.users.index', compact('users', 'tenans'));
    }

    public function create(): View
    {
        $tenans = Tenan::all();

        return view('admin.users.create', compact('tenans'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:admin,penjual'],
            'tenan_option' => ['nullable', 'in:existing,new'],
            'tenans_idtenans' => ['nullable', 'exists:tenans,idtenans'],
            'nama_tenan_baru' => ['nullable', 'string', 'max:45'],
        ]);

        $tenanId = null;

        if ($request->role === 'penjual') {
            if ($request->tenan_option === 'new' && ! empty($request->nama_tenan_baru)) {
                $tenan = Tenan::create(['nama' => $request->nama_tenan_baru]);
                $tenanId = $tenan->idtenans;
            } elseif ($request->tenan_option === 'existing' && ! empty($request->tenans_idtenans)) {
                $tenanId = $request->tenans_idtenans;
            }
        }

        User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'tenans_idtenans' => $tenanId,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        $tenans = Tenan::all();

        return view('admin.users.edit', compact('user', 'tenans'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'in:admin,penjual'],
            'tenan_option' => ['nullable', 'in:existing,new'],
            'tenans_idtenans' => ['nullable', 'exists:tenans,idtenans'],
            'nama_tenan_baru' => ['nullable', 'string', 'max:45'],
        ]);

        $tenanId = $user->tenans_idtenans;

        if ($request->role === 'penjual') {
            if ($request->tenan_option === 'new' && ! empty($request->nama_tenan_baru)) {
                $tenan = Tenan::create(['nama' => $request->nama_tenan_baru]);
                $tenanId = $tenan->idtenans;
            } elseif ($request->tenan_option === 'existing') {
                $tenanId = $request->tenans_idtenans;
            }
        } else {
            $tenanId = null;
        }

        $data = [
            'nama' => $request->nama,
            'username' => $request->username,
            'role' => $request->role,
            'tenans_idtenans' => $tenanId,
        ];

        if (! empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Operator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Tampilkan daftar seluruh pengguna aplikasi.
     */
    public function index(Request $request)
    {
        $role = $request->query('role');
        $status = $request->query('status');
        $search = $request->query('search');

        $query = User::query()->with('operator');

        if ($role && in_array($role, ['super_admin', 'admin_travel', 'customer', 'driver'])) {
            $query->where('role', $role);
        }

        if ($status && in_array($status, ['active', 'banned'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        $counts = [
            'all'          => User::count(),
            'super_admin'  => User::where('role', 'super_admin')->count(),
            'admin_travel' => User::where('role', 'admin_travel')->count(),
            'driver'       => User::where('role', 'driver')->count(),
            'customer'     => User::where('role', 'customer')->count(),
        ];

        return view('super-admin.users.index', compact('users', 'counts', 'role', 'status', 'search'));
    }

    /**
     * Form tambah pengguna baru.
     */
    public function create()
    {
        $operators = Operator::orderBy('name')->get();
        return view('super-admin.users.create', compact('operators'));
    }

    /**
     * Simpan pengguna baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|string|min:8|confirmed',
            'phone'       => 'nullable|string|max:20',
            'role'        => 'required|in:super_admin,admin_travel,driver,customer',
            'operator_id' => 'nullable|required_if:role,admin_travel,driver|exists:operators,id',
            'status'      => 'required|in:active,banned',
        ], [
            'operator_id.required_if' => 'Operator travel wajib dipilih jika peran adalah Admin Travel atau Driver.',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // Jika bukan admin_travel atau driver, kosongkan operator_id
        if (!in_array($validated['role'], ['admin_travel', 'driver'])) {
            $validated['operator_id'] = null;
        }

        User::create($validated);

        return redirect()->route('super-admin.users.index')
            ->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    /**
     * Form edit data pengguna.
     */
    public function edit(User $user)
    {
        $operators = Operator::orderBy('name')->get();
        return view('super-admin.users.edit', compact('user', 'operators'));
    }

    /**
     * Update data pengguna.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password'    => 'nullable|string|min:8|confirmed',
            'phone'       => 'nullable|string|max:20',
            'role'        => 'required|in:super_admin,admin_travel,driver,customer',
            'operator_id' => 'nullable|required_if:role,admin_travel,driver|exists:operators,id',
            'status'      => 'required|in:active,banned',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if (!in_array($validated['role'], ['admin_travel', 'driver'])) {
            $validated['operator_id'] = null;
        }

        $user->update($validated);

        return redirect()->route('super-admin.users.index')
            ->with('success', "Data pengguna '{$user->name}' berhasil diperbarui.");
    }

    /**
     * Hapus akun pengguna.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('super-admin.users.index')
            ->with('success', "Akun pengguna '{$user->name}' berhasil dihapus.");
    }
}

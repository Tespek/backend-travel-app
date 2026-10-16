<?php

namespace App\Http\Controllers\AdminTravel;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    private function operatorId(): int
    {
        return auth()->user()->operator_id;
    }

    public function index()
    {
        $drivers = User::where('role', 'driver')
            ->where('operator_id', $this->operatorId())
            ->latest()
            ->paginate(15);

        return view('admin-travel.drivers.index', compact('drivers'));
    }

    public function create()
    {
        return view('admin-travel.drivers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'phone'       => $validated['phone'] ?? null,
            'password'    => Hash::make($validated['password']),
            'role'        => 'driver',
            'operator_id' => $this->operatorId(),
            'status'      => 'active',
        ]);

        return redirect()->route('admin-travel.drivers.index')
            ->with('success', 'Driver berhasil didaftarkan.');
    }

    public function edit(User $driver)
    {
        $this->authorizeDriver($driver);
        return view('admin-travel.drivers.edit', compact('driver'));
    }

    public function update(Request $request, User $driver)
    {
        $this->authorizeDriver($driver);

        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $driver->id,
            'phone'  => 'nullable|string|max:20',
            'status' => 'required|in:active,banned',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $validated['password'] = Hash::make($request->password);
        }

        $driver->update($validated);

        return redirect()->route('admin-travel.drivers.index')
            ->with('success', 'Data driver berhasil diperbarui.');
    }

    public function destroy(User $driver)
    {
        $this->authorizeDriver($driver);
        $driver->delete();

        return redirect()->route('admin-travel.drivers.index')
            ->with('success', 'Driver berhasil dihapus.');
    }

    /** Pastikan driver milik operator yang login & memang role driver */
    private function authorizeDriver(User $driver): void
    {
        if ($driver->role !== 'driver' || $driver->operator_id !== $this->operatorId()) {
            abort(403, 'Akses ditolak.');
        }
    }
}

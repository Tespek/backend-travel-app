<?php

namespace App\Http\Controllers\AdminTravel;

use App\Http\Controllers\Controller;
use App\Models\Route;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    private function operatorId(): int
    {
        return auth()->user()->operator_id;
    }

    public function index()
    {
        $routes = Route::with(['originCity', 'destinationCity'])
            ->where('operator_id', $this->operatorId())
            ->latest()
            ->paginate(15);

        return view('admin-travel.routes.index', compact('routes'));
    }

    public function create()
    {
        return view('admin-travel.routes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'origin' => 'required|string|max:100',
            'destination' => 'required|string|max:100|different:origin',
            'price' => 'required|numeric|min:0',
            'estimated_duration' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ], [
            'origin.required' => 'Kota asal wajib diisi.',
            'destination.required' => 'Kota tujuan wajib diisi.',
            'destination.different' => 'Kota tujuan tidak boleh sama dengan kota asal.',
        ]);

        $validated['operator_id'] = $this->operatorId();
        $validated['is_active'] = $request->boolean('is_active', true);

        Route::create($validated);

        return redirect()->route('admin-travel.routes.index')
            ->with('success', 'Rute berhasil ditambahkan.');
    }

    public function edit(Route $route)
    {
        $this->authorizeRoute($route);

        return view('admin-travel.routes.edit', compact('route'));
    }

    public function update(Request $request, Route $route)
    {
        $this->authorizeRoute($route);

        $validated = $request->validate([
            'origin' => 'required|string|max:100',
            'destination' => 'required|string|max:100|different:origin',
            'price' => 'required|numeric|min:0',
            'estimated_duration' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ], [
            'origin.required' => 'Kota asal wajib diisi.',
            'destination.required' => 'Kota tujuan wajib diisi.',
            'destination.different' => 'Kota tujuan tidak boleh sama dengan kota asal.',
        ]);

        $validated['is_active'] = $request->boolean('is_active', false);

        $route->update($validated);

        return redirect()->route('admin-travel.routes.index')
            ->with('success', 'Rute berhasil diperbarui.');
    }

    public function destroy(Route $route)
    {
        $this->authorizeRoute($route);

        // Cek apakah ada jadwal aktif
        if ($route->schedules()->whereIn('status', ['open', 'full'])->exists()) {
            return redirect()->route('admin-travel.routes.index')
                ->with('error', 'Rute tidak bisa dihapus karena masih ada jadwal aktif.');
        }

        $route->delete();

        return redirect()->route('admin-travel.routes.index')
            ->with('success', 'Rute berhasil dihapus.');
    }

    /** Pastikan rute milik operator yang sedang login */
    private function authorizeRoute(Route $route): void
    {
        if ($route->operator_id !== $this->operatorId()) {
            abort(403, 'Akses ditolak.');
        }
    }
}

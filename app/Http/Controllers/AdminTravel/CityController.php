<?php

namespace App\Http\Controllers\AdminTravel;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    private function operatorId(): int
    {
        return auth()->user()->operator_id;
    }

    public function index()
    {
        $cities = City::where('operator_id', $this->operatorId())
            ->withCount(['districts', 'pickupPoints'])
            ->latest()
            ->paginate(15);

        return view('admin-travel.cities.index', compact('cities'));
    }

    public function create()
    {
        return view('admin-travel.cities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Nama kota wajib diisi.',
        ]);

        $validated['operator_id'] = $this->operatorId();
        $validated['is_active']   = $request->boolean('is_active', true);

        City::create($validated);

        return redirect()->route('admin-travel.cities.index')
            ->with('success', "Kota '{$validated['name']}' berhasil ditambahkan.");
    }

    public function edit(City $city)
    {
        $this->authorizeCity($city);

        return view('admin-travel.cities.edit', compact('city'));
    }

    public function update(Request $request, City $city)
    {
        $this->authorizeCity($city);

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Nama kota wajib diisi.',
        ]);

        $validated['is_active'] = $request->boolean('is_active', false);

        $city->update($validated);

        return redirect()->route('admin-travel.cities.index')
            ->with('success', "Kota '{$city->name}' berhasil diperbarui.");
    }

    public function destroy(City $city)
    {
        $this->authorizeCity($city);

        if ($city->pickupPoints()->exists()) {
            return back()->with('error', "Kota '{$city->name}' tidak dapat dihapus karena masih memiliki titik penjemputan.");
        }

        $cityName = $city->name;
        $city->delete();

        return redirect()->route('admin-travel.cities.index')
            ->with('success', "Kota '{$cityName}' berhasil dihapus.");
    }

    private function authorizeCity(City $city): void
    {
        if ($city->operator_id !== $this->operatorId()) {
            abort(403, 'Akses ditolak.');
        }
    }
}

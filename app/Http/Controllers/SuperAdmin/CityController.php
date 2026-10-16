<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\District;
use App\Models\Operator;
use App\Models\Route;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Tampilkan daftar kota layanan travel.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        $operatorId = $request->query('operator_id');
        $search = $request->query('search');

        $query = City::query()
            ->with(['operator'])
            ->withCount(['districts', 'pickupPoints']);

        if ($status !== null && in_array($status, ['1', '0'])) {
            $query->where('is_active', (bool) $status);
        }

        if ($operatorId) {
            $query->where('operator_id', $operatorId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('operator', function ($oq) use ($search) {
                      $oq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $cities = $query->latest()->paginate(10)->withQueryString();

        $operators = Operator::orderBy('name')->get();

        $counts = [
            'all'      => City::count(),
            'active'   => City::where('is_active', true)->count(),
            'inactive' => City::where('is_active', false)->count(),
        ];

        return view('super-admin.cities.index', compact('cities', 'operators', 'counts', 'status', 'operatorId', 'search'));
    }

    /**
     * Form tambah kota baru.
     */
    public function create()
    {
        $operators = Operator::orderBy('name')->get();
        return view('super-admin.cities.create', compact('operators'));
    }

    /**
     * Simpan kota baru beserta daftar kecamatan jika ada.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'operator_id' => 'required|exists:operators,id',
            'name'        => 'required|string|max:255',
            'is_active'   => 'boolean',
            'districts'   => 'nullable|string',
        ], [
            'operator_id.required' => 'Operator travel wajib dipilih.',
            'operator_id.exists'   => 'Operator travel tidak valid.',
            'name.required'        => 'Nama kota layanan wajib diisi.',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $city = City::create([
            'operator_id' => $validated['operator_id'],
            'name'        => trim($validated['name']),
            'is_active'   => $validated['is_active'],
        ]);

        // Simpan kecamatan jika diisi (dipisahkan baris baru atau koma)
        if (!empty($validated['districts'])) {
            $districtNames = preg_split('/[\r\n,]+/', $validated['districts']);
            foreach ($districtNames as $districtName) {
                $trimmed = trim($districtName);
                if (!empty($trimmed)) {
                    District::create([
                        'city_id' => $city->id,
                        'name'    => $trimmed,
                    ]);
                }
            }
        }

        return redirect()->route('super-admin.cities.index')
            ->with('success', "Kota layanan '{$city->name}' berhasil ditambahkan.");
    }

    /**
     * Form edit kota layanan.
     */
    public function edit(City $city)
    {
        $city->load('districts');
        $operators = Operator::orderBy('name')->get();
        return view('super-admin.cities.edit', compact('city', 'operators'));
    }

    /**
     * Update data kota layanan.
     */
    public function update(Request $request, City $city)
    {
        $validated = $request->validate([
            'operator_id' => 'required|exists:operators,id',
            'name'        => 'required|string|max:255',
            'is_active'   => 'boolean',
            'districts'   => 'nullable|string',
        ], [
            'operator_id.required' => 'Operator travel wajib dipilih.',
            'name.required'        => 'Nama kota layanan wajib diisi.',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $city->update([
            'operator_id' => $validated['operator_id'],
            'name'        => trim($validated['name']),
            'is_active'   => $validated['is_active'],
        ]);

        // Tambah kecamatan baru jika ada yang dimasukkan
        if (!empty($validated['districts'])) {
            $districtNames = preg_split('/[\r\n,]+/', $validated['districts']);
            foreach ($districtNames as $districtName) {
                $trimmed = trim($districtName);
                if (!empty($trimmed)) {
                    District::firstOrCreate([
                        'city_id' => $city->id,
                        'name'    => $trimmed,
                    ]);
                }
            }
        }

        return redirect()->route('super-admin.cities.index')
            ->with('success', "Data kota '{$city->name}' berhasil diperbarui.");
    }

    /**
     * Hapus kota layanan.
     */
    public function destroy(City $city)
    {
        // Proteksi jika kota digunakan pada rute perjalanan
        $isUsedInRoute = Route::where('origin_city_id', $city->id)
            ->orWhere('destination_city_id', $city->id)
            ->exists();

        if ($isUsedInRoute) {
            return back()->with('error', "Kota '{$city->name}' tidak dapat dihapus karena sedang digunakan dalam rute perjalanan aktif.");
        }

        // Proteksi jika kota memiliki titik penjemputan
        if ($city->pickupPoints()->exists()) {
            return back()->with('error', "Kota '{$city->name}' tidak dapat dihapus karena masih memiliki titik penjemputan.");
        }

        $city->delete();

        return redirect()->route('super-admin.cities.index')
            ->with('success', "Kota '{$city->name}' berhasil dihapus.");
    }
}

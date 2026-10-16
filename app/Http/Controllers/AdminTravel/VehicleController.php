<?php

namespace App\Http\Controllers\AdminTravel;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VehicleController extends Controller
{
    private function operatorId(): int
    {
        return auth()->user()->operator_id;
    }

    private function authorizeVehicle(Vehicle $vehicle): void
    {
        if ($vehicle->operator_id !== $this->operatorId()) {
            abort(403, 'Anda tidak memiliki akses ke data kendaraan ini.');
        }
    }

    /**
     * Tampilkan daftar armada kendaraan travel milik operator saat ini.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $query = Vehicle::where('operator_id', $this->operatorId())
            ->withCount('schedules');

        if ($status && in_array($status, ['available', 'maintenance', 'inactive'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('plate_number', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $vehicles = $query->latest()->paginate(10)->withQueryString();

        $counts = [
            'all'         => Vehicle::where('operator_id', $this->operatorId())->count(),
            'available'   => Vehicle::where('operator_id', $this->operatorId())->where('status', 'available')->count(),
            'maintenance' => Vehicle::where('operator_id', $this->operatorId())->where('status', 'maintenance')->count(),
            'inactive'    => Vehicle::where('operator_id', $this->operatorId())->where('status', 'inactive')->count(),
        ];

        return view('admin-travel.vehicles.index', compact('vehicles', 'counts', 'status', 'search'));
    }

    /**
     * Form tambah armada baru.
     */
    public function create()
    {
        return view('admin-travel.vehicles.create');
    }

    /**
     * Simpan armada baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plate_number' => 'required|string|max:20|unique:vehicles,plate_number',
            'brand'        => 'required|string|max:100',
            'capacity'     => 'required|integer|min:1|max:100',
            'status'       => 'required|in:available,maintenance,inactive',
            'description'  => 'nullable|string|max:1000',
        ], [
            'plate_number.required' => 'Nomor plat kendaraan wajib diisi.',
            'plate_number.unique'   => 'Nomor plat sudah terdaftar dalam sistem.',
            'brand.required'        => 'Merk / tipe kendaraan wajib diisi.',
            'capacity.required'     => 'Kapasitas kursi penumpang wajib diisi.',
            'capacity.min'          => 'Kapasitas minimal 1 kursi.',
            'status.required'       => 'Status armada wajib dipilih.',
        ]);

        $validated['operator_id'] = $this->operatorId();
        $validated['plate_number'] = strtoupper(trim($validated['plate_number']));

        Vehicle::create($validated);

        return redirect()->route('admin-travel.vehicles.index')
            ->with('success', 'Armada kendaraan berhasil ditambahkan.');
    }

    /**
     * Form edit armada kendaraan.
     */
    public function edit(Vehicle $vehicle)
    {
        $this->authorizeVehicle($vehicle);

        return view('admin-travel.vehicles.edit', compact('vehicle'));
    }

    /**
     * Update data armada kendaraan.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $this->authorizeVehicle($vehicle);

        $validated = $request->validate([
            'plate_number' => ['required', 'string', 'max:20', Rule::unique('vehicles')->ignore($vehicle->id)],
            'brand'        => 'required|string|max:100',
            'capacity'     => 'required|integer|min:1|max:100',
            'status'       => 'required|in:available,maintenance,inactive',
            'description'  => 'nullable|string|max:1000',
        ], [
            'plate_number.required' => 'Nomor plat kendaraan wajib diisi.',
            'plate_number.unique'   => 'Nomor plat sudah terdaftar dalam sistem.',
            'brand.required'        => 'Merk / tipe kendaraan wajib diisi.',
            'capacity.required'     => 'Kapasitas kursi penumpang wajib diisi.',
            'capacity.min'          => 'Kapasitas minimal 1 kursi.',
            'status.required'       => 'Status armada wajib dipilih.',
        ]);

        $validated['plate_number'] = strtoupper(trim($validated['plate_number']));

        $vehicle->update($validated);

        return redirect()->route('admin-travel.vehicles.index')
            ->with('success', 'Data armada kendaraan berhasil diperbarui.');
    }

    /**
     * Hapus armada kendaraan.
     */
    public function destroy(Vehicle $vehicle)
    {
        $this->authorizeVehicle($vehicle);

        // Proteksi: jangan hapus jika sudah memiliki jadwal perjalanan
        if ($vehicle->schedules()->exists()) {
            return back()->with('error', 'Armada kendaraan tidak dapat dihapus karena sudah memiliki jadwal keberangkatan.');
        }

        $vehicle->delete();

        return redirect()->route('admin-travel.vehicles.index')
            ->with('success', 'Armada kendaraan berhasil dihapus.');
    }
}

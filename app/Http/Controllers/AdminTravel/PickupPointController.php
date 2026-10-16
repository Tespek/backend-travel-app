<?php

namespace App\Http\Controllers\AdminTravel;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\OperatorPickupPoint;
use Illuminate\Http\Request;

class PickupPointController extends Controller
{
    private function operatorId(): int
    {
        return auth()->user()->operator_id;
    }

    private function authorizePickupPoint(OperatorPickupPoint $pickupPoint): void
    {
        if ($pickupPoint->operator_id !== $this->operatorId()) {
            abort(403, 'Anda tidak memiliki hak akses ke titik penjemputan ini.');
        }
    }

    /**
     * Tampilkan daftar titik penjemputan / pool travel milik operator saat ini.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        $cityId = $request->query('city_id');
        $search = $request->query('search');

        $query = OperatorPickupPoint::where('operator_id', $this->operatorId())
            ->with(['city'])
            ->withCount('bookings');

        if ($status !== null && in_array($status, ['1', '0'])) {
            $query->where('is_active', (bool) $status);
        }

        if ($cityId) {
            $query->where('city_id', $cityId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhereHas('city', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $pickupPoints = $query->latest()->paginate(10)->withQueryString();

        // Ambil daftar kota yang digunakan oleh operator ini untuk dropdown filter
        $cities = City::where('operator_id', $this->operatorId())
            ->orderBy('name')
            ->get();

        // Jika operator belum punya kota sendiri, fallback ke semua kota aktif
        if ($cities->isEmpty()) {
            $cities = City::where('is_active', true)->orderBy('name')->get();
        }

        $counts = [
            'all'      => OperatorPickupPoint::where('operator_id', $this->operatorId())->count(),
            'active'   => OperatorPickupPoint::where('operator_id', $this->operatorId())->where('is_active', true)->count(),
            'inactive' => OperatorPickupPoint::where('operator_id', $this->operatorId())->where('is_active', false)->count(),
        ];

        return view('admin-travel.pickup-points.index', compact('pickupPoints', 'cities', 'counts', 'status', 'cityId', 'search'));
    }

    /**
     * Form tambah titik penjemputan baru.
     */
    public function create()
    {
        $cities = City::where('operator_id', $this->operatorId())
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        if ($cities->isEmpty()) {
            $cities = City::where('is_active', true)->orderBy('name')->get();
        }

        return view('admin-travel.pickup-points.create', compact('cities'));
    }

    /**
     * Simpan titik penjemputan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'city_id'   => 'required|exists:cities,id',
            'name'      => 'required|string|max:255',
            'address'   => 'required|string|max:500',
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'is_active' => 'boolean',
        ], [
            'city_id.required'   => 'Kota penjemputan wajib dipilih.',
            'city_id.exists'     => 'Kota yang dipilih tidak valid.',
            'name.required'      => 'Nama titik jemput / pool wajib diisi.',
            'address.required'   => 'Alamat lengkap wajib diisi.',
            'latitude.required'  => 'Titik koordinat Latitude wajib diisi.',
            'latitude.between'   => 'Nilai Latitude harus berada di antara -90 hingga 90.',
            'longitude.required' => 'Titik koordinat Longitude wajib diisi.',
            'longitude.between'  => 'Nilai Longitude harus berada di antara -180 hingga 180.',
        ]);

        $validated['operator_id'] = $this->operatorId();
        $validated['is_active'] = $request->boolean('is_active', true);

        OperatorPickupPoint::create($validated);

        return redirect()->route('admin-travel.pickup-points.index')
            ->with('success', 'Titik penjemputan berhasil ditambahkan.');
    }

    /**
     * Form edit titik penjemputan.
     */
    public function edit(OperatorPickupPoint $pickupPoint)
    {
        $this->authorizePickupPoint($pickupPoint);

        $cities = City::where('operator_id', $this->operatorId())
            ->orderBy('name')
            ->get();

        if ($cities->isEmpty()) {
            $cities = City::where('is_active', true)->orderBy('name')->get();
        }

        return view('admin-travel.pickup-points.edit', compact('pickupPoint', 'cities'));
    }

    /**
     * Update data titik penjemputan.
     */
    public function update(Request $request, OperatorPickupPoint $pickupPoint)
    {
        $this->authorizePickupPoint($pickupPoint);

        $validated = $request->validate([
            'city_id'   => 'required|exists:cities,id',
            'name'      => 'required|string|max:255',
            'address'   => 'required|string|max:500',
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'is_active' => 'boolean',
        ], [
            'city_id.required'   => 'Kota penjemputan wajib dipilih.',
            'city_id.exists'     => 'Kota yang dipilih tidak valid.',
            'name.required'      => 'Nama titik jemput / pool wajib diisi.',
            'address.required'   => 'Alamat lengkap wajib diisi.',
            'latitude.required'  => 'Titik koordinat Latitude wajib diisi.',
            'latitude.between'   => 'Nilai Latitude harus berada di antara -90 hingga 90.',
            'longitude.required' => 'Titik koordinat Longitude wajib diisi.',
            'longitude.between'  => 'Nilai Longitude harus berada di antara -180 hingga 180.',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $pickupPoint->update($validated);

        return redirect()->route('admin-travel.pickup-points.index')
            ->with('success', 'Titik penjemputan berhasil diperbarui.');
    }

    /**
     * Hapus titik penjemputan.
     */
    public function destroy(OperatorPickupPoint $pickupPoint)
    {
        $this->authorizePickupPoint($pickupPoint);

        // Proteksi: jangan hapus jika sudah memiliki transaksi booking
        if ($pickupPoint->bookings()->exists()) {
            return back()->with('error', 'Titik jemput tidak dapat dihapus karena sudah memiliki riwayat pemesanan tiket.');
        }

        $pickupPoint->delete();

        return redirect()->route('admin-travel.pickup-points.index')
            ->with('success', 'Titik penjemputan berhasil dihapus.');
    }
}

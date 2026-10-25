<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class OperatorController extends Controller
{
    /**
     * Tampilkan daftar operator travel.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $query = Operator::query()->withCount(['vehicles', 'routes', 'users']);

        if ($status && in_array($status, ['pending', 'verified', 'rejected'])) {
            $query->where('verification_status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('owner_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $operators = $query->latest()->paginate(10)->withQueryString();

        $counts = [
            'all' => Operator::count(),
            'pending' => Operator::where('verification_status', 'pending')->count(),
            'verified' => Operator::where('verification_status', 'verified')->count(),
            'rejected' => Operator::where('verification_status', 'rejected')->count(),
        ];

        return view('super-admin.operators.index', compact('operators', 'counts', 'status', 'search'));
    }

    /**
     * Form pendaftaran operator baru oleh Super Admin.
     */
    public function create()
    {
        return view('super-admin.operators.create');
    }

    /**
     * Simpan operator baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:operators,email',
            'owner_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'verification_status' => 'required|in:pending,verified,rejected',
            'rejection_reason' => 'nullable|required_if:verification_status,rejected|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'document_ktp' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'document_siup' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ], [
            'name.required' => 'Nama operator travel wajib diisi.',
            'email.required' => 'Email operator wajib diisi.',
            'email.unique' => 'Email operator sudah terdaftar.',
            'owner_name.required' => 'Nama pemilik wajib diisi.',
            'rejection_reason.required_if' => 'Alasan penolakan wajib diisi jika status Ditolak.',
            'logo.max' => 'Ukuran logo maksimal 2MB.',
            'document_ktp.max' => 'Ukuran dokumen KTP maksimal 5MB.',
            'document_siup.max' => 'Ukuran dokumen SIUP maksimal 5MB.',
        ]);

        // Upload Logo
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('operators/logos', 'public');
        }

        // Upload KTP
        if ($request->hasFile('document_ktp')) {
            $validated['document_ktp'] = $request->file('document_ktp')->store('documents/ktp', 'public');
        }

        // Upload SIUP
        if ($request->hasFile('document_siup')) {
            $validated['document_siup'] = $request->file('document_siup')->store('documents/siup', 'public');
        }

        $operator = Operator::create($validated);

        return redirect()->route('super-admin.operators.show', $operator)
            ->with('success', 'Operator travel berhasil didaftarkan.');
    }

    /**
     * Tampilkan detail profil operator & aksi verifikasi/persetujuan.
     */
    public function show(Operator $operator)
    {
        $operator->loadCount(['vehicles', 'routes', 'pickupPoints', 'bookings']);
        $adminUsers = $operator->users()->where('role', 'admin_travel')->get();
        $drivers = $operator->users()->where('role', 'driver')->get();

        return view('super-admin.operators.show', compact('operator', 'adminUsers', 'drivers'));
    }

    /**
     * Form edit operator.
     */
    public function edit(Operator $operator)
    {
        return view('super-admin.operators.edit', compact('operator'));
    }

    /**
     * Update data profil operator.
     */
    public function update(Request $request, Operator $operator)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('operators')->ignore($operator->id)],
            'owner_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'verification_status' => 'required|in:pending,verified,rejected',
            'rejection_reason' => 'nullable|required_if:verification_status,rejected|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'document_ktp' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'document_siup' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ], [
            'name.required' => 'Nama operator travel wajib diisi.',
            'email.required' => 'Email operator wajib diisi.',
            'owner_name.required' => 'Nama pemilik wajib diisi.',
            'rejection_reason.required_if' => 'Alasan penolakan wajib diisi jika status Ditolak.',
            'logo.max' => 'Ukuran logo maksimal 2MB.',
            'document_ktp.max' => 'Ukuran dokumen KTP maksimal 5MB.',
            'document_siup.max' => 'Ukuran dokumen SIUP maksimal 5MB.',
        ]);

        // Handle Logo replacement
        if ($request->hasFile('logo')) {
            if ($operator->logo && Storage::disk('public')->exists($operator->logo)) {
                Storage::disk('public')->delete($operator->logo);
            }
            $validated['logo'] = $request->file('logo')->store('operators/logos', 'public');
        }

        // Handle KTP replacement
        if ($request->hasFile('document_ktp')) {
            if ($operator->document_ktp && Storage::disk('public')->exists($operator->document_ktp)) {
                Storage::disk('public')->delete($operator->document_ktp);
            }
            $validated['document_ktp'] = $request->file('document_ktp')->store('documents/ktp', 'public');
        }

        // Handle SIUP replacement
        if ($request->hasFile('document_siup')) {
            if ($operator->document_siup && Storage::disk('public')->exists($operator->document_siup)) {
                Storage::disk('public')->delete($operator->document_siup);
            }
            $validated['document_siup'] = $request->file('document_siup')->store('documents/siup', 'public');
        }

        // Bersihkan rejection_reason jika status bukan rejected
        if ($validated['verification_status'] !== 'rejected') {
            $validated['rejection_reason'] = null;
        }

        $operator->update($validated);

        return redirect()->route('super-admin.operators.show', $operator)
            ->with('success', 'Data operator berhasil diperbarui.');
    }

    /**
     * Aksi cepat verifikasi atau tolak status operator.
     */
    public function updateStatus(Request $request, Operator $operator)
    {
        $validated = $request->validate([
            'verification_status' => 'required|in:verified,rejected,pending',
            'rejection_reason' => 'nullable|required_if:verification_status,rejected|string|max:1000',
        ]);

        $updateData = [
            'verification_status' => $validated['verification_status'],
            'rejection_reason' => $validated['verification_status'] === 'rejected' ? $validated['rejection_reason'] : null,
        ];

        $operator->update($updateData);

        $statusText = match ($validated['verification_status']) {
            'verified' => 'diterima dan diverifikasi',
            'rejected' => 'ditolak',
            default => 'diubah statusnya menjadi pending',
        };

        return back()->with('success', "Operator travel '{$operator->name}' berhasil {$statusText}.");
    }

    /**
     * Hapus operator beserta file terkait.
     */
    public function destroy(Operator $operator)
    {
        // Proteksi: jangan hapus jika sudah punya booking
        if ($operator->bookings()->exists()) {
            return back()->with('error', 'Operator tidak dapat dihapus karena sudah memiliki data pemesanan.');
        }

        // Hapus file dari storage
        if ($operator->logo && Storage::disk('public')->exists($operator->logo)) {
            Storage::disk('public')->delete($operator->logo);
        }
        if ($operator->document_ktp && Storage::disk('public')->exists($operator->document_ktp)) {
            Storage::disk('public')->delete($operator->document_ktp);
        }
        if ($operator->document_siup && Storage::disk('public')->exists($operator->document_siup)) {
            Storage::disk('public')->delete($operator->document_siup);
        }

        $operator->delete();

        return redirect()->route('super-admin.operators.index')
            ->with('success', 'Operator berhasil dihapus.');
    }
}

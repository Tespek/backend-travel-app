<?php

namespace App\Http\Controllers\AdminTravel;

use App\Http\Controllers\Controller;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OperatorProfileController extends Controller
{
    /**
     * Tampilkan halaman profil & dokumen travel untuk Admin Travel.
     */
    public function show()
    {
        $operator = Operator::findOrFail(auth()->user()->operator_id);
        $operator->loadCount(['vehicles', 'routes', 'drivers' => function ($q) {
            $q->where('role', 'driver');
        }]);

        return view('admin-travel.profile.operator', compact('operator'));
    }

    /**
     * Update data travel dan upload dokumen KTP/SIUP oleh Admin Travel.
     */
    public function update(Request $request)
    {
        $operator = Operator::findOrFail(auth()->user()->operator_id);

        $validated = $request->validate([
            'phone'         => 'nullable|string|max:20',
            'address'       => 'nullable|string',
            'logo'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'document_ktp'  => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'document_siup' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
        ], [
            'logo.max'          => 'Ukuran logo maksimal 2MB.',
            'document_ktp.max'  => 'Ukuran dokumen KTP maksimal 5MB.',
            'document_siup.max' => 'Ukuran dokumen SIUP maksimal 5MB.',
        ]);

        // Upload Logo
        if ($request->hasFile('logo')) {
            if ($operator->logo && Storage::disk('public')->exists($operator->logo)) {
                Storage::disk('public')->delete($operator->logo);
            }
            $validated['logo'] = $request->file('logo')->store('operators/logos', 'public');
        }

        // Upload KTP
        if ($request->hasFile('document_ktp')) {
            if ($operator->document_ktp && Storage::disk('public')->exists($operator->document_ktp)) {
                Storage::disk('public')->delete($operator->document_ktp);
            }
            $validated['document_ktp'] = $request->file('document_ktp')->store('documents/ktp', 'public');
        }

        // Upload SIUP
        if ($request->hasFile('document_siup')) {
            if ($operator->document_siup && Storage::disk('public')->exists($operator->document_siup)) {
                Storage::disk('public')->delete($operator->document_siup);
            }
            $validated['document_siup'] = $request->file('document_siup')->store('documents/siup', 'public');
        }

        // Jika sebelumnya ditolak dan admin travel mengupload ulang dokumen, kembalikan status ke pending
        if ($operator->verification_status === 'rejected' && ($request->hasFile('document_ktp') || $request->hasFile('document_siup'))) {
            $validated['verification_status'] = 'pending';
            $validated['rejection_reason'] = null;
        }

        $operator->update($validated);

        return redirect()->route('admin-travel.operator-profile.show')
            ->with('success', 'Profil dan dokumen operator travel berhasil diperbarui.');
    }
}

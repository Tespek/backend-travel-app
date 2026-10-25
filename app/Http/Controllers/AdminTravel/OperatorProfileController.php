<?php

namespace App\Http\Controllers\AdminTravel;

use App\Http\Controllers\Controller;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OperatorProfileController extends Controller
{
    /**
     * Tampilkan halaman profil travel untuk Admin Travel.
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
     * Update profil travel oleh Admin Travel.
     */
    public function update(Request $request)
    {
        $operator = Operator::findOrFail(auth()->user()->operator_id);

        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'logo.max' => 'Ukuran logo maksimal 2MB.',
        ]);

        // Upload Logo
        if ($request->hasFile('logo')) {
            if ($operator->logo && Storage::disk('public')->exists($operator->logo)) {
                Storage::disk('public')->delete($operator->logo);
            }
            $validated['logo'] = $request->file('logo')->store('operators/logos', 'public');
        }

        $operator->update($validated);

        return redirect()->route('admin-travel.operator-profile.show')
            ->with('success', 'Profil operator travel berhasil diperbarui.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EmergencyPlan;
use App\Models\PermitToWork;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmergencyPlanController extends Controller
{
    public function create($permitNumber)
    {
        $permit = PermitToWork::with(['receiver', 'permitIssuer'])->where('permit_number', $permitNumber)->firstOrFail();
        
        // Check if emergency plan already exists
        $existingEmergencyPlan = EmergencyPlan::where('permit_number', $permitNumber)->first();
        if ($existingEmergencyPlan) {
            return redirect()->route('emergency-plans.show', $permitNumber)
                ->with('info', 'Emergency & Escape Plan already exists for this permit.');
        }

        return view('emergency-plans.create', compact('permit'));
    }

    public function store(Request $request, $permitNumber)
    {
        $permit = PermitToWork::where('permit_number', $permitNumber)->firstOrFail();

        // Check if emergency plan already exists before creating
        $existingEmergencyPlan = EmergencyPlan::where('permit_number', $permitNumber)->first();
        if ($existingEmergencyPlan) {
            return redirect()->route('emergency-plans.show', $permitNumber)
                ->with('info', 'Emergency & Escape Plan already exists for this permit.');
        }

        $validatedData = $request->validate([
            'kontaminasi_keadaan' => 'nullable|string',
            'petugas_tanggap_darurat' => 'required|boolean',
            'cara_meminta_bantuan' => 'required|boolean',
            'sarana_akses_aman' => 'required|boolean',
            'orang_diselamatkan_aman' => 'required|boolean',
            'tata_graha_keadaan_baik' => 'required|boolean',
            'lokasi_titik_panggilan' => 'required|boolean',
            'ketersediaan_petugas_pertolongan' => 'required|boolean',
            'ketersediaan_defibrilator' => 'required|boolean',
            'ketersediaan_media_pemadam' => 'required|boolean',
            'kebutuhan_alat_pernapasan' => 'required|boolean',
            'apd_khusus_lainnya' => 'required|boolean',
            'sebutkan_apd' => 'nullable|string|required_if:apd_khusus_lainnya,1',
            'alat_ukur_gas_dikalibrasi' => 'required|boolean',
            'peralatan_keselamatan_khusus' => 'required|boolean',
            'alat_keselamatan_digunakan' => 'nullable|string',
            'deskripsi_rencana_penyelamatan' => 'nullable|string',
        ], [
            'petugas_tanggap_darurat.required' => 'Pertanyaan "Petugas tanggap darurat tersedia" wajib dijawab',
            'cara_meminta_bantuan.required' => 'Pertanyaan "Cara meminta bantuan diketahui" wajib dijawab',
            'sarana_akses_aman.required' => 'Pertanyaan "Sarana akses aman tersedia" wajib dijawab',
            'orang_diselamatkan_aman.required' => 'Pertanyaan "Orang yang diselamatkan dalam keadaan aman" wajib dijawab',
            'tata_graha_keadaan_baik.required' => 'Pertanyaan "Tata graha dalam keadaan baik" wajib dijawab',
            'lokasi_titik_panggilan.required' => 'Pertanyaan "Lokasi titik panggilan diketahui" wajib dijawab',
            'ketersediaan_petugas_pertolongan.required' => 'Pertanyaan "Ketersediaan petugas pertolongan" wajib dijawab',
            'ketersediaan_defibrilator.required' => 'Pertanyaan "Ketersediaan defibrilator" wajib dijawab',
            'ketersediaan_media_pemadam.required' => 'Pertanyaan "Ketersediaan media pemadam" wajib dijawab',
            'kebutuhan_alat_pernapasan.required' => 'Pertanyaan "Kebutuhan alat pernapasan" wajib dijawab',
            'apd_khusus_lainnya.required' => 'Pertanyaan "APD khusus lainnya" wajib dijawab',
            'sebutkan_apd.required_if' => 'Wajib menjelaskan APD khusus yang diperlukan jika memilih "Ya"',
            'alat_ukur_gas_dikalibrasi.required' => 'Pertanyaan "Alat ukur gas dikalibrasi" wajib dijawab',
            'peralatan_keselamatan_khusus.required' => 'Pertanyaan "Peralatan keselamatan khusus" wajib dijawab',
        ]);

        $validatedData['permit_number'] = $permitNumber;
        $validatedData['created_by'] = Auth::id();
        $validatedData['status'] = $request->has('submit') ? 'completed' : 'draft';

        EmergencyPlan::create($validatedData);

        return redirect()->route('permits.show', $permit->id)
            ->with('success', 'Emergency & Escape Plan created successfully.');
    }

    public function show($permitNumber)
    {
        $permit = PermitToWork::with(['receiver', 'permitIssuer'])->where('permit_number', $permitNumber)->firstOrFail();
        $emergencyPlan = EmergencyPlan::with('creator')->where('permit_number', $permitNumber)->firstOrFail();

        return view('emergency-plans.show', compact('permit', 'emergencyPlan'));
    }

    public function edit($permitNumber)
    {
        $permit = PermitToWork::with(['receiver', 'permitIssuer'])->where('permit_number', $permitNumber)->firstOrFail();
        $emergencyPlan = EmergencyPlan::where('permit_number', $permitNumber)->firstOrFail();

        return view('emergency-plans.edit', compact('permit', 'emergencyPlan'));
    }

    public function update(Request $request, $permitNumber)
    {
        $permit = PermitToWork::where('permit_number', $permitNumber)->firstOrFail();
        $emergencyPlan = EmergencyPlan::where('permit_number', $permitNumber)->firstOrFail();

        $validatedData = $request->validate([
            'kontaminasi_keadaan' => 'nullable|string',
            'petugas_tanggap_darurat' => 'required|boolean',
            'cara_meminta_bantuan' => 'required|boolean',
            'sarana_akses_aman' => 'required|boolean',
            'orang_diselamatkan_aman' => 'required|boolean',
            'tata_graha_keadaan_baik' => 'required|boolean',
            'lokasi_titik_panggilan' => 'required|boolean',
            'ketersediaan_petugas_pertolongan' => 'required|boolean',
            'ketersediaan_defibrilator' => 'required|boolean',
            'ketersediaan_media_pemadam' => 'required|boolean',
            'kebutuhan_alat_pernapasan' => 'required|boolean',
            'apd_khusus_lainnya' => 'required|boolean',
            'sebutkan_apd' => 'nullable|string|required_if:apd_khusus_lainnya,1',
            'alat_ukur_gas_dikalibrasi' => 'required|boolean',
            'peralatan_keselamatan_khusus' => 'required|boolean',
            'alat_keselamatan_digunakan' => 'nullable|string',
            'deskripsi_rencana_penyelamatan' => 'nullable|string',
        ], [
            'petugas_tanggap_darurat.required' => 'Pertanyaan "Petugas tanggap darurat tersedia" wajib dijawab',
            'cara_meminta_bantuan.required' => 'Pertanyaan "Cara meminta bantuan diketahui" wajib dijawab',
            'sarana_akses_aman.required' => 'Pertanyaan "Sarana akses aman tersedia" wajib dijawab',
            'orang_diselamatkan_aman.required' => 'Pertanyaan "Orang yang diselamatkan dalam keadaan aman" wajib dijawab',
            'tata_graha_keadaan_baik.required' => 'Pertanyaan "Tata graha dalam keadaan baik" wajib dijawab',
            'lokasi_titik_panggilan.required' => 'Pertanyaan "Lokasi titik panggilan diketahui" wajib dijawab',
            'ketersediaan_petugas_pertolongan.required' => 'Pertanyaan "Ketersediaan petugas pertolongan" wajib dijawab',
            'ketersediaan_defibrilator.required' => 'Pertanyaan "Ketersediaan defibrilator" wajib dijawab',
            'ketersediaan_media_pemadam.required' => 'Pertanyaan "Ketersediaan media pemadam" wajib dijawab',
            'kebutuhan_alat_pernapasan.required' => 'Pertanyaan "Kebutuhan alat pernapasan" wajib dijawab',
            'apd_khusus_lainnya.required' => 'Pertanyaan "APD khusus lainnya" wajib dijawab',
            'sebutkan_apd.required_if' => 'Wajib menjelaskan APD khusus yang diperlukan jika memilih "Ya"',
            'alat_ukur_gas_dikalibrasi.required' => 'Pertanyaan "Alat ukur gas dikalibrasi" wajib dijawab',
            'peralatan_keselamatan_khusus.required' => 'Pertanyaan "Peralatan keselamatan khusus" wajib dijawab',
        ]);

        $validatedData['status'] = $request->has('submit') ? 'completed' : 'draft';

        $emergencyPlan->update($validatedData);

        return redirect()->route('permits.show', $permit->id)
            ->with('success', 'Emergency & Escape Plan updated successfully.');
    }

    public function requestApproval($permitNumber)
    {
        $emergencyPlan = EmergencyPlan::where('permit_number', $permitNumber)->firstOrFail();
        
        if ($emergencyPlan->status !== 'completed') {
            return redirect()->back()
                ->with('error', 'Emergency & Escape Plan must be completed before requesting approval.');
        }

        // Update status to indicate approval is requested
        $emergencyPlan->update(['status' => 'pending_approval']);

        return redirect()->back()
            ->with('success', 'Emergency & Escape Plan approval requested successfully.');
    }

    public function approve($permitNumber)
    {
        $user = auth()->user();
        
        // Check if user is Bekaert EHS
        if ($user->role !== 'bekaert' || $user->department !== 'EHS') {
            return redirect()->back()
                ->with('error', 'Only Bekaert EHS department can approve emergency plans.');
        }

        $emergencyPlan = EmergencyPlan::where('permit_number', $permitNumber)->firstOrFail();

        $emergencyPlan->update(['status' => 'approved']);

        return redirect()->back()
            ->with('success', 'Emergency & Escape Plan approved successfully.');
    }

    public function reject($permitNumber)
    {
        $user = auth()->user();
        
        // Check if user is Bekaert EHS
        if ($user->role !== 'bekaert' || $user->department !== 'EHS') {
            return redirect()->back()
                ->with('error', 'Only Bekaert EHS department can reject emergency plans.');
        }

        $emergencyPlan = EmergencyPlan::where('permit_number', $permitNumber)->firstOrFail();

        // Update status to draft (needs revision)
        $emergencyPlan->update(['status' => 'draft']);

        return redirect()->back()
            ->with('error', 'Emergency & Escape Plan rejected. Please revise and resubmit.');
    }
}

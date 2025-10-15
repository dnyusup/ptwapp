<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\HraLotoIsolation;
use App\Models\PermitToWork;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HraLotoIsolationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PermitToWork $permit)
    {
        $hraLotoIsolations = $permit->hraLotoIsolations()->orderBy('created_at', 'desc')->get();
        
        return view('hra.loto-isolations.index', compact('permit', 'hraLotoIsolations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(PermitToWork $permit)
    {
        // Check if loto isolation is required for this permit
        if (!$permit->loto_isolation) {
            return redirect()->route('permits.show', $permit)
                ->with('error', 'LOTO/Isolation is not required for this permit.');
        }
        
        // Check if permit status is active
        if ($permit->status !== 'active') {
            return redirect()->route('permits.show', $permit)
                ->with('error', 'HRA can only be created when permit status is Active.');
        }
        
        // Load permit with receiver relationship
        $permit->load('receiver');
        
        // Get users from the same company as permit receiver
        $users = collect();
        if ($permit->receiver_company_name) {
            // Get users from the same company as receiver
            $users = User::whereHas('company', function($query) use ($permit) {
                $query->where('company_name', $permit->receiver_company_name);
            })->select('id', 'name', 'email', 'phone')->orderBy('name')->get();
        }
        
        return view('hra.loto-isolations.create', compact('permit', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, PermitToWork $permit)
    {
        $validated = $request->validate([
            'worker_name' => 'required|string|max:255',
            'worker_phone' => 'nullable|string|max:20',
            'supervisor_name' => 'required|string|max:255',
            'work_location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_date' => 'required|date',
            'end_time' => 'required|date_format:H:i',
            'work_description' => 'required|string',
            
            // Isolasi Mesin/Tangki
            'machine_tank_name' => 'required|string|max:255',
            
            // Panel Listrik
            'panel_listrik_mati' => 'nullable|boolean',
            'panel_listrik_dikunci' => 'nullable|boolean',
            'panel_listrik_diperiksa' => 'nullable|boolean',
            'panel_listrik_dipasang_tag' => 'nullable|boolean',
            
            // Pneumatic
            'pneumatic_mati' => 'nullable|boolean',
            'pneumatic_dikunci' => 'nullable|boolean',
            'pneumatic_diperiksa' => 'nullable|boolean',
            'pneumatic_dipasang_tag' => 'nullable|boolean',
            
            // Hydraulic
            'hydraulic_mati' => 'nullable|boolean',
            'hydraulic_dikunci' => 'nullable|boolean',
            'hydraulic_diperiksa' => 'nullable|boolean',
            'hydraulic_dipasang_tag' => 'nullable|boolean',
            
            // Gravitasi
            'gravitasi_mati' => 'nullable|boolean',
            'gravitasi_dikunci' => 'nullable|boolean',
            'gravitasi_diperiksa' => 'nullable|boolean',
            'gravitasi_dipasang_tag' => 'nullable|boolean',
            
            // Spring/Per
            'spring_per_mati' => 'nullable|boolean',
            'spring_per_dikunci' => 'nullable|boolean',
            'spring_per_diperiksa' => 'nullable|boolean',
            'spring_per_dipasang_tag' => 'nullable|boolean',
            
            // Rotasi/Gerakan
            'rotasi_gerakan_mati' => 'nullable|boolean',
            'rotasi_gerakan_dikunci' => 'nullable|boolean',
            'rotasi_gerakan_diperiksa' => 'nullable|boolean',
            'rotasi_gerakan_dipasang_tag' => 'nullable|boolean',
            
            // Isolasi Listrik - Panel Listrik
            'bekerja_panel_listrik' => 'nullable|string|in:ya,tidak',
            'referensi_manual_panel' => 'nullable|string|max:255',
            'saklar_diposisi_off' => 'nullable|string|in:ya,tidak',
            'tag_dipasang_panel' => 'nullable|string|in:ya,tidak',
            'sekring_cb_dimatikan' => 'nullable|string|in:ya,tidak',
            'panel_off_panel' => 'nullable|string|in:ya,tidak',
            
            // Isolasi Listrik - Sistem Mekanis
            'bekerja_sistem_mekanis' => 'nullable|string|in:ya,tidak',
            'referensi_manual_sistem' => 'nullable|string|max:255',
            'safety_switch_off' => 'nullable|string|in:ya,tidak',
            'tag_dipasang_sistem' => 'nullable|string|in:ya,tidak',
            'sekring_cb_sistem_dimatikan' => 'nullable|string|in:ya,tidak',
            'sudah_dicoba_dinyalakan' => 'nullable|string|in:ya,tidak',
            
            // Tes Listrik
            'membutuhkan_tes_listrik_on' => 'nullable|string|in:ya,tidak',
            'safety_barrier' => 'nullable|string|in:ya,tidak',
            'full_face_protection' => 'nullable|string|in:ya,tidak',
            'insulated_gloves' => 'nullable|string|in:ya,tidak',
            'insulated_mat' => 'nullable|string|in:ya,tidak',
            'full_length_sleeves' => 'nullable|string|in:ya,tidak',
            'tool_insulation_satisfactory' => 'nullable|string|in:ya,tidak',
            'maximum_voltage' => 'nullable|integer|min:0',
            'alasan_live_test' => 'required_if:membutuhkan_tes_listrik_on,ya|nullable|string|max:1000',
            
            // Isolasi Utility
            'utility_listrik_off' => 'nullable|boolean',
            'utility_listrik_secured' => 'nullable|boolean',
            'utility_listrik_checked' => 'nullable|boolean',
            'utility_listrik_tagged' => 'nullable|boolean',
            'utility_cooling_water_off' => 'nullable|boolean',
            'utility_cooling_water_secured' => 'nullable|boolean',
            'utility_cooling_water_checked' => 'nullable|boolean',
            'utility_cooling_water_tagged' => 'nullable|boolean',
            'utility_oil_hidrolik_off' => 'nullable|boolean',
            'utility_oil_hidrolik_secured' => 'nullable|boolean',
            'utility_oil_hidrolik_checked' => 'nullable|boolean',
            'utility_oil_hidrolik_tagged' => 'nullable|boolean',
            'utility_kompresor_off' => 'nullable|boolean',
            'utility_kompresor_secured' => 'nullable|boolean',
            'utility_kompresor_checked' => 'nullable|boolean',
            'utility_kompresor_tagged' => 'nullable|boolean',
            'utility_vacuum_off' => 'nullable|boolean',
            'utility_vacuum_secured' => 'nullable|boolean',
            'utility_vacuum_checked' => 'nullable|boolean',
            'utility_vacuum_tagged' => 'nullable|boolean',
            'utility_gas_off' => 'nullable|boolean',
            'utility_gas_secured' => 'nullable|boolean',
            'utility_gas_checked' => 'nullable|boolean',
            'utility_gas_tagged' => 'nullable|boolean',
            'utility_lainnya_nama' => 'nullable|string|max:255|min:5',
            'utility_lainnya_off' => 'nullable|boolean',
            'utility_lainnya_secured' => 'nullable|boolean',
            'utility_lainnya_checked' => 'nullable|boolean',
            'utility_lainnya_tagged' => 'nullable|boolean',
            'line_diisolasi_plat' => 'nullable|string|in:ya,tidak',
            'alasan_deskripsi_isolasi' => 'nullable|string|max:1000',
            'area_terdampak_isolasi' => 'nullable|string|max:255',
            'area_sudah_diberitahu' => 'nullable|string|in:ya,tidak',
            
            // Mematikan Pipa
            'isi_line_pipa' => 'nullable|string|max:255',
            'tidak_ada_sisa_tekanan' => 'nullable|string|in:ya,tidak',
            'drain_bleed_valves' => 'nullable|string|in:ya,tidak',
            'pipa_purged_udara' => 'nullable|boolean',
            'pipa_purged_air' => 'nullable|boolean',
            'pipa_purged_nitrogen' => 'nullable|boolean',
            'pipa_diisolasi_plat' => 'nullable|string|in:ya,tidak',
            'pipa_kosong' => 'nullable|string|in:ya,tidak',
            'pipa_bersih' => 'nullable|string|in:ya,tidak',
            'alasan_deskripsi_isolasi_pipa' => 'nullable|string|max:1000',
        ]);
        
        // Custom validation for utility lainnya
        if (!empty($validated['utility_lainnya_nama']) && strlen(trim($validated['utility_lainnya_nama'])) >= 5) {
            // If lainnya name is provided with at least 5 characters, at least one checkbox must be selected
            $lainnyaCheckboxes = [
                $validated['utility_lainnya_off'] ?? false,
                $validated['utility_lainnya_secured'] ?? false,
                $validated['utility_lainnya_checked'] ?? false,
                $validated['utility_lainnya_tagged'] ?? false
            ];
            
            if (!in_array(true, $lainnyaCheckboxes)) {
                return redirect()->back()
                    ->withErrors(['utility_lainnya_checkboxes' => 'Minimal satu status isolasi harus dipilih untuk utility lainnya.'])
                    ->withInput();
            }
        }
        
        // Combine date and time fields into datetime
        $validated['start_datetime'] = $validated['start_date'] . ' ' . $validated['start_time'];
        $validated['end_datetime'] = $validated['end_date'] . ' ' . $validated['end_time'];
        
        // Remove the separate date and time fields as they're not needed in database
        unset($validated['start_date'], $validated['start_time'], $validated['end_date'], $validated['end_time']);

        // Generate HRA permit number
        $hraPermitNumber = HraLotoIsolation::generateHraPermitNumber($permit->permit_number);

        $hraLotoIsolation = HraLotoIsolation::create(array_merge($validated, [
            'hra_permit_number' => $hraPermitNumber,
            'permit_to_work_id' => $permit->id,
            'permit_number' => $permit->permit_number,
            'user_id' => Auth::id(),
            'status' => 'draft',
        ]));

        return redirect()->route('hra.loto-isolations.show', [$permit, $hraLotoIsolation])
                        ->with('success', 'HRA LOTO/Isolation created successfully with permit number: ' . $hraPermitNumber);
    }

    /**
     * Display the specified resource.
     */
    public function show(PermitToWork $permit, HraLotoIsolation $hraLotoIsolation)
    {
        // Ensure the HRA belongs to the permit
        if ($hraLotoIsolation->permit_to_work_id !== $permit->id) {
            abort(404);
        }

        return view('hra.loto-isolations.show', compact('permit', 'hraLotoIsolation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PermitToWork $permit, HraLotoIsolation $hraLotoIsolation)
    {
        // Ensure the HRA belongs to the permit
        if ($hraLotoIsolation->permit_to_work_id !== $permit->id) {
            abort(404);
        }

        return view('hra.loto-isolations.edit', compact('permit', 'hraLotoIsolation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PermitToWork $permit, HraLotoIsolation $hraLotoIsolation)
    {
        // Ensure the HRA belongs to the permit
        if ($hraLotoIsolation->permit_to_work_id !== $permit->id) {
            abort(404);
        }

        $validated = $request->validate([
            'worker_name' => 'required|string|max:255',
            'worker_phone' => 'nullable|string|max:20',
            'supervisor_name' => 'required|string|max:255',
            'work_location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_date' => 'required|date',
            'end_time' => 'required|date_format:H:i',
            'work_description' => 'required|string',
            
            // Isolasi Mesin/Tangki
            'machine_tank_name' => 'required|string|max:255',
            
            // All the validation rules from store method
            'panel_listrik_mati' => 'nullable|boolean',
            'panel_listrik_dikunci' => 'nullable|boolean',
            'panel_listrik_diperiksa' => 'nullable|boolean',
            'panel_listrik_dipasang_tag' => 'nullable|boolean',
            'pneumatic_mati' => 'nullable|boolean',
            'pneumatic_dikunci' => 'nullable|boolean',
            'pneumatic_diperiksa' => 'nullable|boolean',
            'pneumatic_dipasang_tag' => 'nullable|boolean',
            'hydraulic_mati' => 'nullable|boolean',
            'hydraulic_dikunci' => 'nullable|boolean',
            'hydraulic_diperiksa' => 'nullable|boolean',
            'hydraulic_dipasang_tag' => 'nullable|boolean',
            'gravitasi_mati' => 'nullable|boolean',
            'gravitasi_dikunci' => 'nullable|boolean',
            'gravitasi_diperiksa' => 'nullable|boolean',
            'gravitasi_dipasang_tag' => 'nullable|boolean',
            'spring_per_mati' => 'nullable|boolean',
            'spring_per_dikunci' => 'nullable|boolean',
            'spring_per_diperiksa' => 'nullable|boolean',
            'spring_per_dipasang_tag' => 'nullable|boolean',
            'rotasi_gerakan_mati' => 'nullable|boolean',
            'rotasi_gerakan_dikunci' => 'nullable|boolean',
            'rotasi_gerakan_diperiksa' => 'nullable|boolean',
            'rotasi_gerakan_dipasang_tag' => 'nullable|boolean',
            
            // Isolasi Listrik
            'bekerja_panel_listrik' => 'nullable|string|in:ya,tidak',
            'referensi_manual_panel' => 'nullable|string|in:ya,tidak',
            'saklar_diposisi_off' => 'nullable|string|in:ya,tidak',
            'tag_dipasang_panel' => 'nullable|string|in:ya,tidak',
            'sekring_cb_dimatikan' => 'nullable|string|in:ya,tidak',
            'panel_off_panel' => 'nullable|string|in:ya,tidak',
            'bekerja_sistem_mekanis' => 'nullable|string|in:ya,tidak',
            'referensi_manual_sistem' => 'nullable|string|in:ya,tidak',
            'safety_switch_off' => 'nullable|string|in:ya,tidak',
            'tag_dipasang_sistem' => 'nullable|string|in:ya,tidak',
            'sekring_cb_sistem_dimatikan' => 'nullable|string|in:ya,tidak',
            'sudah_dicoba_dinyalakan' => 'nullable|string|in:ya,tidak',
            
            // Tes Listrik
            'membutuhkan_tes_listrik_on' => 'nullable|string|in:ya,tidak',
            'safety_barrier' => 'nullable|string|in:ya,tidak',
            'full_face_protection' => 'nullable|string|in:ya,tidak',
            'insulated_gloves' => 'nullable|string|in:ya,tidak',
            'insulated_mat' => 'nullable|string|in:ya,tidak',
            'full_length_sleeves' => 'nullable|string|in:ya,tidak',
            'tool_insulation_satisfactory' => 'nullable|string|in:ya,tidak',
            'maximum_voltage' => 'nullable|numeric|min:0',
            'alasan_live_test' => 'nullable|string|max:1000',
            
            // Isolasi Utility
            'utility_listrik_off' => 'nullable|boolean',
            'utility_listrik_secured' => 'nullable|boolean',
            'utility_listrik_checked' => 'nullable|boolean',
            'utility_listrik_tagged' => 'nullable|boolean',
            'utility_cooling_water_off' => 'nullable|boolean',
            'utility_cooling_water_secured' => 'nullable|boolean',
            'utility_cooling_water_checked' => 'nullable|boolean',
            'utility_cooling_water_tagged' => 'nullable|boolean',
            'utility_oil_hidrolik_off' => 'nullable|boolean',
            'utility_oil_hidrolik_secured' => 'nullable|boolean',
            'utility_oil_hidrolik_checked' => 'nullable|boolean',
            'utility_oil_hidrolik_tagged' => 'nullable|boolean',
            'utility_kompresor_off' => 'nullable|boolean',
            'utility_kompresor_secured' => 'nullable|boolean',
            'utility_kompresor_checked' => 'nullable|boolean',
            'utility_kompresor_tagged' => 'nullable|boolean',
            'utility_vacuum_off' => 'nullable|boolean',
            'utility_vacuum_secured' => 'nullable|boolean',
            'utility_vacuum_checked' => 'nullable|boolean',
            'utility_vacuum_tagged' => 'nullable|boolean',
            'utility_gas_off' => 'nullable|boolean',
            'utility_gas_secured' => 'nullable|boolean',
            'utility_gas_checked' => 'nullable|boolean',
            'utility_gas_tagged' => 'nullable|boolean',
            'utility_lainnya_nama' => 'nullable|string|max:255|min:5',
            'utility_lainnya_off' => 'nullable|boolean',
            'utility_lainnya_secured' => 'nullable|boolean',
            'utility_lainnya_checked' => 'nullable|boolean',
            'utility_lainnya_tagged' => 'nullable|boolean',
            'line_diisolasi_plat' => 'nullable|string|in:ya,tidak',
            'alasan_deskripsi_isolasi' => 'nullable|string|max:1000',
            'area_terdampak_isolasi' => 'nullable|string|max:255',
            'area_sudah_diberitahu' => 'nullable|string|in:ya,tidak',
            
            // Mematikan Pipa
            'isi_line_pipa' => 'nullable|string|max:255',
            'tidak_ada_sisa_tekanan' => 'nullable|string|in:ya,tidak',
            'drain_bleed_valves' => 'nullable|string|in:ya,tidak',
            'pipa_purged_udara' => 'nullable|boolean',
            'pipa_purged_air' => 'nullable|boolean',
            'pipa_purged_nitrogen' => 'nullable|boolean',
            'pipa_diisolasi_plat' => 'nullable|string|in:ya,tidak',
            'pipa_kosong' => 'nullable|string|in:ya,tidak',
            'pipa_bersih' => 'nullable|string|in:ya,tidak',
            'alasan_deskripsi_isolasi_pipa' => 'nullable|string|max:1000',
        ]);
        
        // Handle datetime combination like in store method
        $validated['start_datetime'] = $validated['start_date'] . ' ' . $validated['start_time'];
        $validated['end_datetime'] = $validated['end_date'] . ' ' . $validated['end_time'];
        
        // Remove separate date/time fields
        unset($validated['start_date'], $validated['start_time'], $validated['end_date'], $validated['end_time']);
        
        // Custom validation for utility lainnya (same as store method)
        if (!empty($validated['utility_lainnya_nama']) && strlen(trim($validated['utility_lainnya_nama'])) >= 5) {
            $lainnyaCheckboxes = [
                $validated['utility_lainnya_off'] ?? false,
                $validated['utility_lainnya_secured'] ?? false,
                $validated['utility_lainnya_checked'] ?? false,
                $validated['utility_lainnya_tagged'] ?? false
            ];
            
            if (!in_array(true, $lainnyaCheckboxes)) {
                return back()->withErrors([
                    'utility_lainnya_off' => 'Jika nama utility lainnya diisi, minimal satu checkbox harus dipilih.'
                ])->withInput();
            }
        }

        $hraLotoIsolation->update($validated);

        return redirect()->route('permits.show', $permit)
                        ->with('success', 'HRA LOTO/Isolation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PermitToWork $permit, HraLotoIsolation $hraLotoIsolation)
    {
        // Ensure the HRA belongs to the permit
        if ($hraLotoIsolation->permit_to_work_id !== $permit->id) {
            abort(404);
        }

        $hraLotoIsolation->delete();

        return redirect()->route('permits.show', $permit)
                        ->with('success', 'HRA LOTO/Isolation deleted successfully.');
    }

    /**
     * Download HRA as PDF
     */
    public function downloadPdf(PermitToWork $permit, HraLotoIsolation $hraLotoIsolation)
    {
        // Load relationships
        $permit->load([
            'permitIssuer', 
            'authorizer', 
            'receiver', 
            'methodStatement'
        ]);
        
        $hraLotoIsolation->load('user');

        // Generate QR Code for HRA
        $qrUrl = url('/permits/' . $permit->id . '/hra/loto-isolations/' . $hraLotoIsolation->id);
        $renderer = new \BaconQrCode\Renderer\ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(80),
            new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
        );
        $writer = new \BaconQrCode\Writer($renderer);
        $qrCode = base64_encode($writer->writeString($qrUrl));

        // Generate QR Code for Main Permit
        $permitUrl = route('permits.show', $permit->id);
        $permitRenderer = new \BaconQrCode\Renderer\ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(70),
            new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
        );
        $permitWriter = new \BaconQrCode\Writer($permitRenderer);
        $permitQrCode = base64_encode($permitWriter->writeString($permitUrl));

        $pdf = \PDF::loadView('hra.loto-isolations.pdf', compact('permit', 'hraLotoIsolation', 'qrCode', 'permitQrCode'));
        
        return $pdf->download('hra-' . $hraLotoIsolation->hra_permit_number . '.pdf');
    }
}

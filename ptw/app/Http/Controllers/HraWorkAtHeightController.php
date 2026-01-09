<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PermitToWork;
use App\Models\HraWorkAtHeight;
use App\Models\User;
use App\Mail\HraApprovalRequest;
use App\Mail\HraApprovalNotification;
use App\Mail\HraRejectionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class HraWorkAtHeightController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PermitToWork $permit)
    {
        $hras = $permit->hraWorkAtHeights()->with('user')->orderBy('created_at', 'desc')->get();
        return view('hra.work-at-heights.index', compact('permit', 'hras'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(PermitToWork $permit)
    {
        // Check if main permit has work_at_heights enabled
        if (!$permit->work_at_heights) {
            return redirect()->route('permits.show', $permit)
                ->with('error', 'Work at Heights is not required for this permit.');
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
        
        return view('hra.work-at-heights.create', compact('permit', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, PermitToWork $permit)
    {
        // Check if permit status is active
        if ($permit->status !== 'active') {
            return redirect()->route('permits.show', $permit)
                ->with('error', 'HRA can only be created when permit status is Active.');
        }
        
        $validated = $request->validate([
            // Basic Information
            'worker_name' => 'required|string|max:255',
            'worker_phone' => 'nullable|string|max:20',
            'supervisor_name' => 'required|string|max:255',
            'work_location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_date' => 'required|date',
            'end_time' => 'required|date_format:H:i',
            'work_description' => 'required|string',
            
            // Overhead Hazards
            'overhead_hazards_checked' => 'boolean',
            'overhead_hazards_minimal_guards' => 'boolean',
            
            // Fixed Scaffolding
            'fixed_scaffolding_checked' => 'boolean',
            'fixed_scaffolding_approved_by_she' => 'boolean',
            
            // Mobile Scaffolding
            'mobile_scaffolding_checked' => 'boolean',
            'mobile_scaffolding_approved_by_she' => 'boolean',
            
            // Mobile Elevation Platform
            'mobile_elevation_checked' => 'boolean',
            'mobile_elevation_operator_trained' => 'boolean',
            'mobile_elevation_rescue_person' => 'boolean',
            'mobile_elevation_monitor_in_place' => 'boolean',
            'mobile_elevation_legal_inspection' => 'boolean',
            'mobile_elevation_pre_use_inspection' => 'boolean',
            
            // Ladder
            'ladder_checked' => 'boolean',
            'mobile_elevation_activities_short' => 'boolean',
            'mobile_elevation_location_marked' => 'boolean',
            'ladder_area_barriers' => 'boolean',
            
            // Fall Arrest
            'fall_arrest_used' => 'boolean',
            'fall_arrest_worker_trained' => 'boolean',
            'fall_arrest_legal_inspection' => 'boolean',
            'fall_arrest_pre_use_inspection' => 'boolean',
            'fall_arrest_qualified_personnel' => 'boolean',
            'safety_personnel_required' => 'boolean',
            
            // Roof Work
            'roof_work_checked' => 'boolean',
            'roof_load_capacity_adequate' => 'boolean',
            'roof_edge_protection' => 'boolean',
            'roof_fall_protection_system' => 'boolean',
            'roof_communication_method' => 'boolean',
            'area_closed_from_below' => 'boolean',
            
            // Work Conditions  
            'workers_have_training_proof' => 'boolean',
            'area_below_blocked' => 'boolean',
            'workers_below_present' => 'boolean',
            'floor_suitable_for_access_equipment' => 'boolean',
            'obstacles_near_work_location' => 'boolean',
            'ventilation_hazardous_emissions' => 'boolean',
            'protection_needed_for_equipment' => 'boolean',
            'safe_access_exit_method' => 'boolean',
            'safe_material_handling_method' => 'boolean',
            'emergency_escape_plan_needed' => 'boolean',
            'other_conditions_check' => 'boolean',
            'other_conditions_text' => 'nullable|string|max:1000',
            
            // Environmental Conditions (single select)
            'visibility_condition' => 'nullable|string|in:terang,remang-remang,gelap,berkabut',
            'rain_condition' => 'nullable|string|in:tidak,rintik,gerimis,deras',
            'surface_condition' => 'nullable|string|in:kering,basah,licin',
            'wind_condition' => 'nullable|string|in:tidak,kecil,sedang,kuat',
            'chemical_spill_condition' => 'nullable|string|in:Ya,Tidak',
            'environment_other_conditions' => 'nullable|string|max:1000',
            
            // Additional Controls
            'additional_controls' => 'nullable|string|max:2000',
        ]);
        
        // Combine date and time fields into datetime
        $validated['start_datetime'] = $validated['start_date'] . ' ' . $validated['start_time'];
        $validated['end_datetime'] = $validated['end_date'] . ' ' . $validated['end_time'];
        
        // Remove the separate date and time fields as they're not needed in database
        unset($validated['start_date'], $validated['start_time'], $validated['end_date'], $validated['end_time']);
        
        // Generate HRA permit number
        $hraPermitNumber = HraWorkAtHeight::generateHraPermitNumber($permit->permit_number);
        
        $validated['hra_permit_number'] = $hraPermitNumber;
        $validated['permit_to_work_id'] = $permit->id;
        $validated['permit_number'] = $permit->permit_number;
        $validated['user_id'] = auth()->id(); // Add current user as creator
        
        $hraPermit = HraWorkAtHeight::create($validated);
        
        return redirect()->route('hra.work-at-heights.show', [$permit, $hraPermit])
            ->with('success', 'HRA Work at Heights permit created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PermitToWork $permit, HraWorkAtHeight $hraWorkAtHeight)
    {
        return view('hra.work-at-heights.show', compact('permit', 'hraWorkAtHeight'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PermitToWork $permit, HraWorkAtHeight $hraWorkAtHeight)
    {
        // Load permit with receiver relationship
        $permit->load('receiver');
        
        // Get users from the same company as permit receiver
        $users = collect();
        if ($permit->receiver_company_name) {
            $users = User::whereHas('company', function($query) use ($permit) {
                $query->where('company_name', $permit->receiver_company_name);
            })->select('id', 'name', 'email', 'phone')->orderBy('name')->get();
        }
        
        return view('hra.work-at-heights.edit', compact('permit', 'hraWorkAtHeight', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PermitToWork $permit, HraWorkAtHeight $hraWorkAtHeight)
    {
        $validated = $request->validate([
            // Basic Information
            'worker_name' => 'required|string|max:255',
            'worker_phone' => 'nullable|string|max:20',
            'supervisor_name' => 'required|string|max:255',
            'work_location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'start_time' => 'required',
            'end_date' => 'required|date',
            'end_time' => 'required',
            'work_description' => 'required|string',
            
            // Overhead Hazards
            'overhead_hazards_checked' => 'boolean',
            'overhead_hazards_minimal_guards' => 'boolean',
            
            // Fixed Scaffolding
            'fixed_scaffolding_checked' => 'boolean',
            'fixed_scaffolding_approved_by_she' => 'boolean',
            
            // Mobile Scaffolding
            'mobile_scaffolding_checked' => 'boolean',
            'mobile_scaffolding_approved_by_she' => 'boolean',
            
            // Mobile Elevation Platform
            'mobile_elevation_checked' => 'boolean',
            'mobile_elevation_operator_trained' => 'boolean',
            'mobile_elevation_rescue_person' => 'boolean',
            'mobile_elevation_monitor_in_place' => 'boolean',
            'mobile_elevation_legal_inspection' => 'boolean',
            'mobile_elevation_pre_use_inspection' => 'boolean',
            
            // Ladder
            'ladder_checked' => 'boolean',
            'mobile_elevation_activities_short' => 'boolean',
            'mobile_elevation_location_marked' => 'boolean',
            'ladder_area_barriers' => 'boolean',
            
            // Fall Arrest
            'fall_arrest_used' => 'boolean',
            'fall_arrest_worker_trained' => 'boolean',
            'fall_arrest_legal_inspection' => 'boolean',
            'fall_arrest_pre_use_inspection' => 'boolean',
            'fall_arrest_qualified_personnel' => 'boolean',
            'safety_personnel_required' => 'boolean',
            
            // Roof Work
            'roof_work_checked' => 'boolean',
            'roof_load_capacity_adequate' => 'boolean',
            'roof_edge_protection' => 'boolean',
            'roof_fall_protection_system' => 'boolean',
            'roof_communication_method' => 'boolean',
            'area_closed_from_below' => 'boolean',
            
            // Work Conditions  
            'workers_have_training_proof' => 'boolean',
            'area_below_blocked' => 'boolean',
            'workers_below_present' => 'boolean',
            'floor_suitable_for_access_equipment' => 'boolean',
            'obstacles_near_work_location' => 'boolean',
            'ventilation_hazardous_emissions' => 'boolean',
            'protection_needed_for_equipment' => 'boolean',
            'safe_access_exit_method' => 'boolean',
            'safe_material_handling_method' => 'boolean',
            'emergency_escape_plan_needed' => 'boolean',
            'other_conditions_check' => 'boolean',
            'other_conditions_text' => 'nullable|string|max:1000',
            
            // Environmental Conditions (single select)
            'visibility_condition' => 'nullable|string|in:terang,remang-remang,gelap,berkabut',
            'rain_condition' => 'nullable|string|in:tidak,rintik,gerimis,deras',
            'surface_condition' => 'nullable|string|in:kering,basah,licin',
            'wind_condition' => 'nullable|string|in:tidak,kecil,sedang,kuat',
            'chemical_spill_condition' => 'nullable|string|in:Ya,Tidak',
            'environment_other_conditions' => 'nullable|string|max:1000',
            
            // Additional Controls
            'additional_controls' => 'nullable|string|max:2000',
        ]);
        
        // Combine date and time fields into datetime
        $validated['start_datetime'] = $validated['start_date'] . ' ' . $validated['start_time'];
        $validated['end_datetime'] = $validated['end_date'] . ' ' . $validated['end_time'];
        
        // Remove the separate date and time fields as they're not needed in database
        unset($validated['start_date'], $validated['start_time'], $validated['end_date'], $validated['end_time']);
        
        $hraWorkAtHeight->update($validated);
        
        return redirect()->route('hra.work-at-heights.show', [$permit, $hraWorkAtHeight])
            ->with('success', 'HRA Work at Heights permit updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PermitToWork $permit, HraWorkAtHeight $hraWorkAtHeight)
    {
        $hraWorkAtHeight->delete();
        
        return redirect()->route('permits.show', $permit)
            ->with('success', 'HRA Work at Heights permit deleted successfully!');
    }

    /**
     * Download HRA as PDF
     */
    public function downloadPdf(PermitToWork $permit, HraWorkAtHeight $hraWorkAtHeight)
    {
        // Load relationships
        $permit->load([
            'permitIssuer', 
            'authorizer', 
            'receiver', 
            'methodStatement'
        ]);
        
        $hraWorkAtHeight->load('user');

        // Generate QR Code for HRA
        $qrUrl = url('/permits/' . $permit->id . '/hra/work-at-heights/' . $hraWorkAtHeight->id);
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

        $pdf = \PDF::loadView('hra.work-at-heights.pdf', compact('permit', 'hraWorkAtHeight', 'qrCode', 'permitQrCode'));
        
        return $pdf->download('hra-' . $hraWorkAtHeight->hra_permit_number . '.pdf');
    }

    /**
     * Request approval for HRA Work at Height
     */
    public function requestApproval(PermitToWork $permit, HraWorkAtHeight $hraWorkAtHeight)
    {
        // Load the locationOwner relationship
        $permit->load('locationOwner');
        
        // Check if user is the creator
        if ($hraWorkAtHeight->user_id !== auth()->id()) {
            return redirect()->route('hra.work-at-heights.show', [$permit, $hraWorkAtHeight])
                ->with('error', 'Only the creator can request approval.');
        }

        // Check if already pending or approved
        if (!in_array($hraWorkAtHeight->approval_status ?? 'draft', ['draft', 'rejected'])) {
            return redirect()->route('hra.work-at-heights.show', [$permit, $hraWorkAtHeight])
                ->with('error', 'Approval has already been requested.');
        }

        // Update status to pending (only EHS approval required)
        $hraWorkAtHeight->update([
            'approval_status' => 'pending',
            'approval_requested_at' => now(),
            'ehs_approval' => 'pending',
        ]);

        // Get Location Owner email for CC
        $locationOwner = $permit->locationOwner;
        $ccEmails = [];
        if ($locationOwner && $locationOwner->email) {
            $ccEmails[] = $locationOwner->email;
        }

        // Get all EHS users (role = bekaert, department = EHS)
        $ehsUsers = User::where('role', 'bekaert')
                        ->where('department', 'EHS')
                        ->pluck('email')
                        ->toArray();

        // Build approval URL
        $approvalUrl = route('hra.work-at-heights.show', [$permit, $hraWorkAtHeight]);

        // Send notification to all EHS users (with Location Owner CCed)
        foreach ($ehsUsers as $ehsEmail) {
            try {
                $mail = Mail::to($ehsEmail);
                if (!empty($ccEmails)) {
                    $mail->cc($ccEmails);
                }
                $mail->send(new HraApprovalRequest(
                    $hraWorkAtHeight,
                    $permit,
                    'Work at Height',
                    $approvalUrl
                ));
                $hraWorkAtHeight->update(['ehs_notified' => true]);
            } catch (\Exception $e) {
                \Log::error('Failed to send HRA Work at Height approval email to EHS: ' . $e->getMessage());
            }
        }

        return redirect()->route('hra.work-at-heights.show', [$permit, $hraWorkAtHeight])
            ->with('success', 'Approval request has been sent to EHS team.');
    }

    /**
     * Process approval/rejection
     */
    public function processApproval(Request $request, PermitToWork $permit, HraWorkAtHeight $hraWorkAtHeight)
    {
        // Load permit with locationOwner
        $permit->load('locationOwner');

        // Only EHS can approve/reject
        $user = auth()->user();
        $isEHS = $user->role === 'bekaert' && $user->department === 'EHS';

        if (!$isEHS) {
            return redirect()->route('hra.work-at-heights.show', [$permit, $hraWorkAtHeight])
                ->with('error', 'Only EHS team members can process this HRA.');
        }

        if (!$hraWorkAtHeight->canBeApproved()) {
            return redirect()->route('hra.work-at-heights.show', [$permit, $hraWorkAtHeight])
                ->with('error', 'This HRA cannot be processed at this time.');
        }

        $action = $request->input('action'); // 'approve' or 'reject'
        $comments = $request->input('comments');

        if ($action === 'approve') {
            // Process EHS approval
            $hraWorkAtHeight->update([
                'ehs_approval' => 'approved',
                'ehs_approved_at' => now(),
                'ehs_approved_by' => $user->id,
                'ehs_comments' => $comments,
                'approval_status' => 'approved',
                'final_approved_at' => now(),
                'status' => 'active',
            ]);

            // Send approval notification to creator (CC Location Owner and EHS)
            $creator = $hraWorkAtHeight->user;
            if ($creator) {
                try {
                    $ccEmails = [];
                    if ($permit->locationOwner) {
                        $ccEmails[] = $permit->locationOwner->email;
                    }
                    $ehsEmails = User::where('role', 'bekaert')
                                    ->where('department', 'EHS')
                                    ->pluck('email')
                                    ->toArray();
                    $ccEmails = array_merge($ccEmails, $ehsEmails);
                    $ccEmails = array_unique(array_filter($ccEmails));

                    $mail = Mail::to($creator->email);
                    if (!empty($ccEmails)) {
                        $mail->cc($ccEmails);
                    }
                    $mail->send(new HraApprovalNotification(
                        $hraWorkAtHeight,
                        $permit,
                        'Work at Height'
                    ));
                } catch (\Exception $e) {
                    \Log::error('Failed to send HRA Work at Height approval notification: ' . $e->getMessage());
                }
            }

            return redirect()->route('hra.work-at-heights.show', [$permit, $hraWorkAtHeight])
                ->with('success', 'HRA Work at Height has been approved by EHS.');

        } else {
            // Process rejection
            $request->validate(['rejection_reason' => 'required|string|min:10']);
            
            $rejectionReason = $request->input('rejection_reason');

            $hraWorkAtHeight->update([
                'ehs_approval' => 'rejected',
                'ehs_approved_at' => now(),
                'ehs_approved_by' => $user->id,
                'ehs_comments' => $rejectionReason,
                'approval_status' => 'rejected',
                'rejection_reason' => $rejectionReason,
                'rejected_at' => now(),
                'rejected_by' => $user->id,
            ]);

            // Send rejection notification to creator (CC Location Owner)
            $creator = $hraWorkAtHeight->user;
            if ($creator) {
                try {
                    $ccEmails = [];
                    if ($permit->locationOwner) {
                        $ccEmails[] = $permit->locationOwner->email;
                    }
                    
                    $mail = Mail::to($creator->email);
                    if (!empty($ccEmails)) {
                        $mail->cc($ccEmails);
                    }
                    $mail->send(new HraRejectionNotification(
                        $hraWorkAtHeight,
                        $permit,
                        'Work at Height',
                        $rejectionReason
                    ));
                } catch (\Exception $e) {
                    \Log::error('Failed to send HRA Work at Height rejection notification: ' . $e->getMessage());
                }
            }

            return redirect()->route('hra.work-at-heights.show', [$permit, $hraWorkAtHeight])
                ->with('info', 'HRA Work at Height has been rejected.');
        }
    }
}

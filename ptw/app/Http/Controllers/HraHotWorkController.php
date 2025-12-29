<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PermitToWork;
use App\Models\HraHotWork;
use App\Models\User;
use Illuminate\Http\Request;

class HraHotWorkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PermitToWork $permit)
    {
        $hras = $permit->hraHotWorks()->with('user')->orderBy('created_at', 'desc')->get();
        return view('hra.hot-works.index', compact('permit', 'hras'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(PermitToWork $permit)
    {
        // Check if main permit has hot_work enabled
        if (!$permit->hot_work) {
            return redirect()->route('permits.show', $permit)
                ->with('error', 'Hot Work is not required for this permit.');
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
        
        return view('hra.hot-works.create', compact('permit', 'users'));
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
            
            // Pre-assessment Questions
            'hot_work_avoidable' => 'nullable|boolean',
            'hot_work_designated_area' => 'nullable|boolean',
            
            // New comprehensive assessment fields
            'flammable_materials_removed' => 'nullable|boolean',
            'flammable_liquids_removed' => 'nullable|boolean',
            'flammable_floors_wetted' => 'nullable|in:0,1,N/A',
            'walls_ceiling_protected' => 'nullable|in:0,1,N/A',
            'floors_swept_clean' => 'nullable|boolean',
            'materials_other_side_removed' => 'nullable|boolean',
            'explosive_atmosphere_removed' => 'nullable|boolean',
            'wall_floor_openings_covered' => 'nullable|in:0,1,N/A',
            'ducts_conveyors_protected' => 'nullable|in:0,1,N/A',
            'fire_risk_prevention_applied' => 'nullable|in:0,1,N/A',
            'equipment_cleaned_flammable' => 'nullable|in:0,1,N/A',
            'containers_emptied_cleaned' => 'nullable|in:0,1,N/A',
            'building_materials_non_flammable' => 'nullable|in:0,1,N/A',
            'flammable_materials_cut_protected' => 'nullable|in:0,1,N/A',
            'ventilation_type' => 'nullable|in:alami,buatan',
            'ventilation_adequate' => 'nullable|boolean',
            'gas_lamps_open_area' => 'nullable|in:0,1,N/A',
            'equipment_installed_monitored' => 'nullable|boolean',
            'workers_notified' => 'nullable|boolean',
            
            // Peralatan Pemadam Api
            'apar_air' => 'nullable|boolean',
            'apar_powder' => 'nullable|boolean',
            'apar_co2' => 'nullable|boolean',
            'apar_foam' => 'nullable|boolean',
            'fire_blanket' => 'nullable|boolean',
            'fire_watch_officer' => 'nullable|boolean',
            'fire_watch_name' => 'nullable|string|max:255',
            'monitoring_sprinkler' => 'nullable|boolean',
            'monitoring_combustible' => 'nullable|boolean',
            'monitoring_distance' => 'nullable|string|max:255',
            'additional_inspection_duration' => 'nullable|in:30min,60min,90min,120min',
            // Emergency Systems validation
            'emergency_call_point' => 'nullable|string|max:255',
            'sprinkler_system_disabled' => 'nullable|boolean',
            'fire_detection_isolated' => 'nullable|boolean',
            'isolation_notified_by' => 'nullable|string|max:255',
            'isolation_notified_when' => 'nullable|string|max:255',
            'reinstatement_notified_by' => 'nullable|string|max:255',
            'reinstatement_notified_when' => 'nullable|string|max:255',
        ]);
        
        // Combine date and time fields into datetime
        $validated['start_datetime'] = $validated['start_date'] . ' ' . $validated['start_time'];
        $validated['end_datetime'] = $validated['end_date'] . ' ' . $validated['end_time'];
        
        // Remove the separate date and time fields as they're not needed in database
        unset($validated['start_date'], $validated['start_time'], $validated['end_date'], $validated['end_time']);
        
        // Generate HRA permit number
        $hraPermitNumber = HraHotWork::generateHraPermitNumber($permit->permit_number);
        
        $validated['hra_permit_number'] = $hraPermitNumber;
        $validated['permit_to_work_id'] = $permit->id;
        $validated['permit_number'] = $permit->permit_number;
        $validated['user_id'] = auth()->id(); // Add current user as creator
        
        $hraPermit = HraHotWork::create($validated);
        
        return redirect()->route('hra.hot-works.show', [$permit, $hraPermit])
            ->with('success', 'HRA Hot Work permit created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PermitToWork $permit, HraHotWork $hraHotWork)
    {
        // Load the locationOwner relationship
        $permit->load('locationOwner');
        
        return view('hra.hot-works.show', compact('permit', 'hraHotWork'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PermitToWork $permit, HraHotWork $hraHotWork)
    {
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
        
        return view('hra.hot-works.edit', compact('permit', 'hraHotWork', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PermitToWork $permit, HraHotWork $hraHotWork)
    {
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
            
            // Pre-assessment Questions  
            'hot_work_avoidable' => 'nullable|boolean',
            'hot_work_designated_area' => 'nullable|boolean',
            
            // New comprehensive assessment fields
            'flammable_materials_removed' => 'nullable|boolean',
            'flammable_liquids_removed' => 'nullable|boolean',
            'flammable_floors_wetted' => 'nullable|in:0,1,N/A',
            'walls_ceiling_protected' => 'nullable|in:0,1,N/A',
            'floors_swept_clean' => 'nullable|boolean',
            'materials_other_side_removed' => 'nullable|boolean',
            'explosive_atmosphere_removed' => 'nullable|boolean',
            'wall_floor_openings_covered' => 'nullable|in:0,1,N/A',
            'ducts_conveyors_protected' => 'nullable|in:0,1,N/A',
            'fire_risk_prevention_applied' => 'nullable|in:0,1,N/A',
            'equipment_cleaned_flammable' => 'nullable|in:0,1,N/A',
            'containers_emptied_cleaned' => 'nullable|in:0,1,N/A',
            'building_materials_non_flammable' => 'nullable|in:0,1,N/A',
            'flammable_materials_cut_protected' => 'nullable|in:0,1,N/A',
            'ventilation_type' => 'nullable|in:alami,buatan',
            'ventilation_adequate' => 'nullable|boolean',
            'gas_lamps_open_area' => 'nullable|in:0,1,N/A',
            'equipment_installed_monitored' => 'nullable|boolean',
            'workers_notified' => 'nullable|boolean',
            
            // Peralatan Pemadam Api
            'apar_air' => 'nullable|boolean',
            'apar_powder' => 'nullable|boolean',
            'apar_co2' => 'nullable|boolean',
            'apar_foam' => 'nullable|boolean',
            'fire_blanket' => 'nullable|boolean',
            'fire_watch_officer' => 'nullable|boolean',
            'fire_watch_name' => 'nullable|string|max:255',
            'monitoring_sprinkler' => 'nullable|boolean',
            'monitoring_combustible' => 'nullable|boolean',
            'monitoring_distance' => 'nullable|string|max:255',
            'additional_inspection_duration' => 'nullable|in:30min,60min,90min,120min',
            // Emergency Systems validation
            'emergency_call_point' => 'nullable|string|max:255',
            'sprinkler_system_disabled' => 'nullable|boolean',
            'fire_detection_isolated' => 'nullable|boolean',
            'isolation_notified_by' => 'nullable|string|max:255',
            'isolation_notified_when' => 'nullable|string|max:255',
            'reinstatement_notified_by' => 'nullable|string|max:255',
            'reinstatement_notified_when' => 'nullable|string|max:255',
        ]);
        
        // Combine date and time fields into datetime
        $validated['start_datetime'] = $validated['start_date'] . ' ' . $validated['start_time'];
        $validated['end_datetime'] = $validated['end_date'] . ' ' . $validated['end_time'];
        
        // Remove the separate date and time fields as they're not needed in database
        unset($validated['start_date'], $validated['start_time'], $validated['end_date'], $validated['end_time']);
        
        $hraHotWork->update($validated);
        
        return redirect()->route('hra.hot-works.show', [$permit, $hraHotWork])
            ->with('success', 'HRA Hot Work permit updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PermitToWork $permit, HraHotWork $hraHotWork)
    {
        $hraHotWork->delete();
        
        return redirect()->route('permits.show', $permit)
            ->with('success', 'HRA Hot Work permit deleted successfully!');
    }

    /**
     * Request approval for HRA Hot Work
     */
    public function requestApproval(PermitToWork $permit, HraHotWork $hraHotWork)
    {
        // Load the locationOwner relationship
        $permit->load('locationOwner');
        
        // Check if already requested approval
        if (!in_array($hraHotWork->approval_status, ['draft', 'rejected'])) {
            return redirect()->back()->with('error', 'Approval can only be requested for draft or rejected HRA.');
        }

        // Update approval status
        $hraHotWork->update([
            'approval_status' => 'pending',
            'approval_requested_at' => now(),
            'area_owner_approval' => 'pending',
            'ehs_approval' => 'pending',
            'area_owner_notified' => false,
            'ehs_notified' => false,
        ]);

        // Send email notification
        try {
            // Get location owner email
            $locationOwner = $permit->locationOwner;
            $areaOwnerEmail = $locationOwner ? $locationOwner->email : null;
            
            // Get all EHS users (role = bekaert, department = EHS)
            $ehsUsers = \App\Models\User::where('role', 'bekaert')
                                      ->where('department', 'EHS')
                                      ->pluck('email')
                                      ->toArray();
            
            // Combine all recipient emails
            $recipients = array_filter(array_merge(
                $areaOwnerEmail ? [$areaOwnerEmail] : [],
                $ehsUsers
            ));
            
            if (!empty($recipients)) {
                \Mail::to($recipients)->send(new \App\Mail\HraApprovalRequest($hraHotWork, $permit));
                
                $hraHotWork->update([
                    'area_owner_notified' => !empty($areaOwnerEmail),
                    'ehs_notified' => !empty($ehsUsers),
                ]);
            }
            
            return redirect()->back()->with('success', 'Approval request sent successfully to Area Owner and EHS Team!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Approval request updated but email notification failed: ' . $e->getMessage());
        }
    }

    /**
     * Show approval form
     */
    public function approve(PermitToWork $permit, HraHotWork $hraHotWork)
    {
        $token = request('token');
        
        if (!in_array($token, ['area_owner', 'ehs'])) {
            abort(400, 'Invalid approval token');
        }

        return view('hra.hot-works.approve', compact('permit', 'hraHotWork', 'token'));
    }

    /**
     * Show rejection form
     */
    public function reject(PermitToWork $permit, HraHotWork $hraHotWork)
    {
        return view('hra.hot-works.reject', compact('permit', 'hraHotWork'));
    }

    /**
     * Process approval or rejection
     */
    public function processApproval(PermitToWork $permit, HraHotWork $hraHotWork)
    {
        $action = request('action'); // 'approve' or 'reject'
        $rejectionReason = request('rejection_reason');
        
        // Load permit with locationOwner
        $permit->load('locationOwner');
        
        if (!$hraHotWork->canBeApproved()) {
            return redirect()->back()->with('error', 'This HRA cannot be processed at this time.');
        }

        // Determine user role for this approval
        $isLocationOwner = $permit->locationOwner && $permit->locationOwner->id === auth()->id();
        $isEHS = auth()->user()->role === 'bekaert' && auth()->user()->department === 'EHS';
        
        if (!$isLocationOwner && !$isEHS) {
            return redirect()->back()->with('error', 'You are not authorized to approve this HRA.');
        }

        if ($action === 'approve') {
            // Process approval based on user role
            if ($isLocationOwner && $hraHotWork->area_owner_approval === 'pending') {
                $hraHotWork->update([
                    'area_owner_approval' => 'approved',
                    'area_owner_approved_at' => now(),
                    'area_owner_approved_by' => auth()->id(),
                ]);
                $approverType = 'Location Owner';
            } elseif ($isEHS && $hraHotWork->ehs_approval === 'pending') {
                $hraHotWork->update([
                    'ehs_approval' => 'approved',
                    'ehs_approved_at' => now(),
                    'ehs_approved_by' => auth()->id(),
                ]);
                $approverType = 'EHS Team';
            } else {
                return redirect()->back()->with('error', 'Your approval is not required or has already been given.');
            }
            
            // Check if fully approved
            $hraHotWork->updateFinalApprovalStatus();
            
            // Send notification email if fully approved
            if ($hraHotWork->approval_status === 'approved') {
                try {
                    // Get HRA creator
                    $hraCreator = $hraHotWork->user;
                    
                    // Get CC recipients: Location Owner and EHS Team
                    $ccRecipients = [];
                    
                    // Add Location Owner to CC
                    if ($permit->locationOwner && $permit->locationOwner->email) {
                        $ccRecipients[] = $permit->locationOwner->email;
                    }
                    
                    // Add EHS Team to CC
                    $ehsUsers = \App\Models\User::where('role', 'bekaert')
                                                ->where('department', 'EHS')
                                                ->pluck('email')
                                                ->toArray();
                    $ccRecipients = array_merge($ccRecipients, $ehsUsers);
                    
                    // Remove duplicates and filter out empty emails
                    $ccRecipients = array_unique(array_filter($ccRecipients));
                    
                    // Send email to HRA creator with CC to Location Owner and EHS
                    if ($hraCreator && $hraCreator->email) {
                        $mail = \Mail::to($hraCreator->email);
                        
                        if (!empty($ccRecipients)) {
                            $mail->cc($ccRecipients);
                        }
                        
                        $mail->send(new \App\Mail\HraApprovalNotification($hraHotWork, $permit));
                    }
                } catch (\Exception $e) {
                    // Log error but don't fail the approval process
                    \Log::error('Failed to send HRA approval notification: ' . $e->getMessage());
                }
            }
            
            return redirect()->route('hra.hot-works.show', [$permit, $hraHotWork])
                ->with('success', "HRA Hot Work approved by {$approverType} successfully!");
                
        } elseif ($action === 'reject') {
            // Process rejection
            if (!$rejectionReason) {
                return redirect()->back()->with('error', 'Rejection reason is required.');
            }
            
            $rejectorType = $isLocationOwner ? 'Location Owner' : 'EHS Team';
            $rejectorName = auth()->user()->name . ' (' . $rejectorType . ')';
            
            // Update HRA status to rejected
            $hraHotWork->update([
                'approval_status' => 'rejected',
                'area_owner_approval' => 'pending',  // Reset approvals for resubmission
                'ehs_approval' => 'pending',
                'area_owner_approved_at' => null,
                'area_owner_approved_by' => null,
                'ehs_approved_at' => null,
                'ehs_approved_by' => null,
                'final_approved_at' => null,
                'rejection_reason' => $rejectionReason,
                'rejected_at' => now(),
                'rejected_by' => auth()->id(),
            ]);
            
            // Send rejection notification email
            try {
                // Get HRA creator
                $hraCreator = $hraHotWork->user;
                
                // Get CC recipients: Location Owner and EHS Team
                $ccRecipients = [];
                
                // Add Location Owner to CC
                if ($permit->locationOwner && $permit->locationOwner->email) {
                    $ccRecipients[] = $permit->locationOwner->email;
                }
                
                // Add EHS Team to CC
                $ehsUsers = \App\Models\User::where('role', 'bekaert')
                                            ->where('department', 'EHS')
                                            ->pluck('email')
                                            ->toArray();
                $ccRecipients = array_merge($ccRecipients, $ehsUsers);
                
                // Remove duplicates and filter out empty emails
                $ccRecipients = array_unique(array_filter($ccRecipients));
                
                // Send email to HRA creator with CC to Location Owner and EHS
                if ($hraCreator && $hraCreator->email) {
                    $mail = \Mail::to($hraCreator->email);
                    
                    if (!empty($ccRecipients)) {
                        $mail->cc($ccRecipients);
                    }
                    
                    $mail->send(new \App\Mail\HraRejectionNotification($hraHotWork, $permit, $rejectionReason, $rejectorName));
                }
            } catch (\Exception $e) {
                // Log error but don't fail the rejection process
                \Log::error('Failed to send HRA rejection notification: ' . $e->getMessage());
            }
            
            return redirect()->route('hra.hot-works.show', [$permit, $hraHotWork])
                ->with('success', "HRA Hot Work rejected by {$rejectorType}. The creator can now edit and resubmit.");
        }

        return redirect()->back()->with('error', 'Invalid action.');
    }

    /**
     * Download HRA Hot Work as PDF
     */
    public function downloadPdf(PermitToWork $permit, HraHotWork $hraHotWork)
    {
        // Only allow download for approved HRA
        if ($hraHotWork->approval_status !== 'approved') {
            return redirect()->back()->with('error', 'PDF can only be downloaded for approved HRA Hot Work.');
        }

        // Load relationships
        $permit->load([
            'permitIssuer', 
            'authorizer', 
            'receiver', 
            'locationOwner'
        ]);
        
        $hraHotWork->load(['user', 'areaOwnerApprovedBy', 'ehsApprovedBy']);

        // Generate QR Code for HRA
        $qrUrl = url('/permits/' . $permit->id . '/hra/hot-works/' . $hraHotWork->id);
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

        $pdf = \PDF::loadView('hra.hot-works.pdf', compact('permit', 'hraHotWork', 'qrCode', 'permitQrCode'));
        
        return $pdf->download('hra-hot-work-' . $hraHotWork->hra_permit_number . '.pdf');
    }
}

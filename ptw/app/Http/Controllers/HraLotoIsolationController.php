<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\HraLotoIsolation;
use App\Models\PermitToWork;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\HraApprovalRequest;
use App\Mail\HraApprovalNotification;
use App\Mail\HraRejectionNotification;

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
            // Pre Isolation
            'pid_reviewed' => 'required|string|in:ya,tidak',
            // Electrical Isolation
            'electrical_enabled' => 'nullable|boolean',
            'electrical_hv_installation' => 'nullable|string|in:ya,tidak',
            'electrical_isolations' => 'nullable|array',
            'electrical_energy_control_method' => 'nullable|string',
            // Mechanical Isolation
            'mechanical_enabled' => 'nullable|boolean',
            'mechanical_gravitasi' => 'nullable|string|in:ya,tidak',
            'mechanical_hidrolik' => 'nullable|string|in:ya,tidak',
            'mechanical_kelembaman' => 'nullable|string|in:ya,tidak',
            'mechanical_spring' => 'nullable|string|in:ya,tidak',
            'mechanical_pneumatik' => 'nullable|string|in:ya,tidak',
            'mechanical_lainnya' => 'nullable|string|max:255',
            'mechanical_isolations' => 'nullable|array',
            'mechanical_energy_control_method' => 'nullable|string',
            // Process Isolation
            'process_enabled' => 'nullable|boolean',
            'process_isolations' => 'nullable|array',
            'process_energy_control_method' => 'nullable|string',
            // Utility Isolation
            'utility_enabled' => 'nullable|boolean',
            'utility_isolations' => 'nullable|array',
            'utility_energy_control_method' => 'nullable|string',
            // Verifikasi Isolasi
            'affected_area' => 'nullable|string|max:255',
            'all_individuals_informed' => 'nullable|string|in:ya,tidak',
            'individual_lototo_required' => 'nullable|string|in:ya,tidak',
            'ptw_issuer_lototo_key' => 'nullable|string|in:ya,tidak',
            // Line Breaking
            'line_content_before' => 'nullable|string|max:255',
            'lb_no_residual_pressure' => 'nullable|string|in:ya,tidak',
            'lb_drain_valve_open' => 'nullable|string|in:ya,tidak',
            'lb_emergency_arrangements' => 'nullable|string|in:ya,tidak',
            'lb_line_isolated' => 'nullable|string|in:ya,tidak',
            'lb_line_empty' => 'nullable|string|in:ya,tidak',
            'lb_line_clean' => 'nullable|string|in:ya,tidak',
            'lb_no_asbestos' => 'nullable|string|in:ya,tidak',
            'lb_pipe_no_support_needed' => 'nullable|string|in:ya,tidak',
            'lb_lototo_drainage' => 'nullable|string|in:ya,tidak',
            'lb_purged_air' => 'nullable|boolean',
            'lb_purged_water' => 'nullable|boolean',
            'lb_purged_n2' => 'nullable|boolean',
            'lb_additional_control' => 'nullable|string',
        ]);
        
        // Combine date and time fields into datetime
        $validated['start_datetime'] = $validated['start_date'] . ' ' . $validated['start_time'];
        $validated['end_datetime'] = $validated['end_date'] . ' ' . $validated['end_time'];
        
        // Remove the separate date and time fields as they're not needed in database
        unset($validated['start_date'], $validated['start_time'], $validated['end_date'], $validated['end_time']);

        // Convert electrical_isolations array to JSON
        if (isset($validated['electrical_isolations'])) {
            $validated['electrical_isolations'] = json_encode($validated['electrical_isolations']);
        }

        // Convert mechanical_isolations array to JSON
        if (isset($validated['mechanical_isolations'])) {
            $validated['mechanical_isolations'] = json_encode($validated['mechanical_isolations']);
        }

        // Convert process_isolations array to JSON
        if (isset($validated['process_isolations'])) {
            $validated['process_isolations'] = json_encode($validated['process_isolations']);
        }

        // Convert utility_isolations array to JSON
        if (isset($validated['utility_isolations'])) {
            $validated['utility_isolations'] = json_encode($validated['utility_isolations']);
        }

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
    public function show(PermitToWork $permit, $hraLotoIsolationId)
    {
        // Find the HRA LOTO record explicitly
        $hraLotoIsolation = HraLotoIsolation::where('id', $hraLotoIsolationId)
            ->where('permit_to_work_id', $permit->id)
            ->firstOrFail();

        // Load relationships if needed
        $hraLotoIsolation->load('user');

        return view('hra.loto-isolations.show', compact('permit', 'hraLotoIsolation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PermitToWork $permit, $hraLotoIsolationId)
    {
        // Find the HRA LOTO record explicitly
        $hraLotoIsolation = HraLotoIsolation::where('id', $hraLotoIsolationId)
            ->where('permit_to_work_id', $permit->id)
            ->firstOrFail();

        return view('hra.loto-isolations.edit', compact('permit', 'hraLotoIsolation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PermitToWork $permit, $hraLotoIsolationId)
    {
        // Find the HRA LOTO record explicitly
        $hraLotoIsolation = HraLotoIsolation::where('id', $hraLotoIsolationId)
            ->where('permit_to_work_id', $permit->id)
            ->firstOrFail();

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
            // Pre Isolation
            'pid_reviewed' => 'required|string|in:ya,tidak',
            // Electrical Isolation
            'electrical_enabled' => 'nullable|boolean',
            'electrical_hv_installation' => 'nullable|string|in:ya,tidak',
            'electrical_isolations' => 'nullable|array',
            'electrical_energy_control_method' => 'nullable|string',
            // Mechanical Isolation
            'mechanical_enabled' => 'nullable|boolean',
            'mechanical_gravitasi' => 'nullable|string|in:ya,tidak',
            'mechanical_hidrolik' => 'nullable|string|in:ya,tidak',
            'mechanical_kelembaman' => 'nullable|string|in:ya,tidak',
            'mechanical_spring' => 'nullable|string|in:ya,tidak',
            'mechanical_pneumatik' => 'nullable|string|in:ya,tidak',
            'mechanical_lainnya' => 'nullable|string|max:255',
            'mechanical_isolations' => 'nullable|array',
            'mechanical_energy_control_method' => 'nullable|string',
            // Process Isolation
            'process_enabled' => 'nullable|boolean',
            'process_isolations' => 'nullable|array',
            'process_energy_control_method' => 'nullable|string',
            // Utility Isolation
            'utility_enabled' => 'nullable|boolean',
            'utility_isolations' => 'nullable|array',
            'utility_energy_control_method' => 'nullable|string',
            // Verifikasi Isolasi
            'affected_area' => 'nullable|string|max:255',
            'all_individuals_informed' => 'nullable|string|in:ya,tidak',
            'individual_lototo_required' => 'nullable|string|in:ya,tidak',
            'ptw_issuer_lototo_key' => 'nullable|string|in:ya,tidak',
            // Line Breaking
            'line_content_before' => 'nullable|string|max:255',
            'lb_no_residual_pressure' => 'nullable|string|in:ya,tidak',
            'lb_drain_valve_open' => 'nullable|string|in:ya,tidak',
            'lb_emergency_arrangements' => 'nullable|string|in:ya,tidak',
            'lb_line_isolated' => 'nullable|string|in:ya,tidak',
            'lb_line_empty' => 'nullable|string|in:ya,tidak',
            'lb_line_clean' => 'nullable|string|in:ya,tidak',
            'lb_no_asbestos' => 'nullable|string|in:ya,tidak',
            'lb_pipe_no_support_needed' => 'nullable|string|in:ya,tidak',
            'lb_lototo_drainage' => 'nullable|string|in:ya,tidak',
            'lb_purged_air' => 'nullable|boolean',
            'lb_purged_water' => 'nullable|boolean',
            'lb_purged_n2' => 'nullable|boolean',
            'lb_additional_control' => 'nullable|string',
        ]);
        
        // Handle datetime combination like in store method
        $validated['start_datetime'] = $validated['start_date'] . ' ' . $validated['start_time'];
        $validated['end_datetime'] = $validated['end_date'] . ' ' . $validated['end_time'];
        
        // Remove separate date/time fields
        unset($validated['start_date'], $validated['start_time'], $validated['end_date'], $validated['end_time']);

        // Convert electrical_isolations array to JSON
        if (isset($validated['electrical_isolations'])) {
            $validated['electrical_isolations'] = json_encode($validated['electrical_isolations']);
        }

        // Convert mechanical_isolations array to JSON
        if (isset($validated['mechanical_isolations'])) {
            $validated['mechanical_isolations'] = json_encode($validated['mechanical_isolations']);
        }

        // Convert process_isolations array to JSON
        if (isset($validated['process_isolations'])) {
            $validated['process_isolations'] = json_encode($validated['process_isolations']);
        }

        // Convert utility_isolations array to JSON
        if (isset($validated['utility_isolations'])) {
            $validated['utility_isolations'] = json_encode($validated['utility_isolations']);
        }

        $hraLotoIsolation->update($validated);

        return redirect()->route('hra.loto-isolations.show', [$permit, $hraLotoIsolation])
                        ->with('success', 'HRA LOTO/Isolation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PermitToWork $permit, $hraLotoIsolationId)
    {
        // Find the HRA LOTO record explicitly
        $hraLotoIsolation = HraLotoIsolation::where('id', $hraLotoIsolationId)
            ->where('permit_to_work_id', $permit->id)
            ->firstOrFail();

        $hraLotoIsolation->delete();

        return redirect()->route('permits.show', $permit)
                        ->with('success', 'HRA LOTO/Isolation deleted successfully.');
    }

    /**
     * Download HRA as PDF
     */
    public function downloadPdf(PermitToWork $permit, $hraLotoIsolationId)
    {
        // Find the HRA LOTO record explicitly
        $hraLotoIsolation = HraLotoIsolation::where('id', $hraLotoIsolationId)
            ->where('permit_to_work_id', $permit->id)
            ->firstOrFail();

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

    /**
     * Request approval for HRA LOTO
     */
    public function requestApproval(PermitToWork $permit, $hraLotoIsolationId)
    {
        $hraLotoIsolation = HraLotoIsolation::where('id', $hraLotoIsolationId)
            ->where('permit_to_work_id', $permit->id)
            ->firstOrFail();

        // Check if user is the creator
        if ($hraLotoIsolation->user_id !== auth()->id()) {
            return redirect()->route('hra.loto-isolations.show', [$permit, $hraLotoIsolation])
                ->with('error', 'Only the creator can request approval.');
        }

        // Check if already pending or approved
        if ($hraLotoIsolation->approval_status !== 'draft') {
            return redirect()->route('hra.loto-isolations.show', [$permit, $hraLotoIsolation])
                ->with('error', 'Approval has already been requested.');
        }

        // Update status to pending (only EHS approval required)
        $hraLotoIsolation->update([
            'approval_status' => 'pending',
            'approval_requested_at' => now(),
            'area_owner_approval' => null, // Location Owner is only CCed, not required to approve
            'ehs_approval' => 'pending',
        ]);

        // Get Location Owner email for CC
        $locationOwner = $permit->locationOwner;
        $locationOwnerEmail = $locationOwner ? $locationOwner->email : null;

        // Get all EHS users (role = bekaert, department = EHS)
        $ehsUsers = User::where('role', 'bekaert')
                        ->where('department', 'EHS')
                        ->pluck('email')
                        ->toArray();

        // Build approval URL
        $approvalUrl = route('hra.loto-isolations.approve', [$permit, $hraLotoIsolation]);

        // Prepare CC list (Location Owner)
        $ccEmails = [];
        if ($locationOwnerEmail) {
            $ccEmails[] = $locationOwnerEmail;
        }

        // Send notification to all EHS users (with Location Owner CCed)
        foreach ($ehsUsers as $ehsEmail) {
            try {
                $mail = Mail::to($ehsEmail);
                if (!empty($ccEmails)) {
                    $mail->cc($ccEmails);
                }
                $mail->send(new HraApprovalRequest(
                    $hraLotoIsolation,
                    $permit,
                    'LOTO/Isolation',
                    $approvalUrl
                ));
                $hraLotoIsolation->update(['ehs_notified' => true]);
            } catch (\Exception $e) {
                \Log::error('Failed to send HRA LOTO approval email to EHS: ' . $e->getMessage());
            }
        }

        return redirect()->route('hra.loto-isolations.show', [$permit, $hraLotoIsolation])
            ->with('success', 'Approval request has been sent to EHS team.');
    }

    /**
     * Show approval form
     */
    public function approve(PermitToWork $permit, $hraLotoIsolationId)
    {
        $hraLotoIsolation = HraLotoIsolation::where('id', $hraLotoIsolationId)
            ->where('permit_to_work_id', $permit->id)
            ->firstOrFail();

        // Check if user can approve (only EHS team can approve)
        $user = auth()->user();
        $isLocationOwner = false; // Location Owner is only CCed, not approver
        $isEHS = $user->role === 'bekaert' && $user->department === 'EHS';

        if (!$isEHS) {
            return redirect()->route('hra.loto-isolations.show', [$permit, $hraLotoIsolation])
                ->with('error', 'Only EHS team members can approve this HRA.');
        }

        if ($hraLotoIsolation->approval_status !== 'pending') {
            return redirect()->route('hra.loto-isolations.show', [$permit, $hraLotoIsolation])
                ->with('error', 'This HRA is not pending approval.');
        }

        return view('hra.loto-isolations.approve', compact('permit', 'hraLotoIsolation', 'isLocationOwner', 'isEHS'));
    }

    /**
     * Show rejection form
     */
    public function reject(PermitToWork $permit, $hraLotoIsolationId)
    {
        $hraLotoIsolation = HraLotoIsolation::where('id', $hraLotoIsolationId)
            ->where('permit_to_work_id', $permit->id)
            ->firstOrFail();

        // Check if user can reject (only EHS team can reject)
        $user = auth()->user();
        $isLocationOwner = false; // Location Owner is only CCed, not approver
        $isEHS = $user->role === 'bekaert' && $user->department === 'EHS';

        if (!$isEHS) {
            return redirect()->route('hra.loto-isolations.show', [$permit, $hraLotoIsolation])
                ->with('error', 'Only EHS team members can reject this HRA.');
        }

        if ($hraLotoIsolation->approval_status !== 'pending') {
            return redirect()->route('hra.loto-isolations.show', [$permit, $hraLotoIsolation])
                ->with('error', 'This HRA is not pending approval.');
        }

        return view('hra.loto-isolations.reject', compact('permit', 'hraLotoIsolation', 'isLocationOwner', 'isEHS'));
    }

    /**
     * Process approval/rejection
     */
    public function processApproval(Request $request, PermitToWork $permit, $hraLotoIsolationId)
    {
        $hraLotoIsolation = HraLotoIsolation::where('id', $hraLotoIsolationId)
            ->where('permit_to_work_id', $permit->id)
            ->firstOrFail();

        $user = auth()->user();
        $isLocationOwner = false; // Location Owner is only CCed, not approver
        $isEHS = $user->role === 'bekaert' && $user->department === 'EHS';

        if (!$isEHS) {
            return redirect()->route('hra.loto-isolations.show', [$permit, $hraLotoIsolation])
                ->with('error', 'Only EHS team members can process this HRA.');
        }

        $action = $request->input('action'); // 'approve' or 'reject'
        $comments = $request->input('comments');

        if ($action === 'approve') {
            // Process EHS approval
            if ($isEHS && $hraLotoIsolation->ehs_approval === 'pending') {
                $hraLotoIsolation->update([
                    'ehs_approval' => 'approved',
                    'ehs_approved_at' => now(),
                    'ehs_approved_by' => $user->id,
                    'ehs_comments' => $comments,
                ]);
            }

            // Refresh model
            $hraLotoIsolation->refresh();

            // EHS approval is the only required approval, so mark as fully approved
            $hraLotoIsolation->update([
                'approval_status' => 'approved',
                'final_approved_at' => now(),
                'status' => 'active',
            ]);

            // Send approval notification to creator (CC Location Owner and EHS)
            $creator = $hraLotoIsolation->user;
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

                    Mail::to($creator->email)
                        ->cc($ccEmails)
                        ->send(new HraApprovalNotification(
                                $hraLotoIsolation,
                                $permit,
                                'LOTO/Isolation'
                            ));
                    } catch (\Exception $e) {
                        \Log::error('Failed to send HRA LOTO approval notification: ' . $e->getMessage());
                    }
                }

            return redirect()->route('hra.loto-isolations.show', [$permit, $hraLotoIsolation])
                ->with('success', 'HRA LOTO/Isolation has been approved by EHS.');

        } else {
            // Process rejection
            $request->validate(['comments' => 'required|string|min:10']);

            // EHS rejection
            $hraLotoIsolation->update([
                'ehs_approval' => 'rejected',
                'ehs_approved_at' => now(),
                'ehs_approved_by' => $user->id,
                'ehs_comments' => $comments,
                'approval_status' => 'rejected',
                'rejection_reason' => $comments,
                'rejected_at' => now(),
                'rejected_by' => $user->id,
            ]);

            // Send rejection notification to creator (CC Location Owner)
            $creator = $hraLotoIsolation->user;
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
                        $hraLotoIsolation,
                        $permit,
                        'LOTO/Isolation',
                        $comments
                    ));
                } catch (\Exception $e) {
                    \Log::error('Failed to send HRA LOTO rejection notification: ' . $e->getMessage());
                }
            }

            return redirect()->route('hra.loto-isolations.show', [$permit, $hraLotoIsolation])
                ->with('info', 'HRA LOTO/Isolation has been rejected.');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PermitToWork;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\PermitApprovalRequest;
use App\Mail\PermitApprovalResult;
use App\Mail\PermitExtensionRequest;
use PDF;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class PermitToWorkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Update expired permits before displaying list
        PermitToWork::updateExpiredPermits();
        
        $query = PermitToWork::with(['user', 'permitIssuer', 'authorizer', 'receiver']);
        
        // Filter by status
        if ($request->filled('status')) {
            $status = $request->get('status');
            if ($status === 'pending') {
                $query->where('status', 'pending_approval');
            } else {
                $query->where('status', $status);
            }
        }
        
        // Search by work title, location, permit number, or creator name
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('work_title', 'like', '%' . $search . '%')
                  ->orWhere('work_location', 'like', '%' . $search . '%')
                  ->orWhere('permit_number', 'like', '%' . $search . '%')
                  ->orWhere('department', 'like', '%' . $search . '%')
                  ->orWhereHas('permitIssuer', function($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        // Filter by work date (permits that are active on this date)
        if ($request->filled('work_date')) {
            $workDate = $request->get('work_date');
            $query->where(function($q) use ($workDate) {
                $q->whereDate('start_date', '<=', $workDate)
                  ->whereDate('end_date', '>=', $workDate);
            });
        }        $permits = $query->latest()->paginate(15)->withQueryString();

        return view('permits.index', compact('permits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('role', '!=', 'contractor')->get();
        $contractors = User::where('role', 'contractor')->with('company')->get();
        $bekaertUsers = User::where('role', 'bekaert')->select('id', 'name', 'email')->orderBy('name')->get();
        return view('permits.create', compact('users', 'contractors', 'bekaertUsers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'work_title' => 'nullable|string|max:255',
            'department' => 'required|string|max:255', 
            'location' => 'nullable|string|max:255',
            'work_location' => 'required|string|max:255',
            'location_owner_id' => 'nullable|exists:users,id',
            'equipment_tools' => 'required|string',
            'work_description' => 'nullable|string',
            'responsible_person' => 'required|string|max:255',
            'responsible_person_email' => 'nullable|email|max:255',
            'receiver_name' => 'nullable|string|max:255',
            'receiver_email' => 'nullable|email|max:255',
            'receiver_company_name' => 'nullable|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'authorizer_id' => 'nullable|exists:users,id',
            'risk_method_assessment' => 'required|in:ya,tidak',
            'chemical_usage_storage' => 'required|in:ya,tidak',
            'equipment_condition' => 'required|in:ya,tidak',
            'asbestos_presence' => 'required|in:ya,tidak',
            'atex_area' => 'required|in:ya,tidak',
            'gas_storage_area' => 'required|in:ya,tidak',
        ]);

        // Additional validation: end_date should not be more than 4 days after start_date (max 5 days total)
        $request->validate([
            'end_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    $startDate = \Carbon\Carbon::parse($request->start_date);
                    $endDate = \Carbon\Carbon::parse($value);
                    $maxEndDate = $startDate->copy()->addDays(4); // 4 days after = 5 days total including start date
                    
                    if ($endDate->gt($maxEndDate)) {
                        $fail('Tanggal selesai maksimal 5 hari termasuk tanggal mulai.');
                    }
                },
            ],
        ]);

        // Generate unique permit number
        $validated['permit_number'] = $this->generateUniquePermitNumber();
        
        $validated['permit_issuer_id'] = Auth::id();
        $validated['responsible_person_email'] = Auth::user()->email;
        $validated['work_at_heights'] = $request->boolean('work_at_heights');
        $validated['hot_work'] = $request->boolean('hot_work');
        $validated['loto_isolation'] = $request->boolean('loto_isolation');
        $validated['line_breaking'] = $request->boolean('line_breaking');
        $validated['excavation'] = $request->boolean('excavation');
        $validated['confined_spaces'] = $request->boolean('confined_spaces');
        $validated['explosive_atmosphere'] = $request->boolean('explosive_atmosphere');
        $validated['form_y_n'] = $request->form_y_n;
        $validated['form_detail'] = $request->form_detail;

        $permit = PermitToWork::create($validated);

        // Check if user wants to submit for approval immediately
        if ($request->has('submit_for_approval')) {
            $permit->update([
                'status' => 'pending_approval',
                'issued_at' => now(),
            ]);
            return redirect()->route('permits.show', $permit)->with('success', 'Permit created and submitted for approval successfully!');
        }

        return redirect()->route('permits.show', $permit)->with('success', 'Permit created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PermitToWork $permit)
    {
        // Check and update if permit has expired
        if ($permit->isExpired()) {
            $permit->update(['status' => 'expired']);
        }
        
        $permit->load(['permitIssuer', 'authorizer', 'receiver', 'locationOwner', 'methodStatement.creator', 'emergencyPlan.creator', 'riskAssessments']);
        return view('permits.show', compact('permit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PermitToWork $permit)
    {
        //if (!in_array($permit->status, ['draft', 'rejected'])) {
        //    return redirect()->route('permits.show', $permit)
        //        ->with('error', 'Only draft and rejected permits can be edited.');
        //}

        // Check if user is the permit creator or administrator
        //if (Auth::id() !== $permit->permit_issuer_id && Auth::user()->role !== 'administrator') {
        //    return redirect()->route('permits.show', $permit)
        //        ->with('error', 'You do not have permission to edit this permit.');
        //}

        $users = User::where('role', '!=', 'contractor')->get();
        $contractors = User::where('role', 'contractor')->with('company')->get();
        $bekaertUsers = User::where('role', 'bekaert')->select('id', 'name', 'email')->orderBy('name')->get();
        return view('permits.edit', compact('permit', 'users', 'contractors', 'bekaertUsers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PermitToWork $permit)
    {
        // Debug logging
        \Log::info('Update attempt', [
            'permit_id' => $permit->id,
            'permit_status' => $permit->status,
            'user_id' => Auth::id(),
            'permit_issuer_id' => $permit->permit_issuer_id,
            'user_role' => Auth::user()->role ?? 'guest',
            'request_data' => $request->except(['_token', '_method'])
        ]);

        // Enhanced validation with better debugging
        $validStatuses = ['draft', 'rejected'];
        if (!in_array($permit->status, $validStatuses)) {
            \Log::warning('Update blocked: wrong status', [
                'current_status' => $permit->status,
                'valid_statuses' => $validStatuses
            ]);
            return redirect()->route('permits.show', $permit)
                ->with('error', "Only permits with status 'draft' or 'rejected' can be updated. Current status: {$permit->status}");
        }

        // Check if user is the permit creator or administrator
        $currentUserId = Auth::id();
        $currentUserRole = Auth::user()->role ?? 'unknown';
        $canEdit = ($currentUserId == $permit->permit_issuer_id) || ($currentUserRole === 'administrator');
        
        if (!$canEdit) {
            \Log::warning('Update blocked: no permission', [
                'user_id' => $currentUserId,
                'permit_issuer_id' => $permit->permit_issuer_id,
                'user_role' => $currentUserRole,
                'can_edit' => $canEdit
            ]);
            return redirect()->route('permits.show', $permit)
                ->with('error', "You do not have permission to update this permit. User: {$currentUserId}, Creator: {$permit->permit_issuer_id}, Role: {$currentUserRole}");
        }

        $validated = $request->validate([
            'work_title' => 'nullable|string|max:255',
            'department' => 'required|string|max:255', 
            'location' => 'nullable|string|max:255',
            'work_location' => 'required|string|max:255',
            'location_owner_id' => 'nullable|exists:users,id',
            'equipment_tools' => 'required|string',
            'work_description' => 'nullable|string',
            'responsible_person' => 'required|string|max:255',
            'responsible_person_email' => 'nullable|email|max:255',
            'receiver_name' => 'nullable|string|max:255',
            'receiver_email' => 'nullable|email|max:255',
            'receiver_company_name' => 'nullable|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'authorizer_id' => 'nullable|exists:users,id',
            'risk_method_assessment' => 'required|in:ya,tidak',
            'chemical_usage_storage' => 'required|in:ya,tidak',
            'equipment_condition' => 'required|in:ya,tidak',
            'asbestos_presence' => 'required|in:ya,tidak',
            'atex_area' => 'required|in:ya,tidak',
            'gas_storage_area' => 'required|in:ya,tidak',
        ]);

        // Additional validation: end_date should not be more than 4 days after start_date (max 5 days total)
        $request->validate([
            'end_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    $startDate = \Carbon\Carbon::parse($request->start_date);
                    $endDate = \Carbon\Carbon::parse($value);
                    $maxEndDate = $startDate->copy()->addDays(4); // 4 days after = 5 days total including start date
                    
                    if ($endDate->gt($maxEndDate)) {
                        $fail('Tanggal selesai maksimal 5 hari termasuk tanggal mulai.');
                    }
                },
            ],
        ]);

        $validated['work_at_heights'] = $request->boolean('work_at_heights');
        $validated['hot_work'] = $request->boolean('hot_work');
        $validated['loto_isolation'] = $request->boolean('loto_isolation');
        $validated['line_breaking'] = $request->boolean('line_breaking');
        $validated['excavation'] = $request->boolean('excavation');
        $validated['confined_spaces'] = $request->boolean('confined_spaces');
        $validated['explosive_atmosphere'] = $request->boolean('explosive_atmosphere');
        $validated['form_y_n'] = $request->form_y_n;
        $validated['form_detail'] = $request->form_detail;

        // Debug: Log data before update
        \Log::info('Data to update', ['validated' => $validated]);
        
        try {
            $beforeUpdate = $permit->toArray();
            \Log::info('Before update', ['data' => $beforeUpdate]);
            
            // Try updating
            $updateResult = $permit->update($validated);
            \Log::info('Update method result', ['result' => $updateResult]);
            
            $afterUpdate = $permit->fresh()->toArray();
            \Log::info('After update', ['data' => $afterUpdate]);
            
            // Check if data actually changed
            $changes = [];
            foreach ($validated as $key => $value) {
                if (isset($beforeUpdate[$key]) && $beforeUpdate[$key] != $value) {
                    $changes[$key] = [
                        'from' => $beforeUpdate[$key],
                        'to' => $value
                    ];
                }
            }
            
            \Log::info('Changes detected', ['changes' => $changes]);
            
        } catch (\Exception $e) {
            \Log::error('Update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('permits.show', $permit)
                ->with('error', 'Update failed: ' . $e->getMessage());
        }

        return redirect()->route('permits.show', $permit)->with('success', 'Permit updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PermitToWork $permit)
    {
        if ($permit->status !== 'draft') {
            return redirect()->route('permits.index')
                ->with('error', 'Only draft permits can be deleted.');
        }

        $permit->delete();

        return redirect()->route('permits.index')->with('success', 'Permit deleted successfully!');
    }

    /**
     * Submit permit for approval
     */
    public function submit(PermitToWork $permit)
    {
        if ($permit->status !== 'draft') {
            return redirect()->route('permits.show', $permit)
                ->with('error', 'Only draft permits can be submitted.');
        }

        $permit->update([
            'status' => 'pending_approval',
            'issued_at' => now(),
        ]);

        return redirect()->route('permits.show', $permit)
            ->with('success', 'Permit submitted for approval successfully!');
    }

    /**
     * Approve permit
     */
    public function approve(PermitToWork $permit)
    {
        if (!in_array($permit->status, ['pending_approval', 'resubmitted'])) {
            return redirect()->route('permits.show', $permit)
                ->with('error', 'Only pending or resubmitted permits can be approved.');
        }

        // Only bekaert users with EHS department can approve
        if (Auth::user()->role !== 'bekaert' || Auth::user()->department !== 'EHS') {
            return redirect()->route('permits.show', $permit)
                ->with('error', 'You do not have permission to approve permits. Only EHS users can approve permits.');
        }

        // Also approve method statement if exists and not already approved
        if ($permit->methodStatement && $permit->methodStatement->status !== 'approved') {
            $permit->methodStatement->update(['status' => 'approved']);
        }

        $permit->update([
            'status' => 'active',
            'authorizer_id' => Auth::id(),
            'authorized_at' => now(),
        ]);
        
        // Send notification email to permit creator, CC location owner if exists
        $creatorEmail = $permit->user->email ?? null;
        $creatorName = $permit->user->name ?? '';
        $permit->created_by_name = $creatorName;
        $ccEmail = null;
        if ($permit->location_owner_id) {
            $locationOwner = \App\Models\User::find($permit->location_owner_id);
            if ($locationOwner && $locationOwner->email) {
                $ccEmail = $locationOwner->email;
            }
        }
        if ($creatorEmail) {
            \Mail::to($creatorEmail)
                ->cc($ccEmail)
                ->send(new \App\Mail\PermitApprovalResult($permit, 'approved'));
        }

        return redirect()->route('permits.show', $permit)
            ->with('success', 'Permit approved and activated successfully! Method Statement has also been approved.');
    }

    /**
     * Reject permit
     */
    public function reject(Request $request, PermitToWork $permit)
    {
        if (!in_array($permit->status, ['pending_approval', 'resubmitted'])) {
            return redirect()->route('permits.show', $permit)
                ->with('error', 'Only pending or resubmitted permits can be rejected.');
        }

        // Only bekaert users with EHS department can reject
        if (Auth::user()->role !== 'bekaert' || Auth::user()->department !== 'EHS') {
            return redirect()->route('permits.show', $permit)
                ->with('error', 'You do not have permission to reject permits. Only EHS users can reject permits.');
        }

        // Validate rejection reason
        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:10|max:1000'
        ]);

        $permit->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'rejected_at' => now(),
            'rejected_by' => Auth::id(),
            'authorizer_id' => null,
            'authorized_at' => null,
        ]);
        
        // Send notification email to permit creator
        $creatorEmail = $permit->user->email ?? null;
        $creatorName = $permit->user->name ?? '';
        $permit->created_by_name = $creatorName;
        if ($creatorEmail) {
            \Mail::to($creatorEmail)->send(new \App\Mail\PermitApprovalResult($permit, 'rejected', $validated['rejection_reason']));
        }

        return redirect()->route('permits.show', $permit)
            ->with('warning', 'Permit rejected successfully. The reason has been recorded and the permit creator can edit and resubmit.');
    }

    /**
     * Resubmit rejected permit for approval
     */
    public function resubmit(PermitToWork $permit)
    {
        // Check if permit is rejected
        //if ($permit->status !== 'rejected') {
        //    return redirect()->route('permits.show', $permit)
        //        ->with('error', 'Only rejected permits can be resubmitted.');
        //}

        // Check if user is the permit creator
        //if (Auth::id() !== $permit->permit_issuer_id) {
        //    return redirect()->route('permits.show', $permit)
        //        ->with('error', 'Only the permit creator can resubmit a rejected permit.');
        //}

        $permit->update([
            'status' => 'pending_approval',
            'rejection_reason' => null,
            'rejected_at' => null,
            'rejected_by' => null,
            'issued_at' => now(),
        ]);

        // Send email to all EHS users (same as request approval)
        $ehsUsers = \App\Models\User::where('role', 'bekaert')
            ->where('department', 'EHS')
            ->get();
        $ehsEmails = $ehsUsers->pluck('email')->filter()->unique()->toArray();
        if (count($ehsEmails) > 0) {
            \Mail::to($ehsEmails)->send(new \App\Mail\PermitApprovalRequest($permit));
        }

        return redirect()->route('permits.show', $permit)
            ->with('success', 'Permit resubmitted for approval successfully!');
    }

    /**
     * Generate unique permit number with format PTW-YYYY-MM-XXXX
     */
    private function generateUniquePermitNumber()
    {
        $year = date('Y');
        $month = date('m');
        $prefix = "PTW-{$year}-{$month}-";
        
        // Get the latest permit number for current year and month
        $latestPermit = PermitToWork::where('permit_number', 'LIKE', $prefix . '%')
            ->orderBy('permit_number', 'desc')
            ->first();
        
        if ($latestPermit) {
            // Extract sequence number from latest permit
            $latestSequence = (int) substr($latestPermit->permit_number, -4);
            $newSequence = $latestSequence + 1;
        } else {
            // First permit for this year-month
            $newSequence = 1;
        }
        
        // Format sequence with leading zeros
        $sequence = str_pad($newSequence, 4, '0', STR_PAD_LEFT);
        $permitNumber = $prefix . $sequence;
        
        // Double check for uniqueness (edge case protection)
        while (PermitToWork::where('permit_number', $permitNumber)->exists()) {
            $newSequence++;
            $sequence = str_pad($newSequence, 4, '0', STR_PAD_LEFT);
            $permitNumber = $prefix . $sequence;
        }
        
        return $permitNumber;
    }

    /**
     * Request approval for permit
     */
    public function requestApproval(PermitToWork $permit)
    {
        // Check if user is the permit creator
        if (Auth::id() !== $permit->permit_issuer_id) {
            return redirect()->back()
                ->with('error', 'Access denied. Only the permit creator can request approval.');
        }

        // Check if permit can be submitted for approval
        if (!in_array($permit->status, ['draft', 'rejected', 'resubmitted'])) {
            return redirect()->back()
                ->with('error', 'This permit cannot be submitted for approval in its current status.');
        }

        // Check if method statement exists and is completed
        if (!$permit->methodStatement || $permit->methodStatement->status !== 'completed') {
            return redirect()->back()
                ->with('error', 'Method Statement must be completed before requesting approval.');
        }

        // Check if emergency plan exists and is completed
        if (!$permit->emergencyPlan || $permit->emergencyPlan->status !== 'completed') {
            return redirect()->back()
                ->with('error', 'Emergency & Escape Plan must be completed before requesting approval.');
        }

        try {
            // Update permit status
            $permit->update([
                'status' => 'pending_approval'
            ]);

            // Get all EHS users
            $ehsUsers = User::where('role', 'bekaert')
                          ->where('department', 'EHS')
                          ->get();
            $ehsEmails = $ehsUsers->pluck('email')->filter()->unique()->toArray();
            if (count($ehsEmails) > 0) {
                Mail::to($ehsEmails)->send(new PermitApprovalRequest($permit));
            }

            $ehsCount = $ehsUsers->count();
            return redirect()->route('permits.show', $permit)
                ->with('success', "Approval request sent successfully! {$ehsCount} EHS member(s) have been notified via email.");

        } catch (\Exception $e) {
            // Rollback status change if email failed
            $permit->update(['status' => 'draft']);
            
            return redirect()->back()
                ->with('error', 'Failed to send approval request. Please try again later. Error: ' . $e->getMessage());
        }
    }

    /**
     * Mark permit as completed
     */
    public function complete(Request $request, PermitToWork $permit)
    {
        // Add logging for debugging
        \Log::info('Complete request started', [
            'permit_id' => $permit->id,
            'permit_status' => $permit->status,
            'user_id' => auth()->id(),
            'permit_issuer_id' => $permit->permit_issuer_id,
            'user_role' => auth()->user()->role ?? 'unknown'
        ]);

        try {
            // Simplified authorization check
            $userId = auth()->id();
            $userRole = auth()->user()->role ?? '';
            $isPermitIssuer = ($userId == $permit->permit_issuer_id);
            $isAdmin = ($userRole === 'administrator');
            
            \Log::info('Complete authorization check', [
                'user_id' => $userId,
                'permit_issuer_id' => $permit->permit_issuer_id,
                'is_permit_issuer' => $isPermitIssuer,
                'user_role' => $userRole,
                'is_admin' => $isAdmin
            ]);

            if (!$isPermitIssuer && !$isAdmin) {
                \Log::warning('Complete request unauthorized', [
                    'user_id' => $userId,
                    'permit_issuer_id' => $permit->permit_issuer_id,
                    'user_role' => $userRole
                ]);
                
                return redirect()->back()
                    ->with('error', 'You do not have permission to complete this permit.');
            }

            // Check permit status
            $validStatuses = ['active', 'expired'];
            if (!in_array($permit->status, $validStatuses)) {
                \Log::warning('Complete request invalid status', [
                    'permit_status' => $permit->status,
                    'valid_statuses' => $validStatuses
                ]);
                
                return redirect()->back()
                    ->with('error', 'Only active or expired permits can be marked as completed.');
            }

            // Validate completion form data
            $validated = $request->validate([
                'work_status' => 'required|in:selesai,belum_selesai',
                'work_status_detail' => 'required|string|min:10|max:1000',
                'area_installation_status' => 'required|in:siap_dioperasikan,belum_siap',
                'area_installation_detail' => 'required|string|min:10|max:1000',
            ], [
                'work_status_detail.required' => 'Detail status pekerjaan wajib diisi.',
                'work_status_detail.min' => 'Detail status pekerjaan minimal 10 karakter.',
                'area_installation_detail.required' => 'Detail status area/instalasi/peralatan wajib diisi.',
                'area_installation_detail.min' => 'Detail status area/instalasi/peralatan minimal 10 karakter.',
            ]);

            \Log::info('Updating permit to completed', [
                'permit_id' => $permit->id,
                'current_status' => $permit->status,
                'completion_data' => $validated
            ]);

            // Update permit status to completed with completion details
            $permit->update([
                'status' => 'completed',
                'work_status' => $validated['work_status'],
                'work_status_detail' => $validated['work_status_detail'],
                'area_installation_status' => $validated['area_installation_status'],
                'area_installation_detail' => $validated['area_installation_detail'],
                'completed_at' => now(),
                'completed_by' => auth()->id()
            ]);

            \Log::info('Complete request successful', [
                'permit_id' => $permit->id,
                'new_status' => 'completed'
            ]);

            return redirect()->route('permits.show', $permit)
                ->with('success', 'Permit has been marked as completed successfully.');

        } catch (\Exception $e) {
            \Log::error('Complete request failed', [
                'permit_id' => $permit->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to complete permit. Please try again.');
        }
    }

    /**
     * Extend an expired permit
     */
    public function extend(Request $request, PermitToWork $permit)
    {
        // Add logging for debugging
        \Log::info('Extension request started', [
            'permit_id' => $permit->id,
            'permit_status' => $permit->status,
            'user_id' => auth()->id(),
            'permit_issuer_id' => $permit->permit_issuer_id,
            'user_role' => auth()->user()->role ?? 'unknown'
        ]);

        // Simplified permission check
        $isPermitCreator = (auth()->id() == $permit->permit_issuer_id);
        $isAdmin = (auth()->user()->role === 'administrator');
        
        if (!$isPermitCreator && !$isAdmin) {
            \Log::warning('Extension denied - permission', [
                'user_id' => auth()->id(),
                'permit_issuer_id' => $permit->permit_issuer_id,
                'user_role' => auth()->user()->role
            ]);
            return redirect()->back()
                ->with('error', 'You do not have permission to extend this permit.');
        }

        // Check if permit is expired
        if ($permit->status !== 'expired') {
            \Log::warning('Extension denied - status', [
                'permit_status' => $permit->status,
                'required' => 'expired'
            ]);
            return redirect()->back()
                ->with('error', 'Only expired permits can be extended.');
        }

        // Simplified validation - calculate max date properly
        $originalEndDate = \Carbon\Carbon::parse($permit->end_date);
        $maxExtensionDate = $originalEndDate->copy()->addDays(5);
        
        $validated = $request->validate([
            'end_date' => [
                'required', 
                'date',
                'after:' . $originalEndDate->format('Y-m-d'),
                'before_or_equal:' . $maxExtensionDate->format('Y-m-d')
            ],
            'extension_reason' => 'required|string|max:500'
        ]);
        
        \Log::info('Extension validation passed', $validated);

        // Store original end date for email
        $originalEndDate = $permit->end_date;
        
        \Log::info('Updating permit for extension', [
            'permit_id' => $permit->id,
            'new_end_date' => $validated['end_date'],
            'original_end_date' => $originalEndDate->format('Y-m-d')
        ]);
        
        // Update permit for extension request
        $permit->update([
            'end_date' => $validated['end_date'],
            'status' => 'pending_extension_approval',
            'extension_reason' => $validated['extension_reason'],
            'extended_at' => now(),
            'extended_by' => auth()->id()
        ]);

        \Log::info('Permit updated successfully', [
            'permit_id' => $permit->id,
            'new_status' => $permit->fresh()->status
        ]);

        // Try to send email notification, but don't fail if email fails
        try {
            $ehsUsers = \App\Models\User::where('role', 'bekaert')
                              ->where('department', 'EHS')
                              ->get();
            
            $ehsEmails = $ehsUsers->pluck('email')->filter()->unique()->toArray();
            
            if (count($ehsEmails) > 0) {
                \Mail::to($ehsEmails)->send(new \App\Mail\PermitExtensionRequest($permit, $originalEndDate, $validated['end_date']));
                \Log::info('Extension request email sent successfully', ['to' => $ehsEmails]);
            }
            
            $ehsCount = $ehsUsers->count();
            $message = "Extension request submitted successfully!";
            if ($ehsCount > 0) {
                $message .= " {$ehsCount} EHS member(s) have been notified via email for approval.";
            }
            
        } catch (\Exception $emailError) {
            \Log::error('Extension email failed but permit updated: ' . $emailError->getMessage());
            $message = "Extension request submitted successfully! (Email notification failed but request is recorded)";
        }

        return redirect()->route('permits.show', $permit)
            ->with('success', $message);
    }

    /**
     * Approve extension request (EHS only)
     */
    public function approveExtension(PermitToWork $permit)
    {
        // Check if permit is in pending extension approval status
        if ($permit->status !== 'pending_extension_approval') {
            return redirect()->route('permits.show', $permit)
                ->with('error', 'Only permits with pending extension approval can be approved.');
        }

        // Only bekaert users with EHS department can approve extensions
        if (auth()->user()->role !== 'bekaert' || auth()->user()->department !== 'EHS') {
            return redirect()->route('permits.show', $permit)
                ->with('error', 'You do not have permission to approve extensions. Only EHS users can approve extensions.');
        }

        try {
            // Approve the extension - change status back to active
            $permit->update([
                'status' => 'active',
                'authorizer_id' => auth()->id(),
                'authorized_at' => now(),
            ]);

            // Send notification email to permit creator
            $creatorEmail = $permit->permitIssuer->email ?? null;
            \Log::info('Sending extension approval email', [
                'permit_id' => $permit->id,
                'creator_email' => $creatorEmail
            ]);
            
            if ($creatorEmail) {
                try {
                    \Mail::to($creatorEmail)->send(new \App\Mail\PermitApprovalResult($permit, true, 'extension'));
                    \Log::info('Extension approval email sent successfully', [
                        'permit_id' => $permit->id,
                        'to' => $creatorEmail
                    ]);
                } catch (\Exception $mailException) {
                    \Log::error('Failed to send extension approval email', [
                        'permit_id' => $permit->id,
                        'to' => $creatorEmail,
                        'error' => $mailException->getMessage()
                    ]);
                    // Don't fail the approval process due to email failure
                }
            } else {
                \Log::warning('No creator email found for extension approval notification', [
                    'permit_id' => $permit->id
                ]);
            }

            return redirect()->route('permits.show', $permit)
                ->with('success', 'Extension has been approved successfully. Permit is now active until ' . 
                       $permit->end_date->format('d M Y') . '.');

        } catch (\Exception $e) {
            \Log::error('Extension approval failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to approve extension: ' . $e->getMessage());
        }
    }

    /**
     * Reject extension request (EHS only)
     */
    public function rejectExtension(Request $request, PermitToWork $permit)
    {
        // Check if permit is in pending extension approval status
        if ($permit->status !== 'pending_extension_approval') {
            return redirect()->route('permits.show', $permit)
                ->with('error', 'Only permits with pending extension approval can be rejected.');
        }

        // Only bekaert users with EHS department can reject extensions
        if (auth()->user()->role !== 'bekaert' || auth()->user()->department !== 'EHS') {
            return redirect()->route('permits.show', $permit)
                ->with('error', 'You do not have permission to reject extensions. Only EHS users can reject extensions.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        try {
            // Reject the extension - change status back to expired
            $permit->update([
                'status' => 'expired',
                'rejection_reason' => $validated['rejection_reason'],
                'rejected_at' => now(),
                'rejected_by' => auth()->id(),
            ]);

            // Send notification email to permit creator
            $creatorEmail = $permit->permitIssuer->email ?? null;
            \Log::info('Sending extension rejection email', [
                'permit_id' => $permit->id,
                'creator_email' => $creatorEmail
            ]);
            
            if ($creatorEmail) {
                try {
                    \Mail::to($creatorEmail)->send(new \App\Mail\PermitApprovalResult($permit, false, 'extension'));
                    \Log::info('Extension rejection email sent successfully', [
                        'permit_id' => $permit->id,
                        'to' => $creatorEmail
                    ]);
                } catch (\Exception $mailException) {
                    \Log::error('Failed to send extension rejection email', [
                        'permit_id' => $permit->id,
                        'to' => $creatorEmail,
                        'error' => $mailException->getMessage()
                    ]);
                    // Don't fail the rejection process due to email failure
                }
            } else {
                \Log::warning('No creator email found for extension rejection notification', [
                    'permit_id' => $permit->id
                ]);
            }

            return redirect()->route('permits.show', $permit)
                ->with('success', 'Extension has been rejected. Permit remains expired.');

        } catch (\Exception $e) {
            \Log::error('Extension rejection failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to reject extension: ' . $e->getMessage());
        }
    }

    /**
     * Download permit as PDF
     */
    public function downloadPdf(PermitToWork $permit)
    {
        try {
            // Load relationships
            $permit->load([
                'permitIssuer', 
                'authorizer', 
                'receiver', 
                'methodStatement',
                'emergencyPlan'
            ]);

            // Check if user has permission to download
            if (!in_array($permit->status, ['active', 'completed'])) {
                return redirect()->back()
                    ->with('error', 'PDF can only be downloaded for active or completed permits.');
            }

            // Get responsible persons with their phone numbers from users table
            $responsiblePersonsWithPhones = [];
            if ($permit->methodStatement) {
                $responsiblePersonsData = $permit->methodStatement->responsible_persons;
                
                if ($responsiblePersonsData && is_array($responsiblePersonsData)) {
                    foreach ($responsiblePersonsData as $person) {
                        if (is_array($person) && isset($person['email']) && !empty($person['email'])) {
                            // Find user by email to get phone number
                            $user = \App\Models\User::where('email', $person['email'])->first();
                            $responsiblePersonsWithPhones[] = [
                                'name' => $user ? (string)$user->name : (string)($person['name'] ?? $person['email']),
                                'email' => (string)$person['email'],
                                'phone' => $user && $user->phone ? (string)$user->phone : 'Not found'
                            ];
                        }
                    }
                }
            }

            // Generate QR Code
            $qrUrl = url('/permits/' . $permit->id);
            $renderer = new ImageRenderer(
                new RendererStyle(80),
                new SvgImageBackEnd()
            );
            $writer = new Writer($renderer);
            $qrCode = base64_encode($writer->writeString($qrUrl));

            $pdf = \PDF::loadView('permits.pdf_simple', compact('permit', 'qrCode', 'responsiblePersonsWithPhones'));
            
            return $pdf->download('permit-' . $permit->permit_number . '.pdf');
        } catch (\Exception $e) {
            \Log::error('PDF Generation Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }
}

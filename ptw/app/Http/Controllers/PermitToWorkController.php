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
    public function index()
    {
        $permits = PermitToWork::with(['user', 'permitIssuer', 'authorizer', 'receiver'])
            ->latest()
            ->paginate(15);

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
        $permit->load(['permitIssuer', 'authorizer', 'receiver', 'locationOwner', 'methodStatement.creator', 'riskAssessments']);
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
        if (!in_array($permit->status, ['draft', 'rejected'])) {
            return redirect()->route('permits.show', $permit)
                ->with('error', 'Only draft and rejected permits can be updated.');
        }

        // Check if user is the permit creator or administrator
        if (Auth::id() !== $permit->permit_issuer_id && Auth::user()->role !== 'administrator') {
            return redirect()->route('permits.show', $permit)
                ->with('error', 'You do not have permission to update this permit.');
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

        $permit->update($validated);

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
        //if (Auth::id() !== $permit->permit_issuer_id) {
        //    return redirect()->back()
        //        ->with('error', 'Access denied. Only the permit creator can request approval.');
        //}

        // Check if permit can be submitted for approval
        //if (!in_array($permit->status, ['draft', 'rejected', 'resubmitted'])) {
        //    return redirect()->back()
        //        ->with('error', 'This permit cannot be submitted for approval in its current status.');
        //}

        // Check if method statement exists and is completed
        //if (!$permit->methodStatement || $permit->methodStatement->status !== 'completed') {
        //    return redirect()->back()
        //        ->with('error', 'Method Statement must be completed before requesting approval.');
        //}

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
    public function complete(PermitToWork $permit)
    {
        // Check if user has permission (permit issuer or administrator)
        if (auth()->id() !== $permit->permit_issuer_id && auth()->user()->role !== 'administrator') {
            return redirect()->back()
                ->with('error', 'You do not have permission to complete this permit.');
        }

        // Check if permit is in active status
        if ($permit->status !== 'active') {
            return redirect()->back()
                ->with('error', 'Only active permits can be marked as completed.');
        }

        // Update permit status to completed
        $permit->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);

        return redirect()->route('permits.show', $permit)
            ->with('success', 'Permit has been marked as completed successfully.');
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
                'methodStatement'
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

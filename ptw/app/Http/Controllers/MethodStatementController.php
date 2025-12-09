<?php

namespace App\Http\Controllers;

use App\Models\MethodStatement;
use App\Models\PermitToWork;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MethodStatementController extends Controller
{
    public function create($permitNumber)
    {
        $permit = PermitToWork::with(['receiver', 'permitIssuer'])->where('permit_number', $permitNumber)->firstOrFail();
        
        // Check if method statement already exists
        $existingMethodStatement = MethodStatement::where('permit_number', $permitNumber)->first();
        if ($existingMethodStatement) {
            return redirect()->route('method-statements.show', $permitNumber)
                ->with('info', 'Method Statement already exists for this permit.');
        }

        // Get users from the same company as the permit receiver
        $users = collect();
        if ($permit->receiver_company_name) {
            // Get users from the same company as receiver
            $users = User::whereHas('company', function($query) use ($permit) {
                $query->where('company_name', $permit->receiver_company_name);
            })->select('id', 'name', 'email')->orderBy('name')->get();
        }

        return view('method-statements.create', compact('permit', 'users'));
    }

    public function store(Request $request, $permitNumber)
    {
        $permit = PermitToWork::where('permit_number', $permitNumber)->firstOrFail();

        // Check if method statement already exists before creating
        $existingMethodStatement = MethodStatement::where('permit_number', $permitNumber)->first();
        if ($existingMethodStatement) {
            return redirect()->route('method-statements.show', $permitNumber)
                ->with('info', 'Method Statement already exists for this permit.');
        }

        $validatedData = $request->validate([
            'responsible_person_name' => 'nullable|string|max:255',
            'method_statement_date' => 'nullable|date',
            'permit_receiver_name' => 'nullable|string|max:255',
            'permit_issuer_name' => 'nullable|string|max:255',
            'responsible_persons' => 'nullable|array',
            // New field validations
            'safe_access_explanation' => $request->has('submit') ? 'required|string' : 'nullable|string',
            'ppe_safety_equipment_explanation' => $request->has('submit') ? 'required|string' : 'nullable|string',
            'qualifications_training_explanation' => $request->has('submit') ? 'required|string' : 'nullable|string',
            'safe_routes_identification_explanation' => $request->has('submit') ? 'required|string' : 'nullable|string',
            'storage_security_explanation' => $request->has('submit') ? 'required|string' : 'nullable|string',
            'equipment_checklist_explanation' => $request->has('submit') ? 'required|string' : 'nullable|string',
            'work_order_explanation' => $request->has('submit') ? 'required|string' : 'nullable|string',
            'temporary_work_explanation' => $request->has('submit') ? 'required|string' : 'nullable|string',
            'weather_conditions_explanation' => $request->has('submit') ? 'required|string' : 'nullable|string',
            'area_maintenance_explanation' => $request->has('submit') ? 'required|string' : 'nullable|string',
            'risk_activities' => 'nullable|array',
            'risk_levels' => 'nullable|array',
            'control_measures' => 'nullable|array',
        ]);

        // Process responsible_persons data
        if (isset($validatedData['responsible_persons'])) {
            $responsiblePersons = [];
            foreach ($validatedData['responsible_persons'] as $personData) {
                if (!empty($personData)) {
                    $decodedPerson = json_decode($personData, true);
                    if ($decodedPerson && isset($decodedPerson['name']) && isset($decodedPerson['email'])) {
                        $responsiblePersons[] = $decodedPerson;
                    }
                }
            }
            $validatedData['responsible_persons'] = $responsiblePersons;
        }

        $validatedData['permit_number'] = $permitNumber;
        $validatedData['created_by'] = Auth::id();
        $validatedData['status'] = $request->has('submit') ? 'completed' : 'draft';

        MethodStatement::create($validatedData);

        // Get the permit by permit_number to get the ID for redirect
        $permit = PermitToWork::where('permit_number', $permitNumber)->firstOrFail();

        return redirect()->route('permits.show', $permit->id)
            ->with('success', 'Method Statement created successfully.');
    }

    public function show($permitNumber)
    {
        $permit = PermitToWork::with(['receiver', 'permitIssuer'])->where('permit_number', $permitNumber)->firstOrFail();
        $methodStatement = MethodStatement::with('creator')->where('permit_number', $permitNumber)->firstOrFail();

        // Get users data for responsible persons
        $responsiblePersonEmails = [];
        if ($methodStatement->responsible_persons && is_array($methodStatement->responsible_persons)) {
            foreach ($methodStatement->responsible_persons as $person) {
                if (isset($person['email'])) {
                    $responsiblePersonEmails[] = $person['email'];
                }
            }
        }
        
        $responsibleUsers = collect();
        if (!empty($responsiblePersonEmails)) {
            $responsibleUsers = User::whereIn('email', $responsiblePersonEmails)
                                   ->select('id', 'name', 'email', 'phone')
                                   ->get()
                                   ->keyBy('email');
        }

        return view('method-statements.show', compact('permit', 'methodStatement', 'responsibleUsers'));
    }

    public function edit($permitNumber)
    {
        $permit = PermitToWork::with(['receiver', 'permitIssuer'])->where('permit_number', $permitNumber)->firstOrFail();
        $methodStatement = MethodStatement::where('permit_number', $permitNumber)->firstOrFail();
        
        // Get users from the same company as the permit receiver
        $users = collect();
        if ($permit->receiver_company_name) {
            // Get users from the same company as receiver
            $users = User::whereHas('company', function($query) use ($permit) {
                $query->where('company_name', $permit->receiver_company_name);
            })->select('id', 'name', 'email')->orderBy('name')->get();
        }

        return view('method-statements.edit', compact('permit', 'methodStatement', 'users'));
    }

    public function update(Request $request, $permitNumber)
    {
        $methodStatement = MethodStatement::where('permit_number', $permitNumber)->firstOrFail();

        $validatedData = $request->validate([
            'responsible_person_name' => 'nullable|string|max:255',
            'method_statement_date' => 'nullable|date',
            'permit_receiver_name' => 'nullable|string|max:255',
            'permit_issuer_name' => 'nullable|string|max:255',
            'responsible_persons' => 'nullable|array',
            // New field validations
            'safe_access_explanation' => $request->has('submit') ? 'required|string' : 'nullable|string',
            'ppe_safety_equipment_explanation' => $request->has('submit') ? 'required|string' : 'nullable|string',
            'qualifications_training_explanation' => $request->has('submit') ? 'required|string' : 'nullable|string',
            'safe_routes_identification_explanation' => $request->has('submit') ? 'required|string' : 'nullable|string',
            'storage_security_explanation' => $request->has('submit') ? 'required|string' : 'nullable|string',
            'equipment_checklist_explanation' => $request->has('submit') ? 'required|string' : 'nullable|string',
            'work_order_explanation' => $request->has('submit') ? 'required|string' : 'nullable|string',
            'temporary_work_explanation' => $request->has('submit') ? 'required|string' : 'nullable|string',
            'weather_conditions_explanation' => $request->has('submit') ? 'required|string' : 'nullable|string',
            'area_maintenance_explanation' => $request->has('submit') ? 'required|string' : 'nullable|string',
            'risk_activities' => 'nullable|array',
            'risk_levels' => 'nullable|array',
            'control_measures' => 'nullable|array',
        ]);

        // Process responsible_persons data
        if (isset($validatedData['responsible_persons'])) {
            $responsiblePersons = [];
            foreach ($validatedData['responsible_persons'] as $personData) {
                if (!empty($personData)) {
                    $decodedPerson = json_decode($personData, true);
                    if ($decodedPerson && isset($decodedPerson['name']) && isset($decodedPerson['email'])) {
                        $responsiblePersons[] = $decodedPerson;
                    }
                }
            }
            $validatedData['responsible_persons'] = $responsiblePersons;
        }

        $validatedData['status'] = $request->has('submit') ? 'completed' : 'draft';

        $methodStatement->update($validatedData);

        // Get the permit by permit_number to get the ID for redirect
        $permit = PermitToWork::where('permit_number', $permitNumber)->firstOrFail();

        return redirect()->route('permits.show', $permit->id)
            ->with('success', 'Method Statement updated successfully.');
    }

    public function requestApproval($permitNumber)
    {
        $methodStatement = MethodStatement::where('permit_number', $permitNumber)->firstOrFail();
        $permit = PermitToWork::where('permit_number', $permitNumber)->firstOrFail();

        // Check if current user can request approval for this permit
        if (!$permit->canRequestApproval()) {
            return redirect()->back()
                ->with('error', 'Access denied. You do not have permission to request approval for this permit.');
        }

        // Update permit status to pending approval
        $permit->update([
            'status' => 'pending_approval'
        ]);

        return redirect()->route('permits.show', $permit->id)
            ->with('success', 'Approval request sent successfully. Waiting for EHS approval.');
    }

    public function approve($permitNumber)
    {
        $user = auth()->user();
        
        // Check if user is Bekaert EHS
        if ($user->role !== 'bekaert' || $user->department !== 'EHS') {
            return redirect()->back()
                ->with('error', 'Only Bekaert EHS department can approve method statements.');
        }

        $methodStatement = MethodStatement::where('permit_number', $permitNumber)->firstOrFail();
        $permit = PermitToWork::where('permit_number', $permitNumber)->firstOrFail();

        // Update permit status to active (not approved, as there are more steps)
        $permit->update([
            'status' => 'active',
            'authorizer_id' => $user->id,
            'authorized_at' => now()
        ]);

        // Update method statement status to approved
        $methodStatement->update([
            'status' => 'approved'
        ]);

        return redirect()->route('permits.show', $permit->id)
            ->with('success', 'Method Statement approved and Permit is now active.');
    }

    public function reject($permitNumber)
    {
        $user = auth()->user();
        
        // Check if user is Bekaert EHS
        if ($user->role !== 'bekaert' || $user->department !== 'EHS') {
            return redirect()->back()
                ->with('error', 'Only Bekaert EHS department can reject method statements.');
        }

        $methodStatement = MethodStatement::where('permit_number', $permitNumber)->firstOrFail();
        $permit = PermitToWork::where('permit_number', $permitNumber)->firstOrFail();

        // Update permit status to rejected
        $permit->update([
            'status' => 'rejected',
            'authorizer_id' => $user->id,
            'authorized_at' => now()
        ]);

        // Update method statement status to draft (needs revision)
        $methodStatement->update([
            'status' => 'draft'
        ]);

        return redirect()->route('permits.show', $permit->id)
            ->with('error', 'Method Statement and Permit rejected. Please revise and resubmit.');
    }
}

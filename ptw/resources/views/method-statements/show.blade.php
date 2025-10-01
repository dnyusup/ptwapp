@extends('layouts.app')

@section('content')
@include('layouts.sidebar-styles')
@include('layouts.sidebar')

<!-- Main Content -->
<div class="main-content">
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1"><i class="fas fa-file-alt me-2 text-primary"></i>Method Statement</h4>
                <p class="text-muted mb-0">Permit Number: {{ $permit->permit_number }}</p>
            </div>
            <div class="d-flex gap-2">
                @php
                    $canEdit = false;
                    $currentUser = auth()->user();
                    
                    // Administrator can always edit
                    if ($currentUser->role === 'administrator') {
                        $canEdit = true;
                    }
                    // Bekaert EHS department can edit
                    elseif ($currentUser->role === 'bekaert' && $currentUser->department === 'EHS') {
                        $canEdit = true;
                    }
                    // User as responsible person in permit
                    elseif ($permit->responsible_person === $currentUser->name) {
                        $canEdit = true;
                    }
                    // User as permit receiver
                    elseif ($permit->receiver_name === $currentUser->name) {
                        $canEdit = true;
                    }
                    // User as method statement creator
                    elseif ($methodStatement->created_by === $currentUser->id) {
                        $canEdit = true;
                    }
                    // User as responsible person in method statement (penanggung jawab)
                    elseif ($methodStatement->responsible_person_name === $currentUser->name) {
                        $canEdit = true;
                    }
                    // User as any of the responsible persons in the new array format
                    elseif ($methodStatement->responsible_persons && is_array($methodStatement->responsible_persons)) {
                        foreach ($methodStatement->responsible_persons as $person) {
                            if (isset($person['name']) && $person['name'] === $currentUser->name) {
                                $canEdit = true;
                                break;
                            }
                        }
                    }
                @endphp
                
                @if($canEdit)
                <a href="{{ route('method-statements.edit', $permit->permit_number) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Edit
                </a>
                @endif
                
                <a href="{{ route('permits.show', $permit->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Permit
                </a>
            </div>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-info-circle fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Method Statement Status</h6>
                        <span class="badge bg-{{ $methodStatement->status === 'completed' ? 'success' : ($methodStatement->status === 'approved' ? 'primary' : 'secondary') }} fs-6">
                            {{ ucfirst($methodStatement->status) }}
                        </span>
                        <p class="mb-0 mt-2 text-muted">
                            Created: {{ $methodStatement->created_at->format('d M Y H:i') }} by {{ $methodStatement->creator->name ?? 'Unknown' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Basic Information -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Basic Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <strong>Nama Penanggung Jawab:</strong>
                    <p class="mb-0">{{ $methodStatement->responsible_person_name ?: 'Not specified' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Tanggal Method Statement:</strong>
                    <p class="mb-0">{{ $methodStatement->method_statement_date ? $methodStatement->method_statement_date->format('d M Y') : 'Not specified' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Nama Permit Receiver:</strong>
                    <p class="mb-0">{{ $methodStatement->permit_receiver_name ?: 'Not specified' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Nama Pembuat Method Statement:</strong>
                    <p class="mb-0">{{ $methodStatement->permit_issuer_name ?: 'Not specified' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Responsible Persons -->
    @if($methodStatement->responsible_persons && count($methodStatement->responsible_persons) > 0)
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-users me-2"></i>Responsible Persons</h5>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($methodStatement->responsible_persons as $index => $person)
                    @php
                        $user = null;
                        if (isset($person['email']) && isset($responsibleUsers)) {
                            $user = $responsibleUsers->get($person['email']);
                        }
                    @endphp
                    <div class="col-md-6 mb-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body py-3">
                                <h6 class="card-title mb-2">
                                    <span class="badge bg-primary me-2">{{ $index + 1 }}</span>
                                    {{ $person['name'] ?? 'Not specified' }}
                                </h6>
                                <p class="card-text small text-muted mb-0">
                                    <i class="fas fa-phone me-1"></i>
                                    {{ $user && $user->phone ? $user->phone : 'No phone number' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Work Method Explanations -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Work Method Explanations</h5>
        </div>
        <div class="card-body">
            @php
                $explanations = [
                    'work_access_explanation' => 'Cara menuju dan dari lokasi kerja',
                    'safety_equipment_explanation' => 'APD dan peralatan safety',
                    'training_competency_explanation' => 'Training/kompetensi/pengalaman',
                    'route_identification_explanation' => 'Identifikasi rute',
                    'work_area_preparation_explanation' => 'Lokasi peralatan dan penyimpanan',
                    'work_sequence_explanation' => 'Urutan pekerjaan',
                    'equipment_maintenance_explanation' => 'Peralatan yang dibutuhkan',
                    'platform_explanation' => 'Platform sementara',
                    'hand_washing_explanation' => 'Pengaruh cuaca',
                    'work_area_cleanliness_explanation' => 'Kebersihan dan kerapian area kerja'
                ];
            @endphp
            
            @foreach($explanations as $field => $label)
                @if($methodStatement->$field)
                <div class="mb-4">
                    <h6 class="fw-bold text-primary">{{ $label }}</h6>
                    <p class="mb-0">{{ $methodStatement->$field }}</p>
                </div>
                @endif
            @endforeach
        </div>
    </div>

    <!-- Risk Assessment Table -->
    @if($methodStatement->risk_activities && count($methodStatement->risk_activities) > 0)
    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0"><i class="fas fa-exclamation-circle me-2"></i>Risk Assessment</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Bahaya</th>
                            <th>Risiko</th>
                            <th>Tindakan Pengendalian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($methodStatement->risk_activities as $index => $activity)
                        @if($activity)
                        <tr>
                            <td>{{ $activity }}</td>
                            <td>
                                @if(isset($methodStatement->risk_levels[$index]))
                                    <span class="badge bg-{{ $methodStatement->risk_levels[$index] === 'High' ? 'danger' : ($methodStatement->risk_levels[$index] === 'Medium' ? 'warning' : 'success') }}">
                                        {{ $methodStatement->risk_levels[$index] }}
                                    </span>
                                @endif
                            </td>
                            <td>{{ $methodStatement->control_measures[$index] ?? '' }}</td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="alert alert-warning mt-4">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Peringatan:</strong> Tuliskan detail tindakan pengendalian untuk aktivitas berisiko Medium dan Tinggi
            </div>
        </div>
    </div>
    @endif
</div>

@include('layouts.sidebar-scripts')
@endsection

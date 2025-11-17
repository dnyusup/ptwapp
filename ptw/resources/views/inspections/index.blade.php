@extends('layouts.app')

@section('content')
@include('layouts.sidebar-styles')
@include('layouts.sidebar')

<!-- Main Content -->
<div class="main-content">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Inspection History</h4>
            <p class="text-muted mb-0">Permit Number: {{ $permit->permit_number }}</p>
        </div>
        <div>
            <a href="{{ route('permits.show', $permit) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Permit
            </a>
        </div>
    </div>

    <!-- Permit Info Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h6 class="fw-semibold text-primary">{{ $permit->work_title }}</h6>
                    <p class="text-muted mb-2">{{ $permit->work_description }}</p>
                    <div class="d-flex gap-3">
                        <small class="text-muted">
                            <i class="fas fa-map-marker-alt me-1"></i>{{ $permit->location }}
                        </small>
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i>{{ $permit->work_start_date }} - {{ $permit->work_end_date }}
                        </small>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    @php
                        $statusColor = 'secondary';
                        if ($permit->status === 'active') {
                            $statusColor = 'success';
                        } elseif ($permit->status === 'pending_approval') {
                            $statusColor = 'warning';
                        } elseif ($permit->status === 'expired') {
                            $statusColor = 'danger';
                        } elseif ($permit->status === 'completed') {
                            $statusColor = 'primary';
                        }
                    @endphp
                    <span class="badge bg-{{ $statusColor }} mb-2">
                        @if($permit->status === 'expired')
                            <i class="fas fa-exclamation-triangle me-1"></i>
                        @elseif($permit->status === 'active')
                            <i class="fas fa-play-circle me-1"></i>
                        @endif
                        {{ ucfirst($permit->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Inspections List -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="fas fa-clipboard-check me-2 text-primary"></i>Inspection Records
                </h6>
                <span class="badge bg-primary">{{ $inspections->count() }} Total</span>
            </div>
        </div>
        <div class="card-body p-0">
            @if($inspections->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="15%">Date & Time</th>
                                <th width="20%">Inspector</th>
                                <th width="20%">Email</th>
                                <th width="40%">Findings</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inspections as $index => $inspection)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $inspection->created_at->format('d M Y') }}</div>
                                    <small class="text-muted">{{ $inspection->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $inspection->inspector_name }}</div>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $inspection->inspector_email }}</small>
                                </td>
                                <td>
                                    <div class="inspection-findings">
                                        {{ Str::limit($inspection->findings, 100) }}
                                        @if(strlen($inspection->findings) > 100)
                                            <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#findingModal{{ $inspection->id }}">
                                                Read more...
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Inspections Yet</h5>
                    <p class="text-muted">No inspections have been recorded for this permit.</p>
                    <a href="{{ route('permits.show', $permit) }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Permit
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Finding Detail Modals -->
@foreach($inspections as $inspection)
<div class="modal fade" id="findingModal{{ $inspection->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-clipboard-check me-2"></i>Inspection Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Inspector:</strong> {{ $inspection->inspector_name }}
                    </div>
                    <div class="col-md-6">
                        <strong>Date:</strong> {{ $inspection->created_at->format('d M Y H:i') }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <strong>Email:</strong> {{ $inspection->inspector_email }}
                    </div>
                </div>
                <hr>
                <div class="mb-3">
                    <strong>Inspection Findings:</strong>
                    <div class="mt-2 p-3 bg-light rounded">
                        {!! nl2br(e($inspection->findings)) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@include('layouts.sidebar-scripts')
@endsection
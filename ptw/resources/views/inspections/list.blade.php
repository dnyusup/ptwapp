@extends('layouts.app')

@section('content')
@include('layouts.sidebar-styles')
@include('layouts.sidebar')

<!-- Main Content -->
<div class="main-content">
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">Inspections</h4>
                <p class="text-muted mb-0">All inspections recorded across every permit to work</p>
            </div>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('inspections.list') }}" class="row g-3 align-items-start">
                <div class="col-lg-1 col-md-3">
                    <label for="finding_type" class="form-label">Finding</label>
                    <select name="finding_type" id="finding_type" class="form-select" onchange="this.form.submit()">
                        <option value="">All</option>
                        <option value="OK" {{ request('finding_type') == 'OK' ? 'selected' : '' }}>OK</option>
                        <option value="NOK" {{ request('finding_type') == 'NOK' ? 'selected' : '' }}>NOK</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-3">
                    <label for="category" class="form-label">Category</label>
                    <select name="category" id="category" class="form-select" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-1 col-md-2">
                    <label for="area" class="form-label">Area</label>
                    <select name="area" id="area" class="form-select" onchange="this.form.submit()">
                        <option value="">All</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}" {{ request('area') == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-4">
                    <label for="company" class="form-label">Company</label>
                    <select name="company" id="company" class="form-select" onchange="this.form.submit()">
                        <option value="">All Company</option>
                        @foreach($companies as $company)
                            <option value="{{ $company }}" {{ request('company') == $company ? 'selected' : '' }}>{{ $company }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-4">
                    <label class="form-label">Date (from &ndash; to)</label>
                    <input type="date" name="date_from" class="form-control form-control-sm mb-1" value="{{ request('date_from') }}" title="From" onchange="this.form.submit()">
                    <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}" title="To" onchange="this.form.submit()">
                </div>
                <div class="col-lg-2 col-md-4">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" name="search" id="search" class="form-control" placeholder="Permit, inspector, findings..." value="{{ request('search') }}">
                </div>
                <div class="col-lg-2 col-md-4">
                    <label class="form-label d-none d-lg-block">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill"><i class="fas fa-search"></i> Filter</button>
                        <a href="{{ route('inspections.list') }}" class="btn btn-secondary flex-fill"><i class="fas fa-times"></i> Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @php
        $findingFilterMap = ['OK' => 'OK', 'NOK' => 'NOK'];
        $areaIdMap = $areas->pluck('id', 'name');
    @endphp

    <!-- Summary dashboard (follows the active table filters) -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="row g-3">
                <div class="col-lg-4 col-md-6">
                    <div class="text-uppercase text-muted fw-semibold mb-2" style="font-size:.72rem;letter-spacing:.5px;">
                        <i class="fas fa-flag me-1"></i>By Finding Type
                        <span class="text-dark">&middot; {{ $summary['total'] }} total</span>
                    </div>
                    <div class="d-flex flex-wrap gap-1">
                        @forelse($summary['byFinding'] as $label => $count)
                            <a href="{{ route('inspections.list', array_merge(request()->except(['finding_type','page']), ['finding_type' => $findingFilterMap[$label] ?? null])) }}"
                               class="badge bg-{{ $label === 'OK' ? 'success' : ($label === 'NOK' ? 'danger' : 'secondary') }} text-decoration-none {{ request('finding_type') === ($findingFilterMap[$label] ?? '_') ? 'border border-2 border-dark' : '' }}"
                               style="font-weight:500;">
                                {{ $label }} <span class="ms-1 opacity-75">{{ $count }}</span>
                            </a>
                        @empty
                            <span class="text-muted small">No data</span>
                        @endforelse
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="text-uppercase text-muted fw-semibold mb-2" style="font-size:.72rem;letter-spacing:.5px;">
                        <i class="fas fa-layer-group me-1"></i>By Category
                    </div>
                    <div class="d-flex flex-wrap gap-1">
                        @forelse($summary['byCategory'] as $label => $count)
                            <a href="{{ route('inspections.list', array_merge(request()->except(['category','page']), ['category' => $label])) }}"
                               class="badge bg-light text-dark border text-decoration-none {{ request('category') === $label ? 'border-2 border-primary' : '' }}"
                               style="font-weight:500;">
                                {{ $label }} <span class="ms-1 text-primary">{{ $count }}</span>
                            </a>
                        @empty
                            <span class="text-muted small">No data</span>
                        @endforelse
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="text-uppercase text-muted fw-semibold mb-2" style="font-size:.72rem;letter-spacing:.5px;">
                        <i class="fas fa-location-dot me-1"></i>By Area
                    </div>
                    <div class="d-flex flex-wrap gap-1">
                        @forelse($summary['byArea'] as $label => $count)
                            @php $aid = $areaIdMap[$label] ?? null; @endphp
                            @if($aid)
                                <a href="{{ route('inspections.list', array_merge(request()->except(['area','page']), ['area' => $aid])) }}"
                                   class="badge bg-light text-dark border text-decoration-none {{ (string) request('area') === (string) $aid ? 'border-2 border-primary' : '' }}"
                                   style="font-weight:500;">
                                    {{ $label }} <span class="ms-1 text-primary">{{ $count }}</span>
                                </a>
                            @else
                                <span class="badge bg-light text-muted border" style="font-weight:500;">
                                    {{ $label }} <span class="ms-1">{{ $count }}</span>
                                </span>
                            @endif
                        @empty
                            <span class="text-muted small">No data</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inspections Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Inspection List</h5>
                <div class="d-flex align-items-center gap-3">
                    <small class="text-muted">
                        @if(request()->hasAny(['finding_type', 'category', 'area', 'company', 'date_from', 'date_to', 'search']))
                            <i class="fas fa-filter me-1"></i>Filtered results: <strong>{{ $inspections->total() }}</strong>
                        @else
                            Total inspections: <strong>{{ $inspections->total() }}</strong>
                        @endif
                        &middot; NOK: <strong class="text-danger">{{ $summary['nok'] }}</strong>
                    </small>
                    <a href="{{ route('inspections.export', request()->query()) }}" class="btn btn-sm btn-success">
                        <i class="fas fa-download me-1"></i>Download
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @if($inspections->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Date &amp; Time</th>
                                <th>Permit</th>
                                <th>Work Title</th>
                                <th>Company</th>
                                <th>Area</th>
                                <th>Inspector</th>
                                <th>Category</th>
                                <th>Finding</th>
                                <th>Findings</th>
                                <th class="text-center">Photo</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inspections as $inspection)
                            <tr>
                                <td class="text-nowrap">
                                    <div class="fw-semibold">{{ $inspection->created_at->format('d M Y') }}</div>
                                    <small class="text-muted">{{ $inspection->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    @if($inspection->permit)
                                        <a href="{{ route('permits.show', $inspection->permit->id) }}" class="text-decoration-none fw-semibold text-primary">
                                            {{ $inspection->permit_number }}
                                        </a>
                                    @else
                                        <span class="fw-semibold">{{ $inspection->permit_number }}</span>
                                    @endif
                                </td>
                                <td>{{ \Illuminate\Support\Str::limit($inspection->permit->work_title ?? '-', 35) }}</td>
                                <td>{{ $inspection->permit->receiver_company_name ?? '-' }}</td>
                                <td>{{ $inspection->permit->area->name ?? 'No Area' }}</td>
                                <td>
                                    <div class="fw-medium">{{ $inspection->inspector_name }}</div>
                                    <small class="text-muted">{{ $inspection->inspector_email }}</small>
                                </td>
                                <td>
                                    @if($inspection->inspection_category)
                                        <span class="badge bg-light text-dark border" style="white-space: normal; text-align:left;">{{ $inspection->inspection_category }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($inspection->finding_type === 'OK')
                                        <span class="badge bg-success">OK</span>
                                    @elseif($inspection->finding_type === 'NOK')
                                        <span class="badge bg-danger">NOK</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ \Illuminate\Support\Str::limit($inspection->findings, 70) }}</td>
                                <td class="text-center">
                                    @if($inspection->photo_path)
                                        <a href="{{ route('storage.serve', $inspection->photo_path) }}" target="_blank">
                                            <img src="{{ route('storage.serve', $inspection->photo_path) }}" alt="Photo" class="img-thumbnail" style="max-height: 44px;">
                                        </a>
                                    @else
                                        <span class="text-muted"><i class="fas fa-image"></i></span>
                                    @endif
                                </td>
                                <td class="text-center text-nowrap">
                                    <div class="btn-group btn-group-sm" role="group">
                                        @if($inspection->permit)
                                            <a href="{{ route('permits.show', $inspection->permit->id) }}" class="btn btn-outline-primary" title="View permit">
                                                <i class="fas fa-file-alt"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('inspections.index', $inspection->permit_number) }}" class="btn btn-outline-info" title="Inspection history">
                                            <i class="fas fa-list"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($inspections->hasPages())
                    <div class="card-footer bg-white">
                        {{ $inspections->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No inspections found</h5>
                    <p class="text-muted">Inspections recorded on permits will appear here.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@include('layouts.sidebar-styles')
@include('layouts.sidebar-scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterForm = document.querySelector('form[method="GET"]');
    const searchInput = document.getElementById('search');
    let t;
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(t);
            t = setTimeout(() => {
                if (this.value.length >= 3 || this.value.length === 0) filterForm.submit();
            }, 500);
        });
    }
});
</script>
@endsection

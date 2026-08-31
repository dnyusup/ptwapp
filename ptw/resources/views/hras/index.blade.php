@extends('layouts.app')

@section('content')
@include('layouts.sidebar-styles')
@include('layouts.sidebar')

<!-- Main Content -->
<div class="main-content">
    <!-- Header -->
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">HRA's (High Risk Activities)</h4>
                <p class="text-muted mb-0">All HRA forms across every permit to work</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter and Search -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('hras.index') }}" class="row g-3 align-items-end">
                <div class="col-lg-2 col-md-3">
                    <label for="type" class="form-label">HRA Type</label>
                    <select name="type" id="type" class="form-select" onchange="this.form.submit()">
                        <option value="">All Types</option>
                        @foreach($typeList as $t)
                            <option value="{{ $t['key'] }}" {{ request('type') == $t['key'] ? 'selected' : '' }}>{{ $t['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending Approval</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="waiting_inspection" {{ request('status') == 'waiting_inspection' ? 'selected' : '' }}>Waiting Inspection</option>
                        <option value="no_inspected" {{ request('status') == 'no_inspected' ? 'selected' : '' }}>No Inspected</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-4">
                    <label class="form-label">Work Date</label>
                    <div class="d-flex gap-1 align-items-center">
                        <input type="date" name="date_from" id="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}" title="From" onchange="this.form.submit()">
                        <span class="text-muted">-</span>
                        <input type="date" name="date_to" id="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}" title="To" onchange="this.form.submit()">
                    </div>
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
                <div class="col-lg-2 col-md-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" name="search" id="search" class="form-control"
                           placeholder="HRA no, permit, worker..." value="{{ request('search') }}">
                </div>
                <div class="col-lg-2 col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('hras.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @php
        // label shown in the summary  ->  value expected by the ?status= filter
        $statusFilterMap = [
            'Pending Approval'   => 'pending',
            'Approved'           => 'approved',
            'Waiting Inspection' => 'waiting_inspection',
            'No Inspected'       => 'no_inspected',
            'Rejected'           => 'rejected',
            'Completed'          => 'completed',
            'Active'             => 'active',
            'Cancelled'          => 'cancelled',
            'Draft'              => 'draft',
        ];
        $statusColorMap = [
            'Pending Approval'   => 'warning',
            'Approved'           => 'success',
            'Waiting Inspection' => 'warning',
            'No Inspected'       => 'danger',
            'Rejected'           => 'danger',
            'Completed'          => 'primary',
            'Active'             => 'success',
            'Cancelled'          => 'danger',
            'Draft'              => 'secondary',
        ];
        $typeKeyMap = $typeList->pluck('key', 'label');
        $areaIdMap  = $areas->pluck('id', 'name');
    @endphp

    <!-- Summary dashboard (follows the active table filters) -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="row g-3">
                <div class="col-lg-4 col-md-6">
                    <div class="text-uppercase text-muted fw-semibold mb-2" style="font-size:.72rem;letter-spacing:.5px;">
                        <i class="fas fa-flag me-1"></i>By Status
                        <span class="text-dark">&middot; {{ $summary['total'] }} total</span>
                    </div>
                    <div class="d-flex flex-wrap gap-1">
                        @forelse($summary['byStatus'] as $label => $count)
                            <a href="{{ route('hras.index', array_merge(request()->except(['status','page']), ['status' => $statusFilterMap[$label] ?? null])) }}"
                               class="badge bg-{{ $statusColorMap[$label] ?? 'secondary' }} text-decoration-none {{ request('status') === ($statusFilterMap[$label] ?? '_') ? 'border border-2 border-dark' : '' }}"
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
                        <i class="fas fa-layer-group me-1"></i>By Type
                    </div>
                    <div class="d-flex flex-wrap gap-1">
                        @forelse($summary['byType'] as $label => $count)
                            <a href="{{ route('hras.index', array_merge(request()->except(['type','page']), ['type' => $typeKeyMap[$label] ?? null])) }}"
                               class="badge bg-light text-dark border text-decoration-none {{ request('type') === ($typeKeyMap[$label] ?? '_') ? 'border-2 border-primary' : '' }}"
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
                                <a href="{{ route('hras.index', array_merge(request()->except(['area','page']), ['area' => $aid])) }}"
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

    @php $isAdmin = auth()->user()->role === 'administrator'; @endphp

    @if($canManage)
    {{-- Hidden form used for every bulk action --}}
    <form id="bulkForm" method="POST" class="d-none">
        @csrf
        <input type="hidden" name="scope" id="bulkScope" value="selected">
        <div id="bulkSelectedInputs"></div>
        <input type="hidden" name="type" value="{{ request('type') }}">
        <input type="hidden" name="status" value="{{ request('status') }}">
        <input type="hidden" name="area" value="{{ request('area') }}">
        <input type="hidden" name="date_from" value="{{ request('date_from') }}">
        <input type="hidden" name="date_to" value="{{ request('date_to') }}">
        <input type="hidden" name="search" value="{{ request('search') }}">
    </form>
    @endif

    <!-- HRA Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0">
                    <i class="fas fa-triangle-exclamation me-2"></i>HRA List
                </h5>
                <small class="text-muted">
                    @if(request()->hasAny(['type', 'status', 'date_from', 'date_to', 'search', 'area']))
                        <i class="fas fa-filter me-1"></i>Filtered results: <strong>{{ $hras->total() }}</strong>
                    @else
                        Total HRA: <strong>{{ $hras->total() }}</strong>
                    @endif
                </small>
            </div>

            @if($hras->count() > 0 && $canManage)
            <div id="bulkBar" class="d-flex align-items-center flex-wrap gap-2 mt-2 pt-2 border-top">
                <span class="small text-muted">
                    <span id="bulkCount">0</span> selected
                </span>
                <span id="bulkAllFilteredWrap" class="small d-none">
                    &mdash;
                    <a href="#" id="bulkSelectAllFiltered">Select all {{ $hras->total() }} filtered HRA</a>
                </span>
                <span id="bulkAllActive" class="small text-success fw-semibold d-none">
                    <i class="fas fa-check-circle me-1"></i>All {{ $hras->total() }} filtered HRA selected.
                    <a href="#" id="bulkClearAll">Clear</a>
                </span>
                <div class="ms-auto d-flex gap-2">
                    {{-- inline colors: custom.css .btn-warning points at an undefined
                         --warning-gradient var, which resolves to a transparent bg --}}
                    <button type="button" id="btnBulkCancel" class="btn btn-sm btn-warning"
                            style="background-color:#f59e0b;border-color:#f59e0b;color:#fff;">
                        <i class="fas fa-ban me-1"></i>Cancel selected
                    </button>
                    @if($isAdmin)
                    <button type="button" id="btnBulkDelete" class="btn btn-sm btn-danger"
                            style="background-color:#ef4444;border-color:#ef4444;color:#fff;">
                        <i class="fas fa-trash me-1"></i>Delete selected
                    </button>
                    @endif
                </div>
            </div>
            @endif
        </div>
        <div class="card-body p-0">
            @if($hras->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                @if($canManage)
                                <th style="width:34px;" class="text-center">
                                    <input type="checkbox" id="checkAll" class="form-check-input" title="Select all on this page">
                                </th>
                                @endif
                                <th>HRA Number</th>
                                <th>Type</th>
                                <th>Permit</th>
                                <th>Work Title</th>
                                <th>Company</th>
                                <th>Location</th>
                                <th>Worker</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hras as $hra)
                            <tr>
                                @if($canManage)
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input rowCheck"
                                           value="{{ $hra['type_key'] }}:{{ $hra['id'] }}"
                                           data-label="{{ $hra['hra_permit_number'] }}">
                                </td>
                                @endif
                                <td><span class="fw-semibold text-primary">{{ $hra['hra_permit_number'] }}</span></td>
                                <td>
                                    <span class="text-nowrap"><i class="{{ $hra['type_icon'] }} me-1 text-muted"></i>{{ $hra['type_label'] }}</span>
                                </td>
                                <td>
                                    @if($hra['permit_id'])
                                        <a href="{{ route('permits.show', $hra['permit_id']) }}" class="text-decoration-none fw-medium">
                                            {{ $hra['permit_number'] }}
                                        </a>
                                    @else
                                        {{ $hra['permit_number'] }}
                                    @endif
                                </td>
                                <td>{{ \Illuminate\Support\Str::limit($hra['work_title'], 40) }}</td>
                                <td>{{ $hra['company'] }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($hra['location'], 30) }}</td>
                                <td>{{ $hra['worker_name'] }}</td>
                                <td class="text-nowrap">{{ $hra['start_datetime'] ? \Carbon\Carbon::parse($hra['start_datetime'])->format('d M Y H:i') : '-' }}</td>
                                <td class="text-nowrap">{{ $hra['end_datetime'] ? \Carbon\Carbon::parse($hra['end_datetime'])->format('d M Y H:i') : '-' }}</td>
                                <td>
                                    @php
                                        $label = $hra['status_label'];
                                        $badge = match($label) {
                                            'Approved'  => 'success',
                                            'Active'    => 'success',
                                            'Completed' => 'primary',
                                            'Rejected'  => 'danger',
                                            'Cancelled' => 'danger',
                                            'No Inspected' => 'danger',
                                            'Pending Approval'   => 'warning',
                                            'Waiting Inspection' => 'warning',
                                            default     => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badge }}{{ $badge === 'warning' ? ' text-dark' : '' }}">{{ $label }}</span>
                                </td>
                                <td>{{ $hra['created_by'] }}</td>
                                <td class="text-center text-nowrap">
                                    <div class="btn-group btn-group-sm" role="group">
                                        @if($hra['show_url'])
                                            <a href="{{ $hra['show_url'] }}" class="btn btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endif
                                        @if($canManage && in_array($hra['status_label'], ['Draft', 'Pending Approval']))
                                            <form method="POST" action="{{ route('hras.cancel', [$hra['type_key'], $hra['id']]) }}"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Cancel HRA {{ $hra['hra_permit_number'] }}? Its status will be set to Cancelled.');">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-warning" title="Cancel">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @if($isAdmin)
                                            <form method="POST" action="{{ route('hras.destroy', [$hra['type_key'], $hra['id']]) }}"
                                                  class="d-inline"
                                                  onsubmit="return confirm('DELETE HRA {{ $hra['hra_permit_number'] }}? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($hras->hasPages())
                    <div class="card-footer bg-white">
                        {{ $hras->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-triangle-exclamation fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No HRA found</h5>
                    <p class="text-muted">HRA forms created inside a permit will appear here.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@include('layouts.sidebar-styles')
@include('layouts.sidebar-scripts')

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.querySelector('form[method="GET"]');
    const searchInput = document.getElementById('search');
    let searchTimeout;
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length >= 3 || this.value.length === 0) {
                    filterForm.submit();
                }
            }, 500);
        });
    }

    @if($canManage)
    // ---- Bulk actions ----
    const ROUTES = {
        cancel: @json(route('hras.bulk-cancel')),
        delete: @json(route('hras.bulk-delete')),
    };
    const PAGE_TOTAL     = {{ $hras->count() }};
    const FILTERED_TOTAL = {{ $hras->total() }};

    const rowChecks   = Array.from(document.querySelectorAll('.rowCheck'));
    const checkAll    = document.getElementById('checkAll');
    const bulkCount   = document.getElementById('bulkCount');
    const btnCancel   = document.getElementById('btnBulkCancel');
    const btnDelete   = document.getElementById('btnBulkDelete');
    const allFilteredWrap = document.getElementById('bulkAllFilteredWrap');
    const allActive       = document.getElementById('bulkAllActive');
    const linkSelectAll   = document.getElementById('bulkSelectAllFiltered');
    const linkClearAll    = document.getElementById('bulkClearAll');
    const bulkForm        = document.getElementById('bulkForm');
    const bulkScope       = document.getElementById('bulkScope');
    const bulkInputs      = document.getElementById('bulkSelectedInputs');

    let scopeAll = false;

    function selectedValues() {
        return rowChecks.filter(c => c.checked).map(c => c.value);
    }

    function refresh() {
        const n = selectedValues().length;
        if (scopeAll) {
            bulkCount.textContent = FILTERED_TOTAL;
            allActive.classList.remove('d-none');
            allFilteredWrap.classList.add('d-none');
        } else {
            bulkCount.textContent = n;
            allActive.classList.add('d-none');
            // offer "select all filtered" only when the whole page is ticked and there is more beyond it
            allFilteredWrap.classList.toggle('d-none', !(n === PAGE_TOTAL && FILTERED_TOTAL > PAGE_TOTAL));
        }
        // NOTE: the bulk buttons are always fully visible and are NEVER mutated by
        // JS (no disabled attr, no class/style changes). Toggling those on a .btn
        // that has `transition: all` triggers a Chromium repaint bug where the
        // button only appears after a mouse hover. Validation happens on click.
        if (checkAll) checkAll.checked = n === PAGE_TOTAL && n > 0;
    }

    function clearScopeAll() {
        scopeAll = false;
        refresh();
    }

    rowChecks.forEach(c => c.addEventListener('change', clearScopeAll));
    if (checkAll) {
        checkAll.addEventListener('change', function() {
            rowChecks.forEach(c => { c.checked = checkAll.checked; });
            clearScopeAll();
        });
    }
    if (linkSelectAll) {
        linkSelectAll.addEventListener('click', function(e) {
            e.preventDefault();
            scopeAll = true;
            refresh();
        });
    }
    if (linkClearAll) {
        linkClearAll.addEventListener('click', function(e) {
            e.preventDefault();
            rowChecks.forEach(c => { c.checked = false; });
            if (checkAll) checkAll.checked = false;
            clearScopeAll();
        });
    }

    function submitBulk(kind) {
        const values = selectedValues();
        const n = scopeAll ? FILTERED_TOTAL : values.length;
        if (!scopeAll && n === 0) {
            alert('Select at least one HRA first (tick the checkboxes).');
            return;
        }

        const verb = kind === 'delete' ? 'DELETE' : 'Cancel';
        let msg = scopeAll
            ? `${verb} ALL ${n} filtered HRA?`
            : `${verb} ${n} selected HRA?`;
        if (kind === 'delete') msg += ' This action cannot be undone.';
        if (!confirm(msg)) return;

        bulkInputs.innerHTML = '';
        bulkScope.value = scopeAll ? 'all' : 'selected';
        if (!scopeAll) {
            values.forEach(v => {
                const i = document.createElement('input');
                i.type = 'hidden';
                i.name = 'selected[]';
                i.value = v;
                bulkInputs.appendChild(i);
            });
        }
        bulkForm.action = ROUTES[kind];
        bulkForm.submit();
    }

    if (btnCancel) btnCancel.addEventListener('click', () => submitBulk('cancel'));
    if (btnDelete) btnDelete.addEventListener('click', () => submitBulk('delete'));

    refresh();
    @endif
});
</script>

@endsection

@extends('layouts.app')

@section('content')
@include('layouts.sidebar-styles')
@include('layouts.sidebar')

<!-- Main Content -->
<div class="main-content">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-tasks me-2"></i>My Tasks</h2>
                <p>Tasks that require your action</p>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <span class="badge bg-primary fs-6 py-2 px-3">{{ $tasks->count() }} Tasks</span>
                <button class="btn btn-outline-primary btn-sm" onclick="location.reload()">
                    <i class="fas fa-sync-alt me-2"></i>Refresh
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Task Categories Summary -->
    @php
        $ehsApprovals = $tasks->where('subtype', 'ehs_approval')->count();
        $extensionApprovals = $tasks->where('subtype', 'extension_approval')->count();
        $locationOwnerApprovals = $tasks->where('subtype', 'location_owner_approval')->count();
        $hraApprovals = $tasks->where('type', 'hra')->where('subtype', '!=', 'rejected')->count();
        $rejectedPermits = $tasks->where('type', 'permit')->where('subtype', 'rejected')->count();
        $rejectedHras = $tasks->where('type', 'hra')->where('subtype', 'rejected')->count();
        $expiredPermits = $tasks->where('subtype', 'expired')->count();
        $pendingExtensions = $tasks->where('subtype', 'pending_extension')->count();
    @endphp

    <div class="row mb-3">
        @if($ehsApprovals > 0)
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card summary-card orange filter-card" data-filter="ehs_approval" style="cursor: pointer;">
                <div class="card-body">
                    <div class="summary-content">
                        <div class="summary-number">{{ $ehsApprovals }}</div>
                        <div class="summary-label">EHS Approval</div>
                    </div>
                    <div class="summary-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if($extensionApprovals > 0)
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card summary-card purple filter-card" data-filter="extension_approval" style="cursor: pointer;">
                <div class="card-body">
                    <div class="summary-content">
                        <div class="summary-number">{{ $extensionApprovals }}</div>
                        <div class="summary-label">Extension Approval</div>
                    </div>
                    <div class="summary-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if($locationOwnerApprovals > 0)
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card summary-card green filter-card" data-filter="location_owner_approval" style="cursor: pointer;">
                <div class="card-body">
                    <div class="summary-content">
                        <div class="summary-number">{{ $locationOwnerApprovals }}</div>
                        <div class="summary-label">Location Owner</div>
                    </div>
                    <div class="summary-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if($hraApprovals > 0)
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card summary-card blue filter-card" data-filter="hra" style="cursor: pointer;">
                <div class="card-body">
                    <div class="summary-content">
                        <div class="summary-number">{{ $hraApprovals }}</div>
                        <div class="summary-label">HRA Approval</div>
                    </div>
                    <div class="summary-icon">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if($rejectedPermits > 0)
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card summary-card red filter-card" data-filter="permit_rejected" style="cursor: pointer;">
                <div class="card-body">
                    <div class="summary-content">
                        <div class="summary-number">{{ $rejectedPermits }}</div>
                        <div class="summary-label">Permit Rejected</div>
                    </div>
                    <div class="summary-icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if($rejectedHras > 0)
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card summary-card red filter-card" data-filter="hra_rejected" style="cursor: pointer;">
                <div class="card-body">
                    <div class="summary-content">
                        <div class="summary-number">{{ $rejectedHras }}</div>
                        <div class="summary-label">HRA Rejected</div>
                    </div>
                    <div class="summary-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if($expiredPermits > 0)
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card summary-card gray filter-card" data-filter="expired" style="cursor: pointer;">
                <div class="card-body">
                    <div class="summary-content">
                        <div class="summary-number">{{ $expiredPermits }}</div>
                        <div class="summary-label">Expired</div>
                    </div>
                    <div class="summary-icon">
                        <i class="fas fa-calendar-times"></i>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if($pendingExtensions > 0)
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card summary-card yellow filter-card" data-filter="pending_extension" style="cursor: pointer;">
                <div class="card-body">
                    <div class="summary-content">
                        <div class="summary-number">{{ $pendingExtensions }}</div>
                        <div class="summary-label">Pending Extension</div>
                    </div>
                    <div class="summary-icon">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Task List -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>Task List
                        <span id="filteredCount" class="badge bg-secondary ms-2">{{ $tasks->count() }}</span>
                    </h5>
                </div>
                <div class="col-md-9">
                    <div class="row g-2 justify-content-end">
                        <div class="col-md-4">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" class="form-control" id="searchInput" placeholder="Search permit, title, company...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select form-select-sm" id="typeFilter">
                                <option value="">All Types</option>
                                <option value="ehs_approval">EHS Approval</option>
                                <option value="extension_approval">Extension Approval</option>
                                <option value="location_owner_approval">Location Owner</option>
                                <option value="hra">HRA Approval</option>
                                <option value="permit_rejected">Permit Rejected</option>
                                <option value="hra_rejected">HRA Rejected</option>
                                <option value="expired">Expired</option>
                                <option value="pending_extension">Pending Extension</option>
                            </select>
                        </div>
                        <div class="col-md-auto">
                            <button class="btn btn-sm btn-outline-secondary" id="clearFilter">
                                <i class="fas fa-times me-1"></i>Clear
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @if($tasks->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle" id="taskTable">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Type</th>
                                <th>Permit/HRA Number</th>
                                <th>Title</th>
                                <th>Company</th>
                                <th>Location</th>
                                <th>Created By</th>
                                <th>Date</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks as $task)
                            <tr class="task-row" 
                                data-type="{{ $task['type'] }}" 
                                data-subtype="{{ $task['subtype'] }}"
                                data-permit="{{ strtolower($task['permit_number']) }}"
                                data-title="{{ strtolower($task['title']) }}"
                                data-company="{{ strtolower($task['company'] ?? '') }}"
                                data-location="{{ strtolower($task['location'] ?? '') }}"
                                data-creator="{{ strtolower($task['created_by']) }}">
                                <td class="ps-3">
                                    <span class="badge {{ $task['badge_class'] }}">
                                        {{ $task['badge_text'] }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ $task['route'] }}" class="text-primary fw-bold text-decoration-none">
                                        {{ $task['permit_number'] }}
                                    </a>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ Str::limit($task['title'], 40) }}</div>
                                    <small class="text-muted">{{ $task['description'] }}</small>
                                </td>
                                <td>{{ $task['company'] ?? '-' }}</td>
                                <td>{{ Str::limit($task['location'], 25) }}</td>
                                <td>{{ $task['created_by'] }}</td>
                                <td>
                                    @if($task['date'])
                                        {{ \Carbon\Carbon::parse($task['date'])->format('d M Y') }}
                                        <br>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($task['date'])->diffForHumans() }}</small>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ $task['route'] }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                    <h5 class="text-muted">All caught up!</h5>
                    <p class="text-muted">You have no pending tasks at the moment.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@include('layouts.sidebar-scripts')

<style>
    .filter-card {
        transition: all 0.3s ease;
        border: 3px solid transparent;
    }
    .filter-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2) !important;
    }
    .filter-card.active {
        border-color: #333 !important;
        box-shadow: 0 8px 25px rgba(0,0,0,0.3) !important;
    }
    .task-row.hidden {
        display: none;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const typeFilter = document.getElementById('typeFilter');
    const clearBtn = document.getElementById('clearFilter');
    const filterCards = document.querySelectorAll('.filter-card');
    const taskRows = document.querySelectorAll('.task-row');
    const filteredCount = document.getElementById('filteredCount');

    function filterTasks() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedType = typeFilter.value;
        let visibleCount = 0;

        taskRows.forEach(row => {
            const type = row.dataset.type;
            const subtype = row.dataset.subtype;
            const permit = row.dataset.permit;
            const title = row.dataset.title;
            const company = row.dataset.company;
            const location = row.dataset.location;
            const creator = row.dataset.creator;

            // Check type filter
            let typeMatch = true;
            if (selectedType) {
                if (selectedType === 'hra') {
                    typeMatch = type === 'hra' && subtype !== 'rejected';
                } else if (selectedType === 'hra_rejected') {
                    typeMatch = type === 'hra' && subtype === 'rejected';
                } else if (selectedType === 'permit_rejected') {
                    typeMatch = type === 'permit' && subtype === 'rejected';
                } else {
                    typeMatch = subtype === selectedType;
                }
            }

            // Check search filter
            let searchMatch = true;
            if (searchTerm) {
                searchMatch = permit.includes(searchTerm) ||
                              title.includes(searchTerm) ||
                              company.includes(searchTerm) ||
                              location.includes(searchTerm) ||
                              creator.includes(searchTerm);
            }

            if (typeMatch && searchMatch) {
                row.classList.remove('hidden');
                visibleCount++;
            } else {
                row.classList.add('hidden');
            }
        });

        filteredCount.textContent = visibleCount;
    }

    // Search input event
    searchInput.addEventListener('input', filterTasks);

    // Type filter change event
    typeFilter.addEventListener('change', function() {
        // Update active card
        filterCards.forEach(card => card.classList.remove('active'));
        if (this.value) {
            const activeCard = document.querySelector(`.filter-card[data-filter="${this.value}"]`);
            if (activeCard) activeCard.classList.add('active');
        }
        filterTasks();
    });

    // Clear filter button
    clearBtn.addEventListener('click', function() {
        searchInput.value = '';
        typeFilter.value = '';
        filterCards.forEach(card => card.classList.remove('active'));
        filterTasks();
    });

    // Filter cards click event
    filterCards.forEach(card => {
        card.addEventListener('click', function() {
            const filterValue = this.dataset.filter;
            
            // Toggle filter
            if (typeFilter.value === filterValue) {
                typeFilter.value = '';
                this.classList.remove('active');
            } else {
                filterCards.forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                typeFilter.value = filterValue;
            }
            
            filterTasks();
        });
    });
});
</script>
@endsection

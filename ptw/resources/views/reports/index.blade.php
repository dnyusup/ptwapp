@extends('layouts.app')

@section('content')
@include('layouts.sidebar-styles')
@include('layouts.sidebar')

<style>
    /* Reports Dashboard Specific Styles */
    .report-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }

    .report-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .stat-card {
        border: none;
        border-radius: 16px;
        padding: 24px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 120px;
        height: 120px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .stat-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(-30%, 30%);
    }

    .stat-card .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        position: relative;
        z-index: 1;
    }

    .stat-card .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
        position: relative;
        z-index: 1;
    }

    .stat-card .stat-icon {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 3rem;
        opacity: 0.3;
    }

    .gradient-blue { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .gradient-green { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .gradient-orange { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
    .gradient-purple { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    .gradient-red { background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%); }
    .gradient-teal { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
    .gradient-indigo { background: linear-gradient(135deg, #5f72bd 0%, #9b23ea 100%); }
    .gradient-yellow { background: linear-gradient(135deg, #f6d365 0%, #fda085 100%); }

    .chart-container {
        position: relative;
        height: 300px;
        padding: 15px;
    }

    .chart-container-lg {
        height: 350px;
    }

    .chart-container-sm {
        height: 250px;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e3c72;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        font-size: 1.1rem;
        color: #3b82f6;
    }

    .mini-stat {
        padding: 15px;
        border-radius: 12px;
        background: #f8fafc;
        text-align: center;
        transition: all 0.3s ease;
    }

    .mini-stat:hover {
        background: #e2e8f0;
        transform: scale(1.02);
    }

    .mini-stat .number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e3c72;
    }

    .mini-stat .label {
        font-size: 0.75rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .hra-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        background: #f1f5f9;
        color: #475569;
        transition: all 0.3s ease;
    }

    .hra-badge:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
    }

    .hra-badge .count {
        background: #3b82f6;
        color: white;
        padding: 2px 8px;
        border-radius: 4px;
        font-weight: 700;
    }

    .progress-custom {
        height: 8px;
        border-radius: 4px;
        background-color: #e2e8f0;
        overflow: hidden;
    }

    .progress-custom .progress-bar {
        border-radius: 4px;
        transition: width 1s ease-in-out;
    }

    .top-item {
        padding: 12px 16px;
        border-radius: 8px;
        background: #f8fafc;
        margin-bottom: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s ease;
    }

    .top-item:hover {
        background: #e2e8f0;
        transform: translateX(5px);
    }

    .top-item .rank {
        width: 28px;
        height: 28px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .inspection-stat {
        text-align: center;
        padding: 20px;
    }

    .inspection-stat .circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-size: 1.5rem;
        font-weight: 700;
        color: white;
    }

    .recent-item {
        padding: 15px;
        border-bottom: 1px solid #e2e8f0;
        transition: background 0.3s ease;
    }

    .recent-item:last-child {
        border-bottom: none;
    }

    .recent-item:hover {
        background: #f8fafc;
    }

    .export-btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .export-btn:hover {
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .stat-card .stat-number {
            font-size: 1.75rem;
        }

        .chart-container {
            height: 250px;
        }

        .stat-card .stat-icon {
            font-size: 2rem;
        }
    }
</style>

<!-- Main Content -->
<div class="main-content">
    <!-- Header -->
    <div class="dashboard-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="mb-1"><i class="fas fa-chart-bar me-2 text-primary"></i>Reports & Analytics</h2>
                <p class="text-muted mb-0">Comprehensive data insights for your permit management system</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary btn-sm" onclick="location.reload()">
                    <i class="fas fa-sync-alt me-2"></i>Refresh
                </button>
                <button class="btn btn-success export-btn btn-sm" onclick="window.print()">
                    <i class="fas fa-print me-2"></i>Print Report
                </button>
            </div>
        </div>
    </div>

    <!-- Overview Statistics -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card gradient-blue">
                <div class="stat-number">{{ $totalPermits }}</div>
                <div class="stat-label">Total Permits</div>
                <i class="fas fa-file-alt stat-icon"></i>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card gradient-green">
                <div class="stat-number">{{ $activePermits }}</div>
                <div class="stat-label">Active Permits</div>
                <i class="fas fa-play-circle stat-icon"></i>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card gradient-purple">
                <div class="stat-number">{{ $completedPermits }}</div>
                <div class="stat-label">Completed</div>
                <i class="fas fa-check-circle stat-icon"></i>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card gradient-orange">
                <div class="stat-number">{{ $totalUsers }}</div>
                <div class="stat-label">Total Users</div>
                <i class="fas fa-users stat-icon"></i>
            </div>
        </div>
    </div>

    <!-- Additional Stats Row -->
    <div class="row g-4 mb-4">
        <div class="col-xl-2 col-md-4 col-6">
            <div class="mini-stat">
                <div class="number text-warning">{{ $pendingPermits }}</div>
                <div class="label">Pending</div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="mini-stat">
                <div class="number text-secondary">{{ $draftPermits }}</div>
                <div class="label">Draft</div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="mini-stat">
                <div class="number text-danger">{{ $expiredPermits }}</div>
                <div class="label">Expired</div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="mini-stat">
                <div class="number text-info">{{ $pendingExtension }}</div>
                <div class="label">Ext. Pending</div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="mini-stat">
                <div class="number text-danger">{{ $rejectedPermits }}</div>
                <div class="label">Rejected</div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="mini-stat">
                <div class="number text-primary">{{ $avgPermitDuration }} days</div>
                <div class="label">Avg Duration</div>
            </div>
        </div>
    </div>

    <!-- Status Distribution & HRA Overview Row -->
    <div class="row g-4 mb-4">
        <!-- Status Distribution -->
        <div class="col-xl-4">
            <div class="card report-card h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="section-title mb-0"><i class="fas fa-chart-pie"></i>Status Distribution</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 280px;">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hazard Risk Assessment Overview -->
        <div class="col-xl-8">
            <div class="card report-card h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="section-title mb-0"><i class="fas fa-exclamation-triangle"></i>Hazard Risk Assessment (HRA) Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-xl col-md-4 col-6">
                            <div class="hra-badge w-100 justify-content-between">
                                <span><i class="fas fa-hard-hat me-2 text-warning"></i>Work at Height</span>
                                <span class="count">{{ $hraWorkAtHeight }}</span>
                            </div>
                        </div>
                        <div class="col-xl col-md-4 col-6">
                            <div class="hra-badge w-100 justify-content-between">
                                <span><i class="fas fa-fire me-2 text-danger"></i>Hot Work</span>
                                <span class="count">{{ $hraHotWork }}</span>
                            </div>
                        </div>
                        <div class="col-xl col-md-4 col-6">
                            <div class="hra-badge w-100 justify-content-between">
                                <span><i class="fas fa-lock me-2 text-info"></i>LOTO/Isolation</span>
                                <span class="count">{{ $hraLotoIsolation }}</span>
                            </div>
                        </div>
                        <div class="col-xl col-md-4 col-6">
                            <div class="hra-badge w-100 justify-content-between">
                                <span><i class="fas fa-tools me-2 text-secondary"></i>Line Breaking</span>
                                <span class="count">{{ $hraLineBreaking }}</span>
                            </div>
                        </div>
                        <div class="col-xl col-md-4 col-6">
                            <div class="hra-badge w-100 justify-content-between">
                                <span><i class="fas fa-hard-hat me-2 text-warning"></i>Excavation</span>
                                <span class="count">{{ $hraExcavation }}</span>
                            </div>
                        </div>
                        <div class="col-xl col-md-4 col-6">
                            <div class="hra-badge w-100 justify-content-between">
                                <span><i class="fas fa-door-closed me-2 text-dark"></i>Confined Space</span>
                                <span class="count">{{ $hraConfinedSpace }}</span>
                            </div>
                        </div>
                        <div class="col-xl col-md-4 col-6">
                            <div class="hra-badge w-100 justify-content-between">
                                <span><i class="fas fa-bomb me-2 text-danger"></i>Explosive Atm.</span>
                                <span class="count">{{ $hraExplosiveAtmosphere }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="chart-container chart-container-sm">
                            <canvas id="hraChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Work Schedule Trend Section (Interactive) -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card report-card">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <h5 class="section-title mb-0"><i class="fas fa-calendar-alt"></i>Work Schedule Trend</h5>
                        <div class="d-flex gap-2 align-items-center flex-wrap">
                            <div class="btn-group" role="group" id="periodSelector">
                                <button type="button" class="btn btn-outline-primary btn-sm active" data-period="daily">Daily</button>
                                <button type="button" class="btn btn-outline-primary btn-sm" data-period="weekly">Weekly</button>
                                <button type="button" class="btn btn-outline-primary btn-sm" data-period="monthly">Monthly</button>
                                <button type="button" class="btn btn-outline-primary btn-sm" data-period="yearly">Yearly</button>
                            </div>
                            <select class="form-select form-select-sm" id="rangeSelector" style="width: auto;">
                                <option value="7">Last 7</option>
                                <option value="14">Last 14</option>
                                <option value="30" selected>Last 30</option>
                                <option value="60">Last 60</option>
                                <option value="90">Last 90</option>
                            </select>
                            <button class="btn btn-sm btn-outline-secondary" id="refreshTrendBtn">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>
                    <p class="text-muted small mt-2 mb-0">
                        <i class="fas fa-info-circle me-1"></i>Based on work schedule dates, not permit creation dates
                    </p>
                </div>
                <div class="card-body">
                    <div class="chart-container chart-container-lg" style="height: 400px;">
                        <canvas id="workScheduleChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- HRA Work Schedule Trend Section -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card report-card">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <h5 class="section-title mb-0"><i class="fas fa-exclamation-circle"></i>HRA Activities by Work Schedule</h5>
                        <div class="d-flex gap-2 align-items-center">
                            <span class="badge bg-light text-dark" id="hraPeriodLabel">Daily - Last 30</span>
                            <small class="text-muted">(Synced with above)</small>
                        </div>
                    </div>
                    <p class="text-muted small mt-2 mb-0">
                        <i class="fas fa-info-circle me-1"></i>HRA count based on actual work dates (start_datetime - end_datetime)
                    </p>
                </div>
                <div class="card-body">
                    <div class="chart-container chart-container-lg" style="height: 400px;">
                        <canvas id="hraScheduleChart"></canvas>
                    </div>
                    <div class="mt-3">
                        <div class="d-flex flex-wrap gap-2 justify-content-center" id="hraLegend">
                            <span class="badge" style="background-color: rgba(255, 193, 7, 0.8);"><i class="fas fa-hard-hat me-1"></i>Work at Height</span>
                            <span class="badge" style="background-color: rgba(220, 53, 69, 0.8);"><i class="fas fa-fire me-1"></i>Hot Work</span>
                            <span class="badge" style="background-color: rgba(23, 162, 184, 0.8);"><i class="fas fa-lock me-1"></i>LOTO/Isolation</span>
                            <span class="badge" style="background-color: rgba(108, 117, 125, 0.8);"><i class="fas fa-tools me-1"></i>Line Breaking</span>
                            <span class="badge" style="background-color: rgba(255, 152, 0, 0.8);"><i class="fas fa-hard-hat me-1"></i>Excavation</span>
                            <span class="badge" style="background-color: rgba(33, 37, 41, 0.8);"><i class="fas fa-door-closed me-1"></i>Confined Space</span>
                            <span class="badge" style="background-color: rgba(156, 39, 176, 0.8);"><i class="fas fa-bomb me-1"></i>Explosive Atm.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Area & Contractor Charts -->
    <div class="row g-4 mb-4">
        <!-- Permits by Area -->
        <div class="col-xl-6">
            <div class="card report-card h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="section-title mb-0"><i class="fas fa-map-marker-alt"></i>Top 10 Areas</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="areaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permits by Contractor -->
        <div class="col-xl-6">
            <div class="card report-card h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="section-title mb-0"><i class="fas fa-building"></i>Top 10 Contractors</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="contractorChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Statistics & Top Performers -->
    <div class="row g-4 mb-4">
        <!-- User Statistics -->
        <div class="col-xl-4">
            <div class="card report-card h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="section-title mb-0"><i class="fas fa-users"></i>User Distribution</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container chart-container-sm">
                        <canvas id="userChart"></canvas>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-4 text-center">
                            <div class="fw-bold text-primary h4 mb-0">{{ $adminUsers }}</div>
                            <small class="text-muted">Admins</small>
                        </div>
                        <div class="col-4 text-center">
                            <div class="fw-bold text-success h4 mb-0">{{ $bekaertUsers }}</div>
                            <small class="text-muted">Bekaert</small>
                        </div>
                        <div class="col-4 text-center">
                            <div class="fw-bold text-info h4 mb-0">{{ $contractorUsers }}</div>
                            <small class="text-muted">Contractor</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Permit Issuers -->
        <div class="col-xl-4">
            <div class="card report-card h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="section-title mb-0"><i class="fas fa-trophy"></i>Top Permit Issuers</h5>
                </div>
                <div class="card-body">
                    @forelse($topIssuers as $index => $issuer)
                    <div class="top-item">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rank">{{ $index + 1 }}</div>
                            <div>
                                <div class="fw-semibold">{{ $issuer['name'] }}</div>
                                <small class="text-muted">{{ $issuer['department'] ?? 'N/A' }}</small>
                            </div>
                        </div>
                        <span class="badge bg-primary rounded-pill">{{ $issuer['permit_count'] }}</span>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-info-circle fa-2x mb-2"></i>
                        <p>No data available</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Top Contractor Companies -->
        <div class="col-xl-4">
            <div class="card report-card h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="section-title mb-0"><i class="fas fa-medal"></i>Top Contractors</h5>
                </div>
                <div class="card-body">
                    @forelse($topContractorCompanies as $index => $company)
                    <div class="top-item">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rank">{{ $index + 1 }}</div>
                            <div class="fw-semibold text-truncate" style="max-width: 180px;">{{ $company['receiver_company_name'] }}</div>
                        </div>
                        <span class="badge bg-success rounded-pill">{{ $company['permit_count'] }}</span>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-info-circle fa-2x mb-2"></i>
                        <p>No data available</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Inspection Statistics -->
    <div class="row g-4 mb-4">
        <div class="col-xl-4">
            <div class="card report-card h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="section-title mb-0"><i class="fas fa-clipboard-check"></i>Inspection Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="inspection-stat">
                                <div class="circle gradient-blue">{{ $inspectionStats['total'] }}</div>
                                <div class="fw-semibold">Total</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="inspection-stat">
                                <div class="circle gradient-green">{{ $inspectionStats['passed'] }}</div>
                                <div class="fw-semibold">Passed</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="inspection-stat">
                                <div class="circle gradient-red">{{ $inspectionStats['failed'] }}</div>
                                <div class="fw-semibold">Failed</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="inspection-stat">
                                <div class="circle gradient-yellow">{{ $inspectionStats['pending'] }}</div>
                                <div class="fw-semibold">Pending</div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-semibold">Pass Rate</span>
                            <span class="fw-bold text-success">{{ $inspectionStats['passRate'] }}%</span>
                        </div>
                        <div class="progress-custom">
                            <div class="progress-bar bg-success" style="width: {{ $inspectionStats['passRate'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contractor & Area Stats -->
        <div class="col-xl-4">
            <div class="card report-card h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="section-title mb-0"><i class="fas fa-building"></i>Contractor Companies</h5>
                </div>
                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="text-center mb-4">
                        <div class="display-4 fw-bold text-primary">{{ $totalContractors }}</div>
                        <div class="text-muted">Registered Companies</div>
                    </div>
                    <div class="d-flex justify-content-around">
                        <div class="text-center">
                            <div class="h3 fw-bold text-success mb-0">{{ $activeContractors }}</div>
                            <small class="text-muted">Active</small>
                        </div>
                        <div class="text-center">
                            <div class="h3 fw-bold text-secondary mb-0">{{ $totalContractors - $activeContractors }}</div>
                            <small class="text-muted">Inactive</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Permits -->
        <div class="col-xl-4">
            <div class="card report-card h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="section-title mb-0"><i class="fas fa-history"></i>Recent Activity</h5>
                </div>
                <div class="card-body p-0" style="max-height: 350px; overflow-y: auto;">
                    @forelse($recentPermits as $permit)
                    <div class="recent-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-semibold">{{ $permit->permit_number }}</div>
                                <small class="text-muted">{{ Str::limit($permit->work_title, 30) }}</small>
                            </div>
                            <span class="badge 
                                @if($permit->status === 'active') bg-success
                                @elseif($permit->status === 'pending_approval') bg-warning
                                @elseif($permit->status === 'completed') bg-primary
                                @elseif($permit->status === 'expired') bg-danger
                                @else bg-secondary
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $permit->status)) }}
                            </span>
                        </div>
                        <small class="text-muted">{{ $permit->created_at->diffForHumans() }}</small>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <p>No recent permits</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Department Distribution -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card report-card">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="section-title mb-0"><i class="fas fa-sitemap"></i>Permits by Department</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="departmentChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.sidebar-scripts')

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Color palette
    const colors = {
        primary: 'rgba(59, 130, 246, 1)',
        primaryLight: 'rgba(59, 130, 246, 0.2)',
        success: 'rgba(34, 197, 94, 1)',
        successLight: 'rgba(34, 197, 94, 0.2)',
        warning: 'rgba(234, 179, 8, 1)',
        warningLight: 'rgba(234, 179, 8, 0.2)',
        danger: 'rgba(239, 68, 68, 1)',
        dangerLight: 'rgba(239, 68, 68, 0.2)',
        purple: 'rgba(168, 85, 247, 1)',
        purpleLight: 'rgba(168, 85, 247, 0.2)',
        cyan: 'rgba(6, 182, 212, 1)',
        cyanLight: 'rgba(6, 182, 212, 0.2)',
    };

    const gradientColors = [
        'rgba(102, 126, 234, 0.8)', // blue-purple
        'rgba(17, 153, 142, 0.8)',  // teal
        'rgba(240, 147, 251, 0.8)', // pink
        'rgba(79, 172, 254, 0.8)',  // sky blue
        'rgba(255, 65, 108, 0.8)',  // red-pink
        'rgba(67, 233, 123, 0.8)',  // green
        'rgba(95, 114, 189, 0.8)',  // indigo
        'rgba(246, 211, 101, 0.8)', // yellow
    ];

    // Status Distribution Chart
    const statusData = @json($permitsByStatus);
    const statusColors = {
        'draft': '#78909C',
        'pending_approval': '#FFA726',
        'active': '#66BB6A',
        'expired': '#EF5350',
        'completed': '#42A5F5',
        'rejected': '#E53935',
        'pending_extension_approval': '#FFB300'
    };

    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: statusData.map(s => s.status.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())),
            datasets: [{
                data: statusData.map(s => s.count),
                backgroundColor: statusData.map(s => statusColors[s.status] || '#9E9E9E'),
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 15, usePointStyle: true }
                }
            },
            cutout: '60%'
        }
    });

    // HRA Chart
    const hraData = {
        labels: ['Work at Height', 'Hot Work', 'LOTO/Isolation', 'Line Breaking', 'Excavation', 'Confined Space', 'Explosive Atm.'],
        values: [{{ $hraWorkAtHeight }}, {{ $hraHotWork }}, {{ $hraLotoIsolation }}, {{ $hraLineBreaking }}, {{ $hraExcavation }}, {{ $hraConfinedSpace }}, {{ $hraExplosiveAtmosphere }}]
    };

    new Chart(document.getElementById('hraChart'), {
        type: 'bar',
        data: {
            labels: hraData.labels,
            datasets: [{
                label: 'Permits with HRA',
                data: hraData.values,
                backgroundColor: [
                    'rgba(255, 193, 7, 0.8)',   // yellow - work at height
                    'rgba(220, 53, 69, 0.8)',   // red - hot work
                    'rgba(23, 162, 184, 0.8)',  // cyan - loto
                    'rgba(108, 117, 125, 0.8)', // gray - line breaking
                    'rgba(255, 152, 0, 0.8)',   // orange - excavation
                    'rgba(33, 37, 41, 0.8)',    // dark - confined
                    'rgba(220, 53, 69, 0.8)',   // red - explosive
                ],
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                y: { grid: { display: false } }
            }
        }
    });

    // Area Chart
    const areaData = @json($permitsByArea);
    new Chart(document.getElementById('areaChart'), {
        type: 'bar',
        data: {
            labels: areaData.map(a => a.area_name || 'Unknown'),
            datasets: [{
                label: 'Permits',
                data: areaData.map(a => a.count),
                backgroundColor: gradientColors,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                y: { grid: { display: false } }
            }
        }
    });

    // Contractor Chart
    const contractorData = @json($permitsByContractor);
    new Chart(document.getElementById('contractorChart'), {
        type: 'bar',
        data: {
            labels: contractorData.map(c => c.receiver_company_name ? c.receiver_company_name.substring(0, 20) : 'Unknown'),
            datasets: [{
                label: 'Permits',
                data: contractorData.map(c => c.count),
                backgroundColor: gradientColors.slice().reverse(),
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                y: { grid: { display: false } }
            }
        }
    });

    // User Distribution Chart
    new Chart(document.getElementById('userChart'), {
        type: 'doughnut',
        data: {
            labels: ['Administrators', 'Bekaert', 'Contractors'],
            datasets: [{
                data: [{{ $adminUsers }}, {{ $bekaertUsers }}, {{ $contractorUsers }}],
                backgroundColor: [colors.primary, colors.success, colors.cyan],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            cutout: '70%'
        }
    });

    // Department Chart
    const deptData = @json($permitsByDepartment);
    new Chart(document.getElementById('departmentChart'), {
        type: 'bar',
        data: {
            labels: deptData.map(d => d.department || 'Unknown'),
            datasets: [{
                label: 'Permits',
                data: deptData.map(d => d.count),
                backgroundColor: gradientColors,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: { grid: { display: false } }
            }
        }
    });

    // Work Schedule Trend Charts (Interactive)
    let workScheduleChart = null;
    let hraScheduleChart = null;
    let currentPeriod = 'daily';
    let currentRange = 30;

    const hraColors = {
        workAtHeight: 'rgba(255, 193, 7, 0.8)',
        hotWork: 'rgba(220, 53, 69, 0.8)',
        lotoIsolation: 'rgba(23, 162, 184, 0.8)',
        lineBreaking: 'rgba(108, 117, 125, 0.8)',
        excavation: 'rgba(255, 152, 0, 0.8)',
        confinedSpace: 'rgba(33, 37, 41, 0.8)',
        explosiveAtmosphere: 'rgba(156, 39, 176, 0.8)',
    };

    // Initialize work schedule charts
    async function loadWorkScheduleTrend() {
        try {
            const response = await fetch(`{{ route('reports.work-schedule-trend') }}?period=${currentPeriod}&range=${currentRange}`);
            const data = await response.json();

            // Update period label
            document.getElementById('hraPeriodLabel').textContent = 
                `${currentPeriod.charAt(0).toUpperCase() + currentPeriod.slice(1)} - Last ${currentRange}`;

            // Update Work Schedule Chart
            if (workScheduleChart) {
                workScheduleChart.destroy();
            }

            workScheduleChart = new Chart(document.getElementById('workScheduleChart'), {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Active Work (Permits)',
                        data: data.workCounts,
                        borderColor: colors.primary,
                        backgroundColor: colors.primaryLight,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: colors.primary,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                title: function(context) {
                                    return `${currentPeriod.charAt(0).toUpperCase() + currentPeriod.slice(1)}: ${context[0].label}`;
                                },
                                label: function(context) {
                                    return `${context.dataset.label}: ${context.parsed.y} permit(s)`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            title: {
                                display: true,
                                text: 'Number of Permits'
                            }
                        },
                        x: {
                            grid: { display: false },
                            title: {
                                display: true,
                                text: currentPeriod.charAt(0).toUpperCase() + currentPeriod.slice(1)
                            }
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });

            // Update HRA Schedule Chart
            if (hraScheduleChart) {
                hraScheduleChart.destroy();
            }

            hraScheduleChart = new Chart(document.getElementById('hraScheduleChart'), {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: 'Work at Height',
                            data: data.hraCounts.workAtHeight,
                            backgroundColor: hraColors.workAtHeight,
                            borderRadius: 4,
                            stack: 'stack0'
                        },
                        {
                            label: 'Hot Work',
                            data: data.hraCounts.hotWork,
                            backgroundColor: hraColors.hotWork,
                            borderRadius: 4,
                            stack: 'stack0'
                        },
                        {
                            label: 'LOTO/Isolation',
                            data: data.hraCounts.lotoIsolation,
                            backgroundColor: hraColors.lotoIsolation,
                            borderRadius: 4,
                            stack: 'stack0'
                        },
                        {
                            label: 'Line Breaking',
                            data: data.hraCounts.lineBreaking,
                            backgroundColor: hraColors.lineBreaking,
                            borderRadius: 4,
                            stack: 'stack0'
                        },
                        {
                            label: 'Excavation',
                            data: data.hraCounts.excavation,
                            backgroundColor: hraColors.excavation,
                            borderRadius: 4,
                            stack: 'stack0'
                        },
                        {
                            label: 'Confined Space',
                            data: data.hraCounts.confinedSpace,
                            backgroundColor: hraColors.confinedSpace,
                            borderRadius: 4,
                            stack: 'stack0'
                        },
                        {
                            label: 'Explosive Atm.',
                            data: data.hraCounts.explosiveAtmosphere,
                            backgroundColor: hraColors.explosiveAtmosphere,
                            borderRadius: 4,
                            stack: 'stack0'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            display: false // We have custom legend below
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                title: function(context) {
                                    return `${currentPeriod.charAt(0).toUpperCase() + currentPeriod.slice(1)}: ${context[0].label}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            stacked: true,
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            title: {
                                display: true,
                                text: 'Number of HRA Activities'
                            }
                        },
                        x: {
                            stacked: true,
                            grid: { display: false },
                            title: {
                                display: true,
                                text: currentPeriod.charAt(0).toUpperCase() + currentPeriod.slice(1)
                            }
                        }
                    }
                }
            });

        } catch (error) {
            console.error('Error loading work schedule trend:', error);
        }
    }

    // Period selector event listeners
    document.querySelectorAll('#periodSelector button').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('#periodSelector button').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentPeriod = this.dataset.period;
            
            // Adjust default range based on period
            const rangeSelect = document.getElementById('rangeSelector');
            if (currentPeriod === 'yearly') {
                rangeSelect.innerHTML = `
                    <option value="3">Last 3</option>
                    <option value="5" selected>Last 5</option>
                    <option value="10">Last 10</option>
                `;
                currentRange = 5;
            } else if (currentPeriod === 'monthly') {
                rangeSelect.innerHTML = `
                    <option value="6">Last 6</option>
                    <option value="12" selected>Last 12</option>
                    <option value="24">Last 24</option>
                `;
                currentRange = 12;
            } else if (currentPeriod === 'weekly') {
                rangeSelect.innerHTML = `
                    <option value="4">Last 4</option>
                    <option value="8" selected>Last 8</option>
                    <option value="12">Last 12</option>
                    <option value="26">Last 26</option>
                `;
                currentRange = 8;
            } else {
                rangeSelect.innerHTML = `
                    <option value="7">Last 7</option>
                    <option value="14">Last 14</option>
                    <option value="30" selected>Last 30</option>
                    <option value="60">Last 60</option>
                    <option value="90">Last 90</option>
                `;
                currentRange = 30;
            }
            
            loadWorkScheduleTrend();
        });
    });

    // Range selector event listener
    document.getElementById('rangeSelector').addEventListener('change', function() {
        currentRange = parseInt(this.value);
        loadWorkScheduleTrend();
    });

    // Refresh button
    document.getElementById('refreshTrendBtn').addEventListener('click', function() {
        loadWorkScheduleTrend();
    });

    // Initial load
    loadWorkScheduleTrend();
});
</script>

@endsection

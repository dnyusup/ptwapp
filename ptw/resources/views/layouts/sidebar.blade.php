<!-- Mobile menu toggle -->
<button class="mobile-menu-toggle" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
</button>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="brand-icon">
            <i class="fas fa-shield-alt"></i>
        </div>
        <h5 class="text-white mb-0">PTW Portal</h5>
        <div class="user-info">
            <div class="user-name">{{ auth()->user()->name }}</div>
            <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
        </div>
    </div>
    
    <nav class="nav flex-column sidebar-nav">
        @if(auth()->user()->role !== 'contractor')
        <a class="nav-link {{ Request::routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <i class="fas fa-chart-line"></i>Dashboard
        </a>
        @endif
        <a class="nav-link {{ Request::routeIs('permits.*') ? 'active' : '' }}" href="{{ route('permits.index') }}">
            <i class="fas fa-clipboard-list"></i>Permits
        </a>
        @if(auth()->user()->role !== 'contractor')
        <a class="nav-link {{ Request::routeIs('permits.create') ? 'active' : '' }}" href="{{ route('permits.create') }}">
            <i class="fas fa-plus-circle"></i>New Permit
        </a>
        @endif
        @if(auth()->user()->role === 'administrator' || (auth()->user()->role === 'bekaert' && auth()->user()->department === 'EHS'))
        <a class="nav-link {{ Request::routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
            <i class="fas fa-users-cog"></i>Users
        </a>
        <a class="nav-link {{ Request::routeIs('kontraktor-lists.*') ? 'active' : '' }}" href="{{ route('kontraktor-lists.index') }}">
            <i class="fas fa-building"></i>Kontraktors
        </a>
        @endif
        @if(auth()->user()->role === 'contractor' && auth()->user()->company_id)
        <a class="nav-link {{ Request::routeIs('contractor-users.*') ? 'active' : '' }}" href="{{ route('contractor-users.index') }}">
            <i class="fas fa-users"></i>Team
        </a>
        @endif
        @if(auth()->user()->role === 'administrator')
        <a class="nav-link" href="#">
            <i class="fas fa-chart-bar"></i>Reports
        </a>
        <a class="nav-link" href="#">
            <i class="fas fa-cog"></i>Settings
        </a>
        @endif
        <a class="nav-link {{ Request::routeIs('profile') ? 'active' : '' }}" href="{{ route('profile') }}">
            <i class="fas fa-user-circle"></i>Profile
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fas fa-sign-out-alt me-2"></i>Logout
            </button>
        </form>
    </div>
</div>

<!-- Sidebar overlay for mobile -->
<div class="sidebar-overlay" onclick="toggleSidebar()"></div>

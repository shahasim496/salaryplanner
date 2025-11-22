@props(['active' => '', 'subtitle' => '', 'showUser' => false])

    <aside class="planner-sidebar">
        <div class="sidebar-brand">
            <div class="brand-mark">SP</div>
            <div>
                <strong>Salary Planner</strong>
                <small style="opacity:0.7;">{{ $subtitle ?: 'Planning' }}</small>
            </div>
        </div>
        @if($showUser)
            <div class="sidebar-user">
                <span>{{ Auth::user()->name }}</span>
                <small style="opacity:0.7;">Monthly strategist</small>
            </div>
        @endif
        <nav class="sidebar-nav">
        <a href="{{ route('dashboard') }}" class="sidebar-link {{ $active === 'dashboard' ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001 1h4a1 1 0 001-1m-6 0V9" />
            </svg>
            Dashboard
        </a>
        <a href="{{ route('salary-plans.create') }}" class="sidebar-link {{ $active === 'create' ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            New Plan
        </a>
        <a href="{{ route('salary-plans.index') }}" class="sidebar-link {{ $active === 'index' ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5h6M9 9h6M9 13h6m-9 4h12" />
            </svg>
            All Plans
        </a>
        @if (Route::has('profile.show'))
            <a href="{{ route('profile.show') }}" class="sidebar-link {{ $active === 'profile' ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Profile
            </a>
        @endif
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-button">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1" />
                </svg>
                Log out
            </button>
        </form>
    </nav>
</aside>


<x-guest-layout>
    <!-- Session Status -->
    @if(session('status'))
        <div style="padding: 12px 16px; background: #ecfdf5; border-left: 4px solid #22c55e; border-radius: 8px; margin-bottom: 24px; color: #16a34a; font-weight: 600; font-size: 14px;">
            {{ session('status') }}
        </div>
    @endif

    <div style="margin-bottom: 32px;">
        <h2 style="margin: 0 0 8px 0; font-size: 32px; font-weight: 700; color: var(--text-dark);">Welcome back</h2>
        <p style="margin: 0; color: var(--muted); font-size: 16px;">Sign in to your account to continue planning</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div style="margin-bottom: 20px;">
            <label for="email" style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-weight: 600; color: var(--text-dark); font-size: 14px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
                Email
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                   class="planner-input" 
                   style="width: 100%; padding: 14px 16px; border: 2px solid rgba(0,0,0,0.08); border-radius: 12px; font-size: 15px; transition: all 0.2s; background: #fafafa;"
                   onfocus="this.style.borderColor='var(--accent)'; this.style.outline='none'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(249,115,22,0.1)';"
                   onblur="this.style.borderColor='rgba(0,0,0,0.08)'; this.style.background='#fafafa'; this.style.boxShadow='none';">
            @error('email')
                <p style="margin: 8px 0 0 0; color: #dc2626; font-size: 13px; font-weight: 500;">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div style="margin-bottom: 20px;">
            <label for="password" style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-weight: 600; color: var(--text-dark); font-size: 14px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                Password
            </label>
            <input id="password" type="password" name="password" required autocomplete="current-password" 
                   class="planner-input" 
                   style="width: 100%; padding: 14px 16px; border: 2px solid rgba(0,0,0,0.08); border-radius: 12px; font-size: 15px; transition: all 0.2s; background: #fafafa;"
                   onfocus="this.style.borderColor='var(--accent)'; this.style.outline='none'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(249,115,22,0.1)';"
                   onblur="this.style.borderColor='rgba(0,0,0,0.08)'; this.style.background='#fafafa'; this.style.boxShadow='none';">
            @error('password')
                <p style="margin: 8px 0 0 0; color: #dc2626; font-size: 13px; font-weight: 500;">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <label for="remember_me" style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                <input id="remember_me" type="checkbox" name="remember" 
                       style="width: 18px; height: 18px; accent-color: var(--accent); cursor: pointer;">
                <span style="font-size: 14px; color: var(--text-dark); font-weight: 500;">Remember me</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" 
                   style="font-size: 14px; color: var(--accent); text-decoration: none; font-weight: 600; transition: color 0.2s;"
                   onmouseover="this.style.color='#f97316';"
                   onmouseout="this.style.color='var(--accent)';">
                    Forgot password?
                </a>
            @endif
        </div>

        <div style="display: flex; flex-direction: column; gap: 12px;">
            <button type="submit" 
                    class="primary-btn" 
                    style="width: 100%; padding: 14px; font-weight: 700; font-size: 15px; text-align: center; border: none; border-radius: 10px; background: var(--accent); color: white; cursor: pointer; transition: all 0.2s;"
                    onmouseover="this.style.background='#f97316'; this.style.transform='translateY(-1px)';"
                    onmouseout="this.style.background='var(--accent)'; this.style.transform='translateY(0)';">
                Log in
            </button>
            <div style="text-align: center; margin-top: 8px;">
                <span style="font-size: 14px; color: var(--muted);">Don't have an account? </span>
                <a href="{{ route('register') }}" 
                   style="font-size: 14px; color: var(--accent); text-decoration: none; font-weight: 700; transition: color 0.2s;"
                   onmouseover="this.style.color='#f97316';"
                   onmouseout="this.style.color='var(--accent)';">
                    Register
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>

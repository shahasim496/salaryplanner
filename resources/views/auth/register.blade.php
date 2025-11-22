<x-guest-layout>
    <div style="margin-bottom: 32px;">
        <h2 style="margin: 0 0 8px 0; font-size: 32px; font-weight: 700; color: var(--text-dark);">Create your account</h2>
        <p style="margin: 0; color: var(--muted); font-size: 16px;">Start planning your salary with clarity and confidence</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div style="margin-bottom: 20px;">
            <label for="name" style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-weight: 600; color: var(--text-dark); font-size: 14px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                Name
            </label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" 
                   class="planner-input" 
                   style="width: 100%; padding: 14px 16px; border: 2px solid rgba(0,0,0,0.08); border-radius: 12px; font-size: 15px; transition: all 0.2s; background: #fafafa;"
                   onfocus="this.style.borderColor='var(--accent)'; this.style.outline='none'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(249,115,22,0.1)';"
                   onblur="this.style.borderColor='rgba(0,0,0,0.08)'; this.style.background='#fafafa'; this.style.boxShadow='none';">
            @error('name')
                <p style="margin: 8px 0 0 0; color: #dc2626; font-size: 13px; font-weight: 500;">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email Address -->
        <div style="margin-bottom: 20px;">
            <label for="email" style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-weight: 600; color: var(--text-dark); font-size: 14px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
                Email
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" 
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
            <input id="password" type="password" name="password" required autocomplete="new-password" 
                   class="planner-input" 
                   style="width: 100%; padding: 14px 16px; border: 2px solid rgba(0,0,0,0.08); border-radius: 12px; font-size: 15px; transition: all 0.2s; background: #fafafa;"
                   onfocus="this.style.borderColor='var(--accent)'; this.style.outline='none'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(249,115,22,0.1)';"
                   onblur="this.style.borderColor='rgba(0,0,0,0.08)'; this.style.background='#fafafa'; this.style.boxShadow='none';">
            @error('password')
                <p style="margin: 8px 0 0 0; color: #dc2626; font-size: 13px; font-weight: 500;">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div style="margin-bottom: 24px;">
            <label for="password_confirmation" style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-weight: 600; color: var(--text-dark); font-size: 14px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                Confirm Password
            </label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" 
                   class="planner-input" 
                   style="width: 100%; padding: 14px 16px; border: 2px solid rgba(0,0,0,0.08); border-radius: 12px; font-size: 15px; transition: all 0.2s; background: #fafafa;"
                   onfocus="this.style.borderColor='var(--accent)'; this.style.outline='none'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(249,115,22,0.1)';"
                   onblur="this.style.borderColor='rgba(0,0,0,0.08)'; this.style.background='#fafafa'; this.style.boxShadow='none';">
            @error('password_confirmation')
                <p style="margin: 8px 0 0 0; color: #dc2626; font-size: 13px; font-weight: 500;">{{ $message }}</p>
            @enderror
        </div>

        <div style="display: flex; flex-direction: column; gap: 12px;">
            <button type="submit" 
                    class="primary-btn" 
                    style="width: 100%; padding: 14px; font-weight: 700; font-size: 15px; text-align: center; border: none; border-radius: 10px; background: var(--accent); color: white; cursor: pointer; transition: all 0.2s;"
                    onmouseover="this.style.background='#f97316'; this.style.transform='translateY(-1px)';"
                    onmouseout="this.style.background='var(--accent)'; this.style.transform='translateY(0)';">
                Register
            </button>
            <div style="text-align: center; margin-top: 8px;">
                <span style="font-size: 14px; color: var(--muted);">Already have an account? </span>
                <a href="{{ route('login') }}" 
                   style="font-size: 14px; color: var(--accent); text-decoration: none; font-weight: 700; transition: color 0.2s;"
                   onmouseover="this.style.color='#f97316';"
                   onmouseout="this.style.color='var(--accent)';">
                    Log in
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>

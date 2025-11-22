<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Salary Planner') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="{{ asset('css/planner.css') }}">
        
        <style>
            @keyframes gradientShift {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            
            @keyframes float {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                50% { transform: translateY(-20px) rotate(5deg); }
            }
            
            @keyframes pulse {
                0%, 100% { opacity: 0.6; }
                50% { opacity: 1; }
            }
            
            .auth-background {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 30%, #f093fb 60%, #4facfe 100%);
                background-size: 400% 400%;
                animation: gradientShift 15s ease infinite;
                z-index: -2;
            }
            
            .auth-background::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: 
                    radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                    radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                    radial-gradient(circle at 40% 20%, rgba(255, 255, 255, 0.08) 0%, transparent 50%);
                z-index: -1;
            }
            
            .decorative-shape {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                animation: float 6s ease-in-out infinite;
            }
            
            .shape-1 {
                width: 300px;
                height: 300px;
                top: -150px;
                right: -150px;
                animation-delay: 0s;
            }
            
            .shape-2 {
                width: 200px;
                height: 200px;
                bottom: -100px;
                left: -100px;
                animation-delay: 2s;
            }
            
            .shape-3 {
                width: 150px;
                height: 150px;
                top: 50%;
                right: 10%;
                animation-delay: 4s;
            }
            
            .logo-icon {
                width: 64px;
                height: 64px;
                background: linear-gradient(135deg, #f97316 0%, #fb923c 100%);
                border-radius: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 800;
                font-size: 24px;
                color: white;
                box-shadow: 0 8px 24px rgba(249, 115, 22, 0.4);
                position: relative;
                overflow: hidden;
            }
            
            .logo-icon::before {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: linear-gradient(45deg, transparent, rgba(255,255,255,0.3), transparent);
                animation: pulse 3s ease-in-out infinite;
            }
            
            .auth-container {
                position: relative;
                z-index: 1;
                width: 100%;
                max-width: 1400px;
                margin: 0 auto;
                padding: 40px;
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 60px;
                align-items: center;
                min-height: 100vh;
            }
            
            .auth-logo-section {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: flex-start;
                padding: 40px;
            }
            
            .big-logo-icon {
                width: 120px;
                height: 120px;
                background: linear-gradient(135deg, #f97316 0%, #fb923c 100%);
                border-radius: 24px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 800;
                font-size: 48px;
                color: white;
                box-shadow: 0 20px 60px rgba(249, 115, 22, 0.4);
                position: relative;
                overflow: hidden;
                margin-bottom: 32px;
            }
            
            .big-logo-icon::before {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: linear-gradient(45deg, transparent, rgba(255,255,255,0.3), transparent);
                animation: pulse 3s ease-in-out infinite;
            }
            
            .auth-form-section {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
            }
            
            .auth-card {
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(30px);
                border-radius: 24px;
                padding: 48px;
                width: 100%;
                max-width: 500px;
                box-shadow: 
                    0 20px 60px rgba(0,0,0,0.15),
                    0 0 0 1px rgba(255,255,255,0.5) inset;
                border: 1px solid rgba(255,255,255,0.8);
                position: relative;
                overflow: hidden;
            }
            
            .auth-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 4px;
                background: linear-gradient(90deg, #f97316, #fb923c, #f97316);
                background-size: 200% 100%;
                animation: gradientShift 3s ease infinite;
            }
            
            @media (max-width: 968px) {
                .auth-container {
                    grid-template-columns: 1fr;
                    gap: 40px;
                    padding: 20px;
                }
                
                .auth-logo-section {
                    text-align: center;
                    align-items: center;
                    padding: 20px;
                }
                
                .big-logo-icon {
                    width: 80px;
                    height: 80px;
                    font-size: 32px;
                    margin: 0 auto 24px;
                }
            }
        </style>
    </head>
    <body style="font-family: 'Inter', sans-serif; min-height: 100vh; position: relative; overflow-x: hidden;">
        <div class="auth-background"></div>
        <div class="decorative-shape shape-1"></div>
        <div class="decorative-shape shape-2"></div>
        <div class="decorative-shape shape-3"></div>
        
        <div class="auth-container">
            <div class="auth-logo-section">
                <a href="/" style="text-decoration: none; display: flex; flex-direction: column; align-items: flex-start; gap: 24px;">
                    <div class="big-logo-icon">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 style="margin: 0 0 12px 0; font-size: 56px; font-weight: 800; color: white; text-shadow: 0 4px 20px rgba(0,0,0,0.3); letter-spacing: -1px; line-height: 1.1;">Salary Planner</h1>
                        <p style="margin: 0 0 8px 0; color: rgba(255,255,255,0.95); font-weight: 600; font-size: 20px; line-height: 1.4;">Plan every paycheck<br>with clarity and confidence</p>
                        <p style="margin: 24px 0 0 0; color: rgba(255,255,255,0.8); font-size: 16px; line-height: 1.6; max-width: 400px;">
                            Take control of your finances. Track income, manage expenses, save for goals, and invest wisely—all in one place.
                        </p>
                    </div>
                </a>
            </div>

            <div class="auth-form-section">
                <div class="auth-card">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>

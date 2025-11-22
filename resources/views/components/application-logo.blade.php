<svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
    <!-- Professional Salary Planner Logo -->
    <defs>
        <linearGradient id="logoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#667eea;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#764ba2;stop-opacity:1" />
        </linearGradient>
    </defs>
    
    <!-- Background Circle -->
    <circle cx="50" cy="50" r="48" fill="url(#logoGradient)"/>
    
    <!-- Dollar Sign -->
    <path d="M50 20 L50 80 M45 30 L55 30 M45 70 L55 70" 
          stroke="white" 
          stroke-width="4" 
          stroke-linecap="round"/>
    
    <!-- Chart Bars -->
    <rect x="60" y="45" width="8" height="20" fill="white" rx="2"/>
    <rect x="72" y="40" width="8" height="25" fill="white" rx="2"/>
    
    <!-- Savings Arrow -->
    <path d="M30 50 L20 50 L25 45 M20 50 L25 55" 
          stroke="white" 
          stroke-width="3" 
          stroke-linecap="round" 
          stroke-linejoin="round" 
          fill="none"/>
</svg>

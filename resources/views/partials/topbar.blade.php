  <!-- =========================================================================
       1. TOP BAR
       ========================================================================= -->
  <aside class="top-bar">
    <div class="container">
      <div class="top-bar-left">
        <span class="pulse-dot"></span>
        <span class="top-bar-badge">Authorized Global Visa Consultancy</span>
        <span class="top-bar-hours">Sat – Thu: 9:00 AM – 8:00 PM</span>
      </div>
      <div class="top-bar-right">
        <a href="tel:+8801XXXXXXXXX" class="top-bar-link">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
          </svg>
          +880 1XXX-XXXXXX
        </a>
        <a href="mailto:info@adibatech.com" class="top-bar-link">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <rect width="20" height="16" x="2" y="4" rx="2"/>
            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
          </svg>
          info@adibatech.com
        </a>
        @auth
          <a href="{{ route('dashboard') }}" class="top-bar-link top-bar-portal-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
              <path d="m9 12 2 2 4-4"/>
            </svg>
            My Vault ({{ Auth::user()->name }})
          </a>
        @else
          <a href="{{ route('login') }}" class="top-bar-link top-bar-portal-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
              <polyline points="10 17 15 12 10 7"/>
              <line x1="15" y1="12" x2="3" y2="12"/>
            </svg>
            Client Login
          </a>
        @endauth
      </div>
    </div>
  </aside>

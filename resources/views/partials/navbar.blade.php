<!-- =========================================================================
       2. MAIN NAVBAR
       ========================================================================= -->
<header class="site-header">
  <div class="container">
    <nav class="navbar">
      <a href="{{ url('/') }}" class="site-logo">
        <img src="{{ asset('assets/images/logo.png') }}" alt="Adiba Global - A Concern of AdibaTech.com"
          class="logo-img">
      </a>

      <ul class="nav-links" id="navLinks">
        <li><a href="{{ url('/') }}" class="nav-link {{ ($activePage ?? '') === 'home' ? 'active' : '' }}">Home</a></li>
        <li><a href="{{ url('/services.html') }}" class="nav-link {{ ($activePage ?? '') === 'services' ? 'active' : '' }}">Visa Services</a></li>
        <li><a href="{{ url('/countries.html') }}" class="nav-link {{ ($activePage ?? '') === 'countries' ? 'active' : '' }}">Countries</a></li>
        <li><a href="{{ ($activePage ?? '') === 'home' ? '#process' : url('/#process') }}" class="nav-link">Process</a></li>
        <li><a href="{{ url('/about.html') }}" class="nav-link {{ ($activePage ?? '') === 'about' ? 'active' : '' }}">About Us</a></li>
        <li><a href="{{ url('/blog.html') }}" class="nav-link {{ ($activePage ?? '') === 'blog' ? 'active' : '' }}">Blog</a></li>
        <li><a href="{{ url('/contact.html') }}" class="nav-link {{ ($activePage ?? '') === 'contact' ? 'active' : '' }}">Contact</a></li>
        @auth
          <li class="mobile-only-nav-item"><a href="{{ route('dashboard') }}" class="nav-link"
              style="color: var(--color-brand-blue); font-weight: 700;">📂 My Document Vault</a></li>
        @else
          <li class="mobile-only-nav-item"><a href="{{ route('login') }}" class="nav-link"
              style="color: var(--color-brand-blue); font-weight: 700;">🔑 Client Login</a></li>
          <li class="mobile-only-nav-item"><a href="{{ route('register') }}" class="nav-link">📝 Register</a></li>
        @endauth
      </ul>

      <div style="display: flex; align-items: center; gap: 10px;">
        @auth
          <a href="{{ route('dashboard') }}" class="header-portal-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" width="16" height="16">
              <path d="M3 7v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-6l-2-2H5a2 2 0 0 0-2 2z"></path>
            </svg>
            <span>My Vault</span>
          </a>
        @else
          <a href="{{ route('login') }}" class="header-login-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" width="15" height="15">
              <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
              <polyline points="10 17 15 12 10 7" />
              <line x1="15" y1="12" x2="3" y2="12" />
            </svg>
            <span>Login</span>
          </a>
          <a href="{{ route('register') }}" class="header-register-btn">
            Register &rarr;
          </a>
        @endauth

        <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle menu">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round">
            <line x1="4" x2="20" y1="12" y2="12" />
            <line x1="4" x2="20" y1="6" y2="6" />
            <line x1="4" x2="20" y1="18" y2="18" />
          </svg>
        </button>
      </div>
    </nav>
  </div>
</header>
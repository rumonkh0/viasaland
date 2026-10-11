<!-- =========================================================================
       UNIVERSAL FOOTER
       ========================================================================= -->
<footer class="site-footer" id="contact">
  <div class="container">
    <div class="footer-grid">
      <!-- Col 1: Brand -->
      <div class="footer-brand">
        <img src="{{ asset('assets/images/logo.png') }}" alt="Adiba Global" class="footer-logo">
      </div>

      <!-- Col 2: Quick Links -->
      <div class="footer-col footer-accordion">
        <button class="footer-col-title footer-accordion-btn" aria-expanded="false">
          <span>Quick Links</span>
          <svg class="accordion-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
            stroke-linecap="round" stroke-linejoin="round">
            <polyline points="6 9 12 15 18 9" />
          </svg>
        </button>
        <div class="footer-accordion-panel">
          <ul class="footer-links-list">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li><a href="{{ url('/services.html') }}">Visa Services</a></li>
            <li><a href="{{ url('/countries.html') }}">Countries</a></li>
            <li><a href="{{ url('/about.html') }}">About Us</a></li>
            <li><a href="{{ url('/contact.html') }}">Contact</a></li>
            <li><a href="{{ route('login') }}" style="color: var(--color-brand-blue); font-weight: 600;">Client
                Login</a></li>
            <li><a href="{{ route('dashboard') }}" style="color: var(--color-brand-blue); font-weight: 600;">Document
                Vault</a></li>
          </ul>
        </div>
      </div>

      <!-- Col 3: Our Services -->
      <div class="footer-col footer-accordion">
        <button class="footer-col-title footer-accordion-btn" aria-expanded="false">
          <span>Our Services</span>
          <svg class="accordion-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
            stroke-linecap="round" stroke-linejoin="round">
            <polyline points="6 9 12 15 18 9" />
          </svg>
        </button>
        <div class="footer-accordion-panel">
          <ul class="footer-links-list">
            <li><a href="{{ url('/student-visa.html') }}">Student Visa</a></li>
            <li><a href="{{ url('/work-visa.html') }}">Work Visa</a></li>
            <li><a href="{{ url('/tourist-visa.html') }}">Tourist Visa</a></li>
            <li><a href="{{ url('/business-visa.html') }}">Business Visa</a></li>
            <li><a href="{{ url('/umrah-visa.html') }}">Umrah Visa</a></li>
          </ul>
        </div>
      </div>

      <!-- Col 4: Support -->
      <div class="footer-col footer-accordion">
        <button class="footer-col-title footer-accordion-btn" aria-expanded="false">
          <span>Support</span>
          <svg class="accordion-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
            stroke-linecap="round" stroke-linejoin="round">
            <polyline points="6 9 12 15 18 9" />
          </svg>
        </button>
        <div class="footer-accordion-panel">
          <ul class="footer-links-list">
            <li><a href="{{ url('/faq.html') }}">FAQs</a></li>
            <li><a href="{{ url('/documents.html') }}">Required Documents</a></li>
            <li><a href="{{ url('/blog.html') }}">Visa Blog</a></li>
            <li><a href="{{ url('/terms.html') }}">Terms &amp; Conditions</a></li>
            <li><a href="{{ url('/privacy.html') }}">Privacy Policy</a></li>
          </ul>
        </div>
      </div>

      <!-- Col 5: Contact Us (Open by default) -->
      <div class="footer-col footer-accordion is-open">
        <button class="footer-col-title footer-accordion-btn" aria-expanded="true">
          <span>Contact Us</span>
          <svg class="accordion-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
            stroke-linecap="round" stroke-linejoin="round">
            <polyline points="6 9 12 15 18 9" />
          </svg>
        </button>
        <div class="footer-accordion-panel">
          <ul class="footer-contact-list">
            <li class="footer-contact-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                stroke-linejoin="round">
                <path
                  d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
              </svg>
              <span>+880 1XXX-XXXXXX</span>
            </li>
            <li class="footer-contact-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                stroke-linejoin="round">
                <rect width="20" height="16" x="2" y="4" rx="2" />
                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
              </svg>
              <span>info@adibatech.com</span>
            </li>
            <li class="footer-contact-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
                <circle cx="12" cy="10" r="3" />
              </svg>
              <span>Pabna Bazar, Raiganj Sirajganj, Bangladesh</span>
            </li>
          </ul>

          <!-- Social Icons -->
          <div class="footer-social-row">
            <a href="#" class="social-icon-btn social-facebook" aria-label="Facebook">
              <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
              </svg>
            </a>
            <a href="#" class="social-icon-btn social-youtube" aria-label="YouTube">
              <svg viewBox="0 0 24 24" fill="currentColor">
                <path
                  d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z" />
                <polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02" fill="#FFFFFF" />
              </svg>
            </a>
            <a href="https://wa.me/8801XXXXXXXXX" class="social-icon-btn social-whatsapp" aria-label="WhatsApp">
              <svg viewBox="0 0 24 24" fill="currentColor">
                <path
                  d="M17.472 14.382c-.301-.15-1.78-.877-2.056-.977-.276-.1-.477-.15-.678.15-.2.301-.778.977-.954 1.178-.175.2-.351.226-.652.075-.3-.15-1.27-.468-2.42-1.493-.894-.798-1.498-1.784-1.674-2.085-.175-.3-.019-.463.131-.613.136-.135.301-.351.451-.527.151-.175.2-.301.301-.502.101-.2.05-.376-.025-.526-.075-.15-.678-1.631-.929-2.233-.244-.588-.493-.508-.678-.517-.175-.01-.376-.01-.577-.01-.2 0-.527.075-.803.376s-1.054 1.03-1.054 2.512c0 1.482 1.079 2.912 1.23 3.113.15.2 2.124 3.243 5.144 4.547.719.311 1.28.497 1.718.636.722.23 1.379.197 1.9.12.58-.087 1.78-.727 2.03-1.43.251-.703.251-1.305.176-1.43-.075-.126-.276-.201-.577-.351z" />
              </svg>
            </a>
            <a href="#" class="social-icon-btn social-instagram" aria-label="Instagram">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <rect width="20" height="20" x="2" y="2" rx="5" ry="5" />
                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
                <line x1="17.5" x2="17.51" y1="6.5" y2="6.5" />
              </svg>
            </a>
            <a href="#" class="social-icon-btn social-tiktok" aria-label="TikTok">
              <svg viewBox="0 0 24 24" fill="currentColor">
                <path
                  d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.35 0 .68.06 1 .17V9.08a6.38 6.38 0 0 0-1-.08A6.34 6.34 0 0 0 3 15.34a6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.34-6.34V8.72A8.19 8.19 0 0 0 21 10V6.69h-1.41z" />
              </svg>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Sub Footer -->
  <div class="sub-footer">
    <div class="container">
      <div>&copy; 2026 Adiba Global. A Concern of AdibaTech.com. All rights reserved.</div>
      <div class="sub-footer-right">
        <span>Explore the World with Adiba Global</span>
        <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor">
          <path
            d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z" />
        </svg>
      </div>
    </div>
  </div>
</footer>
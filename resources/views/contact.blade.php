@extends('layouts.public', [
  'title' => 'Contact Us & Office Location - Adiba Global',
  'description' => 'Get in touch with Adiba Global visa advisors. Office address in Pabna Bazar, helpline, email support and WhatsApp counseling.',
  'activePage' => 'contact'
])

@section('content')
  <!-- Page Hero Banner -->
  <section class="page-hero">
    <div class="container">
      <div class="page-breadcrumb">
        <a href="{{ url('/') }}">Home</a>
        <span class="separator">&rsaquo;</span>
        <span class="current">Contact Us</span>
      </div>
      <h1 class="page-hero-title">Get in Touch With Our Visa Experts</h1>
      <p class="page-hero-desc">Have questions regarding admission, work permit eligibility, or visa requirements? Reach out to our dedicated advisory team today.</p>
    </div>
  </section>

  <!-- Main Content -->
  <main class="page-main-section">
    <div class="container">
      <div class="contact-layout">
        <!-- Col 1: Contact Info -->
        <div class="content-card">
          <h2>Head Office &amp; Contact Details</h2>
          <p>We welcome you to visit our office or reach out through any of our direct support channels. Our counselors are available Saturday through Thursday.</p>

          <ul class="contact-info-list">
            <li class="contact-info-row">
              <div class="contact-info-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
                  <circle cx="12" cy="10" r="3"/>
                </svg>
              </div>
              <div>
                <div class="contact-info-title">Office Address</div>
                <div class="contact-info-val">Pabna Bazar, Raiganj, Sirajganj, Bangladesh</div>
              </div>
            </li>

            <li class="contact-info-row">
              <div class="contact-info-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                </svg>
              </div>
              <div>
                <div class="contact-info-title">Helpline / Hotline</div>
                <div class="contact-info-val"><a href="tel:+8801XXXXXXXXX">+880 1XXX-XXXXXX</a></div>
              </div>
            </li>

            <li class="contact-info-row">
              <div class="contact-info-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                  <rect width="20" height="16" x="2" y="4" rx="2"/>
                  <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                </svg>
              </div>
              <div>
                <div class="contact-info-title">Email Address</div>
                <div class="contact-info-val"><a href="mailto:info@adibatech.com">info@adibatech.com</a></div>
              </div>
            </li>

            <li class="contact-info-row">
              <div class="contact-info-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"/>
                  <polyline points="12 6 12 12 16 14"/>
                </svg>
              </div>
              <div>
                <div class="contact-info-title">Visiting Hours</div>
                <div class="contact-info-val">Saturday &ndash; Thursday: 9:00 AM &ndash; 8:00 PM (Friday Closed)</div>
              </div>
            </li>
          </ul>

          <div style="margin-top: 28px;">
            <a href="https://wa.me/8801XXXXXXXXX" class="btn-primary" style="background: #25D366; border-color: #25D366; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
              <span>Chat on WhatsApp Directly</span>
              &rarr;
            </a>
          </div>
        </div>

        <!-- Col 2: Interactive Message Form -->
        <div class="content-card">
          <h2>Send Us an Online Inquiry</h2>
          <p>Fill in your details below and a designated senior counselor will respond within 24 hours.</p>

          <form id="pageContactForm" action="{{ route('applications.storeLead') }}" method="POST">
            @csrf
            <div class="form-group">
              <label for="cName">Your Full Name *</label>
              <input type="text" id="cName" name="name" class="form-control" placeholder="e.g. Md. Tanvir Ahmed" value="{{ Auth::user()->name ?? '' }}" required>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="cPhone">Phone Number *</label>
                <input type="tel" id="cPhone" name="phone" class="form-control" placeholder="01XXXXXXXXX" value="{{ Auth::user()->phone ?? '' }}" required>
              </div>
              <div class="form-group">
                <label for="cEmail">Email Address *</label>
                <input type="email" id="cEmail" name="email" class="form-control" placeholder="yourname@gmail.com" value="{{ Auth::user()->email ?? '' }}" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="cCountry">Country Destination</label>
                <input type="text" id="cCountry" name="country" class="form-control" placeholder="e.g. UK / USA / India" value="UK">
              </div>
              <div class="form-group">
                <label for="cVisa">Visa Category</label>
                <input type="text" id="cVisa" name="visaType" class="form-control" placeholder="e.g. Student / Tourist" value="Student">
              </div>
            </div>
            <div class="form-group">
              <label for="cMessage">Message</label>
              <textarea id="cMessage" name="message" class="form-control" rows="4" placeholder="How can we assist you with your visa processing?" required></textarea>
            </div>
            <button type="submit" class="btn-submit" style="width: 100%; border-radius: 8px; padding: 12px;">Send Message Now &rarr;</button>
          </form>
        </div>
      </div>
    </div>
  </main>
@endsection

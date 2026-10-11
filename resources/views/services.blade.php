@extends('layouts.public', [
  'title' => 'Visa Processing Services - Adiba Global',
  'description' => 'Comprehensive visa processing and immigration consultancy services by Adiba Global. Student, Work, Tourist, Business, and Umrah visas with 99% success rate.',
  'activePage' => 'services'
])

@section('content')
  <!-- Page Hero Banner -->
  <section class="page-hero">
    <div class="container">
      <div class="page-breadcrumb">
        <a href="{{ url('/') }}">Home</a>
        <span class="separator">&rsaquo;</span>
        <span class="current">Our Visa Services</span>
      </div>
      <h1 class="page-hero-title">Comprehensive Visa Processing &amp; Consultancy</h1>
      <p class="page-hero-desc">From higher education admissions to global work permits and leisure holidays, Adiba Global provides expert, transparent guidance for a smooth, rejection-free visa journey.</p>
    </div>
  </section>

  <!-- Main Content -->
  <main class="page-main-section">
    <div class="container">
      <!-- Intro Card -->
      <div class="content-card">
        <h2>Expert Visa Support Tailored to Your Dreams</h2>
        <p>Whether you're aiming for top universities in the UK, USA, and Canada, pursuing a lucrative overseas career in Europe or Southeast Asia, or traveling for business or leisure, our certified immigration consultants handle your documentation, interview coaching, and embassy submissions from start to finish.</p>
        <div class="inner-grid-3">
          <div class="inner-feature-box">
            <h4>99% Success Rate</h4>
            <p>Rigorous pre-submission profile assessment minimizes risk of rejection.</p>
          </div>
          <div class="inner-feature-box">
            <h4>Certified Counselors</h4>
            <p>Government and embassy compliant procedures with genuine guidance.</p>
          </div>
          <div class="inner-feature-box">
            <h4>End-to-End Support</h4>
            <p>From university application &amp; SOP writing to biometric booking &amp; ticketing.</p>
          </div>
        </div>
      </div>

      <!-- Services Grid -->
      <div class="section-header" style="margin-top: 40px;">
        <div>
          <h2 class="section-title">Explore Our Specialized Visa Categories</h2>
          <p class="section-sub">Select any category to read details, requirements, and apply immediately.</p>
        </div>
      </div>

      <div class="services-grid" style="margin-top: 24px;">
        <!-- Student Visa Card -->
        <div class="service-card">
          <div class="service-icon-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
              <path d="M6 12v5c3 3 9 3 12 0v-5"/>
            </svg>
          </div>
          <h3 class="service-title">Student Visa Consultancy</h3>
          <p class="service-desc">Complete university admission support, scholarship guidance, CAS/I-20 assistance, financial statement audits, and mock visa interview training for UK, USA, Canada, Australia &amp; Europe.</p>
          <div class="service-card-footer">
            <a href="{{ url('/student-visa.html') }}" class="service-card-btn">Learn More &amp; Apply &rarr;</a>
          </div>
        </div>

        <!-- Work Visa Card -->
        <div class="service-card">
          <div class="service-icon-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect width="20" height="14" x="2" y="7" rx="2" ry="2"/>
              <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
            </svg>
          </div>
          <h3 class="service-title">Work &amp; Skilled Migration Visa</h3>
          <p class="service-desc">Legitimate employer sponsorship processing, work permit renewals, job offer attestation, and skilled worker points calculation for Malaysia, Saudi Arabia, UAE, and European nations.</p>
          <div class="service-card-footer">
            <a href="{{ url('/work-visa.html') }}" class="service-card-btn">Learn More &amp; Apply &rarr;</a>
          </div>
        </div>

        <!-- Tourist Visa Card -->
        <div class="service-card">
          <div class="service-icon-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="m2 9 3-3 3 3"/>
              <path d="M13 18H7a2 2 0 0 1-2-2V6"/>
              <path d="m22 15-3 3-3-3"/>
              <path d="M11 6h6a2 2 0 0 1 2 2v10"/>
            </svg>
          </div>
          <h3 class="service-title">Tourist &amp; Visitor Visa</h3>
          <p class="service-desc">Express visitor visas for vacations, family visits, and medical checkups in India, Thailand, Singapore, UAE, Schengen countries, and UK with full itinerary planning and hotel booking.</p>
          <div class="service-card-footer">
            <a href="{{ url('/tourist-visa.html') }}" class="service-card-btn">Learn More &amp; Apply &rarr;</a>
          </div>
        </div>

        <!-- Business Visa Card -->
        <div class="service-card">
          <div class="service-icon-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/>
              <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/>
              <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/>
            </svg>
          </div>
          <h3 class="service-title">Business &amp; Investor Visa</h3>
          <p class="service-desc">Fast-track visas for corporate executives, trade conferences, commercial investment delegations, company registrations, and multiple-entry business permits worldwide.</p>
          <div class="service-card-footer">
            <a href="{{ url('/business-visa.html') }}" class="service-card-btn">Learn More &amp; Apply &rarr;</a>
          </div>
        </div>

        <!-- Umrah Visa Card -->
        <div class="service-card">
          <div class="service-icon-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"/>
              <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/>
              <path d="M2 12h20"/>
            </svg>
          </div>
          <h3 class="service-title">Umrah &amp; Hajj E-Visa Packages</h3>
          <p class="service-desc">Authentic Saudi electronic Umrah visas processed within 24–48 hours with hotel reservations in Makkah and Madinah, airport transfers, and customized pilgrimage packages.</p>
          <div class="service-card-footer">
            <a href="{{ url('/umrah-visa.html') }}" class="service-card-btn">Learn More &amp; Apply &rarr;</a>
          </div>
        </div>

        <!-- Medical & Appeal Card -->
        <div class="service-card">
          <div class="service-icon-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
            </svg>
          </div>
          <h3 class="service-title">Medical Visas &amp; Refusal Appeals</h3>
          <p class="service-desc">Urgent medical patient and attendant visas for premier hospitals in India, Thailand, and Singapore. Comprehensive file re-audit and legal appeal letters for previously refused applications.</p>
          <div class="service-card-footer">
            <a href="#consultation" class="service-card-btn js-open-modal" data-country="India" data-visa="Medical">Request File Review &rarr;</a>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

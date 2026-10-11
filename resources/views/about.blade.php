@extends('layouts.public', [
  'title' => 'About Us - Adiba Global Visa Consultancy',
  'description' => 'Your trusted global pathway. A premier visa processing and education consultancy under AdibaTech.com, dedicated to unlocking international opportunities with honesty and excellence.',
  'activePage' => 'about'
])

@section('content')
  <!-- Page Hero Banner -->
  <section class="page-hero">
    <div class="container">
      <div class="page-breadcrumb">
        <a href="{{ url('/') }}">Home</a>
        <span class="separator">&rsaquo;</span>
        <span class="current">About Us</span>
      </div>
      <h1 class="page-hero-title">About Adiba Global</h1>
      <p class="page-hero-desc">Your trusted global pathway. A premier visa processing and education consultancy under AdibaTech.com, dedicated to unlocking international opportunities with honesty and excellence.</p>
    </div>
  </section>

  <!-- Main Content -->
  <main class="page-main-section">
    <div class="container">
      <!-- Story Card -->
      <div class="content-card">
        <h2>Turning Dreams Into Global Realities</h2>
        <p>Founded with a vision to eliminate fraudulent practices and ambiguity in immigration consulting, <strong>Adiba Global</strong> has grown into a beacon of trust for students, professionals, entrepreneurs, and families across Bangladesh and South Asia.</p>
        <p>As a key division of <strong>AdibaTech.com</strong>, we blend modern digital documentation management with decades of collective consular expertise. Whether securing scholarships in Europe, work permits in Malaysia and the Middle East, or rapid-entry tourist visas for North America and Asia, our certified legal and visa advisors handle every petition with uncompromising attention to detail.</p>
        
        <div class="inner-grid-3">
          <div class="inner-feature-box">
            <h4>Our Mission</h4>
            <p>To provide transparent, affordable, and 100% genuine visa guidance that empowers aspiring travelers to achieve their career, educational, and spiritual goals.</p>
          </div>
          <div class="inner-feature-box">
            <h4>Our Vision</h4>
            <p>To be the most reputable and tech-enabled cross-border migration and travel consultancy in South Asia, recognized for ethical practices and record-high success rates.</p>
          </div>
          <div class="inner-feature-box">
            <h4>Our Integrity</h4>
            <p>We do not promise false guarantees; we deliver meticulous profile enhancement, genuine embassy documentation, and rigorous file audits that speak for themselves.</p>
          </div>
        </div>
      </div>

      <!-- Why Choose Us Pillars -->
      <div class="content-card">
        <h2>Why Clients Trust Adiba Global</h2>
        <div class="inner-grid-2">
          <div style="display: flex; gap: 16px;">
            <div class="service-icon-box" style="margin: 0; min-width: 48px;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <div>
              <h4 style="color: #002244; font-size: 16px; margin-bottom: 6px;">100% Genuine &amp; Verified Documentation</h4>
              <p style="font-size: 14px; color: #4A5568; line-height: 1.6;">Every bank statement, sponsor affidavit, and academic credential is comprehensively verified according to each specific embassy's latest immigration rules.</p>
            </div>
          </div>

          <div style="display: flex; gap: 16px;">
            <div class="service-icon-box" style="margin: 0; min-width: 48px;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div>
              <h4 style="color: #002244; font-size: 16px; margin-bottom: 6px;">Express Processing &amp; Biometrics Assistance</h4>
              <p style="font-size: 14px; color: #4A5568; line-height: 1.6;">We expedite file preparation, secure premium embassy appointment slots, and offer full VFS/TLS/embassy center biometric guidance.</p>
            </div>
          </div>

          <div style="display: flex; gap: 16px;">
            <div class="service-icon-box" style="margin: 0; min-width: 48px;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div>
              <h4 style="color: #002244; font-size: 16px; margin-bottom: 6px;">Certified Counselors &amp; Embassy Experts</h4>
              <p style="font-size: 14px; color: #4A5568; line-height: 1.6;">Our advisors possess over 8+ years of specialized immigration experience with deep insights into consular interview expectations.</p>
            </div>
          </div>

          <div style="display: flex; gap: 16px;">
            <div class="service-icon-box" style="margin: 0; min-width: 48px;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <div>
              <h4 style="color: #002244; font-size: 16px; margin-bottom: 6px;">99% Proven Track Record</h4>
              <p style="font-size: 14px; color: #4A5568; line-height: 1.6;">Over 1,200+ visas approved with zero hidden service fees and complete post-visa travel support.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Action Banner -->
      <div style="background: linear-gradient(135deg, #002B66 0%, #0066FF 100%); border-radius: 16px; padding: 36px 32px; color: #FFFFFF; text-align: center;">
        <h3 style="font-size: 24px; font-weight: 800; margin-bottom: 12px; color: #FFFFFF;">Ready to Start Your International Journey?</h3>
        <p style="max-width: 600px; margin: 0 auto 24px; font-size: 15px; color: rgba(255, 255, 255, 0.9);">Book a 1-on-1 personalized profile assessment with our senior visa consultant today. No obligations.</p>
        <a href="#consultation" class="btn-primary js-open-modal" style="background: #FFFFFF; color: #0066FF; padding: 12px 28px; font-weight: 700; text-decoration: none; border-radius: 9999px; display: inline-block;">Get Free Consultation &rarr;</a>
      </div>
    </div>
  </main>
@endsection

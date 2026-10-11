@extends('layouts.public', [
  'title' => 'Work & Skilled Migration Visas - Adiba Global',
  'description' => 'Expand your career internationally. Legitimate employer sponsorship processing, job offer validations, and skilled worker visas with 100% genuine legal verification.',
  'activePage' => 'services'
])

@section('content')
  <!-- Page Hero Banner -->
  <section class="page-hero">
    <div class="container">
      <div class="page-breadcrumb">
        <a href="{{ url('/') }}">Home</a>
        <span class="separator">&rsaquo;</span>
        <a href="{{ url('/services.html') }}">Services</a>
        <span class="separator">&rsaquo;</span>
        <span class="current">Work Visa</span>
      </div>
      <h1 class="page-hero-title">Work &amp; Skilled Migration Visas</h1>
      <p class="page-hero-desc">Expand your career internationally. Legitimate employer sponsorship processing, job offer validations, and skilled worker visas with 100% genuine legal verification.</p>
    </div>
  </section>

  <!-- Main Content -->
  <main class="page-main-section">
    <div class="container">
      <div class="content-card">
        <h2>Advance Your Global Career With Certified Guidance</h2>
        <p>Navigating international labor immigration requires strict adherence to ministry guidelines and employment regulations. At <strong>Adiba Global</strong>, we safeguard candidates against unverified recruiters by connecting you solely with attested government sponsorships, registered recruitment quotas, and certified employers.</p>

        <div class="inner-grid-3">
          <div class="inner-feature-box">
            <h4>Attestation &amp; Legal Verification</h4>
            <p>Verification of employment contracts, BMET clearances, and embassy attestation for guaranteed legal entry.</p>
          </div>
          <div class="inner-feature-box">
            <h4>Skilled Worker Points Systems</h4>
            <p>Comprehensive eligibility scoring for European Opportunity Cards, UK Skilled Worker (Health &amp; Tech), and Canada Express Entry.</p>
          </div>
          <div class="inner-feature-box">
            <h4>Medical &amp; Biometric Fast-Tracking</h4>
            <p>GAMCA medical appointment scheduling, biometrics registration, and pre-departure briefings.</p>
          </div>
        </div>
      </div>

      <!-- Top Work Destinations -->
      <div class="content-card">
        <h2>Top Employment &amp; Work Permit Destinations</h2>
        <div class="inner-grid-2">
          <div style="border: 1px solid rgba(0, 102, 255, 0.12); padding: 20px; border-radius: 12px; background: #FFFFFF;">
            <h4 style="color: #002244; font-size: 17px; margin-bottom: 8px;">🇲🇾 Malaysia (Calling Visa &amp; Professional Pass)</h4>
            <p style="font-size: 13.5px; color: #4A5568;">Verified calling visas for manufacturing, hospitality, plantation, and IT professionals under Malaysian Immigration approval (e-VDR). Processing: 5–10 days.</p>
          </div>
          <div style="border: 1px solid rgba(0, 102, 255, 0.12); padding: 20px; border-radius: 12px; background: #FFFFFF;">
            <h4 style="color: #002244; font-size: 17px; margin-bottom: 8px;">🇸🇦 Saudi Arabia &amp; 🇦🇪 UAE (Work Visas)</h4>
            <p style="font-size: 13.5px; color: #4A5568;">Government-approved employment contracts, Qiwa portal registration, Dubai 2-year resident employment visas, and free-zone work permits.</p>
          </div>
          <div style="border: 1px solid rgba(0, 102, 255, 0.12); padding: 20px; border-radius: 12px; background: #FFFFFF;">
            <h4 style="color: #002244; font-size: 17px; margin-bottom: 8px;">🇩🇪 Germany (Opportunity Card / Chancenkarte)</h4>
            <p style="font-size: 13.5px; color: #4A5568;">New points-based job seeker visa allowing skilled graduates and tradespeople to live and work in Germany with pathways to the EU Blue Card.</p>
          </div>
          <div style="border: 1px solid rgba(0, 102, 255, 0.12); padding: 20px; border-radius: 12px; background: #FFFFFF;">
            <h4 style="color: #002244; font-size: 17px; margin-bottom: 8px;">🇬🇧 United Kingdom (Skilled Worker Route)</h4>
            <p style="font-size: 13.5px; color: #4A5568;">Processing for holders of Certificate of Sponsorship (CoS) across healthcare, engineering, hospitality, and tech sectors with family dependants.</p>
          </div>
        </div>
      </div>

      <!-- Core Requirements -->
      <div class="content-card">
        <h2>Checklist for Work Permit Processing</h2>
        <ul class="checklist-group">
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Valid Machine Readable / E-Passport with at least 1–2 years validity.</span>
          </li>
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Signed Official Employment Contract / Offer Letter / Calling Visa approval from destination country's Ministry of Labor.</span>
          </li>
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Police Clearance Certificate (PCC) verified by Ministry of Foreign Affairs (MOFA).</span>
          </li>
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>GAMCA / Embassy-authorized Medical Fitness Report.</span>
          </li>
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Trade or Educational Certificates attested by relevant boards and MOFA.</span>
          </li>
        </ul>

        <div style="margin-top: 24px; text-align: center;">
          <a href="#consultation" class="btn-primary js-open-modal" style="padding: 12px 28px; font-weight: 700; text-decoration: none; border-radius: 9999px; display: inline-block;">
            Check Work Visa Eligibility &rarr;
          </a>
        </div>
      </div>
    </div>
  </main>
@endsection

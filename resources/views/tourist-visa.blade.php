@extends('layouts.public', [
  'title' => 'Tourist & Visitor Visa Consultancy - Adiba Global',
  'description' => 'Discover the world with stress-free visit visas. Express e-visas, verified hotel bookings, confirmed flight itineraries, and embassy interview preparation.',
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
        <span class="current">Tourist Visa</span>
      </div>
      <h1 class="page-hero-title">Tourist &amp; Visitor Visa Consultancy</h1>
      <p class="page-hero-desc">Discover the world with stress-free visit visas. Express e-visas, verified hotel bookings, confirmed flight itineraries, and embassy interview preparation.</p>
    </div>
  </section>

  <!-- Main Content -->
  <main class="page-main-section">
    <div class="container">
      <div class="content-card">
        <h2>Effortless Travel Planning for Leisure &amp; Family Visits</h2>
        <p>Whether traveling to India for sightseeing or medical treatment, vacationing in Thailand and Dubai, visiting loved ones in the UK and USA, or exploring the cultural wonders of Europe's Schengen zone, <strong>Adiba Global</strong> provides end-to-end tourist visa filing that maximizes your approval probability.</p>

        <div class="inner-grid-3">
          <div class="inner-feature-box">
            <h4>Fast-Track E-Visas</h4>
            <p>Rapid 24-to-72-hour e-visa processing for India, UAE, Malaysia, Saudi Arabia, and Singapore.</p>
          </div>
          <div class="inner-feature-box">
            <h4>Confirmed Itineraries</h4>
            <p>Embassy-compliant verifiable flight reservations and hotel vouchers without non-refundable upfront purchases.</p>
          </div>
          <div class="inner-feature-box">
            <h4>Strong Cover Letters</h4>
            <p>Professionally drafted travel purpose statements proving strong home country ties and financial solvency.</p>
          </div>
        </div>
      </div>

      <!-- Top Tourist Destinations -->
      <div class="content-card">
        <h2>Featured Visit &amp; Holiday Destinations</h2>
        <div class="inner-grid-2">
          <div style="border: 1px solid rgba(0, 102, 255, 0.12); padding: 20px; border-radius: 12px; background: #FFFFFF;">
            <h4 style="color: #002244; font-size: 17px; margin-bottom: 8px;">🇮🇳 India (Tourist, Medical &amp; Double Entry)</h4>
            <p style="font-size: 13.5px; color: #4A5568;">IVAC online token booking, form filling, double-entry permits for Bhutan/Nepal transit, and urgent medical visas. Processing: 3–5 days.</p>
          </div>
          <div style="border: 1px solid rgba(0, 102, 255, 0.12); padding: 20px; border-radius: 12px; background: #FFFFFF;">
            <h4 style="color: #002244; font-size: 17px; margin-bottom: 8px;">🇦🇪 UAE &amp; Dubai (30 / 60 Days E-Visa)</h4>
            <p style="font-size: 13.5px; color: #4A5568;">Guaranteed e-visa issuance within 48–72 hours with passport copy and photograph only. Single and multiple entry available.</p>
          </div>
          <div style="border: 1px solid rgba(0, 102, 255, 0.12); padding: 20px; border-radius: 12px; background: #FFFFFF;">
            <h4 style="color: #002244; font-size: 17px; margin-bottom: 8px;">🇪🇺 Schengen Tourist Visa (29 European Countries)</h4>
            <p style="font-size: 13.5px; color: #4A5568;">Single entry or multiple entry C-type tourist visa covering Italy, France, Germany, Spain, Switzerland, and the entire Schengen territory.</p>
          </div>
          <div style="border: 1px solid rgba(0, 102, 255, 0.12); padding: 20px; border-radius: 12px; background: #FFFFFF;">
            <h4 style="color: #002244; font-size: 17px; margin-bottom: 8px;">🇬🇧 UK Standard Visitor Visa (6 Months – 10 Years)</h4>
            <p style="font-size: 13.5px; color: #4A5568;">Full document preparation, financial audit, family visit sponsorship verification, and VFS Global appointment booking.</p>
          </div>
        </div>
      </div>

      <!-- Core Requirements -->
      <div class="content-card">
        <h2>Standard Tourist Visa Documentation Checklist</h2>
        <ul class="checklist-group">
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Original Passport with at least 6 months validity from travel date and minimum 2 blank pages.</span>
          </li>
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Recent 2 copies passport-size photographs on matte paper with white background (as per embassy specs).</span>
          </li>
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Last 6 months Bank Statement showing consistent cashflow and healthy closing balance, plus Bank Solvency Certificate.</span>
          </li>
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Professional Proof: Trade License with English translation (business owners) OR Leave Approval letter / NOC &amp; Pay Slips (job holders).</span>
          </li>
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Detailed day-by-day travel itinerary with hotel bookings and flight booking slips (provided by Adiba Global).</span>
          </li>
        </ul>

        <div style="margin-top: 24px; text-align: center;">
          <a href="#consultation" class="btn-primary js-open-modal" style="padding: 12px 28px; font-weight: 700; text-decoration: none; border-radius: 9999px; display: inline-block;">
            Apply for Tourist Visa &rarr;
          </a>
        </div>
      </div>
    </div>
  </main>
@endsection

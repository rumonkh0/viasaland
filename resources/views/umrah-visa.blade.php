@extends('layouts.public', [
  'title' => 'Umrah E-Visa & Pilgrimage Services - Adiba Global',
  'description' => 'Embark on your spiritual journey to Makkah and Madinah with peace of mind. Direct Saudi Nusuk platform e-visa approvals with 100% guarantee in 24–48 hours.',
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
        <span class="current">Umrah Visa</span>
      </div>
      <h1 class="page-hero-title">Umrah E-Visa &amp; Pilgrimage Services</h1>
      <p class="page-hero-desc">Embark on your spiritual journey to Makkah and Madinah with peace of mind. Direct Saudi Nusuk platform e-visa approvals with 100% guarantee in 24–48 hours.</p>
    </div>
  </section>

  <!-- Main Content -->
  <main class="page-main-section">
    <div class="container">
      <div class="content-card">
        <h2>Fulfill Your Holy Pilgrimage With Peace of Mind</h2>
        <p>Performing Umrah is a sacred life milestone. <strong>Adiba Global</strong> provides seamless, transparent, and hassle-free Umrah visa processing registered directly through the official Saudi Ministry of Hajj &amp; Umrah (Nusuk platform), ensuring prompt visa issuance and comfortable accommodation near Haram.</p>

        <div class="inner-grid-3">
          <div class="inner-feature-box">
            <h4>24 to 48-Hour E-Visa</h4>
            <p>Direct electronic visa delivery with 90-day validity and multiple entry across all Saudi international airports.</p>
          </div>
          <div class="inner-feature-box">
            <h4>Haram-Facing Hotels</h4>
            <p>Curated 3-star, 4-star, and 5-star hotel bookings within walking distance of Masjid al-Haram and Prophet's Mosque.</p>
          </div>
          <div class="inner-feature-box">
            <h4>VIP Ground Logistics</h4>
            <p>Haramain high-speed bullet train reservations, private AC airport transfers, and guided Ziyarat tours.</p>
          </div>
        </div>
      </div>

      <!-- Umrah Packages -->
      <div class="content-card">
        <h2>Customized Umrah Packages</h2>
        <div class="inner-grid-3">
          <div style="border: 1px solid rgba(0, 102, 255, 0.12); padding: 22px; border-radius: 12px; background: #FFFFFF; text-align: center;">
            <h4 style="color: #002244; font-size: 18px; margin-bottom: 6px;">Economy Package</h4>
            <div style="font-size: 22px; font-weight: 800; color: #0066FF; margin-bottom: 12px;">14 Days</div>
            <p style="font-size: 13.5px; color: #4A5568; line-height: 1.6; margin-bottom: 16px;">Verified Umrah e-visa, 3-star hotel (500m from Haram), shared AC transport, and group Ziyarat in Makkah and Madinah.</p>
            <a href="#consultation" class="btn-primary js-open-modal" style="padding: 8px 18px; font-size: 13px; text-decoration: none; display: inline-block;">Inquire Now &rarr;</a>
          </div>

          <div style="border: 2px solid #0066FF; padding: 22px; border-radius: 12px; background: #F0F6FF; text-align: center; position: relative;">
            <span style="position: absolute; top: -11px; left: 50%; transform: translateX(-50%); background: #0066FF; color: #FFFFFF; font-size: 11px; font-weight: 700; padding: 2px 12px; border-radius: 9999px;">MOST POPULAR</span>
            <h4 style="color: #002244; font-size: 18px; margin-bottom: 6px;">Deluxe Package</h4>
            <div style="font-size: 22px; font-weight: 800; color: #0066FF; margin-bottom: 12px;">10 / 14 Days</div>
            <p style="font-size: 13.5px; color: #4A5568; line-height: 1.6; margin-bottom: 16px;">4-star luxury hotels (200m from Haram), bullet train tickets (Jeddah–Makkah–Madinah), buffet breakfast, and private transport.</p>
            <a href="#consultation" class="btn-primary js-open-modal" style="padding: 8px 18px; font-size: 13px; text-decoration: none; display: inline-block;">Inquire Now &rarr;</a>
          </div>

          <div style="border: 1px solid rgba(0, 102, 255, 0.12); padding: 22px; border-radius: 12px; background: #FFFFFF; text-align: center;">
            <h4 style="color: #002244; font-size: 18px; margin-bottom: 6px;">VIP Executive Package</h4>
            <div style="font-size: 22px; font-weight: 800; color: #0066FF; margin-bottom: 12px;">7 / 10 Days</div>
            <p style="font-size: 13.5px; color: #4A5568; line-height: 1.6; margin-bottom: 16px;">Clock Tower / Front-row 5-star suites with direct Kaaba view, private GMC SUV transport, VIP protocol at Jeddah airport.</p>
            <a href="#consultation" class="btn-primary js-open-modal" style="padding: 8px 18px; font-size: 13px; text-decoration: none; display: inline-block;">Inquire Now &rarr;</a>
          </div>
        </div>
      </div>

      <!-- Core Requirements -->
      <div class="content-card">
        <h2>Simple Documents Required for Umrah E-Visa</h2>
        <ul class="checklist-group">
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Color scan of original Passport (valid for at least 6 months from intended entry date).</span>
          </li>
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Passport size digital photograph with plain white background.</span>
          </li>
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>National ID Card (NID) copy or Birth Certificate (for minors).</span>
          </li>
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Proof of relationship / Mahram documentation (if applicable, though females of all ages are now permitted without Mahram under new Saudi rules).</span>
          </li>
        </ul>

        <div style="margin-top: 24px; text-align: center;">
          <a href="#consultation" class="btn-primary js-open-modal" style="padding: 12px 28px; font-weight: 700; text-decoration: none; border-radius: 9999px; display: inline-block;">
            Book Your Umrah Consultation &rarr;
          </a>
        </div>
      </div>
    </div>
  </main>
@endsection

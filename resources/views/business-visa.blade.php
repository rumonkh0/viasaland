@extends('layouts.public', [
  'title' => 'Business & Commercial Visa Processing - Adiba Global',
  'description' => 'Facilitating international trade, commercial meetings, and investor delegations across global economic hubs with VIP consular support.',
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
        <span class="current">Business Visa</span>
      </div>
      <h1 class="page-hero-title">Business &amp; Commercial Visa Processing</h1>
      <p class="page-hero-desc">Facilitating international trade, commercial meetings, and investor delegations across global economic hubs with VIP consular support.</p>
    </div>
  </section>

  <!-- Main Content -->
  <main class="page-main-section">
    <div class="container">
      <div class="content-card">
        <h2>Unlocking Cross-Border Commerce &amp; Investments</h2>
        <p>In today's interconnected economy, rapid business mobility is vital. <strong>Adiba Global</strong> assists company founders, directors, and corporate delegations in securing short-term and multi-entry business visas for trade expos, client negotiations, factory inspections, and corporate ventures.</p>

        <div class="inner-grid-3">
          <div class="inner-feature-box">
            <h4>Trade Delegations &amp; Expos</h4>
            <p>Direct registration and visa sponsorship letters for international trade fairs in China, UAE, Germany, and USA.</p>
          </div>
          <div class="inner-feature-box">
            <h4>Corporate Fast-Track Filing</h4>
            <p>Priority embassy appointments and group dossier management for business executives.</p>
          </div>
          <div class="inner-feature-box">
            <h4>Commercial Invitation Audits</h4>
            <p>Legal validation of overseas company invitations and Chamber of Commerce (FBCCI / DCCI) endorsements.</p>
          </div>
        </div>
      </div>

      <!-- Top Business Hubs -->
      <div class="content-card">
        <h2>Key Commercial Hubs We Facilitate</h2>
        <div class="inner-grid-2">
          <div style="border: 1px solid rgba(0, 102, 255, 0.12); padding: 20px; border-radius: 12px; background: #FFFFFF;">
            <h4 style="color: #002244; font-size: 17px; margin-bottom: 8px;">🇦🇪 UAE &amp; Saudi Arabia (Commercial &amp; Investor Visas)</h4>
            <p style="font-size: 13.5px; color: #4A5568;">Instant business e-visas for Gulf Gulfood, GITEX, and Saudi Ministry of Foreign Affairs (MOFA) commercial visit endorsements.</p>
          </div>
          <div style="border: 1px solid rgba(0, 102, 255, 0.12); padding: 20px; border-radius: 12px; background: #FFFFFF;">
            <h4 style="color: #002244; font-size: 17px; margin-bottom: 8px;">🇺🇸 USA (B-1 Business) &amp; 🇬🇧 UK Business Route</h4>
            <p style="font-size: 13.5px; color: #4A5568;">Attend conferences, negotiate contracts, and establish corporate branches with 5-year to 10-year multiple-entry visas.</p>
          </div>
          <div style="border: 1px solid rgba(0, 102, 255, 0.12); padding: 20px; border-radius: 12px; background: #FFFFFF;">
            <h4 style="color: #002244; font-size: 17px; margin-bottom: 8px;">🇪🇺 Schengen Business Visas (Germany, Italy, France)</h4>
            <p style="font-size: 13.5px; color: #4A5568;">Facilitation for Canton, Hannover Messe, and Milan exhibitions with verified company sponsorship documentation.</p>
          </div>
          <div style="border: 1px solid rgba(0, 102, 255, 0.12); padding: 20px; border-radius: 12px; background: #FFFFFF;">
            <h4 style="color: #002244; font-size: 17px; margin-bottom: 8px;">🇸🇬 Singapore &amp; 🇲🇾 Malaysia Commercial Permits</h4>
            <p style="font-size: 13.5px; color: #4A5568;">Rapid corporate entry permits for regional headquarters visits, fintech conferences, and business meetings.</p>
          </div>
        </div>
      </div>

      <!-- Core Requirements -->
      <div class="content-card">
        <h2>Required Business Visa Documentation</h2>
        <ul class="checklist-group">
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Valid Passport with minimum 6 months remaining validity.</span>
          </li>
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Official Invitation Letter from host company abroad stating meeting agenda, duration, and financial responsibility.</span>
          </li>
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Company Forwarding Letter on official company letterhead introducing the applicant and executive role.</span>
          </li>
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Company Registration Documents: Trade License (notarized English translation), TIN, and Incorporation Certificate.</span>
          </li>
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Company &amp; Personal Bank Statements for last 6 months with Solvency Certificates.</span>
          </li>
        </ul>

        <div style="margin-top: 24px; text-align: center;">
          <a href="#consultation" class="btn-primary js-open-modal" style="padding: 12px 28px; font-weight: 700; text-decoration: none; border-radius: 9999px; display: inline-block;">
            Book Corporate Visa Consultation &rarr;
          </a>
        </div>
      </div>
    </div>
  </main>
@endsection

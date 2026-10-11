@extends('layouts.public', [
  'title' => 'Official Required Documents Checklist - Adiba Global',
  'description' => 'Prepare a flawless, rejection-proof application. Detailed checklists covering academic transcripts, financial solvency, police clearance, and embassy attestations.',
  'activePage' => 'support'
])

@section('content')
  <!-- Page Hero Banner -->
  <section class="page-hero">
    <div class="container">
      <div class="page-breadcrumb">
        <a href="{{ url('/') }}">Home</a>
        <span class="separator">&rsaquo;</span>
        <span class="current">Required Documents</span>
      </div>
      <h1 class="page-hero-title">Official Required Documents Checklist</h1>
      <p class="page-hero-desc">Prepare a flawless, rejection-proof application. Detailed checklists covering academic transcripts, financial solvency, police clearance, and embassy attestations.</p>
    </div>
  </section>

  <!-- Main Content -->
  <main class="page-main-section">
    <div class="container">
      <!-- Universal Documents Card -->
      <div class="content-card">
        <h2>1. Universal Baseline Requirements (All Visas)</h2>
        <p>Every applicant regardless of country or visa category must prepare these core identification documents:</p>
        <ul class="checklist-group">
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span><strong>Valid Passport:</strong> Minimum 6 months validity beyond intended travel date with at least 2 consecutive blank pages. All previous/expired passports must also be presented.</span>
          </li>
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span><strong>Photographs:</strong> 2 to 4 recent passport-size biometric photographs (taken within 3 months) on matte paper with 80% face coverage and clear white background.</span>
          </li>
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span><strong>National Identification:</strong> Clear photocopy of applicant's National ID (NID) card or Online Verified English Birth Certificate for minors.</span>
          </li>
        </ul>
      </div>

      <!-- Category Specific Checklists -->
      <div class="content-card">
        <h2>2. Student Visa Checklist</h2>
        <ul class="checklist-group">
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Attested Academic Certificates and Mark Sheets (SSC, HSC, Bachelor's, Master's) from Education Boards and Ministry of Foreign Affairs (MOFA).</span>
          </li>
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>English Language Proficiency Test Certificate (IELTS TRF, PTE score report, or TOEFL iBT).</span>
          </li>
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Unconditional University Admission Offer Letter / CAS statement (UK) / Form I-20 (USA).</span>
          </li>
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Sponsor's Bank Statement (last 6 months or 28 consecutive days for UK) showing required tuition + living costs maintenance fund.</span>
          </li>
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Bank Solvency Certificate from the issuing branch manager.</span>
          </li>
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Notarized Affidavit of Financial Sponsorship and proof of source of funds (Trade License, Tax Return, Salary slips, or Land deeds).</span>
          </li>
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Statement of Purpose (SOP) / Personal Statement detailing academic roadmap and future intentions.</span>
          </li>
        </ul>
      </div>

      <!-- Tourist & Visitor Checklist -->
      <div class="content-card">
        <h2>3. Tourist &amp; Visitor Visa Checklist</h2>
        <ul class="checklist-group">
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Last 6 months active Bank Statement with original bank stamp and signature, plus Solvency Certificate.</span>
          </li>
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Income Tax Return (ITR) Acknowledgement Receipt for the latest fiscal year.</span>
          </li>
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Professional Proof:
              <br>&bull; <em>For Business Persons:</em> Updated Trade License (English notarized translation), TIN, and Company Bank Statement.
              <br>&bull; <em>For Employed Persons:</em> No Objection Certificate (NOC) on office letterhead, Pay Slips (last 3 months), and Visiting Card.
            </span>
          </li>
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Round-trip flight booking slip and confirmed hotel reservation voucher (arranged by Adiba Global).</span>
          </li>
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Marriage Certificate (Nikahnama with English translation) if applying with spouse/family.</span>
          </li>
        </ul>
      </div>

      <!-- Need Audit Help? -->
      <div style="background: #F0F6FF; border: 1px solid rgba(0, 102, 255, 0.15); border-radius: 16px; padding: 32px; text-align: center;">
        <h3 style="color: #002244; margin-bottom: 8px;">Need a Complimentary Document File Audit?</h3>
        <p style="color: #4A5568; max-width: 600px; margin: 0 auto 20px; font-size: 14.5px;">Bring or send your document copies to our certified consultants. We will review your papers against the destination embassy's requirements before you pay any consular fees.</p>
        <a href="#consultation" class="btn-primary js-open-modal" style="padding: 11px 26px; font-weight: 700; text-decoration: none; border-radius: 9999px; display: inline-block;">
          Request Free File Review &rarr;
        </a>
      </div>
    </div>
  </main>
@endsection

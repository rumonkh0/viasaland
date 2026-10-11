@extends('layouts.public', [
  'title' => 'Frequently Asked Questions - Adiba Global Visa Consultancy',
  'description' => 'Have queries about visa application deadlines, financial sponsorship rules, or interview procedures? Find detailed answers compiled by our legal consultants.',
  'activePage' => 'support'
])

@section('content')
  <!-- Page Hero Banner -->
  <section class="page-hero">
    <div class="container">
      <div class="page-breadcrumb">
        <a href="{{ url('/') }}">Home</a>
        <span class="separator">&rsaquo;</span>
        <span class="current">FAQs</span>
      </div>
      <h1 class="page-hero-title">Frequently Asked Questions</h1>
      <p class="page-hero-desc">Have queries about visa application deadlines, financial sponsorship rules, or interview procedures? Find detailed answers compiled by our legal consultants.</p>
    </div>
  </section>

  <!-- Main Content -->
  <main class="page-main-section">
    <div class="container">
      <div class="content-card">
        <h2>General Visa Queries</h2>
        <div class="faq-accordion-group">
          <div class="faq-card is-active">
            <button class="faq-header-btn" aria-expanded="true">
              <span>How does Adiba Global maintain a 99% visa success rate?</span>
              <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="faq-body-panel">
              We perform a strict pre-screening assessment before accepting any visa file. We do not submit applications that have incomplete documentation, unverifiable financial records, or weak ties to home country. If your profile needs enhancement, we guide you on how to resolve discrepancies prior to embassy submission.
            </div>
          </div>

          <div class="faq-card">
            <button class="faq-header-btn" aria-expanded="false">
              <span>How early should I begin my visa application process?</span>
              <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="faq-body-panel">
              For student visas, you should begin 4 to 6 months prior to the academic intake to accommodate university offer letters, document attestations, and CAS/I-20 issuance. For tourist and business visas, starting 4 to 8 weeks before your intended travel date is recommended.
            </div>
          </div>

          <div class="faq-card">
            <button class="faq-header-btn" aria-expanded="false">
              <span>What happens if my visa has been refused previously?</span>
              <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="faq-body-panel">
              A past refusal is not a permanent barrier. Our legal advisors analyze the embassy refusal letter and CAIPS/GCMS notes (for Canada) to identify the specific grounds for refusal. We then restructure your file with targeted legal rebuttal letters and reinforced evidence before reapplying.
            </div>
          </div>
        </div>
      </div>

      <div class="content-card">
        <h2>Financial &amp; Sponsorship Questions</h2>
        <div class="faq-accordion-group">
          <div class="faq-card">
            <button class="faq-header-btn" aria-expanded="false">
              <span>Who can act as my financial sponsor for a student visa?</span>
              <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="faq-body-panel">
              First-degree relatives (parents, legal guardians, or spouse) are universally preferred by UK, USA, Canadian, and Australian embassies. In certain countries (e.g. USA), third-party sponsors with clear affidavits and proven ties can also be accepted. We audit your sponsor's tax returns (ITR) and bank statements to prove genuine financial capacity.
            </div>
          </div>

          <div class="faq-card">
            <button class="faq-header-btn" aria-expanded="false">
              <span>How much closing balance is required for tourist visas?</span>
              <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="faq-body-panel">
              While requirements vary by destination, embassies generally look for a closing balance that comfortably covers your round-trip flights, accommodations, daily living expenses (typically $100–$150 per day), with a remaining cushion. For India, BDT 30,000–50,000 is common; for Schengen/UK/USA, BDT 4,00,000–8,00,000+ with active transaction history is recommended.
            </div>
          </div>
        </div>
      </div>

      <div class="content-card">
        <h2>Interviews &amp; Biometrics</h2>
        <div class="faq-accordion-group">
          <div class="faq-card">
            <button class="faq-header-btn" aria-expanded="false">
              <span>Do I need to appear in person for visa submission?</span>
              <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="faq-body-panel">
              Most embassies (including UK, Canada, Schengen nations, and USA) require biometric data (fingerprints and digital photo) captured in person at VFS Global, TLScontact, or the Embassy consular section. For certain e-visa destinations like UAE, Malaysia, and Saudi Umrah, physical presence is not required.
            </div>
          </div>

          <div class="faq-card">
            <button class="faq-header-btn" aria-expanded="false">
              <span>Does Adiba Global provide mock interview coaching?</span>
              <svg class="faq-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="faq-body-panel">
              Yes! We conduct multiple 1-on-1 mock interview sessions simulating actual consular questions for US F-1 visas, UK Credibility interviews, and European study admissions, focusing on clear articulation of your academic plan, post-study goals, and financial ties.
            </div>
          </div>
        </div>
      </div>

      <div style="text-align: center; margin-top: 36px;">
        <h3 style="color: #002244; margin-bottom: 12px;">Still Have Questions?</h3>
        <p style="color: #4A5568; margin-bottom: 20px;">Our senior counselors are available to review your profile and provide instant clarity.</p>
        <a href="#consultation" class="btn-primary js-open-modal" style="padding: 12px 28px; font-weight: 700; text-decoration: none; border-radius: 9999px; display: inline-block;">
          Speak to a Visa Consultant Now &rarr;
        </a>
      </div>
    </div>
  </main>
@endsection

@extends('layouts.public', [
  'title' => 'International Student Visa Consultancy - Adiba Global',
  'description' => 'Unlock world-class higher education across the UK, USA, Canada, Australia, and Europe with comprehensive admission and student visa guidance.',
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
        <span class="current">Student Visa</span>
      </div>
      <h1 class="page-hero-title">International Student Visa Consultancy</h1>
      <p class="page-hero-desc">Unlock world-class higher education across the UK, USA, Canada, Australia, and Europe with comprehensive admission and student visa guidance.</p>
    </div>
  </section>

  <!-- Main Content -->
  <main class="page-main-section">
    <div class="container">
      <div class="content-card">
        <h2>Your Gateway to World-Ranked Universities</h2>
        <p>Studying abroad is an investment in your career and future. At <strong>Adiba Global</strong>, our education counselors help you identify ideal academic programs, apply for partial and full scholarships, write winning Statements of Purpose (SOP), and secure your student visa with a <strong>99% proven success rate</strong>.</p>

        <div class="inner-grid-3">
          <div class="inner-feature-box">
            <h4>Admissions &amp; Offer Letters</h4>
            <p>Direct applications to Russell Group (UK), Ivy League &amp; State Universities (USA), and Top Public Universities in Canada &amp; Europe.</p>
          </div>
          <div class="inner-feature-box">
            <h4>Scholarships &amp; Financial Aid</h4>
            <p>Assistance securing regional merit scholarships, tuition fee waivers, and regional Italian DSU government grants.</p>
          </div>
          <div class="inner-feature-box">
            <h4>Visa Interview Mock Drills</h4>
            <p>Rigorous 1-on-1 embassy interview preparation for USA F-1 and UK Credibility interviews.</p>
          </div>
        </div>
      </div>

      <!-- Top Study Destinations -->
      <div class="content-card">
        <h2>Popular Study Destinations We Support</h2>
        <div class="inner-grid-2">
          <div style="border: 1px solid rgba(0, 102, 255, 0.12); padding: 20px; border-radius: 12px; background: #FFFFFF;">
            <h4 style="color: #002244; font-size: 17px; margin-bottom: 8px;">🇬🇧 United Kingdom (Student Route)</h4>
            <p style="font-size: 13.5px; color: #4A5568;">Fast CAS issuance, 2-year post-study work permit (Graduate Route), spouse visa eligibility for research students. Average processing: 2–3 weeks.</p>
          </div>
          <div style="border: 1px solid rgba(0, 102, 255, 0.12); padding: 20px; border-radius: 12px; background: #FFFFFF;">
            <h4 style="color: #002244; font-size: 17px; margin-bottom: 8px;">🇺🇸 United States (F-1 Visa)</h4>
            <p style="font-size: 13.5px; color: #4A5568;">Up to 3-year STEM OPT extension after graduation, on-campus work permits, high-value graduate research assistantships.</p>
          </div>
          <div style="border: 1px solid rgba(0, 102, 255, 0.12); padding: 20px; border-radius: 12px; background: #FFFFFF;">
            <h4 style="color: #002244; font-size: 17px; margin-bottom: 8px;">🇨🇦 Canada (Study Permit)</h4>
            <p style="font-size: 13.5px; color: #4A5568;">Designated Learning Institutions (DLI), Post-Graduation Work Permit (PGWP), clear pathways to Permanent Residency (PR).</p>
          </div>
          <div style="border: 1px solid rgba(0, 102, 255, 0.12); padding: 20px; border-radius: 12px; background: #FFFFFF;">
            <h4 style="color: #002244; font-size: 17px; margin-bottom: 8px;">🇮🇹 Italy &amp; Germany (Schengen)</h4>
            <p style="font-size: 13.5px; color: #4A5568;">100% tuition-free public universities in Germany, €7,000+ yearly regional living stipends in Italy, Schengen-wide travel freedom.</p>
          </div>
        </div>
      </div>

      <!-- Required Documents -->
      <div class="content-card">
        <h2>Core Required Documents for Student Visas</h2>
        <ul class="checklist-group">
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Valid International Passport (minimum 6 months validity from departure date).</span>
          </li>
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Official Academic Transcripts and Certificates (SSC, HSC, Bachelor's) attested by Education Board / Ministry.</span>
          </li>
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>English Proficiency Test Report (IELTS, TOEFL, PTE, or Duolingo as accepted by institution).</span>
          </li>
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Unconditional Offer Letter / CAS (UK) / I-20 (USA) / LOA (Canada).</span>
          </li>
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Sponsor's Bank Statement (showing required tuition + living expenses maintenance funds with source of income audit).</span>
          </li>
          <li class="checklist-item">
            <svg class="checklist-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>Compelling Statement of Purpose (SOP) explaining academic intentions and post-study career plans.</span>
          </li>
        </ul>

        <div style="margin-top: 24px; text-align: center;">
          <a href="#consultation" class="btn-primary js-open-modal" style="padding: 12px 28px; font-weight: 700; text-decoration: none; border-radius: 9999px; display: inline-block;">
            Apply for Student Visa Consultation &rarr;
          </a>
        </div>
      </div>
    </div>
  </main>
@endsection

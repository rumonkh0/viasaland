@extends('layouts.public', [
  'title' => 'Privacy Policy & Data Security - Adiba Global',
  'description' => 'Your personal documentation and financial confidentiality are our highest operational priority. Learn how Adiba Global protects your data.',
  'activePage' => 'privacy'
])

@section('content')
  <!-- Page Hero Banner -->
  <section class="page-hero">
    <div class="container">
      <div class="page-breadcrumb">
        <a href="{{ url('/') }}">Home</a>
        <span class="separator">&rsaquo;</span>
        <span class="current">Privacy Policy</span>
      </div>
      <h1 class="page-hero-title">Privacy Policy &amp; Data Security</h1>
      <p class="page-hero-desc">Your personal documentation and financial confidentiality are our highest operational priority. Learn how Adiba Global protects your data.</p>
    </div>
  </section>

  <!-- Main Content -->
  <main class="page-main-section">
    <div class="container">
      <div class="content-card legal-prose">
        <p><em>Last Updated: September 2026</em></p>

        <h2>1. Commitment to Client Confidentiality</h2>
        <p>At <strong>Adiba Global</strong> (a division of AdibaTech.com), we recognize that visa processing involves sensitive personal, financial, and family records. We are dedicated to maintaining strict confidentiality standards and protecting all client information against unauthorized disclosure or misuse.</p>

        <h2>2. Information We Collect</h2>
        <p>To prepare university applications, embassy petition forms, and biometric appointment bookings, we collect:</p>
        <ul>
          <li><strong>Personal Identification Data:</strong> Full legal name, date of birth, passport number, NID, and passport-size photographs.</li>
          <li><strong>Contact Information:</strong> Phone number, email address, physical residence, and emergency contacts.</li>
          <li><strong>Academic &amp; Employment History:</strong> Educational certificates, grade sheets, language test scores, curriculum vitae, and employer NOC letters.</li>
          <li><strong>Financial Evidence:</strong> Bank statements, tax returns, solvency letters, and property valuations necessary to fulfill embassy requirements.</li>
        </ul>

        <h2>3. How We Use Your Data</h2>
        <p>Your documentation is utilized solely for lawful purposes related directly to your requested services:</p>
        <ul>
          <li>Submitting visa applications, university admission dossiers, or work permit petitions to verified institutions and government portals.</li>
          <li>Scheduling VFS Global, TLScontact, or Embassy consular appointments.</li>
          <li>Communicating real-time updates regarding file progress and visa decision notifications.</li>
        </ul>
        <p><strong>We never sell, rent, lease, or monetize your personal or financial records to third-party advertisers or marketing agencies under any circumstance.</strong></p>

        <h2>4. Data Retention &amp; Document Security</h2>
        <p>Digital files submitted to our consultancy are stored in encrypted environments accessible solely by authorized caseworkers assigned to your petition. Hard-copy document originals (such as passports or certificates) are secured in fireproof safes and returned to you immediately following physical submission or upon request.</p>

        <h2>5. Your Rights</h2>
        <p>You retain the right to review the records stored in your case file, request corrections to personal details, or request document deletion following the conclusion of your visa processing journey.</p>
      </div>
    </div>
  </main>
@endsection

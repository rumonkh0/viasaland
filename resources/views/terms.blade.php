@extends('layouts.public', [
  'title' => 'Terms & Conditions - Adiba Global',
  'description' => 'Please review the operational terms, service agreements, and advisory guidelines governing Adiba Global visa consultancy services.',
  'activePage' => 'terms'
])

@section('content')
  <!-- Page Hero Banner -->
  <section class="page-hero">
    <div class="container">
      <div class="page-breadcrumb">
        <a href="{{ url('/') }}">Home</a>
        <span class="separator">&rsaquo;</span>
        <span class="current">Terms &amp; Conditions</span>
      </div>
      <h1 class="page-hero-title">Terms &amp; Conditions</h1>
      <p class="page-hero-desc">Please review the operational terms, service agreements, and advisory guidelines governing Adiba Global visa consultancy services.</p>
    </div>
  </section>

  <!-- Main Content -->
  <main class="page-main-section">
    <div class="container">
      <div class="content-card legal-prose">
        <p><em>Last Updated: September 2026</em></p>
        
        <h2>1. Nature of Our Consultancy Services</h2>
        <p><strong>Adiba Global</strong> (a division of AdibaTech.com) operates as a professional visa advisory and document facilitation firm. We provide expert advice, university admission processing, file auditing, and appointment coordination. <strong>We are not an embassy, high commission, or immigration authority.</strong> The ultimate authority to grant or refuse any visa petition rests solely and exclusively with the respective sovereign government's consular officers.</p>

        <h2>2. Authenticity of Client Documentation</h2>
        <p>All clients are required to provide 100% genuine, lawful, and verifiable records. Adiba Global adheres to a strict zero-tolerance policy against forged diplomas, fabricated financial statements, or deceptive documentation. Presenting falsified credentials will result in immediate termination of representation with no refund, and may incur legal reporting to appropriate law enforcement bodies.</p>

        <h2>3. Service Fees &amp; Refund Policy</h2>
        <p>Consultancy and processing service fees are agreed upon prior to initiating document preparation:</p>
        <ul>
          <li>Government and embassy visa application fees, VFS biometric charges, medical test fees, and courier charges are paid directly to respective authorities or billed at cost and are strictly non-refundable once incurred.</li>
          <li>Professional advisory fees compensate for time, analysis, and processing invested by our certified counselors. In the rare event that an application is refused due to a verifiable clerical error on our part, we will reprocess your application at zero additional agency fee.</li>
        </ul>

        <h2>4. Processing Timelines &amp; Delays</h2>
        <p>Stated processing times (e.g., 3–5 days for India, 2–3 weeks for UK) represent typical operational timelines based on current embassy workloads. We cannot be held liable for administrative delays caused by embassy strikes, security background checks, technical portal outages, or sovereign policy amendments beyond our control.</p>

        <h2>5. Client Responsibilities</h2>
        <p>Clients agree to attend scheduled biometric and consular interview appointments punctually, disclose full travel and refusal histories transparently, and notify our team of any change in contact details or civil status during processing.</p>
      </div>
    </div>
  </main>
@endsection

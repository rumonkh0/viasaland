@extends('layouts.public', [
  'title' => 'Visa Blog & Immigration Insights - Adiba Global',
  'description' => 'Stay updated with the latest international immigration policy changes, student visa interview guides, and travel tips from the Adiba Global advisory team.',
  'activePage' => 'blog'
])

@section('content')
  <!-- Page Hero Banner -->
  <section class="page-hero">
    <div class="container">
      <div class="page-breadcrumb">
        <a href="{{ url('/') }}">Home</a>
        <span class="separator">&rsaquo;</span>
        <span class="current">Visa Blog &amp; Articles</span>
      </div>
      <h1 class="page-hero-title">Adiba Global Visa Blog</h1>
      <p class="page-hero-desc">Expert insights, updated immigration policies, interview strategies, and actionable advice to help you navigate international visa applications with confidence.</p>
    </div>
  </section>

  <!-- Main Content -->
  <main class="page-main-section">
    <div class="container">
      <div class="blog-grid">
        <!-- Article 1 -->
        <article class="blog-item-card js-open-article" data-article-id="uk-student-visa" style="cursor: pointer;">
          <div class="blog-img-box">
            <img src="{{ asset('assets/images/dest-uk-photo.png') }}" alt="Big Ben UK">
            <span class="blog-tag-badge">STUDENT VISA</span>
          </div>
          <div class="blog-item-body">
            <div class="blog-date-meta">September 18, 2026 &bull; 5 min read</div>
            <h3 class="blog-item-title"><a href="javascript:void(0)" class="blog-card-title-link">Navigating UK Student Visa Rules: CAS &amp; Credibility Interview Prep</a></h3>
            <p class="blog-item-excerpt">Learn how UK Visas and Immigration (UKVI) evaluates credibility interviews and how to prove genuine student intent to guarantee CAS visa success.</p>
            <div style="margin-top: auto; display: flex; align-items: center; justify-content: space-between; padding-top: 14px; border-top: 1px solid #f1f5f9;">
              <span class="blog-read-more" style="color: var(--color-brand-blue); font-weight: 700; font-size: 13.5px;">Read Full Article &rarr;</span>
              <button type="button" class="btn-primary js-open-modal" data-country="UK" data-visa="Student" onclick="event.stopPropagation();" style="padding: 6px 14px; font-size: 12px; border-radius: 6px;">Apply &rarr;</button>
            </div>
          </div>
        </article>

        <!-- Article 2 -->
        <article class="blog-item-card js-open-article" data-article-id="usa-f1-visa" style="cursor: pointer;">
          <div class="blog-img-box">
            <img src="{{ asset('assets/images/dest-usa-photo.png') }}" alt="USA Statue of Liberty">
            <span class="blog-tag-badge">USA IMMIGRATION</span>
          </div>
          <div class="blog-item-body">
            <div class="blog-date-meta">September 12, 2026 &bull; 6 min read</div>
            <h3 class="blog-item-title"><a href="javascript:void(0)" class="blog-card-title-link">Cracking the US F-1 Visa Interview: 10 Common Questions &amp; Winning Answers</a></h3>
            <p class="blog-item-excerpt">Discover why section 214(b) refusals happen and how to convincingly demonstrate strong home ties and financial sponsorship to consular officers.</p>
            <div style="margin-top: auto; display: flex; align-items: center; justify-content: space-between; padding-top: 14px; border-top: 1px solid #f1f5f9;">
              <span class="blog-read-more" style="color: var(--color-brand-blue); font-weight: 700; font-size: 13.5px;">Read Full Article &rarr;</span>
              <button type="button" class="btn-primary js-open-modal" data-country="USA" data-visa="Student" onclick="event.stopPropagation();" style="padding: 6px 14px; font-size: 12px; border-radius: 6px;">Apply &rarr;</button>
            </div>
          </div>
        </article>

        <!-- Article 3 -->
        <article class="blog-item-card js-open-article" data-article-id="india-visa-guide" style="cursor: pointer;">
          <div class="blog-img-box">
            <img src="{{ asset('assets/images/dest-india-photo.png') }}" alt="Taj Mahal India">
            <span class="blog-tag-badge">TRAVEL &amp; MEDICAL</span>
          </div>
          <div class="blog-item-body">
            <div class="blog-date-meta">September 05, 2026 &bull; 4 min read</div>
            <h3 class="blog-item-title"><a href="javascript:void(0)" class="blog-card-title-link">Complete Guide to India Tourist, Medical &amp; Double Entry Visas in 2026</a></h3>
            <p class="blog-item-excerpt">Step-by-step checklist for IVAC token scheduling, essential hospital invitations for medical patients, and obtaining transit double-entry stamps.</p>
            <div style="margin-top: auto; display: flex; align-items: center; justify-content: space-between; padding-top: 14px; border-top: 1px solid #f1f5f9;">
              <span class="blog-read-more" style="color: var(--color-brand-blue); font-weight: 700; font-size: 13.5px;">Read Full Article &rarr;</span>
              <button type="button" class="btn-primary js-open-modal" data-country="India" data-visa="Tourist" onclick="event.stopPropagation();" style="padding: 6px 14px; font-size: 12px; border-radius: 6px;">Apply &rarr;</button>
            </div>
          </div>
        </article>

        <!-- Article 4 -->
        <article class="blog-item-card js-open-article" data-article-id="italy-scholarship-visa" style="cursor: pointer;">
          <div class="blog-img-box">
            <img src="{{ asset('assets/images/dest-italy-photo.png') }}" alt="Colosseum Italy">
            <span class="blog-tag-badge">EUROPE SCHENGEN</span>
          </div>
          <div class="blog-item-body">
            <div class="blog-date-meta">August 28, 2026 &bull; 7 min read</div>
            <h3 class="blog-item-title"><a href="javascript:void(0)" class="blog-card-title-link">Study in Italy on 100% Scholarship: DSU Regional Stipends &amp; Visa Steps</a></h3>
            <p class="blog-item-excerpt">How South Asian students can study in Italy with zero tuition fees and receive €7,000+ yearly living stipends via regional DSU and ISEE Parificato.</p>
            <div style="margin-top: auto; display: flex; align-items: center; justify-content: space-between; padding-top: 14px; border-top: 1px solid #f1f5f9;">
              <span class="blog-read-more" style="color: var(--color-brand-blue); font-weight: 700; font-size: 13.5px;">Read Full Article &rarr;</span>
              <button type="button" class="btn-primary js-open-modal" data-country="Italy" data-visa="Student" onclick="event.stopPropagation();" style="padding: 6px 14px; font-size: 12px; border-radius: 6px;">Apply &rarr;</button>
            </div>
          </div>
        </article>

        <!-- Article 5 -->
        <article class="blog-item-card js-open-article" data-article-id="malaysia-calling-visa" style="cursor: pointer;">
          <div class="blog-img-box">
            <img src="{{ asset('assets/images/dest-malaysia-photo.png') }}" alt="Petronas Towers Malaysia">
            <span class="blog-tag-badge">WORK VISAS</span>
          </div>
          <div class="blog-item-body">
            <div class="blog-date-meta">August 19, 2026 &bull; 5 min read</div>
            <h3 class="blog-item-title"><a href="javascript:void(0)" class="blog-card-title-link">Malaysia Calling Visa Verification: How to Spot and Avoid Fake Job Offers</a></h3>
            <p class="blog-item-excerpt">Protect your hard-earned money. Essential online verification methods on the official ESD and FWCEMS portals to ensure your calling visa is 100% genuine.</p>
            <div style="margin-top: auto; display: flex; align-items: center; justify-content: space-between; padding-top: 14px; border-top: 1px solid #f1f5f9;">
              <span class="blog-read-more" style="color: var(--color-brand-blue); font-weight: 700; font-size: 13.5px;">Read Full Article &rarr;</span>
              <button type="button" class="btn-primary js-open-modal" data-country="Malaysia" data-visa="Work" onclick="event.stopPropagation();" style="padding: 6px 14px; font-size: 12px; border-radius: 6px;">Apply &rarr;</button>
            </div>
          </div>
        </article>

        <!-- Article 6 -->
        <article class="blog-item-card js-open-article" data-article-id="saudi-umrah-rules" style="cursor: pointer;">
          <div class="blog-img-box">
            <img src="{{ asset('assets/images/dest-saudi-photo.png') }}" alt="Kaaba Saudi Arabia">
            <span class="blog-tag-badge">HOLY PILGRIMAGE</span>
          </div>
          <div class="blog-item-body">
            <div class="blog-date-meta">August 10, 2026 &bull; 4 min read</div>
            <h3 class="blog-item-title"><a href="javascript:void(0)" class="blog-card-title-link">New Saudi Umrah Rules: Nusuk E-Visa, Female Travelers &amp; Hotel Bookings</a></h3>
            <p class="blog-item-excerpt">Everything pilgrims need to know about the instant 90-day multi-entry Umrah e-visa, Nusuk app slot reservations, and high-speed train transit.</p>
            <div style="margin-top: auto; display: flex; align-items: center; justify-content: space-between; padding-top: 14px; border-top: 1px solid #f1f5f9;">
              <span class="blog-read-more" style="color: var(--color-brand-blue); font-weight: 700; font-size: 13.5px;">Read Full Article &rarr;</span>
              <button type="button" class="btn-primary js-open-modal" data-country="Saudi Arabia" data-visa="Umrah" onclick="event.stopPropagation();" style="padding: 6px 14px; font-size: 12px; border-radius: 6px;">Apply &rarr;</button>
            </div>
          </div>
        </article>
      </div>
    </div>
  </main>
@endsection

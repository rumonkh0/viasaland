@extends('layouts.public', [
  'title' => 'Global Destinations & Country Visas - Adiba Global',
  'description' => 'Explore 20+ available country visas with processing times, visa types, and success rates for UK, USA, Canada, Australia, Europe, India, Saudi Arabia and more.',
  'activePage' => 'countries'
])

@section('content')
  <!-- Page Hero Banner -->
  <section class="page-hero">
    <div class="container">
      <div class="page-breadcrumb">
        <a href="{{ url('/') }}">Home</a>
        <span class="separator">&rsaquo;</span>
        <span class="current">Global Destinations</span>
      </div>
      <h1 class="page-hero-title">Explore 20+ Available Country Visas</h1>
      <p class="page-hero-desc">Discover processing times, available visa classes, and success rates for leading travel, study, and work destinations worldwide.</p>
    </div>
  </section>

  <!-- Main Content -->
  <main class="page-main-section">
    <div class="container">
      <!-- Search & Filters Toolbar -->
      <div class="catalog-toolbar" style="background: #FFFFFF; padding: 20px; border-radius: 14px; border: 1px solid rgba(0, 102, 255, 0.1); margin-bottom: 28px;">
        <div class="search-box-wrapper" style="max-width: 100%; margin-bottom: 14px;">
          <input type="text" id="countrySearchInput" class="catalog-search-input" placeholder="🔍 Search any country or visa type (e.g., India, UK, Student, Medical)...">
        </div>
        <div class="filter-pills-row" id="countryFilterPills">
          <button type="button" class="filter-pill active" data-continent="all">All Continents</button>
          <button type="button" class="filter-pill" data-continent="asia-middle-east">Asia &amp; Middle East</button>
          <button type="button" class="filter-pill" data-continent="europe">Europe</button>
          <button type="button" class="filter-pill" data-continent="north-america">North America</button>
          <button type="button" class="filter-pill" data-continent="oceania">Oceania</button>
        </div>
      </div>

      <!-- Countries Catalog Grid -->
      <div class="catalog-countries-grid" id="catalogCountriesGrid">
        <!-- 1. India -->
        <div class="catalog-country-card" data-continent="asia-middle-east" data-country="India Bharat Tourist Medical Double Entry">
          <div class="cat-card-top">
            <span class="cat-flag">🇮🇳</span>
            <div>
              <h4 class="cat-country-name">India</h4>
              <span class="cat-continent-badge">South Asia</span>
            </div>
          </div>
          <div class="cat-meta-row">
            <span class="cat-time">⏱️ 3–5 Days</span>
            <span class="cat-success">★ 99% Success</span>
          </div>
          <div class="cat-visas-tags">
            <span class="visa-tag">Tourist</span>
            <span class="visa-tag">Medical</span>
            <span class="visa-tag">Double Entry</span>
          </div>
          <button type="button" class="cat-apply-btn js-apply-from-catalog" data-country="India" data-visa="Tourist">
            Apply for India &rarr;
          </button>
        </div>

        <!-- 2. UK -->
        <div class="catalog-country-card" data-continent="europe" data-country="United Kingdom UK England Britain">
          <div class="cat-card-top">
            <span class="cat-flag">🇬🇧</span>
            <div>
              <h4 class="cat-country-name">United Kingdom</h4>
              <span class="cat-continent-badge">Europe</span>
            </div>
          </div>
          <div class="cat-meta-row">
            <span class="cat-time">⏱️ 2–3 Weeks</span>
            <span class="cat-success">★ 99% Success</span>
          </div>
          <div class="cat-visas-tags">
            <span class="visa-tag">Student</span>
            <span class="visa-tag">Visitor</span>
            <span class="visa-tag">Skilled Worker</span>
          </div>
          <button type="button" class="cat-apply-btn js-apply-from-catalog" data-country="UK" data-visa="Student">
            Apply for UK &rarr;
          </button>
        </div>

        <!-- 3. USA -->
        <div class="catalog-country-card" data-continent="north-america" data-country="United States of America USA America">
          <div class="cat-card-top">
            <span class="cat-flag">🇺🇸</span>
            <div>
              <h4 class="cat-country-name">United States</h4>
              <span class="cat-continent-badge">North America</span>
            </div>
          </div>
          <div class="cat-meta-row">
            <span class="cat-time">⏱️ Slot Dependent</span>
            <span class="cat-success">★ 97% Success</span>
          </div>
          <div class="cat-visas-tags">
            <span class="visa-tag">F-1 Student</span>
            <span class="visa-tag">B1/B2 Visitor</span>
            <span class="visa-tag">J-1 Exchange</span>
          </div>
          <button type="button" class="cat-apply-btn js-apply-from-catalog" data-country="USA" data-visa="Student">
            Apply for USA &rarr;
          </button>
        </div>

        <!-- 4. Canada -->
        <div class="catalog-country-card" data-continent="north-america" data-country="Canada Toronto Vancouver">
          <div class="cat-card-top">
            <span class="cat-flag">🇨🇦</span>
            <div>
              <h4 class="cat-country-name">Canada</h4>
              <span class="cat-continent-badge">North America</span>
            </div>
          </div>
          <div class="cat-meta-row">
            <span class="cat-time">⏱️ 4–8 Weeks</span>
            <span class="cat-success">★ 98% Success</span>
          </div>
          <div class="cat-visas-tags">
            <span class="visa-tag">Study Permit</span>
            <span class="visa-tag">Visitor TRV</span>
            <span class="visa-tag">Express Entry</span>
          </div>
          <button type="button" class="cat-apply-btn js-apply-from-catalog" data-country="Canada" data-visa="Student">
            Apply for Canada &rarr;
          </button>
        </div>

        <!-- 5. Australia -->
        <div class="catalog-country-card" data-continent="oceania" data-country="Australia Sydney Melbourne">
          <div class="cat-card-top">
            <span class="cat-flag">🇦🇺</span>
            <div>
              <h4 class="cat-country-name">Australia</h4>
              <span class="cat-continent-badge">Oceania</span>
            </div>
          </div>
          <div class="cat-meta-row">
            <span class="cat-time">⏱️ 3–5 Weeks</span>
            <span class="cat-success">★ 98% Success</span>
          </div>
          <div class="cat-visas-tags">
            <span class="visa-tag">Subclass 500 Student</span>
            <span class="visa-tag">Subclass 600 Tourist</span>
          </div>
          <button type="button" class="cat-apply-btn js-apply-from-catalog" data-country="Australia" data-visa="Student">
            Apply for Australia &rarr;
          </button>
        </div>

        <!-- 6. Italy -->
        <div class="catalog-country-card" data-continent="europe" data-country="Italy Schengen Rome Milan">
          <div class="cat-card-top">
            <span class="cat-flag">🇮🇹</span>
            <div>
              <h4 class="cat-country-name">Italy (Schengen)</h4>
              <span class="cat-continent-badge">Europe</span>
            </div>
          </div>
          <div class="cat-meta-row">
            <span class="cat-time">⏱️ 2–4 Weeks</span>
            <span class="cat-success">★ 99% Success</span>
          </div>
          <div class="cat-visas-tags">
            <span class="visa-tag">Study (Scholarship)</span>
            <span class="visa-tag">Tourist C</span>
            <span class="visa-tag">Seasonal Work</span>
          </div>
          <button type="button" class="cat-apply-btn js-apply-from-catalog" data-country="Italy" data-visa="Student">
            Apply for Italy &rarr;
          </button>
        </div>

        <!-- 7. Malaysia -->
        <div class="catalog-country-card" data-continent="asia-middle-east" data-country="Malaysia Kuala Lumpur">
          <div class="cat-card-top">
            <span class="cat-flag">🇲🇾</span>
            <div>
              <h4 class="cat-country-name">Malaysia</h4>
              <span class="cat-continent-badge">Southeast Asia</span>
            </div>
          </div>
          <div class="cat-meta-row">
            <span class="cat-time">⏱️ 5–7 Days</span>
            <span class="cat-success">★ 99% Success</span>
          </div>
          <div class="cat-visas-tags">
            <span class="visa-tag">eNTRI/eVISA</span>
            <span class="visa-tag">Calling Visa</span>
            <span class="visa-tag">Student EMGS</span>
          </div>
          <button type="button" class="cat-apply-btn js-apply-from-catalog" data-country="Malaysia" data-visa="Work">
            Apply for Malaysia &rarr;
          </button>
        </div>

        <!-- 8. Saudi Arabia -->
        <div class="catalog-country-card" data-continent="asia-middle-east" data-country="Saudi Arabia KSA Riyadh Makkah Madinah Umrah">
          <div class="cat-card-top">
            <span class="cat-flag">🇸🇦</span>
            <div>
              <h4 class="cat-country-name">Saudi Arabia</h4>
              <span class="cat-continent-badge">Middle East</span>
            </div>
          </div>
          <div class="cat-meta-row">
            <span class="cat-time">⏱️ 24–48 Hours</span>
            <span class="cat-success">★ 100% Success</span>
          </div>
          <div class="cat-visas-tags">
            <span class="visa-tag">Umrah E-Visa</span>
            <span class="visa-tag">Work Visa</span>
            <span class="visa-tag">Commercial Visit</span>
          </div>
          <button type="button" class="cat-apply-btn js-apply-from-catalog" data-country="Saudi Arabia" data-visa="Umrah">
            Apply for Saudi Arabia &rarr;
          </button>
        </div>

        <!-- 9. UAE -->
        <div class="catalog-country-card" data-continent="asia-middle-east" data-country="United Arab Emirates UAE Dubai Abu Dhabi">
          <div class="cat-card-top">
            <span class="cat-flag">🇦🇪</span>
            <div>
              <h4 class="cat-country-name">United Arab Emirates (UAE)</h4>
              <span class="cat-continent-badge">Middle East</span>
            </div>
          </div>
          <div class="cat-meta-row">
            <span class="cat-time">⏱️ 2–3 Days</span>
            <span class="cat-success">★ 99% Success</span>
          </div>
          <div class="cat-visas-tags">
            <span class="visa-tag">30/60 Days Tourist</span>
            <span class="visa-tag">Job Seeker Visa</span>
            <span class="visa-tag">Golden Visa</span>
          </div>
          <button type="button" class="cat-apply-btn js-apply-from-catalog" data-country="UAE" data-visa="Tourist">
            Apply for UAE &rarr;
          </button>
        </div>

        <!-- 10. Germany -->
        <div class="catalog-country-card" data-continent="europe" data-country="Germany Deutschland Berlin Schengen">
          <div class="cat-card-top">
            <span class="cat-flag">🇩🇪</span>
            <div>
              <h4 class="cat-country-name">Germany</h4>
              <span class="cat-continent-badge">Europe</span>
            </div>
          </div>
          <div class="cat-meta-row">
            <span class="cat-time">⏱️ 3–6 Weeks</span>
            <span class="cat-success">★ 98% Success</span>
          </div>
          <div class="cat-visas-tags">
            <span class="visa-tag">Free Tuition Student</span>
            <span class="visa-tag">Opportunity Card</span>
            <span class="visa-tag">Chancenkarte</span>
          </div>
          <button type="button" class="cat-apply-btn js-apply-from-catalog" data-country="Germany" data-visa="Student">
            Apply for Germany &rarr;
          </button>
        </div>

        <!-- 11. Japan -->
        <div class="catalog-country-card" data-continent="asia-middle-east" data-country="Japan Tokyo Osaka">
          <div class="cat-card-top">
            <span class="cat-flag">🇯🇵</span>
            <div>
              <h4 class="cat-country-name">Japan</h4>
              <span class="cat-continent-badge">East Asia</span>
            </div>
          </div>
          <div class="cat-meta-row">
            <span class="cat-time">⏱️ 7–10 Days</span>
            <span class="cat-success">★ 97% Success</span>
          </div>
          <div class="cat-visas-tags">
            <span class="visa-tag">Student COE</span>
            <span class="visa-tag">SSW Specified Worker</span>
            <span class="visa-tag">Short-Term Tourist</span>
          </div>
          <button type="button" class="cat-apply-btn js-apply-from-catalog" data-country="Japan" data-visa="Student">
            Apply for Japan &rarr;
          </button>
        </div>

        <!-- 12. South Korea -->
        <div class="catalog-country-card" data-continent="asia-middle-east" data-country="South Korea Seoul">
          <div class="cat-card-top">
            <span class="cat-flag">🇰🇷</span>
            <div>
              <h4 class="cat-country-name">South Korea</h4>
              <span class="cat-continent-badge">East Asia</span>
            </div>
          </div>
          <div class="cat-meta-row">
            <span class="cat-time">⏱️ 2–3 Weeks</span>
            <span class="cat-success">★ 98% Success</span>
          </div>
          <div class="cat-visas-tags">
            <span class="visa-tag">D-2 / D-4 Student</span>
            <span class="visa-tag">E-7 Skilled Worker</span>
            <span class="visa-tag">C-3 Tourist</span>
          </div>
          <button type="button" class="cat-apply-btn js-apply-from-catalog" data-country="South Korea" data-visa="Student">
            Apply for South Korea &rarr;
          </button>
        </div>
      </div>
    </div>
  </main>
@endsection

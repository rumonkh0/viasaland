// Adiba Global - Interactive Scripts

document.addEventListener('DOMContentLoaded', () => {
  // =========================================================================
  // 1. Universal Modal Controller (Consultation + Catalog Modals)
  // =========================================================================
  const allModals = document.querySelectorAll('.modal-overlay');
  const consultationModal = document.getElementById('consultationModal');
  const modalForm = document.getElementById('consultationForm');
  const successAlert = document.getElementById('modalSuccessAlert');
  const countrySelect = document.getElementById('country');
  const visaTypeSelect = document.getElementById('visaType');

  function openTargetModal(modalEl) {
    if (!modalEl) return;
    closeAllModals();
    modalEl.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function closeAllModals() {
    allModals.forEach(m => m.classList.remove('active'));
    document.body.style.overflow = '';
    if (successAlert) successAlert.style.display = 'none';
  }

  // Consultation open triggers
  document.querySelectorAll('.js-open-modal').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      openTargetModal(consultationModal);
    });
  });

  // Catalog Modals open triggers (Countries, Services, Reviews)
  document.querySelectorAll('.js-open-catalog').forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      const targetId = link.getAttribute('data-target');
      const targetModal = document.getElementById(targetId);
      if (targetModal) openTargetModal(targetModal);
    });
  });

  // Close triggers (X buttons, Cancel)
  document.querySelectorAll('.modal-close-btn, .js-close-modal').forEach(btn => {
    btn.addEventListener('click', () => {
      closeAllModals();
    });
  });

  // Backdrop click to close
  allModals.forEach(m => {
    m.addEventListener('click', (e) => {
      if (e.target === m) closeAllModals();
    });
  });

  // Escape key to close
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeAllModals();
    }
  });

  // 1-Click Apply from Catalog Modals directly to Consultation Modal
  document.querySelectorAll('.js-apply-from-catalog').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const country = btn.getAttribute('data-country');
      const visa = btn.getAttribute('data-visa');

      closeAllModals();
      openTargetModal(consultationModal);

      if (country && countrySelect) {
        countrySelect.value = country;
      }
      if (visa && visaTypeSelect) {
        visaTypeSelect.value = visa;
      }
      updateVisaRequirementsDisplay(visa, country);
    });
  });

  // Dynamic Visa Requirements Display (Country + Visa Type)
  const reqCountryName = document.getElementById('reqCountryName');
  const reqVisaTypeName = document.getElementById('reqVisaTypeName');
  const reqCountBadge = document.getElementById('reqCountBadge');
  const visaRequirementsList = document.getElementById('visaRequirementsList');
  const fileInput = document.getElementById('consultationDocuments');
  const selectedFilesSummary = document.getElementById('selectedFilesSummary');
  const dropZoneContainer = document.getElementById('dropZoneContainer');

  function getRequirementsFor(visaType, country) {
    if (!window.visaRequirements) return [];

    // If it's an object keyed by visa_type (legacy)
    if (!Array.isArray(window.visaRequirements) && typeof window.visaRequirements === 'object') {
      return window.visaRequirements[visaType] || [];
    }

    const list = Array.isArray(window.visaRequirements) ? window.visaRequirements : [];
    const vType = (visaType || '').trim().toLowerCase();
    const cName = (country || '').trim().toLowerCase();

    // 1. Try matching country and visa_type
    if (cName) {
      const countryMatched = list.filter(r => {
        return (r.country || '').trim().toLowerCase() === cName &&
          (r.visa_type || '').trim().toLowerCase() === vType;
      });
      if (countryMatched.length > 0) {
        return countryMatched;
      }
    }

    // 2. Try global default for visa_type (country is null/empty or 'all countries')
    const defaultMatched = list.filter(r => {
      const isGlobal = !r.country || (r.country || '').trim().toLowerCase() === 'all countries';
      return isGlobal && (r.visa_type || '').trim().toLowerCase() === vType;
    });
    if (defaultMatched.length > 0) {
      return defaultMatched;
    }

    // 3. Any item matching visa_type
    const anyMatched = list.filter(r => {
      return (r.visa_type || '').trim().toLowerCase() === vType;
    });
    if (anyMatched.length > 0) {
      return anyMatched;
    }

    // 4. Default fallback
    return [
      { name: 'Passport', is_mandatory: true },
      { name: 'Photograph', is_mandatory: true },
      { name: 'Bank Statement', is_mandatory: true },
      { name: 'Visa Application Form', is_mandatory: true },
    ];
  }

  function updateVisaRequirementsDisplay(visaType, country) {
    if (!visaRequirementsList) return;

    visaType = visaType || (visaTypeSelect ? visaTypeSelect.value : 'Tourist');
    country = country || (countrySelect ? countrySelect.value : 'India');

    if (reqCountryName) {
      reqCountryName.textContent = country ? country + ' ' : '';
    }
    if (reqVisaTypeName) {
      reqVisaTypeName.textContent = visaType;
    }

    const reqs = getRequirementsFor(visaType, country);

    if (reqCountBadge) {
      reqCountBadge.textContent = reqs.length + ' Requirements';
    }

    visaRequirementsList.innerHTML = '';
    reqs.forEach(r => {
      const pill = document.createElement('span');
      pill.className = 'vr-pill';
      pill.innerHTML = `
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#0066FF" stroke-width="2.2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
        <span>${r.name}</span>
        ${r.is_mandatory ? '<span class="pill-badge">Mandatory</span>' : '<span class="pill-badge" style="background:#f1f5f9;color:#64748b;">Optional</span>'}
      `;
      visaRequirementsList.appendChild(pill);
    });
  }

  if (visaTypeSelect) {
    visaTypeSelect.addEventListener('change', () => {
      updateVisaRequirementsDisplay(visaTypeSelect.value, countrySelect ? countrySelect.value : null);
    });
  }

  if (countrySelect) {
    countrySelect.addEventListener('change', () => {
      updateVisaRequirementsDisplay(visaTypeSelect ? visaTypeSelect.value : null, countrySelect.value);
    });
  }

  // Initial display
  updateVisaRequirementsDisplay(
    visaTypeSelect ? visaTypeSelect.value : 'Tourist',
    countrySelect ? countrySelect.value : 'India'
  );

  // File selection summary
  if (fileInput && selectedFilesSummary) {
    fileInput.addEventListener('change', () => {
      const files = Array.from(fileInput.files);
      if (files.length === 0) {
        selectedFilesSummary.style.display = 'none';
        selectedFilesSummary.innerHTML = '';
        return;
      }

      selectedFilesSummary.style.display = 'flex';
      selectedFilesSummary.innerHTML = '';
      files.forEach(f => {
        const sizeFormatted = (f.size / 1024).toFixed(0) + ' KB';
        const chip = document.createElement('span');
        chip.className = 'file-chip';
        chip.innerHTML = `📎 ${f.name} (${sizeFormatted})`;
        selectedFilesSummary.appendChild(chip);
      });
    });
  }

  // Drag and drop support on dropZoneContainer
  if (dropZoneContainer && fileInput) {
    ['dragenter', 'dragover'].forEach(eventName => {
      dropZoneContainer.addEventListener(eventName, (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropZoneContainer.style.borderColor = '#0066FF';
        dropZoneContainer.style.backgroundColor = '#EBF3FF';
      }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
      dropZoneContainer.addEventListener(eventName, (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropZoneContainer.style.borderColor = '#CBD5E1';
        dropZoneContainer.style.backgroundColor = '#F8FAFC';
      }, false);
    });

    dropZoneContainer.addEventListener('drop', (e) => {
      const dt = e.dataTransfer;
      const files = dt.files;
      if (files.length > 0) {
        fileInput.files = files;
        fileInput.dispatchEvent(new Event('change'));
      }
    }, false);
  }


  // =========================================================================
  // 2. Real-Time Search & Continent Filtering for Countries Catalog
  // =========================================================================
  const countrySearchInput = document.getElementById('countrySearchInput');
  const continentPills = document.querySelectorAll('#countryFilterPills .filter-pill');
  const countryCards = document.querySelectorAll('.catalog-country-card');

  let activeContinent = 'all';

  function filterCountries() {
    const query = countrySearchInput ? countrySearchInput.value.trim().toLowerCase() : '';

    countryCards.forEach(card => {
      const cardContinent = card.getAttribute('data-continent');
      const cardCountryData = (card.getAttribute('data-country') || '').toLowerCase();
      const cardText = card.textContent.toLowerCase();

      const matchesContinent = (activeContinent === 'all') || (cardContinent === activeContinent);
      const matchesSearch = !query || cardCountryData.includes(query) || cardText.includes(query);

      if (matchesContinent && matchesSearch) {
        card.style.display = 'flex';
      } else {
        card.style.display = 'none';
      }
    });
  }

  if (countrySearchInput) {
    countrySearchInput.addEventListener('input', filterCountries);
  }

  continentPills.forEach(pill => {
    pill.addEventListener('click', () => {
      continentPills.forEach(p => p.classList.remove('active'));
      pill.classList.add('active');
      activeContinent = pill.getAttribute('data-continent');
      filterCountries();
    });
  });

  // =========================================================================
  // 3. Navigation Links, Smooth Scroll & Mobile Menu Toggle
  // =========================================================================
  const mobileToggle = document.getElementById('mobileMenuToggle');
  const navLinksContainer = document.getElementById('navLinks');

  if (mobileToggle && navLinksContainer) {
    mobileToggle.addEventListener('click', () => {
      navLinksContainer.classList.toggle('mobile-active');
    });
  }

  // Smooth scroll ONLY for anchor links targeting an ID on the current page
  document.querySelectorAll('a[href^="#"]:not([href="#"]):not([href="#consultation"]):not([href="#all-countries"]):not([href="#all-services"]):not([href="#all-reviews"])').forEach(link => {
    link.addEventListener('click', (e) => {
      const hash = link.getAttribute('href');
      if (!hash || hash === '#') return;

      try {
        const target = document.querySelector(hash);
        if (target) {
          e.preventDefault();
          const headerOffset = 75;
          const elementPosition = target.getBoundingClientRect().top;
          const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

          window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
          });

          document.querySelectorAll('.nav-links .nav-link').forEach(l => l.classList.remove('active'));
          link.classList.add('active');

          if (history.pushState) {
            history.pushState(null, null, hash);
          }
        }
      } catch (err) {
        // Ignore querySelector error
      }

      if (navLinksContainer) {
        navLinksContainer.classList.remove('mobile-active');
      }
    });
  });

  // ScrollSpy to highlight active section as user scrolls
  const trackedSections = document.querySelectorAll('section[id], footer[id]');
  window.addEventListener('scroll', () => {
    const scrollPos = window.scrollY + 120;
    trackedSections.forEach(section => {
      const top = section.offsetTop;
      const height = section.offsetHeight;
      const id = section.getAttribute('id');
      if (scrollPos >= top && scrollPos < top + height) {
        document.querySelectorAll('.nav-links .nav-link').forEach(link => {
          if (link.getAttribute('href') === '#' + id) {
            document.querySelectorAll('.nav-links .nav-link').forEach(l => l.classList.remove('active'));
            link.classList.add('active');
          }
        });
      }
    });
  }, { passive: true });

  // =========================================================================
  // 4. Footer Accordion on Mobile
  // =========================================================================
  const footerAccordionBtns = document.querySelectorAll('.footer-accordion-btn');
  footerAccordionBtns.forEach(btn => {
    btn.addEventListener('click', (e) => {
      if (window.innerWidth <= 768) {
        e.preventDefault();
        const col = btn.closest('.footer-accordion');
        if (!col) return;
        const isOpen = col.classList.contains('is-open');

        document.querySelectorAll('.footer-accordion').forEach(item => {
          if (item !== col) {
            item.classList.remove('is-open');
            const otherBtn = item.querySelector('.footer-accordion-btn');
            if (otherBtn) otherBtn.setAttribute('aria-expanded', 'false');
          }
        });

        col.classList.toggle('is-open', !isOpen);
        btn.setAttribute('aria-expanded', String(!isOpen));
      }
    });
  });

  // =========================================================================
  // 5. FAQ Page Accordion Toggles
  // =========================================================================
  const faqHeaders = document.querySelectorAll('.faq-header-btn');
  faqHeaders.forEach(btn => {
    btn.addEventListener('click', () => {
      const card = btn.closest('.faq-card');
      if (!card) return;
      const isActive = card.classList.contains('is-active');

      card.classList.toggle('is-active', !isActive);
      btn.setAttribute('aria-expanded', String(!isActive));
    });
  });

  // =========================================================================
  // 6. Interactive Article Reader Modal (Blog Posts)
  // =========================================================================
  const articleModal = document.getElementById('articleReaderModal');
  const readerTitle = document.getElementById('readerArticleTitle');
  const readerTag = document.getElementById('readerTagBadge');
  const readerDateMeta = document.getElementById('readerDateMeta');
  const readerAuthor = document.getElementById('readerAuthor');
  const readerCoverImg = document.getElementById('readerCoverImg');
  const readerBody = document.getElementById('readerArticleBody');
  const readerApplyBtn = document.getElementById('readerApplyBtn');

  const articlesData = {
    'uk-student-visa': {
      title: 'Navigating UK Student Visa Rules: CAS & Credibility Interview Prep',
      tag: 'STUDENT VISA',
      meta: 'September 18, 2026 • 5 min read',
      author: 'Senior UK Education Counselor',
      img: 'assets/images/dest-uk-photo.png',
      country: 'UK',
      visaType: 'Student',
      html: `
        <p style="font-size: 16px; font-weight: 500; color: #1e293b; margin-bottom: 20px;">
          Securing your Confirmation of Acceptance for Studies (CAS) is a major milestone, but the credibility interview conducted by UK Visas and Immigration (UKVI) is where the real evaluation happens. Here is our comprehensive preparation guide.
        </p>
        <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin: 24px 0 12px;">1. Understanding the Credibility Interview</h3>
        <p>The interview is usually conducted via video link by an Entry Clearance Officer (ECO) in Sheffield. The officer evaluates three core elements: your genuine intention to study, your academic background relevance, and your realistic post-study career trajectory.</p>
        <div style="background: #f8fafc; border-left: 4px solid #0066ff; padding: 14px 18px; margin: 20px 0; border-radius: 0 8px 8px 0;">
          <strong style="color: #0f172a;">Key Rule:</strong> UKVI assesses whether you know specific module names, faculty credentials, and tuition breakdown for your chosen university without looking at notes.
        </div>
        <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin: 24px 0 12px;">2. Financial Maintenance (28-Day Strict Rule)</h3>
        <p>You must prove maintenance funds of £1,334 per month for up to 9 months (£12,006) for London institutions, or £1,023 per month (£9,207) outside London. The entire amount plus outstanding tuition must remain untouched in an approved bank account for a continuous 28-day holding period before your application date.</p>
        <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin: 24px 0 12px;">3. The Top 4 Interview Questions</h3>
        <ul style="padding-left: 20px; margin-bottom: 20px; line-height: 1.8;">
          <li style="margin-bottom: 8px;"><strong>Why this university?</strong> Detail specific modules, laboratory facilities, and professors rather than generic statements about university rankings.</li>
          <li style="margin-bottom: 8px;"><strong>Why study in the UK instead of Bangladesh?</strong> Compare local program curriculum gaps with UK course accreditation.</li>
          <li style="margin-bottom: 8px;"><strong>How will this course help your career?</strong> Name target employers, designated job titles, and expected remuneration in Bangladesh.</li>
          <li style="margin-bottom: 8px;"><strong>Who is funding your studies?</strong> Clearly state family business turnover, tax filings, and bank solvency details.</li>
        </ul>
      `
    },
    'usa-f1-visa': {
      title: 'Cracking the US F-1 Visa Interview: 10 Common Questions & Winning Answers',
      tag: 'USA IMMIGRATION',
      meta: 'September 12, 2026 • 6 min read',
      author: 'US Admissions Specialist',
      img: 'assets/images/dest-usa-photo.png',
      country: 'USA',
      visaType: 'Student',
      html: `
        <p style="font-size: 16px; font-weight: 500; color: #1e293b; margin-bottom: 20px;">
          The US F-1 visa interview is famous for being short—often under three minutes. In that brief window, consular officers make a determination under Section 214(b) of the Immigration and Nationality Act. Here is how to present your case convincingly.
        </p>
        <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin: 24px 0 12px;">1. Overcoming Section 214(b) (Non-Immigrant Intent)</h3>
        <p>By US federal law, every student applicant is presumed to have immigrant intent until they convince the consular officer otherwise. You cannot just state "I will return"—you must articulate an unavoidable post-graduation return plan backed by strong economic and familial home ties.</p>
        <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin: 24px 0 12px;">2. Crucial Guidelines for Sponsor Answers</h3>
        <p>When asked "Who is sponsoring you?", do not simply reply "My father." Provide his profession, business registration, annual income, and clear liquid asset proof. The officer needs to know that financing your education will not deplete your family's basic livelihood.</p>
        <div style="background: #f8fafc; border-left: 4px solid #0066ff; padding: 14px 18px; margin: 20px 0; border-radius: 0 8px 8px 0;">
          <strong style="color: #0f172a;">Consular Golden Rule:</strong> Keep answers direct, concise (under 30 seconds per question), maintain eye contact, and never volunteer documents unless the officer explicitly requests them.
        </div>
      `
    },
    'india-visa-guide': {
      title: 'Complete Guide to India Tourist, Medical & Double Entry Visas in 2026',
      tag: 'TRAVEL & MEDICAL',
      meta: 'September 05, 2026 • 4 min read',
      author: 'Regional Visa Operations Head',
      img: 'assets/images/dest-india-photo.png',
      country: 'India',
      visaType: 'Tourist',
      html: `
        <p style="font-size: 16px; font-weight: 500; color: #1e293b; margin-bottom: 20px;">
          India is the most frequent travel destination for Bangladeshi citizens for tourism, medical treatment, and transit to third countries. Here is everything you need to know about the latest IVAC token guidelines and documentation.
        </p>
        <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin: 24px 0 12px;">1. Tourist Visa (T-Visa) Requirements</h3>
        <ul style="padding-left: 20px; margin-bottom: 20px; line-height: 1.8;">
          <li style="margin-bottom: 8px;">Original Passport valid for at least 6 months with all previous old passports attached.</li>
          <li style="margin-bottom: 8px;">Recent 2x2 inch photograph with pure white background and 70-80% face coverage.</li>
          <li style="margin-bottom: 8px;">Current utility bill copy (Electricity/Gas/Landline) matching the present address on the application.</li>
          <li style="margin-bottom: 8px;">Bank endorsement of minimum $150 USD or 6 months bank statement showing minimum BDT 20,000 balance.</li>
        </ul>
        <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin: 24px 0 12px;">2. Medical Visas (MED & MED-X)</h3>
        <p>Medical visas require an authentic medical invitation letter from an accredited Indian hospital, accompanied by doctor prescriptions and diagnosis reports from a recognized specialist. The medical attendant visa (MED-X) is issued for family companions traveling alongside the patient.</p>
      `
    },
    'italy-scholarship-visa': {
      title: 'Study in Italy on 100% Scholarship: DSU Regional Stipends & Visa Steps',
      tag: 'EUROPE SCHENGEN',
      meta: 'August 28, 2026 • 7 min read',
      author: 'European Education Lead',
      img: 'assets/images/dest-italy-photo.png',
      country: 'Italy',
      visaType: 'Student',
      html: `
        <p style="font-size: 16px; font-weight: 500; color: #1e293b; margin-bottom: 20px;">
          Italy is one of the highest-rated European study destinations for South Asian students, offering world-class universities with English-medium programs, 100% tuition waivers, and regional living stipends up to €7,500/year.
        </p>
        <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin: 24px 0 12px;">1. How the DSU Regional Scholarship Works</h3>
        <p>Regional scholarship bodies (such as DSU Toscana, Disco Lazio, EDiSU, ER.GO) provide financial assistance based on family income parity rather than purely high grades. Benefits include 100% university tuition fee waiver, free daily cafeteria meals, subsidized university dormitories, and direct cash disbursements to your Italian bank account.</p>
        <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin: 24px 0 12px;">2. Preparing the ISEE Parificato</h3>
        <p>You must legalize your family income certificate, family composition certificate, and property records through the Ministry of Foreign Affairs (MOFA) in Dhaka, translate them into Italian, and have them verified for your ISEE calculation.</p>
      `
    },
    'malaysia-calling-visa': {
      title: 'Malaysia Calling Visa Verification: How to Spot and Avoid Fake Job Offers',
      tag: 'WORK VISAS',
      meta: 'August 19, 2026 • 5 min read',
      author: 'Labor & Compliance Advisor',
      img: 'assets/images/dest-malaysia-photo.png',
      country: 'Malaysia',
      visaType: 'Work',
      html: `
        <p style="font-size: 16px; font-weight: 500; color: #1e293b; margin-bottom: 20px;">
          The demand for employment in Malaysia has led to an increase in counterfeit Calling Visas (VDR) and unregistered middlemen. Here is the verified verification methodology to protect your rights and finances.
        </p>
        <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin: 24px 0 12px;">1. Online Verification on Official Portals</h3>
        <p>Genuine Calling Visas (Visa Dengan Rujukan) are issued directly by the Immigration Department of Malaysia (JIM). You can verify your application status online using your passport number through the official Malaysian immigration system (ePLKS / ESD portal).</p>
        <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin: 24px 0 12px;">2. Mandatory BMET Clearance</h3>
        <p>Before leaving Bangladesh on a genuine work permit, every worker must obtain a BMET Smart Card from the Bureau of Manpower, Employment and Training. This provides legal protection, wage guarantee verification, and mandatory government worker insurance.</p>
      `
    },
    'saudi-umrah-rules': {
      title: 'New Saudi Umrah Rules: Nusuk E-Visa, Female Travelers & Hotel Bookings',
      tag: 'HOLY PILGRIMAGE',
      meta: 'August 10, 2026 • 4 min read',
      author: 'Hajj & Umrah Coordinator',
      img: 'assets/images/dest-saudi-photo.png',
      country: 'Saudi Arabia',
      visaType: 'Umrah',
      html: `
        <p style="font-size: 16px; font-weight: 500; color: #1e293b; margin-bottom: 20px;">
          Saudi Arabia's Ministry of Hajj and Umrah has modernized the pilgrimage experience under Vision 2030, granting unprecedented flexibility, longer visa validity, and streamlined mobile app permits.
        </p>
        <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin: 24px 0 12px;">1. The 90-Day Multi-Entry Umrah Visa</h3>
        <p>Umrah visas are now valid for a full 90-day stay from date of entry, allowing pilgrims to explore historical sites across Jeddah, Taif, Riyadh, and AlUla after completing their sacred rites in Makkah and Madinah.</p>
        <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin: 24px 0 12px;">2. Booking Rawdah Permits on Nusuk</h3>
        <p>Entry into the noble Rawdah Sharif (Riyadh ul Jannah) in the Prophet's Mosque requires an advance booking slot on the Nusuk application. Slots open on specific weekly schedules and are tied directly to your passport and visa number.</p>
      `
    }
  };

  function openArticleReader(articleId) {
    const article = articlesData[articleId];
    if (!article || !articleModal) return;

    if (readerTitle) readerTitle.textContent = article.title;
    if (readerTag) readerTag.textContent = article.tag;
    if (readerDateMeta) readerDateMeta.textContent = article.meta;
    if (readerAuthor) readerAuthor.textContent = article.author;
    if (readerCoverImg) readerCoverImg.src = article.img;
    if (readerBody) readerBody.innerHTML = article.html;

    if (readerApplyBtn) {
      readerApplyBtn.onclick = () => {
        closeAllModals();
        openTargetModal(consultationModal);
        if (countrySelect && article.country) countrySelect.value = article.country;
        if (visaTypeSelect && article.visaType) visaTypeSelect.value = article.visaType;
        updateVisaRequirementsDisplay(article.visaType, article.country);
      };
    }

    closeAllModals();
    articleModal.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  // Bind click event on every blog article card
  document.querySelectorAll('.js-open-article').forEach(item => {
    item.addEventListener('click', (e) => {
      if (e.target.closest('.js-open-modal') || e.target.closest('button.btn-primary')) {
        return;
      }
      e.preventDefault();
      const articleId = item.getAttribute('data-article-id');
      if (articleId) openArticleReader(articleId);
    });
  });

  document.querySelectorAll('.js-close-reader-modal').forEach(btn => {
    btn.addEventListener('click', () => {
      if (articleModal) articleModal.classList.remove('active');
      document.body.style.overflow = '';
    });
  });

  window.openArticle = openArticleReader;
});


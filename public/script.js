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
  const navLinkItems = document.querySelectorAll('.nav-links .nav-link');

  if (mobileToggle && navLinksContainer) {
    mobileToggle.addEventListener('click', () => {
      navLinksContainer.classList.toggle('mobile-active');
    });
  }

  navLinkItems.forEach(link => {
    link.addEventListener('click', (e) => {
      const href = link.getAttribute('href');
      if (href && href.startsWith('#')) {
        const target = document.querySelector(href);
        if (target) {
          e.preventDefault();
          const headerOffset = 75;
          const elementPosition = target.getBoundingClientRect().top;
          const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

          window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
          });

          navLinkItems.forEach(l => l.classList.remove('active'));
          link.classList.add('active');

          if (history.pushState) {
            history.pushState(null, null, href);
          }
        }
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
        navLinkItems.forEach(link => {
          if (link.getAttribute('href') === '#' + id) {
            navLinkItems.forEach(l => l.classList.remove('active'));
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

      // Toggle current card
      card.classList.toggle('is-active', !isActive);
      btn.setAttribute('aria-expanded', String(!isActive));
    });
  });
});


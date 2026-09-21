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
    });
  });

  // Form submission
  if (modalForm) {
    modalForm.addEventListener('submit', (e) => {
      e.preventDefault();
      if (successAlert) {
        successAlert.style.display = 'block';
        successAlert.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      }
      setTimeout(() => {
        closeAllModals();
        modalForm.reset();
      }, 3500);
    });
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
  // 3. Mobile Menu Toggle
  // =========================================================================
  const mobileToggle = document.getElementById('mobileMenuToggle');
  const navLinks = document.getElementById('navLinks');

  if (mobileToggle && navLinks) {
    mobileToggle.addEventListener('click', () => {
      navLinks.classList.toggle('mobile-active');
    });

    document.querySelectorAll('.nav-link').forEach(link => {
      link.addEventListener('click', () => {
        navLinks.classList.remove('mobile-active');
      });
    });
  }

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


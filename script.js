// Adiba Global - Interactive Scripts

document.addEventListener('DOMContentLoaded', () => {
  // 1. Consultation Modal Logic
  const modal = document.getElementById('consultationModal');
  const openModalBtns = document.querySelectorAll('.js-open-modal');
  const closeModalBtn = document.getElementById('closeModalBtn');
  const modalForm = document.getElementById('consultationForm');
  const successAlert = document.getElementById('modalSuccessAlert');

  function openModal() {
    if (modal) {
      modal.classList.add('active');
      document.body.style.overflow = 'hidden';
    }
  }

  function closeModal() {
    if (modal) {
      modal.classList.remove('active');
      document.body.style.overflow = '';
      if (successAlert) successAlert.style.display = 'none';
      if (modalForm) modalForm.reset();
    }
  }

  openModalBtns.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      openModal();
    });
  });

  if (closeModalBtn) {
    closeModalBtn.addEventListener('click', closeModal);
  }

  if (modal) {
    modal.addEventListener('click', (e) => {
      if (e.target === modal) closeModal();
    });
  }

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal && modal.classList.contains('active')) {
      closeModal();
    }
  });

  if (modalForm) {
    modalForm.addEventListener('submit', (e) => {
      e.preventDefault();
      if (successAlert) {
        successAlert.style.display = 'block';
        successAlert.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      }
      setTimeout(() => {
        closeModal();
      }, 3500);
    });
  }

  // 2. Mobile Menu Toggle
  const mobileToggle = document.getElementById('mobileMenuToggle');
  const navLinks = document.getElementById('navLinks');

  if (mobileToggle && navLinks) {
    mobileToggle.addEventListener('click', () => {
      navLinks.classList.toggle('mobile-active');
    });

    // Close menu when clicking outside or on a link
    document.querySelectorAll('.nav-link').forEach(link => {
      link.addEventListener('click', () => {
        navLinks.classList.remove('mobile-active');
      });
    });
  }

  // 3. Footer Accordion on Mobile
  const footerAccordionBtns = document.querySelectorAll('.footer-accordion-btn');
  footerAccordionBtns.forEach(btn => {
    btn.addEventListener('click', (e) => {
      // Only toggle accordion on mobile
      if (window.innerWidth <= 768) {
        e.preventDefault();
        const col = btn.closest('.footer-accordion');
        if (!col) return;
        const isOpen = col.classList.contains('is-open');

        // Close other accordions
        document.querySelectorAll('.footer-accordion').forEach(item => {
          if (item !== col) {
            item.classList.remove('is-open');
            const otherBtn = item.querySelector('.footer-accordion-btn');
            if (otherBtn) otherBtn.setAttribute('aria-expanded', 'false');
          }
        });

        // Toggle this accordion
        col.classList.toggle('is-open', !isOpen);
        btn.setAttribute('aria-expanded', String(!isOpen));
      }
    });
  });
});

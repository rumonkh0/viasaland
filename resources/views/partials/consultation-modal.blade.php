  <!-- =========================================================================
       UNIVERSAL CONSULTATION MODAL
       ========================================================================= -->
  <div class="modal-overlay" id="consultationModal" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <div class="modal-card">
      <div class="modal-header">
        <h3 id="modalTitle">Get Free Visa Consultation</h3>
        <button class="modal-close-btn" id="closeModalBtn" aria-label="Close modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="modal-success-alert" id="modalSuccessAlert">
          &check; Thank you! Your application has been submitted successfully.
        </div>
        <form id="consultationForm" action="{{ route('applications.storeLead') }}" method="POST">
          @csrf
          <div class="form-group">
            <label for="fullName">Full Name *</label>
            <input type="text" id="fullName" name="name" class="form-control" placeholder="e.g. Md. Tanvir Ahmed" value="{{ Auth::user()->name ?? '' }}" required>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="phone">Phone Number *</label>
              <input type="tel" id="phone" name="phone" class="form-control" placeholder="01XXXXXXXXX" value="{{ Auth::user()->phone ?? '' }}" required>
            </div>
            <div class="form-group">
              <label for="email">Email Address *</label>
              <input type="email" id="email" name="email" class="form-control" placeholder="yourname@gmail.com" value="{{ Auth::user()->email ?? '' }}" required>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="country">Desired Destination</label>
              <select id="country" name="country" class="form-control">
                <option value="India">India</option>
                <option value="UK">United Kingdom (UK)</option>
                <option value="USA">United States (USA)</option>
                <option value="Canada">Canada</option>
                <option value="Australia">Australia</option>
                <option value="Italy">Italy (Schengen)</option>
                <option value="Malaysia">Malaysia</option>
                <option value="Saudi Arabia">Saudi Arabia</option>
                <option value="UAE">United Arab Emirates (UAE)</option>
                <option value="Turkey">Turkey</option>
                <option value="Qatar">Qatar</option>
                <option value="New Zealand">New Zealand</option>
                <option value="Other">Other Country</option>
              </select>
            </div>
            <div class="form-group">
              <label for="visaType">Visa Type</label>
              <select id="visaType" name="visaType" class="form-control">
                <option value="Tourist">Tourist Visa</option>
                <option value="Medical">Medical Visa</option>
                <option value="Double Entry">Double Entry Visa</option>
                <option value="Student">Student Visa</option>
                <option value="Work">Work Visa</option>
                <option value="Business">Business Visa</option>
                <option value="Family">Family / Spouse Visa</option>
                <option value="Umrah">Umrah Visa</option>
                <option value="PR">Permanent Residency / Investor</option>
                <option value="Appeal">Visa Appeal / Refusal Review</option>
              </select>
            </div>
          </div>

          <!-- Dynamic Visa File Requirements Display -->
          <div class="visa-requirements-panel" id="visaRequirementsPanel">
            <div class="vr-header">
              <div class="vr-title">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                Required Visa Documents for <span id="reqCountryName">India </span><span id="reqVisaTypeName">Tourist</span> Visa:
              </div>
              <span class="vr-count-badge" id="reqCountBadge">5 Requirements</span>
            </div>
            <div class="vr-list" id="visaRequirementsList">
              <!-- Populated dynamically via JS -->
            </div>
            <div class="vr-note">
              <span>💡 Dedicated folders will be generated for each required document above in your vault.</span>
            </div>
          </div>

          <!-- Notice: Documents are uploaded in the Vault after creation -->
          <div class="form-group" style="margin: 16px 0;">
            <div style="display: flex; align-items: flex-start; gap: 12px; padding: 14px 16px; background: linear-gradient(135deg, #f0f7ff 0%, #f8fafc 100%); border: 1.5px solid #dbeafe; border-radius: 12px;">
              <div style="width: 32px; height: 32px; border-radius: 8px; background-color: #0066FF; color: #fff; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-6l-2-2H5a2 2 0 0 0-2 2z"></path></svg>
              </div>
              <div style="font-size: 12px; line-height: 1.5; color: #334155;">
                <div style="font-weight: 700; color: #0f172a; margin-bottom: 2px;">Upload Documents in Your Vault</div>
                <div>You need to upload these required documents in your <strong>Document Vault</strong> after creating this application. You will be taken straight to your vault where each requirement has its own dedicated upload folder.</div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="message">Your Query / Background Details</label>
            <textarea id="message" name="message" class="form-control" rows="2" placeholder="Tell us about your background, travel plans, or questions..."></textarea>
          </div>

          @auth
            <button type="submit" class="form-submit-btn">
              Create Application & Open Vault &rarr;
            </button>
          @else
            <button type="submit" class="form-submit-btn">
              Submit Application & Continue to Login &rarr;
            </button>
            <div style="text-align: center; margin-top: 8px; font-size: 11.5px; color: #64748b;">
              Already registered? <a href="{{ route('login') }}" style="color: #0066FF; font-weight: 600;">Log in here</a>
            </div>
          @endauth
        </form>
      </div>
    </div>
  </div>

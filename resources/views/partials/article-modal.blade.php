  <!-- =========================================================================
       ARTICLE READER MODAL (FULL BLOG POSTS)
       ========================================================================= -->
  <div class="modal-overlay" id="articleReaderModal" role="dialog" aria-modal="true" aria-labelledby="readerArticleTitle" style="z-index: 9999;">
    <div class="modal-card modal-large" style="max-width: 860px; max-height: 90vh; overflow-y: auto;">
      <div class="modal-header" style="position: sticky; top: 0; background: #ffffff; z-index: 10; border-bottom: 1px solid #e2e8f0; padding: 18px 24px;">
        <div style="display: flex; align-items: center; gap: 10px;">
          <span class="blog-tag-badge" id="readerTagBadge" style="position: static; font-size: 11px;">STUDENT VISA</span>
          <span id="readerDateMeta" style="font-size: 12px; color: #64748b; font-weight: 500;">September 18, 2026 &bull; 5 min read</span>
        </div>
        <button class="modal-close-btn js-close-reader-modal" aria-label="Close article" style="font-size: 26px; line-height: 1; border: none; background: none; cursor: pointer; color: #64748b;">&times;</button>
      </div>

      <div class="modal-body" style="padding: 24px 28px 32px;">
        <h1 id="readerArticleTitle" style="font-size: 26px; font-weight: 800; color: #0f172a; line-height: 1.35; margin-bottom: 16px;">
          <!-- Title populated by JS -->
        </h1>

        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 22px; padding-bottom: 18px; border-bottom: 1px solid #f1f5f9;">
          <div style="width: 38px; height: 38px; border-radius: 50%; background: #0066ff; color: #ffffff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 15px;">
            AG
          </div>
          <div>
            <div style="font-size: 13.5px; font-weight: 700; color: #1e293b;" id="readerAuthor">Adiba Global Advisory Team</div>
            <div style="font-size: 11.5px; color: #64748b;">Verified Immigration &amp; Visa Counseling Division</div>
          </div>
        </div>

        <div style="width: 100%; height: 280px; border-radius: 12px; overflow: hidden; margin-bottom: 24px; background: #f8fafc; border: 1px solid #e2e8f0;">
          <img id="readerCoverImg" src="" alt="Article Banner" style="width: 100%; height: 100%; object-fit: cover;">
        </div>

        <div id="readerArticleBody" class="article-content" style="font-size: 15px; line-height: 1.8; color: #334155;">
          <!-- Populated dynamically via JS -->
        </div>

        <!-- In-Article Consultation CTA Banner -->
        <div style="margin-top: 36px; padding: 22px 24px; background: linear-gradient(135deg, #f0f7ff 0%, #e0f2fe 100%); border: 1.5px solid #bae6fd; border-radius: 14px; display: flex; flex-direction: column; gap: 14px;">
          <div style="display: flex; align-items: flex-start; gap: 14px;">
            <div style="width: 40px; height: 40px; border-radius: 10px; background: #0066ff; color: #ffffff; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            </div>
            <div>
              <h4 style="margin: 0 0 4px; font-size: 16px; font-weight: 700; color: #0369a1;">Need Personalized Guidance for This Visa?</h4>
              <p style="margin: 0; font-size: 13px; color: #0c4a6e; line-height: 1.5;">Our accredited visa officers evaluate your profile, verify document checklists, and guide you through submission with zero consultation fee.</p>
            </div>
          </div>
          <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <button type="button" id="readerApplyBtn" class="form-submit-btn" style="width: auto; padding: 10px 22px; font-size: 13.5px; border-radius: 8px;">
              Apply For Free Visa Consultation &rarr;
            </button>
            <button type="button" class="js-close-reader-modal" style="padding: 10px 18px; font-size: 13px; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; color: #475569; font-weight: 600; cursor: pointer;">
              Close Article
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

@php
    $ext = strtolower(pathinfo($document->file_name, PATHINFO_EXTENSION));
    $url = route('documents.show', $document);
    $downloadUrl = route('documents.download', $document);
    $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
    $isPdf = $ext === 'pdf';
    $applicant = $document->user;
    $application = $document->application;
@endphp

<style>
    .vp-modal-wrap {
        font-family: inherit;
        color: #e2e8f0;
        display: flex;
        flex-direction: column;
        gap: 0.875rem;
    }
    .vp-toolbar {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 60%, #0f172a 100%);
        border: 1px solid #334155;
        border-radius: 0.875rem;
        padding: 1rem 1.25rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4);
    }
    .vp-title-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .vp-file-icon {
        width: 2.75rem;
        height: 2.75rem;
        border-radius: 0.75rem;
        background: rgba(99, 102, 241, 0.2);
        border: 1px solid rgba(129, 140, 248, 0.35);
        color: #818cf8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        font-weight: bold;
        flex-shrink: 0;
    }
    .vp-file-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #ffffff !important;
        letter-spacing: -0.01em;
        line-height: 1.3;
        word-break: break-all;
    }
    .vp-meta-row {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-top: 0.375rem;
        font-size: 0.75rem;
        color: #94a3b8;
    }
    .vp-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.2rem 0.55rem;
        border-radius: 0.5rem;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    .vp-badge-cat {
        background: rgba(99, 102, 241, 0.2);
        color: #c7d2fe !important;
        border: 1px solid rgba(99, 102, 241, 0.35);
    }
    .vp-badge-size {
        background: rgba(255, 255, 255, 0.08);
        color: #cbd5e1 !important;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .vp-badge-ver {
        background: rgba(255, 255, 255, 0.08);
        color: #e2e8f0 !important;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .vp-badge-approved {
        background: rgba(16, 185, 129, 0.2);
        color: #6ee7b7 !important;
        border: 1px solid rgba(16, 185, 129, 0.4);
    }
    .vp-badge-pending {
        background: rgba(245, 158, 11, 0.2);
        color: #fcd34d !important;
        border: 1px solid rgba(245, 158, 11, 0.4);
    }
    .vp-badge-rejected {
        background: rgba(239, 68, 68, 0.2);
        color: #fca5a5 !important;
        border: 1px solid rgba(239, 68, 68, 0.4);
    }
    .vp-badge-locked {
        background: rgba(244, 63, 94, 0.2);
        color: #fda4af !important;
        border: 1px solid rgba(244, 63, 94, 0.35);
    }
    .vp-actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-shrink: 0;
    }
    .vp-btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: #4f46e5;
        hover-background: #4338ca;
        color: #ffffff !important;
        padding: 0.45rem 0.9rem;
        border-radius: 0.625rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        box-shadow: 0 2px 8px rgba(79, 70, 229, 0.4);
        transition: all 0.15s ease;
    }
    .vp-btn-primary:hover {
        background: #4338ca;
        transform: translateY(-1px);
    }
    .vp-btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: rgba(255, 255, 255, 0.1);
        color: #e2e8f0 !important;
        border: 1px solid rgba(255, 255, 255, 0.15);
        padding: 0.45rem 0.85rem;
        border-radius: 0.625rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.15s ease;
    }
    .vp-btn-secondary:hover {
        background: rgba(255, 255, 255, 0.18);
        color: #ffffff !important;
    }
    .vp-applicant-bar {
        margin-top: 0.75rem;
        padding-top: 0.75rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        font-size: 0.75rem;
        color: #cbd5e1;
        flex-wrap: wrap;
    }
    .vp-canvas-box {
        background: #020617;
        border: 1px solid #1e293b;
        border-radius: 0.875rem;
        overflow: hidden;
        min-height: 680px;
        height: 75vh;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.5);
    }
    .vp-pdf-frame {
        width: 100%;
        height: 100%;
        min-height: 680px;
        border: none;
        display: block;
        background: #ffffff;
    }
    .vp-img-preview {
        max-width: 96%;
        max-height: 70vh;
        object-fit: contain;
        border-radius: 0.5rem;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6);
        border: 1px solid #334155;
        background: #ffffff;
    }
    .vp-non-preview {
        text-align: center;
        padding: 3rem 1.5rem;
        max-width: 28rem;
    }
    .vp-notes-box {
        background: rgba(245, 158, 11, 0.1);
        border: 1px solid rgba(245, 158, 11, 0.3);
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        font-size: 0.75rem;
        color: #fde68a;
    }
</style>

<div class="vp-modal-wrap">
    {{-- Header File Metadata Bar --}}
    <div class="vp-toolbar">
        <div class="vp-title-row">
            <div style="display: flex; align-items: center; gap: 0.875rem; min-width: 0; flex: 1;">
                <div class="vp-file-icon">
                    @if ($isPdf)
                        📄
                    @elseif ($isImage)
                        🖼️
                    @else
                        📁
                    @endif
                </div>
                <div style="min-width: 0; flex: 1;">
                    <div class="vp-file-title">
                        {{ $document->file_name }}
                    </div>
                    <div class="vp-meta-row">
                        <span class="vp-badge vp-badge-cat">
                            📂 {{ $document->categoryName() }}
                        </span>
                        <span class="vp-badge vp-badge-size">
                            {{ $document->fileSizeFormatted() }}
                        </span>
                        <span class="vp-badge vp-badge-ver">
                            v{{ $document->version }}
                        </span>
                        <span class="vp-badge {{ $document->status === 'approved' ? 'vp-badge-approved' : ($document->status === 'rejected' ? 'vp-badge-rejected' : 'vp-badge-pending') }}">
                            @if ($document->status === 'approved')
                                ✓ Approved
                            @elseif ($document->status === 'rejected')
                                ✕ Rejected
                            @else
                                ⏳ Pending Review
                            @endif
                        </span>
                        @if ($document->is_locked)
                            <span class="vp-badge vp-badge-locked">
                                🔒 Locked
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="vp-actions">
                <a href="{{ $url }}" target="_blank" class="vp-btn-primary">
                    <svg style="width: 0.875rem; height: 0.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    <span>Open in New Tab</span>
                </a>
                <a href="{{ $downloadUrl }}" class="vp-btn-secondary">
                    <svg style="width: 0.875rem; height: 0.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <span>Download</span>
                </a>
            </div>
        </div>

        {{-- Applicant and Application Context --}}
        <div class="vp-applicant-bar">
            <div>
                <span style="color: #64748b;">Applicant:</span>
                <strong style="color: #f1f5f9; margin-left: 0.25rem;">{{ $applicant?->name ?? 'Unknown Applicant' }}</strong>
                <span style="color: #64748b; margin-left: 0.25rem;">({{ $applicant?->email }})</span>
            </div>
            @if ($application)
                <div>
                    <span style="color: #64748b;">Target Visa:</span>
                    <strong style="color: #818cf8; margin-left: 0.25rem;">{{ $application->visa_type }} Visa — {{ $application->target_country }}</strong>
                    <span style="color: #64748b; margin-left: 0.25rem;">(Vault #{{ $application->id }})</span>
                </div>
            @endif
        </div>
    </div>

    {{-- File Content Preview Canvas (Expansive full 75vh canvas) --}}
    <div class="vp-canvas-box">
        @if ($isPdf)
            <iframe 
                src="{{ $url }}" 
                class="vp-pdf-frame"
                title="{{ $document->file_name }}"
            ></iframe>
        @elseif ($isImage)
            <img 
                src="{{ $url }}" 
                alt="{{ $document->file_name }}" 
                class="vp-img-preview"
            />
        @else
            <div class="vp-non-preview">
                <div style="width: 4rem; height: 4rem; border-radius: 1rem; background: rgba(99, 102, 241, 0.2); border: 1px solid rgba(99, 102, 241, 0.35); display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1rem;">
                    📄
                </div>
                <h4 style="font-size: 1rem; font-weight: 700; color: #ffffff; margin-bottom: 0.5rem;">
                    {{ $document->file_name }}
                </h4>
                <p style="font-size: 0.75rem; color: #94a3b8; margin-bottom: 1.25rem; line-height: 1.5;">
                    Inline browser viewing is optimized for PDF and image formats (JPG, PNG, WebP). Download the file below to view or edit in your local application.
                </p>
                <a href="{{ $downloadUrl }}" class="vp-btn-primary" style="display: inline-flex; justify-content: center; padding: 0.6rem 1.25rem;">
                    <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <span>Download {{ strtoupper($ext) }} File ({{ $document->fileSizeFormatted() }})</span>
                </a>
            </div>
        @endif
    </div>

    {{-- Admin Notes Callout (if present) --}}
    @if ($document->admin_notes)
        <div class="vp-notes-box">
            <strong style="color: #fbbf24;">Admin Review Notes:</strong>
            <span style="margin-left: 0.35rem;">{{ $document->admin_notes }}</span>
        </div>
    @endif
</div>

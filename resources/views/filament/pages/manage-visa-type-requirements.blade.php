<x-filament-panels::page>
    <style>
        /* Container and page spacing */
        .vtr-page-wrapper {
            display: flex;
            flex-direction: column;
            gap: 2rem !important;
            padding-bottom: 3rem !important;
        }

        /* Step Progress Bar */
        .vtr-step-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.75rem !important;
            padding-bottom: 1.25rem !important;
            margin-bottom: 1.75rem !important;
            border-bottom: 1px solid rgba(229, 231, 235, 0.8);
        }
        .dark .vtr-step-bar {
            border-bottom-color: rgba(255, 255, 255, 0.08);
        }

        /* Toolbar with guaranteed margins and spacing */
        .vtr-toolbar {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            gap: 1.5rem !important;
            margin-top: 0.5rem !important;
            margin-bottom: 2rem !important;
            flex-wrap: wrap !important;
        }
        .vtr-toolbar-count {
            font-size: 0.875rem !important;
            font-weight: 600 !important;
            white-space: nowrap !important;
            color: #6b7280 !important;
        }
        .dark .vtr-toolbar-count {
            color: #9ca3af !important;
        }
        .vtr-toolbar-search {
            width: 22rem !important;
            max-width: 100% !important;
        }

        /* Banner Cards */
        .vtr-banner-card {
            border-radius: 1rem !important;
            padding: 1.75rem 2rem !important;
            margin-bottom: 2rem !important;
            background: #ffffff !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05) !important;
            border: 1px solid rgba(229, 231, 235, 0.8) !important;
        }
        .dark .vtr-banner-card {
            background: #111827 !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3) !important;
        }

        /* Flag Emblem Box - Guarantees Left and Right space */
        .vtr-flag-box {
            width: 3.75rem !important;
            height: 3.75rem !important;
            min-width: 3.75rem !important;
            border-radius: 0.85rem !important;
            background: rgba(245, 158, 11, 0.12) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 2.25rem !important;
            margin-right: 1.5rem !important;
            margin-left: 0.25rem !important;
            flex-shrink: 0 !important;
            user-select: none !important;
            border: 1px solid rgba(245, 158, 11, 0.2) !important;
        }
        .dark .vtr-flag-box {
            background: rgba(245, 158, 11, 0.16) !important;
            border-color: rgba(245, 158, 11, 0.25) !important;
        }

        /* Title + Code Badge Row */
        .vtr-title-row {
            display: flex !important;
            align-items: center !important;
            flex-wrap: wrap !important;
            gap: 0.85rem !important;
        }

        /* Country Code Badge - Always has guaranteed margin and padding */
        .vtr-code-badge {
            margin-left: 0.85rem !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0.25rem 0.85rem !important;
            font-size: 0.75rem !important;
            font-weight: 700 !important;
            letter-spacing: 0.05em !important;
            border-radius: 9999px !important;
            background: #f3f4f6 !important;
            color: #374151 !important;
            border: 1px solid #d1d5db !important;
            line-height: 1.25rem !important;
            vertical-align: middle !important;
        }
        .dark .vtr-code-badge {
            background: rgba(255, 255, 255, 0.08) !important;
            color: #e5e7eb !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
        }

        /* Back Button - Spacious, Never Wraps */
        .vtr-back-btn {
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
            padding: 0.6rem 1.35rem !important;
            font-size: 0.8125rem !important;
            font-weight: 600 !important;
            white-space: nowrap !important;
            border-radius: 0.5rem !important;
            background: #f3f4f6 !important;
            color: #374151 !important;
            transition: all 0.15s ease !important;
            border: 1px solid rgba(209, 213, 219, 0.7) !important;
            flex-shrink: 0 !important;
            cursor: pointer !important;
            margin-right: 0.5rem !important;
        }
        .vtr-back-btn:hover {
            background: #e5e7eb !important;
            color: #111827 !important;
        }
        .dark .vtr-back-btn {
            background: rgba(255, 255, 255, 0.08) !important;
            color: #e5e7eb !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
        }
        .dark .vtr-back-btn:hover {
            background: rgba(255, 255, 255, 0.14) !important;
            color: #ffffff !important;
        }

        /* Spacious Pill Badges - Fixes tight text issue */
        .vtr-pill {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 0.45rem !important;
            padding: 0.35rem 0.9rem !important;
            border-radius: 9999px !important;
            font-size: 0.75rem !important;
            line-height: 1.15rem !important;
            font-weight: 600 !important;
            letter-spacing: 0.02em !important;
            white-space: nowrap !important;
            border: 1px solid transparent !important;
            margin-right: 0.25rem !important;
        }
        .vtr-pill-sm {
            padding: 0.3rem 0.8rem !important;
            font-size: 0.75rem !important;
            font-weight: 500 !important;
        }
        .vtr-pill-gray {
            background: #f3f4f6 !important;
            color: #374151 !important;
            border-color: #e5e7eb !important;
        }
        .dark .vtr-pill-gray {
            background: rgba(255, 255, 255, 0.08) !important;
            color: #d1d5db !important;
            border-color: rgba(255, 255, 255, 0.12) !important;
        }
        .vtr-pill-primary {
            background: rgba(245, 158, 11, 0.12) !important;
            color: #b45309 !important;
            border-color: rgba(245, 158, 11, 0.25) !important;
        }
        .dark .vtr-pill-primary {
            background: rgba(245, 158, 11, 0.18) !important;
            color: #fbbf24 !important;
            border-color: rgba(245, 158, 11, 0.3) !important;
        }
        .vtr-pill-danger {
            background: rgba(239, 68, 68, 0.1) !important;
            color: #b91c1c !important;
            border-color: rgba(239, 68, 68, 0.25) !important;
        }
        .dark .vtr-pill-danger {
            background: rgba(239, 68, 68, 0.18) !important;
            color: #f87171 !important;
            border-color: rgba(239, 68, 68, 0.3) !important;
        }
        .vtr-pill-success {
            background: rgba(16, 185, 129, 0.1) !important;
            color: #047857 !important;
            border-color: rgba(16, 185, 129, 0.25) !important;
        }
        .dark .vtr-pill-success {
            background: rgba(16, 185, 129, 0.18) !important;
            color: #34d399 !important;
            border-color: rgba(16, 185, 129, 0.3) !important;
        }
        .vtr-pill-info {
            background: rgba(59, 130, 246, 0.1) !important;
            color: #1d4ed8 !important;
            border-color: rgba(59, 130, 246, 0.25) !important;
        }
        .dark .vtr-pill-info {
            background: rgba(59, 130, 246, 0.18) !important;
            color: #60a5fa !important;
            border-color: rgba(59, 130, 246, 0.3) !important;
        }

        /* Grids with guaranteed row and column gaps */
        .vtr-grid-cards {
            display: grid !important;
            grid-template-columns: repeat(auto-fill, minmax(290px, 1fr)) !important;
            gap: 1.75rem !important;
            row-gap: 2rem !important;
            column-gap: 1.75rem !important;
        }
        .vtr-grid-cards-lg {
            display: grid !important;
            grid-template-columns: repeat(auto-fill, minmax(330px, 1fr)) !important;
            gap: 1.75rem !important;
            row-gap: 2rem !important;
            column-gap: 1.75rem !important;
        }

        /* Card container */
        .vtr-card {
            border-radius: 1rem !important;
            padding: 1.6rem !important;
            background: #ffffff !important;
            border: 1px solid rgba(229, 231, 235, 0.8) !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05) !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            min-height: 220px !important;
            transition: all 0.2s ease !important;
        }
        .vtr-card:hover {
            box-shadow: 0 6px 12px -2px rgba(0, 0, 0, 0.08) !important;
            border-color: rgba(245, 158, 11, 0.5) !important;
        }
        .dark .vtr-card {
            background: #111827 !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3) !important;
        }
        .dark .vtr-card:hover {
            border-color: rgba(245, 158, 11, 0.5) !important;
            box-shadow: 0 8px 16px -2px rgba(0, 0, 0, 0.4) !important;
        }

        /* Card footer button container */
        .vtr-card-footer {
            margin-top: 1.5rem !important;
            padding-top: 1.25rem !important;
            border-top: 1px solid rgba(229, 231, 235, 0.6) !important;
        }
        .dark .vtr-card-footer {
            border-top-color: rgba(255, 255, 255, 0.08) !important;
        }

        /* Document pills list inside card */
        .vtr-tags-wrap {
            display: flex !important;
            flex-wrap: wrap !important;
            gap: 0.6rem !important;
            margin-top: 0.85rem !important;
        }

        /* Level 3 Split layout */
        .vtr-split-layout {
            display: grid !important;
            grid-template-columns: repeat(12, minmax(0, 1fr)) !important;
            gap: 2rem !important;
            row-gap: 2rem !important;
            column-gap: 2rem !important;
        }
        .vtr-col-left {
            grid-column: span 12 / span 12 !important;
        }
        .vtr-col-right {
            grid-column: span 12 / span 12 !important;
        }
        @media (min-width: 1024px) {
            .vtr-col-left {
                grid-column: span 7 / span 7 !important;
            }
            .vtr-col-right {
                grid-column: span 5 / span 5 !important;
            }
        }
    </style>

    <div class="vtr-page-wrapper">
        {{-- Elegant Step Progress Pill Bar --}}
        <div class="vtr-step-bar">
            <button 
                type="button" 
                wire:click="backToCountries" 
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg transition text-xs font-semibold {{ ! $country_id ? 'bg-primary-50 text-primary-600 dark:bg-primary-950/60 dark:text-primary-400 ring-1 ring-primary-500/20' : 'text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white' }}"
            >
                <x-filament::icon icon="heroicon-m-globe-alt" class="h-4 w-4" />
                <span>1. Countries ({{ $this->countries->count() }})</span>
            </button>

            <x-filament::icon icon="heroicon-m-chevron-right" class="h-4 w-4 text-gray-400 dark:text-gray-600" />

            <button 
                type="button" 
                @if ($this->selectedCountry) wire:click="backToVisaTypes" @endif
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg transition text-xs font-semibold {{ $country_id && ! $visa_type_id ? 'bg-primary-50 text-primary-600 dark:bg-primary-950/60 dark:text-primary-400 ring-1 ring-primary-500/20' : ($this->selectedCountry ? 'text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white' : 'text-gray-400 dark:text-gray-600 cursor-not-allowed') }}"
            >
                <x-filament::icon icon="heroicon-m-briefcase" class="h-4 w-4" />
                <span>2. Visa Types @if($this->selectedCountry) ({{ $this->selectedCountry->name }}) @endif</span>
            </button>

            <x-filament::icon icon="heroicon-m-chevron-right" class="h-4 w-4 text-gray-400 dark:text-gray-600" />

            <span class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-xs font-semibold {{ $country_id && $visa_type_id ? 'bg-primary-50 text-primary-600 dark:bg-primary-950/60 dark:text-primary-400 ring-1 ring-primary-500/20' : 'text-gray-400 dark:text-gray-600' }}">
                <x-filament::icon icon="heroicon-m-clipboard-document-check" class="h-4 w-4" />
                <span>3. Required Documents @if($this->selectedVisaType) ({{ $this->selectedVisaType->name }}) @endif</span>
            </span>
        </div>

        {{-- =============================================================== --}}
        {{-- LEVEL 1: COUNTRIES LIST                                         --}}
        {{-- =============================================================== --}}
        @if (! $country_id)
            <div>
                {{-- Toolbar with Search --}}
                <div class="vtr-toolbar">
                    <div class="vtr-toolbar-count">
                        {{ $this->countries->count() }} {{ Str::plural('country', $this->countries->count()) }} configured
                    </div>

                    <div class="vtr-toolbar-search">
                        <x-filament::input.wrapper prefix-icon="heroicon-m-magnifying-glass">
                            <x-filament::input 
                                type="text" 
                                wire:model.live.debounce.300ms="countrySearch" 
                                placeholder="Search countries..." 
                            />
                        </x-filament::input.wrapper>
                    </div>
                </div>

                {{-- Countries Grid --}}
                <div class="vtr-grid-cards">
                    @forelse ($this->countries as $country)
                        <div 
                            wire:key="country-card-{{ $country->id }}"
                            class="vtr-card"
                        >
                            <div>
                                <div class="flex items-start justify-between gap-2 mb-4">
                                    <div class="flex items-center">
                                        <div class="vtr-flag-box" style="margin-left: 0; margin-right: 1.15rem; width: 3.5rem; height: 3.5rem; min-width: 3.5rem;">
                                            <span>{{ $country->flag ?: '📍' }}</span>
                                        </div>
                                        <div>
                                            <div class="vtr-title-row">
                                                <h4 class="font-bold text-base text-gray-950 dark:text-white">
                                                    {{ $country->name }}
                                                </h4>
                                                @if ($country->code)
                                                    <span class="vtr-code-badge">
                                                        {{ $country->code }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <x-filament::icon-button 
                                        wire:click="deleteCountry({{ $country->id }})" 
                                        wire:confirm="Are you sure you want to delete {{ $country->name }} and all its configured visa types?" 
                                        icon="heroicon-m-trash" 
                                        color="danger" 
                                        size="sm" 
                                        tooltip="Delete Country" 
                                    />
                                </div>

                                <div class="flex flex-wrap items-center gap-2 pt-2">
                                    <span class="vtr-pill vtr-pill-primary">
                                        <x-filament::icon icon="heroicon-m-briefcase" class="w-3.5 h-3.5" />
                                        <span>{{ $country->visa_types_count }} Visa Types</span>
                                    </span>
                                    <span class="vtr-pill vtr-pill-gray">
                                        <x-filament::icon icon="heroicon-m-document-text" class="w-3.5 h-3.5" />
                                        <span>{{ $country->requirements->count() }} Rules</span>
                                    </span>
                                </div>
                            </div>

                            <div class="vtr-card-footer">
                                <x-filament::button 
                                    wire:click="selectCountry({{ $country->id }})" 
                                    color="primary" 
                                    size="md" 
                                    icon="heroicon-m-arrow-right" 
                                    icon-position="after" 
                                    class="w-full"
                                >
                                    Manage Visa Types
                                </x-filament::button>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full rounded-xl bg-white p-12 text-center shadow-sm border border-gray-200 dark:border-white/10 dark:bg-gray-900">
                            <div class="text-3xl mb-2">🔍</div>
                            <h4 class="font-bold text-sm text-gray-950 dark:text-white">No countries found</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                No countries match "{{ $countrySearch }}".
                            </p>
                            <x-filament::button wire:click="$set('countrySearch', '')" color="gray" size="xs" class="mt-4">
                                Clear search
                            </x-filament::button>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif

        {{-- =============================================================== --}}
        {{-- LEVEL 2: VISA TYPES LIST FOR SELECTED COUNTRY                  --}}
        {{-- =============================================================== --}}
        @if ($country_id && ! $visa_type_id)
            <div>
                {{-- Selected Country Info Banner --}}
                <div class="vtr-banner-card">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-5">
                        {{-- Left Side: Flag Emblem + Country Info with generous padding and space --}}
                        <div class="flex items-center">
                            <div class="vtr-flag-box">
                                <span>{{ $this->selectedCountry->flag ?: '📍' }}</span>
                            </div>
                            <div>
                                <div class="vtr-title-row">
                                    <h2 class="text-xl font-bold tracking-tight text-gray-950 dark:text-white">
                                        {{ $this->selectedCountry->name }}
                                    </h2>
                                    @if ($this->selectedCountry->code)
                                        <span class="vtr-code-badge">
                                            {{ $this->selectedCountry->code }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Right Side: Back Button & Search Input with clean breathing room --}}
                        <div class="flex items-center gap-3 w-full md:w-auto">
                            <button 
                                type="button" 
                                wire:click="backToCountries" 
                                class="vtr-back-btn"
                            >
                                <x-filament::icon icon="heroicon-m-arrow-left" class="w-4 h-4" />
                                <span>Back to Countries</span>
                            </button>

                            <div class="w-full sm:w-64">
                                <x-filament::input.wrapper prefix-icon="heroicon-m-magnifying-glass">
                                    <x-filament::input 
                                        type="text" 
                                        wire:model.live.debounce.300ms="visaTypeSearch" 
                                        placeholder="Filter visa types..." 
                                    />
                                </x-filament::input.wrapper>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Visa Types Grid --}}
                <div class="vtr-grid-cards-lg">
                    @forelse ($this->countryVisaTypes as $visaType)
                        @php
                            $reqCount = $visaType->requirements->count();
                            $mandatoryCount = $visaType->requirements->where('is_mandatory', true)->count();
                            $optionalCount = $visaType->requirements->where('is_mandatory', false)->count();
                        @endphp
                        <div 
                            wire:key="visa-type-card-{{ $visaType->id }}"
                            class="vtr-card"
                        >
                            <div>
                                <div class="flex items-start justify-between gap-2 mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-primary-50 dark:bg-primary-950/60 flex items-center justify-center text-primary-600 dark:text-primary-400">
                                            <x-filament::icon icon="heroicon-m-identification" class="w-5 h-5" />
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-base text-gray-950 dark:text-white">
                                                {{ $visaType->display_name }}
                                            </h4>
                                        </div>
                                    </div>

                                    <x-filament::icon-button 
                                        wire:click="deleteVisaType({{ $visaType->id }})" 
                                        wire:confirm="Are you sure you want to delete '{{ $visaType->display_name }}' and its requirements?" 
                                        icon="heroicon-m-trash" 
                                        color="danger" 
                                        size="sm" 
                                        tooltip="Delete Visa Type" 
                                    />
                                </div>

                                {{-- Badges Summary --}}
                                <div class="flex flex-wrap items-center gap-2 mb-3">
                                    <span class="vtr-pill vtr-pill-primary">
                                        {{ $reqCount }} Required {{ Str::plural('Document', $reqCount) }}
                                    </span>
                                    @if ($mandatoryCount > 0)
                                        <span class="vtr-pill vtr-pill-danger">
                                            {{ $mandatoryCount }} Mandatory
                                        </span>
                                    @endif
                                    @if ($optionalCount > 0)
                                        <span class="vtr-pill vtr-pill-gray">
                                            {{ $optionalCount }} Optional
                                        </span>
                                    @endif
                                </div>

                                {{-- Configured Document Pills --}}
                                <div class="vtr-tags-wrap pt-2 border-t border-gray-100 dark:border-white/5">
                                    @forelse ($visaType->requirements->take(5) as $req)
                                        <span class="vtr-pill vtr-pill-sm {{ $req->is_mandatory ? 'vtr-pill-gray' : 'vtr-pill-info' }}">
                                            {{ $req->documentCategory?->name }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-gray-400 italic py-1">No document requirements linked yet.</span>
                                    @endforelse
                                    @if ($reqCount > 5)
                                        <span class="vtr-pill vtr-pill-sm vtr-pill-gray">
                                            +{{ $reqCount - 5 }} more
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="vtr-card-footer">
                                <x-filament::button 
                                    wire:click="selectVisaType({{ $visaType->id }})" 
                                    color="primary" 
                                    size="md" 
                                    icon="heroicon-m-adjustments-horizontal" 
                                    icon-position="after" 
                                    class="w-full"
                                >
                                    Configure Requirements
                                </x-filament::button>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full rounded-xl bg-white p-12 text-center shadow-sm border border-gray-200 dark:border-white/10 dark:bg-gray-900">
                            <div class="text-3xl mb-2">📄</div>
                            <h4 class="font-bold text-sm text-gray-950 dark:text-white">No visa categories defined</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-sm mx-auto">
                                {{ $this->selectedCountry->name }} has no visa categories yet. Click "Add Visa Type" in the top header.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif

        {{-- =============================================================== --}}
        {{-- LEVEL 3: DOCUMENT REQUIREMENTS SELECTION FOR SELECTED VISA TYPE --}}
        {{-- =============================================================== --}}
        @if ($country_id && $visa_type_id && $this->selectedVisaType)
            <div>
                {{-- Level 3 Top Context Banner --}}
                <div class="vtr-banner-card">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-5">
                        <div class="flex items-center">
                            <div class="vtr-flag-box">
                                <span>{{ $this->selectedCountry->flag ?: '📍' }}</span>
                            </div>
                            <div>
                                <div class="vtr-title-row">
                                    <h2 class="text-xl font-bold tracking-tight text-gray-950 dark:text-white">
                                        {{ $this->selectedCountry->name }} &bull; {{ $this->selectedVisaType->display_name }}
                                    </h2>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <button 
                                type="button" 
                                wire:click="backToVisaTypes" 
                                class="vtr-back-btn"
                            >
                                <x-filament::icon icon="heroicon-m-arrow-left" class="w-4 h-4" />
                                <span>Back to Visa Categories</span>
                            </button>

                            <x-filament::button 
                                wire:click="applyCoreRequirements" 
                                color="primary" 
                                size="md" 
                                icon="heroicon-m-bolt"
                            >
                                Add Core 4
                            </x-filament::button>
                        </div>
                    </div>
                </div>

                {{-- Two-Column Layout for Active vs Catalog Documents --}}
                <div class="vtr-split-layout">
                    {{-- Left Column: Configured Active Requirements (7 cols) --}}
                    <div class="vtr-col-left">
                        <x-filament::section>
                            <x-slot name="heading">
                                Active Required Documents ({{ $this->selectedVisaType->requirements->count() }})
                            </x-slot>
                            <x-slot name="description">
                                Applicants applying for {{ $this->selectedVisaType->display_name }} must upload these files.
                            </x-slot>

                            <x-slot name="headerEnd">
                                @if ($this->selectedVisaType->requirements->count() > 0)
                                    <x-filament::button 
                                        wire:click="clearAllRequirements" 
                                        wire:confirm="Remove all required documents from {{ $this->selectedVisaType->display_name }}?" 
                                        color="danger" 
                                        size="xs" 
                                        icon="heroicon-m-trash"
                                    >
                                        Clear All
                                    </x-filament::button>
                                @endif
                            </x-slot>

                            @if ($this->selectedVisaType->requirements->isEmpty())
                                <div class="py-12 text-center">
                                    <div class="text-3xl mb-2">📂</div>
                                    <h4 class="font-bold text-sm text-gray-950 dark:text-white">No requirements configured</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-xs mx-auto">
                                        Select document categories from the right catalog, or click below to populate the 4 standard core documents.
                                    </p>
                                    <x-filament::button wire:click="applyCoreRequirements" color="primary" size="md" icon="heroicon-m-bolt" class="mt-4">
                                        Apply Core 4 Documents
                                    </x-filament::button>
                                </div>
                            @else
                                <div class="divide-y divide-gray-100 dark:divide-white/5 -my-2">
                                    @foreach ($this->selectedVisaType->requirements as $req)
                                        <div wire:key="req-row-{{ $req->id }}" class="py-3.5 flex items-center justify-between gap-3">
                                            <div class="flex items-center gap-3.5">
                                                <div class="w-8 h-8 rounded-xl flex items-center justify-center font-bold text-xs {{ $req->is_mandatory ? 'bg-primary-500 text-white' : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300' }}">
                                                    ✓
                                                </div>
                                                <div>
                                                    <div class="font-semibold text-sm text-gray-950 dark:text-white">
                                                        {{ $req->documentCategory?->name ?? 'Category #'.$req->document_category_id }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-2.5">
                                                {{-- Toggle Mandatory/Optional Badge with generous space --}}
                                                <button 
                                                    type="button"
                                                    wire:click="toggleMandatory({{ $req->id }})" 
                                                    title="Click to toggle Mandatory vs Optional"
                                                    class="vtr-pill {{ $req->is_mandatory ? 'vtr-pill-danger' : 'vtr-pill-gray' }} cursor-pointer hover:opacity-85 transition"
                                                >
                                                    <span class="w-1.5 h-1.5 rounded-full {{ $req->is_mandatory ? 'bg-red-500' : 'bg-gray-400' }}"></span>
                                                    <span>{{ $req->is_mandatory ? 'Mandatory' : 'Optional' }}</span>
                                                </button>

                                                {{-- Remove Button --}}
                                                <x-filament::icon-button 
                                                    wire:click="toggleRequirement({{ $req->document_category_id }})" 
                                                    icon="heroicon-m-x-mark" 
                                                    color="danger" 
                                                    size="sm" 
                                                    tooltip="Remove requirement" 
                                                />
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </x-filament::section>
                    </div>

                    {{-- Right Column: Category Catalog (5 cols) --}}
                    <div class="vtr-col-right">
                        <x-filament::section>
                            <x-slot name="heading">
                                Document Catalog
                            </x-slot>
                            <x-slot name="description">
                                Click any category to add or remove it from this visa type.
                            </x-slot>

                            <div class="space-y-4">
                                <x-filament::input.wrapper prefix-icon="heroicon-m-magnifying-glass">
                                    <x-filament::input 
                                        type="text" 
                                        wire:model.live.debounce.300ms="categorySearch" 
                                        placeholder="Filter categories..." 
                                    />
                                </x-filament::input.wrapper>

                                @php
                                    $activeCategoryIds = $this->selectedVisaType->requirements->pluck('document_category_id')->toArray();
                                @endphp

                                <div class="divide-y divide-gray-100 dark:divide-white/5 max-h-[460px] overflow-y-auto pr-1">
                                    @forelse ($this->allCategories as $category)
                                        @php
                                            $isIncluded = in_array($category->id, $activeCategoryIds);
                                        @endphp
                                        <div 
                                            wire:key="cat-row-{{ $category->id }}"
                                            wire:click="toggleRequirement({{ $category->id }})" 
                                            class="py-3 px-2.5 flex items-center justify-between cursor-pointer rounded-lg hover:bg-gray-50 dark:hover:bg-white/5 transition"
                                        >
                                            <span class="text-xs font-semibold {{ $isIncluded ? 'text-primary-600 dark:text-primary-400' : 'text-gray-800 dark:text-gray-200' }}">
                                                {{ $category->name }}
                                            </span>

                                            <div>
                                                @if ($isIncluded)
                                                    <span class="vtr-pill vtr-pill-sm vtr-pill-success">
                                                        <x-filament::icon icon="heroicon-m-check" class="w-3 h-3" />
                                                        <span>Included</span>
                                                    </span>
                                                @else
                                                    <span class="text-xs font-semibold text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 px-2 py-1">
                                                        + Add
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="py-4 text-center text-xs text-gray-400">
                                            No categories match your search.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </x-filament::section>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>

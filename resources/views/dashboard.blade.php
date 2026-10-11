<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    {{ __('Visa Applications & Document Vault') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Centralized visa portal for <span class="font-semibold text-gray-800">{{ auth()->user()->name }}</span> ({{ auth()->user()->email }})
                </p>
            </div>
            <div>
                <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-new-app-modal'))" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Apply for New Visa</span>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen" 
         x-data="{ 
             activeTab: '{{ request('tab', $applications->count() > 1 ? 'applications' : 'vault') }}',
             newAppModalOpen: false, 
             newAppVisaType: 'Tourist', 
             newAppCountry: '{{ $countries->first()?->name ?? 'India' }}', 
             selectedAppId: {{ request('app') ?? $applications->first()?->id ?? 'null' }},
             previewOpen: false,
             previewUrl: '',
             previewName: '',
             previewExt: '',
             countriesData: {{ json_encode($countries->map(function ($c) {
                 return [
                     'name' => $c->name,
                     'flag' => $c->flag ?: '📍',
                     'code' => $c->code,
                     'visaTypes' => $c->visaTypes->map(fn ($vt) => [
                         'name' => $vt->name,
                         'display_name' => $vt->display_name ?: $vt->name . ' Visa',
                         'requirements' => $vt->requirements->map(fn ($r) => [
                             'name' => $r->documentCategory?->name,
                             'is_mandatory' => (bool) $r->is_mandatory,
                         ])->values(),
                     ])->values(),
                 ];
             })->values()) }},
             getAvailableVisaTypes() {
                 let c = this.countriesData.find(item => item.name === this.newAppCountry);
                 if (c && c.visaTypes && c.visaTypes.length > 0) {
                     return c.visaTypes;
                 }
                 return [
                     { name: 'Tourist', display_name: 'Tourist Visa', requirements: [] },
                     { name: 'Business', display_name: 'Business Visa', requirements: [] },
                     { name: 'Student', display_name: 'Student Visa', requirements: [] },
                     { name: 'Work', display_name: 'Work Visa', requirements: [] },
                 ];
             },
             onCountryChange() {
                 let types = this.getAvailableVisaTypes();
                 if (types.length > 0 && !types.some(t => t.name === this.newAppVisaType)) {
                     this.newAppVisaType = types[0].name;
                 }
             },
             getModalReqs() {
                 let c = this.countriesData.find(item => item.name === this.newAppCountry);
                 if (c && c.visaTypes) {
                     let vt = c.visaTypes.find(item => item.name === this.newAppVisaType);
                     if (vt && vt.requirements && vt.requirements.length > 0) {
                         return vt.requirements;
                     }
                 }
                 return [
                     { name: 'Passport', is_mandatory: true },
                     { name: 'Photograph', is_mandatory: true },
                     { name: 'Bank Statement', is_mandatory: true },
                     { name: 'Visa Application Form', is_mandatory: true }
                 ];
             }
         }" 
         @open-preview-modal.window="previewOpen = true; previewUrl = $event.detail.url; previewName = $event.detail.name; previewExt = $event.detail.ext;"
         @open-new-app-modal.window="newAppModalOpen = true">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
            @if(session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-lg shadow-sm">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-rose-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>
                            <p class="text-sm font-semibold text-rose-800">Please review the following errors:</p>
                            <ul class="mt-1 list-disc list-inside text-xs text-rose-700 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Global Stats Bar -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-bold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Applications</p>
                        <p class="text-2xl font-extrabold text-gray-900">{{ $applications->count() }} Active</p>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-sky-50 text-sky-600 rounded-xl flex items-center justify-center font-bold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Vault Files</p>
                        <p class="text-2xl font-extrabold text-gray-900">{{ $applications->sum(fn($app) => $app->documents->count()) }} Files</p>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center font-bold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Locked / Protected</p>
                        <p class="text-2xl font-extrabold text-gray-900">{{ $applications->sum(fn($app) => $app->documents->where('is_locked', true)->count()) }} Files</p>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            @if($applications->isEmpty())
                <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center shadow-sm">
                    <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">No Visa Applications Found</h3>
                    <p class="text-sm text-gray-500 max-w-md mx-auto mb-6">You don't have any active visa applications yet. Apply now to unlock your personalized document checklist and secure vault.</p>
                    <div class="flex items-center justify-center gap-3">
                        <button type="button" @click="newAppModalOpen = true" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-sm transition">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Apply for Visa & Open Vault
                        </button>
                        <a href="{{ url('/') }}" class="inline-flex items-center px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">
                            Browse Programs
                        </a>
                    </div>
                </div>
            @else
                {{-- Navigation Tabs: My Applications vs Vault View --}}
                <div class="flex items-center border-b border-gray-200 pb-3">
                    <div class="flex items-center gap-2">
                        <button type="button" 
                                @click="activeTab = 'applications'"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition"
                                :class="activeTab === 'applications' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            <span>My Applications ({{ $applications->count() }})</span>
                        </button>

                        <button type="button" 
                                @click="activeTab = 'vault'"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition"
                                :class="activeTab === 'vault' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                            <span>Document Vault & Checklist</span>
                        </button>
                    </div>
                </div>

                {{-- TAB 1: ALL APPLICATIONS DIRECTORY --}}
                <div x-show="activeTab === 'applications'" class="space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                        <div class="mb-5">
                            <h3 class="font-bold text-lg text-gray-900 flex items-center gap-2">
                                <span>My Visa Applications</span>
                                <span class="px-2.5 py-0.5 text-xs bg-indigo-100 text-indigo-700 rounded-full font-bold">{{ $applications->count() }}</span>
                            </h3>
                            <p class="text-xs text-gray-500 mt-0.5">
                                Review your visa applications below. Click "Open Vault" on any application to review checklist requirements and deposit documents.
                            </p>
                        </div>

                        <!-- Desktop Table View -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="border-b border-gray-200 text-gray-400 uppercase tracking-wider font-semibold text-[11px] bg-slate-50/70">
                                        <th class="py-3 px-4">Vault ID</th>
                                        <th class="py-3 px-4">Destination & Category</th>
                                        <th class="py-3 px-4">Status</th>
                                        <th class="py-3 px-4">Document Progress</th>
                                        <th class="py-3 px-4">Vault Files</th>
                                        <th class="py-3 px-4">Applied Date</th>
                                        <th class="py-3 px-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-gray-700">
                                    @foreach($applications as $app)
                                        @php
                                            $countryFlag = \App\Models\Country::where('name', $app->target_country)->value('flag') ?: '📍';
                                            $completion = $app->completionPercentage();
                                        @endphp
                                        <tr class="hover:bg-slate-50/70 transition">
                                            <td class="py-3.5 px-4 font-mono font-bold text-gray-900">
                                                #{{ $app->id }}
                                            </td>
                                            <td class="py-3.5 px-4">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-xl">{{ $countryFlag }}</span>
                                                    <div>
                                                        <span class="font-bold text-gray-900 block">{{ $app->visa_type }} Visa</span>
                                                        <span class="text-gray-500 text-[11px]">{{ $app->target_country }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3.5 px-4">
                                                <span class="px-2.5 py-1 text-[10px] font-bold rounded-full uppercase tracking-wider
                                                    {{ $app->status === 'approved' ? 'bg-emerald-100 text-emerald-800' :
                                                       ($app->status === 'rejected' ? 'bg-rose-100 text-rose-800' :
                                                       'bg-sky-100 text-sky-800') }}">
                                                    {{ str_replace('_', ' ', $app->status) }}
                                                </span>
                                            </td>
                                            <td class="py-3.5 px-4">
                                                <div class="w-36">
                                                    <div class="flex items-center justify-between text-[11px] text-gray-500 mb-1">
                                                        <span>{{ $completion }}% fulfilled</span>
                                                        <span>{{ $app->requirements->where('is_fulfilled', true)->count() }}/{{ $app->requirements->where('is_mandatory', true)->count() }}</span>
                                                    </div>
                                                    <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                                                        <div class="h-1.5 rounded-full {{ $completion >= 100 ? 'bg-emerald-500' : 'bg-indigo-600' }}" style="width: {{ $completion }}%"></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3.5 px-4 font-semibold text-gray-900">
                                                {{ $app->documents->count() }} file(s)
                                            </td>
                                            <td class="py-3.5 px-4 text-gray-500">
                                                {{ $app->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="py-3.5 px-4 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <button type="button" 
                                                            @click="selectedAppId = {{ $app->id }}; activeTab = 'vault'"
                                                            class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-2xs transition">
                                                        <span>Open Vault &rarr;</span>
                                                    </button>
                                                    @if($app->documents->isNotEmpty())
                                                        <a href="{{ route('applications.downloadZip', $app) }}" class="p-1.5 text-gray-500 hover:text-indigo-600 hover:bg-gray-100 rounded-lg transition" title="Download All (ZIP)">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Cards View -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 md:hidden">
                            @foreach($applications as $app)
                                @php
                                    $countryFlag = \App\Models\Country::where('name', $app->target_country)->value('flag') ?: '📍';
                                @endphp
                                <div class="p-4 rounded-xl border border-gray-200 bg-white shadow-2xs flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-start justify-between gap-2 mb-2">
                                            <div class="flex items-center gap-2">
                                                <span class="text-2xl">{{ $countryFlag }}</span>
                                                <div>
                                                    <h4 class="font-bold text-sm text-gray-900">{{ $app->visa_type }} Visa</h4>
                                                    <p class="text-xs text-gray-500">{{ $app->target_country }} &bull; Vault #{{ $app->id }}</p>
                                                </div>
                                            </div>
                                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full uppercase
                                                {{ $app->status === 'approved' ? 'bg-emerald-100 text-emerald-800' :
                                                   ($app->status === 'rejected' ? 'bg-rose-100 text-rose-800' :
                                                   'bg-sky-100 text-sky-800') }}">
                                                {{ $app->status }}
                                            </span>
                                        </div>

                                        <div class="mt-2 text-xs text-gray-500 flex justify-between">
                                            <span>{{ $app->documents->count() }} file(s) in vault</span>
                                            <span class="font-bold {{ $app->completionPercentage() >= 100 ? 'text-emerald-600' : 'text-indigo-600' }}">{{ $app->completionPercentage() }}% complete</span>
                                        </div>
                                    </div>

                                    <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between gap-2">
                                        <button type="button" 
                                                @click="selectedAppId = {{ $app->id }}; activeTab = 'vault'"
                                                class="flex-1 text-center py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-2xs transition">
                                            Open Vault &rarr;
                                        </button>
                                        @if($app->documents->isNotEmpty())
                                            <a href="{{ route('applications.downloadZip', $app) }}" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-gray-100 rounded-lg border border-gray-200 transition" title="Download ZIP">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- TAB 2: DOCUMENT VAULT & CHECKLIST VIEW --}}
                <div x-show="activeTab === 'vault'" class="space-y-6">

                    <!-- Vault Selector Toolbar -->
                    <div class="bg-white rounded-2xl border border-gray-200 p-4 shadow-sm flex items-center justify-between gap-3 flex-wrap">
                        <div class="flex items-center gap-3 flex-wrap">
                            <button type="button" 
                                    @click="activeTab = 'applications'" 
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                <span>&larr; All Applications</span>
                            </button>

                            <span class="text-gray-300">|</span>

                            <div class="flex items-center gap-2">
                                <label class="text-xs font-bold text-gray-600 uppercase tracking-wider">Active Vault:</label>
                                <select x-model="selectedAppId" class="rounded-xl border-gray-300 text-xs font-bold py-1.5 pr-8 pl-3 focus:border-indigo-500 focus:ring-indigo-500 shadow-2xs">
                                    @foreach($applications as $app)
                                        @php
                                            $countryFlag = \App\Models\Country::where('name', $app->target_country)->value('flag') ?: '📍';
                                        @endphp
                                        <option value="{{ $app->id }}">
                                            {{ $countryFlag }} {{ $app->visa_type }} Visa — {{ $app->target_country }} (Vault #{{ $app->id }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-8">
                    @foreach($applications as $app)
                        <div x-show="selectedAppId == {{ $app->id }}" x-cloak class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden" x-data="{ uploadOpen: false, activeReplaceId: null, selectedCat: '', customCatInput: '', folderTargetName: '' }">
                            
                            <!-- Application Vault Header -->
                            <div class="p-6 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center text-indigo-400 border border-white/10">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-3 flex-wrap">
                                                <h3 class="text-xl font-bold text-white tracking-tight">
                                                    {{ $app->visa_type }} Visa — {{ $app->target_country }}
                                                </h3>
                                                <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full uppercase tracking-wider
                                                    {{ $app->status === 'approved' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 
                                                       ($app->status === 'rejected' ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30' : 
                                                       'bg-sky-500/20 text-sky-300 border border-sky-500/30') }}">
                                                    {{ str_replace('_', ' ', $app->status) }}
                                                </span>
                                            </div>
                                            
                                            <!-- Client Email & Vault Details -->
                                            <div class="flex items-center gap-2 mt-1.5 text-xs text-gray-300 flex-wrap">
                                                <span class="inline-flex items-center gap-1 font-semibold text-sky-300 bg-sky-950/60 px-2 py-0.5 rounded border border-sky-800/50">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"></path></svg>
                                                    {{ $app->user?->email }}
                                                </span>
                                                <span class="text-gray-500">•</span>
                                                <span>Applicant: <strong>{{ $app->user?->name }}</strong></span>
                                                <span class="text-gray-500">•</span>
                                                <span>Vault ID #{{ $app->id }}</span>
                                                <span class="text-gray-500">•</span>
                                                <span>Total Size: {{ $app->vaultSizeFormatted() }} ({{ $app->documents->count() }} files)</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2.5">
                                        <!-- Download ZIP Button -->
                                        @if($app->documents->isNotEmpty())
                                            <a href="{{ route('applications.downloadZip', $app) }}" class="inline-flex items-center px-3.5 py-2 bg-white/10 hover:bg-white/20 text-white text-xs font-semibold rounded-xl border border-white/20 shadow-sm transition">
                                                <svg class="w-4 h-4 mr-1.5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                Download All (ZIP)
                                            </a>
                                        @endif

                                        <!-- Toggle Upload Drawer -->
                                        <button type="button" @click="uploadOpen = !uploadOpen; if(!uploadOpen){ selectedCat = ''; customCatInput = ''; folderTargetName = ''; }" class="inline-flex items-center px-4 py-2 bg-indigo-500 hover:bg-indigo-400 text-white text-xs font-semibold rounded-xl shadow-sm transition">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            <span x-text="uploadOpen ? 'Close Uploader' : '+ Upload Document'"></span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Progress bar for requirements -->
                                @if($app->requirements->isNotEmpty())
                                    <div class="mt-4 pt-4 border-t border-white/10">
                                        <div class="flex items-center justify-between text-xs text-gray-300 mb-1.5">
                                            <span>Document Checklist Completion</span>
                                            <span class="font-semibold text-white">{{ $app->completionPercentage() }}% Fulfilled</span>
                                        </div>
                                        <div class="w-full bg-white/10 rounded-full h-2 overflow-hidden">
                                            <div class="bg-indigo-400 h-2 rounded-full transition-all duration-500" style="width: {{ $app->completionPercentage() }}%"></div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Upload Drawer (Alpine collapsible) -->
                            <div x-show="uploadOpen" x-cloak class="p-6 bg-indigo-50/70 border-b border-indigo-100">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-bold text-gray-900 flex items-center">
                                        <svg class="w-4 h-4 mr-1.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                        <span>Upload Document to Application Vault #{{ $app->id }}</span>
                                    </h4>
                                    <button type="button" @click="uploadOpen = false" class="text-xs text-gray-400 hover:text-gray-600">✕ Close</button>
                                </div>

                                <form action="{{ route('documents.store', $app) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <!-- Category Selector -->
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Target Folder / Category</label>
                                            <select name="document_category_id" x-model="selectedCat" class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" required>
                                                <option value="" disabled selected>-- Select Folder / Category --</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                                <option value="custom">Other / Custom Folder Name</option>
                                            </select>
                                        </div>

                                        <!-- Custom Category Input (shown if Other selected) -->
                                        <div x-show="selectedCat === 'custom'" x-cloak>
                                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Custom Folder Name</label>
                                            <input type="text" name="custom_category" x-model="customCatInput" placeholder="e.g. Police Clearance, Sponsor Tax Return" class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                        </div>

                                        <!-- File Input -->
                                        <div :class="selectedCat === 'custom' ? 'md:col-span-1' : 'md:col-span-2'">
                                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Select File(s) (PDF, JPG, PNG, DOC - Max 10MB each)</label>
                                            <input type="file" name="documents[]" multiple class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 file:cursor-pointer cursor-pointer border border-gray-300 rounded-xl bg-white p-1" required>
                                            <p class="text-[11px] text-gray-400 mt-1">Tip: You can select multiple files at once to deposit into this folder (e.g. multiple bank statements).</p>
                                        </div>
                                    </div>

                                    <div class="flex justify-between items-center pt-2">
                                        <span class="text-xs text-gray-400">Files will be stored and organized under the selected folder.</span>
                                        <div class="flex gap-2">
                                            <button type="button" @click="uploadOpen = false" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-800 rounded-xl border border-gray-300 bg-white hover:bg-gray-50">Cancel</button>
                                            <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition">
                                                <span x-text="folderTargetName ? 'Upload to ' + folderTargetName : 'Upload to Folder'"></span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Organized Vault Folders -->
                            @php
                                $vaultFolders = $app->getVaultFolders();
                            @endphp

                            <div class="p-6">
                                <div class="flex items-center justify-between mb-5 flex-wrap gap-2">
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider flex items-center gap-2">
                                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Visa Document Requirements Checklist & Folders ({{ $vaultFolders->count() }})
                                        </h4>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            Official required document categories for {{ $app->visa_type }} Visa ({{ $app->target_country }}). Deposit files into each folder to complete your application.
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($app->requirements->isNotEmpty())
                                            <span class="text-xs font-semibold px-2.5 py-1.5 rounded-xl {{ $app->completionPercentage() >= 100 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                                {{ $app->completionPercentage() }}% Fulfilled ({{ $app->requirements->where('is_fulfilled', true)->count() }}/{{ $app->requirements->where('is_mandatory', true)->count() }} required)
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    @foreach($vaultFolders as $folder)
                                        <div class="border rounded-2xl overflow-hidden transition {{ $folder['documents']->isNotEmpty() ? 'border-gray-200 bg-white shadow-sm' : 'border-gray-200/80 bg-slate-50/50' }}"
                                             x-data="{ folderOpen: {{ $folder['documents']->isNotEmpty() ? 'true' : 'false' }} }">
                                            
                                            <!-- Folder Header -->
                                            <div class="p-4 flex items-center justify-between gap-4 cursor-pointer select-none transition hover:bg-slate-50"
                                                 @click="folderOpen = !folderOpen">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold flex-shrink-0
                                                        {{ $folder['documents']->isNotEmpty() ? 'bg-indigo-50 text-indigo-600 border border-indigo-200' : 'bg-gray-100 text-gray-400 border border-gray-200' }}">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                                                    </div>
                                                    <div>
                                                        <div class="flex items-center gap-2 flex-wrap">
                                                            <span class="font-bold text-sm text-gray-900 tracking-tight">{{ $folder['name'] }}</span>

                                                            @if($folder['name'] === 'Others')
                                                                <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded-md bg-purple-100 text-purple-800 border border-purple-200">
                                                                    Others • General Vault
                                                                </span>
                                                            @elseif($folder['is_mandatory'])
                                                                <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded-md {{ $folder['is_fulfilled'] ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                                                    {{ $folder['is_fulfilled'] ? 'Mandatory • Fulfilled' : 'Mandatory • Required' }}
                                                                </span>
                                                            @else
                                                                <span class="px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider rounded-md bg-slate-100 text-slate-600 border border-slate-200">
                                                                    Optional Category
                                                                </span>
                                                            @endif

                                                            <span class="px-2 py-0.5 text-[11px] font-semibold rounded-full {{ $folder['documents']->isNotEmpty() ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-500' }}">
                                                                {{ $folder['documents']->count() }} {{ \Illuminate\Support\Str::plural('file', $folder['documents']->count()) }}
                                                            </span>
                                                        </div>
                                                        <p class="text-[11px] text-gray-400 mt-0.5">
                                                            {{ $folder['documents']->isEmpty() ? 'Folder is currently empty' : $folder['documents']->count() . ' document(s) deposited under this category' }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="flex items-center gap-2.5" @click.stop>
                                                    <!-- Toggle folder view / upload -->
                                                    <button type="button" 
                                                            @click="folderOpen = !folderOpen"
                                                            class="inline-flex items-center px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-xl border border-indigo-200 transition">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                        <span x-text="folderOpen ? 'Close Folder' : 'Open / Deposit'"></span>
                                                    </button>

                                                    <!-- Expand / Collapse chevron -->
                                                    <button type="button" @click="folderOpen = !folderOpen" class="p-1.5 text-gray-400 hover:text-gray-600 rounded-lg">
                                                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': folderOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Folder Body -->
                                            <div x-show="folderOpen" class="border-t border-gray-100 p-4 bg-slate-50/30">
                                                @if($folder['documents']->isEmpty())
                                                    <div class="py-5 px-4 text-center border-2 border-dashed border-gray-200 rounded-xl bg-white">
                                                        <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mx-auto mb-2">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                                        </div>
                                                        <p class="text-xs font-bold text-gray-800">No documents deposited into {{ $folder['name'] }} yet</p>
                                                        <p class="text-[11px] text-gray-400 mt-0.5">Select and deposit required file(s) (PDF, JPG, PNG, DOC - Max 10MB each):</p>

                                                        <form action="{{ route('documents.store', $app) }}" method="POST" enctype="multipart/form-data" class="mt-3.5 max-w-lg mx-auto flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                                                            @csrf
                                                            <input type="hidden" name="document_category_id" value="{{ $folder['category_id'] }}">
                                                            @if(!$folder['category_id'] && $folder['custom_category'])
                                                                <input type="hidden" name="custom_category" value="{{ $folder['custom_category'] }}">
                                                            @endif
                                                            <input type="file" name="documents[]" multiple class="w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 file:cursor-pointer cursor-pointer border border-gray-300 rounded-xl bg-slate-50 p-1 flex-1" required>
                                                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition whitespace-nowrap">
                                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                                Deposit File
                                                            </button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <div class="divide-y divide-gray-100 bg-white rounded-xl border border-gray-100 px-4">
                                                        @foreach($folder['documents'] as $doc)
                                                            <div class="py-3.5 space-y-2.5" x-data="{ showVersions: false }">
                                                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                                                    
                                                                    <!-- File Info -->
                                                                    <div class="flex items-start gap-3">
                                                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 font-bold text-xs
                                                                            {{ $doc->is_locked ? 'bg-rose-50 text-rose-600 border border-rose-200' : 'bg-indigo-50 text-indigo-600 border border-indigo-200' }}">
                                                                            @if($doc->is_locked)
                                                                                <svg class="w-4 h-4 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                                                                            @else
                                                                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                                            @endif
                                                                        </div>

                                                                        <div>
                                                                            <div class="flex items-center gap-2 flex-wrap">
                                                                                <span class="font-semibold text-sm text-gray-900">{{ $doc->file_name }}</span>
                                                                                <span class="px-2 py-0.5 text-[11px] font-medium rounded bg-gray-100 text-gray-600">
                                                                                    v{{ $doc->version }}
                                                                                </span>
                                                                            </div>
                                                                            <div class="flex items-center gap-2.5 text-xs text-gray-500 mt-0.5 flex-wrap">
                                                                                <span>{{ $doc->fileSizeFormatted() }}</span>
                                                                                <span>•</span>
                                                                                <span>Uploaded: {{ $doc->created_at->format('M d, Y H:i') }}</span>
                                                                                @if($doc->versions->isNotEmpty())
                                                                                    <span>•</span>
                                                                                    <button type="button" @click="showVersions = !showVersions" class="text-indigo-600 hover:text-indigo-800 font-semibold underline text-xs">
                                                                                        <span x-text="showVersions ? 'Hide History' : 'History ({{ $doc->versions->count() }} previous)'"></span>
                                                                                    </button>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Status & Actions -->
                                                                    <div class="flex items-center gap-2.5 flex-wrap sm:flex-nowrap">
                                                                        
                                                                        <!-- Status Badge -->
                                                                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full flex items-center gap-1
                                                                            {{ $doc->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : 
                                                                               ($doc->status === 'rejected' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                                                                            @if($doc->status === 'approved')
                                                                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                                            @elseif($doc->status === 'rejected')
                                                                                <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                                            @else
                                                                                <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                                            @endif
                                                                            {{ ucfirst($doc->status) }}
                                                                        </span>

                                                                        <!-- Lock indicator -->
                                                                        @if($doc->is_locked)
                                                                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold text-rose-700 bg-rose-50 rounded border border-rose-200" title="This file is locked by the admin and cannot be deleted or replaced.">
                                                                                <svg class="w-3 h-3 mr-1 text-rose-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                                                                                Locked
                                                                            </span>
                                                                        @endif

                                                                        <!-- View Button -->
                                                                        <button type="button" 
                                                                                @click="$dispatch('open-preview-modal', { url: '{{ route('documents.show', $doc) }}', name: '{{ addslashes($doc->file_name) }}', ext: '{{ strtolower(pathinfo($doc->file_name, PATHINFO_EXTENSION)) }}' })"
                                                                                class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg border border-indigo-200 shadow-2xs transition" 
                                                                                title="View Document in Website">
                                                                            <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                                            <span>View</span>
                                                                        </button>

                                                                        <!-- Download Action -->
                                                                        <a href="{{ route('documents.download', $doc) }}" class="p-1.5 text-gray-500 hover:text-indigo-600 hover:bg-gray-100 rounded-lg transition" title="Download Document">
                                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                                        </a>

                                                                        @if(!$doc->is_locked)
                                                                            <!-- Replace Button -->
                                                                            <button type="button" @click="activeReplaceId = (activeReplaceId === {{ $doc->id }} ? null : {{ $doc->id }})" class="p-1.5 text-gray-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Replace file with newer version">
                                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                                                            </button>

                                                                            <!-- Delete Button -->
                                                                            <form action="{{ route('documents.destroy', $doc) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this document from the vault?');" class="inline">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit" class="p-1.5 text-gray-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Delete Document">
                                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                                                </button>
                                                                            </form>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <!-- Admin Feedback Notes Callout (if rejected or reviewed with notes) -->
                                                                @if($doc->admin_notes)
                                                                    <div class="p-2.5 rounded-xl text-xs {{ $doc->status === 'rejected' ? 'bg-rose-50 border border-rose-200 text-rose-800' : 'bg-slate-50 border border-slate-200 text-slate-700' }}">
                                                                        <span class="font-bold">Admin Review Notes:</span> {{ $doc->admin_notes }}
                                                                    </div>
                                                                @endif

                                                                <!-- Replace File Inline Drawer -->
                                                                <div x-show="activeReplaceId === {{ $doc->id }}" x-cloak class="p-3 bg-amber-50/60 rounded-xl border border-amber-200">
                                                                    <p class="text-xs font-bold text-amber-900 mb-1">Upload new version for {{ $doc->file_name }} (Current: v{{ $doc->version }})</p>
                                                                    <p class="text-[11px] text-amber-700 mb-2">The current file will be moved to archived history and replaced.</p>
                                                                    <form action="{{ route('documents.replace', $doc) }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-2.5 items-center">
                                                                        @csrf
                                                                        <input type="file" name="document" class="text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-600 file:text-white hover:file:bg-amber-700 file:cursor-pointer cursor-pointer border border-amber-300 rounded-lg bg-white p-1 w-full" required>
                                                                        <div class="flex gap-2 flex-shrink-0">
                                                                            <button type="button" @click="activeReplaceId = null" class="px-3 py-1.5 text-xs text-gray-600 bg-white border border-gray-300 rounded-lg">Cancel</button>
                                                                            <button type="submit" class="px-3 py-1.5 text-xs font-bold text-white bg-amber-600 hover:bg-amber-700 rounded-lg">Save Version {{ $doc->version + 1 }}</button>
                                                                        </div>
                                                                    </form>
                                                                </div>

                                                                <!-- Previous Version History -->
                                                                <div x-show="showVersions" x-cloak class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                                                                    <p class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Archived Version History</p>
                                                                    <div class="space-y-1.5">
                                                                        @foreach($doc->versions as $v)
                                                                            <div class="flex items-center justify-between text-xs text-gray-600 bg-white p-2 rounded-lg border border-gray-200">
                                                                                <div>
                                                                                    <span class="font-semibold text-gray-900">v{{ $v->version }}: {{ $v->file_name }}</span>
                                                                                    <span class="text-gray-400 ml-2">{{ $v->fileSizeFormatted() }} • Archived {{ $v->created_at->format('M d, Y H:i') }}</span>
                                                                                </div>
                                                                                <span class="text-[11px] text-gray-500">Replaced by {{ $v->replacedBy?->name ?? 'User' }}</span>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        @endforeach
                                                    </div>

                                                    <!-- Direct in-folder upload for additional files -->
                                                    <div class="mt-3 p-3 bg-indigo-50/50 rounded-xl border border-indigo-100">
                                                        <p class="text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-2 flex items-center gap-1">
                                                            <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                            Deposit Additional File(s) to {{ $folder['name'] }}
                                                        </p>
                                                        <form action="{{ route('documents.store', $app) }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                                                            @csrf
                                                            <input type="hidden" name="document_category_id" value="{{ $folder['category_id'] }}">
                                                            @if(!$folder['category_id'] && $folder['custom_category'])
                                                                <input type="hidden" name="custom_category" value="{{ $folder['custom_category'] }}">
                                                            @endif
                                                            <input type="file" name="documents[]" multiple class="w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 file:cursor-pointer cursor-pointer border border-indigo-200 rounded-xl bg-white p-1 flex-1" required>
                                                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition whitespace-nowrap">
                                                                Deposit to Folder
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>

                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    @endforeach
                    </div>
                </div>
            @endif

            <!-- Modal: Apply for New Visa -->
            <div x-show="newAppModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4" @keydown.escape.window="newAppModalOpen = false">
                <div class="bg-white rounded-2xl max-w-lg w-full overflow-hidden shadow-2xl border border-gray-100" @click.outside="newAppModalOpen = false">
                    <div class="p-5 bg-gradient-to-r from-slate-900 to-indigo-950 text-white flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-base">Apply for New Visa Program</h3>
                            <p class="text-xs text-gray-300 mt-0.5">Open a new visa application vault with personalized document requirements</p>
                        </div>
                        <button type="button" @click="newAppModalOpen = false" class="text-gray-400 hover:text-white text-lg font-bold">✕</button>
                    </div>

                    <form action="{{ route('applications.storeLead') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Target Country *</label>
                                <select name="country" x-model="newAppCountry" @change="onCountryChange()" class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" required>
                                    <template x-for="c in countriesData" :key="c.name">
                                        <option :value="c.name" x-text="(c.flag ? c.flag + ' ' : '') + c.name"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Visa Type *</label>
                                <select name="visaType" x-model="newAppVisaType" class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" required>
                                    <template x-for="vt in getAvailableVisaTypes()" :key="vt.name">
                                        <option :value="vt.name" x-text="vt.display_name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <!-- Dynamic Requirements Preview in Modal -->
                        <div class="p-3.5 bg-slate-50 rounded-xl border border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-gray-800 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Required Documents for <span x-text="(newAppCountry ? newAppCountry + ' ' : '') + newAppVisaType + ' Visa'" class="text-indigo-600 font-bold"></span>:
                                </span>
                            </div>
                            <div class="flex flex-wrap gap-1.5">
                                <template x-for="req in getModalReqs()" :key="req.name">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-white border border-gray-200 text-gray-800 shadow-2xs">
                                        <svg class="w-3 h-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        <span x-text="req.name"></span>
                                        <span x-show="req.is_mandatory" class="text-[9px] px-1 py-0.2 bg-amber-50 text-amber-700 font-bold rounded">Req</span>
                                    </span>
                                </template>
                            </div>
                        </div>

                        <!-- Pre-populated user details -->
                        <input type="hidden" name="fullName" value="{{ auth()->user()->name }}">
                        <input type="hidden" name="phone" value="{{ auth()->user()->phone ?? '+880 1000-000000' }}">
                        <input type="hidden" name="email" value="{{ auth()->user()->email }}">

                        <!-- Direct File Upload Field -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Attach Visa Documents (Optional - PDF, JPG, PNG, DOC)</label>
                            <input type="file" name="documents[]" multiple class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 file:cursor-pointer cursor-pointer border border-gray-300 rounded-xl bg-white p-1">
                            <p class="text-[11px] text-gray-400 mt-1">You can attach multiple files now or deposit them into folder vaults after creation.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Additional Notes / Inquiry</label>
                            <textarea name="message" rows="2" class="w-full rounded-xl border-gray-300 text-xs focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="Any specific requirements, sponsor notes, or questions..."></textarea>
                        </div>

                        <div class="flex justify-end gap-2 pt-2">
                            <button type="button" @click="newAppModalOpen = false" class="px-4 py-2 text-xs font-semibold text-gray-600 bg-white border border-gray-300 rounded-xl hover:bg-gray-50">Cancel</button>
                            <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition">
                                Create Application & Open Vault
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Document In-Website Preview Modal -->
            <div x-show="previewOpen" 
                 x-cloak 
                 class="fixed inset-0 z-50 overflow-y-auto"
                 @keydown.escape.window="previewOpen = false">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                    <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-xs transition-opacity" @click="previewOpen = false"></div>

                    <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-4xl w-full max-h-[92vh] flex flex-col z-10">
                        <!-- Modal Header -->
                        <div class="bg-slate-900 px-6 py-4 flex items-center justify-between text-white border-b border-slate-800 flex-shrink-0">
                            <div class="flex items-center gap-3 truncate">
                                <div class="w-9 h-9 rounded-xl bg-indigo-500/20 border border-indigo-400/30 flex items-center justify-center text-indigo-400 flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </div>
                                <div class="truncate">
                                    <h3 class="font-bold text-sm text-white truncate" x-text="previewName">Document Preview</h3>
                                    <p class="text-xs text-gray-400" x-text="'Previewing file (.' + previewExt + ')'"></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <a :href="previewUrl" target="_blank" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 text-white text-xs font-semibold rounded-lg transition inline-flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    <span>New Tab</span>
                                </a>
                                <button type="button" @click="previewOpen = false" class="p-1.5 text-gray-400 hover:text-white rounded-lg hover:bg-white/10 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Modal Content (Viewer) -->
                        <div class="flex-1 p-4 bg-slate-100 overflow-auto flex items-center justify-center min-h-[420px]">
                            <template x-if="['jpg', 'jpeg', 'png', 'webp', 'gif'].includes(previewExt.toLowerCase())">
                                <img :src="previewUrl" :alt="previewName" class="max-w-full max-h-[72vh] object-contain rounded-xl shadow-md bg-white border border-gray-200">
                            </template>
                            <template x-if="previewExt.toLowerCase() === 'pdf'">
                                <iframe :src="previewUrl" class="w-full h-[72vh] rounded-xl border border-gray-300 bg-white" frameborder="0"></iframe>
                            </template>
                            <template x-if="!['jpg', 'jpeg', 'png', 'webp', 'gif', 'pdf'].includes(previewExt.toLowerCase())">
                                <div class="text-center py-12 px-6">
                                    <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center mx-auto mb-3 text-indigo-600 border border-gray-200">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <p class="font-bold text-gray-900 text-sm mb-1" x-text="previewName"></p>
                                    <p class="text-xs text-gray-500 mb-4 max-w-sm mx-auto">Inline viewer supports PDF and Image files. For Word documents or other formats, download to open.</p>
                                    <a :href="previewUrl" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded-xl shadow-sm hover:bg-indigo-700 transition">
                                        Download File
                                    </a>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

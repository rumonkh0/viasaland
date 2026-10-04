<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    {{ __('Visa Document Vault') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Centralized & secure document storage organized by your visa applications.
                </p>
            </div>
            <div class="flex items-center gap-2">
                @if(auth()->user()->isAdmin())
                    <a href="{{ url('/admin') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold uppercase tracking-widest rounded-lg shadow-sm transition">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Admin Control Panel
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
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

            <!-- Applications / Vault Folders -->
            @if($applications->isEmpty())
                <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center shadow-sm">
                    <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">No Visa Applications Found</h3>
                    <p class="text-sm text-gray-500 max-w-md mx-auto mb-6">You don't have any visa applications active yet. Inquire or apply to open your personal document vault.</p>
                    <a href="{{ url('/') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                        Browse Visa Programs
                    </a>
                </div>
            @else
                <div class="space-y-8">
                    @foreach($applications as $app)
                        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden" x-data="{ uploadOpen: false, activeReplaceId: null }">
                            
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
                                            <p class="text-xs text-gray-400 mt-1">
                                                Vault ID #{{ $app->id }} • Total Storage: {{ $app->vaultSizeFormatted() }} • {{ $app->documents->count() }} file(s)
                                            </p>
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
                                        <button type="button" @click="uploadOpen = !uploadOpen" class="inline-flex items-center px-4 py-2 bg-indigo-500 hover:bg-indigo-400 text-white text-xs font-semibold rounded-xl shadow-sm transition">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            <span x-text="uploadOpen ? 'Close Uploader' : 'Upload File'"></span>
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

                            <!-- Requirements Checklist Strip -->
                            @if($app->requirements->isNotEmpty())
                                <div class="px-6 py-4 bg-slate-100 border-b border-gray-200">
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5">Required Document Categories</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($app->requirements as $req)
                                            @php
                                                $uploadedDocsCount = $app->documents->where('document_category_id', $req->document_category_id)->count();
                                            @endphp
                                            <div class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium border
                                                {{ $uploadedDocsCount > 0 ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-amber-50 text-amber-800 border-amber-200' }}">
                                                @if($uploadedDocsCount > 0)
                                                    <svg class="w-3.5 h-3.5 mr-1 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    <span>{{ $req->documentCategory->name }} ({{ $uploadedDocsCount }})</span>
                                                @else
                                                    <svg class="w-3.5 h-3.5 mr-1 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                    <span>{{ $req->documentCategory->name }} (Missing)</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Upload Drawer (Alpine collapsible) -->
                            <div x-show="uploadOpen" x-collapse class="p-6 bg-indigo-50/50 border-b border-indigo-100" x-data="{ selectedCat: '' }">
                                <h4 class="text-sm font-bold text-gray-900 mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-1.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                    Add New File to Application #{{ $app->id }} Vault
                                </h4>
                                <form action="{{ route('documents.store', $app) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <!-- Category Selector -->
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Document Category</label>
                                            <select name="document_category_id" x-model="selectedCat" class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" required>
                                                <option value="" disabled selected>-- Select Category --</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                                <option value="custom">Other / Custom Category</option>
                                            </select>
                                        </div>

                                        <!-- Custom Category Input (shown if Other selected) -->
                                        <div x-show="selectedCat === 'custom'" x-cloak>
                                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Custom Category Name</label>
                                            <input type="text" name="custom_category" placeholder="e.g. Police Clearance, Land Deeds" class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                        </div>

                                        <!-- File Input -->
                                        <div :class="selectedCat === 'custom' ? 'md:col-span-1' : 'md:col-span-2'">
                                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Select File (PDF, JPG, PNG, DOC - Max 10MB)</label>
                                            <input type="file" name="document" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 file:cursor-pointer cursor-pointer border border-gray-300 rounded-xl bg-white p-1" required>
                                        </div>
                                    </div>

                                    <div class="flex justify-end gap-2 pt-2">
                                        <button type="button" @click="uploadOpen = false" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-800 rounded-xl border border-gray-300 bg-white hover:bg-gray-50">Cancel</button>
                                        <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition">Upload to Vault</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Vault Documents List -->
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Vault Files ({{ $app->documents->count() }})</h4>
                                    <span class="text-xs text-gray-400">Multiple files per category are stored and managed independently</span>
                                </div>

                                @if($app->documents->isEmpty())
                                    <div class="p-8 text-center border-2 border-dashed border-gray-200 rounded-xl bg-gray-50/50">
                                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                        <p class="text-sm font-medium text-gray-600">No documents in this application vault yet.</p>
                                        <p class="text-xs text-gray-400 mt-1">Click "Upload File" above to start depositing your required visa files.</p>
                                    </div>
                                @else
                                    <div class="divide-y divide-gray-100">
                                        @foreach($app->documents as $doc)
                                            <div class="py-4 space-y-3" x-data="{ showVersions: false }">
                                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                                    
                                                    <!-- File Info -->
                                                    <div class="flex items-start gap-3">
                                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 font-bold text-xs
                                                            {{ $doc->is_locked ? 'bg-rose-50 text-rose-600 border border-rose-200' : 'bg-indigo-50 text-indigo-600 border border-indigo-200' }}">
                                                            @if($doc->is_locked)
                                                                <svg class="w-5 h-5 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                                                            @else
                                                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                            @endif
                                                        </div>

                                                        <div>
                                                            <div class="flex items-center gap-2 flex-wrap">
                                                                <span class="font-semibold text-sm text-gray-900">{{ $doc->file_name }}</span>
                                                                <span class="px-2 py-0.5 text-xs font-semibold rounded-md bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                                    {{ $doc->categoryName() }}
                                                                </span>
                                                                <span class="px-2 py-0.5 text-[11px] font-medium rounded bg-gray-100 text-gray-600">
                                                                    v{{ $doc->version }}
                                                                </span>
                                                            </div>
                                                            <div class="flex items-center gap-3 text-xs text-gray-500 mt-1 flex-wrap">
                                                                <span>{{ $doc->fileSizeFormatted() }}</span>
                                                                <span>•</span>
                                                                <span>Uploaded: {{ $doc->created_at->format('M d, Y H:i') }}</span>
                                                                @if($doc->versions->isNotEmpty())
                                                                    <span>•</span>
                                                                    <button type="button" @click="showVersions = !showVersions" class="text-indigo-600 hover:text-indigo-800 font-semibold underline text-xs">
                                                                        <span x-text="showVersions ? 'Hide Version History' : 'History ({{ $doc->versions->count() }} previous)'"></span>
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Status & Actions -->
                                                    <div class="flex items-center gap-3 flex-wrap sm:flex-nowrap">
                                                        
                                                        <!-- Status Badge -->
                                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full flex items-center gap-1
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

                                                        <!-- Download Action -->
                                                        <a href="{{ route('documents.download', $doc) }}" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-gray-100 rounded-lg transition" title="Download Document">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                        </a>

                                                        @if(!$doc->is_locked)
                                                            <!-- Replace Button -->
                                                            <button type="button" @click="activeReplaceId = (activeReplaceId === {{ $doc->id }} ? null : {{ $doc->id }})" class="p-2 text-gray-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Replace file with newer version">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                                            </button>

                                                            <!-- Delete Button -->
                                                            <form action="{{ route('documents.destroy', $doc) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this document from the vault?');" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="p-2 text-gray-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Delete Document">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Admin Feedback Notes Callout (if rejected or reviewed with notes) -->
                                                @if($doc->admin_notes)
                                                    <div class="ml-13 p-3 rounded-xl text-xs {{ $doc->status === 'rejected' ? 'bg-rose-50 border border-rose-200 text-rose-800' : 'bg-slate-50 border border-slate-200 text-slate-700' }}">
                                                        <span class="font-bold">Admin Review Notes:</span> {{ $doc->admin_notes }}
                                                    </div>
                                                @endif

                                                <!-- Replace File Inline Drawer -->
                                                <div x-show="activeReplaceId === {{ $doc->id }}" x-cloak class="p-4 bg-amber-50/60 rounded-xl border border-amber-200">
                                                    <p class="text-xs font-bold text-amber-900 mb-2">Upload new version for {{ $doc->file_name }} (Current: v{{ $doc->version }})</p>
                                                    <p class="text-[11px] text-amber-700 mb-3">The existing file will be archived in version history and replaced with your new upload.</p>
                                                    <form action="{{ route('documents.replace', $doc) }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-3 items-center">
                                                        @csrf
                                                        <input type="file" name="document" class="text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-600 file:text-white hover:file:bg-amber-700 file:cursor-pointer cursor-pointer border border-amber-300 rounded-lg bg-white p-1 w-full" required>
                                                        <div class="flex gap-2 flex-shrink-0">
                                                            <button type="button" @click="activeReplaceId = null" class="px-3 py-1.5 text-xs text-gray-600 bg-white border border-gray-300 rounded-lg">Cancel</button>
                                                            <button type="submit" class="px-3 py-1.5 text-xs font-bold text-white bg-amber-600 hover:bg-amber-700 rounded-lg">Save Version {{ $doc->version + 1 }}</button>
                                                        </div>
                                                    </form>
                                                </div>

                                                <!-- Previous Version History -->
                                                <div x-show="showVersions" x-cloak class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                                                    <p class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Archived Version History</p>
                                                    <div class="space-y-2">
                                                        @foreach($doc->versions as $v)
                                                            <div class="flex items-center justify-between text-xs text-gray-600 bg-white p-2.5 rounded-lg border border-gray-200">
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
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>

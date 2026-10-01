<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Client Portal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Applications Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">My Visa Applications</h3>
                    @if($applications->isEmpty())
                        <p class="text-gray-500">You have no active applications.</p>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @foreach($applications as $app)
                                <li class="py-4 flex justify-between items-center">
                                    <div>
                                        <p class="font-semibold">{{ $app->visa_type }} Visa - {{ $app->target_country }}</p>
                                        <p class="text-sm text-gray-500">Submitted: {{ $app->created_at->format('M d, Y') }}</p>
                                    </div>
                                    <div>
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ ucfirst(str_replace('_', ' ', $app->status)) }}
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <!-- Documents Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Document Vault</h3>
                    
                    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="mb-8 bg-gray-50 p-4 rounded-lg border">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Document Type</label>
                                <select name="document_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="Passport">Passport</option>
                                    <option value="Bank Statement">Bank Statement</option>
                                    <option value="Photo">Photo</option>
                                    <option value="Police Clearance">Police Clearance</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">File</label>
                                <input type="file" name="document" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                            </div>
                            <div>
                                <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Upload</button>
                            </div>
                        </div>
                    </form>

                    @if($documents->isEmpty())
                        <p class="text-gray-500">No documents uploaded yet.</p>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @foreach($documents as $doc)
                                <li class="py-4 flex justify-between items-center">
                                    <div>
                                        <p class="font-semibold">{{ $doc->document_type }}</p>
                                        <p class="text-sm text-gray-500">{{ $doc->original_name }}</p>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $doc->status === 'approved' ? 'bg-green-100 text-green-800' : ($doc->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ ucfirst($doc->status) }}
                                        </span>
                                        <a href="{{ route('documents.download', $doc) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Download</a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

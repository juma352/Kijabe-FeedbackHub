<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Import External Feedback') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Navigation -->
            <div class="mb-6">
                <a href="{{ route('feedback.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-900">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Feedback List
                </a>
            </div>

            <!-- Info Section -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <svg class="w-5 h-5 text-blue-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="text-blue-800 font-medium">Import Feedback from External Sources</h3>
                        <p class="text-blue-700 text-sm mt-1">Import feedback from Microsoft Forms, Google Forms, Facebook, or upload CSV files. All imported feedback will be automatically analyzed for sentiment and scored.</p>
                    </div>
                </div>
            </div>

            <!-- Errors -->
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Import Options -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Google Forms -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-6 h-6 text-green-600" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Google Forms</h3>
                        </div>
                        
                        <form method="POST" action="{{ route('feedback.import.process') }}">
                            @csrf
                            <input type="hidden" name="import_type" value="google_forms">
                            
                            <div class="mb-4">
                                <x-input-label for="google_spreadsheet_id" :value="__('Google Sheets ID')" />
                                <x-text-input id="google_spreadsheet_id" class="block mt-1 w-full" type="text" name="source_id" placeholder="1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms" required />
                                <p class="text-sm text-gray-600 mt-1">Get this from your Google Sheets URL</p>
                            </div>
                            
                            <x-primary-button class="w-full justify-center">
                                {{ __('Import from Google Forms') }}
                            </x-primary-button>
                        </form>
                    </div>
                </div>

                <!-- Microsoft Forms -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-6 h-6 text-blue-600" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M22,17.74L16.74,22H16V16.74L22,11.5M15,2V13.5L8.5,20H2V2M13,4H4V18H7.08L13,12.08M11,6H6V8H11M9,10H6V12H9"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Microsoft Forms</h3>
                        </div>
                        
                        <form method="POST" action="{{ route('feedback.import.process') }}">
                            @csrf
                            <input type="hidden" name="import_type" value="microsoft_forms">
                            
                            <div class="mb-4">
                                <x-input-label for="ms_form_id" :value="__('Form ID')" />
                                <x-text-input id="ms_form_id" class="block mt-1 w-full" type="text" name="source_id" placeholder="01ABCDEFGHIJKLMNOPQRSTUVWXYZ" required />
                                <p class="text-sm text-gray-600 mt-1">Microsoft Forms file ID</p>
                            </div>
                            
                            <div class="mb-4">
                                <x-input-label for="ms_access_token" :value="__('Access Token')" />
                                <x-text-input id="ms_access_token" class="block mt-1 w-full" type="password" name="access_token" placeholder="Your Microsoft Graph access token" required />
                                <p class="text-sm text-gray-600 mt-1">Microsoft Graph API token</p>
                            </div>
                            
                            <x-primary-button class="w-full justify-center">
                                {{ __('Import from Microsoft Forms') }}
                            </x-primary-button>
                        </form>
                    </div>
                </div>

                <!-- Facebook -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-6 h-6 text-blue-600" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Facebook Page</h3>
                        </div>
                        
                        <form method="POST" action="{{ route('feedback.import.process') }}">
                            @csrf
                            <input type="hidden" name="import_type" value="facebook">
                            
                            <div class="mb-4">
                                <x-input-label for="fb_page_id" :value="__('Page ID')" />
                                <x-text-input id="fb_page_id" class="block mt-1 w-full" type="text" name="source_id" placeholder="123456789012345" required />
                                <p class="text-sm text-gray-600 mt-1">Your Facebook page ID</p>
                            </div>
                            
                            <div class="mb-4">
                                <x-input-label for="fb_access_token" :value="__('Access Token')" />
                                <x-text-input id="fb_access_token" class="block mt-1 w-full" type="password" name="access_token" placeholder="Your Facebook access token" required />
                                <p class="text-sm text-gray-600 mt-1">Facebook Graph API token</p>
                            </div>
                            
                            <x-primary-button class="w-full justify-center">
                                {{ __('Import from Facebook') }}
                            </x-primary-button>
                        </form>
                    </div>
                </div>

                <!-- Kijabe Hospital CSV -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 border-blue-200">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Kijabe Hospital Education Dept</h3>
                                <p class="text-sm text-blue-600">Optimized for hospital feedback format</p>
                            </div>
                        </div>
                        
                        <form method="POST" action="{{ route('feedback.import.process') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="import_type" value="kijabe_hospital">
                            
                            <div class="mb-4">
                                <x-input-label for="kijabe_csv_file" :value="__('Hospital Feedback CSV')" />
                                <input id="kijabe_csv_file" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="file" name="csv_file" accept=".csv,.txt" required />
                                <p class="text-sm text-gray-600 mt-1">Upload your Kijabe Hospital education department feedback CSV file</p>
                            </div>
                            
                            <x-primary-button class="w-full justify-center bg-blue-600 hover:bg-blue-700">
                                {{ __('Import Hospital Feedback') }}
                            </x-primary-button>
                        </form>
                        
                        <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                            <p class="text-sm text-blue-800">
                                <strong>Features:</strong> Automatically categorizes feedback by department codes (CURR, WELF, ENVR, etc.), 
                                tracks response status, and analyzes hospital-specific terminology.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Generic CSV Upload -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Generic CSV File</h3>
                        </div>
                        
                        <form method="POST" action="{{ route('feedback.import.process') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="import_type" value="csv">
                            
                            <div class="mb-4">
                                <x-input-label for="csv_file" :value="__('CSV File')" />
                                <input id="csv_file" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="file" name="csv_file" accept=".csv,.txt" required />
                                <p class="text-sm text-gray-600 mt-1">Upload a CSV with columns: timestamp, email, feedback, rating</p>
                            </div>
                            
                            <x-primary-button class="w-full justify-center">
                                {{ __('Upload CSV File') }}
                            </x-primary-button>
                        </form>
                    </div>
                </div>

                <!-- Dynamic CSV Upload -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 border-green-200">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Smart CSV Upload</h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    ✨ New & Recommended
                                </span>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-3">
                                Upload any CSV format! Our smart analyzer will automatically detect your columns and let you map them to feedback fields. Works with any structure - no specific format required.
                            </p>
                            <div class="bg-green-50 border border-green-200 rounded-md p-3 mb-4">
                                <div class="flex">
                                    <svg class="w-4 h-4 text-green-400 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <div class="text-sm text-green-700">
                                        <p class="font-medium">Features:</p>
                                        <ul class="mt-1 space-y-1 text-xs">
                                            <li>• Automatically detects feedback content, ratings, dates</li>
                                            <li>• Works with any column names or structure</li>
                                            <li>• Preview before import with smart suggestions</li>
                                            <li>• Preserves additional data fields</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <form method="POST" action="{{ route('feedback.dynamic.csv') }}" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="mb-4">
                                <x-input-label for="dynamic_csv_file" :value="__('CSV File (Any Format)')" />
                                <input id="dynamic_csv_file" class="block mt-1 w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm" type="file" name="csv_file" accept=".csv,.txt,.tsv" required />
                                <p class="text-sm text-gray-600 mt-1">Upload CSV, TXT, or TSV files - any format supported! Max 10MB.</p>
                            </div>
                            
                            <x-primary-button class="w-full justify-center bg-green-600 hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:ring-green-500">
                                {{ __('🚀 Analyze & Import CSV') }}
                            </x-primary-button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Setup Instructions</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Google Forms Integration</h4>
                            <ol class="text-sm text-gray-600 list-decimal list-inside space-y-1">
                                <li>Create a Google Form and collect responses</li>
                                <li>View responses in Google Sheets</li>
                                <li>Copy the Sheets ID from the URL</li>
                                <li>Ensure the sheet is publicly readable or configure API access</li>
                            </ol>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Microsoft Forms Integration</h4>
                            <ol class="text-sm text-gray-600 list-decimal list-inside space-y-1">
                                <li>Create a Microsoft Form and collect responses</li>
                                <li>Get your form ID from the form URL</li>
                                <li>Register an app in Azure AD</li>
                                <li>Generate an access token with appropriate permissions</li>
                            </ol>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Facebook Integration</h4>
                            <ol class="text-sm text-gray-600 list-decimal list-inside space-y-1">
                                <li>Create a Facebook App in developers.facebook.com</li>
                                <li>Get your page ID from your Facebook page</li>
                                <li>Generate a page access token</li>
                                <li>Import posts and comments as feedback</li>
                            </ol>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">CSV File Format</h4>
                            <div class="text-sm text-gray-600">
                                <p class="mb-2">Your CSV should have these columns:</p>
                                <code class="bg-gray-100 px-2 py-1 rounded text-xs">timestamp,email,feedback,rating</code>
                                <p class="mt-2">Physical forms can be manually entered into this format.</p>
                                <p class="mt-2">
                                    <a href="{{ route('sample.csv') }}" class="text-blue-600 hover:text-blue-800 underline">
                                        Download Sample CSV Template
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
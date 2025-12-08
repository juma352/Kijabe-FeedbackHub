<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Services\ExternalSourceService;

Artisan::command('test:google-sheets {spreadsheet_id} {--api-key=}', function (string $spreadsheet_id, string $api_key = null) {
    $this->info('Testing Google Sheets connection...');
    
    if (!$api_key) {
        $api_key = config('services.google.api_key');
        if (!$api_key) {
            $this->error('No API key provided. Use --api-key=YOUR_KEY or set GOOGLE_API_KEY in .env');
            return;
        }
    }
    
    $service = new ExternalSourceService();
    
    $this->info("Using API Key: " . substr($api_key, 0, 10) . "...");
    $this->info("Spreadsheet ID: $spreadsheet_id");
    
    try {
        $result = $service->importFromGoogleForms($spreadsheet_id, 'Form Responses 1', $api_key);
        
        if ($result['success']) {
            $this->info("✅ Success! Imported {$result['count']} rows");
        } else {
            $this->error("❌ Failed: " . $result['error']);
            if (isset($result['debug'])) {
                $this->line("Debug info: " . json_encode($result['debug'], JSON_PRETTY_PRINT));
            }
        }
    } catch (Exception $e) {
        $this->error("❌ Exception: " . $e->getMessage());
    }
})->purpose('Test Google Sheets API connection');

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

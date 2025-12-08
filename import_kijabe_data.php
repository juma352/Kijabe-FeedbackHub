<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Services\KijabeHospitalImportService;
use Illuminate\Foundation\Application;

// Initialize Laravel app
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Import the CSV
$kijabeService = new KijabeHospitalImportService();
$result = $kijabeService->importKijabeCSV(__DIR__ . '/public/converted_csv.csv');

if ($result['success']) {
    echo "✅ SUCCESS: {$result['message']}\n";
    echo "Imported {$result['count']} feedback entries from Kijabe Hospital\n";
} else {
    echo "❌ ERROR: {$result['error']}\n";
}

echo "\nYou can now view the imported data at:\n";
echo "- Dashboard: http://localhost:8001/dashboard\n";
echo "- Hospital Analytics: http://localhost:8001/kijabe-analytics\n";
echo "- All Feedback: http://localhost:8001/feedback\n";
<?php

namespace App\Services;

use App\Models\Feedback;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExternalSourceService
{
    /**
     * Import from Microsoft Forms (via Microsoft Graph API)
     */
    public function importFromMicrosoftForms($formId, $accessToken)
    {
        try {
            $response = Http::withToken($accessToken)
                ->get("https://graph.microsoft.com/v1.0/me/drive/items/{$formId}/workbook/worksheets/FormResponses1/usedRange");
            
            if ($response->successful()) {
                $data = $response->json();
                $rows = $data['values'] ?? [];
                
                // Skip header row
                array_shift($rows);
                
                foreach ($rows as $row) {
                    $this->createFeedbackFromRow('Microsoft Forms', $row);
                }
                
                return ['success' => true, 'count' => count($rows)];
            }
            
            return ['success' => false, 'error' => 'Failed to fetch form data'];
            
        } catch (\Exception $e) {
            Log::error('Microsoft Forms import failed: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Import from Google Forms (via Google Sheets API)
     */
    public function importFromGoogleForms($spreadsheetId, $range = 'Form Responses 1', $apiKey = null)
    {
        try {
            // Extract spreadsheet ID if full URL is provided
            if (strpos($spreadsheetId, 'docs.google.com') !== false) {
                preg_match('/\/spreadsheets\/d\/([a-zA-Z0-9-_]+)/', $spreadsheetId, $matches);
                $spreadsheetId = $matches[1] ?? $spreadsheetId;
            }
            
            // Try service account authentication first, then fallback to API key
            if ($this->hasServiceAccount()) {
                $accessToken = $this->getServiceAccountAccessToken();
                $response = Http::withOptions([
                    'verify' => false, // Disable SSL verification for local development
                ])->withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                ])->get("https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheetId}/values/{$range}");
            } else {
                $apiKey = $apiKey ?: config('services.google.api_key');
                $response = Http::withOptions([
                    'verify' => false, // Disable SSL verification for local development
                ])->get("https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheetId}/values/{$range}", [
                    'key' => $apiKey
                ]);
            }
            
            if ($response->successful()) {
                $data = $response->json();
                $rows = $data['values'] ?? [];
                
                // Skip header row
                array_shift($rows);
                
                foreach ($rows as $row) {
                    $this->createFeedbackFromRow('Google Forms', $row);
                }
                
                return ['success' => true, 'count' => count($rows)];
            }
            
            $errorData = $response->json();
            $statusCode = $response->status();
            $errorMessage = $errorData['error']['message'] ?? 'Failed to fetch form data';
            
            return [
                'success' => false, 
                'error' => "HTTP {$statusCode}: {$errorMessage}",
                'debug' => $errorData
            ];
            
        } catch (\Exception $e) {
            Log::error('Google Forms import failed: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Import from Facebook Page (via Facebook Graph API)
     */
    public function importFromFacebook($pageId, $accessToken, $since = null)
    {
        try {
            $params = [
                'fields' => 'message,created_time,from,reactions.summary(total_count),comments.summary(total_count)'
            ];
            
            if ($since) {
                $params['since'] = $since;
            }
            
            $response = Http::get("https://graph.facebook.com/v18.0/{$pageId}/posts", [
                'access_token' => $accessToken,
                ...$params
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                $posts = $data['data'] ?? [];
                
                foreach ($posts as $post) {
                    if (isset($post['message'])) {
                        $metadata = [
                            'post_id' => $post['id'],
                            'created_time' => $post['created_time'],
                            'author' => $post['from']['name'] ?? 'Unknown',
                            'reactions' => $post['reactions']['summary']['total_count'] ?? 0,
                            'comments' => $post['comments']['summary']['total_count'] ?? 0
                        ];
                        
                        Feedback::create([
                            'source' => 'Facebook',
                            'content' => $post['message'],
                            'metadata' => json_encode($metadata),
                            'rating' => $this->estimateRatingFromReactions($post)
                        ]);
                    }
                }
                
                return ['success' => true, 'count' => count($posts)];
            }
            
            return ['success' => false, 'error' => 'Failed to fetch Facebook posts'];
            
        } catch (\Exception $e) {
            Log::error('Facebook import failed: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Import from CSV file (for physical forms that have been digitized)
     */
    public function importFromCsv($filePath, $sourceType = 'Physical Form')
    {
        try {
            // Ensure the file exists
            if (!file_exists($filePath)) {
                Log::error("CSV file not found: {$filePath}");
                return ['success' => false, 'error' => 'CSV file not found: ' . $filePath];
            }

            // Check if file is readable
            if (!is_readable($filePath)) {
                Log::error("CSV file is not readable: {$filePath}");
                return ['success' => false, 'error' => 'CSV file is not readable'];
            }

            $handle = fopen($filePath, 'r');
            
            if ($handle === false) {
                Log::error("Failed to open CSV file: {$filePath}");
                return ['success' => false, 'error' => 'Failed to open CSV file'];
            }

            $header = fgetcsv($handle); // Get header row
            
            if ($header === false || empty($header)) {
                fclose($handle);
                return ['success' => false, 'error' => 'CSV file appears to be empty or invalid'];
            }

            $count = 0;
            $errors = [];
            
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) !== count($header)) {
                    $errors[] = "Row " . ($count + 2) . " has mismatched columns";
                    continue;
                }
                
                $data = array_combine($header, $row);
                
                // Flexible mapping - adjust based on your CSV structure
                $content = $data['feedback'] ?? $data['comment'] ?? $data['response'] ?? $data['content'] ?? '';
                $rating = isset($data['rating']) ? (int)$data['rating'] : null;
                
                if (!empty(trim($content))) {
                    $metadata = array_diff_key($data, ['feedback' => '', 'comment' => '', 'response' => '', 'content' => '', 'rating' => '']);
                    
                    Feedback::create([
                        'source' => $sourceType,
                        'content' => trim($content),
                        'rating' => $rating,
                        'metadata' => json_encode($metadata)
                    ]);
                    
                    $count++;
                }
            }
            
            fclose($handle);
            
            // Clean up the uploaded file
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            $result = ['success' => true, 'count' => $count];
            
            if (!empty($errors)) {
                $result['warnings'] = $errors;
            }
            
            return $result;
            
        } catch (\Exception $e) {
            Log::error('CSV import failed: ' . $e->getMessage(), [
                'file_path' => $filePath,
                'trace' => $e->getTraceAsString()
            ]);
            
            return ['success' => false, 'error' => 'Import failed: ' . $e->getMessage()];
        }
    }
    
    /**
     * Batch analyze imported feedback
     */
    public function analyzeImportedFeedback($source = null)
    {
        $query = Feedback::whereNull('sentiment');
        
        if ($source) {
            $query->where('source', $source);
        }
        
        $feedbacks = $query->get();
        $analysisService = new FeedbackAnalysisService();
        
        foreach ($feedbacks as $feedback) {
            $scoreData = $analysisService->calculateScore($feedback);
            
            // Update feedback with analysis results
            $feedback->update([
                'sentiment' => $scoreData['sentiment_score'] > 0 ? 'positive' : ($scoreData['sentiment_score'] < 0 ? 'negative' : 'neutral'),
                'keyword' => $scoreData['keywords']
            ]);
            
            // Create or update score
            $feedback->score()->updateOrCreate([], [
                'sentiment_score' => $scoreData['sentiment_score'],
                'keyword_score' => $scoreData['rating_score'],
                'urgency_score' => $scoreData['urgency_score'],
                'total_score' => $scoreData['total_score']
            ]);
        }
        
        return $feedbacks->count();
    }
    
    /**
     * Create feedback from form row data
     */
    private function createFeedbackFromRow($source, $row)
    {
        // Flexible mapping - adjust indices based on your form structure
        $timestamp = $row[0] ?? null;
        $email = $row[1] ?? null;
        $content = $row[2] ?? '';
        $rating = isset($row[3]) ? (int)$row[3] : null;
        
        if (!empty($content)) {
            $metadata = [
                'timestamp' => $timestamp,
                'email' => $email,
                'imported_at' => now()
            ];
            
            Feedback::create([
                'source' => $source,
                'content' => $content,
                'rating' => $rating,
                'metadata' => json_encode($metadata)
            ]);
        }
    }
    
    /**
     * Estimate rating from Facebook reactions
     */
    private function estimateRatingFromReactions($post)
    {
        $reactions = $post['reactions']['summary']['total_count'] ?? 0;
        $comments = $post['comments']['summary']['total_count'] ?? 0;
        
        // Simple heuristic - more engagement usually means more positive
        if ($reactions > 10 || $comments > 5) {
            return 4;
        } elseif ($reactions > 5 || $comments > 2) {
            return 3;
        } elseif ($reactions > 0 || $comments > 0) {
            return 3;
        }
        
        return null;
    }
    
    /**
     * Check if service account credentials are available
     */
    private function hasServiceAccount(): bool
    {
        $keyPath = storage_path('app/google-service-account.json');
        return file_exists($keyPath);
    }
    
    /**
     * Get access token using service account
     */
    private function getServiceAccountAccessToken(): string
    {
        $client = new \Google\Client();
        $client->setAuthConfig(storage_path('app/google-service-account.json'));
        $client->setScopes(['https://www.googleapis.com/auth/spreadsheets.readonly']);
        
        // Disable SSL verification for local development
        $httpClient = new \GuzzleHttp\Client([
            'verify' => false
        ]);
        $client->setHttpClient($httpClient);
        
        $accessToken = $client->fetchAccessTokenWithAssertion();
        
        if (isset($accessToken['error'])) {
            throw new \Exception('Service account authentication failed: ' . $accessToken['error_description']);
        }
        
        return $accessToken['access_token'];
    }
}
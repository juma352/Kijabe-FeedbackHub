<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Services\FeedbackAnalysisService;
use App\Services\ExternalSourceService;
use App\Services\KijabeHospitalImportService;
use App\Services\LearnerExperienceService;
use App\Services\FeedbackStatsService;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $feedbacks = Feedback::with('score')->latest()->paginate(10);
        return view('feedback.index', compact('feedbacks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('feedback.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'source' => 'required|string|max:255',
            'content' => 'required|string',
            'metadata' => 'nullable|json',
            'rating' => 'nullable|integer|between:1,5',
        ]);

        $feedback = Feedback::create($validated);
        
        // Auto-analyze the feedback
        $this->analyzeFeedback($feedback);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Feedback received', 'id' => $feedback->id], 201);
        }

        return redirect()->route('feedback.index')->with('success', 'Feedback created and analyzed successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Feedback $feedback)
    {
        $feedback->load('score');
        return view('feedback.show', compact('feedback'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Feedback $feedback)
    {
        return view('feedback.edit', compact('feedback'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Feedback $feedback)
    {
        $validated = $request->validate([
            'source' => 'required|string|max:255',
            'content' => 'required|string',
            'metadata' => 'nullable|json',
            'rating' => 'nullable|integer|between:1,5',
            'sentiment' => 'nullable|in:positive,neutral,negative',
        ]);

        // Handle sentiment changes
        if ($request->has('sentiment') && $request->sentiment !== $feedback->sentiment) {
            $feedback->updateSentiment($request->sentiment, auth()->user());
            unset($validated['sentiment']); // Remove from validated array since it's handled by updateSentiment
        }

        $feedback->update($validated);

        return redirect()->route('feedback.management')->with('success', 'Feedback updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Feedback $feedback)
    {
        $feedback->delete();

        return redirect()->route('feedback.index')->with('success', 'Feedback deleted successfully.');
    }
    
    /**
     * Show analytics dashboard
     */
    public function analytics()
    {
        $analysisService = new FeedbackAnalysisService();
        $insights = $analysisService->getInsights();
        
        return view('feedback.analytics', compact('insights'));
    }
    
    /**
     * Show import page for external sources
     */
    public function import()
    {
        return view('feedback.import');
    }
    
    /**
     * Process import from external source
     */
    public function processImport(Request $request)
    {
        $request->validate([
            'import_type' => 'required|in:google_forms,microsoft_forms,facebook,csv,kijabe_hospital',
            'source_id' => 'required_unless:import_type,csv,kijabe_hospital|string',
            'csv_file' => 'required_if:import_type,csv,kijabe_hospital|file|mimes:csv,txt',
            'access_token' => 'required_unless:import_type,google_forms,csv,kijabe_hospital|string'
        ]);
        
        $externalService = new ExternalSourceService();
        $result = ['success' => false];
        
        switch ($request->import_type) {
            case 'google_forms':
                $result = $externalService->importFromGoogleForms($request->source_id);
                break;
            case 'microsoft_forms':
                $result = $externalService->importFromMicrosoftForms($request->source_id, $request->access_token);
                break;
            case 'facebook':
                $result = $externalService->importFromFacebook($request->source_id, $request->access_token);
                break;
            case 'csv':
                try {
                    // Ensure imports directory exists
                    $importsPath = storage_path('app/imports');
                    if (!is_dir($importsPath)) {
                        mkdir($importsPath, 0755, true);
                    }
                    
                    // Store the file
                    $file = $request->file('csv_file');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $fullPath = $importsPath . '/' . $filename;
                    
                    // Move the uploaded file
                    if ($file->move($importsPath, $filename)) {
                        $result = $externalService->importFromCsv($fullPath);
                    } else {
                        $result = ['success' => false, 'error' => 'Failed to save uploaded file'];
                    }
                } catch (\Exception $e) {
                    $result = ['success' => false, 'error' => 'File upload error: ' . $e->getMessage()];
                }
                break;
        }
        
        if ($result['success']) {
            // Auto-analyze imported feedback
            $analyzedCount = $externalService->analyzeImportedFeedback();
            
            $message = "Successfully imported {$result['count']} feedback entries and analyzed {$analyzedCount} items.";
            
            if (isset($result['warnings']) && !empty($result['warnings'])) {
                $message .= ' Warnings: ' . implode(', ', array_slice($result['warnings'], 0, 3));
                if (count($result['warnings']) > 3) {
                    $message .= ' and ' . (count($result['warnings']) - 3) . ' more.';
                }
            }
            
            return redirect()->route('feedback.index')->with('success', $message);
        } else {
            return back()->withErrors(['error' => $result['error']]);
        }
    }
    
    /**
     * Process dynamic CSV import - analyze CSV structure first
     */
    public function processDynamicCSV(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|max:10240'
        ]);

        try {
            $file = $request->file('csv_file');
            
            // Debug information
            $originalName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $mimeType = $file->getMimeType();
            
            \Log::info('Dynamic CSV Upload Debug', [
                'original_name' => $originalName,
                'file_size' => $fileSize,
                'mime_type' => $mimeType,
                'is_valid' => $file->isValid()
            ]);
            
            if (!$file->isValid()) {
                throw new \Exception('Uploaded file is not valid');
            }
            
            // Use Storage facade for more reliable file handling
            $filename = 'dynamic_import_' . time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', $originalName);
            
            // Store using Storage facade
            $success = \Storage::disk('local')->putFileAs('imports', $file, $filename);
            
            if (!$success) {
                throw new \Exception('Failed to store uploaded file via Storage facade');
            }
            
            $filePath = 'imports/' . $filename;
            $fullPath = \Storage::disk('local')->path($filePath);
            
            // Verify file was stored correctly
            if (!file_exists($fullPath)) {
                throw new \Exception('File storage verification failed - file not found at: ' . $fullPath);
            }
            
            // Additional file validation
            if (filesize($fullPath) === 0) {
                throw new \Exception('Uploaded file appears to be empty');
            }
            
            \Log::info('File stored successfully', [
                'file_path' => $filePath,
                'full_path' => $fullPath,
                'file_size_on_disk' => filesize($fullPath)
            ]);
            
            // Analyze CSV structure
            $analyzer = new \App\Services\DynamicCSVAnalyzerService();
            $analysis = $analyzer->analyzeCSV($fullPath);
            
            return view('feedback.dynamic-import', [
                'analysis' => $analysis,
                'filePath' => $filePath
            ]);
            
        } catch (\Exception $e) {
            // Clean up any uploaded file
            if (isset($filePath) && \Storage::disk('local')->exists($filePath)) {
                \Storage::disk('local')->delete($filePath);
            }
            
            \Log::error('Dynamic CSV Analysis Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors(['error' => 'Error analyzing CSV: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Process dynamic CSV import with custom mappings
     */
    public function processDynamicImport(Request $request)
    {
        $request->validate([
            'file_path' => 'required|string',
            'mappings.feedback_content' => 'required|integer',
            'source_name' => 'string|max:255'
        ]);

        $filePath = $request->input('file_path');
        $fullPath = \Storage::disk('local')->path($filePath);
        
        if (!file_exists($fullPath)) {
            return back()->withErrors(['error' => 'Upload file not found. Please upload again.']);
        }

        try {
            $analyzer = new \App\Services\DynamicCSVAnalyzerService();
            $mappings = $this->prepareMappings($request->input('mappings', []));
            
            // Add additional fields
            if ($request->has('additional_fields')) {
                $mappings['additional_fields'] = array_map(function($index) {
                    return ['column_index' => (int)$index];
                }, $request->input('additional_fields', []));
            }
            
            $result = $analyzer->processWithMappings($fullPath, $mappings);
            
            if ($result['success']) {
                $sourceName = $request->input('source_name', 'Dynamic CSV Import');
                $autoAnalyze = $request->boolean('auto_analyze', true);
                $calculateLearnerExperience = $request->boolean('calculate_learner_experience', true);
                
                $importedCount = $this->saveDynamicFeedbackData($result['data'], $sourceName, $autoAnalyze, $calculateLearnerExperience);
                
                // Clean up file
                unlink($fullPath);
                
                return redirect()->route('feedback.index')->with('success', 
                    "Successfully imported {$importedCount} feedback entries from {$sourceName}");
            } else {
                throw new \Exception('Failed to process CSV data');
            }
            
        } catch (\Exception $e) {
            // Clean up file
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
            
            return back()->withErrors(['error' => 'Import failed: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Prepare mappings from form input
     */
    private function prepareMappings(array $formMappings): array
    {
        $mappings = [];
        
        foreach ($formMappings as $field => $columnIndex) {
            if (!empty($columnIndex)) {
                $mappings[$field] = (int)$columnIndex;
            }
        }
        
        return $mappings;
    }
    
    /**
     * Save dynamic feedback data
     */
    private function saveDynamicFeedbackData(array $feedbackData, string $sourceName, bool $autoAnalyze, bool $calculateLearnerExperience): int
    {
        $count = 0;
        
        foreach ($feedbackData as $data) {
            $feedback = Feedback::create([
                'content' => $data['content'],
                'source' => $sourceName,
                'sentiment' => 'neutral',
                'sentiment_score' => 0,
                'keyword' => null,
                'category' => $data['department'] ?? null,
                'metadata' => json_encode($data['additional_data'] ?? []),
                'created_at' => $data['date'] ?? now(),
                'updated_at' => now()
            ]);
            
            // Set rating if provided
            if (isset($data['rating']) && $data['rating'] !== null) {
                $feedback->score()->create([
                    'sentiment_score' => $data['rating'],
                    'keyword_score' => $data['rating'],
                    'urgency_score' => $data['rating'],
                    'total_score' => $data['rating']
                ]);
            }
            
            // Auto-analyze if requested
            if ($autoAnalyze) {
                $this->analyzeFeedback($feedback);
            }
            
            // Calculate learner experience if requested
            if ($calculateLearnerExperience) {
                $learnerExperienceService = new \App\Services\LearnerExperienceService();
                $experienceData = $learnerExperienceService->calculateLearnerExperienceScore($feedback);
                
                $feedback->learnerExperience()->create([
                    'total_score' => $experienceData['total_score'],
                    'experience_level' => $experienceData['experience_level'],
                    'environment_score' => $experienceData['environment_score'],
                    'content_quality_score' => $experienceData['content_quality_score'],
                    'engagement_score' => $experienceData['engagement_score'],
                    'support_system_score' => $experienceData['support_system_score'],
                    'experience_data' => $experienceData
                ]);
            }
            
            $count++;
        }
        
        return $count;
    }
    
    /**
     * Bulk analyze feedback
     */
    public function bulkAnalyze()
    {
        $externalService = new ExternalSourceService();
        $count = $externalService->analyzeImportedFeedback();
        
        return redirect()->route('feedback.index')->with('success', "Analyzed {$count} feedback entries.");
    }
    
    /**
     * Bulk calculate learner experience scores
     */
    public function bulkCalculateLearnerExperience(Request $request)
    {
        $learnerExperienceService = new LearnerExperienceService();
        $recalculate = $request->input('recalculate', false);
        
        // Get feedbacks without learner experience OR all if recalculating
        if ($recalculate) {
            $feedbacks = Feedback::with('learnerExperience')->get();
        } else {
            $feedbacks = Feedback::whereDoesntHave('learnerExperience')->get();
        }
        
        $processed = 0;
        $updated = 0;
        $created = 0;
        
        foreach ($feedbacks as $feedback) {
            try {
                $experienceData = $learnerExperienceService->calculateLearnerExperienceScore($feedback);
                
                if ($feedback->learnerExperience) {
                    // Update existing
                    $feedback->learnerExperience()->update([
                        'total_score' => $experienceData['total_score'],
                        'experience_level' => $experienceData['experience_level'],
                        'environment_score' => $experienceData['environment_score'],
                        'content_quality_score' => $experienceData['content_quality_score'],
                        'engagement_score' => $experienceData['engagement_score'],
                        'support_system_score' => $experienceData['support_system_score'],
                        'experience_data' => $experienceData
                    ]);
                    $updated++;
                } else {
                    // Create new
                    $feedback->learnerExperience()->create([
                        'total_score' => $experienceData['total_score'],
                        'experience_level' => $experienceData['experience_level'],
                        'environment_score' => $experienceData['environment_score'],
                        'content_quality_score' => $experienceData['content_quality_score'],
                        'engagement_score' => $experienceData['engagement_score'],
                        'support_system_score' => $experienceData['support_system_score'],
                        'experience_data' => $experienceData
                    ]);
                    $created++;
                }
                
                $processed++;
            } catch (\Exception $e) {
                \Log::error('Error calculating learner experience for feedback ' . $feedback->id . ': ' . $e->getMessage());
            }
        }
        
        $remaining = Feedback::whereDoesntHave('learnerExperience')->count();
        
        if ($recalculate) {
            $message = "Recalculated {$processed} learner experiences ({$updated} updated). {$remaining} without experience scores.";
        } else {
            $message = "Processed {$processed} learner experiences ({$created} created). {$remaining} remaining.";
        }
        
        return redirect()->back()->with('success', $message);
    }
    
    /**
     * Show Kijabe Hospital specific analytics
     */
    public function kijabeAnalytics()
    {
        $kijabeService = new KijabeHospitalImportService();
        $categoryStats = $kijabeService->getCategoryStats();
        
        $analysisService = new FeedbackAnalysisService();
        $insights = $analysisService->getInsights();
        
        // Filter for hospital feedback only
        $hospitalFeedbacks = Feedback::whereJsonContains('metadata->hospital_department', 'Education')->get();
        
        return view('feedback.kijabe-analytics', compact('insights', 'categoryStats', 'hospitalFeedbacks'));
    }
    
    /**
     * Show learner experience analytics
     */
    public function learnerExperienceAnalytics()
    {
        $learnerExperienceService = new LearnerExperienceService();
        $experienceInsights = $learnerExperienceService->getExperienceInsights();
        
        // Get recent feedback with experience scores
        $recentExperiences = Feedback::with(['learnerExperience', 'score'])
            ->whereHas('learnerExperience')
            ->latest()
            ->limit(20)
            ->get();
            
        return view('feedback.learner-experience', compact('experienceInsights', 'recentExperiences'));
    }
    
    /**
     * Analyze individual feedback
     */
    private function analyzeFeedback(Feedback $feedback)
    {
        $analysisService = new FeedbackAnalysisService();
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
        
        // Calculate and store learner experience
        $learnerExperienceService = new LearnerExperienceService();
        $experienceData = $learnerExperienceService->calculateLearnerExperienceScore($feedback);
        
        $feedback->learnerExperience()->updateOrCreate([], [
            'total_score' => $experienceData['total_score'],
            'experience_level' => $experienceData['experience_level'],
            'environment_score' => $experienceData['environment_score'],
            'content_quality_score' => $experienceData['content_quality_score'],
            'engagement_score' => $experienceData['engagement_score'],
            'support_system_score' => $experienceData['support_system_score'],
            'experience_data' => json_encode($experienceData)
        ]);
    }

    /**
     * Update sentiment for a feedback item
     */
    public function updateSentiment(Request $request, Feedback $feedback)
    {
        $request->validate([
            'sentiment' => 'required|in:positive,neutral,negative',
        ]);

        $feedback->updateSentiment($request->sentiment, auth()->user());

        // Invalidate cache
        FeedbackStatsService::invalidate();

        return response()->json([
            'message' => 'Sentiment updated successfully',
            'feedback' => $feedback->fresh()
        ]);
    }

    /**
     * Require action for feedback items
     */
    public function requireAction(Request $request)
    {
        $request->validate([
            'feedback_ids' => 'required|array',
            'feedback_ids.*' => 'exists:feedback,id',
            'departments' => 'required|array',
            'departments.*' => 'string',
            'notes' => 'nullable|string|max:1000',
        ]);

        $feedbacks = Feedback::whereIn('id', $request->feedback_ids)->get();
        $updatedCount = 0;

        foreach ($feedbacks as $feedback) {
            $feedback->requireAction($request->departments, $request->notes);
            $updatedCount++;
        }

        // Invalidate cache
        FeedbackStatsService::invalidate();

        return response()->json([
            'message' => "{$updatedCount} feedback items marked as requiring action",
            'updated_count' => $updatedCount
        ]);
    }

    /**
     * Send bulk notifications to departments
     */
    public function sendBulkNotifications(Request $request)
    {
        $request->validate([
            'feedback_ids' => 'required|array',
            'feedback_ids.*' => 'exists:feedback,id',
            'departments' => 'required|array',
            'departments.*' => 'string',
            'custom_message' => 'nullable|string|max:1000',
        ]);

        $actionService = app(\App\Services\FeedbackActionService::class);
        $result = $actionService->sendBulkNotifications(
            $request->feedback_ids,
            $request->departments,
            $request->custom_message
        );

        if ($result['success']) {
            return response()->json([
                'message' => $result['message'],
                'details' => $result
            ]);
        } else {
            return response()->json([
                'message' => $result['message']
            ], 422);
        }
    }

    /**
     * Get suggested departments for feedback
     */
    public function suggestDepartments(Feedback $feedback)
    {
        $actionService = app(\App\Services\FeedbackActionService::class);
        $suggestions = $actionService->suggestDepartments($feedback);
        $urgencyAnalysis = $actionService->analyzeFeedbackUrgency($feedback);

        return response()->json([
            'suggested_departments' => $suggestions,
            'urgency_analysis' => $urgencyAnalysis,
            'available_departments' => Feedback::getAvailableDepartments()
        ]);
    }

    /**
     * Get action report
     */
    public function getActionReport(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $actionService = app(\App\Services\FeedbackActionService::class);
        $report = $actionService->generateActionReport($startDate, $endDate);

        return response()->json($report);
    }

    /**
     * Feedback management page with enhanced features
     */
    public function management()
    {
        $feedbacks = Feedback::with('score')
            ->latest()
            ->paginate(10);

        $departments = Feedback::getAvailableDepartments();
        
        // Get cached summary statistics
        $stats = FeedbackStatsService::getStats();

        return view('feedback.management', compact('feedbacks', 'departments', 'stats'));
    }
}


<?php

use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicFormController;
use App\Http\Controllers\RoleController;
use App\Models\Feedback;
use App\Services\FeedbackAnalysisService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $recentFeedbacks = Feedback::with('score')->latest()->limit(5)->get();
    $analysisService = new FeedbackAnalysisService();
    $insights = $analysisService->getInsights();
    
    // Extract specific data for dashboard compatibility
    $totalFeedbacks = $insights['total_feedbacks'];
    $averageRating = $insights['avg_rating'];
    $sentimentCounts = $insights['sentiment_distribution'];
    
    return view('dashboard', compact('recentFeedbacks', 'totalFeedbacks', 'averageRating', 'sentimentCounts'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Feedback routes
    Route::resource('feedback', FeedbackController::class);
    Route::get('feedback-analytics', [FeedbackController::class, 'analytics'])->name('feedback.analytics');
    Route::get('feedback-import', [FeedbackController::class, 'import'])->name('feedback.import');
    Route::post('feedback-import', [FeedbackController::class, 'processImport'])->name('feedback.import.process');
    Route::post('feedback-dynamic-csv', [FeedbackController::class, 'processDynamicCSV'])->name('feedback.dynamic.csv');
    Route::post('feedback-dynamic-import', [FeedbackController::class, 'processDynamicImport'])->name('feedback.dynamic.import.process');
    Route::post('feedback-bulk-analyze', [FeedbackController::class, 'bulkAnalyze'])->name('feedback.bulk.analyze');
    Route::post('feedback-bulk-learner-experience', [FeedbackController::class, 'bulkCalculateLearnerExperience'])->name('feedback.bulk.learner.experience');
    Route::get('learner-experience', [FeedbackController::class, 'learnerExperienceAnalytics'])->name('feedback.learner.experience');
    Route::get('sample-csv', function () {
        $filePath = storage_path('app/sample_feedback.csv');
        return response()->download($filePath, 'sample_feedback.csv');
    })->name('sample.csv');
    
    // Enhanced Feedback Management Routes
    Route::get('feedback-management', [FeedbackController::class, 'management'])->name('feedback.management');
    Route::put('feedback/{feedback}/sentiment', [FeedbackController::class, 'updateSentiment'])->name('feedback.update.sentiment');
    Route::post('feedback/require-action', [FeedbackController::class, 'requireAction'])->name('feedback.require.action');
    Route::post('feedback/bulk-notifications-preview', [FeedbackController::class, 'previewBulkNotifications'])->name('feedback.bulk.notifications.preview');
    Route::post('feedback/bulk-notifications', [FeedbackController::class, 'sendBulkNotifications'])->name('feedback.bulk.notifications');
    Route::get('feedback/{feedback}/suggest-departments', [FeedbackController::class, 'suggestDepartments'])->name('feedback.suggest.departments');
    Route::get('feedback/action-report', [FeedbackController::class, 'getActionReport'])->name('feedback.action.report');
    Route::get('feedback/{feedback}/resolution-time', [FeedbackController::class, 'getResolutionTime'])->name('feedback.resolution.time');
    Route::post('feedback/{feedback}/mark-resolved', [FeedbackController::class, 'markAsResolved'])->name('feedback.mark.resolved');
    
    // Form Builder Routes (authenticated users)
    Route::resource('forms', FormController::class);
    Route::post('forms/{form}/toggle-status', [FormController::class, 'toggleStatus'])->name('forms.toggle.status');
    Route::post('forms/{form}/duplicate', [FormController::class, 'duplicate'])->name('forms.duplicate');
    Route::get('forms/{form}/analytics', [FormController::class, 'analytics'])->name('forms.analytics');
    Route::get('forms/{form}/responses', [FormController::class, 'responses'])->name('forms.responses');
    Route::get('forms/{form}/preview', [PublicFormController::class, 'preview'])->name('forms.preview');
    Route::get('forms/builder-preview', [PublicFormController::class, 'builderPreview'])->name('forms.builder-preview');

    // Role and Permission Management Routes (admin only)
    Route::middleware('permission:manage_roles')->group(function () {
        Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    });

    Route::middleware('permission:manage_users')->group(function () {
        Route::get('users-management', [RoleController::class, 'manageUsers'])->name('users.management');
        Route::post('users/create', [RoleController::class, 'createUser'])->name('users.create');
        Route::put('users/{user}/role', [RoleController::class, 'updateUserRole'])->name('users.update.role');
        
        // Department management
        Route::get('departments', [\App\Http\Controllers\DepartmentHeadController::class, 'index'])->name('departments.index');
        Route::post('departments', [\App\Http\Controllers\DepartmentHeadController::class, 'store'])->name('departments.store');
        Route::put('departments/{departmentHead}', [\App\Http\Controllers\DepartmentHeadController::class, 'update'])->name('departments.update');
    });
});

// Public Form Routes (no authentication required)
Route::get('form/{token}', [PublicFormController::class, 'show'])->name('forms.public.show');
Route::post('form/{token}', [PublicFormController::class, 'submit'])->name('forms.public.submit');
Route::get('form/{token}/success', [PublicFormController::class, 'success'])->name('forms.public.success');

require __DIR__.'/auth.php';



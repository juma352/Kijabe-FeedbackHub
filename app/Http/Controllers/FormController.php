<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FormController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Admin can see all forms, users can see only their own
        if ($user->isAdmin()) {
            $forms = Form::with('user')->latest()->paginate(10);
        } else {
            $forms = $user->forms()->latest()->paginate(10);
        }

        return view('forms.index', compact('forms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Form::getDepartments();
        $subDepartments = [];
        
        return view('forms.create', compact('departments', 'subDepartments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'fields' => 'required|array|min:1',
            'expires_at' => 'nullable|date|after:now',
            'department' => 'required|in:kchs,research,gme,cpd',
            'department_subdivision' => 'required|string',
        ]);

        $form = auth()->user()->forms()->create([
            'title' => $request->title,
            'description' => $request->description,
            'fields' => $request->fields,
            'expires_at' => $request->expires_at,
            'department' => $request->department,
            'department_subdivision' => $request->department_subdivision,
            'settings' => $request->settings ?? [],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Form created successfully!',
            'form' => $form,
            'redirect' => route('forms.show', $form)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Form $form)
    {
        // Check if user can view this form
        if (!auth()->user()->isAdmin() && $form->user_id !== auth()->id()) {
            abort(403);
        }

        $form->load(['responses' => function($query) {
            $query->latest()->take(10);
        }]);

        return view('forms.show', compact('form'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Form $form)
    {
        // Check if user can edit this form
        if (!auth()->user()->isAdmin() && $form->user_id !== auth()->id()) {
            abort(403);
        }

        return view('forms.edit', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Form $form)
    {
        // Check if user can update this form
        if (!auth()->user()->isAdmin() && $form->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'fields' => 'required|array|min:1',
            'is_active' => 'boolean',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $form->update([
            'title' => $request->title,
            'description' => $request->description,
            'fields' => $request->fields,
            'is_active' => $request->has('is_active'),
            'expires_at' => $request->expires_at,
            'settings' => $request->settings ?? [],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Form updated successfully!',
            'form' => $form
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Form $form)
    {
        // Check if user can delete this form
        if (!auth()->user()->isAdmin() && $form->user_id !== auth()->id()) {
            abort(403);
        }

        $form->delete();

        return response()->json([
            'success' => true,
            'message' => 'Form deleted successfully!'
        ]);
    }

    /**
     * Toggle form status
     */
    public function toggleStatus(Form $form)
    {
        // Check if user can modify this form
        if (!auth()->user()->isAdmin() && $form->user_id !== auth()->id()) {
            abort(403);
        }

        $form->update(['is_active' => !$form->is_active]);

        return response()->json([
            'success' => true,
            'message' => $form->is_active ? 'Form activated!' : 'Form deactivated!',
            'is_active' => $form->is_active
        ]);
    }

    /**
     * Duplicate a form
     */
    public function duplicate(Form $form)
    {
        // Only owner or admin can duplicate
        if (!auth()->user()->isAdmin() && $form->user_id !== auth()->id()) {
            abort(403);
        }

        $copy = $form->replicate(['share_token', 'slug']);
        $copy->title = $form->title . ' (Copy)';
        $copy->slug = Str::slug($copy->title . '-' . Str::random(6));
        $copy->share_token = Str::random(32);
        $copy->is_active = false; // start as inactive to avoid accidental use
        $copy->created_at = now();
        $copy->updated_at = now();
        $copy->save();

        return response()->json([
            'success' => true,
            'message' => 'Form duplicated successfully. You can edit and activate it now.',
            'form_id' => $copy->id,
            'edit_url' => route('forms.edit', $copy),
            'show_url' => route('forms.show', $copy),
        ]);
    }

    /**
     * Get form analytics
     */
    public function analytics(Form $form)
    {
        // Check if user can view this form
        if (!auth()->user()->isAdmin() && $form->user_id !== auth()->id()) {
            abort(403);
        }

        $responses = $form->responses()->get();
        $analytics = $this->generateAnalytics($form, $responses);

        return view('forms.analytics', compact('form', 'analytics'));
    }

    /**
     * List form responses with pagination
     */
    public function responses(Form $form)
    {
        // Only owner or admin can view full responses
        if (!auth()->user()->isAdmin() && $form->user_id !== auth()->id()) {
            abort(403);
        }

        $responses = $form->responses()
            ->latest('submitted_at')
            ->paginate(20);

        return view('forms.responses', compact('form', 'responses'));
    }

    /**
     * Generate analytics for form
     */
    private function generateAnalytics(Form $form, $responses)
    {
        $analytics = [
            'total_responses' => $responses->count(),
            'response_rate' => 0, // This would need view tracking
            'completion_time' => '~2 minutes', // This would need timestamp tracking
            'field_analytics' => []
        ];

        // Analyze each field
        foreach ($form->fields as $field) {
            $fieldKey = $field['key'];
            $fieldType = $field['type'];
            
            $fieldResponses = $responses->filter(function($response) use ($fieldKey) {
                return $response->hasField($fieldKey);
            });

            $fieldAnalysis = [
                'field' => $field,
                'response_count' => $fieldResponses->count(),
                'completion_rate' => $responses->count() > 0 ? 
                    round(($fieldResponses->count() / $responses->count()) * 100, 1) : 0,
            ];

            // Type-specific analysis
            switch ($fieldType) {
                case 'rating':
                case 'number':
                    $values = $fieldResponses->map(function($response) use ($fieldKey) {
                        return (float) $response->getFieldResponse($fieldKey);
                    })->filter();
                    
                    if ($values->count() > 0) {
                        $fieldAnalysis['average'] = round($values->average(), 2);
                        $fieldAnalysis['min'] = $values->min();
                        $fieldAnalysis['max'] = $values->max();
                    }
                    break;
                    
                case 'select':
                case 'radio':
                    $valueCounts = $fieldResponses
                        ->groupBy(function($response) use ($fieldKey) {
                            return $response->getFieldResponse($fieldKey);
                        })
                        ->map->count();
                    
                    $fieldAnalysis['value_distribution'] = $valueCounts;
                    break;
                    
                case 'checkbox':
                    $allValues = [];
                    $fieldResponses->each(function($response) use ($fieldKey, &$allValues) {
                        $value = $response->getFieldResponse($fieldKey);
                        if (is_array($value)) {
                            $allValues = array_merge($allValues, $value);
                        }
                    });
                    
                    $valueCounts = collect($allValues)->countBy();
                    $fieldAnalysis['value_distribution'] = $valueCounts;
                    break;
            }

            $analytics['field_analytics'][] = $fieldAnalysis;
        }

        return $analytics;
    }
}

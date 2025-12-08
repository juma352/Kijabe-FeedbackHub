<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormResponse;
use Illuminate\Http\Request;

class PublicFormController extends Controller
{
    /**
     * Show public form
     */
    public function show($token)
    {
        $form = Form::where('share_token', $token)
            ->where('is_active', true)
            ->firstOrFail();

        // Check if form is expired
        if ($form->isExpired()) {
            abort(410, 'This form has expired and is no longer accepting responses.');
        }

        return view('forms.public.show', compact('form'));
    }

    /**
     * Submit response to public form
     */
    public function submit(Request $request, $token)
    {
        $form = Form::where('share_token', $token)
            ->where('is_active', true)
            ->firstOrFail();

        // Check if form can receive responses
        if (!$form->canReceiveResponses()) {
            return response()->json([
                'success' => false,
                'message' => 'This form is no longer accepting responses.'
            ], 410);
        }

        // Validate the form fields dynamically
        $rules = $this->buildValidationRules($form->fields);
        $request->validate($rules);

        // Create the response
        $formResponse = FormResponse::create([
            'form_id' => $form->id,
            'responses' => $request->only(array_column($form->fields, 'key')),
            'respondent_email' => $request->input('_respondent_email'),
            'respondent_name' => $request->input('_respondent_name'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'submitted_at' => now(),
        ]);

        // Clear any stored draft
        session()->forget("form_draft_{$form->share_token}");

        // If this is an AJAX request, return JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your response has been submitted successfully.',
                'redirect' => route('forms.public.success', ['token' => $form->share_token])
            ]);
        }

        // Otherwise redirect to success page
        return redirect()->route('forms.public.success', ['token' => $form->share_token]);
    }

    /**
     * Build validation rules from form fields
     */
    private function buildValidationRules($fields)
    {
        $rules = [];

        foreach ($fields as $field) {
            $fieldRules = [];
            $key = $field['key'];

            // Required field
            if ($field['required'] ?? false) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }

            // Type-specific rules
            switch ($field['type']) {
                case 'email':
                    $fieldRules[] = 'email';
                    break;

                case 'number':
                    $fieldRules[] = 'numeric';
                    if (isset($field['min'])) {
                        $fieldRules[] = 'min:' . $field['min'];
                    }
                    if (isset($field['max'])) {
                        $fieldRules[] = 'max:' . $field['max'];
                    }
                    break;

                case 'rating':
                    $fieldRules[] = 'numeric';
                    $fieldRules[] = 'min:1';
                    $fieldRules[] = 'max:' . ($field['max_rating'] ?? 5);
                    break;

                case 'text':
                case 'textarea':
                    if (isset($field['min_length'])) {
                        $fieldRules[] = 'min:' . $field['min_length'];
                    }
                    if (isset($field['max_length'])) {
                        $fieldRules[] = 'max:' . $field['max_length'];
                    }
                    break;

                case 'file':
                    $fieldRules[] = 'file';
                    if (isset($field['max_size'])) {
                        $fieldRules[] = 'max:' . $field['max_size'];
                    }
                    if (isset($field['allowed_types'])) {
                        $fieldRules[] = 'mimes:' . implode(',', $field['allowed_types']);
                    }
                    break;

                case 'checkbox':
                    $fieldRules[] = 'array';
                    break;

                case 'select':
                case 'radio':
                    if (isset($field['options'])) {
                        $validOptions = array_column($field['options'], 'value');
                        $fieldRules[] = 'in:' . implode(',', $validOptions);
                    }
                    break;
            }

            $rules[$key] = implode('|', $fieldRules);
        }

        return $rules;
    }

    /**
     * Show success page after form submission
     */
    public function success($token)
    {
        // Verify the token exists (optional - just for security)
        $form = Form::where('share_token', $token)->first();
        
        if (!$form) {
            abort(404);
        }

        return view('forms.public.success');
    }

    /**
     * Preview form (for testing)
     */
    public function preview(Form $form)
    {
        // Only form owner or admin can preview
        if (!auth()->check() || (!auth()->user()->isAdmin() && $form->user_id !== auth()->id())) {
            abort(403);
        }

        return view('forms.public.preview', compact('form'));
    }

    /**
     * Preview form from builder
     */
    public function builderPreview()
    {
        if (!auth()->check()) {
            abort(403);
        }

        return view('forms.public.builder-preview');
    }
}

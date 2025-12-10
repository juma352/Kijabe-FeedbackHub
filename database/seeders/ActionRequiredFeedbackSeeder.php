<?php

namespace Database\Seeders;

use App\Models\Feedback;
use App\Models\Score;
use App\Services\FeedbackAnalysisService;
use Illuminate\Database\Seeder;

class ActionRequiredFeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $feedbacks = [
            [
                'source' => 'Email',
                'content' => 'The training materials provided were completely outdated and irrelevant to our current learning objectives. The instructors seemed unprepared and did not address our specific concerns about the curriculum adequacy.',
                'rating' => 1,
                'sentiment' => 'negative',
                'keyword' => 'outdated, irrelevant, unprepared, curriculum',
                'metadata' => ['department' => 'GME', 'program' => 'Residency', 'trainer' => 'Unprepared'],
                'action_required' => true,
                'departments' => ['gme', 'quality_improvement'],
                'action_notes' => 'Curriculum review needed. Training materials must be updated.',
            ],
            [
                'source' => 'Feedback Form',
                'content' => 'The learning environment in the simulation center is very poor. Equipment is not properly maintained, lighting is inadequate, and the space is too cramped for comfortable learning. This is affecting student engagement and learning outcomes.',
                'rating' => 1,
                'sentiment' => 'negative',
                'keyword' => 'poor environment, maintenance, inadequate lighting, cramped',
                'metadata' => ['department' => 'GME', 'location' => 'Simulation Center'],
                'action_required' => true,
                'departments' => ['simulation_manager', 'quality_assurance'],
                'action_notes' => 'Facility assessment and maintenance required urgently.',
            ],
            [
                'source' => 'Survey',
                'content' => 'The content quality in the e-learning platform is inconsistent. Some modules are well-designed while others are poorly structured with unclear learning objectives. There is also a lack of interactive elements which reduces engagement.',
                'rating' => 2,
                'sentiment' => 'negative',
                'keyword' => 'inconsistent content, poorly structured, unclear objectives, lack of interactivity',
                'metadata' => ['department' => 'CPD', 'platform' => 'e-learning', 'module_count' => 25],
                'action_required' => true,
                'departments' => ['cpd_coordinator', 'elearning_coordinator'],
                'action_notes' => 'Content audit and redesign of underperforming modules needed.',
            ],
            [
                'source' => 'Direct Feedback',
                'content' => 'The support system for learners is inadequate. When I had questions about the course material, it took over a week to get a response from the support team. This delay significantly impacted my learning experience.',
                'rating' => 2,
                'sentiment' => 'negative',
                'keyword' => 'inadequate support, slow response, delay, impact',
                'metadata' => ['department' => 'CPD', 'support_queue' => 'email'],
                'action_required' => true,
                'departments' => ['cpd_coordinator', 'quality_improvement'],
                'action_notes' => 'Support response time SLA needs review and improvement.',
            ],
            [
                'source' => 'Feedback Form',
                'content' => 'The research program lacks clear guidance on project expectations and timelines. Supervisors are difficult to reach and do not provide regular feedback on progress. This is causing confusion and delaying project completion.',
                'rating' => 2,
                'sentiment' => 'negative',
                'keyword' => 'lacks guidance, unclear expectations, difficult to reach, no feedback',
                'metadata' => ['department' => 'Research', 'program' => 'PhD'],
                'action_required' => true,
                'departments' => ['research_coordinator', 'quality_assurance'],
                'action_notes' => 'Supervisor training and project management framework needed.',
            ],
            [
                'source' => 'Email',
                'content' => 'The clinical training rotations are poorly coordinated. There are conflicts in the schedule, learners are sometimes assigned to units where they do not have adequate supervision, and there is no clear feedback mechanism for performance evaluation.',
                'rating' => 1,
                'sentiment' => 'negative',
                'keyword' => 'poorly coordinated, schedule conflicts, inadequate supervision, no feedback',
                'metadata' => ['department' => 'KCHS', 'rotation_type' => 'Clinical'],
                'action_required' => true,
                'departments' => ['quality_assurance', 'internship_coordinator'],
                'action_notes' => 'Rotation scheduling system overhaul and supervision protocol needed.',
            ],
            [
                'source' => 'Survey',
                'content' => 'The learning environment lacks diversity and inclusion initiatives. Some learners from minority backgrounds report feeling unwelcome and not hearing relevant perspectives in class discussions. This is a serious concern for equity in medical education.',
                'rating' => 2,
                'sentiment' => 'negative',
                'keyword' => 'lacks diversity, unwelcome, exclusion, equity concerns',
                'metadata' => ['department' => 'KCHS', 'focus' => 'Inclusion'],
                'action_required' => true,
                'departments' => ['quality_assurance', 'customer_satisfaction_chair'],
                'action_notes' => 'DEI audit and strategy development required.',
            ],
            [
                'source' => 'Direct Interview',
                'content' => 'The international visitor program lacks essential information and preparation materials. Visitors are not given adequate orientation about the institution, there are no structured learning activities, and the housing arrangements are often substandard.',
                'rating' => 2,
                'sentiment' => 'negative',
                'keyword' => 'lacks information, inadequate orientation, no structure, substandard housing',
                'metadata' => ['department' => 'GME', 'program' => 'Visitor Program'],
                'action_required' => true,
                'departments' => ['visitors_gme_coordinator', 'quality_improvement'],
                'action_notes' => 'Visitor program documentation and logistics assessment needed.',
            ],
            [
                'source' => 'Feedback Form',
                'content' => 'The simulation equipment is outdated and does not reflect current clinical practice. This makes the training less relevant and learners struggle to transfer skills to real clinical settings. Investment in modern equipment is critical.',
                'rating' => 2,
                'sentiment' => 'negative',
                'keyword' => 'outdated equipment, not current practice, skill transfer issues',
                'metadata' => ['department' => 'GME', 'area' => 'Simulation'],
                'action_required' => true,
                'departments' => ['simulation_manager', 'quality_assurance'],
                'action_notes' => 'Equipment upgrade and maintenance plan required.',
            ],
            [
                'source' => 'Email',
                'content' => 'The quality assurance processes are not transparent. Learners are not informed about assessment criteria and there are inconsistencies in how evaluations are conducted across different supervisors. This raises concerns about fairness.',
                'rating' => 2,
                'sentiment' => 'negative',
                'keyword' => 'not transparent, unclear criteria, inconsistencies, fairness concerns',
                'metadata' => ['department' => 'Quality Assurance', 'issue' => 'Assessment'],
                'action_required' => true,
                'departments' => ['quality_assurance', 'quality_improvement'],
                'action_notes' => 'Assessment framework standardization and documentation required.',
            ],
        ];

        $analysisService = new FeedbackAnalysisService();

        foreach ($feedbacks as $feedbackData) {
            $feedback = Feedback::create($feedbackData);
            
            // Use the analysis service for accurate scoring
            $scoreData = $analysisService->calculateScore($feedback);
            
            // Update feedback with refined analysis
            $feedback->update([
                'sentiment' => $scoreData['sentiment_score'] > 0 ? 'positive' : ($scoreData['sentiment_score'] < 0 ? 'negative' : 'neutral'),
                'keyword' => $scoreData['keywords']
            ]);
            
            // Create score record
            Score::create([
                'feedback_id' => $feedback->id,
                'sentiment_score' => $scoreData['sentiment_score'],
                'keyword_score' => $scoreData['rating_score'],
                'urgency_score' => $scoreData['urgency_score'],
                'total_score' => $scoreData['total_score'],
            ]);
        }
    }
}

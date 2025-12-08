<?php

namespace Database\Seeders;

use App\Models\Feedback;
use App\Models\Score;
use App\Services\FeedbackAnalysisService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $feedbacks = [
            [
                'source' => 'Website',
                'content' => 'Your customer service was absolutely amazing! The representative was very helpful and solved my issue quickly. I will definitely recommend your company to others.',
                'rating' => 5,
                'sentiment' => 'positive',
                'keyword' => 'customer service, amazing, helpful, recommend',
                'metadata' => json_encode(['customer_id' => 1001, 'product' => 'Premium Support', 'channel' => 'live_chat'])
            ],
            [
                'source' => 'Email',
                'content' => 'I am very disappointed with the recent update. The new interface is confusing and I cannot find the features I need. Please revert to the old design.',
                'rating' => 2,
                'sentiment' => 'negative',
                'keyword' => 'disappointed, confusing, revert, old design',
                'metadata' => json_encode(['customer_id' => 1002, 'product' => 'Mobile App', 'version' => '2.1.0'])
            ],
            [
                'source' => 'WhatsApp',
                'content' => 'The delivery was on time and the product quality is good. However, the packaging could be improved as the box was slightly damaged.',
                'rating' => 4,
                'sentiment' => 'neutral',
                'keyword' => 'delivery, on time, quality, packaging, damaged',
                'metadata' => json_encode(['customer_id' => 1003, 'order_number' => 'ORD-123456', 'product' => 'Electronics'])
            ],
            [
                'source' => 'Twitter',
                'content' => 'Wow! Just received my order and it exceeded all my expectations. The quality is outstanding and the price is very reasonable. Thank you!',
                'rating' => 5,
                'sentiment' => 'positive',
                'keyword' => 'exceeded expectations, outstanding, reasonable price, thank you',
                'metadata' => json_encode(['customer_id' => 1004, 'order_number' => 'ORD-123457', 'social_handle' => '@happycustomer'])
            ],
            [
                'source' => 'Phone Call',
                'content' => 'I had to wait 45 minutes on hold before speaking to someone. This is unacceptable for a premium service. You need to hire more staff.',
                'rating' => 1,
                'sentiment' => 'negative',
                'keyword' => 'wait, 45 minutes, unacceptable, premium service, hire staff',
                'metadata' => json_encode(['customer_id' => 1005, 'call_duration' => 52, 'wait_time' => 45])
            ],
            [
                'source' => 'Facebook',
                'content' => 'The new feature you added is exactly what I was looking for. It makes my workflow so much easier. Great job on listening to customer feedback!',
                'rating' => 5,
                'sentiment' => 'positive',
                'keyword' => 'new feature, workflow, easier, great job, customer feedback',
                'metadata' => json_encode(['customer_id' => 1006, 'feature' => 'bulk_export', 'social_handle' => 'business_user_99'])
            ],
            [
                'source' => 'Survey',
                'content' => 'The checkout process is straightforward, but I would like to see more payment options available, especially cryptocurrency payments.',
                'rating' => 3,
                'sentiment' => 'neutral',
                'keyword' => 'checkout, straightforward, payment options, cryptocurrency',
                'metadata' => json_encode(['customer_id' => 1007, 'survey_id' => 'SUR-2024-001', 'completion_rate' => 100])
            ],
            [
                'source' => 'Instagram',
                'content' => 'Love the aesthetics of your new product line! The colors and design are perfect. When will you be releasing more variations?',
                'rating' => 4,
                'sentiment' => 'positive',
                'keyword' => 'love, aesthetics, colors, design, perfect, variations',
                'metadata' => json_encode(['customer_id' => 1008, 'product_line' => 'summer_collection', 'social_handle' => '@design_lover'])
            ]
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

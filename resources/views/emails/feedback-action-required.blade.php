<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Action Required: Feedback Issues</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        .summary-box {
            background: white;
            border-left: 5px solid #e74c3c;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .summary-stats {
            display: flex;
            justify-content: space-around;
            text-align: center;
            margin: 20px 0;
        }
        .stat {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            min-width: 120px;
        }
        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #e74c3c;
        }
        .stat-label {
            font-size: 14px;
            color: #7f8c8d;
            text-transform: uppercase;
        }
        .feedback-item {
            background: white;
            margin: 15px 0;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #e74c3c;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .feedback-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .feedback-id {
            font-weight: bold;
            color: #2c3e50;
            font-size: 16px;
        }
        .sentiment-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .sentiment-negative {
            background: #fee;
            color: #e74c3c;
            border: 1px solid #f5c6cb;
        }
        .sentiment-neutral {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        .sentiment-positive {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .feedback-content {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            font-style: italic;
            margin: 15px 0;
        }
        .feedback-meta {
            font-size: 12px;
            color: #6c757d;
            margin-top: 10px;
        }
        .custom-message {
            background: #e8f4fd;
            border: 1px solid #bee5eb;
            border-radius: 5px;
            padding: 20px;
            margin: 20px 0;
        }
        .custom-message h3 {
            margin-top: 0;
            color: #0c5460;
        }
        .departments-list {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .department-tag {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 12px;
            margin: 3px;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            background: white;
            border-radius: 8px;
            color: #6c757d;
            font-size: 14px;
        }
        .urgent-notice {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }
        .urgent-notice strong {
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🚨 Action Required: Feedback Issues</h1>
        <p>{{ $totalCount }} feedback item(s) require immediate attention</p>
        <p><strong>{{ $actionRequiredDate }}</strong></p>
    </div>

    @if ($negativeCount > 0)
    <div class="urgent-notice">
        <strong>⚠️ High Priority:</strong> {{ $negativeCount }} of these items have negative sentiment and require immediate action
    </div>
    @endif

    <div class="summary-stats">
        <div class="stat">
            <div class="stat-number">{{ $totalCount }}</div>
            <div class="stat-label">Total Items</div>
        </div>
        <div class="stat">
            <div class="stat-number">{{ $negativeCount }}</div>
            <div class="stat-label">Negative</div>
        </div>
        <div class="stat">
            <div class="stat-number">{{ $totalCount - $negativeCount }}</div>
            <div class="stat-label">Other</div>
        </div>
    </div>

    @if ($customMessage)
    <div class="custom-message">
        <h3>📝 Additional Message</h3>
        <p>{{ $customMessage }}</p>
    </div>
    @endif

    <div class="departments-list">
        <h3>🎯 Departments Involved</h3>
        @foreach ($departments as $dept)
            <span class="department-tag">{{ ucwords(str_replace('_', ' ', $dept)) }}</span>
        @endforeach
    </div>

    <h2>📋 Feedback Items Requiring Action</h2>

    @foreach ($feedbacks as $feedback)
    <div class="feedback-item">
        <div class="feedback-header">
            <div class="feedback-id">Feedback #{{ $feedback->id }}</div>
            <span class="sentiment-badge sentiment-{{ $feedback->sentiment }}">
                {{ ucfirst($feedback->sentiment) }}
                @if ($feedback->sentiment_manually_edited)
                    (Manually Edited)
                @endif
            </span>
        </div>

        <div class="feedback-content">
            "{{ $feedback->content }}"
        </div>

        <div class="feedback-meta">
            <strong>Source:</strong> {{ $feedback->source }} | 
            <strong>Date:</strong> {{ $feedback->created_at->format('M j, Y g:i A') }} |
            @if ($feedback->departments)
                <strong>Departments:</strong> {{ implode(', ', array_map('ucwords', $feedback->departments)) }}
            @endif
        </div>

        @if ($feedback->action_notes)
        <div style="margin-top: 10px; padding: 10px; background: #f8f9fa; border-radius: 3px; font-size: 13px;">
            <strong>Notes:</strong> {{ $feedback->action_notes }}
        </div>
        @endif
    </div>
    @endforeach

    <div class="summary-box">
        <h3>📌 Next Steps</h3>
        <ul>
            <li><strong>Review each feedback item carefully</strong> and assess the situation</li>
            <li><strong>Take appropriate corrective action</strong> for negative feedback</li>
            <li><strong>Follow up with patients/customers</strong> where necessary</li>
            <li><strong>Document actions taken</strong> in the feedback system</li>
            <li><strong>Implement preventive measures</strong> to avoid similar issues</li>
        </ul>
    </div>

    <div class="footer">
        <p><strong>This is an automated notification from the Feedback Management System</strong></p>
        <p>Please log into the system to mark actions as completed and add follow-up notes.</p>
        <p>If you have questions about this notification, please contact the system administrator.</p>
    </div>
</body>
</html>
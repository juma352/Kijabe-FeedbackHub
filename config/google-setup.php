<?php

/*
|--------------------------------------------------------------------------
| Google Forms Setup Instructions
|--------------------------------------------------------------------------
|
| To integrate Google Forms with your feedback system:
|
| 1. Get Google API Key:
|    - Go to https://console.developers.google.com/
|    - Create a new project or select existing one
|    - Enable Google Sheets API
|    - Create credentials (API Key)
|    - Add the API key to your .env: GOOGLE_API_KEY=your_api_key_here
|
| 2. Google Sheets URL Format:
|    - Your Google Sheets URL should be: 
|      https://docs.google.com/spreadsheets/d/SPREADSHEET_ID/edit
|    - Extract the SPREADSHEET_ID from the URL
|    - Use only the ID, not the full URL
|
| 3. SSL Certificate Issues (Windows/WAMP):
|    - Download CA bundle: curl -o storage/app/cacert.pem https://curl.se/ca/cacert.pem
|    - Add to .env: CURL_CA_BUNDLE=storage/app/cacert.pem
|    - Or disable SSL verification in development (already implemented)
|
| 4. Your Spreadsheet ID:
|    From: https://docs.google.com/spreadsheets/d/1I0UnOwpEYJG32x703BL9G-yyVHCk0yiDVwYhxuaMTpY/edit#gid=1587561639
|    ID is: 1I0UnOwpEYJG32x703BL9G-yyVHCk0yiDVwYhxuaMTpY
|
| 5. Make your Google Sheet public:
|    - Open your Google Sheet
|    - Click Share > Get Link
|    - Change to "Anyone with the link can view"
|    - OR use service account authentication for private sheets
|
*/

return [
    'google_forms' => [
        'your_spreadsheet_id' => '1I0UnOwpEYJG32x703BL9G-yyVHCk0yiDVwYhxuaMTpY',
        'range' => 'Form Responses 1',
        'instructions' => 'Add GOOGLE_API_KEY to .env and make sheet public or use service account'
    ]
];
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Success - Form Submitted</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen flex flex-col items-center justify-center">
            <div class="max-w-md w-full bg-white shadow-lg rounded-lg p-8 text-center">
                <!-- Success Icon -->
                <div class="mb-6">
                    <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100">
                        <i class="fas fa-check text-green-600 text-3xl"></i>
                    </div>
                </div>

                <!-- Success Message -->
                <h1 class="text-2xl font-bold text-gray-900 mb-4">Thank You!</h1>
                <p class="text-gray-600 mb-6">Your response has been submitted successfully. We appreciate you taking the time to provide your feedback.</p>

                <!-- Additional Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        <p class="text-sm text-blue-800">
                            Your response has been recorded and the form owner will be notified.
                        </p>
                    </div>
                </div>

                <!-- Action Button -->
                <button onclick="window.close()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-md transition duration-200">
                    <i class="fas fa-times mr-2"></i>
                    Close Window
                </button>

                <!-- Footer -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-500">
                        Powered by <span class="font-semibold text-blue-600">Feedback Hub</span>
                    </p>
                </div>
            </div>
        </div>

        <script>
            // Auto-close after 5 seconds if opened in a popup
            if (window.opener) {
                setTimeout(() => {
                    window.close();
                }, 5000);
            }

            // Clear any saved draft for this form
            const urlParams = new URLSearchParams(window.location.search);
            const token = urlParams.get('token');
            if (token) {
                localStorage.removeItem(`form_draft_${token}`);
            }
        </script>
    </body>
</html>
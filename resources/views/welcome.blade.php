<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GitHub Login</title>
    
    <!-- Add Tailwind CDN for quick styling -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md">
            <h1 class="text-2xl font-bold mb-6 text-center">Welcome</h1>
            
            <a href="{{ route('github.login') }}" class="flex items-center justify-center gap-2 bg-gray-900 text-white px-6 py-3 rounded-md hover:bg-gray-700 transition-colors">
                Login with GitHub
            </a>
        </div>
    </div>
</body>
</html>
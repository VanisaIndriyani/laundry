<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Laundry' }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#0b1a2d] text-white">
    <header class="sticky top-0 z-50 bg-[#0d233b] shadow">
        <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="/" class="font-semibold tracking-wide">Laundry Tracking</a>
            <nav class="flex items-center gap-4 text-sm">
                <a href="/" class="hover:underline">Pelanggan</a>
                <a href="{{ route('admin.login') }}" class="hover:underline">Admin</a>
            </nav>
        </div>
    </header>
    <main class="max-w-5xl mx-auto px-4 py-6">
        {{ $slot }}
    </main>
    <footer class="mt-10 border-t border-white/10">
        <div class="max-w-5xl mx-auto px-4 py-6 text-sm text-white/60">
            &copy; {{ date('Y') }} Laundry. Dibuat responsif (navy/putih).
        </div>
    </footer>
</body>
</html>
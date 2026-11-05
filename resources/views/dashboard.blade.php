<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Election Analytics Dashboard | Bincom</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { sans: ['"Public Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'] } } }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    <style>
        [x-cloak]{display:none !important}
        .gv-nav a { display:flex; align-items:center; gap:12px; padding:11px 20px; font-size:14px; font-weight:500; color:#d6dff0; border-left:4px solid transparent; }
        .gv-nav a:hover { background:#1f3b66; color:#fff; }
        .gv-nav a.is-current { background:#0f2140; color:#fff; border-left-color:#face00; font-weight:600; }
        .gv-nav a:focus-visible, .gv-topbtn:focus-visible { outline:3px solid #2491ff; outline-offset:-3px; }
        .gv-topbtn { height:36px; padding:0 16px; border:2px solid #162e51; background:#fff; color:#162e51; font-size:14px; font-weight:600; border-radius:4px; cursor:pointer; }
        .gv-topbtn:hover { background:#e7edf6; }
    </style>
</head>
<body class="bg-[#f0f0f0] min-h-screen text-[#1b1b1b] font-sans">

    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <aside class="w-64 bg-[#162e51] flex flex-col shrink-0" aria-label="Main navigation">
            <div class="px-4 py-4 border-b-4 border-[#face00]">
                <img src="{{ asset('images/bincom-logo.svg') }}" alt="Bincom" class="h-12 w-auto">
            </div>

            <nav class="gv-nav flex-1 py-3 overflow-y-auto">
                <div class="px-5 pt-3 pb-2 text-[13px] font-semibold text-[#9db0cf]">Data</div>
                <a href="#" class="is-current" aria-current="page">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    Polling Units
                </a>
                <a href="#">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5-2V5l5 2m0 13l6-2m-6 2V7m6 11l5 2V7l-5-2m0 13V5"/></svg>
                    LGA Results
                </a>
                <a href="#">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12M8 12h12M8 17h12M4 7h.01M4 12h.01M4 17h.01"/></svg>
                    Unique's documentation.
                </a>
            </nav>

            <div class="px-5 py-3 text-xs text-[#9db0cf] border-t border-[#2c4672]">
                Bincom Election Analytics
            </div>
        </aside>

        <!-- Main -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top bar -->
            <div class="h-14 bg-white border-b border-[#c9c9c9] flex items-center justify-between px-6 gap-2 shrink-0">
                <span class="text-sm text-[#565c65]">Election Analytics Dashboard</span>
                <button type="button" class="gv-topbtn">Unique Okeke</button>
            </div>

            <main class="flex-1 overflow-y-auto p-6">
                <nav aria-label="Breadcrumb" class="text-[13px] text-[#565c65] mb-4">
                    <span>Data</span>
                    <span class="mx-1.5">/</span>
                    <span class="font-semibold text-[#1b1b1b]">Polling Units</span>
                </nav>
                @yield('content')
            </main>
        </div>

    </div>

</body>
</html>
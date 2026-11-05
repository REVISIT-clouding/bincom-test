
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Election Analytics Dashboard | Bincom</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Public Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            min-height: 100%;
            margin: 0;
            overflow-x: hidden;
        }

        /* Sidebar */
        .gv-sidebar {
            width: 256px;
            min-width: 256px;
            background: #162e51;
            transition: width 0.3s ease, min-width 0.3s ease,
                        transform 0.3s ease;
            z-index: 40;
        }

        .gv-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 20px;
            font-size: 14px;
            font-weight: 500;
            color: #d6dff0;
            border-left: 4px solid transparent;
            text-decoration: none;
            transition: background 0.2s ease;
        }

        .gv-nav a:hover {
            background: #1f3b66;
            color: #fff;
        }

        .gv-nav a.is-current {
            background: #0f2140;
            color: #fff;
            border-left-color: #face00;
            font-weight: 600;
        }

        .gv-nav a:focus-visible,
        .gv-topbtn:focus-visible,
        .gv-menu-btn:focus-visible {
            outline: 3px solid #2491ff;
            outline-offset: 2px;
        }

        /* Collapsed desktop sidebar */
        .gv-sidebar.is-collapsed {
            width: 64px;
            min-width: 64px;
        }

        .gv-sidebar.is-collapsed .nav-text,
        .gv-sidebar.is-collapsed .section-label,
        .gv-sidebar.is-collapsed .sidebar-footer {
            display: none;
        }

        .gv-sidebar.is-collapsed .brand-logo {
            width: 36px;
            height: auto;
        }

        .gv-sidebar.is-collapsed .gv-nav a {
            justify-content: center;
            padding: 14px 8px;
        }

        /* Top navigation */
        .gv-topbar {
            min-height: 56px;
            flex-shrink: 0;
        }

        .gv-topbtn {
            min-height: 36px;
            padding: 0 16px;
            border: 2px solid #162e51;
            background: #fff;
            color: #162e51;
            font-size: 14px;
            font-weight: 600;
            border-radius: 4px;
            cursor: pointer;
            white-space: nowrap;
        }

        .gv-topbtn:hover {
            background: #e7edf6;
        }

        .gv-menu-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border: none;
            border-radius: 6px;
            background: transparent;
            color: #162e51;
            cursor: pointer;
            flex-shrink: 0;
        }

        .gv-menu-btn:hover {
            background: #e7edf6;
        }

        /* Main content */
        .gv-main {
            min-width: 0;
            width: 100%;
            overflow-x: hidden;
        }

        .gv-main img {
            max-width: 100%;
            height: auto;
        }

        .gv-main input,
        .gv-main select,
        .gv-main textarea {
            max-width: 100%;
        }

        .gv-main .overflow-x-auto {
            max-width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Tablet */
        @media (max-width: 1024px) and (min-width: 641px) {
            .gv-sidebar {
                width: 64px;
                min-width: 64px;
            }

            .gv-sidebar .nav-text,
            .gv-sidebar .section-label,
            .gv-sidebar .sidebar-footer {
                display: none;
            }

            .gv-sidebar .brand-logo {
                width: 36px;
                height: auto;
            }

            .gv-nav a {
                justify-content: center;
                padding: 14px 8px;
            }

            .gv-main {
                padding: 20px;
            }
        }

        /* Mobile */
        @media (max-width: 640px) {
            .gv-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                width: 256px;
                min-width: 256px;
                transform: translateX(-100%);
                box-shadow: none;
            }

            .gv-sidebar.mobile-open {
                transform: translateX(0);
                box-shadow: 4px 0 20px rgba(0, 0, 0, 0.2);
            }

            .gv-sidebar .nav-text,
            .gv-sidebar .section-label,
            .gv-sidebar .sidebar-footer {
                display: initial;
            }

            .gv-sidebar .gv-nav a {
                justify-content: flex-start;
                padding: 14px 20px;
            }

            .gv-sidebar .brand-logo {
                width: auto;
                max-width: 150px;
                height: 48px;
            }

            .gv-sidebar-backdrop {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.45);
                z-index: 30;
            }

            .gv-topbar {
                padding: 10px 12px;
                gap: 8px;
            }

            .gv-topbar span {
                font-size: 11px;
                max-width: 55%;
                line-height: 1.4;
            }

            .gv-topbtn {
                min-height: 32px;
                padding: 0 8px;
                font-size: 11px;
            }

            .gv-main {
                padding: 14px 12px;
            }

            .gv-main h1 {
                font-size: 21px;
                line-height: 1.35;
            }

            .gv-main table {
                min-width: 550px;
            }

            .gv-main input,
            .gv-main select,
            .gv-main textarea {
                width: 100%;
            }
        }

        /* Small phones */
        @media (max-width: 380px) {
            .gv-main {
                padding: 12px 8px;
            }

            .gv-topbtn {
                padding: 0 6px;
                font-size: 10px;
            }

            .gv-topbar span {
                font-size: 10px;
            }
        }
    </style>
</head>

<body class="bg-[#f0f0f0] min-h-screen text-[#1b1b1b] font-sans">

    <div
        class="flex h-screen min-h-screen overflow-hidden"
        x-data="{
            sidebarCollapsed: false,
            mobileSidebarOpen: false
        }"
    >

        <!-- Sidebar -->
        <aside
            class="gv-sidebar flex flex-col shrink-0"
            :class="{
                'is-collapsed': sidebarCollapsed,
                'mobile-open': mobileSidebarOpen
            }"
            aria-label="Main navigation"
        >

            <div class="px-4 py-4 border-b-4 border-[#face00]">
                <img
                    src="{{ asset('images/bincom-logo.svg') }}"
                    alt="Bincom"
                    class="brand-logo h-12 w-auto"
                >
            </div>

            <nav class="gv-nav flex-1 py-3 overflow-y-auto">

                <div class="section-label px-5 pt-3 pb-2 text-[13px] font-semibold text-[#9db0cf]">
                    Data
                </div>

                <a href="{{ route('polling-units') }}"
                   class="{{ request()->routeIs('polling-units') ? 'is-current' : '' }}"
                   aria-current="{{ request()->routeIs('polling-units') ? 'page' : 'false' }}">

                    <svg class="w-4 h-4 shrink-0" fill="none"
                         stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7" rx="1"/>
                        <rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" rx="1"/>
                        <rect x="14" y="14" width="7" height="7" rx="1"/>
                    </svg>

                    <span class="nav-text">Polling Units</span>
                </a>

                <a href="{{ route('results') }}"
                   class="{{ request()->routeIs('results') ? 'is-current' : '' }}">

                    <svg class="w-4 h-4 shrink-0" fill="none"
                         stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 20l-5-2V5l5 2m0 13l6-2m-6 2V7m6 11l5 2V7l-5-2m0 13V5"/>
                    </svg>

                    <span class="nav-text">LGA Results</span>
                <a href="{{ url('/create-result') }}">
                    <svg class="w-4 h-4 shrink-0" fill="none"
                         stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 5v14m-7-7h14"/>
                    </svg>
                    <span class="nav-text">Submit Results</span>
                </a>
                </a>


            </nav>

            <div class="sidebar-footer px-5 py-3 text-xs text-[#9db0cf] border-t border-[#2c4672]">
                Bincom Election Analytics
            </div>

        </aside>

        <!-- Mobile backdrop -->
        <div
            x-cloak
            x-show="mobileSidebarOpen"
            x-transition.opacity
            @click="mobileSidebarOpen = false"
            class="gv-sidebar-backdrop"
        ></div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- Top Navigation -->
            <header class="gv-topbar bg-white border-b border-[#c9c9c9]
                           flex items-center justify-between px-6 gap-3">

                <div class="flex items-center gap-3 min-w-0">

                    <!-- Sidebar toggle -->
                    <button
                        type="button"
                        class="gv-menu-btn"
                        aria-label="Toggle navigation"
                        @click="
                            if (window.innerWidth <= 640) {
                                mobileSidebarOpen = !mobileSidebarOpen;
                            } else {
                                sidebarCollapsed = !sidebarCollapsed;
                            }
                        "
                    >
                        <svg class="w-6 h-6" fill="none"
                             stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <span class="text-sm text-[#565c65] truncate">
                        Election Analytics Dashboard
                    </span>

                </div>

                <button type="button" class="gv-topbtn">
                    Unique Okeke
                </button>

            </header>

            <!-- Page Content -->
            <main class="gv-main flex-1 overflow-y-auto p-6">

                <!-- Breadcrumb -->
                <nav aria-label="Breadcrumb"
                     class="text-[13px] text-[#565c65] mb-4">

                    <span>Data</span>
                    <span class="mx-1.5">/</span>
                    <span class="font-semibold text-[#1b1b1b]">
                        Polling Units
                    </span>

                </nav>

                <!-- Dynamic Page Content -->
                @yield('content')

            </main>

        </div>

    </div>

</body>
</html>

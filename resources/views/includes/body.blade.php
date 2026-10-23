<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ingeniería Mecánica Rubio</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="{{ asset('css/bootstrap_custom.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/menu.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/main.css') }}" rel="stylesheet" />
    @yield('css')
</head>

<body>

@if (Auth::check())

    @include('includes.menu')

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="main-wrapper" id="mainWrapper">

        <header class="topbar">
            <button class="sidebar-toggle" id="sidebarToggle" title="Menú">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
            <span class="topbar-title d-none d-lg-block">Ingeniería Mecánica Rubio</span>
            <div class="ms-auto d-flex align-items-center gap-3">
                <span class="topbar-user">{{ Auth::user()->name }}</span>
            </div>
        </header>

        <main class="main-content">
            @yield('body')
            @yield('content')
            @yield('modal')
        </main>

    </div>

@else

    @yield('body')
    @yield('content')
    @yield('modal')

@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
(function () {
    var sidebar = document.getElementById('sidebar');
    var mainWrapper = document.getElementById('mainWrapper');
    var toggle = document.getElementById('sidebarToggle');
    var overlay = document.getElementById('sidebarOverlay');

    if (!toggle || !sidebar) return;

    toggle.addEventListener('click', function () {
        if (window.innerWidth < 992) {
            sidebar.classList.toggle('open');
            if (overlay) overlay.classList.toggle('active');
        } else {
            document.body.classList.toggle('sidebar-collapsed');
        }
    });

    if (overlay) {
        overlay.addEventListener('click', function () {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        });
    }
})();
</script>
@yield('js')

</body>
</html>

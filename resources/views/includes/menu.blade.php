<aside class="sidebar" id="sidebar">

    {{-- Brand --}}
    <div class="sidebar-brand">
        <div class="sidebar-brand-name">IMR</div>
        <div class="sidebar-brand-sub">Ingeniería Mecánica Rubio</div>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav">

        {{-- Clientes --}}
        <div class="sidebar-section-label">Clientes</div>

        <a class="sidebar-nav-link" data-bs-toggle="collapse" href="#nav-clientes" role="button" aria-expanded="false">
            <x-feathericon-users/>
            <span>Clientes</span>
            <x-feathericon-chevron-right class="sidebar-chevron"/>
        </a>
        <div class="collapse sidebar-sub-nav" id="nav-clientes">
            <a class="sidebar-nav-link" href="{{ route('admin.client.create') }}">Nuevo cliente</a>
            <a class="sidebar-nav-link" href="{{ route('admin.client.index') }}">Lista de clientes</a>
        </div>

        <a class="sidebar-nav-link" data-bs-toggle="collapse" href="#nav-autos" role="button" aria-expanded="false">
            <x-feathericon-truck/>
            <span>Automóviles</span>
            <x-feathericon-chevron-right class="sidebar-chevron"/>
        </a>
        <div class="collapse sidebar-sub-nav" id="nav-autos">
            <a class="sidebar-nav-link" href="{{ route('admin.car.create') }}">Nuevo automóvil</a>
            <a class="sidebar-nav-link" href="{{ route('admin.car.index') }}">Lista de autos</a>
        </div>

        {{-- Servicios --}}
        <div class="sidebar-section-label">Servicios</div>

        <a class="sidebar-nav-link" data-bs-toggle="collapse" href="#nav-servicios" role="button" aria-expanded="false">
            <x-feathericon-tool/>
            <span>Servicios</span>
            <x-feathericon-chevron-right class="sidebar-chevron"/>
        </a>
        <div class="collapse sidebar-sub-nav" id="nav-servicios">
            <a class="sidebar-nav-link" href="{{ route('admin.service.quote.index') }}">Cotizaciones</a>
            <a class="sidebar-nav-link" href="{{ route('admin.service.create') }}">Nuevo servicio</a>
            <a class="sidebar-nav-link" href="{{ route('admin.service.index') }}">Lista de servicios</a>
        </div>

        <a class="sidebar-nav-link" href="{{ route('admin.service.calendar.index') }}">
            <x-feathericon-calendar/>
            <span>Calendario</span>
        </a>

        {{-- Finanzas --}}
        <div class="sidebar-section-label">Finanzas</div>

        <a class="sidebar-nav-link" href="{{ route('admin.finance.income') }}">
            <x-feathericon-trending-up/>
            <span>Ingresos</span>
        </a>

        <a class="sidebar-nav-link" data-bs-toggle="collapse" href="#nav-nominas" role="button" aria-expanded="false">
            <x-feathericon-dollar-sign/>
            <span>Nóminas</span>
            <x-feathericon-chevron-right class="sidebar-chevron"/>
        </a>
        <div class="collapse sidebar-sub-nav" id="nav-nominas">
            <a class="sidebar-nav-link" href="{{ route('admin.finance.payroll.create') }}">Nueva nómina</a>
            <a class="sidebar-nav-link" href="{{ route('admin.finance.payroll.index') }}">Lista de nóminas</a>
        </div>

        <a class="sidebar-nav-link" data-bs-toggle="collapse" href="#nav-egresos" role="button" aria-expanded="false">
            <x-feathericon-trending-down/>
            <span>Egresos</span>
            <x-feathericon-chevron-right class="sidebar-chevron"/>
        </a>
        <div class="collapse sidebar-sub-nav" id="nav-egresos">
            <a class="sidebar-nav-link" href="{{ route('admin.finance.expense.create') }}">Nuevo egreso</a>
            <a class="sidebar-nav-link" href="{{ route('admin.finance.expense.index') }}">Lista de egresos</a>
        </div>

        {{-- Reportes --}}
        <div class="sidebar-section-label">Reportes</div>

        <a class="sidebar-nav-link" href="{{ route('admin.dashboard.index') }}">
            <x-feathericon-bar-chart-2/>
            <span>Resumen</span>
        </a>

        <a class="sidebar-nav-link" href="{{ route('admin.finance.monthly-closing') }}">
            <x-feathericon-calendar/>
            <span>Cierre de mes</span>
        </a>

        {{-- Configuración --}}
        <div class="sidebar-section-label">Configuración</div>

        <a class="sidebar-nav-link" data-bs-toggle="collapse" href="#nav-usuarios" role="button" aria-expanded="false">
            <x-feathericon-users/>
            <span>Usuarios</span>
            <x-feathericon-chevron-right class="sidebar-chevron"/>
        </a>
        <div class="collapse sidebar-sub-nav" id="nav-usuarios">
            <a class="sidebar-nav-link" href="{{ route('admin.user.create') }}">Nuevo usuario</a>
            <a class="sidebar-nav-link" href="{{ route('admin.user.index') }}">Lista de usuarios</a>
        </div>

        <a class="sidebar-nav-link" data-bs-toggle="collapse" href="#nav-empleados" role="button" aria-expanded="false">
            <x-feathericon-briefcase/>
            <span>Empleados</span>
            <x-feathericon-chevron-right class="sidebar-chevron"/>
        </a>
        <div class="collapse sidebar-sub-nav" id="nav-empleados">
            <a class="sidebar-nav-link" href="{{ route('admin.employee.create') }}">Nuevo empleado</a>
            <a class="sidebar-nav-link" href="{{ route('admin.employee.index') }}">Lista de empleados</a>
        </div>

        <a class="sidebar-nav-link" href="{{ route('admin.setting.index') }}">
            <x-feathericon-settings/>
            <span>Configuración</span>
        </a>

        @if (Auth::user()->id == 1)
        <div class="sidebar-section-label">Admin</div>
        <a class="sidebar-nav-link" href="{{ route('admin.investment.index') }}">
            <x-feathericon-activity/>
            <span>Inversiones</span>
        </a>
        @endif

    </nav>

    {{-- User footer --}}
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-user-avatar">
                {{ Str::upper(Str::limit(Auth::user()->name, 1, '')) }}
            </div>
            <div style="min-width:0;">
                <div class="sidebar-user-name">{{ Auth::user()->name }}</div>
                <div class="sidebar-user-role">Administrador</div>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.profile.index') }}" class="btn btn-sm btn-outline-secondary flex-fill">
                Perfil
            </a>
            <form action="{{ route('admin.logout') }}" method="POST" class="flex-fill">
                @csrf
                <button class="btn btn-sm btn-danger w-100" type="submit">Salir</button>
            </form>
        </div>
    </div>

</aside>

<div class="menu-container shadow" style="display: flex; justify-content: space-around;">
	<ul class="dropdown p-0">
		<li>
			<a href="#clients">
				<h5 class="menu-title">
					<x-feathericon-users class="table-icon" style="margin-top:-4px;"/>
					Clientes
				</h5>
			</a>
			<ul class="menu-dropdown">
				<li>
					<a class="a-item" href="{{ route('admin.client.index') }}">Clientes</a>
					<ul class="submenu">
						<li><a class="a-item" href="{{ route('admin.client.create') }}">Crear nuevo</a></li>
						<li><a class="a-item" href="{{ route('admin.client.index') }}">Buscar</a></li>
					</ul>
				</li>
				<li>
					<a class="a-item" href="{{ route('admin.car.index') }}">Automoviles</a>
					<ul class="submenu">
						<li><a class="a-item" href="{{ route('admin.car.create') }}">Crear nuevo</a></li>
						<li><a class="a-item" href="{{ route('admin.car.index') }}">Buscar</a></li>
					</ul>
				</li>
			</ul>
		</li>
		<li>
			<a href="#services">
				<h5 class="menu-title">
					<x-feathericon-tool class="table-icon" style="margin-top:-4px;"/>
					Servicios
				</h5>
			</a>
			<ul class="menu-dropdown">
				<li>
					<a class="a-item" href="{{ route('admin.service.index') }}">Servicios</a>
					<ul class="submenu">
						<li><a class="a-item" href="{{ route('admin.service.create') }}">Nueva cotización</a></li>
						<li><a class="a-item" href="{{ route('admin.service.quote.index') }}">Cotizaciones</a></li>
						<li><a class="a-item" href="{{ route('admin.service.create') }}">Nuevo Servicio</a></li>
						<li><a class="a-item" href="{{ route('admin.service.index') }}">Servicios</a></li>
					</ul>
				</li>
				<li>
					<a class="a-item" href="{{ route('admin.service.calendar.index') }}">
						Calendario
						<!--
						<span class="top-0 badge rounded-pill bg-danger">
							2
						</span>
						-->
					</a>
				</li>
			</ul>
		</li>
		<li>
			<a href="#finance">
				<h5 class="menu-title">
					<x-feathericon-dollar-sign class="table-icon" style="margin-top:-4px;"/>
					Finanzas
				</h5>
			</a>
			<ul class="menu-dropdown">
				<li>
					<a class="a-item" href="{{ route('admin.finance.income') }}">Ingresos</a>
				</li>
				<li>
					<a class="a-item" href="{{ route('admin.finance.payroll.index') }}">Nominas</a>
					<ul class="submenu">
						<li><a class="a-item" href="{{ route('admin.finance.payroll.create') }}">Crear nuevo</a></li>
						<li><a class="a-item" href="{{ route('admin.finance.payroll.index') }}">Buscar</a></li>
					</ul>
				</li>
				<li>
					<a class="a-item" href="{{ route('admin.finance.expense.index') }}">Egresos</a>
					<ul class="submenu">
						<li><a class="a-item" href="{{ route('admin.finance.expense.create') }}">Crear nuevo</a></li>
						<li><a class="a-item" href="{{ route('admin.finance.expense.index') }}">Buscar</a></li>
					</ul>
				</li>
			</ul>
		</li>
		<li>
			<a href="#reports">
				<h5 class="menu-title">
					<x-feathericon-settings class="table-icon" style="margin-top:-4px;"/>
					Reportes
				</h5>
			</a>
			<ul class="menu-dropdown">
				<li><a class="a-item" href="{{ route('admin.dashboard.index') }}">Resumen</a></li>
				<li>
					<a class="a-item" href="#">Reportes</a>
					<ul class="submenu">
						<li><a class="a-item" href="#">Autos</a></li>
						<li>
							<a class="a-item" href="#">Servicios</a>
							<ul class="menu-dropdown">
								<li>
									<a class="a-item" href="#">Por cliente</a>
								</li>
							</ul>
						</li>
						<li><a class="a-item" href="#">Empleados</a></li>
					</ul>
				</li>
				<li><a class="a-item" href="{{ route('admin.finance.monthly-closing') }}">Cierre de mes</a></li>
			</ul>
		</li>
		<li>
			<a href="#settings">
				<h5 class="menu-title">
					<x-feathericon-settings class="table-icon" style="margin-top:-4px;"/>
					Configuracion
				</h5>
			</a>
			<ul class="menu-dropdown">
				<li>
					<a class="a-item" href="{{ route('admin.user.index') }}">Usuarios</a>
					<ul class="submenu">
						<li><a class="a-item" href="{{ route('admin.user.create') }}">Crear nuevo</a></li>
						<li><a class="a-item" href="{{ route('admin.user.index') }}">Buscar</a></li>
					</ul>
				</li>
				<li>
					<a class="a-item" href="{{ route('admin.employee.index') }}">Empleados</a>
					<ul class="submenu">
						<li><a class="a-item" href="{{ route('admin.employee.create') }}">Crear nuevo</a></li>
						<li><a class="a-item" href="{{ route('admin.employee.index') }}">Buscar</a></li>
					</ul>
				</li>
				<li><a class="a-item" href="{{ route('admin.setting.index') }}">Configuración</a></li>
			</ul>
		</li>
	</ul>
	
	<ul class="dropdown p-0">
		<li>
			<a href="#clients">
				<h5 class="menu-title">
					<x-feathericon-user class="table-icon" style="margin-top:-4px;"/>
					{{ Auth::user()->name }}
				</h5>
			</a>
			<ul class="menu-dropdown">
				<li>
					<a class="a-item" href="{{ route('admin.profile.index') }}">Perfil</a>
				</li>
				@if (Auth::user()->id == 1)
					<li>
						<a class="a-item" href="{{ route('admin.investment.index') }}">Inversiones</a>
					</li>
				@endif
				<li>
					<form action="{{ route('admin.logout') }}" method="POST">
						@csrf
						<button class="btn btn-logout a-item" type="submit">Cerrar sesion</button>
					</form>
				</li>
			</ul>
		</li>
	</ul>
</div>
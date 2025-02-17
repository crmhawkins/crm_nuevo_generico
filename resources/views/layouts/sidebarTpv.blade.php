<div id="sidebar" class="">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    <a href="/dashboard"><img src="{{asset('assets/images/logo/logo.png')}}" alt="Logo" srcset="" class="img-fluid"></a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>

                <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{route('dashboard')}}" class='sidebar-link'>
                        <i class="fa-solid fa-calculator fs-5"></i>
                        <span>TPV</span>
                    </a>
                </li>
                @php
                    $clientesActive = request()->routeIs('clientes.index') || request()->routeIs('clientes.create') || request()->routeIs('clientes.show') || request()->routeIs('cliente.createFromBudget') || request()->routeIs('clientes.edit');
                    $presupuestoActive = request()->routeIs('presupuestos.index') || request()->routeIs('presupuesto.create') || request()->routeIs('presupuesto.show');
                    $ContabilidadActive = request()->routeIs('cuentasContables.*') || request()->routeIs('subCuentasContables.*') || request()->routeIs('subCuentasHijaContables.*') || request()->routeIs('grupoContabilidad.*') || request()->routeIs('subGrupoContabilidad.*') || request()->routeIs('admin.planContable.index');
                    @endphp

                <li class="sidebar-item {{ request()->routeIs('tpv.index') ? 'active' : '' }}">
                    <a href="{{route('tpv.index')}}" class='sidebar-link'>
                        <i class="fa-solid fa-file-invoice-dollar fs-5"></i>
                        <span>Cuentas Abiertas</span>
                    </a>
                </li>
                <li class="sidebar-item has-sub {{ $presupuestoActive ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="fa-solid fa-file-invoice-dollar fs-5"></i>

                        <span>Presupuestos</span>
                    </a>
                    <ul class="submenu" style="{{ $presupuestoActive ? 'display:block;' : 'display:none;' }}">
                        <li class="submenu-item {{ request()->routeIs('presupuestos.index') ? 'active' : '' }}">
                            <a href="{{route('presupuestos.index')}}">
                                <i class="fa-solid fa-list"></i>
                                <span>
                                    Ver todos
                                </span>
                            </a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('presupuesto.create') ? 'active' : '' }}">
                            <a href="{{route('presupuesto.create')}}">
                                <i class="fa-solid fa-plus"></i>
                                <span>
                                    Crear presupuesto
                                </span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item has-sub {{ $ContabilidadActive ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="fa-solid fa-calculator fs-5"></i>
                        <span>Contablilidad</span>
                    </a>
                    <ul class="submenu" style="{{ $ContabilidadActive ? 'display:block;' : 'display:none;' }}">
                        <li class="submenu-item {{ request()->routeIs('admin.planContable.index') ? 'active' : '' }}">
                            <a href="{{route('admin.planContable.index')}}">
                                <i class="fa-solid fa-list"></i>
                                <span>
                                    Plan Contable
                                </span>
                            </a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('grupoContabilidad.index') ? 'active' : '' }}">
                            <a href="{{route('grupoContabilidad.index')}}">
                                <i class="fa-solid fa-list"></i>
                                <span>
                                    Grupo Contable
                                </span>
                            </a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('subGrupoContabilidad.index') ? 'active' : '' }}">
                            <a href="{{route('subGrupoContabilidad.index')}}">
                                <i class="fa-solid fa-list"></i>
                                <span>
                                    Subgrupo Contable
                                </span>
                            </a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('cuentasContables.index') ? 'active' : '' }}">
                            <a href="{{route('cuentasContables.index')}}">
                                <i class="fa-solid fa-list"></i>
                                <span>
                                    Cuenta Contable
                                </span>
                            </a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('subCuentasContables.index') ? 'active' : '' }}">
                            <a href="{{route('subCuentasContables.index')}}">
                                <i class="fa-solid fa-list"></i>
                                <span>
                                    Subcuenta Contable
                                </span>
                            </a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('subCuentasHijaContables.index') ? 'active' : '' }}">
                            <a href="{{route('subCuentasHijaContables.index')}}">
                                <i class="fa-solid fa-list"></i>
                                <span>
                                    Subcuenta hija Contable
                                </span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">

                    <a  class='sidebar-link' href="{{ route('logout') }}"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa-solid fa-arrow-right-from-bracket "></i>
                        <span>Salir</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>

        </div>
    </div>
</div>

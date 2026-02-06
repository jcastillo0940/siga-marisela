<aside id="sidebar" 
       class="w-72 bg-accent-red text-white flex flex-col shadow-elegant fixed lg:relative inset-y-0 left-0 z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
    
    <div class="h-20 sm:h-24 flex items-center justify-between px-6 border-b border-red-800 border-opacity-30">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white rounded-full flex items-center justify-center">
                <span class="text-accent-red font-display text-xl sm:text-2xl font-bold">A</span>
            </div>
            <span class="text-xl sm:text-2xl font-display tracking-wide">Academia</span>
        </div>
        
        <button onclick="toggleMobileMenu()" class="lg:hidden text-white p-2 hover:bg-red-700 rounded transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    
    <nav class="flex-1 px-4 py-6 sm:py-8 space-y-1 overflow-y-auto">
        
        {{-- ===================================================================== --}}
        {{-- MENÚ PARA ESTUDIANTES                                                 --}}
        {{-- ===================================================================== --}}
        @if(auth()->user()->hasRole('student'))
            
            <a href="{{ route('student-dashboard.root') }}"
               class="flex items-center px-4 sm:px-6 py-3 sm:py-4 rounded-sm transition-all duration-300 group
                      {{ request()->routeIs('student-dashboard.*') ? 'bg-white bg-opacity-10' : 'hover:bg-white hover:bg-opacity-5' }}">
                <svg class="w-5 h-5 mr-3 sm:mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="text-xs font-medium tracking-widest uppercase">Mi Dashboard</span>
            </a>

            <a href="{{ route('public.leads.create') }}"
               class="flex items-center px-4 sm:px-6 py-3 sm:py-4 rounded-sm transition-all duration-300 group hover:bg-white hover:bg-opacity-5">
                <svg class="w-5 h-5 mr-3 sm:mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span class="text-xs font-medium tracking-widest uppercase">Inscribir Curso</span>
            </a>

        {{-- ===================================================================== --}}
        {{-- MENÚ PARA ADMINISTRATIVOS (Admin, Staff, Super-Admin)                 --}}
        {{-- ===================================================================== --}}
        @else

            <a href="{{ route('dashboard') }}"
               class="flex items-center px-4 sm:px-6 py-3 sm:py-4 rounded-sm transition-all duration-300 group
                      {{ request()->routeIs('dashboard') ? 'bg-white bg-opacity-10' : 'hover:bg-white hover:bg-opacity-5' }}">
                <svg class="w-5 h-5 mr-3 sm:mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
                <span class="text-xs font-medium tracking-widest uppercase">Dashboard Admin</span>
            </a>

            <a href="{{ route('student-dashboard.select') }}"
               class="flex items-center px-4 sm:px-6 py-3 sm:py-4 rounded-sm transition-all duration-300 group
                      {{ request()->routeIs('student-dashboard.*') ? 'bg-white bg-opacity-10' : 'hover:bg-white hover:bg-opacity-5' }}">
                <svg class="w-5 h-5 mr-3 sm:mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="text-xs font-medium tracking-widest uppercase">Ver como Alumno</span>
            </a>

            <div class="accordion-menu">
                <button onclick="toggleAccordion('gestion-menu')" type="button"
                        class="w-full flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 rounded-sm transition-all duration-300 hover:bg-white hover:bg-opacity-5
                               {{ request()->routeIs('users.*') || request()->routeIs('students.*') || request()->routeIs('leads.*') || request()->routeIs('courses.*') || request()->routeIs('course-offerings.*') || request()->routeIs('enrollments.*') ? 'bg-white bg-opacity-10' : '' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 sm:mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span class="text-xs font-medium tracking-widest uppercase">Gestión Académica</span>
                    </div>
                    <svg id="gestion-menu-icon" class="w-4 h-4 transition-transform duration-200 {{ request()->routeIs('users.*') || request()->routeIs('students.*') || request()->routeIs('leads.*') || request()->routeIs('courses.*') || request()->routeIs('course-offerings.*') || request()->routeIs('enrollments.*') ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div id="gestion-menu" class="accordion-content {{ request()->routeIs('users.*') || request()->routeIs('students.*') || request()->routeIs('leads.*') || request()->routeIs('courses.*') || request()->routeIs('course-offerings.*') || request()->routeIs('enrollments.*') ? '' : 'hidden' }} ml-4 sm:ml-8 mt-1 space-y-1">
                    @if(auth()->user()->hasPermission('leads.view'))
                        <a href="{{ route('leads.index') }}" class="flex items-center px-4 py-2 rounded-sm text-xs hover:bg-white hover:bg-opacity-5 {{ request()->routeIs('leads.*') ? 'text-white font-bold' : 'text-red-100' }}">Leads / Potenciales</a>
                    @endif
                    @if(auth()->user()->hasPermission('students.view'))
                        <a href="{{ route('students.index') }}" class="flex items-center px-4 py-2 rounded-sm text-xs hover:bg-white hover:bg-opacity-5 {{ request()->routeIs('students.*') ? 'text-white font-bold' : 'text-red-100' }}">Estudiantes</a>
                    @endif
                    @if(auth()->user()->hasPermission('courses.view'))
                        <a href="{{ route('courses.index') }}" class="flex items-center px-4 py-2 rounded-sm text-xs hover:bg-white hover:bg-opacity-5 {{ request()->routeIs('courses.*') ? 'text-white font-bold' : 'text-red-100' }}">Catálogo de Cursos</a>
                        <a href="{{ route('course-offerings.index') }}" class="flex items-center px-4 py-2 rounded-sm text-xs hover:bg-white hover:bg-opacity-5 {{ request()->routeIs('course-offerings.*') ? 'text-white font-bold' : 'text-red-100' }}">Programación Clases</a>
                    @endif
                    @if(auth()->user()->hasPermission('enrollments.view'))
                        <a href="{{ route('enrollments.index') }}" class="flex items-center px-4 py-2 rounded-sm text-xs hover:bg-white hover:bg-opacity-5 {{ request()->routeIs('enrollments.index') || request()->routeIs('enrollments.show') || request()->routeIs('enrollments.create') || request()->routeIs('enrollments.edit') ? 'text-white font-bold' : 'text-red-100' }}">Inscripciones</a>
                        <a href="{{ route('enrollments.pending-approvals') }}" class="flex items-center px-4 py-2 rounded-sm text-xs hover:bg-white hover:bg-opacity-5 {{ request()->routeIs('enrollments.pending-approvals') ? 'text-white font-bold' : 'text-red-100' }}">Aprobaciones Pendientes</a>
                    @endif
                </div>
            </div>

            @if(auth()->user()->hasPermission('courses.view'))
            <a href="{{ route('meal-menus.index') }}"
               class="flex items-center px-4 sm:px-6 py-3 sm:py-4 rounded-sm transition-all duration-300 group
                      {{ request()->routeIs('meal-menus.*') ? 'bg-white bg-opacity-10' : 'hover:bg-white hover:bg-opacity-5' }}">
                <svg class="w-5 h-5 mr-3 sm:mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span class="text-xs font-medium tracking-widest uppercase">Menús de Comida</span>
            </a>
            @endif

            <div class="accordion-menu">
                <button onclick="toggleAccordion('asistencia-menu')" type="button"
                        class="w-full flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 rounded-sm transition-all duration-300 hover:bg-white hover:bg-opacity-5
                               {{ request()->routeIs('attendance.*') || request()->routeIs('certificates.*') || request()->routeIs('certificate-templates.*') ? 'bg-white bg-opacity-10' : '' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 sm:mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        <span class="text-xs font-medium tracking-widest uppercase">Asistencia</span>
                    </div>
                    <svg id="asistencia-menu-icon" class="w-4 h-4 transition-transform duration-200 {{ request()->routeIs('attendance.*') || request()->routeIs('certificates.*') || request()->routeIs('certificate-templates.*') ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div id="asistencia-menu" class="accordion-content {{ request()->routeIs('attendance.*') || request()->routeIs('certificates.*') || request()->routeIs('certificate-templates.*') ? '' : 'hidden' }} ml-4 sm:ml-8 mt-1 space-y-1">
                    <a href="{{ route('attendance.index') }}" class="flex items-center px-4 py-2 rounded-sm text-xs hover:bg-white hover:bg-opacity-5 {{ request()->routeIs('attendance.*') && !request()->routeIs('attendance.student-qr') && !request()->routeIs('attendance.scan-qr') ? 'text-white font-bold' : 'text-red-100' }}">Gestión de Asistencia</a>
                    <a href="{{ route('certificates.index') }}" class="flex items-center px-4 py-2 rounded-sm text-xs hover:bg-white hover:bg-opacity-5 {{ request()->routeIs('certificates.*') && !request()->routeIs('certificate-templates.*') ? 'text-white font-bold' : 'text-red-100' }}">Certificados</a>
                    <a href="{{ route('certificate-templates.index') }}" class="flex items-center px-4 py-2 rounded-sm text-xs hover:bg-white hover:bg-opacity-5 {{ request()->routeIs('certificate-templates.*') ? 'text-white font-bold' : 'text-red-100' }}">Plantillas de Certificados</a>
                </div>
            </div>

            @if(auth()->user()->hasPermission('payments.view'))
            <div class="accordion-menu">
                <button onclick="toggleAccordion('pos-menu')"
                        type="button"
                        class="w-full flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 rounded-sm transition-all duration-300 hover:bg-white hover:bg-opacity-5
                               {{ request()->routeIs('pos.*') || request()->routeIs('cash-registers.*') || request()->routeIs('payments.*') || request()->routeIs('sales.*') || request()->routeIs('products.*') ? 'bg-white bg-opacity-10' : '' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 sm:mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="text-xs font-medium tracking-widest uppercase">POS / Caja</span>
                    </div>
                    <svg id="pos-menu-icon" class="w-4 h-4 transition-transform duration-200 {{ request()->routeIs('pos.*') || request()->routeIs('cash-registers.*') || request()->routeIs('payments.*') || request()->routeIs('sales.*') || request()->routeIs('products.*') ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <div id="pos-menu" class="accordion-content {{ request()->routeIs('pos.*') || request()->routeIs('cash-registers.*') || request()->routeIs('payments.*') || request()->routeIs('sales.*') || request()->routeIs('products.*') ? '' : 'hidden' }} ml-4 sm:ml-8 mt-1 space-y-1">
                    
                    <a href="{{ route('pos.unified') }}"
                       class="flex items-center px-4 py-2 rounded-sm text-xs hover:bg-white hover:bg-opacity-5 {{ request()->routeIs('pos.unified') ? 'text-white font-bold' : 'text-red-100' }}">
                        <span class="flex h-2 w-2 rounded-full bg-green-400 mr-2"></span> POS Unificado (Beta)
                    </a>
                    <hr class="border-red-800 border-opacity-30 my-1 mr-4">
                    
                    <a href="{{ route('pos.index') }}"
                       class="flex items-center px-4 py-2 rounded-sm text-xs hover:bg-white hover:bg-opacity-5 {{ request()->routeIs('pos.index') ? 'text-white font-bold' : 'text-red-100' }}">POS - Pagos Estudiantes</a>
                    
                    <a href="{{ route('sales.pos') }}"
                       class="flex items-center px-4 py-2 rounded-sm text-xs hover:bg-white hover:bg-opacity-5 {{ request()->routeIs('sales.pos') ? 'text-white font-bold' : 'text-red-100' }}">POS - Productos/Servicios</a>
                    
                    <a href="{{ route('products.index') }}"
                       class="flex items-center px-4 py-2 rounded-sm text-xs hover:bg-white hover:bg-opacity-5 {{ request()->routeIs('products.*') ? 'text-white font-bold' : 'text-red-100' }}">Gestión de Productos</a>
                    
                    <a href="{{ route('cash-registers.index') }}"
                       class="flex items-center px-4 py-2 rounded-sm text-xs hover:bg-white hover:bg-opacity-5 {{ request()->routeIs('cash-registers.*') ? 'text-white font-bold' : 'text-red-100' }}">Gestión de Caja</a>
                    
                    <a href="{{ route('payments.index') }}"
                       class="flex items-center px-4 py-2 rounded-sm text-xs hover:bg-white hover:bg-opacity-5 {{ request()->routeIs('payments.index') || request()->routeIs('payments.show') || request()->routeIs('payments.create') ? 'text-white font-bold' : 'text-red-100' }}">Historial de Pagos</a>
                    
                    <a href="{{ route('sales.index') }}"
                       class="flex items-center px-4 py-2 rounded-sm text-xs hover:bg-white hover:bg-opacity-5 {{ request()->routeIs('sales.index') || request()->routeIs('sales.show') ? 'text-white font-bold' : 'text-red-100' }}">Historial de Ventas</a>
                </div>
            </div>
            @endif

            <div class="accordion-menu">
                <button onclick="toggleAccordion('reports-menu')" type="button"
                        class="w-full flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 rounded-sm transition-all duration-300 hover:bg-white hover:bg-opacity-5
                               {{ request()->routeIs('reports.*') ? 'bg-white bg-opacity-10' : '' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 sm:mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="text-xs font-medium tracking-widest uppercase">Reportes</span>
                    </div>
                    <svg id="reports-menu-icon" class="w-4 h-4 transition-transform duration-200 {{ request()->routeIs('reports.*') ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div id="reports-menu" class="accordion-content {{ request()->routeIs('reports.*') ? '' : 'hidden' }} ml-4 sm:ml-8 mt-1 space-y-1">
                    <a href="{{ route('reports.accounts-receivable') }}" class="flex items-center px-4 py-2 rounded-sm text-xs hover:bg-white hover:bg-opacity-5 {{ request()->routeIs('reports.accounts-receivable*') ? 'text-white font-bold' : 'text-red-100' }}">Cuentas por Cobrar</a>
                </div>
            </div>

            <div class="accordion-menu">
                <button onclick="toggleAccordion('admin-menu')" type="button"
                        class="w-full flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 rounded-sm transition-all duration-300 hover:bg-white hover:bg-opacity-5
                               {{ request()->routeIs('users.*') || request()->routeIs('roles.*') ? 'bg-white bg-opacity-10' : '' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 sm:mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="text-xs font-medium tracking-widest uppercase">Configuración</span>
                    </div>
                    <svg id="admin-menu-icon" class="w-4 h-4 transition-transform duration-200 {{ request()->routeIs('users.*') || request()->routeIs('roles.*') ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div id="admin-menu" class="accordion-content {{ request()->routeIs('users.*') || request()->routeIs('roles.*') ? '' : 'hidden' }} ml-4 sm:ml-8 mt-1 space-y-1">
                    @if(auth()->user()->hasPermission('users.view'))
                        <a href="{{ route('users.index') }}" class="flex items-center px-4 py-2 rounded-sm text-xs hover:bg-white hover:bg-opacity-5 {{ request()->routeIs('users.*') ? 'text-white font-bold' : 'text-red-100' }}">Usuarios del Sistema</a>
                    @endif
                    @if(auth()->user()->hasPermission('roles.view'))
                        <a href="{{ route('roles.index') }}" class="flex items-center px-4 py-2 rounded-sm text-xs hover:bg-white hover:bg-opacity-5 {{ request()->routeIs('roles.*') ? 'text-white font-bold' : 'text-red-100' }}">Roles y Permisos</a>
                    @endif
                </div>
            </div>

        @endif
        {{-- FIN DEL IF --}}

    </nav>
    
    <div class="px-4 sm:px-6 py-4 sm:py-6 border-t border-red-800 border-opacity-30">
        <p class="text-xs text-red-100 tracking-wide uppercase opacity-75">
            Academia Auténtica ERP
        </p>
        <p class="text-xs text-red-200 mt-1 opacity-60">
            Versión 1.0.0
        </p>
    </div>
</aside>

<script>
    function toggleAccordion(id) {
        const content = document.getElementById(id);
        const icon = document.getElementById(id + '-icon');
        
        if (content && icon) {
            content.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }
    }
</script>
<aside id="sidebar" 
       class="w-72 bg-accent-red text-white flex flex-col shadow-elegant fixed lg:relative inset-y-0 left-0 z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
    
    <!-- Logo -->
    <div class="h-20 sm:h-24 flex items-center justify-between px-6 border-b border-red-800 border-opacity-30">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white rounded-full flex items-center justify-center">
                <span class="text-accent-red font-display text-xl sm:text-2xl font-bold">A</span>
            </div>
            <span class="text-xl sm:text-2xl font-display tracking-wide">Academia</span>
        </div>
        
        <!-- Close button (mobile only) -->
        <button onclick="toggleMobileMenu()" class="lg:hidden text-white p-2 hover:bg-red-700 rounded transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    
    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 sm:py-8 space-y-1 overflow-y-auto">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" 
           class="flex items-center px-4 sm:px-6 py-3 sm:py-4 rounded-sm transition-all duration-300 group
                  {{ request()->routeIs('dashboard') ? 'bg-white bg-opacity-10' : 'hover:bg-white hover:bg-opacity-5' }}">
            <svg class="w-5 h-5 mr-3 sm:mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span class="text-xs font-medium tracking-widest uppercase">Dashboard</span>
        </a>

        <!-- Usuarios (Acordeón) -->
        @if(auth()->user()->hasPermission('users.view'))
        <div class="accordion-menu">
            <button onclick="toggleAccordion('users-menu')" 
                    type="button"
                    class="w-full flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 rounded-sm transition-all duration-300 hover:bg-white hover:bg-opacity-5
                           {{ request()->routeIs('users.*') ? 'bg-white bg-opacity-10' : '' }}">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 sm:mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span class="text-xs font-medium tracking-widest uppercase">Usuarios</span>
                </div>
                <svg id="users-menu-icon" class="w-4 h-4 transition-transform duration-200 {{ request()->routeIs('users.*') ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            
            <!-- Submenú -->
            <div id="users-menu" class="accordion-content {{ request()->routeIs('users.*') ? '' : 'hidden' }} ml-4 sm:ml-8 mt-1 space-y-1">
                <!-- Ver Usuarios -->
                <a href="{{ route('users.index') }}" 
                   class="flex items-center px-4 sm:px-6 py-2 sm:py-3 rounded-sm text-xs transition-all duration-300 hover:bg-white hover:bg-opacity-5
                          {{ request()->routeIs('users.index') || request()->routeIs('users.show') || request()->routeIs('users.edit') ? 'bg-white bg-opacity-10' : '' }}">
                    <svg class="w-4 h-4 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <span class="tracking-wider uppercase">Ver Usuarios</span>
                </a>
                
                <!-- Crear Usuario -->
                @if(auth()->user()->hasPermission('users.create'))
                <a href="{{ route('users.create') }}" 
                   class="flex items-center px-4 sm:px-6 py-2 sm:py-3 rounded-sm text-xs transition-all duration-300 hover:bg-white hover:bg-opacity-5
                          {{ request()->routeIs('users.create') ? 'bg-white bg-opacity-10' : '' }}">
                    <svg class="w-4 h-4 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    <span class="tracking-wider uppercase">Crear Usuario</span>
                </a>
                @endif
            </div>
        </div>
        @endif
        
        <!-- Estudiantes (Acordeón) -->
@if(auth()->user()->hasPermission('students.view'))
<div class="accordion-menu">
    <button onclick="toggleAccordion('students-menu')" 
            type="button"
            class="w-full flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 rounded-sm transition-all duration-300 hover:bg-white hover:bg-opacity-5
                   {{ request()->routeIs('students.*') ? 'bg-white bg-opacity-10' : '' }}">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-3 sm:mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <span class="text-xs font-medium tracking-widest uppercase">Estudiantes</span>
        </div>
        <svg id="students-menu-icon" class="w-4 h-4 transition-transform duration-200 {{ request()->routeIs('students.*') ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    
    <!-- Submenú -->
    <div id="students-menu" class="accordion-content {{ request()->routeIs('students.*') ? '' : 'hidden' }} ml-4 sm:ml-8 mt-1 space-y-1">
        <!-- Ver Estudiantes -->
        <a href="{{ route('students.index') }}" 
           class="flex items-center px-4 sm:px-6 py-2 sm:py-3 rounded-sm text-xs transition-all duration-300 hover:bg-white hover:bg-opacity-5
                  {{ request()->routeIs('students.index') || request()->routeIs('students.show') || request()->routeIs('students.edit') ? 'bg-white bg-opacity-10' : '' }}">
            <svg class="w-4 h-4 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <span class="tracking-wider uppercase">Ver Estudiantes</span>
        </a>
        
        <!-- Crear Estudiante -->
        @if(auth()->user()->hasPermission('students.create'))
        <a href="{{ route('students.create') }}" 
           class="flex items-center px-4 sm:px-6 py-2 sm:py-3 rounded-sm text-xs transition-all duration-300 hover:bg-white hover:bg-opacity-5
                  {{ request()->routeIs('students.create') ? 'bg-white bg-opacity-10' : '' }}">
            <svg class="w-4 h-4 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
            </svg>
            <span class="tracking-wider uppercase">Crear Estudiante</span>
        </a>
        @endif
    </div>
</div>
@endif

<!-- Leads (Acordeón) -->
@if(auth()->user()->hasPermission('leads.view'))
<div class="accordion-menu">
    <button onclick="toggleAccordion('leads-menu')" 
            type="button"
            class="w-full flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 rounded-sm transition-all duration-300 hover:bg-white hover:bg-opacity-5
                   {{ request()->routeIs('leads.*') ? 'bg-white bg-opacity-10' : '' }}">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-3 sm:mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
            </svg>
            <span class="text-xs font-medium tracking-widest uppercase">Leads</span>
        </div>
        <svg id="leads-menu-icon" class="w-4 h-4 transition-transform duration-200 {{ request()->routeIs('leads.*') ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    
    <!-- Submenú -->
    <div id="leads-menu" class="accordion-content {{ request()->routeIs('leads.*') ? '' : 'hidden' }} ml-4 sm:ml-8 mt-1 space-y-1">
        <!-- Ver Leads -->
        <a href="{{ route('leads.index') }}" 
           class="flex items-center px-4 sm:px-6 py-2 sm:py-3 rounded-sm text-xs transition-all duration-300 hover:bg-white hover:bg-opacity-5
                  {{ request()->routeIs('leads.index') || request()->routeIs('leads.show') || request()->routeIs('leads.edit') ? 'bg-white bg-opacity-10' : '' }}">
            <svg class="w-4 h-4 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <span class="tracking-wider uppercase">Ver Leads</span>
        </a>
        
        <!-- Crear Lead -->
        @if(auth()->user()->hasPermission('leads.create'))
        <a href="{{ route('leads.create') }}" 
           class="flex items-center px-4 sm:px-6 py-2 sm:py-3 rounded-sm text-xs transition-all duration-300 hover:bg-white hover:bg-opacity-5
                  {{ request()->routeIs('leads.create') ? 'bg-white bg-opacity-10' : '' }}">
            <svg class="w-4 h-4 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span class="tracking-wider uppercase">Crear Lead</span>
        </a>
        @endif
    </div>
</div>
@endif

<!-- Cursos (Acordeón) -->
@if(auth()->user()->hasPermission('courses.view'))
<div class="accordion-menu">
    <button onclick="toggleAccordion('courses-menu')" 
            type="button"
            class="w-full flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 rounded-sm transition-all duration-300 hover:bg-white hover:bg-opacity-5
                   {{ request()->routeIs('courses.*') ? 'bg-white bg-opacity-10' : '' }}">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-3 sm:mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <span class="text-xs font-medium tracking-widest uppercase">Cursos</span>
        </div>
        <svg id="courses-menu-icon" class="w-4 h-4 transition-transform duration-200 {{ request()->routeIs('courses.*') ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    
    <!-- Submenú -->
    <div id="courses-menu" class="accordion-content {{ request()->routeIs('courses.*') ? '' : 'hidden' }} ml-4 sm:ml-8 mt-1 space-y-1">
        <!-- Ver Cursos -->
        <a href="{{ route('courses.index') }}" 
           class="flex items-center px-4 sm:px-6 py-2 sm:py-3 rounded-sm text-xs transition-all duration-300 hover:bg-white hover:bg-opacity-5
                  {{ request()->routeIs('courses.index') || request()->routeIs('courses.show') || request()->routeIs('courses.edit') ? 'bg-white bg-opacity-10' : '' }}">
            <svg class="w-4 h-4 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <span class="tracking-wider uppercase">Ver Cursos</span>
        </a>
        
        <!-- Crear Curso -->
        @if(auth()->user()->hasPermission('courses.create'))
        <a href="{{ route('courses.create') }}" 
           class="flex items-center px-4 sm:px-6 py-2 sm:py-3 rounded-sm text-xs transition-all duration-300 hover:bg-white hover:bg-opacity-5
                  {{ request()->routeIs('courses.create') ? 'bg-white bg-opacity-10' : '' }}">
            <svg class="w-4 h-4 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span class="tracking-wider uppercase">Crear Curso</span>
        </a>
        @endif
    </div>
</div>
@endif

<!-- Ofertas de Cursos (Acordeón) -->
@if(auth()->user()->hasPermission('courses.view'))
<div class="accordion-menu">
    <button onclick="toggleAccordion('offerings-menu')" 
            type="button"
            class="w-full flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 rounded-sm transition-all duration-300 hover:bg-white hover:bg-opacity-5
                   {{ request()->routeIs('course-offerings.*') ? 'bg-white bg-opacity-10' : '' }}">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-3 sm:mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <span class="text-xs font-medium tracking-widest uppercase">Programación</span>
        </div>
        <svg id="offerings-menu-icon" class="w-4 h-4 transition-transform duration-200 {{ request()->routeIs('course-offerings.*') ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    
    <!-- Submenú -->
    <div id="offerings-menu" class="accordion-content {{ request()->routeIs('course-offerings.*') ? '' : 'hidden' }} ml-4 sm:ml-8 mt-1 space-y-1">
        <!-- Ver Ofertas -->
        <a href="{{ route('course-offerings.index') }}" 
           class="flex items-center px-4 sm:px-6 py-2 sm:py-3 rounded-sm text-xs transition-all duration-300 hover:bg-white hover:bg-opacity-5
                  {{ request()->routeIs('course-offerings.index') || request()->routeIs('course-offerings.show') || request()->routeIs('course-offerings.edit') ? 'bg-white bg-opacity-10' : '' }}">
            <svg class="w-4 h-4 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <span class="tracking-wider uppercase">Ver Programación</span>
        </a>
        
        <!-- Crear Oferta -->
        @if(auth()->user()->hasPermission('courses.create'))
        <a href="{{ route('course-offerings.create') }}" 
           class="flex items-center px-4 sm:px-6 py-2 sm:py-3 rounded-sm text-xs transition-all duration-300 hover:bg-white hover:bg-opacity-5
                  {{ request()->routeIs('course-offerings.create') ? 'bg-white bg-opacity-10' : '' }}">
            <svg class="w-4 h-4 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span class="tracking-wider uppercase">Programar Curso</span>
        </a>
        @endif
    </div>
</div>
@endif

<!-- Inscripciones (Acordeón) -->
@if(auth()->user()->hasPermission('enrollments.view'))
<div class="accordion-menu">
    <button onclick="toggleAccordion('enrollments-menu')" 
            type="button"
            class="w-full flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 rounded-sm transition-all duration-300 hover:bg-white hover:bg-opacity-5
                   {{ request()->routeIs('enrollments.*') ? 'bg-white bg-opacity-10' : '' }}">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-3 sm:mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span class="text-xs font-medium tracking-widest uppercase">Inscripciones</span>
        </div>
        <svg id="enrollments-menu-icon" class="w-4 h-4 transition-transform duration-200 {{ request()->routeIs('enrollments.*') ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    
    <!-- Submenú -->
    <div id="enrollments-menu" class="accordion-content {{ request()->routeIs('enrollments.*') ? '' : 'hidden' }} ml-4 sm:ml-8 mt-1 space-y-1">
        <!-- Ver Inscripciones -->
        <a href="{{ route('enrollments.index') }}" 
           class="flex items-center px-4 sm:px-6 py-2 sm:py-3 rounded-sm text-xs transition-all duration-300 hover:bg-white hover:bg-opacity-5
                  {{ request()->routeIs('enrollments.index') || request()->routeIs('enrollments.show') || request()->routeIs('enrollments.edit') ? 'bg-white bg-opacity-10' : '' }}">
            <svg class="w-4 h-4 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <span class="tracking-wider uppercase">Ver Inscripciones</span>
        </a>
        
        <!-- Crear Inscripción -->
        @if(auth()->user()->hasPermission('enrollments.create'))
        <a href="{{ route('enrollments.create') }}" 
           class="flex items-center px-4 sm:px-6 py-2 sm:py-3 rounded-sm text-xs transition-all duration-300 hover:bg-white hover:bg-opacity-5
                  {{ request()->routeIs('enrollments.create') ? 'bg-white bg-opacity-10' : '' }}">
            <svg class="w-4 h-4 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span class="tracking-wider uppercase">Nueva Inscripción</span>
        </a>
        @endif
    </div>
</div>
@endif

<!-- Pagos/Caja (Acordeón) -->
@if(auth()->user()->hasPermission('payments.view'))
<div class="accordion-menu">
    <button onclick="toggleAccordion('payments-menu')" 
            type="button"
            class="w-full flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 rounded-sm transition-all duration-300 hover:bg-white hover:bg-opacity-5
                   {{ request()->routeIs('payments.*') ? 'bg-white bg-opacity-10' : '' }}">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-3 sm:mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <span class="text-xs font-medium tracking-widest uppercase">Pagos / Caja</span>
        </div>
        <svg id="payments-menu-icon" class="w-4 h-4 transition-transform duration-200 {{ request()->routeIs('payments.*') ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    
    <!-- Submenú -->
    <div id="payments-menu" class="accordion-content {{ request()->routeIs('payments.*') ? '' : 'hidden' }} ml-4 sm:ml-8 mt-1 space-y-1">
        <!-- Ver Pagos -->
        <a href="{{ route('payments.index') }}" 
           class="flex items-center px-4 sm:px-6 py-2 sm:py-3 rounded-sm text-xs transition-all duration-300 hover:bg-white hover:bg-opacity-5
                  {{ request()->routeIs('payments.index') || request()->routeIs('payments.show') ? 'bg-white bg-opacity-10' : '' }}">
            <svg class="w-4 h-4 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <span class="tracking-wider uppercase">Ver Pagos</span>
        </a>
        
        <!-- Registrar Pago -->
        @if(auth()->user()->hasPermission('payments.create'))
        <a href="{{ route('payments.create') }}" 
           class="flex items-center px-4 sm:px-6 py-2 sm:py-3 rounded-sm text-xs transition-all duration-300 hover:bg-white hover:bg-opacity-5
                  {{ request()->routeIs('payments.create') ? 'bg-white bg-opacity-10' : '' }}">
            <svg class="w-4 h-4 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span class="tracking-wider uppercase">Registrar Pago</span>
        </a>
        @endif
    </div>
</div>
@endif

   <!-- ------------------------------------------------------------------------  -->

        <!-- Roles -->
        @if(auth()->user()->hasPermission('roles.view'))
        <a href="{{ route('roles.index') }}" 
           class="flex items-center px-4 sm:px-6 py-3 sm:py-4 rounded-sm transition-all duration-300 group
                  {{ request()->routeIs('roles.*') ? 'bg-white bg-opacity-10' : 'hover:bg-white hover:bg-opacity-5' }}">
            <svg class="w-5 h-5 mr-3 sm:mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
            <span class="text-xs font-medium tracking-widest uppercase">Roles</span>
        </a>
        @endif
    </nav>
    
    <!-- Footer Info -->
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
    // Accordion functionality
    function toggleAccordion(id) {
        const content = document.getElementById(id);
        const icon = document.getElementById(id + '-icon');
        
        if (content && icon) {
            content.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }
    }
</script>
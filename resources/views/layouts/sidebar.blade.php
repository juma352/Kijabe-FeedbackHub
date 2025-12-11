<!-- Sidebar -->
<div class="hidden md:flex fixed left-0 top-0 h-screen bg-gray-800 text-white w-64 px-4 py-6 flex-col overflow-y-auto z-40">
    <!-- Logo -->
    <div class="mb-8">
        <div class="flex items-center">
            <i class="fas fa-comments text-2xl text-blue-400 mr-3"></i>
            <h1 class="text-xl font-bold">Feedback Hub</h1>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1">
        <ul class="space-y-2">
            <li>
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <i class="fas fa-tachometer-alt w-5 mr-3"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li>
                <a href="{{ route('feedback.management') }}" 
                   class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('feedback.management') || request()->routeIs('feedback.index') || request()->routeIs('feedback.show') || request()->routeIs('feedback.edit') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <i class="fas fa-cogs w-5 mr-3"></i>
                    <span>Manage Feedback</span>
                </a>
            </li>

            <li>
                <a href="{{ route('feedback.analytics') }}" 
                   class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('feedback.analytics') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <i class="fas fa-chart-bar w-5 mr-3"></i>
                    <span>Analytics</span>
                </a>
            </li>

            <li>
                <a href="{{ route('feedback.learner.experience') }}" 
                   class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('feedback.learner.experience') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <i class="fas fa-graduation-cap w-5 mr-3"></i>
                    <span>Learner Experience</span>
                </a>
            </li>

            <!-- Form Builder Section -->
            <li class="pt-4 mt-4 border-t border-gray-700">
                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">
                    Form Builder
                </div>
            </li>

            <li>
                <a href="{{ route('forms.index') }}" 
                   class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('forms.index') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <i class="fas fa-list w-5 mr-3"></i>
                    <span>My Forms</span>
                </a>
            </li>

            <li>
                <a href="{{ route('forms.create') }}" 
                   class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('forms.create') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <i class="fas fa-plus-circle w-5 mr-3"></i>
                    <span>Create Form</span>
                </a>
            </li>

            @if(Auth::user()->isAdmin())
            <!-- Admin Only Section -->
            <li class="pt-4 mt-4 border-t border-gray-700">
                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">
                    Admin
                </div>
            </li>

            <li>
                <a href="{{ route('forms.index') }}?show=all" 
                   class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->get('show') === 'all' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <i class="fas fa-globe w-5 mr-3"></i>
                    <span>All Forms</span>
                </a>
            </li>

            <li>
                <a href="{{ route('roles.index') }}" 
                   class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('roles.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <i class="fas fa-shield-alt w-5 mr-3"></i>
                    <span>Roles & Permissions</span>
                </a>
            </li>

            <li>
                <a href="{{ route('departments.index') }}" 
                   class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('departments.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <i class="fas fa-building w-5 mr-3"></i>
                    <span>Departments</span>
                </a>
            </li>

            <li>
                <a href="{{ route('users.management') }}" 
                   class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('users.management') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <i class="fas fa-users w-5 mr-3"></i>
                    <span>User Management</span>
                </a>
            </li>
            @endif

            <!-- Divider -->
            <li class="pt-4 mt-4 border-t border-gray-700">
                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">
                    Settings
                </div>
            </li>

            <li>
                <a href="{{ route('profile.edit') }}" 
                   class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('profile.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <i class="fas fa-user w-5 mr-3"></i>
                    <span>Profile</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- User Info & Logout -->
    <div class="border-t border-gray-700 pt-4">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                <i class="fas fa-user text-white"></i>
            </div>
            <div>
                <div class="font-medium text-white">{{ Auth::user()->name }}</div>
                <div class="text-sm text-gray-400">{{ Auth::user()->email }}</div>
            </div>
        </div>
        
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center px-4 py-3 rounded-lg transition-colors duration-200 text-gray-300 hover:bg-red-600 hover:text-white">
                <i class="fas fa-sign-out-alt w-5 mr-3"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</div>

<!-- Mobile Sidebar Overlay -->
<div x-data="{ open: false }" class="md:hidden">
    <!-- Mobile menu button -->
    <div class="fixed top-4 left-4 z-50">
        <button @click="open = !open" class="bg-gray-800 text-white p-2 rounded-lg shadow-lg">
            <i class="fas fa-bars" x-show="!open"></i>
            <i class="fas fa-times" x-show="open"></i>
        </button>
    </div>

    <!-- Mobile sidebar -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="transform -translate-x-full"
         x-transition:enter-end="transform translate-x-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="transform translate-x-0"
         x-transition:leave-end="transform -translate-x-full"
         class="fixed inset-y-0 left-0 z-40 w-64 bg-gray-800 text-white">
        
        <div class="px-4 py-6 flex flex-col h-full">
            <!-- Mobile Logo -->
            <div class="mb-8 mt-12">
                <div class="flex items-center">
                    <i class="fas fa-comments text-2xl text-blue-400 mr-3"></i>
                    <h1 class="text-xl font-bold">Feedback Hub</h1>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <nav class="flex-1">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('dashboard') }}" @click="open = false"
                           class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <i class="fas fa-tachometer-alt w-5 mr-3"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('feedback.index') }}" @click="open = false"
                           class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('feedback.*') && !request()->routeIs('feedback.analytics') && !request()->routeIs('feedback.learner.experience') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <i class="fas fa-comments w-5 mr-3"></i>
                            <span>Feedback</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('feedback.analytics') }}" @click="open = false"
                           class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('feedback.analytics') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <i class="fas fa-chart-bar w-5 mr-3"></i>
                            <span>Analytics</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('feedback.learner.experience') }}" @click="open = false"
                           class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('feedback.learner.experience') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <i class="fas fa-graduation-cap w-5 mr-3"></i>
                            <span>Learner Experience</span>
                        </a>
                    </li>

                    <!-- Mobile Form Builder Section -->
                    <li class="pt-4 mt-4 border-t border-gray-700">
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">
                            Form Builder
                        </div>
                    </li>

                    <li>
                        <a href="{{ route('forms.index') }}" @click="open = false"
                           class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('forms.index') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <i class="fas fa-list w-5 mr-3"></i>
                            <span>My Forms</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('forms.create') }}" @click="open = false"
                           class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('forms.create') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <i class="fas fa-plus-circle w-5 mr-3"></i>
                            <span>Create Form</span>
                        </a>
                    </li>

                    @if(Auth::user()->isAdmin())
                    <!-- Mobile Admin Only Section -->
                    <li class="pt-4 mt-4 border-t border-gray-700">
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">
                            Admin
                        </div>
                    </li>

                    <li>
                        <a href="{{ route('forms.index') }}?show=all" @click="open = false"
                           class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->get('show') === 'all' ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <i class="fas fa-globe w-5 mr-3"></i>
                            <span>All Forms</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('departments.index') }}" @click="open = false"
                           class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('departments.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <i class="fas fa-building w-5 mr-3"></i>
                            <span>Departments</span>
                        </a>
                    </li>

                    <li>
                        <a href="#" @click="open = false; showUserManagement()" 
                           class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 text-gray-300 hover:bg-gray-700 hover:text-white">
                            <i class="fas fa-users w-5 mr-3"></i>
                            <span>User Management</span>
                        </a>
                    </li>
                    @endif

                    <!-- Divider -->
                    <li class="pt-4 mt-4 border-t border-gray-700">
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">
                            Settings
                        </div>
                    </li>

                    <li>
                        <a href="{{ route('profile.edit') }}" @click="open = false"
                           class="flex items-center px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('profile.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <i class="fas fa-user w-5 mr-3"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Mobile User Info & Logout -->
            <div class="border-t border-gray-700 pt-4">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div>
                        <div class="font-medium text-white">{{ Auth::user()->name }}</div>
                        <div class="text-sm text-gray-400">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" @click="open = false" class="w-full flex items-center px-4 py-3 rounded-lg transition-colors duration-200 text-gray-300 hover:bg-red-600 hover:text-white">
                        <i class="fas fa-sign-out-alt w-5 mr-3"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Mobile overlay backdrop -->
    <div x-show="open" @click="open = false" class="fixed inset-0 z-30 bg-black opacity-50"></div>
</div>
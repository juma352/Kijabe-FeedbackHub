<!-- Top Bar -->
<header class="bg-white shadow-sm border-b border-gray-200">
    <div class="flex items-center justify-between h-16 px-6">
        <!-- Mobile menu button (hidden on desktop) -->
        <div class="md:hidden">
            <!-- This will be handled by the mobile sidebar component -->
        </div>

        <!-- Desktop breadcrumb or page title -->
        <div class="hidden md:flex items-center">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <div>
                            <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-gray-500">
                                <i class="fas fa-home w-5 h-5"></i>
                                <span class="sr-only">Home</span>
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 w-4 h-4 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500">
                                @if(request()->routeIs('dashboard'))
                                    Dashboard
                                @elseif(request()->routeIs('feedback.index'))
                                    Feedback
                                @elseif(request()->routeIs('feedback.analytics'))
                                    Analytics
                                @elseif(request()->routeIs('feedback.kijabe.analytics'))
                                    Hospital Analytics
                                @elseif(request()->routeIs('feedback.learner.experience'))
                                    Learner Experience
                                @elseif(request()->routeIs('profile.*'))
                                    Profile
                                @else
                                    {{ ucfirst(request()->segment(1)) }}
                                @endif
                            </span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Right side - User menu and notifications -->
        <div class="flex items-center space-x-4">
            <!-- Notifications -->
            <button class="p-2 text-gray-400 hover:text-gray-500 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                <i class="fas fa-bell w-5 h-5"></i>
                <span class="sr-only">View notifications</span>
            </button>

            <!-- Settings -->
            <button class="p-2 text-gray-400 hover:text-gray-500 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                <i class="fas fa-cog w-5 h-5"></i>
                <span class="sr-only">Settings</span>
            </button>

            <!-- User menu dropdown (desktop only - mobile uses sidebar) -->
            <div class="hidden md:relative md:inline-block" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-gray-600"></i>
                    </div>
                    <span class="ml-2 text-gray-700 font-medium">{{ Auth::user()->name }}</span>
                    <i class="fas fa-chevron-down ml-2 text-gray-400 w-4 h-4"></i>
                </button>

                <div x-show="open" @click.away="open = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 z-10 mt-2 w-48 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5">
                    
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-user-edit w-4 mr-2"></i>
                        Profile Settings
                    </a>
                    
                    <div class="border-t border-gray-100"></div>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt w-4 mr-2"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
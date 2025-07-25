<header class="flex w-[96%] justify-end items-center p-2 justify-self-end text-sm m-3 ml-6 not-has-[nav]:hidden">
    @if (Route::has('login'))
        <nav class="flex items-center justify-between w-full gap-4">
            <button @click="sidebarOpen = !sidebarOpen"
                class="p-2 text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                <!-- Heroicon or Lucide -->
                <!-- Lucide Menu Icon -->
                <svg x-show="!sidebarOpen" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 lucide lucide-menu"
                    fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 6h16M4 12h16M4 18h16" />
                </svg>

                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <!-- Lucide X Icon -->
                <svg x-show="sidebarOpen" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 lucide lucide-x"
                    fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6 6 18M6 6l12 12" />
                </svg>

                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            @auth
                @if (auth()->user()->hasVerifiedEmail())
                    <div class="flex gap-4">
                        <a href="{{ route('admin.products.index') }}">
                            <span
                                class="p-2 text-gray-400 transition duration-75 border-2 border-gray-400 border-solid rounded-full material-icons dark:border-gray-600 dark:text-gray-600 hover:text-gray-900 dark:hover:text-white hover:border-gray-900 dark:hover:border-white">
                                man
                            </span>
                        </a>

                    </div>
                    <a href="{{ route(auth()->user()->type == 1 ? 'admin.dashboard' : 'employer.dashboard') }}"
                        class="inline-block px-5 py-1.5 text-black dark:text-white border border-black hover:border-gray-700 dark:border-gray-400 dark:hover:border-white hover:bg-gray-400 transition duration-200 rounded-sm text-sm leading-normal">
                        Dashboard
                    </a>
                @endif
            @else
                <div class="flex justify-end w-full gap-4">
                    <a href="{{ route('welcome') }}"
                        class="inline-block px-5 py-1.5 text-[#EDEDEC] border border-white hover:border-[#19140035] rounded-sm text-sm leading-normal">
                        Home
                    </a>
                    <a href="{{ route('login') }}"
                        class="inline-block px-5 py-1.5 text-[#EDEDEC] border border-white hover:border-[#19140035] rounded-sm text-sm leading-normal">
                        Log in
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="inline-block px-5 py-1.5 text-[#EDEDEC] border border-white hover:border-[#19140035] rounded-sm text-sm leading-normal">
                            Register
                        </a>
                    @endif
                </div>
            @endauth

        </nav>
    @endif

</header>

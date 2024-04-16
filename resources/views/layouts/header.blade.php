<header class="bg-gray-800 py-6">
    <div class="container mx-auto flex justify-between items-center px-6">
        <div>
            <a href="{{ url('/') }}" class="text-lg font-semibold text-gray-100 no-underline">
                {{ config('app.name', 'Laravel Blog') }}
            </a>
        </div>
        <nav class="space-x-4 text-gray-300 text-sm sm:text-base">
            <a class="no-underline hover:underline" href="/">Home</a>
            <a class="no-underline hover:underline" href="/blog">Blog</a>

            @if (Route::has('login'))
                    @auth
                        <span>{{ Auth::user()->name }}</span>

                        <!-- <div class="mt-3 space-y-1">
                            <x-responsive-nav-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-responsive-nav-link>
                        </div> -->

                        <!-- Authentication -->
                            <a href="{{ route('logout') }}"
                            class="no-underline hover:underline"
                            onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">{{ __('Log Out') }}</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                            </form>
                    @else
                        <a class="no-underline hover:underline" href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Log in</a>

                        @if (Route::has('register'))
                            <a class="no-underline hover:underline" href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">{{ __('Register') }}</a>
                        @endif
                    @endauth
            @endif
        </nav>
    </div>
</header>
<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- ... existing navigation code ... -->

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- ... existing navigation items ... -->

                <!-- Add these new navigation items -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('admin.students')" :active="request()->routeIs('admin.students')">
                        {{ __('Students') }}
                    </x-nav-link>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('admin.school_classes')" :active="request()->routeIs('admin.school_classes')">
                        {{ __('School Classes') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- ... rest of the navigation code ... -->
        </div>
    </div>
</nav>
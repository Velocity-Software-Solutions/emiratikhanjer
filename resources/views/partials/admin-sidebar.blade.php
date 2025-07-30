<aside :class="sidebarOpen ? 'block' : 'hidden'" class="fixed md:relative lg:relative w-[40%] md:w-[25%] min-h-[100%] bg-white p-2 pl-5 border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700 overflow-scroll custom-scrollbar scrollbar-hide">
    <div class="flex flex-col items-center justify-center text-black dark:text-white">
        <img src="{{ asset('storage/images/logo.png') }}" alt="Antiques Shop" class=" rounded-full w-[70px] h-[70px] mb-4">
        <p class="text-xl font-semibold ">{{ auth()->user()->name }}</p>
        <p class="text-sm text-gray-700 dark:text-gray-300 font-regular"> {{ auth()->user()->email }}</p>
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit"
                class="flex items-center justify-center w-full gap-2 p-2 mt-5 mb-5 border-2 border-gray-600 border-solid rounded-md hover:bg-gray-600">
                <span class="material-symbols-outlined ">
                    logout
                </span> Log Out </button>
        </form>
    </div>

    <ul class=" space-y-3 dark:text-[#ffffff] text-lg">


        <li>
            <button
                class="flex items-center w-full gap-2 p-3 toggle rounded-xl hover:bg-gray-300 dark:hover:bg-gray-500 group">
                <span
                    class="text-2xl text-gray-400 transition duration-75 material-symbols-outlined dark:text-gray-600 group-hover:text-gray-900 dark:group-hover:text-white">
                    build
                </span>
                <span class="flex-1 ml-3 text-left whitespace-nowrap">Setup </span>
                <span
                    class="text-black transition duration-100 material-symbols-outlined justify-self-end material-symbols-filled dark:text-white">
                    keyboard_arrow_down
                </span>
            </button>
            <ul class="py-1 space-y-1 dropdown">
                <a href="{{ route('admin.orders.index') }}" class="w-full">
                    <li
                        class="flex items-center w-full gap-2 p-2 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-500 group">
                        <span
                            class="text-2xl text-gray-400 transition duration-75 material-symbols-outlined dark:text-gray-600 group-hover:text-gray-900 dark:group-hover:text-white">
                            receipt_long</span>
                        <span class="flex-1 ml-3 text-left whitespace-nowrap">Orders</span>

                    </li>
                </a>
      

    
                <a href="{{ route('admin.products.index') }}" class="w-full">
                    <li
                        class="flex items-center w-full gap-2 p-2 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-500 group">
                        <span
                            class="text-2xl text-gray-400 transition duration-75 material-symbols-outlined dark:text-gray-600 group-hover:text-gray-900 dark:group-hover:text-white">
                            shopping_bag</span>
                        <span class="flex-1 ml-3 text-left whitespace-nowrap">Products </span>
                    </li>
                </a>
            
                <a href="{{ route('admin.categories.index') }}" class="w-full">
                    <li
                        class="flex items-center w-full gap-2 p-2 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-500 group">
                        <span
                            class="text-2xl text-gray-400 transition duration-75 material-symbols-outlined dark:text-gray-600 group-hover:text-gray-900 dark:group-hover:text-white">
                            category</span>
                        <span class="flex-1 ml-3 text-left whitespace-nowrap">Categories </span>
                    </li>
                </a>
         
            <a href="{{ route('admin.coupons.index') }}" class="w-full">
                <li
                    class="flex items-center w-full gap-2 p-2 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-500 group">
                    <span
                        class="text-2xl text-gray-400 transition duration-75 material-symbols-outlined dark:text-gray-600 group-hover:text-gray-900 dark:group-hover:text-white">
                        redeem </span>
                    <span class="flex-1 ml-3 text-left whitespace-nowrap">Coupons Setup </span>
                </li>
            </a>
      
            <a href="{{ route('admin.shippingOptions.index') }}" class="w-full">
                <li
                    class="flex items-center w-full gap-2 p-2 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-500 group">
                    <span
                        class="text-2xl text-gray-400 transition duration-75 material-symbols-outlined dark:text-gray-600 group-hover:text-gray-900 dark:group-hover:text-white">
                        local_shipping </span>
                    <span class="flex-1 ml-3 text-left whitespace-nowrap">Shipping Options</span>
                </li>
            </a>
       
            <a href="" class="w-full">
                <li
                    class="flex items-center w-full gap-2 p-2 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-500 group">
                    <span
                        class="text-2xl text-gray-400 transition duration-75 material-symbols-outlined dark:text-gray-600 group-hover:text-gray-900 dark:group-hover:text-white">
                        people</span>
                    <span class="flex-1 ml-3 text-left whitespace-nowrap">Users</span>
                </li>
            </a>
        </ul>
        </li>

</aside>

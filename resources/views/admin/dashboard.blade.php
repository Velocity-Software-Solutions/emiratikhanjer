@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

    <div class="max-h-full h-full overflow-scroll scroll scroll-m-0 pt-5 scrollbar-hide">
        <div class="flex w-full justify-evenly flex-wrap">
            <a href="" class="w-1/3 sm:w-1/5">
                <button
                    class="w-full h-[100px] text-left p-3 bg-white dark:bg-gray-800 rounded-md overflow-hidden relative hover:scale-110 hover:shadow-2xl shadow-black dark:shadow-white transition-all duration-300 hover-3d group">

                    <p class="text-2xl font-medium dark:text-white text-gray-700  z-[2] relative">Products</p>
                    <p class="text-4xl font-semibold dark:text-white text-gray-700 transition-all duration-200 m-2">
                  
                    </p>
                </button>
            </a>
            <a href="" class="w-1/3 sm:w-1/5">
                <button
                    class="w-full h-[100px] text-left p-3 bg-white dark:bg-gray-800 rounded-md overflow-hidden relative hover:scale-110 hover:shadow-2xl shadow-black dark:shadow-white transition-all duration-300 hover-3d group">

                    <p class="text-2xl font-medium dark:text-white text-gray-700  z-[2] relative">Pending Orders</p>
                    <p class="text-4xl font-semibold dark:text-white text-gray-700 transition-all duration-200 m-2">
          
                    </p>
                </button>
            </a>
            <a href="" class="w-1/3 sm:w-1/5">
                <button
                    class="w-full h-[100px] text-left p-3 bg-white dark:bg-gray-800 rounded-md overflow-hidden relative hover:scale-110 hover:shadow-2xl shadow-black dark:shadow-white transition-all duration-300 hover-3d group">
                    <p class="text-2xl font-medium dark:text-white text-gray-700  z-[2] relative">Past Orders</p>
                    <p class="text-4xl font-semibold dark:text-white text-gray-700 transition-all duration-200 m-2">
                    </p>
                </button>
            </a>
            <a href="" class="w-1/3 sm:w-1/5">
                <button
                    class="w-full h-[100px] text-left p-3 bg-white dark:bg-gray-800 rounded-md overflow-hidden relative hover:scale-110 hover:shadow-2xl shadow-black dark:shadow-white transition-all duration-300 hover-3d group">
                    <p class="text-2xl font-medium dark:text-white text-gray-700  z-[2] relative">Customers</p>
                    <p class="text-4xl font-semibold dark:text-white text-gray-700 transition-all duration-200 m-2">
                       
                    </p>
                </button>
            </a>
        </div>


@endsection

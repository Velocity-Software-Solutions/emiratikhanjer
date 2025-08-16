@extends('layouts.admin')

@section('title', 'Shipping Options')

@section('content')
<div class="bg-white dark:bg-gray-800 mx-3 rounded-md p-5  overflow-scroll scroll scroll-m-0 scrollbar-hide">
    <h3 class="text-gray-700 dark:text-white font-bold text-4xl mb-6">Shipping Options</h3>

    {{-- Header --}}
    <div class="grid grid-cols-8 text-left text-md font-bold text-black dark:text-white">
        @foreach (['#', 'Name', 'Price', 'Delivery Time', 'Description', 'Country', 'Cities', 'Action'] as $label)
            <div class="px-4 py-3 border-b border-gray-200">{{ $label }}</div>
        @endforeach
    </div>

    {{-- Create Row --}}
    <form action="{{ route('admin.shippingOptions.store') }}" method="POST" class="grid grid-cols-8 border-b" x-data="shippingRow()">
        @csrf
        <div class="px-4 py-3 font-medium dark:text-white">
            {{ ($shippingOptions->currentPage() - 1) * $shippingOptions->perPage() + $shippingOptions->count() + 1 }}
        </div>
        <div class="px-4 py-3"><input name="name" class="form-input w-full" value="{{ old('name') }}"></div>
        <div class="px-4 py-3"><input name="price" type="number" step="0.01" class="form-input w-full" value="{{ old('price') }}"></div>
        <div class="px-4 py-3"><input name="delivery_time" class="form-input w-full" value="{{ old('delivery_time') }}"></div>
        <div class="px-4 py-3"><input name="description" class="form-input w-full" value="{{ old('description') }}"></div>
        <div class="px-4 py-3">
            <select name="country" class="form-select w-full" x-model="country" @change="resetCities()">
                <option value="">Select Country</option>
                <template x-for="(list, name) in countriesMap" :key="name">
                    <option :value="name" x-text="name"></option>
                </template>
            </select>
        </div>
        <div class="px-4 py-3">
            <div class="max-h-32  overflow-y-scroll scroll scroll-m-0 scrollbar-hide space-y-1" x-show="country">
                <template x-for="c in (countriesMap[country] || [])" :key="c">
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" class="rounded text-blue-600" :value="c" x-model="cities">
                        <span x-text="c"></span>
                    </label>
                </template>
            </div>
            <template x-for="c in cities" :key="'new-' + c">
                <input type="hidden" name="cities[]" :value="c">
            </template>
        </div>
        <div class="px-4 py-3 flex items-center justify-center">
            <button type="submit" title="Add">
                <span class="material-icons p-2 rounded-full dark:hover:bg-gray-500 hover:bg-gray-300 dark:text-white">add</span>
            </button>
        </div>
    </form>

    {{-- Existing Rows --}}
    @foreach ($shippingOptions as $option)
        <form action="{{ route('admin.shippingOptions.update', $option->id) }}" method="POST"
              class="grid grid-cols-8" x-data="shippingRow('{{ $option->country }}', {{ Js::from($option->cities ?? []) }})">
            @csrf
            @method('PUT')

            <div class="px-4 py-3 font-medium dark:text-white">{{ $loop->iteration }}</div>
            <div class="px-4 py-3"><input name="name" class="form-input w-full" value="{{ old('name', $option->name) }}"></div>
            <div class="px-4 py-3"><input name="price" type="number" step="0.01" class="form-input w-full" value="{{ old('price', $option->price) }}"></div>
            <div class="px-4 py-3"><input name="delivery_time" class="form-input w-full" value="{{ old('delivery_time', $option->delivery_time) }}"></div>
            <div class="px-4 py-3"><input name="description" class="form-input w-full" value="{{ old('description', $option->description) }}"></div>
            <div class="px-4 py-3">
                <select name="country" class="form-select w-full" x-model="country" @change="syncCities()">
                    <option value="">Select Country</option>
                    <template x-for="(list, name) in countriesMap" :key="name">
                        <option :value="name" x-text="name"></option>
                    </template>
                </select>
            </div>
            <div class="px-4 py-3">
                <div class="max-h-32 overflow-y-scroll scroll scroll-m-0 scrollbar-hide space-y-1" x-show="country">
                    <template x-for="c in (countriesMap[country] || [])" :key="c">
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" class="rounded text-blue-600" :value="c" x-model="cities">
                            <span x-text="c"></span>
                        </label>
                    </template>
                </div>
                <template x-for="c in cities" :key="'edit-' + c">
                    <input type="hidden" name="cities[]" :value="c">
                </template>
            </div>
            <div class="px-4 py-3 flex items-center justify-center gap-2">
                <button type="submit" title="Save">
                    <span class="material-icons p-2 rounded-full dark:hover:bg-gray-500 hover:bg-gray-300 dark:text-white">edit</span>
                </button>
        </form>
        <form action="{{ route('admin.shippingOptions.destroy', $option->id) }}" method="POST"
              class="contents" onsubmit="return confirm('Are you sure?');">
            @csrf
            @method('DELETE')
            <button type="submit" title="Delete">
                <span class="material-icons p-2 rounded-full hover:bg-red-300 text-red-600">delete</span>
            </button>
        </form>
        </div>
    @endforeach

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $shippingOptions->links() }}
    </div>
</div>


    {{-- Alpine helpers --}}
    <script>
        function shippingRow(initialCountry = '', initialCities = []) {
            return {
                country: initialCountry || '',
                cities: Array.isArray(initialCities) ? [...initialCities] : [],
                countriesMap: {
                    'UAE': ['Dubai', 'Abu Dhabi', 'Sharjah', 'Ajman', 'Umm Al Quwain', 'Ras Al Khaimah', 'Fujairah'],
                    'Saudi Arabia': ['Riyadh', 'Jeddah', 'Mecca', 'Medina', 'Dammam', 'Khobar', 'Dhahran', 'Tabuk', 'Abha',
                        'Hail'
                    ],
                    'Kuwait': ['Kuwait City', 'Salmiya', 'Hawally', 'Farwaniya', 'Jahra', 'Fahaheel', 'Mangaf',
                        'Sabah Al Salem', 'Mahboula', 'Abu Halifa'
                    ],
                    'Qatar': ['Doha', 'Al Rayyan', 'Umm Salal', 'Al Wakrah', 'Al Khor', 'Al Daayen', 'Al Shamal',
                        'Al Shahaniya'
                    ],
                    'Oman': ['Muscat', 'Salalah', 'Sohar', 'Nizwa', 'Sur', 'Ibri', 'Barka', 'Rustaq'],
                    'Bahrain': ['Manama', 'Muharraq', 'Riffa', 'Isa Town', 'Sitra', 'Budaiya', 'Hamad Town', "A'ali"],
                },
                resetCities() {
                    this.cities = [];
                },
                syncCities() {
                    // Ensure selected cities still exist in the chosen country
                    const allowed = new Set(this.countriesMap[this.country] || []);
                    this.cities = this.cities.filter(c => allowed.has(c));
                }
            }
        }
    </script>
@endsection

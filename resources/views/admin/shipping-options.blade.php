@extends('layouts.admin')

@section('title', 'Manage Shipping Options')

@section('content')
    <div class="p-6 mx-2 h-full space-y-6 bg-white rounded-md shadow-md dark:bg-gray-800" x-data="{ showNewRow: false }">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Shipping Options</h2>
            <button @click="showNewRow = true" class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                + Add Shipping Option
            </button>
        </div>

        {{-- Success message --}}
        @if (session('success'))
            <div class="px-4 py-2 text-green-700 bg-green-100 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- Table --}}
        <div class="overflow-x-auto border border-gray-200 rounded-md dark:border-gray-700">
            <table class="min-w-full text-sm table-auto">
                <thead class="text-gray-700 bg-gray-100 dark:bg-gray-700 dark:text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">#</th>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Price</th>
                        <th class="px-4 py-2 text-left">Delivery Time</th>
                        <th class="px-4 py-2 text-left">Description</th>
                        <th class="px-4 py-2 text-left">Country</th>
                        <th class="px-4 py-2 text-left">City</th>
                        <th class="px-4 py-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                    {{-- Add Row --}}
                    <tr x-show="showNewRow" x-data="countries" class="bg-gray-50 dark:bg-gray-900">
                        <form action="{{ route('admin.shippingOptions.store') }}" method="POST"
                            class="flex items-center w-full">
                            @csrf
                            <td class="px-4 py-2">New</td>
                            <td class="px-4 py-2"><input name="name" class="text-sm rounded-md form-input w-20" /></td>
                            <td class="px-4 py-2"><input name="price" type="number" step="0.01"
                                    class="w-24 text-sm rounded-md form-input" /></td>
                            <td class="px-4 py-2"><input name="delivery_time" class="w-24 text-sm rounded-md form-input" />
                            </td>
                            <td class="px-4 py-2"><input name="description" class="text-sm rounded-md form-input w-44" />
                            </td>

                            {{-- Country Select --}}
                            <td class="px-4 py-2">
                                <select name="country" x-model="country"
                                    @change="$dispatch('cities-updated', { options: updateCities() })"
                                    class="w-32 text-sm rounded-md form-select">
                                    <option value="">Select Country</option>
                                    <template x-for="(cities, name) in allCountries" :key="name">
                                        <option :value="name" x-text="name"></option>
                                    </template>
                                </select>
                            </td>

                            {{-- Cities Multi Select --}}
                            <td class="px-4 py-2 min-w-28">
                                <template x-if="country">
                                    <x-multi-select name="cities" x-bind:options="citiesOptions"
                                        :old-values="old('cities' ?? [])" />
                                </template>
                            </td>

                            <td class="px-4 py-2 text-center">
                                <button type="submit"
                                    class="flex items-center justify-center px-2 py-1 text-xs font-semibold text-white transition bg-green-500 rounded hover:bg-green-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="M8 12h8M12 8v8" />
                                    </svg>
                                    Add
                                </button>
                            </td>
                        </form>
                    </tr>


                    {{-- Existing Shipping Options --}}
                    @foreach ($shippingOptions as $option)
                        <tr x-data="{
                            country: '{{ $option->country }}',
                            city: '{{ $option->city }}',
                            countries: {
                                'UAE': ['Dubai', 'Abu Dhabi', 'Sharjah', 'Ajman', 'Umm Al Quwain', 'Ras Al Khaimah', 'Fujairah'],
                                'Saudi Arabia': ['Riyadh', 'Jeddah', 'Mecca', 'Medina', 'Dammam', 'Khobar', 'Dhahran', 'Tabuk', 'Abha', 'Hail'],
                                'Kuwait': ['Kuwait City', 'Salmiya', 'Hawally', 'Farwaniya', 'Jahra', 'Fahaheel', 'Mangaf', 'Sabah Al Salem', 'Mahboula', 'Abu Halifa'],
                                'Qatar': ['Doha', 'Al Rayyan', 'Umm Salal', 'Al Wakrah', 'Al Khor', 'Al Daayen', 'Al Shamal', 'Al Shahaniya'],
                                'Oman': ['Muscat', 'Salalah', 'Sohar', 'Nizwa', 'Sur', 'Ibri', 'Barka', 'Rustaq'],
                                'Bahrain': ['Manama', 'Muharraq', 'Riffa', 'Isa Town', 'Sitra', 'Budaiya', 'Hamad Town', 'A\'ali']
                        
                            }
                        }">
                            <form action="{{ route('admin.shippingOptions.update', $option->id) }}" method="POST"
                                class="flex items-center w-full">
                                @csrf
                                @method('PUT')
                                <td class="px-4 py-2">{{ $loop->iteration }}</td>
                                <td class="px-4 py-2"><input name="name" value="{{ $option->name }}"
                                        class="text-sm rounded-md form-input w-44" /></td>
                                <td class="px-4 py-2"><input name="price" value="{{ $option->price }}" type="number"
                                        step="0.01" class="w-24 text-sm rounded-md form-input" /></td>
                                <td class="px-4 py-2"><input name="delivery_time" value="{{ $option->delivery_time }}"
                                        class="w-24 text-sm rounded-md form-input" /></td>
                                <td class="px-4 py-2"><input name="description" value="{{ $option->description }}"
                                        class="text-sm rounded-md form-input w-44" /></td>
                                <td class="px-4 py-2">
                                    <select name="country" x-model="country" class="w-32 text-sm rounded-md form-select">
                                        <option value="">Select Country</option>
                                        <template x-for="(cities, name) in countries" :key="name">
                                            <option :value="name" x-text="name" :selected="country === name">
                                            </option>
                                        </template>
                                    </select>
                                </td>
                                <td class="px-4 py-2" x-data="{ cities: {{ Js::from($option->cities ?? []) }} }">
                                    <div class="w-48 p-2 overflow-y-auto bg-white border rounded shadow max-h-40"
                                        x-show="country">
                                        <template x-for="c in countries[country] || []" :key="c">
                                            <label class="flex items-center space-x-2 text-sm text-gray-700">
                                                <input type="checkbox" :value="c" x-model="cities"
                                                    class="text-blue-600 border-gray-300 rounded">
                                                <span x-text="c"></span>
                                            </label>
                                        </template>

                                        <!-- Hidden inputs for form submission -->
                                        <template x-for="c in cities" :key="'edit-' + c">
                                            <input type="hidden" name="cities[]" :value="c">
                                        </template>
                                    </div>
                                </td>

                                <td class="flex items-center justify-center px-4 py-2 space-x-1">
                                    <button type="submit"
                                        class="p-1 text-xs text-white transition bg-blue-500 rounded hover:bg-blue-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                                            <path d="m15 5 4 4" />
                                        </svg>
                                    </button>
                            </form>
                            <form action="{{ route('admin.shippingOptions.destroy', $option->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="p-1 text-xs text-white transition bg-red-500 rounded hover:bg-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                                        <path d="M3 6h18" />
                                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                    </svg>
                                </button>
                            </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="flex justify-end pt-4">
            {{ $shippingOptions->links('pagination::tailwind') }}
        </div>
    </div>
    <script>
        function multiSelect(options, fieldName, initial) {
            return {
                open: false,
                query: '',
                selected: [],
                filtered: [],
                allOptions: options,
                name: fieldName,

                init() {
                    console.log(this.allOptions)
                    this.selected = Array.from(new Set(initial.map(id => String(id))));
                    this.filtered = this.allOptions;

                },

                toggle(id) {
                    id = String(id);
                    if (this.selected.includes(id)) {
                        this.selected = this.selected.filter(i => i !== id);
                    } else {
                        this.selected.push(id);
                    }
                    console.log(this.selected);

                },

                remove(id) {
                    this.selected = this.selected.filter(i => i !== String(id));

                },

                filter() {
                    const q = this.query.toLowerCase().trim();
                    this.filtered = q ?
                        this.allOptions.filter(opt => opt.name.toLowerCase().includes(q)) :
                        this.allOptions;
                },

                getNameById(id) {
                    id = String(id);
                    const item = this.allOptions.find(opt => String(opt.id) === id);
                    return item ? item.name : '';
                },
                updateOptions(newOptions) {
                    this.allOptions = newOptions;
                    this.selected = this.selected.filter(id => this.allOptions.some(opt => opt.id === id));
                    this.filter();
                }


            };
        }

        function countries() {
            return {
                country: '',
                citiesOptions: [],
                allCountries: {
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
                    'Bahrain': ['Manama', 'Muharraq', 'Riffa', 'Isa Town', 'Sitra', 'Budaiya', 'Hamad Town', 'A\'ali']
                },
                updateCities() {
                    return (this.allCountries[this.country] || []).map((obj, index) => ({
                        id: obj,
                        name: obj
                    }));
                    console.log('Updated cities:', this.citiesOptions);
                }
            }
        }
    </script>
@endsection

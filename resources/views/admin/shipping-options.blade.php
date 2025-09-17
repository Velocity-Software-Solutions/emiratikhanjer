@extends('layouts.admin')

@section('title', 'Shipping Options')

@section('content')
    <div class="bg-white dark:bg-gray-900 mx-3 rounded-md p-5 overflow-scroll custom-scrollbar scrollbar-hide">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-gray-800 dark:text-white font-bold text-3xl">Shipping Options</h3>
            <span class="text-sm text-gray-500 dark:text-gray-400">Total: {{ $shippingOptions->total() }}</span>
        </div>

        {{-- Cards grid --}}
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">

            {{-- Create Card --}}
            <form action="{{ route('admin.shipping-options.store') }}" method="POST" x-data="shippingRow()"
                class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm hover:shadow transition p-5">
                @csrf

                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white">New Shipping Option</h4>
                    <span
                        class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 text-xs text-gray-600 dark:text-gray-300">
                        {{ ($shippingOptions->currentPage() - 1) * $shippingOptions->perPage() + $shippingOptions->count() + 1 }}
                    </span>
                </div>

                <div class="space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="form-label">Name</label>
                            <input name="name" class="form-input w-full" value="{{ old('name') }}">
                        </div>
                        <div>
                            <label class="form-label">Arabic Name</label>
                            <input name="name_ar" class="form-input w-full" value="{{ old('name_ar') }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="form-label">Price</label>
                            <input name="price" type="number" step="0.01" class="form-input w-full"
                                value="{{ old('price') }}">
                        </div>
                        <div>
                            <label class="form-label">Delivery Time</label>
                            <input name="delivery_time" class="form-input w-full" value="{{ old('delivery_time') }}">
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Description</label>
                        <input name="description" class="form-input w-full" value="{{ old('description') }}">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="form-label">Country</label>
                            <select name="country" class="form-select w-full" x-model="country" @change="resetCities()">
                                <option value="">Select Country</option>
                                <template x-for="(list, name) in countriesMap" :key="name">
                                    <option :value="name" x-text="name"></option>
                                </template>
                            </select>
                        </div>

                        <div>
                            <label class="form-label">Cities</label>
                            <div class="max-h-28 overflow-y-auto border border-gray-200 dark:border-gray-700 rounded p-2 space-y-1"
                                x-show="country">
                                <template x-for="c in (countriesMap[country] || [])" :key="c">
                                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
                                        <input type="checkbox" class="rounded text-blue-600 focus:ring-0"
                                            :value="c" x-model="cities">
                                        <span x-text="c"></span>
                                    </label>
                                </template>
                            </div>

                            {{-- Selected chips --}}
                            <div class="mt-2 flex flex-wrap gap-1" x-show="cities.length">
                                <template x-for="c in cities" :key="'chip-new-' + c">
                                    <span
                                        class="px-2 py-0.5 text-xs rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200"
                                        x-text="c"></span>
                                </template>
                            </div>

                            {{-- Hidden inputs --}}
                            <template x-for="c in cities" :key="'new-' + c">
                                <input type="hidden" name="cities[]" :value="c">
                            </template>
                        </div>
                    </div>
                </div>

                <div class="mt-5 flex justify-end">
                    <button type="submit" title="Add"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-black text-white hover:bg-black/90">
                        <span class="material-icons text-base">add</span>
                        Save
                    </button>
                </div>
            </form>

            {{-- Existing Cards --}}
            @foreach ($shippingOptions as $option)
                <div
                    class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm hover:shadow transition p-5">

                    {{-- UPDATE FORM (fields only, no submit button here) --}}
                    <form id="form-update-{{ $option->id }}"
                        action="{{ route('admin.shipping-options.update', $option->id) }}" method="POST"
                        x-data="shippingRow({{ Js::from($option->country) }}, {{ Js::from($option->cityItems->pluck('city')->values()) }})" @csrf @method('PUT') <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-white">#{{ $loop->iteration }} • Edit</h4>
                        <span
                            class="text-xs px-2 py-1 rounded bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                            ID: {{ $option->id }}
                        </span>
                </div>

                <div class="space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="form-label">Name</label>
                            <input name="name" class="form-input w-full" value="{{ old('name', $option->name) }}">
                        </div>
                        <div>
                            <label class="form-label">Arabic Name</label>
                            <input name="name_ar" class="form-input w-full" value="{{ old('name_ar', $option->name_ar) }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="form-label">Price</label>
                            <input name="price" type="number" step="0.01" class="form-input w-full"
                                value="{{ old('price', $option->price) }}">
                        </div>
                        <div>
                            <label class="form-label">Delivery Time</label>
                            <input name="delivery_time" class="form-input w-full"
                                value="{{ old('delivery_time', $option->delivery_time) }}">
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Description</label>
                        <input name="description" class="form-input w-full"
                            value="{{ old('description', $option->description) }}">
                    </div>

                    <div>
                        <label class="form-label">Country</label>
                        <select name="country" class="form-select w-full" x-model="country" @change="syncCities()">
                            <option value="">Select Country</option>
                            <template x-for="(list, name) in countriesMap" :key="name">
                                <option :value="name" :selected="country == name" x-text="name"></option>
                            </template>
                        </select>
                    </div>


                    <div>
                        <label class="form-label">Cities</label>
                        <div class="max-h-28 overflow-y-auto border border-gray-200 dark:border-gray-700 rounded p-2 space-y-1"
                            x-show="country">
                            <template x-for="c in (countriesMap[country] || [])" :key="c">
                                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
                                    <input type="checkbox" class="rounded text-blue-600 focus:ring-0"
                                        :value="c" x-model="cities">
                                    <span x-text="c"></span>
                                </label>
                            </template>
                        </div>

                        {{-- Selected chips --}}
                        <div class="mt-2 flex flex-wrap gap-1" x-show="cities.length">
                            <template x-for="c in cities" :key="'chip-edit-' + c">
                                <span
                                    class="px-2 py-0.5 text-xs rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200"
                                    x-text="c"></span>
                            </template>
                        </div>

                        {{-- Hidden inputs --}}
                        <template x-for="c in cities" :key="'edit-' + c">
                            <input type="hidden" name="cities[]" :value="c">
                        </template>
                    </div>
                </div>
                </form>

                {{-- FOOTER with Save (submits update form by ID) + Delete (separate form) --}}
                <div class="mt-5 flex items-center justify-between">
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        Updated: {{ optional($option->updated_at)->format('Y-m-d H:i') ?? '—' }}
                    </div>
                    <div class="flex items-center gap-2">
                        {{-- Save button outside update form --}}
                        <button type="submit" form="form-update-{{ $option->id }}" title="Save"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-black text-white hover:bg-black/90">
                            <span class="material-icons text-base">edit</span>
                            Save
                        </button>

                        {{-- Delete is a completely separate form --}}
                        <form action="{{ route('admin.shipping-options.destroy', $option->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Delete"
                                class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-red-100 text-red-700 hover:bg-red-200">
                                <span class="material-icons text-base">delete</span>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>

        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $shippingOptions->links() }}
    </div>
    </div>

    {{-- Alpine helpers + tiny form UI presets --}}
    <style>
        .form-label {
            @apply block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1;
        }

        .form-input {
            @apply px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 w-full focus:outline-none focus:ring-2 focus:ring-black/30;
        }

        .form-select {
            @apply px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 w-full focus:outline-none focus:ring-2 focus:ring-black/30;
        }
    </style>

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
                    const allowed = new Set(this.countriesMap[this.country] || []);
                    this.cities = this.cities.filter(c => allowed.has(c));
                }
            }
        }
    </script>
@endsection

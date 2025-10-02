@extends('layouts.app')

@section('content')
<!-- ================= HERO ================= -->
<style>
.hidden{
    display: none;
}
</style>
<section class="relative min-h-[72vh] grid place-items-center overflow-hidden rounded-b-3xl">
  <img src="{{ asset('images/hero-bg.jpg') }}" alt="Hero" class="absolute inset-0 w-full h-full object-cover" />
  <div class="absolute inset-0 bg-gradient-to-b from-black/50 to-black/35"></div>

  <div class="relative z-10 max-w-4xl mx-auto text-center px-4">
    <h1 class="montaga-regular text-white text-4xl sm:text-6xl md:text-7xl leading-[1.05] drop-shadow">
      {{ __('home.hero_title') }}
    </h1>
    <p class="mt-4 text-white/95 text-base sm:text-lg md:text-xl">
      {{ __('home.hero_subtitle') }}
    </p>

    <div class="mt-6">
      <a href="{{ route('products.index') }}"
         class="inline-flex items-center justify-center px-5 py-2 rounded-full
                text-white font-medium bg-amber-700 hover:bg-amber-800
                shadow-md transition-transform duration-200 hover:-translate-y-0.5">
        {{ __('home.featured_khinjars') }}
      </a>
    </div>
  </div>
</section>

<!-- ================= TRUST BAR ================= -->
<section class="max-w-6xl mx-auto px-4 mt-6 sm:mt-10">
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <div class="flex items-center gap-3 bg-white border border-black/10 rounded-xl px-4 py-3 shadow-sm">
      <div class="w-9 h-9 rounded-full grid place-items-center text-white bg-stone-800">
        <svg viewBox="0 0 24 24" class="w-5 h-5 fill-current" aria-hidden="true"><path d="M12 2l7 3v6c0 5-3.4 9.3-7 11-3.6-1.7-7-6-7-11V5l7-3z"/></svg>
      </div>
      <div class="text-stone-800 text-sm font-medium">{{ __('home.trust_authentic') }}</div>
    </div>
    <div class="flex items-center gap-3 bg-white border border-black/10 rounded-xl px-4 py-3 shadow-sm">
      <div class="w-9 h-9 rounded-full grid place-items-center text-white bg-stone-800">
        <svg viewBox="0 0 24 24" class="w-5 h-5 fill-current" aria-hidden="true"><path d="M2 21l9-9 3 3-9 9H2v-3zM14.7 3.3l6 6-2.8 2.8-6-6L14.7 3.3zM11 7l-2-2 2-2 2 2-2 2z"/></svg>
      </div>
      <div class="text-stone-800 text-sm font-medium">{{ __('home.trust_handcrafted') }}</div>
    </div>
    <div class="flex items-center gap-3 bg-white border border-black/10 rounded-xl px-4 py-3 shadow-sm">
      <div class="w-9 h-9 rounded-full grid place-items-center text-white bg-stone-800">
        <svg viewBox="0 0 24 24" class="w-5 h-5 fill-current" aria-hidden="true"><path d="M3 3h13v10h-1.5a3.5 3.5 0 00-3.45 3H7.95A3.5 3.5 0 004.5 13H3V3zm16 6l2 3v4h-1.55a3.5 3.5 0 00-3.45-3H16V9h3zM6 20a2 2 0 110-4 2 2 0 010 4z"/></svg>
      </div>
      <div class="text-stone-800 text-sm font-medium">{{ __('home.trust_fast_shipping') }}</div>
    </div>
    <div class="flex items-center gap-3 bg-white border border-black/10 rounded-xl px-4 py-3 shadow-sm">
      <div class="w-9 h-9 rounded-full grid place-items-center text-white bg-stone-800">
        <svg viewBox="0 0 24 24" class="w-5 h-5 fill-current" aria-hidden="true"><path d="M17 3H7a2 2 0 00-2 2v13l7-3 7 3V5a2 2 0 00-2-2z"/></svg>
      </div>
      <div class="text-stone-800 text-sm font-medium">{{ __('home.trust_certified') }}</div>
    </div>
  </div>
</section>

<!-- ================= ABOUT ================= -->
<section class="relative mt-14">
  <div class="absolute inset-0 -z-10 bg-gradient-to-b from-white via-amber-50 to-white"></div>

  <div class="max-w-6xl mx-auto px-4">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">

      <!-- Copy -->
      <div class="order-2 lg:order-1">
        <div class="inline-flex items-center gap-2 rounded-full bg-amber-100 text-amber-800 px-3 py-1 text-xs font-semibold">
          <span class="w-1.5 h-1.5 rounded-full bg-amber-700"></span>
          {{ __('home.about_us') }}
        </div>

        <h2 class="montaga-regular text-stone-900 text-3xl sm:text-4xl mt-3">
          {{ __('home.about_us') }}
        </h2>

        <p class="mt-4 text-stone-700 leading-relaxed">
          {{ __('home.about_us_text') }}
        </p>

        <ul class="mt-6 space-y-3">
          <li class="flex items-start gap-3">
            <span class="mt-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-amber-600 text-white text-[10px]">✓</span>
            <span class="text-stone-800">{{ __('home.bullets_authentic') }}</span>
          </li>
          <li class="flex items-start gap-3">
            <span class="mt-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-amber-600 text-white text-[10px]">✓</span>
            <span class="text-stone-800">{{ __('home.bullets_curated') }}</span>
          </li>
          <li class="flex items-start gap-3">
            <span class="mt-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-amber-600 text-white text-[10px]">✓</span>
            <span class="text-stone-800">{{ __('home.bullets_shipping') }}</span>
          </li>
        </ul>

        <div class="mt-6 grid grid-cols-3 gap-3">
          <div class="rounded-xl border border-black/10 bg-white px-4 py-3 text-center">
            <div class="text-xl font-bold text-stone-900">100%</div>
            <div class="text-xs text-stone-600">{{ __('home.stats_handmade') }}</div>
          </div>
          <div class="rounded-xl border border-black/10 bg-white px-4 py-3 text-center">
            <div class="text-xl font-bold text-stone-900">15+</div>
            <div class="text-xs text-stone-600">{{ __('home.stats_pieces') }}</div>
          </div>
          <div class="rounded-xl border border-black/10 bg-white px-4 py-3 text-center">
            <div class="text-xl font-bold text-stone-900">100%</div>
            <div class="text-xs text-stone-600">{{ __('home.stats_authentic') }}</div>
          </div>
        </div>

        <div class="mt-7">
          <a href="{{ route('home') }}"
             class="inline-flex items-center justify-center px-5 py-2 rounded-full
                    text-white font-medium bg-amber-700 hover:bg-amber-800
                    shadow-md transition-transform duration-200 hover:-translate-y-0.5">
            {{ __('home.featured_khinjars') }}
          </a>
        </div>
      </div>

      <!-- Images -->
      <div class="order-1 lg:order-2">
        <div class="grid grid-cols-3 gap-3">
          @foreach ([
            ['src' => asset('images/khinjar-1.jpg'), 'label' => __('home.card_collectors_pick')],
            ['src' => asset('images/khinjar-2.png'), 'label' => __('home.card_handcrafted')],
            ['src' => asset('images/khinjar-3.png'), 'label' => __('home.card_heritage_grade')],
          ] as $i => $img)
            <figure class="relative rounded-2xl border border-black/10 bg-white shadow-md p-2">
              <div class="w-full h-56 sm:h-64 lg:h-72 grid place-items-center bg-white rounded-xl overflow-hidden">
                <img src="{{ $img['src'] }}" alt="{{ __('home.card_alt', ['number' => $i+1]) }}" class="h-full w-full object-cover" />
              </div>
              <figcaption class="absolute top-3 left-3 text-[10px] font-semibold text-amber-700 bg-amber-100/80 px-2 py-0.5 rounded">
                {{ $img['label'] }}
              </figcaption>
            </figure>
          @endforeach
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ================= FEATURED (optional) ================= -->
@if(isset($featuredProducts) && $featuredProducts->isNotEmpty())
<section class="max-w-6xl mx-auto px-4 mt-14">
  <h2 class="montaga-regular text-3xl sm:text-4xl text-stone-800 text-center">{{ __('home.featured_khinjars') }}</h2>
  <p class="mt-2 text-center text-stone-600">{{ __('home.featured_subtitle') }}</p>

  <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
    @foreach($featuredProducts as $p)
      <div class="bg-white border border-black/10 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition">
        <div class="grid place-items-center bg-white h-64">
          @if($p->images->count())
            <img src="{{ asset('storage/'.$p->images->first()->image_path) }}"
                 alt="{{ $p->name }}"
                 class="max-h-full max-w-full object-contain">
          @endif
        </div>
        <div class="p-4">
          <h3 class="font-semibold text-stone-800">
            {{ app()->getLocale() === 'ar' && $p->name_ar ? $p->name_ar : $p->name }}
          </h3>
          <p class="mt-1 text-stone-600 text-sm">
            {{ \Illuminate\Support\Str::limit(app()->getLocale() === 'ar' && $p->description_ar ? $p->description_ar : $p->description, 80) }}
          </p>

          <div class="mt-3 flex items-center justify-between">
            <span class="text-amber-700 font-bold text-lg">
              {{ __('product.currency_aed') }} {{ number_format($p->price, 2) }}
            </span>
            <a href="{{ route('products.show', $p->slug ?? $p->id) }}"
               class="inline-flex items-center justify-center px-4 py-2 rounded-full
                      text-white font-medium bg-amber-700 hover:bg-amber-800
                      transition-transform duration-200 hover:-translate-y-0.5">
              {{ __('product.view') }}
            </a>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="mt-6 text-center">
    <a href="{{ route('products.index') }}"
       class="inline-flex items-center justify-center px-5 py-2 rounded-full
              text-white font-medium bg-stone-800 hover:bg-stone-900
              transition-transform duration-200 hover:-translate-y-0.5">
      {{ __('home.browse_all') }}
    </a>
  </div>
</section>
@endif

<!-- ================= CONTACT ================= -->
<section class="max-w-5xl mx-auto px-4 mt-16 mb-10">
  <div class="grid grid-cols-1 gap-6">
    <div class="bg-white border border-black/10 rounded-2xl shadow-md p-6 sm:p-8">
      <h2 class="montaga-regular text-3xl text-stone-800 text-center">{{ __('home.contact_us') }}</h2>

      <form id="contactForm" class="mt-6 max-w-2xl mx-auto">
        {{-- Honeypot fields --}}
        <input type="text" name="_hp" value="" class="hidden" tabindex="-1" autocomplete="off" />
        <input type="hidden" name="_hpt" id="hpt" value="">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="name" class="block text-sm text-stone-700">{{ __('home.name') }}</label>
            <input id="name" name="name" type="text" placeholder="{{ __('home.placeholder_name') }}"
                   class="mt-1 w-full rounded-lg border border-black/20 px-3 py-2
                          focus:outline-none focus:border-amber-600 focus:ring-2 focus:ring-amber-200">
          </div>
          <div>
            <label for="email" class="block text-sm text-stone-700">{{ __('home.email_address') }}</label>
            <input id="email" name="email" type="email" placeholder="{{ __('home.placeholder_email') }}"
                   class="mt-1 w-full rounded-lg border border-black/20 px-3 py-2
                          focus:outline-none focus:border-amber-600 focus:ring-2 focus:ring-amber-200">
          </div>
        </div>

        <div class="mt-4">
          <label for="message" class="block text-sm text-stone-700">{{ __('home.message') }}</label>
          <textarea id="message" name="message" rows="4"
                    class="mt-1 w-full rounded-lg border border-black/20 px-3 py-2
                           focus:outline-none focus:border-amber-600 focus:ring-2 focus:ring-amber-200"></textarea>
        </div>

        <div class="mt-6 flex items-center justify-center">
          <button id="contactSubmit" type="submit"
                  class="inline-flex items-center justify-center px-5 py-2 rounded-full
                         text-white font-medium bg-amber-700 hover:bg-amber-800
                         transition-transform duration-200 hover:-translate-y-0.5">
            <svg id="spinner" class="hidden -ml-1 mr-2 h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
            </svg>
            <span>{{ __('home.submit') }}</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  {{-- Toast --}}
  <div id="toast"
       class="fixed right-4 bottom-4 bg-black text-white px-4 py-3 rounded-xl shadow-lg
              opacity-0 translate-y-2 transition duration-200 pointer-events-none">
    <span id="toastMsg">{{ __('home.toast_success') }}</span>
  </div>
</section>

{{-- Single, unified JS (no duplicates) --}}
<script>
(function initHoneypotTs(){
  const hpt = document.getElementById('hpt');
  if (hpt) hpt.value = Math.floor(Date.now() / 1000);
})();

const form = document.getElementById('contactForm');
const btn  = document.getElementById('contactSubmit');
const spin = document.getElementById('spinner');
const toast = document.getElementById('toast');
const toastMsg = document.getElementById('toastMsg');

function showToast(msg, ok=true){
  toastMsg.textContent = msg || (ok ? @json(__('home.toast_success')) : @json(__('home.toast_error')));
  toast.classList.remove('opacity-0','translate-y-2');
  toast.classList.add('opacity-100','translate-y-0');
  toast.style.backgroundColor = ok ? '#111827' : '#991B1B';
  setTimeout(()=> {
    toast.classList.add('opacity-0','translate-y-2');
    toast.classList.remove('opacity-100','translate-y-0');
  }, 2600);
}

form?.addEventListener('submit', async (e) => {
  e.preventDefault();
  spin.classList.remove('hidden');
  btn.setAttribute('disabled', 'true');

  const payload = {
    name:   form.name.value || '',
    email:  form.email.value || '',
    message:form.message.value || '',
    _hp:    form._hp.value || '',
    _hpt:   form._hpt.value || '',
  };

  try{
    const res = await fetch(@json(route('api.contact')), {
      method:'POST',
      headers:{ 'Content-Type':'application/json', 'Accept':'application/json' },
      body: JSON.stringify(payload),
    });

    const json = await res.json().catch(()=>({}));

    if (!res.ok) {
      showToast(json?.message || @json(__('home.toast_error')), false);
    } else {
      showToast(json?.message || @json(__('home.toast_success')), true);
      form.reset();
      const hpt = document.getElementById('hpt');
      if (hpt) hpt.value = Math.floor(Date.now() / 1000);
    }
  } catch(err){
    console.error(err);
    showToast(@json(__('home.toast_error')), false);
  } finally{
    spin.classList.add('hidden');
    btn.removeAttribute('disabled');
  }
});
</script>
@endsection

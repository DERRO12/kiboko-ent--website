@props(['logo' => null])
@php
  $items = Cache::rememberForever('nav.header', fn () => \App\Models\NavigationItem::where('location', 'header')->where('is_active', true)->orderBy('sort_order')->get());
  $phone = setting('contact_phone'); $email = setting('contact_email');
  $social = array_filter(['facebook-f' => setting('social_facebook'), 'x-twitter' => setting('social_x'), 'linkedin-in' => setting('social_linkedin'), 'instagram' => setting('social_instagram'), 'youtube' => setting('social_youtube')]);
@endphp
<div class="topbar d-none d-lg-block">
  <div class="container d-flex align-items-center gap-4">
    @if ($phone)<a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}"><i class="fa-solid fa-phone"></i> {{ $phone }}</a>@endif
    @if ($email)<a href="mailto:{{ $email }}"><i class="fa-solid fa-envelope"></i> {{ $email }}</a>@endif
    @if (setting('business_hours'))<span><i class="fa-regular fa-clock"></i> {{ Str::before(setting('business_hours'), "\n") }}</span>@endif
    <div class="ms-auto d-flex align-items-center gap-3">
      @if (setting('store_url'))<a href="{{ setting('store_url') }}" target="_blank" rel="noopener" class="tb-store"><i class="fa-solid fa-bag-shopping"></i> Online Store</a>@endif
      @foreach ($social as $icon => $url)<a href="{{ $url }}" target="_blank" rel="noopener" aria-label="{{ $icon }}"><i class="fa-brands fa-{{ $icon }}"></i></a>@endforeach
    </div>
  </div>
</div>
<header class="site-header" id="siteHeader">
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand" href="{{ url('/') }}">
        @if ($logo)<img src="{{ $logo->url }}" alt="{{ $logo->alt ?: setting('site_name') }}" height="52">@else<span class="brand-text">{{ setting('site_name', 'Kiboko') }}</span>@endif
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation"><i class="fa-solid fa-bars"></i></button>
      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav ms-auto align-items-lg-center">
          @foreach ($items as $item)
            <li class="nav-item"><a href="{{ $item->href }}" @class(['nav-link', 'active' => $item->isCurrent()]) @if($item->isCurrent()) aria-current="page" @endif @if($item->new_tab) target="_blank" rel="noopener" @endif>{{ $item->label }}</a></li>
          @endforeach
          @if (setting('header_cta_label'))
            <li class="nav-item ms-lg-2 mt-2 mt-lg-0"><a href="{{ link_url(setting('header_cta_url', '/contact-us')) }}" class="btn btn-kiboko btn-sm nav-cta">{{ setting('header_cta_label') }}</a></li>
          @endif
        </ul>
      </div>
    </div>
  </nav>
</header>

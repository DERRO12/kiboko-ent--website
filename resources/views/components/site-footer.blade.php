@props(['logo' => null])
@php
  $links = Cache::rememberForever('nav.footer', fn () => \App\Models\NavigationItem::where('location', 'footer')->where('is_active', true)->orderBy('sort_order')->get());
  $social = array_filter(['facebook-f' => setting('social_facebook'), 'x-twitter' => setting('social_x'), 'linkedin-in' => setting('social_linkedin'), 'instagram' => setting('social_instagram'), 'youtube' => setting('social_youtube')]);
@endphp
<footer class="site-footer">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4">
        @if ($logo)<img src="{{ $logo->url }}" alt="{{ $logo->alt ?: setting('site_name') }}" class="footer-logo" loading="lazy">@else<div class="brand-text text-white">{{ setting('site_name') }}</div>@endif
        <p class="mt-3">{{ setting('footer_about') }}</p>
        <div class="social">@foreach ($social as $icon => $url)<a href="{{ $url }}" target="_blank" rel="noopener" aria-label="{{ $icon }}"><i class="fa-brands fa-{{ $icon }}"></i></a>@endforeach</div>
      </div>
      <div class="col-6 col-lg-3 offset-lg-1">
        <h5>Quick Links</h5>
        <ul class="list-unstyled">@foreach ($links as $l)<li><a href="{{ $l->href }}" @if($l->new_tab) target="_blank" rel="noopener" @endif>{{ $l->label }}</a></li>@endforeach</ul>
      </div>
      <div class="col-6 col-lg-4">
        <h5>Get in Touch</h5>
        <ul class="list-unstyled contact-mini">
          @if (setting('contact_address'))<li><i class="fa-solid fa-location-dot"></i> {{ setting('contact_address') }}</li>@endif
          @if (setting('contact_phone'))<li><i class="fa-solid fa-phone"></i> <a href="tel:{{ preg_replace('/[^0-9+]/', '', setting('contact_phone')) }}">{{ setting('contact_phone') }}</a></li>@endif
          @if (setting('contact_email'))<li><i class="fa-solid fa-envelope"></i> <a href="mailto:{{ setting('contact_email') }}">{{ setting('contact_email') }}</a></li>@endif
          @if (setting('business_hours'))<li><i class="fa-solid fa-clock"></i> {{ setting('business_hours') }}</li>@endif
        </ul>
      </div>
    </div>
    <div class="footer-bottom">{{ str_replace('{year}', date('Y'), setting('copyright', '© {year} Kiboko Enterprises. All rights reserved.')) }}</div>
  </div>
</footer>

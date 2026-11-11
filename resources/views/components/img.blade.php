@props(['media' => null, 'alt' => null, 'sizes' => '(max-width: 768px) 100vw, 50vw', 'eager' => false])
@php
  $fallback = setting_media('fallback_image');
  $m = $media ?? $fallback;
  $src = $m?->url ?? asset('assets/images/fallback.svg');
@endphp
<img src="{{ $src }}"
  @if ($m && $m->thumb_path) srcset="{{ $m->thumb_url }} 640w, {{ $m->url }} {{ $m->width ?: 1920 }}w" sizes="{{ $sizes }}" @endif
  @if ($m && $m->width) width="{{ $m->width }}" height="{{ $m->height }}" @endif
  alt="{{ $alt ?? $m?->alt ?? '' }}" loading="{{ $eager ? 'eager' : 'lazy' }}" decoding="async" {{ $attributes }}>

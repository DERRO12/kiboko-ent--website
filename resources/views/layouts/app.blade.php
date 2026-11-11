@php
  $siteName = setting('site_name', 'Kiboko Enterprises');
  $meta = $meta ?? [];
  $title = $meta['full_title'] ?? (($meta['title'] ?? $siteName).' | '.$siteName);
  $desc = Str::limit(strip_tags($meta['description'] ?? '') ?: setting('seo_description', ''), 300);
  $ogImage = $meta['image'] ?? setting_media('og_image') ?? setting_media('logo');
  $canonical = $meta['canonical'] ?? url()->current();
  $logo = setting_media('logo');
  $favicon = setting_media('favicon');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $title }}</title>
<meta name="description" content="{{ $desc }}">
<link rel="canonical" href="{{ $canonical }}">
@if (! empty($meta['noindex']))<meta name="robots" content="noindex,nofollow">@endif
<meta property="og:type" content="{{ $meta['og_type'] ?? 'website' }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $desc }}">
<meta property="og:url" content="{{ $canonical }}">
@if ($ogImage)<meta property="og:image" content="{{ $ogImage->url }}">@endif
<meta name="twitter:card" content="summary_large_image">
@if ($favicon)<link rel="icon" href="{{ $favicon->url }}">@endif
<meta name="theme-color" content="#003366">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
@stack('styles')
<link href="{{ asset('assets/css/site.css') }}?v=4" rel="stylesheet">
<script type="application/ld+json">{!! json_encode(array_filter([
  '@context' => 'https://schema.org', '@type' => 'Organization', 'name' => $siteName, 'url' => url('/'),
  'logo' => $logo?->url, 'email' => setting('contact_email'),
  'telephone' => setting('contact_phone'),
  'address' => setting('contact_address') ? ['@type' => 'PostalAddress', 'streetAddress' => setting('contact_address'), 'addressCountry' => 'UG'] : null,
  'sameAs' => array_values(array_filter([setting('social_facebook'), setting('social_x'), setting('social_linkedin'), setting('social_instagram'), setting('social_youtube')])),
]), JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
@stack('schema')
</head>
<body>
@if (! empty($preview))<div class="preview-bar">Preview mode{{ isset($page) && ! $page->is_published ? ': this page is not published yet' : '' }}</div>@endif
<x-site-header :logo="$logo" />
<main id="main">
@yield('content')
</main>
<x-site-footer :logo="setting_media('footer_logo') ?? $logo" />
<x-floating-actions />
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
@stack('scripts')
<script src="{{ asset('assets/js/site.js') }}?v=4" defer></script>
</body>
</html>

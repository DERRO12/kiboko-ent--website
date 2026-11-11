@props(['eyebrow' => null, 'heading' => null, 'center' => true])
@if ($eyebrow || $heading)
<div @class(['section-title', 'text-center' => $center])>
  @if ($eyebrow)<span class="eyebrow">{{ $eyebrow }}</span>@endif
  @if ($heading)<h2>{{ $heading }}</h2>@endif
  {{ $slot }}
</div>
@endif

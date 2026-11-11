@push('styles')<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">@endpush
@push('scripts')<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" defer></script>@endpush
@php
  $points = $d['locations']->map(fn ($l) => ['n' => $l->name, 'd' => $l->district, 'r' => $l->region, 't' => $l->type, 'lat' => $l->latitude, 'lng' => $l->longitude, 'x' => $l->description, 'img' => $l->media?->thumb_url])->values();
  $regions = $d['locations']->groupBy('region')->map->count();
@endphp
<div class="container">
  <div class="row g-5 align-items-center">
    <div class="col-lg-7"><div class="reach-map" data-map='@json($points)' role="region" aria-label="Map of Kiboko distribution locations in Uganda"></div></div>
    <div class="col-lg-5">
      <x-section-title :eyebrow="$s->eyebrow" :heading="$s->heading" :center="false" />
      <div class="rich">{!! clean_html($s->body) !!}</div>
      <ul class="region-list">
        @foreach ($regions as $region => $count)<li><i class="fa-solid fa-location-dot"></i> <b>{{ $region === 'Tanzania' ? 'Tanzania' : $region.' Region' }}</b> <span>{{ $count }} {{ Str::plural('location', $count) }}</span></li>@endforeach
      </ul>
    </div>
  </div>
  @if ($d['stats']->isNotEmpty())
    <div class="row g-4 mt-4">
      @foreach ($d['stats'] as $stat)<div class="col-6 col-lg-3"><div class="stat-card"><x-stat :stat="$stat" /></div></div>@endforeach
    </div>
  @endif
</div>

@props(['service', 'photo' => true])
@if ($photo)
<a href="{{ route('services.show', $service->slug) }}" class="svc-card">
  <div class="svc-img"><x-img :media="$service->media" :alt="$service->name" sizes="(max-width: 768px) 100vw, 25vw" /></div>
  <span class="icon-badge"><i class="{{ icon_class($service->icon) }}"></i></span>
  <div class="svc-body"><h3>{{ $service->name }}</h3><p>{{ Str::limit($service->summary, 110) }}</p></div>
</a>
@else
<a href="{{ route('services.show', $service->slug) }}" class="svc-tile">
  <span class="icon-circle"><i class="{{ icon_class($service->icon) }}"></i></span>
  <h3>{{ $service->name }}</h3>
  <p>{{ $service->summary }}</p>
  <span class="more">Learn more <i class="fa-solid fa-arrow-right"></i></span>
</a>
@endif

<div class="container">
  <x-section-title :eyebrow="$s->eyebrow" :heading="$s->heading">@if ($s->body)<div class="sub">{!! clean_html($s->body) !!}</div>@endif</x-section-title>
  <div class="row g-4">
    @foreach ($d['services'] as $service)
      <div class="col-md-6 col-lg-4"><x-service-card :service="$service" :photo="false" /></div>
    @endforeach
  </div>
</div>

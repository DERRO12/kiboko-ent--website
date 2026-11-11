<div class="container">
  <x-section-title :eyebrow="$s->eyebrow" :heading="$s->heading">@if ($s->body)<div class="sub">{!! clean_html($s->body) !!}</div>@endif</x-section-title>
  <div class="row g-4">
    @foreach ($d['services'] as $service)
      <div class="col-sm-6 col-lg-3"><x-service-card :service="$service" /></div>
    @endforeach
  </div>
  @if ($s->button_label)<div class="text-center mt-4"><x-button :href="$s->button_url" variant="outline-kiboko">{{ $s->button_label }}</x-button></div>@endif
</div>

<div class="container">
  <x-section-title :eyebrow="$s->eyebrow" :heading="$s->heading" />
  <div class="row g-4 justify-content-center">
    @foreach ($d['values'] as $v)
      <div class="col-md-6 col-lg-4"><div class="value-card"><span class="icon-circle"><i class="{{ icon_class($v->icon) }}"></i></span><h3>{{ $v->heading }}</h3><p>{{ $v->description }}</p></div></div>
    @endforeach
  </div>
</div>

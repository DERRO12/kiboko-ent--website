<div class="container">
  <div class="row g-5 align-items-center">
    <div class="col-lg-6">
      <x-section-title :eyebrow="$s->eyebrow" :heading="$s->heading" :center="false" />
      <div class="rich">{!! clean_html($s->body) !!}</div>
      @if ($s->button_label)<a href="{{ $s->button_url ? link_url($s->button_url) : '#journey-'.$s->id }}" class="btn btn-kiboko mt-3">{{ $s->button_label }}</a>@endif
    </div>
    <div class="col-lg-6"><div class="img-frame"><x-img :media="$s->media" :alt="$s->heading" /></div></div>
  </div>
  @if ($d['milestones']->isNotEmpty())
    <div class="timeline" id="journey-{{ $s->id }}">
      @foreach ($d['milestones'] as $m)
        <div class="tl-item"><span class="tl-year">{{ $m->year }}</span><h3>{{ $m->title }}</h3><p>{{ $m->description }}</p></div>
      @endforeach
    </div>
  @endif
</div>

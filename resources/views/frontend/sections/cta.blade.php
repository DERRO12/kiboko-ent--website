<div class="container">
  <div class="cta-band">
    <div><h2>{{ $s->heading }}</h2>@if ($s->body)<div>{!! clean_html($s->body) !!}</div>@endif</div>
    <x-button :href="$s->button_url" variant="light">{{ $s->button_label }}</x-button>
  </div>
</div>

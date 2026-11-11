<div class="container narrow text-center">
  <x-section-title :eyebrow="$s->eyebrow" :heading="$s->heading" />
  @if ($s->body)<div class="lead-text">{!! clean_html($s->body) !!}</div>@endif
  <x-button :href="$s->button_url" class="mt-3">{{ $s->button_label }}</x-button>
</div>

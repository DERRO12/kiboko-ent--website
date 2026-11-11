<div class="container">
  <div class="row g-5 align-items-center">
    <div @class(['col-lg-6', 'order-lg-2' => $s->variant === 'image-left'])>
      <x-section-title :eyebrow="$s->eyebrow" :heading="$s->heading" :center="false" />
      <div class="rich">{!! clean_html($s->body) !!}</div>
      <x-button :href="$s->button_url" class="mt-3">{{ $s->button_label }}</x-button>
    </div>
    <div class="col-lg-6"><div class="img-frame"><x-img :media="$s->media" :alt="$s->heading" /></div></div>
  </div>
</div>

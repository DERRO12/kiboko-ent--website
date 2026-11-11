<div class="container">
  <x-section-title :eyebrow="$s->eyebrow" :heading="$s->heading">@if ($s->body)<div class="sub">{!! clean_html($s->body) !!}</div>@endif</x-section-title>
  <div class="brand-carousel" data-carousel>
    <button class="bc-arrow prev" type="button" aria-label="Previous brands" data-dir="-1"><i class="fa-solid fa-chevron-left"></i></button>
    <div class="bc-track" data-track>
      @foreach ($d['brands'] as $brand)<x-brand-card :brand="$brand" />@endforeach
    </div>
    <button class="bc-arrow next" type="button" aria-label="Next brands" data-dir="1"><i class="fa-solid fa-chevron-right"></i></button>
  </div>
  @if ($s->button_label)<div class="text-center mt-4"><x-button :href="$s->button_url" variant="outline-kiboko">{{ $s->button_label }}</x-button></div>@endif
</div>

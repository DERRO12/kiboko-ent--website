@props(['heroes' => collect(), 'small' => false, 'fallbackTitle' => null, 'fallbackMedia' => null, 'fallbackText' => null])
@php
  $slides = collect($heroes)->values();
  if ($slides->isEmpty()) {
      $slides = collect([(object) ['heading' => $fallbackTitle, 'text' => $fallbackText, 'media' => $fallbackMedia, 'button_label' => null, 'button2_label' => null, 'button_url' => null, 'button2_url' => null]]);
  }
  $bg = fn ($m) => ($m ?? setting_media('fallback_image'))?->url ?? asset('assets/images/fallback.svg');
  $id = 'hero'.uniqid();
@endphp
<section @class(['hero', 'hero-sm' => $small])>
  <div id="{{ $id }}" class="carousel slide carousel-fade" @if($slides->count() > 1) data-bs-ride="carousel" data-bs-interval="{{ (int) setting('hero_interval', 6000) }}" @endif>
    @if ($slides->count() > 1)
      <div class="carousel-indicators">@foreach ($slides as $i => $s)<button type="button" data-bs-target="#{{ $id }}" data-bs-slide-to="{{ $i }}" @class(['active' => $i === 0]) aria-label="Slide {{ $i + 1 }}"></button>@endforeach</div>
    @endif
    <div class="carousel-inner">
      @foreach ($slides as $i => $s)
        <div @class(['carousel-item', 'active' => $i === 0])>
          <div class="hero-slide" style="background-image:url('{{ $bg($s->media) }}')" role="img" aria-label="{{ $s->media?->alt ?? $s->heading }}">
            <div class="container hero-content">
              @if ($i === 0)<h1>{{ $s->heading }}</h1>@else<h2 class="h1">{{ $s->heading }}</h2>@endif
              @if ($s->text)<p>{{ $s->text }}</p>@endif
              @if ($s->button_label || $s->button2_label)
                <div class="hero-btns">
                  @if ($s->button_label)<a href="{{ link_url($s->button_url) }}" class="btn btn-kiboko">{{ $s->button_label }}</a>@endif
                  @if ($s->button2_label)<a href="{{ link_url($s->button2_url) }}" class="btn btn-outline-light btn-ghost">{{ $s->button2_label }} <i class="fa-solid fa-arrow-right ms-1"></i></a>@endif
                </div>
              @endif
            </div>
          </div>
        </div>
      @endforeach
    </div>
    @if ($slides->count() > 1)
      <button class="carousel-control-prev" type="button" data-bs-target="#{{ $id }}" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Previous</span></button>
      <button class="carousel-control-next" type="button" data-bs-target="#{{ $id }}" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Next</span></button>
    @endif
  </div>
  <svg class="hero-wave" viewBox="0 0 1440 90" preserveAspectRatio="none" aria-hidden="true"><path d="M0,60 C320,10 620,100 960,55 C1180,25 1320,40 1440,60 L1440,90 L0,90 Z" fill="#54B900" opacity=".9"/><path d="M0,72 C360,30 640,110 980,70 C1200,45 1330,60 1440,75 L1440,90 L0,90 Z" fill="#fff"/></svg>
</section>

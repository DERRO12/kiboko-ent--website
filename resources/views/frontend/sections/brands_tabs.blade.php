<div class="container">
  <x-section-title :eyebrow="$s->eyebrow" :heading="$s->heading">@if ($s->body)<div class="sub">{!! clean_html($s->body) !!}</div>@endif</x-section-title>
  <div class="brand-tools">
    <ul class="nav pill-tabs" role="tablist">
      <li class="nav-item" role="presentation"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-partners-{{ $s->id }}" type="button" role="tab">Our Principal Partners <span class="count">{{ $d['partners']->count() }}</span></button></li>
      <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-own-{{ $s->id }}" type="button" role="tab">Our Own Brands <span class="count">{{ $d['own']->count() }}</span></button></li>
    </ul>
    <div class="brand-search"><i class="fa-solid fa-magnifying-glass"></i><input type="search" placeholder="Find a brand…" aria-label="Find a brand" data-brand-search></div>
  </div>
  <div class="tab-content">
    @foreach (['partners' => 'tab-partners-', 'own' => 'tab-own-'] as $key => $tab)
      <div class="tab-pane fade @if($key === 'partners') show active @endif" id="{{ $tab }}{{ $s->id }}" role="tabpanel">
        @php $cats = $d[$key]->pluck('category.name')->filter()->unique()->values(); @endphp
        @if ($cats->count() > 1)
          <div class="cat-chips">
            <button type="button" class="active" data-cat="">All</button>
            @foreach ($cats as $c)<button type="button" data-cat="{{ $c }}">{{ $c }}</button>@endforeach
          </div>
        @endif
        <div class="brand-grid">
          @forelse ($d[$key] as $brand)
            <div class="brand-grid-item" data-name="{{ Str::lower($brand->name) }}" data-cat="{{ $brand->category?->name }}"><x-brand-card :brand="$brand" /><b>{{ $brand->name }}</b>@if ($brand->category)<small>{{ $brand->category->name }}</small>@endif</div>
          @empty
            <p class="text-muted text-center w-100">Brands will be listed here soon.</p>
          @endforelse
          @if ($s->button_label && $d[$key]->isNotEmpty())
            <div class="brand-grid-item more-item"><a href="{{ link_url($s->button_url ?: '/contact-us') }}" class="brand-card more-tile">{{ $s->button_label }}</a></div>
          @endif
        </div>
        <p class="text-center text-muted mt-3 d-none" data-no-results>No brands match your search.</p>
      </div>
    @endforeach
  </div>
</div>

<div class="container">
  <x-section-title :eyebrow="$s->eyebrow" :heading="$s->heading" />
  <div class="pill-filters">
    <a href="{{ url()->current() }}#insights_list-{{ $s->id }}" @class(['active' => ! $d['activeCategory']])>All Updates</a>
    @foreach ($d['categories'] as $cat)
      <a href="{{ url()->current() }}?category={{ $cat->slug }}#insights_list-{{ $s->id }}" @class(['active' => $d['activeCategory'] === $cat->slug])>{{ $cat->name }}</a>
    @endforeach
  </div>
  <div class="row g-4">
    @forelse ($d['insights'] as $insight)
      <div class="col-md-6 col-lg-4"><x-article-card :insight="$insight" /></div>
    @empty
      <p class="text-center text-muted">No updates in this category yet.</p>
    @endforelse
  </div>
  <div class="mt-4 d-flex justify-content-center">{{ $d['insights']->fragment('insights_list-'.$s->id)->links() }}</div>
</div>

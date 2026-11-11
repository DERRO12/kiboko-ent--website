<div class="container">
  <x-section-title :eyebrow="$s->eyebrow" :heading="$s->heading" />
  <div class="stats-strip">
    @foreach ($d['stats'] as $stat)<x-stat :stat="$stat" />@endforeach
  </div>
</div>

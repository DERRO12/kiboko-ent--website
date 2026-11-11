@props(['insight'])
<article class="article-card">
  <a href="{{ route('insights.show', $insight->slug) }}" class="ac-img"><x-img :media="$insight->media" :alt="$insight->title" sizes="(max-width: 768px) 100vw, 33vw" />
    @if ($insight->category)<span class="badge-cat">{{ $insight->category->name }}</span>@endif</a>
  <div class="ac-body">
    <h3><a href="{{ route('insights.show', $insight->slug) }}">{{ $insight->title }}</a></h3>
    <p>{{ Str::limit($insight->excerpt, 120) }}</p>
    <div class="ac-foot"><time datetime="{{ $insight->published_at?->toDateString() }}">{{ $insight->published_at?->format('d M Y') }}</time>
      <a href="{{ route('insights.show', $insight->slug) }}" class="arrow" aria-label="Read more: {{ $insight->title }}"><i class="fa-solid fa-arrow-right"></i></a></div>
  </div>
</article>

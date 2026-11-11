@extends('layouts.app')
@php
  $meta = ['title' => $insight->seo?->title ?: $insight->title, 'description' => $insight->seo?->description ?: $insight->excerpt, 'image' => $insight->seo?->media ?? $insight->media, 'canonical' => $insight->seo?->canonical_url ?: route('insights.show', $insight->slug), 'noindex' => $insight->seo?->noindex, 'og_type' => 'article'];
@endphp
@push('schema')
<script type="application/ld+json">{!! json_encode(array_filter(['@context' => 'https://schema.org', '@type' => 'NewsArticle', 'headline' => $insight->title, 'description' => $insight->excerpt, 'image' => $insight->media?->url, 'datePublished' => $insight->published_at?->toIso8601String(), 'dateModified' => $insight->updated_at?->toIso8601String(), 'author' => $insight->author ? ['@type' => 'Person', 'name' => $insight->author->name] : null, 'publisher' => ['@type' => 'Organization', 'name' => setting('site_name')], 'mainEntityOfPage' => route('insights.show', $insight->slug)]), JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
@endpush
@section('content')
<x-hero :small="true" :fallback-title="$insight->title" :fallback-media="$insight->media" />
<section class="sec">
  <div class="container narrow">
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li><li class="breadcrumb-item"><a href="{{ url('insights') }}">Insights</a></li><li class="breadcrumb-item active">{{ Str::limit($insight->title, 40) }}</li></ol></nav>
    <div class="article-meta">
      @if ($insight->category)<a class="badge-cat static" href="{{ url('insights') }}?category={{ $insight->category->slug }}">{{ $insight->category->name }}</a>@endif
      <span><i class="fa-regular fa-calendar"></i> {{ $insight->published_at->format('d F Y') }}</span>
      @if ($insight->author)<span><i class="fa-regular fa-user"></i> {{ $insight->author->name }}</span>@endif
    </div>
    @if ($insight->excerpt)<p class="lead">{{ $insight->excerpt }}</p>@endif
    <div class="img-frame my-4"><x-img :media="$insight->media" :alt="$insight->title" :eager="true" sizes="(max-width: 992px) 100vw, 800px" /></div>
    <div class="rich article-body">{!! clean_html($insight->content) !!}</div>
    <a href="{{ url('insights') }}" class="btn btn-outline-kiboko mt-4"><i class="fa-solid fa-arrow-left me-1"></i> Back to Insights</a>
  </div>
</section>
@if ($related->isNotEmpty())
<section class="sec bg-soft">
  <div class="container">
    <x-section-title heading="More Insights" />
    <div class="row g-4">@foreach ($related as $r)<div class="col-md-6 col-lg-4"><x-article-card :insight="$r" /></div>@endforeach</div>
  </div>
</section>
@endif
@endsection

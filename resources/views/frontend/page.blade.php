@extends('layouts.app')
@php
  $hero = $page->heroes->first();
  $isHome = $page->slug === 'home';
  $meta = [
      'title' => $page->seo?->title ?: $page->title,
      'description' => $page->seo?->description ?: $hero?->text,
      'image' => $page->seo?->media ?? $hero?->media,
      'canonical' => $page->seo?->canonical_url ?: $page->url,
      'noindex' => $page->seo?->noindex || ! $page->is_published,
  ];
  if ($isHome && ! $page->seo?->title) $meta['full_title'] = setting('site_name').' | '.setting('tagline', $page->title);
@endphp
@section('content')
<x-hero :heroes="$page->heroes" :small="! $isHome" :fallback-title="$page->title" />
@foreach ($page->sections as $s)
  @php
    $style = collect([$s->background_color ? 'background-color:'.$s->background_color : null, $s->background ? "background-image:url('".e($s->background->url)."')" : null])->filter()->implode(';');
  @endphp
  <section id="{{ $s->type }}-{{ $s->id }}" @class(['sec', 'sec-'.$s->type, 'has-bg' => $s->background]) @if($style) style="{{ $style }}" @endif>
    @includeIf('frontend.sections.'.$s->type, ['s' => $s, 'd' => $sectionData[$s->id] ?? []])
  </section>
@endforeach
@endsection

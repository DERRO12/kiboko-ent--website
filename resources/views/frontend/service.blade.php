@extends('layouts.app')
@php
  $meta = ['title' => $service->seo?->title ?: $service->name, 'description' => $service->seo?->description ?: $service->summary, 'image' => $service->seo?->media ?? $service->media, 'canonical' => $service->seo?->canonical_url ?: route('services.show', $service->slug), 'noindex' => $service->seo?->noindex];
  $all = \App\Models\Service::active()->get(['id', 'name', 'slug']);
@endphp
@section('content')
<x-hero :small="true" :fallback-title="$service->name" :fallback-text="$service->summary" :fallback-media="$service->media" />
<section class="sec">
  <div class="container">
    <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li><li class="breadcrumb-item"><a href="{{ $parent?->url ?? url('what-we-do') }}">{{ $parent?->title ?? 'What We Do' }}</a></li><li class="breadcrumb-item active">{{ $service->name }}</li></ol></nav>
    <div class="row g-5">
      <div class="col-lg-8">
        <div class="img-frame mb-4"><x-img :media="$service->media" :alt="$service->name" :eager="true" sizes="(max-width: 992px) 100vw, 66vw" /></div>
        <div class="rich">{!! clean_html($service->description ?: $service->summary) !!}</div>
        @if ($service->content)<div class="rich mt-4">{!! clean_html($service->content) !!}</div>@endif
      </div>
      <aside class="col-lg-4">
        <div class="side-card"><h3>Our Services</h3>
          <ul class="list-unstyled side-links">@foreach ($all as $o)<li><a href="{{ route('services.show', $o->slug) }}" @class(['active' => $o->id === $service->id])>{{ $o->name }} <i class="fa-solid fa-chevron-right"></i></a></li>@endforeach</ul>
        </div>
        <div class="side-card cta-mini"><h3>Partner with us</h3><p>{{ setting('tagline') }}</p><a href="{{ url('contact-us') }}" class="btn btn-light">Contact Us</a></div>
      </aside>
    </div>
  </div>
</section>
@endsection

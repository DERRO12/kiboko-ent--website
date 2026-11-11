@extends('layouts.app')
@php $meta = ['title' => 'Page not found', 'noindex' => true]; @endphp
@section('content')
<section class="sec text-center" style="padding:120px 0">
  <div class="container narrow">
    <span class="icon-circle mx-auto mb-3"><i class="fa-solid fa-route"></i></span>
    <h1>Page not found</h1>
    <p class="lead-text">The page you are looking for has moved or no longer exists.</p>
    <a href="{{ url('/') }}" class="btn btn-kiboko">Back to Home</a>
  </div>
</section>
@endsection

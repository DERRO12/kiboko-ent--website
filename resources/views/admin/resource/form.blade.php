@extends('admin.layout')
@section('title', ($item->exists ? 'Edit' : 'Add').' · '.$c['label'])
@section('content')
@php $return = url()->previous() !== url()->current() && str_contains(url()->previous(), '/admin/r/'.$c['key']) ? parse_url(url()->previous(), PHP_URL_PATH).(parse_url(url()->previous(), PHP_URL_QUERY) ? '?'.parse_url(url()->previous(), PHP_URL_QUERY) : '') : null; @endphp
<form method="POST" enctype="multipart/form-data" action="{{ $item->exists ? route('admin.r.update', [$c['key'], $item->id]) : route('admin.r.store', $c['key']) }}">
  @csrf @if ($item->exists) @method('PUT') @endif
  <input type="hidden" name="return_to" value="{{ old('return_to', $return) }}">
  @if ($c['key'] === 'seo' && $item->exists)<div class="alert alert-light border">Editing SEO for <b>{{ $item->owner_label }}</b></div>@endif
  <div class="row g-4">
    <div class="col-xl-8"><div class="card"><div class="card-body">
      @foreach ($c['fields'] as $f)
        @include('admin.partials.field', ['f' => $f, 'item' => $item])
      @endforeach
      @if ($c['key'] === 'sections')
        <details class="small text-muted"><summary>Section type hints</summary><ul class="mt-2">@foreach (\App\Models\PageSection::TYPES as $k => $v)<li><code>{{ $k }}</code>: {{ $v }}</li>@endforeach</ul></details>
      @endif
    </div></div></div>
    <div class="col-xl-4">
      @if (! empty($c['seo']))
        @php $seo = $item->exists ? $item->seo : null; @endphp
        <div class="card mb-3"><div class="card-header"><i class="fa-solid fa-magnifying-glass"></i> SEO</div><div class="card-body">
          <label class="form-label">SEO title</label><input name="seo_title" maxlength="255" class="form-control mb-2" value="{{ old('seo_title', $seo?->title) }}">
          <label class="form-label">Meta description</label><textarea name="seo_description" maxlength="500" rows="3" class="form-control">{{ old('seo_description', $seo?->description) }}</textarea>
          @if ($seo)<a class="small" href="{{ route('admin.r.edit', ['seo', $seo->id]) }}">Social image & advanced SEO</a>@endif
        </div></div>
      @endif
      <div class="card"><div class="card-body d-grid gap-2">
        <button class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Save</button>
        <a href="{{ $return ?: route('admin.r.index', $c['key']) }}" class="btn btn-light">Cancel</a>
        @if ($item->exists && $c['key'] === 'pages')<a href="{{ $item->url }}?preview=1" target="_blank" class="btn btn-outline-primary">Preview page</a>@endif
      </div></div>
    </div>
  </div>
</form>
@endsection

@extends('admin.layout')
@section('title', 'Media Library')
@section('content')
<div class="card mb-3"><div class="card-body">
  <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" class="row g-2 align-items-end">
    @csrf
    <div class="col-md-5"><label class="form-label small">Upload images (up to 20 at once)</label><input type="file" name="files[]" multiple required accept="image/jpeg,image/png,image/webp,image/gif" class="form-control"></div>
    <div class="col-md-2"><label class="form-label small">Folder</label><input name="folder" class="form-control" placeholder="general" list="folders"></div>
    <div class="col-md-3"><label class="form-label small">Alt text</label><input name="alt" class="form-control" placeholder="Describe the image"></div>
    <div class="col-md-2 d-grid"><button class="btn btn-success"><i class="fa-solid fa-upload"></i> Upload</button></div>
    <datalist id="folders">@foreach ($folders as $f)<option value="{{ $f }}">@endforeach</datalist>
  </form>
</div></div>
<form class="d-flex gap-2 mb-3" method="GET">
  <input type="search" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Search by title, alt text or file…" style="max-width:300px">
  <select name="folder" class="form-select form-select-sm w-auto" onchange="this.form.submit()"><option value="">All folders</option>@foreach ($folders as $f)<option @selected(request('folder') === $f)>{{ $f }}</option>@endforeach</select>
  <button class="btn btn-sm btn-outline-secondary">Search</button>
</form>
<div class="media-grid">
  @forelse ($items as $m)
    <a href="{{ route('admin.media.edit', $m) }}" class="media-tile" title="{{ $m->label }}">
      <img src="{{ $m->thumb_url }}" alt="{{ $m->alt }}" loading="lazy">
      <span>{{ Str::limit($m->label, 26) }}<small>{{ $m->folder }} · {{ number_format($m->size / 1024) }} KB</small></span>
    </a>
  @empty
    <p class="text-muted">No images found.</p>
  @endforelse
</div>
<div class="mt-3">{{ $items->links() }}</div>
@endsection

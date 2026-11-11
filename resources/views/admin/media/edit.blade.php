@extends('admin.layout')
@section('title', 'Edit image')
@section('content')
<div class="row g-4">
  <div class="col-lg-6"><div class="card"><div class="card-body text-center">
    <img src="{{ $m->url }}" alt="{{ $m->alt }}" class="img-fluid rounded" style="max-height:420px">
    <p class="small text-muted mt-2 mb-0">{{ $m->width ? $m->width.'×'.$m->height.' px · ' : '' }}{{ number_format($m->size / 1024) }} KB · {{ $m->mime }}<br>
      URL: <code>{{ $m->url }}</code><br>Used in {{ $usage }} place(s)</p>
  </div></div></div>
  <div class="col-lg-6"><div class="card"><div class="card-body">
    <form method="POST" action="{{ route('admin.media.update', $m) }}" enctype="multipart/form-data">
      @csrf @method('PUT')
      <label class="form-label">Title</label><input name="title" value="{{ old('title', $m->title) }}" class="form-control mb-3">
      <label class="form-label">Alt text (for accessibility and SEO)</label><input name="alt" value="{{ old('alt', $m->alt) }}" class="form-control mb-3">
      <label class="form-label">Folder</label><input name="folder" value="{{ old('folder', $m->folder) }}" class="form-control mb-3" required>
      <label class="form-label">Replace image (keeps every place that uses it)</label><input type="file" name="file" accept="image/jpeg,image/png,image/webp,image/gif" class="form-control mb-3">
      <button class="btn btn-success">Save</button> <a href="{{ route('admin.media.index') }}" class="btn btn-light">Back</a>
    </form>
    <hr>
    <form method="POST" action="{{ route('admin.media.destroy', $m) }}" onsubmit="return confirm('Delete this image permanently?')">@csrf @method('DELETE')
      <button class="btn btn-outline-danger btn-sm" @disabled($usage > 0)><i class="fa-solid fa-trash"></i> Delete image</button>
      @if ($usage > 0)<span class="small text-muted ms-2">In use, so it can't be deleted.</span>@endif
    </form>
  </div></div></div>
</div>
@endsection

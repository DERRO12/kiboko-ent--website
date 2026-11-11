@extends('admin.layout')
@section('title', 'Dashboard')
@section('content')
<div class="row g-3 mb-4">
  @foreach ($counts as [$label, $n, $icon, $mod])
    <div class="col-6 col-md-4 col-xl-2">
      <a href="{{ $mod ? route('admin.r.index', $mod) : route('admin.enquiries.index') }}" class="stat-tile">
        <i class="fa-solid {{ $icon }}"></i><b>{{ $n }}</b><span>{{ $label }}</span>
      </a>
    </div>
  @endforeach
</div>
<div class="row g-4">
  <div class="col-lg-6"><div class="card h-100"><div class="card-header d-flex">Recent enquiries <span class="badge bg-success ms-2">{{ $unread }} unread</span><a class="ms-auto small" href="{{ route('admin.enquiries.index') }}">View all</a></div>
    <ul class="list-group list-group-flush">
      @forelse ($enquiries as $e)
        <a href="{{ route('admin.enquiries.show', $e) }}" class="list-group-item list-group-item-action d-flex"><span>@unless($e->is_read)<b>@endunless{{ $e->name }}: {{ Str::limit($e->subject, 40) }}@unless($e->is_read)</b>@endunless</span><small class="ms-auto text-muted">{{ $e->created_at->diffForHumans() }}</small></a>
      @empty <li class="list-group-item text-muted">No enquiries yet.</li> @endforelse
    </ul></div></div>
  <div class="col-lg-6"><div class="card h-100"><div class="card-header d-flex">Recently published articles<a class="ms-auto small" href="{{ route('admin.r.index', 'insights') }}">Manage</a></div>
    <ul class="list-group list-group-flush">
      @forelse ($articles as $a)
        <a href="{{ route('admin.r.edit', ['insights', $a->id]) }}" class="list-group-item list-group-item-action d-flex"><span>{{ Str::limit($a->title, 55) }}</span><small class="ms-auto text-muted">{{ $a->published_at->format('d M Y') }}</small></a>
      @empty <li class="list-group-item text-muted">No published articles.</li> @endforelse
    </ul></div></div>
</div>
@endsection

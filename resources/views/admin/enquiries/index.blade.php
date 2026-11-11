@extends('admin.layout')
@section('title', 'Contact Enquiries')
@section('content')
<form class="d-flex flex-wrap gap-2 mb-3" method="GET">
  <input type="search" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Search…" style="max-width:240px">
  <select name="status" class="form-select form-select-sm w-auto" onchange="this.form.submit()"><option value="">All</option><option value="unread" @selected(request('status') === 'unread')>Unread</option><option value="read" @selected(request('status') === 'read')>Read</option></select>
  <button class="btn btn-sm btn-outline-secondary">Filter</button>
</form>
<div class="card"><div class="table-responsive"><table class="table table-hover align-middle mb-0">
  <thead><tr><th></th><th>From</th><th>Subject</th><th>Received</th><th>Replied</th><th class="text-end">Actions</th></tr></thead>
  <tbody>
  @forelse ($items as $e)
    <tr @class(['fw-semibold' => ! $e->is_read])>
      <td>@unless($e->is_read)<span class="badge bg-success">New</span>@endunless</td>
      <td>{{ $e->name }}<br><small class="text-muted">{{ $e->email }}</small></td>
      <td>{{ Str::limit($e->subject, 50) }}</td>
      <td class="small">{{ $e->created_at->format('d M Y H:i') }}</td>
      <td class="small">{{ $e->replied_at?->format('d M Y') ?? '–' }}</td>
      <td class="text-end text-nowrap">
        <a class="btn btn-sm btn-primary" href="{{ route('admin.enquiries.show', $e) }}">Open</a>
        <form method="POST" action="{{ route('admin.enquiries.toggle', $e) }}" class="d-inline">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-secondary">{{ $e->is_read ? 'Mark unread' : 'Mark read' }}</button></form>
      </td>
    </tr>
  @empty
    <tr><td colspan="6" class="text-center text-muted py-4">No enquiries.</td></tr>
  @endforelse
  </tbody>
</table></div></div>
<div class="mt-3">{{ $items->links() }}</div>
@endsection

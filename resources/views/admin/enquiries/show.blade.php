@extends('admin.layout')
@section('title', 'Enquiry')
@section('content')
<div class="row g-4">
  <div class="col-lg-7"><div class="card"><div class="card-body">
    <h2 class="h5">{{ $e->subject }}</h2>
    <p class="small text-muted">From <b>{{ $e->name }}</b> &lt;<a href="mailto:{{ $e->email }}">{{ $e->email }}</a>&gt; · {{ $e->created_at->format('d M Y H:i') }} · IP {{ $e->ip_address }}</p>
    <div class="border rounded p-3 bg-light" style="white-space:pre-wrap">{{ $e->message }}</div>
    @if ($e->reply)
      <h3 class="h6 mt-4">Your reply ({{ $e->replied_at->format('d M Y H:i') }})</h3>
      <div class="border rounded p-3" style="white-space:pre-wrap">{{ $e->reply }}</div>
    @endif
  </div></div></div>
  <div class="col-lg-5"><div class="card"><div class="card-body">
    <form method="POST" action="{{ route('admin.enquiries.reply', $e) }}">@csrf
      <label class="form-label">Reply by email</label>
      <textarea name="reply" rows="8" class="form-control mb-2" required>{{ old('reply', "Dear {$e->name},\n\nThank you for contacting ".setting('site_name', 'Kiboko Enterprises').".\n\n\n\nKind regards,\n".auth()->user()->name) }}</textarea>
      <button class="btn btn-success"><i class="fa-solid fa-paper-plane"></i> Send reply</button>
    </form>
    <hr>
    <div class="d-flex gap-2">
      <form method="POST" action="{{ route('admin.enquiries.toggle', $e) }}">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-secondary">Mark {{ $e->is_read ? 'unread' : 'read' }}</button></form>
      <form method="POST" action="{{ route('admin.enquiries.destroy', $e) }}" onsubmit="return confirm('Delete this enquiry?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form>
      <a href="{{ route('admin.enquiries.index') }}" class="btn btn-sm btn-light ms-auto">Back</a>
    </div>
  </div></div></div>
</div>
@endsection

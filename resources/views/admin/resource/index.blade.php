@extends('admin.layout')
@section('title', $c['label'])
@section('content')
@if (! empty($c['note']))<div class="alert alert-info py-2 small">{{ $c['note'] }}</div>@endif
<div class="d-flex flex-wrap gap-2 mb-3">
  <form class="d-flex flex-wrap gap-2" method="GET">
    <input type="search" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Search…" style="width:200px">
    @foreach ($c['filters'] ?? [] as $f)
      @php $fd = collect($c['fields'])->firstWhere('name', $f); @endphp
      @if ($fd)
        <select name="{{ $f }}" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
          <option value="">All {{ Str::lower(Str::before($fd['label'], ' (')) }}</option>
          @foreach (\App\Support\FieldOptions::for($fd) as $v => $l)<option value="{{ $v }}" @selected((string) request($f) === (string) $v)>{{ $l }}</option>@endforeach
        </select>
      @endif
    @endforeach
    <button class="btn btn-sm btn-outline-secondary">Filter</button>
  </form>
  @if (($c['creatable'] ?? true) !== false)
    <a href="{{ route('admin.r.create', [$c['key']] + request()->only($c['filters'] ?? [])) }}" class="btn btn-sm btn-success ms-auto"><i class="fa-solid fa-plus"></i> Add new</a>
  @endif
</div>
<div class="card"><div class="table-responsive"><table class="table table-hover align-middle mb-0">
  <thead><tr>@foreach ($c['columns'] as $col => $label)<th>{{ $label }}</th>@endforeach<th class="text-end">Actions</th></tr></thead>
  <tbody>
  @forelse ($items as $item)
    <tr>
      @foreach ($c['columns'] as $col => $label)
        @php $v = data_get($item, $col); @endphp
        <td>
          @if ($col === 'media')
            @if ($v)<img src="{{ $v->thumb_url }}" alt="" class="thumb">@else<span class="text-muted small">none</span>@endif
          @elseif (is_bool($v))
            <span class="badge {{ $v ? 'bg-success' : 'bg-secondary' }}">{{ $v ? 'Yes' : 'No' }}</span>
          @elseif ($v instanceof \Carbon\CarbonInterface)
            {{ $v->format('d M Y') }}
          @elseif ($col === 'icon')
            <i class="{{ icon_class($v) }} text-success"></i> <small class="text-muted">{{ $v }}</small>
          @else
            {{ Str::limit(strip_tags((string) $v), 60) }}
          @endif
        </td>
      @endforeach
      <td class="text-end text-nowrap">
        @foreach ($c['actions'] ?? [] as $a)
          @php $href = preg_replace_callback('/\{(\w+)\}/', fn ($m) => $m[1] === 'url' ? $item->url : $item->{$m[1]}, $a[1]); @endphp
          <a href="{{ $href }}" class="btn btn-sm btn-outline-primary" @if(! empty($a[2])) target="_blank" @endif>{{ $a[0] }}</a>
        @endforeach
        <a href="{{ route('admin.r.edit', [$c['key'], $item->id]) }}" class="btn btn-sm btn-primary"><i class="fa-solid fa-pen"></i></a>
        @if (($c['creatable'] ?? true) !== false)
        <form method="POST" action="{{ route('admin.r.destroy', [$c['key'], $item->id]) }}" class="d-inline" onsubmit="return confirm('Delete this item?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button></form>
        @endif
      </td>
    </tr>
  @empty
    <tr><td colspan="{{ count($c['columns']) + 1 }}" class="text-center text-muted py-4">Nothing here yet.</td></tr>
  @endforelse
  </tbody>
</table></div></div>
<div class="mt-3">{{ $items->links() }}</div>
@endsection

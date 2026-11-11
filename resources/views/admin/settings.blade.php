@extends('admin.layout')
@section('title', \App\Http\Controllers\Admin\SettingController::GROUPS[$group])
@section('content')
<ul class="nav nav-pills mb-3">
  @foreach (\App\Http\Controllers\Admin\SettingController::GROUPS as $g => $label)
    <li class="nav-item"><a class="nav-link @if($g === $group) active @endif" href="{{ route('admin.settings.edit', $g) }}">{{ $label }}</a></li>
  @endforeach
</ul>
<form method="POST" enctype="multipart/form-data" action="{{ route('admin.settings.update', $group) }}">
  @csrf @method('PUT')
  <div class="card"><div class="card-body">
    @foreach ($settings as $s)
      <div class="mb-3">
        <label class="form-label">{{ $s->label }}</label>
        @php $v = old('settings.'.$s->key, $s->value); @endphp
        @if ($s->type === 'image')
          @include('admin.partials.media-field', ['name' => 'settings['.$s->key.']', 'upload' => 'uploads['.$s->key.']', 'value' => $v])
        @elseif ($s->type === 'textarea')
          <textarea name="settings[{{ $s->key }}]" rows="3" class="form-control">{{ $v }}</textarea>
        @else
          <input type="{{ in_array($s->type, ['url', 'email', 'number']) ? $s->type : 'text' }}" name="settings[{{ $s->key }}]" value="{{ $v }}" class="form-control">
        @endif
      </div>
    @endforeach
    <button class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Save settings</button>
  </div></div>
</form>
@endsection

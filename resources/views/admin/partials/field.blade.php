@php
  $n = $f['name'];
  $val = old($n, $f['type'] === 'many' ? ($item->exists ? $item->{$n}->pluck('id')->all() : []) : $item->{$n});
  $req = str_contains($f['rules'], 'required');
@endphp
<div class="mb-3">
  @if ($f['type'] === 'checkbox')
    <div class="form-check form-switch"><input type="hidden" name="{{ $n }}" value="0"><input class="form-check-input" type="checkbox" role="switch" name="{{ $n }}" value="1" id="f_{{ $n }}" @checked($val)><label class="form-check-label" for="f_{{ $n }}">{{ $f['label'] }}</label></div>
  @else
    <label class="form-label" for="f_{{ $n }}">{{ $f['label'] }} @if($req)<span class="text-danger">*</span>@endif</label>
    @switch($f['type'])
      @case('textarea')
        <textarea name="{{ $n }}" id="f_{{ $n }}" rows="3" class="form-control" @required($req)>{{ $val }}</textarea> @break
      @case('html')
        <textarea name="{{ $n }}" id="f_{{ $n }}" rows="8" class="form-control font-monospace small" @required($req)>{{ $val }}</textarea>
        <div class="form-text">Plain text is fine (line breaks are kept). Basic HTML is allowed: &lt;p&gt; &lt;strong&gt; &lt;ul&gt;&lt;li&gt; &lt;h3&gt; &lt;a href&gt; &lt;img src&gt;.</div> @break
      @case('select')
      @case('belongs')
        <select name="{{ $n }}" id="f_{{ $n }}" class="form-select" @required($req)>
          @unless($req)<option value="">None</option>@endunless
          @foreach (\App\Support\FieldOptions::for($f) as $k => $l)<option value="{{ $k }}" @selected((string) $val === (string) $k)>{{ $l }}</option>@endforeach
        </select> @break
      @case('many')
        <div class="row">@foreach (\App\Support\FieldOptions::for($f) as $k => $l)
          <div class="col-sm-6 col-lg-4"><div class="form-check"><input class="form-check-input" type="checkbox" name="{{ $n }}[]" value="{{ $k }}" id="f_{{ $n }}_{{ $k }}" @checked(in_array($k, (array) $val))><label class="form-check-label" for="f_{{ $n }}_{{ $k }}">{{ $l }}</label></div></div>
        @endforeach</div> @break
      @case('media')
        @include('admin.partials.media-field', ['name' => $n, 'upload' => $n.'_upload', 'value' => $val])
        @break
      @case('datetime')
        <input type="datetime-local" name="{{ $n }}" id="f_{{ $n }}" class="form-control" value="{{ $val ? \Illuminate\Support\Carbon::parse($val)->format('Y-m-d\TH:i') : '' }}"> @break
      @case('password')
        <input type="password" name="{{ $n }}" id="f_{{ $n }}" class="form-control" autocomplete="new-password" @required(! $item->exists)> @break
      @case('icon')
        <div class="input-group"><span class="input-group-text"><i class="{{ icon_class($val) }} text-success" data-icon-preview></i></span><input type="text" name="{{ $n }}" id="f_{{ $n }}" class="form-control" value="{{ $val }}" placeholder="fa-truck" data-icon-input></div>
        <div class="form-text">A Font Awesome icon name, e.g. fa-truck, fa-warehouse, fa-store, fa-users. Browse at fontawesome.com/icons.</div> @break
      @case('color')
        <input type="text" name="{{ $n }}" id="f_{{ $n }}" class="form-control" value="{{ $val }}" placeholder="#F4F8FA" pattern="#[0-9a-fA-F]{6}"> @break
      @default
        <input type="{{ in_array($f['type'], ['number', 'url', 'email']) ? $f['type'] : 'text' }}" name="{{ $n }}" id="f_{{ $n }}" class="form-control" value="{{ $val }}" @required($req)>
    @endswitch
  @endif
</div>

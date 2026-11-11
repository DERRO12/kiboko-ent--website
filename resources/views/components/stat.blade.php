@props(['stat'])
@php $n = (int) preg_replace('/[^0-9]/', '', $stat->number); @endphp
<div class="stat">
  <i class="{{ icon_class($stat->icon) }}"></i>
  <div><b><span @if($n > 0) data-count="{{ $n }}" @endif>{{ $stat->number }}</span>{{ $stat->suffix }}</b><span>{{ $stat->label }}</span></div>
</div>

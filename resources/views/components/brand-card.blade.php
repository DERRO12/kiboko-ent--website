@props(['brand'])
@php $tag = $brand->website_url ? 'a' : 'div'; @endphp
<{{ $tag }} @if($brand->website_url) href="{{ $brand->website_url }}" target="_blank" rel="noopener nofollow" @endif class="brand-card" title="{{ $brand->name }}">
  @if ($brand->media)<x-img :media="$brand->media" :alt="$brand->name.' logo'" sizes="200px" />@else<span class="brand-name">{{ $brand->name }}</span>@endif
</{{ $tag }}>

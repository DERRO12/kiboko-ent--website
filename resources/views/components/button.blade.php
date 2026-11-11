@props(['href' => null, 'variant' => 'kiboko'])
@if ($href)
<a href="{{ link_url($href) }}" {{ $attributes->merge(['class' => 'btn btn-'.$variant]) }}>{{ $slot }}</a>
@endif

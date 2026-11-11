@php $wa = preg_replace('/[^0-9]/', '', (string) setting('whatsapp_number')); @endphp
@if ($wa)
  <a href="https://wa.me/{{ $wa }}" class="wa-float" target="_blank" rel="noopener" aria-label="Chat with us on WhatsApp"><i class="fa-brands fa-whatsapp"></i><span>Chat with us</span></a>
@endif
<button type="button" class="to-top" aria-label="Back to top" data-to-top><i class="fa-solid fa-arrow-up"></i></button>


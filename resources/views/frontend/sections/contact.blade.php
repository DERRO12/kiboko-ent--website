@php session(['contact_form_at' => time()]); @endphp
<div class="container">
  <div class="row g-4">
    <div class="col-lg-5">
      <x-section-title :eyebrow="$s->eyebrow" :heading="$s->heading" :center="false" />
      @if ($s->body)<div class="rich mb-3">{!! clean_html($s->body) !!}</div>@endif
      <ul class="contact-list">
        @foreach ([['fa-location-dot', 'Head Office', setting('contact_address'), null], ['fa-phone', 'Call Us', setting('contact_phone'), 'tel:'], ['fa-envelope', 'Email Us', setting('contact_email'), 'mailto:'], ['fa-clock', 'Business Hours', setting('business_hours'), null]] as [$icon, $label, $value, $proto])
          @if ($value)
            <li><span class="icon-circle"><i class="fa-solid {{ $icon }}"></i></span><div><b>{{ $label }}</b>
              @if ($proto)<a href="{{ $proto }}{{ $proto === 'tel:' ? preg_replace('/[^0-9+]/', '', $value) : $value }}">{{ $value }}</a>@else<span>{!! nl2br(e($value)) !!}</span>@endif
              @if ($label === 'Call Us' && setting('contact_phone_2'))<a href="tel:{{ preg_replace('/[^0-9+]/', '', setting('contact_phone_2')) }}">{{ setting('contact_phone_2') }}</a>@endif
            </div></li>
          @endif
        @endforeach
      </ul>
    </div>
    <div class="col-lg-7">
      <form method="POST" action="{{ route('contact.store') }}" class="form-card" id="contact-form" novalidate>
        @csrf
        <h3>Send Us a Message</h3>
        @if (session('contact_ok'))<div class="alert alert-success">{{ session('contact_ok') }}</div>@endif
        @if ($errors->any())<div class="alert alert-danger">Please correct the highlighted fields.</div>@endif
        <div class="hp" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
        <div class="row g-3">
          @foreach (['name' => ['Full Name', 'text', 'name'], 'email' => ['Email Address', 'email', 'email'], 'subject' => ['Subject', 'text', 'off']] as $field => [$label, $type, $ac])
            <div class="{{ $field === 'subject' ? 'col-12' : 'col-md-6' }}">
              <label class="form-label" for="c_{{ $field }}">{{ $label }}</label>
              <input type="{{ $type }}" name="{{ $field }}" id="c_{{ $field }}" value="{{ old($field) }}" autocomplete="{{ $ac }}" required maxlength="190" @class(['form-control', 'is-invalid' => $errors->has($field)])>
              @error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          @endforeach
          <div class="col-12">
            <label class="form-label" for="c_message">Message</label>
            <textarea name="message" id="c_message" rows="5" required maxlength="5000" @class(['form-control', 'is-invalid' => $errors->has('message')])>{{ old('message') }}</textarea>
            @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-12"><button class="btn btn-kiboko">{{ $s->button_label ?: 'Send Message' }} <i class="fa-solid fa-paper-plane ms-1"></i></button></div>
        </div>
      </form>
    </div>
  </div>
</div>

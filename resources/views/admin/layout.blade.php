<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}"><meta name="robots" content="noindex,nofollow">
<title>@yield('title', 'Dashboard') · {{ setting('site_name', 'Kiboko') }} Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="{{ asset('assets/css/admin.css') }}" rel="stylesheet">
</head>
<body>
<div class="admin-wrap">
  <aside class="admin-side" id="adminSide">
    <a href="{{ route('admin.dashboard') }}" class="brand"><i class="fa-solid fa-leaf"></i> {{ setting('site_name', 'Kiboko') }} <small>CMS</small></a>
    <nav>
      <a href="{{ route('admin.dashboard') }}" @class(['active' => request()->routeIs('admin.dashboard')])><i class="fa-solid fa-gauge"></i> Dashboard</a>
      @foreach (config('cms.menu') as $group => $keys)
        @php
          $links = [];
          foreach ($keys as $k) {
              if ($mod = config("cms.modules.$k")) {
                  if (auth()->user()->can($k)) $links[] = [$mod['label'], $mod['icon'], route('admin.r.index', $k), request()->is('admin/r/'.$k.'*')];
              } elseif ($cu = config("cms.custom.$k")) {
                  if (auth()->user()->can($cu[4] ?? $k)) { $u = route($cu[2], $cu[3]); $links[] = [$cu[0], $cu[1], $u, url()->current() === $u || ($k !== 'contact' && $k !== 'settings' && request()->is('admin/'.$k.'*'))]; }
              }
          }
        @endphp
        @if ($links)
          <div class="grp">{{ $group }}</div>
          @foreach ($links as [$label, $icon, $url, $on])
            <a href="{{ $url }}" @class(['active' => $on])><i class="fa-solid {{ $icon }}"></i> {{ $label }}</a>
          @endforeach
        @endif
      @endforeach
      <div class="grp">Account</div>
      <a href="{{ route('admin.profile') }}" @class(['active' => request()->routeIs('admin.profile')])><i class="fa-solid fa-user"></i> Admin Profile</a>
      <a href="{{ url('/') }}" target="_blank"><i class="fa-solid fa-up-right-from-square"></i> View website</a>
    </nav>
  </aside>
  <main class="admin-main">
    <header class="admin-top">
      <button class="btn btn-sm btn-light d-lg-none" onclick="document.getElementById('adminSide').classList.toggle('open')"><i class="fa-solid fa-bars"></i></button>
      <h1>@yield('title', 'Dashboard')</h1>
      <div class="ms-auto d-flex align-items-center gap-2">
        <span class="small text-muted d-none d-sm-inline">{{ auth()->user()->name }} · {{ auth()->user()->role?->name }}</span>
        <form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-right-from-bracket"></i> Logout</button></form>
      </div>
    </header>
    <div class="p-3 p-md-4">
      @if (session('ok'))<div class="alert alert-success">{{ session('ok') }}</div>@endif
      @if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
      @if ($errors->any())<div class="alert alert-danger"><strong>Please fix the following:</strong><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
      @yield('content')
    </div>
  </main>
</div>

<div class="modal fade" id="mediaPicker" tabindex="-1"><div class="modal-dialog modal-xl modal-dialog-scrollable"><div class="modal-content">
  <div class="modal-header"><h5 class="modal-title">Media library</h5><input type="search" class="form-control form-control-sm ms-3 w-auto" placeholder="Search images…" id="mpSearch"><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
  <div class="modal-body"><div class="mp-grid" id="mpGrid"></div><div class="text-center mt-3"><button class="btn btn-sm btn-outline-secondary d-none" id="mpMore">Load more</button></div></div>
</div></div></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>window.MEDIA_PICKER_URL = @json(route('admin.media.picker'));</script>
<script src="{{ asset('assets/js/admin.js') }}"></script>
</body>
</html>

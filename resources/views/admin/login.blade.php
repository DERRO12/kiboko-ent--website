<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex">
<title>Admin login · {{ setting('site_name', 'Kiboko') }}</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="{{ asset('assets/css/admin.css') }}" rel="stylesheet"></head>
<body class="login-bg">
<form method="POST" action="{{ route('admin.login') }}" class="login-card">
  @csrf
  @if ($logo = setting_media('logo'))<img src="{{ $logo->url }}" alt="{{ $logo->alt }}" class="mb-3" style="max-height:56px">@endif
  <h1 class="h5 mb-3">Admin sign in</h1>
  @if ($errors->any())<div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>@endif
  <label class="form-label">Email</label><input type="email" name="email" value="{{ old('email') }}" class="form-control mb-3" required autofocus autocomplete="username">
  <label class="form-label">Password</label><input type="password" name="password" class="form-control mb-3" required autocomplete="current-password">
  <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="remember" id="rem"><label class="form-check-label" for="rem">Remember me</label></div>
  <button class="btn btn-success w-100">Sign in</button>
</form>
</body></html>

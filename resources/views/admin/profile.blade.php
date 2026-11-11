@extends('admin.layout')
@section('title', 'Admin Profile')
@section('content')
<div class="card" style="max-width:640px"><div class="card-body">
  <form method="POST" action="{{ route('admin.profile') }}">@csrf @method('PUT')
    <label class="form-label">Name</label><input name="name" value="{{ old('name', $user->name) }}" class="form-control mb-3" required>
    <label class="form-label">Email</label><input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control mb-3" required>
    <hr><p class="small text-muted">To change your password, enter your current password and the new one twice.</p>
    <label class="form-label">Current password</label><input type="password" name="current_password" class="form-control mb-3" autocomplete="current-password">
    <label class="form-label">New password</label><input type="password" name="password" class="form-control mb-3" autocomplete="new-password">
    <label class="form-label">Confirm new password</label><input type="password" name="password_confirmation" class="form-control mb-3" autocomplete="new-password">
    <button class="btn btn-success">Save profile</button>
  </form>
</div></div>
@endsection

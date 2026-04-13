@extends('layouts.app')
@section('title', 'Create User')

@section('content')
<nav class="navbar navbar-dark bg-dark shadow">
  <div class="container">
    <span class="navbar-brand fw-bold text-uppercase">
      <i class="bi bi-shield-lock-fill me-2"></i>TechWyse Admin Portal
    </span>
    <div class="d-flex align-items-center gap-3">
      <span class="text-white small">Admin: <strong>{{ session('user_name') }}</strong></span>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="btn btn-outline-light btn-sm">Logout</button>
      </form>
    </div>
  </div>
</nav>

<div class="container mt-5 pt-3">
  <div class="row justify-content-center">
    <div class="col-md-6">

      <div class="d-flex align-items-center mb-4 gap-3">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
          <i class="bi bi-arrow-left me-1"></i>Back
        </a>
        <h4 class="fw-bold mb-0">Create New User</h4>
      </div>

      <div class="card shadow border-0 rounded-4 p-4">

        <div class="alert alert-info border-0 small mb-4 py-2">
          <i class="bi bi-info-circle me-1"></i>
          Only basic details needed now. Phone, address and photo can be added via <strong>Edit</strong>.
        </div>

        @if($errors->any())
          <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.users.store') }}">
          @csrf

          {{-- Name --}}
          <div class="mb-3">
            <label class="form-label fw-bold">Full Name</label>
            <input type="text" name="name"
                   class="form-control @error('name') is-invalid @enderror"
                   placeholder="Letters only, max 15"
                   maxlength="15"
                   value="{{ old('name') }}">
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Email --}}
          <div class="mb-3">
            <label class="form-label fw-bold">Email Address</label>
            <input type="email" name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   placeholder="user@example.com"
                   value="{{ old('email') }}">
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Password --}}
          <div class="mb-3">
            <label class="form-label fw-bold">Password</label>
            <div class="input-group">
              <input type="password" name="password" id="createPassword"
                     class="form-control @error('password') is-invalid @enderror"
                     placeholder="Min 6 characters">
              <button class="btn btn-outline-secondary" type="button"
                      onclick="togglePass('createPassword', this)">
                <i class="bi bi-eye"></i>
              </button>
              @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          {{-- Role --}}
          <div class="mb-4">
            <label class="form-label fw-bold">Role</label>
            <select name="role" class="form-select">
              <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
              <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-warning px-4 fw-bold">
              <i class="bi bi-person-check-fill me-1"></i>Create User
            </button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary px-4">Cancel</a>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>

<script>
  function togglePass(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');
    input.type  = input.type === 'password' ? 'text' : 'password';
    icon.className = input.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
  }
</script>
@endsection
@extends('layouts.app')
@section('title', 'Register')

@section('content')
<div class="container">
  <div class="row justify-content-center align-items-center" style="min-height:100vh;">
    <div class="col-md-5">

      <div class="card shadow border-0 rounded-4 p-4">
        <div class="text-center mb-4">
          <i class="bi bi-cpu-fill text-warning fs-1"></i>
          <h4 class="fw-bold mt-2">TechWyse</h4>
          <p class="text-muted small">Create your account</p>
        </div>

        {{-- Show errors --}}
        @if($errors->any())
          <div class="alert alert-danger py-2 small">
            {{ $errors->first() }}
          </div>
        @endif

        {{-- Show success --}}
        @if(session('success'))
          <div class="alert alert-success py-2 small">
            {{ session('success') }}
          </div>
        @endif

        <form method="POST" action="/register">
          @csrf

          {{-- Name --}}
          <div class="mb-3">
            <label class="form-label fw-bold">Full Name</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
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
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   placeholder="you@example.com"
                   value="{{ old('email') }}">
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Password --}}
          <div class="mb-3">
            <label class="form-label fw-bold">Password</label>
            <div class="input-group">
              <input type="password" name="password" id="password"
                     class="form-control @error('password') is-invalid @enderror"
                     placeholder="Min 8 characters">
              <button class="btn btn-outline-secondary" type="button"
                      onclick="togglePass('password', this)">
                <i class="bi bi-eye"></i>
              </button>
              @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          {{-- Confirm Password --}}
          <div class="mb-4">
            <label class="form-label fw-bold">Confirm Password</label>
            <div class="input-group">
              <input type="password" name="password_confirmation" id="confirm_password"
                     class="form-control"
                     placeholder="Repeat password">
              <button class="btn btn-outline-secondary" type="button"
                      onclick="togglePass('confirm_password', this)">
                <i class="bi bi-eye"></i>
              </button>
            </div>
          </div>

          <button type="submit" class="btn btn-warning w-100 fw-bold">
            <i class="bi bi-person-plus-fill me-2"></i>Register
          </button>
        </form>

        <p class="text-center text-muted small mt-3">
          Already have an account?
          <a href="/login" class="text-warning fw-bold text-decoration-none">Login</a>
        </p>
      </div>

    </div>
  </div>
</div>

<script>
  function togglePass(id, btn) {
    const input = document.getElementById(id);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
      input.type = 'text';
      icon.className = 'bi bi-eye-slash';
    } else {
      input.type = 'password';
      icon.className = 'bi bi-eye';
    }
  }
</script>
@endsection
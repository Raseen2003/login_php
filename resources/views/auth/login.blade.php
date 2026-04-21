@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="container">
  <div class="row justify-content-center align-items-center" style="min-height:100vh;">
    <div class="col-md-5">
      <div class="card shadow border-0 rounded-4 p-4">
        <div class="text-center mb-4">
          <i class="bi bi-cpu-fill text-warning fs-1"></i>
          <h4 class="fw-bold mt-2">TechWyse</h4>
          <p class="text-muted small">Sign in to your account</p>
        </div>

        @if($errors->any())
          <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
        @endif
        @if(session('success'))
          <div class="alert alert-success py-2 small">{{ session('success') }}</div>
        @endif
        @if(session('error'))
          <div class="alert alert-danger py-2 small">{{ session('error') }}</div>
        @endif

        <form method="POST" action="/login">
          @csrf

          <div class="mb-3">
            <label class="form-label fw-bold">Email Address</label>
            <input type="email" name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   placeholder="Enter Your email"
                   value="{{ old('email') }}">
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Password — space blocked --}}
          <div class="mb-4">
            <label class="form-label fw-bold">Password</label>
            <div class="input-group">
              <input type="password" name="password" id="loginPassword"
                     class="form-control @error('password') is-invalid @enderror"
                     placeholder="Your password"
                     onkeydown="blockSpaces(event)"
                     onpaste="blockPasteSpaces(event)">
              <button class="btn btn-outline-secondary" type="button"
                      onclick="togglePass('loginPassword', this)">
                <i class="bi bi-eye"></i>
              </button>
              @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="text-end mt-1">
              <a href="/forgot-password" class="text-muted small text-decoration-none">
                Forgot password?
              </a>
            </div>
          </div>

          <button type="submit" class="btn btn-warning w-100 fw-bold">
            <i class="bi bi-box-arrow-in-right me-2"></i>Login
          </button>
        </form>

        <p class="text-center text-muted small mt-3">
          Don't have an account?
          <a href="/register" class="text-warning fw-bold text-decoration-none">Register</a>
        </p>
      </div>
    </div>
  </div>
</div>

<script>
  function blockSpaces(event) {
    if (event.key === ' ' || event.code === 'Space') {
      event.preventDefault();
    }
  }

  function blockPasteSpaces(event) {
    const pasted = (event.clipboardData || window.clipboardData).getData('text');
    if (pasted.includes(' ')) {
      event.preventDefault();
      const input = event.target;
      const clean = pasted.replace(/\s/g, '');
      const start = input.selectionStart;
      const end   = input.selectionEnd;
      input.value = input.value.substring(0, start) + clean + input.value.substring(end);
    }
  }

  function togglePass(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');
    input.type  = input.type === 'password' ? 'text' : 'password';
    icon.className = input.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
  }
</script>
@endsection
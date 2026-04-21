@extends('layouts.app')
@section('title', 'Reset Password')

@section('content')
<div class="container">
  <div class="row justify-content-center align-items-center" style="min-height:100vh;">
    <div class="col-md-5">
      <div class="card shadow border-0 rounded-4 p-4">

        <div class="text-center mb-4">
          <i class="bi bi-shield-lock-fill text-warning fs-1"></i>
          <h4 class="fw-bold mt-2">Set New Password</h4>
          <p class="text-muted small">Enter your new password below.</p>
        </div>

        @if($errors->any())
          <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="/reset-password/{{ $token }}">
          @csrf

          {{-- New Password — space blocked --}}
          <div class="mb-3">
            <label class="form-label fw-bold">New Password</label>
            <div class="input-group">
              <input type="password" name="password" id="newPassword"
                     class="form-control @error('password') is-invalid @enderror"
                     placeholder="Min 6 characters"
                     onkeydown="blockSpaces(event)"
                     onpaste="blockPasteSpaces(event)">
              <button class="btn btn-outline-secondary" type="button"
                      onclick="togglePass('newPassword', this)">
                <i class="bi bi-eye"></i>
              </button>
              @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          {{-- Confirm Password — space blocked --}}
          <div class="mb-4">
            <label class="form-label fw-bold">Confirm New Password</label>
            <div class="input-group">
              <input type="password" name="password_confirmation" id="confirmPassword"
                     class="form-control"
                     placeholder="Repeat new password"
                     onkeydown="blockSpaces(event)"
                     onpaste="blockPasteSpaces(event)">
              <button class="btn btn-outline-secondary" type="button"
                      onclick="togglePass('confirmPassword', this)">
                <i class="bi bi-eye"></i>
              </button>
            </div>
          </div>

          <button type="submit" class="btn btn-warning w-100 fw-bold">
            <i class="bi bi-check-circle me-2"></i>Reset Password
          </button>
        </form>

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
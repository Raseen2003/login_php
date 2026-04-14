@extends('layouts.app')
@section('title', 'Forgot Password')

@section('content')
<div class="container">
  <div class="row justify-content-center align-items-center" style="min-height:100vh;">
    <div class="col-md-5">
      <div class="card shadow border-0 rounded-4 p-4">

        <div class="text-center mb-4">
          <i class="bi bi-lock-fill text-warning fs-1"></i>
          <h4 class="fw-bold mt-2">Forgot Password?</h4>
          <p class="text-muted small">Enter your email and we'll send you a reset link.</p>
        </div>

        @if(session('success'))
          <div class="alert alert-success py-2 small">
            <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
          </div>
        @endif

        @if(session('error'))
          <div class="alert alert-danger py-2 small">
            <i class="bi bi-x-circle me-1"></i>{{ session('error') }}
          </div>
        @endif

        {{-- Shows reset link directly if email config fails (development only) --}}
        @if(session('mail_error'))
          <div class="alert alert-warning py-2 small">
            <i class="bi bi-exclamation-triangle me-1"></i>
            {!! session('mail_error') !!}
          </div>
        @endif

        @if($errors->any())
          <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="/forgot-password">
          @csrf
          <div class="mb-4">
            <label class="form-label fw-bold">Email Address</label>
            <input type="email" name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   placeholder="you@example.com"
                   value="{{ old('email') }}">
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <button type="submit" class="btn btn-warning w-100 fw-bold">
            <i class="bi bi-send me-2"></i>Send Reset Link
          </button>
        </form>

        <p class="text-center text-muted small mt-3">
          <a href="/login" class="text-warning fw-bold text-decoration-none">
            <i class="bi bi-arrow-left me-1"></i>Back to Login
          </a>
        </p>
      </div>
    </div>
  </div>
</div>
@endsection
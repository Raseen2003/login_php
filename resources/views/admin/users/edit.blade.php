@extends('layouts.app')
@section('title', 'Edit User')

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
        <h4 class="fw-bold mb-0">Edit User</h4>
      </div>

      <div class="card shadow border-0 rounded-4 p-4">

        @if($errors->any())
          <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.users.update', $user->id) }}"
              enctype="multipart/form-data">
          @csrf

          {{-- Profile Picture --}}
          <div class="mb-3">
            <label class="form-label fw-bold">Profile Picture (JPG only)</label>

            @if($user->profile_pic && $user->profile_pic !== 'default-avatar.png')
              <div class="mb-2 d-flex align-items-center gap-3">
                <img src="{{ asset('storage/' . $user->profile_pic) }}"
                     class="rounded-circle  border-warning border-2"
                     style="width:70px;height:70px;object-fit:cover;">
                <span class="text-muted small">Current photo — choose a new file to replace it</span>
              </div>
            @endif

            <input type="file" name="profile_pic" class="form-control"
                   accept=".jpg,.jpeg"
                   onchange="previewImage(this)">
            <small class="text-muted">Leave empty to keep current picture. JPG only, max 5MB.</small>

            <div id="previewDiv" class="mt-2" style="display:none;">
              <img id="previewImg"
                   class="rounded-circle  border-success border-2"
                   style="width:70px;height:70px;object-fit:cover;">
              <span class="text-success small fw-bold ms-2">New photo selected ✓</span>
            </div>
          </div>

          {{-- Name --}}
          <div class="mb-3">
            <label class="form-label fw-bold">Full Name</label>
            <input type="text" name="name"
                   class="form-control @error('name') is-invalid @enderror"
                   placeholder="Max 15 letters"
                   maxlength="15"
                   value="{{ old('name', $user->name) }}">
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Email read only --}}
          <div class="mb-3">
            <label class="form-label fw-bold">Email Address</label>
            <input type="email" class="form-control bg-light"
                   value="{{ $user->email }}" readonly>
            <small class="text-muted">Email cannot be changed.</small>
          </div>

          {{-- Phone --}}
          <div class="mb-3">
            <label class="form-label fw-bold">Phone Number</label>
            <input type="text" name="phoneno"
                   class="form-control @error('phoneno') is-invalid @enderror"
                   placeholder="10 digits only"
                   maxlength="10"
                   value="{{ old('phoneno', $user->phoneno) }}">
            @error('phoneno')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Password — space blocked --}}
          <div class="mb-3">
            <label class="form-label fw-bold">
              Change Password
              <span class="text-muted fw-normal">(leave blank to keep current)</span>
            </label>
            <div class="input-group">
              <input type="password" name="password" id="editPassword"
                     class="form-control @error('password') is-invalid @enderror"
                     placeholder="Min 6 characters"
                     onkeydown="blockSpaces(event)"
                     onpaste="blockPasteSpaces(event)">
              <button class="btn btn-outline-secondary" type="button"
                      onclick="togglePass('editPassword', this)">
                <i class="bi bi-eye"></i>
              </button>
              @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          {{-- Address --}}
          <div class="mb-3">
            <label class="form-label fw-bold">Address</label>
            <textarea name="address"
                      class="form-control @error('address') is-invalid @enderror"
                      rows="2"
                      maxlength="50"
                      placeholder="Max 50 characters">{{ old('address', $user->address) }}</textarea>
            @error('address')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Role --}}
          <div class="mb-4">
            <label class="form-label fw-bold">Role</label>
            <select name="role" class="form-select">
              <option value="user"  {{ $user->role == 'user'  ? 'selected' : '' }}>User</option>
              <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-warning px-4 fw-bold">
              <i class="bi bi-check-circle me-1"></i>Save Changes
            </button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary px-4">Cancel</a>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>

<script>
  //  Block spacebar on password fields
  function blockSpaces(event) {
    if (event.key === ' ' || event.code === 'Space') {
      event.preventDefault();
    }
  }

  //  Block pasting passwords that contain spaces
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

  function previewImage(input) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('previewDiv').style.display = 'block';
      };
      reader.readAsDataURL(input.files[0]);
    }
  }
</script>
@endsection
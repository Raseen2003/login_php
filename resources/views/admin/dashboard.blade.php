@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')

{{-- Navbar --}}
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

  {{-- Header row --}}
  <div class="row align-items-center mb-4">
    <div class="col-md-6">
      <h2 class="fw-bold">User Management</h2>
      <p class="text-muted">Manage system users, roles, and permissions.</p>
    </div>
    <div class="col-md-6 text-md-end">
      <a href="{{ route('admin.users.create') }}" class="btn btn-warning btn-lg shadow-sm">
        <i class="bi bi-person-plus-fill me-2"></i>Create New User
      </a>
    </div>
  </div>

  {{-- Stats cards --}}
  <div class="row mb-4 g-3">
    <div class="col-md-3">
      <div class="card border-0 shadow-sm bg-warning text-dark p-3">
        <div class="small text-uppercase opacity-75">Total Users</div>
        <div class="fw-bold fs-4">{{ $totalUsers }}</div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm bg-dark text-white p-3">
        <div class="small text-uppercase opacity-75">Admins</div>
        <div class="fw-bold fs-4">{{ $totalAdmins }}</div>
      </div>
    </div>
  </div>

  {{-- Success / Error messages --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- Users table --}}
  <div class="card shadow-sm border-0">
    <div class="card-body p-0">

      {{-- Search bar top-right --}}
      <div class="d-flex justify-content-end px-3 pt-3 pb-2">
        <form method="GET" action="{{ route('admin.dashboard') }}" class="d-flex gap-2" style="max-width:280px;">
          <div class="input-group input-group-sm">
            <input type="text" name="search" class="form-control border-end-0"
                   placeholder="Search name or email..."
                   value="{{ $search }}">
            <span class="input-group-text bg-white border-start-0">
              <i class="bi bi-search text-muted"></i>
            </span>
          </div>
          @if($search)
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">
              <i class="bi bi-x"></i>
            </a>
          @endif
        </form>
      </div>

      {{-- Table --}}
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="bg-light">
            <tr>
              <th class="ps-4 py-3 text-secondary small fw-semibold text-uppercase">Member</th>
              <th class="py-3 text-secondary small fw-semibold text-uppercase">Contact Info</th>
              <th class="py-3 text-secondary small fw-semibold text-uppercase">Location</th>
              <th class="py-3 text-secondary small fw-semibold text-uppercase">Email</th>
              <th class="py-3 text-secondary small fw-semibold text-uppercase text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($users as $user)
              <tr>
                <td class="ps-4 py-3">
                  <div class="d-flex align-items-center">
                    @if($user->profile_pic && $user->profile_pic !== 'default-avatar.png')
                      <img src="{{ asset('storage/' . $user->profile_pic) }}"
                           class="rounded-circle me-3 border border-warning"
                           style="width:40px;height:40px;object-fit:cover;">
                    @else
                      <div class="rounded-circle bg-warning bg-opacity-10 text-dark d-flex align-items-center justify-content-center fw-bold me-3"
                           style="width:40px;height:40px;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                      </div>
                    @endif
                    <div>
                      <div class="fw-semibold text-dark">{{ $user->name }}</div>
                      <div class="small text-muted text-capitalize">{{ $user->role }}</div>
                    </div>
                  </div>
                </td>
                <td class="py-3 text-dark">
                  <i class="bi bi-telephone text-muted me-2"></i>
                  {{ $user->phoneno ?: 'Not Set' }}
                </td>
                <td class="py-3">
                  <span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary px-3">
                    {{ $user->address ?: 'No Address' }}
                  </span>
                </td>
                <td class="py-3 text-dark">{{ $user->email }}</td>
                <td class="py-3 text-center">
                  <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('admin.users.edit', $user->id) }}"
                       class="btn btn-outline-primary btn-sm rounded-2 px-3">
                      <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    {{-- Soft Delete --}}
                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                          onsubmit="return confirm('This user will be deactivated. Continue?')">
                      @csrf
                      <button class="btn btn-outline-danger btn-sm rounded-2 px-3">
                        <i class="bi bi-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center py-5 text-muted">
                  <i class="bi bi-inbox display-6 d-block mb-2"></i>
                  @if($search)
                    No users found for "<strong>{{ $search }}</strong>"
                  @else
                    No users found in the system.
                  @endif
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>
@endsection
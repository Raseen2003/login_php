@extends('layouts.app')
@section('title', 'Home')

@section('content')

{{-- Navbar --}}
<nav class="navbar navbar-dark bg-primary shadow-sm fixed-top">
  <div class="container">
    <span class="navbar-brand fw-bold">
      <i class="bi bi-cpu-fill me-2"></i>TechWyse
    </span>
    <div class="d-flex align-items-center gap-3">
      <span class="text-white small">
        Welcome, <strong>{{ session('user_name') }}</strong>
      </span>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="btn btn-light btn-sm rounded-pill px-3 fw-bold text-primary">
          <i class="bi bi-box-arrow-right me-1"></i>Logout
        </button>
      </form>
    </div>
  </div>
</nav>

<div class="container" style="margin-top: 80px;">
  <h5 class="fw-bold mb-3">All Users</h5>

  {{-- User cards --}}
  <div class="row g-3">
    @forelse($users as $index => $user)
      <div class="col-12">
        <div class="card border-0 shadow-sm rounded-3">
          <div class="card-body py-2 px-3">
            <div class="d-flex align-items-center">

              {{-- Number --}}
              <div class="text-muted fw-bold me-3" style="min-width:28px;">
                {{ $index + 1 }}
              </div>

              {{-- Avatar --}}
              @if($user->profile_pic && $user->profile_pic !== 'default-avatar.png')
                <img src="{{ asset('storage/' . $user->profile_pic) }}"
                     class="rounded-circle border border-warning me-3"
                     style="width:38px;height:38px;object-fit:cover;">
              @else
                <div class="rounded-circle bg-warning bg-opacity-10 text-dark d-flex align-items-center justify-content-center fw-bold me-3"
                     style="width:38px;height:38px;">
                  {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
              @endif

              {{-- Name --}}
              <div class="fw-semibold text-dark me-4" style="min-width:120px;">
                {{ $user->name }}
              </div>

              {{-- Email --}}
              <div class="text-muted small flex-grow-1 d-none d-md-block">
                <i class="bi bi-envelope me-1"></i>{{ $user->email }}
              </div>

              {{-- View More button --}}
              <button class="btn btn-sm btn-outline-warning rounded-pill px-3 ms-auto"
                      data-bs-toggle="modal"
                      data-bs-target="#viewModal{{ $user->id }}">
                <i class="bi bi-eye me-1"></i>View
              </button>

            </div>
          </div>
        </div>
      </div>

      {{-- View More Modal --}}
      <div class="modal fade" id="viewModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-warning text-white border-0 rounded-top-4">
              <h5 class="modal-title fw-bold">
                <i class="bi bi-person-circle me-2"></i>User Details
              </h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
              <div class="text-center mb-4">
                @if($user->profile_pic && $user->profile_pic !== 'default-avatar.png')
                  <img src="{{ asset('storage/' . $user->profile_pic) }}"
                       class="rounded-circle border border-warning border-3"
                       style="width:110px;height:110px;object-fit:cover;">
                @else
                  <div class="rounded-circle bg-warning bg-opacity-25 text-dark d-inline-flex align-items-center justify-content-center fw-bold"
                       style="width:110px;height:110px;font-size:40px;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                  </div>
                @endif
                <div class="mt-2 fw-bold fs-5">{{ $user->name }}</div>
              </div>
              <ul class="list-group list-group-flush">
                <li class="list-group-item px-0 d-flex gap-3">
                  <i class="bi bi-envelope-fill text-warning mt-1"></i>
                  <div>
                    <div class="text-muted small">Email</div>
                    <div class="fw-semibold">{{ $user->email }}</div>
                  </div>
                </li>
                <li class="list-group-item px-0 d-flex gap-3">
                  <i class="bi bi-telephone-fill text-warning mt-1"></i>
                  <div>
                    <div class="text-muted small">Phone</div>
                    <div class="fw-semibold">{{ $user->phoneno ?: 'Not set' }}</div>
                  </div>
                </li>
                <li class="list-group-item px-0 d-flex gap-3">
                  <i class="bi bi-geo-alt-fill text-warning mt-1"></i>
                  <div>
                    <div class="text-muted small">Address</div>
                    <div class="fw-semibold">{{ $user->address ?: 'Not set' }}</div>
                  </div>
                </li>
              </ul>
            </div>
            <div class="modal-footer border-0">
              <button type="button" class="btn btn-secondary rounded-pill px-4"
                      data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

    @empty
      <div class="col-12 text-center py-5 text-muted">
        <i class="bi bi-inbox display-6 d-block mb-2"></i>
        <span>No users found.</span>
      </div>
    @endforelse
  </div>
</div>
@endsection
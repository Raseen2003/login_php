@extends('layouts.app')
@section('title', 'Home')

@section('content')

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

<div class="container" style="margin-top: 100px;">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold m-0">All Users</h4>
    <span class="badge bg-primary rounded-pill">{{ count($users) }} Total</span>
  </div>
  <div class="d-flex justify-content-end px-3 pt-3 pb-2">
        <div class="input-group input-group-sm" style="max-width:280px;">
          <input type="text"
                 id="liveSearch"
                 class="form-control border-end-0"
                 placeholder="Search name or email..."
                 autocomplete="off">
          <span class="input-group-text bg-white border-start-0">
            <i class="bi bi-search text-muted"></i>
          </span>
          <button id="clearSearch"
                  class="btn btn-sm btn-outline-secondary ms-1"
                  style="display:none;"
                  onclick="clearSearchFn()">
            <i class="bi bi-x"></i>
          </button>
        </div>
      </div>

  <div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="bg-light">
            <tr>
              <th class="ps-4 py-3 text-uppercase small fw-bold text-secondary" style="width: 80px;">#</th>
              <th class="py-3 text-uppercase small fw-bold text-secondary">Name</th>
              <th class="py-3 text-uppercase small fw-bold text-secondary d-none d-md-table-cell">Email Address</th>
              <th class="py-3 text-uppercase small fw-bold text-secondary text-center">Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($users as $index => $user)
              <tr class="user-row" data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}">
                <td class="ps-4 text-muted fw-bold">
                  {{ $index + 1 }}
                </td>
                <td>
                  <div class="d-flex align-items-center">
                    @if($user->profile_pic && $user->profile_pic !== 'default-avatar.png')
                      <img src="{{ asset('storage/' . $user->profile_pic) }}"
                           class="rounded-circle border border-warning me-3"
                           style="width:40px;height:40px;object-fit:cover;cursor:pointer;"
                           onclick="openLightbox('{{ asset('storage/' . $user->profile_pic) }}', '{{ $user->name }}')"
                           alt="{{ $user->name }}">
                    @else
                      <div class="rounded-circle bg-warning bg-opacity-10 text-dark d-flex align-items-center justify-content-center fw-bold me-3"
                           style="width:40px;height:40px;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                      </div>
                    @endif
                    <span class="fw-semibold">{{ $user->name }}</span>
                  </div>
                </td>
                <td class="text-muted d-none d-md-table-cell">
                  {{ $user->email }}
                </td>
                <td class="text-center pe-3">
                  <button class="btn btn-sm btn-outline-primary rounded-pill px-3"
                          data-bs-toggle="modal"
                          data-bs-target="#viewModal{{ $user->id }}">
                    <i class="bi bi-eye me-1"></i>View
                  </button>
                </td>
              </tr>

              {{-- Modal for each user --}}
              <div class="modal fade" id="viewModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header bg-primary text-white border-0 rounded-top-4">
                      <h5 class="modal-title fw-bold">User Profile</h5>
                      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 text-center">
                        @if($user->profile_pic && $user->profile_pic !== 'default-avatar.png')
                          <img src="{{ asset('storage/' . $user->profile_pic) }}"
                               class="rounded-circle  border-warning border-3 mb-3"
                               style="width:100px;height:100px;object-fit:cover;">
                        @else
                          <div class="rounded-circle bg-warning bg-opacity-25 text-dark d-inline-flex align-items-center justify-content-center fw-bold mb-3"
                               style="width:100px;height:100px;font-size:35px;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                          </div>
                        @endif
                        <h5 class="fw-bold">{{ $user->name }}</h5>
                        <hr>
                        <div class="text-start">
                          <p class="mb-1 text-muted small">Email Address</p>
                          <p class="fw-semibold mb-3">{{ $user->email }}</p>
                          <p class="mb-1 text-muted small">Phone Number</p>
                          <p class="fw-semibold mb-3">{{ $user->phoneno ?: 'Not provided' }}</p>
                          <p class="mb-1 text-muted small">Location</p>
                          <p class="fw-semibold mb-0">{{ $user->address ?: 'Not provided' }}</p>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            @empty
              <tr>
                <td colspan="4" class="text-center py-5 text-muted">
                  <i class="bi bi-people display-4 d-block mb-3"></i>
                  No users available.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div id="noResults" class="text-center py-5 text-muted" style="display:none;">
        <i class="bi bi-search display-4 d-block mb-3"></i>
        No users match your search.
      </div>
    </div>
  </div>
</div>

{{-- Lightbox Code --}}
<div id="lightboxOverlay" onclick="closeLightbox()" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.85);z-index:9999;align-items:center;justify-content:center;cursor:pointer;">
  <div style="text-align:center;">
    <img id="lightboxImg" src="" style="max-width:90vw;max-height:80vh;border-radius:10px;box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
    <p id="lightboxName" class="text-white mt-3 fw-bold fs-5"></p>
  </div>
</div>

<script>
  document.getElementById('liveSearch').addEventListener('input', function () {
    const query = this.value.toLowerCase().trim();
    const rows  = document.querySelectorAll('.user-row');
    let visibleCount = 0;

    rows.forEach(function (row) {
      const name  = row.getAttribute('data-name');
      const email = row.getAttribute('data-email');

      if (name.includes(query) || email.includes(query)) {
        row.style.display = '';
        visibleCount++;
      } else {
        row.style.display = 'none';
      }       
    });

    document.getElementById('clearSearch').style.display = query ? 'inline-block' : 'none';

    document.getElementById('noResults').style.display = visibleCount === 0 ? 'block' : 'none';
  });

  //  Clear search 
  function clearSearchFn() {
    document.getElementById('liveSearch').value = '';
    document.querySelectorAll('.user-row').forEach(r => r.style.display = '');
    document.getElementById('clearSearch').style.display = 'none';
    document.getElementById('noResults').style.display = 'none';   
  }
  function openLightbox(src, name) {
    document.getElementById('lightboxImg').src = src;
    document.getElementById('lightboxName').textContent = name;
    document.getElementById('lightboxOverlay').style.display = 'flex';
  }
  function closeLightbox() {
    document.getElementById('lightboxOverlay').style.display = 'none';
  }

</script>

@endsection
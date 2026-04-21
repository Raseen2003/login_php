<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechWyse — @yield('title', 'Welcome')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .btn-warning { color: #fff; }
    </style>
</head>
<body>
    @yield('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      
        @if(session('logged_out'))
            localStorage.setItem('techwyse_session_status', 'logged_out_' + Date.now());
        @endif

        window.addEventListener('storage', function (event) {
            if (event.key === 'techwyse_session_status' && event.newValue.startsWith('logged_out_')) {
                window.location.href = "{{ route('login') }}";
            }
        });  

        @if(session('user_id'))
            if(localStorage.getItem('techwyse_session_status')) {
                localStorage.removeItem('techwyse_session_status');
            }
        @endif
    </script>
</body>

</html>
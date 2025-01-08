<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>

    <!-- Link ke file CSS -->
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <div class="header">Halaman Admin</div>

    <!-- Welcome Message -->
    <div class="welcome-message">
        @if ($message = Session::get('success'))
            {{ $message }}
        @else
            Selamat Datang di Dashboard Anda!
        @endif
    </div>

    <!-- Data Siswa Link -->
    <a class="nav-link" href="{{ route('siswa.index') }}">Data Siswa</a>

    <!-- Logout Button -->
    <a href="{{ route('logout') }}" 
       class="logout-btn"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        Logout
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10V5a2 2 0 00-2-2h-6a2 2 0 00-2 2v14a2 2 0 002 2h6a2 2 0 002-2v-1"/>
        </svg>
    </a>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sekolah App - @yield('title')</title>
</head>
<body>
    @if(session('success'))
        <div style="color: green; padding: 10px; border: 1px solid green; margin: 10px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="color: red; padding: 10px; border: 1px solid red; margin: 10px;">
            {{ session('error') }}
        </div>
    @endif

    <nav style="background: #f0f0f0; padding: 10px; margin-bottom: 20px;">
        @if(session('user'))
            <span>Welcome, {{ session('user')->nama }} ({{ session('user')->role }})</span>
            <a href="/logout" style="margin-left: 20px;">Logout</a>
            
            @if(session('user')->role == 'admin')
                <a href="/admin/dashboard" style="margin-left: 20px;">Dashboard</a>
                <a href="/admin/siswa">Siswa</a>
                <a href="/admin/guru">Guru</a>
                <a href="/admin/kelas">Kelas</a>
                <a href="/admin/mapel">Mapel</a>
                <a href="/admin/nilai">Nilai</a>
            @endif

            @if(session('user')->role == 'guru')
                <a href="/guru/dashboard" style="margin-left: 20px;">Dashboard</a>
                <a href="/guru/nilai">Nilai</a>
            @endif

            @if(session('user')->role == 'siswa')
                <a href="/siswa/dashboard" style="margin-left: 20px;">Dashboard</a>
                <a href="/siswa/nilai">Nilai Saya</a>
            @endif
        @endif
    </nav>

    <main style="padding: 20px;">
        @yield('content')
    </main>

    <footer style="text-align: center; padding: 20px; margin-top: 20px; background: #f0f0f0;">
        &copy; {{ date('Y') }} Sekolah App
    </footer>
</body>
</html>
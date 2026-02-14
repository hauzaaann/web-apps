@extends('layouts.main')

@section('title', 'Login')

@section('content')
    <h2>Login</h2>
    
    <form method="POST" action="/login">
        @csrf
        <table>
            <tr>
                <td>Email:</td>
                <td><input type="email" name="email" required></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><input type="password" name="password" required></td>
            </tr>
            <tr>
                <td></td>
                <td><button type="submit">Login</button></td>
            </tr>
        </table>
    </form>

    <div style="margin-top: 20px;">
        <h4>Test Accounts:</h4>
        <ul>
            <li>Admin: admin@sekolah.com / password</li>
            <li>Guru: budisantoso@guru.com / password</li>
            <li>Siswa: ahmadsiswa@siswa.com / password</li>
        </ul>
    </div>
@endsection
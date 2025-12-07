@extends('adminlte::auth.login') {{-- layout auth --}}
@section('title', 'Login')

@section('auth_header', 'Silakan Login')

@section('auth_body')
<form action="{{ route('web.login.post') }}" method="post">
    @csrf
    <div class="input-group mb-3">
        <input type="username" name="username" class="form-control" placeholder="Username" required autofocus>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-envelope"></span>
            </div>
        </div>
    </div>

    <div class="input-group mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-lock"></span>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary btn-block">Login</button>
</form>
@endsection

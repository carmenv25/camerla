@extends('layouts.app')
@section('content')
<div class="row justify-content-center align-items-center min-vh-75">
    <div class="col-md-6 col-lg-5">
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-body p-4 p-lg-5">
                <div class="text-center mb-4">
                    <div class="brand-icon mx-auto mb-3">A</div>
                    <h1 class="h3 fw-bold">Bienvenido</h1>
                    <p class="text-secondary">Plataforma de aprendizaje adaptativo</p>
                </div>
                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" name="email" class="form-control form-control-lg" value="{{ old('email') }}" placeholder="estudiante@demo.com" required autofocus>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control form-control-lg" placeholder="password" required>
                    </div>
                    <button class="btn btn-primary btn-lg w-100">Iniciar sesión</button>
                </form>
                <div class="demo-box mt-4">
                    <strong>Cuenta de demostración</strong><br>
                    estudiante@demo.com / password
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

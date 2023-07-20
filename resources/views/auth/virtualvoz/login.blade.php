@extends('layouts.virtualvoz.app')

@section('content')
<div class="container borna">
    <div class="d-flex justify-content-center align-items-center">
        <div class="col-12 col-md-8 col-lg-8">
            <div class="d-flex flex-wrap justify-content-center align-items-center">
                <img src="{{ asset('virtualvoz_logo_horizontal.png') }}" height="100">
            </div>
            <div class="card mt-2">
                <div class="card-header">
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <span class="text-uppercase font-weight-bold">{{ __('Acceso') }}</span>
                        <a href="{{ route('register') }}"
                            class="btn btn-lg btn-color-1 btn-custom-1">
                            {{ __('Crear ticket sin entrar') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}" id="loginForm">
                        @csrf
                        <div class="form-group row">
                            <label for="email"
                                class="col-md-4 col-form-label text-right">{{ __('Dirección de correo') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password"
                                class="col-md-4 col-form-label text-md-right">{{ __('Contraseña') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="current-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                        {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Recuérdame') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <div class="d-flex flex-row justify-content-between">
                        <button type="submit" class="btn btn-lg btn-color-2 btn-custom-1"
                            form="loginForm">
                            {{ __('Entrar') }}
                        </button>

                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('¿Has olvidado tu contraseña?') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
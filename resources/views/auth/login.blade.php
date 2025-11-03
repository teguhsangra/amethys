@extends('layouts.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="card card-login card-hidden">
                    <div class="card-header card-header-success text-center">
                        <h4 class="card-title">Login</h4>
                    </div>
                    <div class="card-body ">
                        <span class="bmd-form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="material-icons">email</i>
                                    </span>
                                </div>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Email..." class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </span>
                        <span class="bmd-form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="material-icons">lock_outline</i>
                                    </span>
                                </div>
                                <input id="password" type="password" name="password" placeholder="Password..." class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </span>
                    </div>
                    <div class="card-footer justify-content-center">
                        <button type="submit" class="btn btn-success">
                            {{ __('Login') }}
                        </button>

                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<footer class="footer">
    <div class="container">
        <nav class="float-left">
            <ul>
            </ul>
        </nav>
        <div class="copyright float-right">
            &copy;
            <script>
            document.write(new Date().getFullYear())
            </script>
        </div>
    </div>
</footer>
@endsection
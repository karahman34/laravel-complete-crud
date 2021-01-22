@extends('layouts.auth.layout')

@section('content')
  <div class="row">
    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
      <div class="card card-primary">
        <div class="card-header">
          <h4>Register</h4>
        </div>

        <div class="card-body">
          <form method="POST" action="{{ route('register') }}" class="needs-validation" novalidate="">
            @csrf

            <div class="form-group">
              <label for="name">Name</label>
              <input id="name" type="text" class="form-control" name="name" tabindex="1" value="{{ old('name') }}"
                required autofocus>
              @error('name')
                <div class="text-danger">
                  {{ $message }}
                </div>
              @enderror
            </div>

            <div class="form-group">
              <label for="email">Email</label>
              <input id="email" type="email" class="form-control" name="email" tabindex="2" value="{{ old('email') }}"
                required>
              @error('email')
                <div class="text-danger">
                  {{ $message }}
                </div>
              @enderror
            </div>

            <div class="form-group">
              <label for="password" class="control-label">Password</label>
              <input id="password" type="password" class="form-control" name="password" tabindex="3" required>
              @error('password')
                <div class="text-danger">
                  {{ $message }}
                </div>
              @enderror
            </div>

            <div class="form-group">
              <label for="repeat_password">Repeat Password</label>
              <input id="repeat_password" type="password" class="form-control" name="password_confirmation" tabindex="4"
                required>
            </div>

            <div class="form-group">
              <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                Register
              </button>
            </div>
          </form>
        </div>
      </div>

      <div class="mt-5 text-muted text-center">
        Already have an account? <a href="{{ route('login') }}">Sign In</a>
      </div>
    </div>
  </div>
@endsection

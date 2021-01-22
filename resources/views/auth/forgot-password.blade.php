@extends('layouts.auth.layout')

@section('content')
  <div class="row">
    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
      <div class="alert alert-warning">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
      </div>

      <div class="card card-primary">
        <div class="card-header">
          <h4>Forgot Password</h4>
        </div>

        <div class="card-body">
          <form method="POST" action="{{ route('password.email') }}" class="needs-validation" novalidate="">
            @csrf

            <div class="form-group">
              <label for="email">Email</label>
              <input id="email" type="email" class="form-control" name="email" tabindex="1" value="{{ old('email') }}"
                required autofocus>
              @error('email')
                <div class="text-danger">
                  {{ $message }}
                </div>
              @enderror
            </div>

            <div class="form-group">
              <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="2">
                Submit
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

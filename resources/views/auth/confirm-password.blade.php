@extends('layouts.auth.layout')

@section('content')
  <div class="row">
    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
      <div class="alert alert-warning">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
      </div>

      <div class="card card-primary">
        <div class="card-header">
          <h4>Confirm Password</h4>
        </div>

        <div class="card-body">
          <form method="POST" action="{{ route('password.confirm') }}" class="needs-validation" novalidate="">
            @csrf

            <div class="form-group">
              <label for="password" class="control-label">Password</label>
              <input id="password" type="password" class="form-control" name="password" tabindex="1" required>
              @error('password')
                <div class="text-danger">
                  {{ $message }}
                </div>
              @enderror
            </div>

            <div class="form-group">
              <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="2">
                Confirm
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@extends('layouts.auth.layout')

@section('content')
  <div class="row">
    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
      <div class="alert alert-warning">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
      </div>

      @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success">
          {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
      @endif

      <div class="card card-primary">
        <div class="card-header">
          <h4>Verify Email</h4>
        </div>

        <div class="card-body">
          <form method="POST" action="{{ route('verification.send') }}" class="needs-validation" novalidate="">
            @csrf

            <button class="btn btn-primary w-100">
              {{ __('Resend Verification Email') }}
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@extends('_layout')

@section('content')
  <form class="form-signing" method="POST" action="/accounts">
    <h2 class="form-signing-heading">Sign up</h2>

    <label for="input-email-address" class="sr-only">Email address</label>
    <input type="email" id="input-email-address" class="form-control form-control-first" name="email_address" value="{{ $email_address or '' }}" placeholder="Email address" required autofocus>

    <label for="input-password" class="sr-only">Password</label>
    <input type="password" id="input-password" class="form-control form-control-middle" name="password" value="{{ $password or '' }}" placeholder="Password" required>

    <label for="input-password-confirmation" class="sr-only">Password</label>
    <input type="password" id="input-password-confirmation" class="form-control form-control-last" name="password_confirmation" value="{{ $password_confirmation or '' }}" placeholder="Password Confirmation" required>

    <button class="btn btn-lg btn-primary btn-block" type="submit">Create New Account</button>
  </form>
@endsection

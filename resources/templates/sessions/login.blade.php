@extends('_layout')

@section('content')
  <form class="form-signing" method="POST" action="/sessions">
    <h2 class="form-signing-heading">Login</h2>

    <label for="input-email-address" class="sr-only">Email address</label>
    <input type="email" id="input-email-address" class="form-control form-control-first" name="email_address" value="{{ $email_address or '' }}" placeholder="Email address" required autofocus>

    <label for="input-password" class="sr-only">Password</label>
    <input type="password" id="input-password" class="form-control form-control-last" name="password" value="{{ $password or '' }}" placeholder="Password" required>

    <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
    <a class="btn btn-lg btn-secondary btn-block" href="/accounts/sign_up">Create New Account</a>
  </form>
@endsection

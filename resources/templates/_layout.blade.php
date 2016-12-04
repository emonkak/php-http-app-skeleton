<html>
  <head>
    <title>HTTP Application Skeleton</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.5/css/bootstrap.css">
    <link rel="stylesheet" href="/css/app.css">
    @stack('head')
  </head>
  <body>
    <header>
      <nav class="navbar navbar-full navbar-dark bg-inverse mb-1">
        <div class="container">
          <a class="navbar-brand" href="/">Http Application Skeleton</a>
          <ul class="nav navbar-nav float-xs-right">
            @if ($account)
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">{{ $account->getEmailAddress() }}</a>
                <div class="dropdown-menu">
                  <form class="my-0" action="/sessions" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="dropdown-item">Logout</button>
                  </form>
                </div>
              </li>
            @endif
          </ul>
        </div>
      </nav>
    </header>
    <main>
      <div class="container">
        @foreach (['success', 'info', 'warning', 'danger'] as $type)
          @if ($flashes->has($type))
            @include('_alert', ['type' => $type, 'messages' => $flashes->get($type)])
          @endif
        @endforeach
        @yield('content')
      </div>
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.slim.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.5/js/bootstrap.js"></script>
    @stack('scripts')
  </body>
</html>

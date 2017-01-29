<!doctype html>
<head>
  <title>
    {{ ! isset($customPageTitle) || $customPageTitle === '' ? $pageTitle === '' ? null : $pageTitle : $customPageTitle }}
  </title>
</head>
<html>
  @if (isset($content))
    {!! $content !!}
  @else
    @yield('content')
  @endif
  </body>
</html>

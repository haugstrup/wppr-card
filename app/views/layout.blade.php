<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>WPPR Cards | @yield("title")</title>
  <!-- <link rel="apple-touch-icon" href="/img/touch-icon.png"> -->
  <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0">
  <link rel="stylesheet" href="/css/bootstrap.min.css">
  <link rel="stylesheet" href="/css/style.css">
</head>
<body>

  <div class="menu">
    <a href="{{URL::route('front')}}">WPPR Cards</a> @yield("title")
  </div>

  <div class="container">
    @if(Session::get('success'))<div class="alert alert-success">{{ Session::get('success') }}</div>@endif
    @if(Session::get('error'))<div class="alert alert-danger">{{ Session::get('error') }}</div>@endif
    @if(Session::get('errors'))
      <div class="alert alert-danger">
        @foreach (Session::get('errors')->all() as $message)
          {{{ $message }}}<br>
        @endforeach
      </div>
    @endif

    @yield("content")

  </div>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-1808074-6', 'auto');
  ga('send', 'pageview');

</script>
</body>
</html>

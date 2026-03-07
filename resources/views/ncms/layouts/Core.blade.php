<!DOCTYPE html>
<html lang="{{ env('APP_LOCALE', 'en') }}">

<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @stack('head-metadata')
  <title>{{ $page_title ?? '' }}{{ gv('site_name', 'NCMS') }}</title>
  <link rel="icon" href="{{ Vite::asset('resources/views/assets/img/LOGO.png') }}" type="image/x-icon">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  @stack('head-links')
  @stack('head-scripts')
  @stack('head-styles')
</head>

<body>
  <section class="content-container">
    @yield('content-section')
    @html('eita')
  </section>
  @stack('body-styles')
  @stack('body-scripts')
</body>

</html>
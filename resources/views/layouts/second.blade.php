<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $pageTitle }}</title>

  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900 dark:text-white">
    <header>
      <div class="bg-gray-800 text-white text-center py-4">
        <p>Opa</p>
      </div>
    </header>
    <main>
      {{ $slot }}
    </main>
    <article></article>
    <layouts:second.second_footer />
  </div>
</body>

</html>
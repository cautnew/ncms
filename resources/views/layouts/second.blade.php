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
    <footer>
      <div class="bg-gray-800 text-white text-center mt-3 py-4">
        <p><i class="fa-solid fa-copyright mr-2" alt="Copyright" title="Todos os direitos reservados a CautNew"></i>2025 Second Layout</p>
      </div>
    </footer>
  </div>
</body>

</html>
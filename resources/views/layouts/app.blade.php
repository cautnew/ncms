<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'NCMS') }}</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/core.layout.js'])
</head>

<body class="font-sans antialiased">
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <livewire:layout.navigation />

    <!-- Page Heading -->
    @if (isset($header))
    <header class="bg-white dark:bg-gray-800 shadow">
      <div class="max-w-7xl mx-auto py-4 px-3 sm:px-4 lg:px-6">
        {{ $header }}
      </div>
    </header>
    @endif

    <!-- Page Content -->
    <main class="app-main-content">
      {{ $slot }}
    </main>

    <footer class="m-footer">
      <div class="container">
        <div class="flex justify-between items-center">
          <div class="text-sm text-gray-500 text-center w-full">
            <p class="mb-2">Feito com <i class="fa-solid fa-heart text-red-600 mx-1" title="Amor"></i> por CautNew</p>
            <p title="Todos os direitos reservados a CautNew"><i class="fa-solid fa-copyright" title="Copyright"></i> 2025</p>
          </div>
        </div>
      </div>
    </footer>
  </div>
</body>

</html>
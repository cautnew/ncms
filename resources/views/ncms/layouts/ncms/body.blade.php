<div class="min-h-screen bg-gray-100 dark:bg-gray-900 dark:text-white">
  <main>
  <div class="mb-2 py-2 px-3 flex justify-between align-items-middle border-b-1 border-b-gray-500 bg-gray-600">
    <h1 class="text-lg text-bold">@yield('pageTitle')</h1>
    <form action="{{ route('ncms.auth.logout') }}" method="post">
      @csrf
      @method('POST')
      <button type="submit" class="bg-gray-500 rounded-md px-3 py-1">Logout</button>
    </form>
  </div>
    @yield('content')
  </main>
  <footer>
    @include('ncms.layouts.ncms.footer')
  </footer>
</div>
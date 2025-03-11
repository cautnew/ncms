@extends('ncms.layouts.first.layout')

@section('pageTitle', 'Login')

@section('content')
<div class="bg-gray-100 dark:bg-gray-900 dark:text-white">
  <div class="bg-gray-800 text-center py-4 px-2">
    <form action="{{ route('ncms.auth.login') }}" method="POST">
      @csrf
      @method('POST')
      <div class="mb-4">
        <label for="email" class="block text-sm font-bold mb-2">E-mail</label>
        <input type="email" name="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
      </div>
      <div class="mb-4">
        <label for="password" class="block text-sm font-bold mb-2">Password</label>
        <input type="password" name="password" id="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
      </div>
      <div class="flex items-center justify-between mb-2">
        <a href="{{ route('ncms.auth.create') }}" class="hover:underline text-blue-500 hover:text-blue-800">
          Register
        </a>
      </div>
      <div class="flex items-center justify-between">
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">
          Login
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
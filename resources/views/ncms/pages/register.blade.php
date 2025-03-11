@extends('ncms.layouts.first.layout')

@section('pageTitle', 'Register')

@section('content')
<div class="bg-gray-100 dark:bg-gray-900 dark:text-white">
  @if ($errors->any())
  
  <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
    <strong class="font-bold">Ops!</strong>
    @foreach ($errors->all() as $error)
    <span class="block sm:inline">{{ $error }}</span>
    @endforeach
  </div>
  @endif
  <div class="bg-gray-800 text-center py-4">
    <form action="{{ route('ncms.auth.store') }}" method="POST">
      @csrf
      @method('POST')
      <div class="mb-4">
        <label for="name" class="block text-sm font-bold mb-2">Name</label>
        <input type="text" name="name" id="name" value="{{ old('name') }}" class="shadow appearance-none border rounded w-full py-2 px-3 bg-white text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
      </div>
      <div class="mb-4">
        <label for="email" class="block text-sm font-bold mb-2">E-mail</label>
        <input type="email" name="email" id="email" value="{{ old('email') }}" class="shadow appearance-none border rounded w-full py-2 px-3 bg-white text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
      </div>
      <div class="mb-6">
        <label for="password" class="block text-sm font-bold mb-2">Senha</label>
        <input type="password" name="password" id="password" value="{{ old('password') }}" class="shadow appearance-none border rounded w-full py-2 px-3 bg-white text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
      </div>
      <div class="flex items-center justify-between">
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">
          Register
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
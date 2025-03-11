@extends('ncms.layouts.ncms.layout')

@section('pageTitle', 'Home')
@section('title', 'Home Page')

@section('content')
<p class="mb-2">Welcome to the home page, {{ $name }}!!!</p>
@endsection
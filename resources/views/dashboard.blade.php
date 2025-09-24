@extends('layout.master')
@section('title', 'Dashboard')

@section('body_content')
<h3>Dashboard</h3>
<p>Welcome to your dashboard, {{ $user->name }}</p>
@endsection
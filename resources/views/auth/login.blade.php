@extends('layouts.app')

@section('title','Login Admin')

@section('content')

<div class="auth-wrapper">

<div class="container">

<div class="row justify-content-center">

<div class="col-lg-10">

<div class="card login-card">

<div class="row g-0">

<div class="col-lg-6 left-side">

<i class="bi bi-heart-pulse-fill"></i>

<h2 class="fw-bold">

Mental Health Monitoring

</h2>

<p class="mt-3">

Platform Monitoring Kesehatan Mental Karyawan
berbasis AI.

</p>

</div>

<div class="col-lg-6 right-side">

<h3 class="fw-bold mb-4">

Login Admin

</h3>

@if(session('error'))

<div class="alert alert-danger">

{{ session('error') }}

</div>

@endif

<form method="POST" action="/login">

@csrf

<div class="mb-3">

<label>Username</label>

<div class="input-group">

<span class="input-group-text">

<i class="bi bi-person-fill"></i>

</span>

<input
type="text"
name="email"
class="form-control"
placeholder="Masukkan Username"
required>

</div>

</div>

<div class="mb-4">

<label>Password</label>

<div class="input-group">

<span class="input-group-text">

<i class="bi bi-lock-fill"></i>

</span>

<input
type="password"
id="password"
name="password"
class="form-control"
placeholder="Masukkan Password"
required>

<button
class="btn btn-outline-secondary"
type="button"
onclick="togglePassword()">

<i
id="eye"
class="bi bi-eye-fill"></i>

</button>

</div>

</div>

<button
class="btn btn-primary btn-login w-100">

<i class="bi bi-box-arrow-in-right"></i>

Login

</button>

</form>

<div class="text-center mt-4">

<a href="/employee">

Login sebagai Karyawan

</a>

</div>

</div>

</div>

</div>

</div>

</div>

</div>

@endsection

@push('scripts')

<script>

function togglePassword(){

const p=document.getElementById('password');

const eye=document.getElementById('eye');

if(p.type==="password"){

p.type="text";

eye.className="bi bi-eye-slash-fill";

}else{

p.type="password";

eye.className="bi bi-eye-fill";

}

}

</script>

@endpush
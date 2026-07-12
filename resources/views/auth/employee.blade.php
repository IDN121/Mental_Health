@extends('layouts.app')

@section('title','Login Karyawan')

@section('content')

<div class="auth-wrapper">

<div class="container">

<div class="row justify-content-center">

<div class="col-lg-10">

<div class="card login-card">

<div class="row g-0">

<div class="col-lg-6 left-side bg-success">

<i class="bi bi-person-workspace"></i>

<h2 class="fw-bold">

Mental Health Monitoring

</h2>

<p class="mt-3">

Silakan masuk menggunakan
kode anonim yang diberikan.

</p>

</div>

<div class="col-lg-6 right-side">

<h3 class="fw-bold mb-4">

Login Karyawan

</h3>

@if(session('error'))
<div class="alert alert-danger">
{{ session('error') }}
</div>
@endif

@if($errors->any())
<div class="alert alert-danger">
<ul class="mb-0">
@foreach($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif

<form method="POST" action="/employee">

@csrf

<div class="mb-4">

<label>Kode Anonim</label>

<div class="input-group">

<span class="input-group-text">

<i class="bi bi-person-badge-fill"></i>

</span>

<input
type="text"
name="unique_code"
class="form-control"
placeholder="Masukkan Kode (4 angka)"
maxlength="4"
minlength="4"
pattern="\d{4}"
required>

</div>

</div>

<button
class="btn btn-success btn-login w-100">

<i class="bi bi-box-arrow-in-right"></i>

Masuk

</button>

</form>

<div class="text-center mt-4">

<a href="/login">

Login sebagai Admin

</a>

</div>

</div>

</div>

</div>

</div>

</div>

</div>

@endsection
@extends('layouts.app')

@section('title','Dashboard Admin')

@section('page-title','Dashboard')

@section('content')

@include('components.sidebar')

<div class="main-content">

@include('components.navbar')

<div class="row mb-4">

<div class="col-md-4">

<div class="card card-modern p-4">

<div class="d-flex justify-content-between">

<div>

<small>Total Karyawan</small>

<h2>{{ $employeeCount }}</h2>

</div>

<div class="stat-icon bg-blue">

<i class="bi bi-people-fill"></i>

</div>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card card-modern p-4">

<div class="d-flex justify-content-between">

<div>

<small>Total Chat</small>

<h2>{{ $chatCount }}</h2>

</div>

<div class="stat-icon bg-green">

<i class="bi bi-chat-dots-fill"></i>

</div>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card card-modern p-4">

<div class="d-flex justify-content-between">

<div>

<small>Mood Hari Ini</small>

<h2>{{ $moodCount }}</h2>

</div>

<div class="stat-icon bg-orange">

<i class="bi bi-emoji-smile-fill"></i>

</div>

</div>

</div>

</div>

</div>

<div class="row">

<div class="col-lg-8">

<div class="card card-modern p-4">

<h5 class="fw-bold mb-4">

Aktivitas Terbaru

</h5>

<table class="table align-middle">

<thead>

<tr>

<th>Pesan</th>

<th>Waktu</th>

</tr>

</thead>

<tbody>

@forelse($latestMessages as $message)

<tr>

<td>

{{ Str::limit($message->message,50) }}

</td>

<td>

{{ $message->created_at->diffForHumans() }}

</td>

</tr>

@empty

<tr>

<td colspan="2">

Belum ada aktivitas.

</td>

</tr>

@endforelse

</tbody>

</table>

</div>

</div>

<div class="col-lg-4">

<div class="card card-modern p-4">

<h5 class="fw-bold mb-3">

Quick Menu

</h5>

<a href="/admin/chat" class="btn btn-primary w-100 mb-3">

<i class="bi bi-chat-dots-fill"></i>

Chat Konseling

</a>

<a href="/admin/monitoring" class="btn btn-success w-100 mb-3">

<i class="bi bi-emoji-smile-fill"></i>

Monitoring AI

</a>

<a href="/admin/monitoring" class="btn btn-warning w-100">

<i class="bi bi-bar-chart-fill"></i>

Grafik Mood

</a>

</div>

</div>

</div>

@include('components.footer')

</div>

@endsection
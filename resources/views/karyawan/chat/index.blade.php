@extends('layouts.app')

@section('title','Chat Konseling')

@section('content')

@include('components.sidebar')

<div class="main-content">

@include('components.navbar')

<div class="card card-modern p-4">

<h3 class="mb-4">
Chat Konseling
</h3>

<table class="table table-hover align-middle">

<thead>

<tr>

<th>Karyawan</th>

<th>Jumlah Pesan</th>

<th></th>

</tr>

</thead>

<tbody>

@foreach($users as $user)

<tr>

<td>

Anonim #{{ str_pad($user->id,3,'0',STR_PAD_LEFT) }}

</td>

<td>

{{ $user->messages->count() }}

</td>

<td>

<a href="/karyawan/chat/{{ $user->id }}" class="btn btn-sm btn-primary">
    <i class="bi bi-chat-dots"></i> Lihat
</a>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>

@endsection
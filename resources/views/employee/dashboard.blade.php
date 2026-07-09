<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<title>Dashboard Karyawan</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f4f6f9;
}

.card{
    border:none;
    border-radius:15px;
}

</style>

</head>

<body>

<nav class="navbar navbar-dark bg-primary">

<div class="container">

<a class="navbar-brand">
🧠 MentalCare
</a>

<a href="/logout" class="btn btn-light">
Logout
</a>

</div>

</nav>

<div class="container mt-5">

<h2>Selamat Datang</h2>

<p>Semoga harimu menyenangkan 😊</p>

<div class="row mt-4">

<div class="col-md-4">

<div class="card shadow">

<div class="card-body text-center">

<h3>😊</h3>

<h5>Mood Harian</h5>

<a href="/mood" class="btn btn-primary">
Isi Mood
</a>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card shadow">

<div class="card-body text-center">

<h3>💬</h3>

<h5>Konseling</h5>

<a href="/chat" class="btn btn-success">
Mulai Chat
</a>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card shadow">

<div class="card-body text-center">

<h3>📊</h3>

<h5>Riwayat Mood</h5>

<a href="/employee/riwayat-mood" class="btn btn-warning">
Lihat
</a>

</div>

</div>

</div>

</div>

</div>

</body>

</html>
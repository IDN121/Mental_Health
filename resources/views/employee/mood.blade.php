<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">

<title>Mood Harian</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f4f6f9;
}

.card{
    border:none;
    border-radius:15px;
}

.emoji{

    font-size:45px;
    cursor:pointer;
    transition:.2s;

}

.emoji:hover{

    transform:scale(1.2);

}

</style>

</head>

<body>

<div class="container mt-5">

<div class="card shadow">

<div class="card-body">

<h2>😊 Mood Hari Ini</h2>

<p>Bagaimana perasaanmu hari ini?</p>

<div class="d-flex justify-content-around mt-4">

<div class="text-center">
<div class="emoji">😁</div>
<p>Happy</p>
</div>

<div class="text-center">
<div class="emoji">😐</div>
<p>Neutral</p>
</div>

<div class="text-center">
<div class="emoji">😔</div>
<p>Sad</p>
</div>

<div class="text-center">
<div class="emoji">😟</div>
<p>Anxiety</p>
</div>

<div class="text-center">
<div class="emoji">😠</div>
<p>Stress</p>
</div>

</div>

<div class="mt-5">

<a href="/employee/dashboard" class="btn btn-secondary">

Kembali

</a>

</div>

</div>

</div>

</div>

</body>
</html>
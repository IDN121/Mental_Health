<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Mood Harian</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background:#f4f6f9; }
.card { border:none; border-radius:15px; }
.emoji-btn {
    font-size:45px;
    background: transparent;
    border: 2px solid transparent;
    border-radius: 50%;
    cursor:pointer;
    transition:.2s;
    padding: 10px;
}
.emoji-btn:hover { transform:scale(1.2); }
.emoji-btn.selected { border-color: #0d6efd; background-color: #e9ecef; }
</style>
</head>
<body>
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-body p-5">
            <h2 class="mb-3 text-center">😊 Mood Hari Ini</h2>
            <p class="text-center text-muted mb-4">Bagaimana perasaanmu hari ini?</p>

            <form action="/employee/mood" method="POST">
                @csrf
                <input type="hidden" name="mood" id="selectedMood" required>

                <div class="d-flex justify-content-around mt-4 flex-wrap gap-3">
                    <div class="text-center">
                        <button type="button" class="emoji-btn" onclick="selectMood(this, 'Senang')">😁</button>
                        <p class="mt-2 fw-semibold">Senang</p>
                    </div>
                    <div class="text-center">
                        <button type="button" class="emoji-btn" onclick="selectMood(this, 'Biasa Saja')">😐</button>
                        <p class="mt-2 fw-semibold">Biasa Saja</p>
                    </div>
                    <div class="text-center">
                        <button type="button" class="emoji-btn" onclick="selectMood(this, 'Sedih')">😔</button>
                        <p class="mt-2 fw-semibold">Sedih</p>
                    </div>
                    <div class="text-center">
                        <button type="button" class="emoji-btn" onclick="selectMood(this, 'Cemas')">😰</button>
                        <p class="mt-2 fw-semibold">Cemas</p>
                    </div>
                    <div class="text-center">
                        <button type="button" class="emoji-btn" onclick="selectMood(this, 'Marah')">😡</button>
                        <p class="mt-2 fw-semibold">Marah</p>
                    </div>
                    <div class="text-center">
                        <button type="button" class="emoji-btn" onclick="selectMood(this, 'Lelah')">😴</button>
                        <p class="mt-2 fw-semibold">Lelah</p>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="form-label fw-bold">Catatan Harian (Opsional)</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Ceritakan sedikit tentang harimu..."></textarea>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="/employee/dashboard" class="btn btn-secondary px-4">Kembali</a>
                    <button type="submit" class="btn btn-primary px-4">Simpan Mood</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function selectMood(btn, moodValue) {
    // Remove selected class from all
    document.querySelectorAll('.emoji-btn').forEach(el => el.classList.remove('selected'));
    // Add selected class to clicked btn
    btn.classList.add('selected');
    // Set hidden input value
    document.getElementById('selectedMood').value = moodValue;
}
</script>
</body>
</html>
<div class="navbar-custom d-flex justify-content-between align-items-center">

    <div class="d-flex align-items-center">
        <!-- Hamburger Menu for mobile -->
        <button class="btn d-lg-none me-2" onclick="toggleSidebar()">
            <i class="bi bi-list fs-4"></i>
        </button>

        <i class="bi bi-chat-dots text-primary fs-4 me-3"></i>

        <div>

            <h3 class="fw-bold mb-0">
                @yield('page-title','Chat Konseling')
            </h3>

            <small class="text-muted d-none d-md-block">
                Employee Mental Health Monitoring System
            </small>

        </div>

    </div>

    <div class="d-flex align-items-center">

        {{-- Jam --}}
        <div class="me-4 text-muted">
            <i class="bi bi-clock me-1"></i>
            <span id="clock"></span>
        </div>

        {{-- Tanggal --}}
        <div class="me-4 text-muted">
            <i class="bi bi-calendar3 me-1"></i>
            <span id="today"></span>
        </div>

        {{-- Notifikasi --}}
        @php
            $unreadCount = \App\Models\Message::where('is_read', 0)->where('is_admin', 0)->count();
            $unreadMessages = \App\Models\Message::where('is_read', 0)
                                ->where('is_admin', 0)
                                ->with('anonymousUser')
                                ->latest()
                                ->take(5)
                                ->get();
        @endphp

        <div class="dropdown me-3">
            <button class="btn position-relative" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-bell fs-5"></i>
                @if($unreadCount > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ $unreadCount }}
                </span>
                @endif
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow" style="width: 320px; max-height: 400px; overflow-y: auto;">
                <li><h6 class="dropdown-header">Notifikasi Baru</h6></li>
                @forelse($unreadMessages as $msg)
                <li>
                    <a class="dropdown-item d-flex flex-column border-bottom py-2" href="/admin/chat/{{ $msg->anonymous_user_id }}">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-bold small">{{ $msg->anonymousUser->unique_code ?? 'Karyawan' }}</span>
                            <small class="text-muted" style="font-size: 0.75rem;">{{ $msg->created_at->diffForHumans() }}</small>
                        </div>
                        <span class="text-truncate small" style="max-width: 100%;">{{ $msg->message }}</span>
                    </a>
                </li>
                @empty
                <li><span class="dropdown-item text-muted small text-center py-3">Tidak ada notifikasi baru</span></li>
                @endforelse
                @if($unreadCount > 0)
                <li><a class="dropdown-item text-center small text-primary py-2 fw-semibold" href="/admin/chat">Lihat Semua Pesan</a></li>
                @endif
            </ul>
        </div>

        {{-- User --}}
        <div class="dropdown">

            <a href="#"
               class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle"
               data-bs-toggle="dropdown">

                <img
                    src="https://ui-avatars.com/api/?name=Administrator&background=2563EB&color=ffffff"
                    width="45"
                    height="45"
                    class="rounded-circle me-2">

                <div class="text-start">

                    <div class="fw-semibold">
                        Administrator
                    </div>

                    <small class="text-success">
                        <i class="bi bi-circle-fill" style="font-size:8px"></i>
                        Online
                    </small>

                </div>

            </a>

            <ul class="dropdown-menu dropdown-menu-end shadow">

                <li>

                    <a class="dropdown-item" href="/dashboard">

                        <i class="bi bi-house me-2"></i>

                        Dashboard

                    </a>

                </li>

                <li><hr class="dropdown-divider"></li>

                <li>

                    <a class="dropdown-item text-danger" href="/logout">

                        <i class="bi bi-box-arrow-right me-2"></i>

                        Logout

                    </a>

                </li>

            </ul>

        </div>

    </div>

</div>

<script>
function updateClock(){

    const now = new Date();

    document.getElementById("clock").innerHTML =
        now.toLocaleTimeString('id-ID');

    document.getElementById("today").innerHTML =
        now.toLocaleDateString('id-ID',{
            weekday:'long',
            day:'numeric',
            month:'long',
            year:'numeric'
        });

}

updateClock();

setInterval(updateClock,1000);

</script>
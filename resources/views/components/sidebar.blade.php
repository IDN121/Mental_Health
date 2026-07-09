<div class="sidebar">

    <div class="sidebar-logo">

        <div class="mb-3">

            <i class="bi bi-heart-pulse-fill"></i>

        </div>

        <h4>Mental Health</h4>

        <small class="text-white-50">
            @if(session()->has('admin_id'))
                Admin System
            @else
                Employee Portal
            @endif
        </small>

    </div>

    <ul class="mt-4">

        @if(session()->has('admin_id'))
        <li>
            <a href="/dashboard"
               class="{{ request()->is('dashboard') ? 'active-menu' : '' }}">
                <i class="bi bi-grid-fill"></i>
                Dashboard Admin
            </a>
        </li>
        <li>
            <a href="/admin/chat"
               class="{{ request()->is('admin/chat*') ? 'active-menu' : '' }}">
                <i class="bi bi-chat-dots-fill"></i>
                Chat Konseling (Admin)
            </a>
        </li>
        <li>
            <a href="/admin/monitoring"
               class="{{ request()->is('admin/monitoring') ? 'active-menu' : '' }}">
                <i class="bi bi-cpu-fill"></i>
                Monitoring AI
            </a>
        </li>
        <li>
            <a href="/admin/statistik"
               class="{{ request()->is('admin/statistik') ? 'active-menu' : '' }}">
                <i class="bi bi-bar-chart-fill"></i>
                Statistik
            </a>
        </li>
        <li>
            <a href="/admin/laporan"
               class="{{ request()->is('admin/laporan*') ? 'active-menu' : '' }}">
                <i class="bi bi-file-earmark-text-fill"></i>
                Laporan
            </a>
        </li>
        @endif

        @if(session()->has('user_id'))
        <li>
            <a href="/employee/dashboard"
               class="{{ request()->is('employee/dashboard') ? 'active-menu' : '' }}">
                <i class="bi bi-grid-fill"></i>
                Dashboard Karyawan
            </a>
        </li>
        <li>
            <a href="/chat"
               class="{{ request()->is('chat') ? 'active-menu' : '' }}">
                <i class="bi bi-chat-dots-fill"></i>
                Chat Konseling (AI)
            </a>
        </li>
        <li>
            <a href="/mood"
               class="{{ request()->is('mood') ? 'active-menu' : '' }}">
                <i class="bi bi-emoji-smile-fill"></i>
                Isi Mood Harian
            </a>
        </li>
        <li>
            <a href="/employee/riwayat-mood"
               class="{{ request()->is('employee/riwayat-mood') ? 'active-menu' : '' }}">
                <i class="bi bi-clock-history"></i>
                Riwayat Mood
            </a>
        </li>
        @endif

    </ul>

    <div class="mt-auto">

        <hr class="border-light opacity-25">

        <a href="/logout" class="text-white">

            <i class="bi bi-box-arrow-right"></i>

            Logout

        </a>

    </div>

</div>
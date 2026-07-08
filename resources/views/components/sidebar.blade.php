<div class="sidebar">

    <div class="sidebar-logo">

        <div class="mb-3">

            <i class="bi bi-heart-pulse-fill"></i>

        </div>

        <h4>Mental Health</h4>

        <small class="text-white-50">
            Monitoring System
        </small>

    </div>

    <ul class="mt-4">

        <li>

            <a href="/dashboard"
               class="{{ request()->is('dashboard') ? 'active-menu' : '' }}">

                <i class="bi bi-grid-fill"></i>

                Dashboard

            </a>

        </li>

        <li>

            <a href="/admin/chat"
               class="{{ request()->is('admin/chat*') ? 'active-menu' : '' }}">

                <i class="bi bi-chat-dots-fill"></i>

                Chat Konseling

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

    </ul>

    <div class="mt-auto">

        <hr class="border-light opacity-25">

        <a href="/logout" class="text-white">

            <i class="bi bi-box-arrow-right"></i>

            Logout

        </a>

    </div>

</div>
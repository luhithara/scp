@php
    $user = auth()->user();

    $role = strtolower(trim($user->role ?? ''));

    $dashboardRoute = match ($role) {
        'waka' => 'waka.dashboard',
        'guru' => 'guru.dashboard',
        'bk' => 'bk.dashboard',
        'orangtua' => 'orangtua.dashboard',
        default => 'dashboard',
    };
@endphp

<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
>

<style>
    .scp-sidebar {
        width: 270px;
        min-height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        overflow-y: auto;
        background: linear-gradient(180deg, #0f4c81 0%, #123f68 100%);
        color: #fff;
        box-shadow: 4px 0 20px rgba(0, 0, 0, .08);
    }

    .scp-sidebar-header {
        padding: 24px 20px;
        border-bottom: 1px solid rgba(255, 255, 255, .12);
    }

    .scp-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #fff;
        text-decoration: none;
    }

    .scp-brand-icon {
        width: 46px;
        height: 46px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 13px;
        background: #fff;
        color: #0f4c81;
        font-size: 13px;
        font-weight: 800;
        letter-spacing: .5px;
    }

    .scp-brand-title {
        font-size: 18px;
        font-weight: 800;
        line-height: 1.2;
    }

    .scp-brand-subtitle {
        margin-top: 4px;
        font-size: 10px;
        opacity: .7;
        letter-spacing: .2px;
    }

    .scp-sidebar-user {
        margin: 18px 16px;
        padding: 14px;
        border: 1px solid rgba(255, 255, 255, .06);
        border-radius: 13px;
        background: rgba(255, 255, 255, .08);
    }

    .scp-user-name {
        margin-bottom: 5px;
        overflow: hidden;
        color: #fff;
        font-size: 14px;
        font-weight: 700;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .scp-user-role {
        color: #fff;
        font-size: 10px;
        opacity: .65;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .scp-menu-title {
        padding: 8px 20px;
        color: #fff;
        font-size: 9px;
        font-weight: 700;
        opacity: .55;
        text-transform: uppercase;
        letter-spacing: 1.3px;
    }

    .scp-menu {
        padding: 0 12px 20px;
    }

    .scp-menu a {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 4px;
        padding: 11px 13px;
        border-radius: 11px;
        color: rgba(255, 255, 255, .78);
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all .2s ease;
    }

    .scp-menu a:hover {
        background: rgba(255, 255, 255, .09);
        color: #fff;
        transform: translateX(2px);
    }

    .scp-menu a.active {
        background: #fff;
        color: #0f4c81;
        box-shadow: 0 5px 15px rgba(0, 0, 0, .10);
    }

    .scp-menu-icon {
        width: 32px;
        height: 32px;
        min-width: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        background: rgba(255, 255, 255, .08);
        color: rgba(255, 255, 255, .85);
        font-size: 13px;
        transition: all .2s ease;
    }

    .scp-menu a:hover .scp-menu-icon {
        background: rgba(255, 255, 255, .14);
        color: #fff;
    }

    .scp-menu a.active .scp-menu-icon {
        background: #eaf3fb;
        color: #0f4c81;
    }

    .scp-sidebar-footer {
        margin-top: auto;
        padding: 15px 12px 22px;
        border-top: 1px solid rgba(255, 255, 255, .10);
    }

    .scp-logout {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 13px;
        border: none;
        border-radius: 11px;
        background: rgba(255, 255, 255, .07);
        color: rgba(255, 255, 255, .82);
        cursor: pointer;
        font-family: inherit;
        font-size: 13px;
        font-weight: 600;
        transition: all .2s ease;
    }

    .scp-logout:hover {
        background: rgba(220, 38, 38, .90);
        color: #fff;
    }

    .scp-logout:hover .scp-menu-icon {
        background: rgba(255, 255, 255, .15);
        color: #fff;
    }

    .scp-sidebar::-webkit-scrollbar {
        width: 5px;
    }

    .scp-sidebar::-webkit-scrollbar-track {
        background: transparent;
    }

    .scp-sidebar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, .20);
        border-radius: 10px;
    }

    @media (max-width: 768px) {
        .scp-sidebar {
            width: 235px;
        }

        .scp-brand-title {
            font-size: 15px;
        }

        .scp-menu a {
            font-size: 12px;
        }
    }
</style>

<aside class="scp-sidebar">

    {{-- HEADER --}}
    <div class="scp-sidebar-header">
        <a href="{{ route($dashboardRoute) }}" class="scp-brand">
            <div class="scp-brand-icon">SCP</div>

            <div>
                <div class="scp-brand-title">
                    School Class Progress
                </div>

                <div class="scp-brand-subtitle">
                    Sistem Informasi Sekolah
                </div>
            </div>
        </a>
    </div>

    {{-- INFORMASI PENGGUNA --}}
    <div class="scp-sidebar-user">
        <div class="scp-user-name">
            {{ $user->name ?? 'Pengguna' }}
        </div>

        <div class="scp-user-role">
            {{ ucfirst($user->role ?? 'Pengguna') }}
        </div>
    </div>

    {{-- MENU WAKA --}}
    @if($role === 'waka')

        <div class="scp-menu-title">Menu Waka</div>

        <nav class="scp-menu">

            <a href="{{ route('waka.dashboard') }}"
               class="{{ request()->routeIs('waka.dashboard') ? 'active' : '' }}">
                <span class="scp-menu-icon"><i class="fa-solid fa-house"></i></span>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('waka.guru') }}"
               class="{{ request()->routeIs('waka.guru*') ? 'active' : '' }}">
                <span class="scp-menu-icon"><i class="fa-solid fa-user-tie"></i></span>
                <span>Data Guru</span>
            </a>

            <a href="{{ route('waka.siswa') }}"
               class="{{ request()->routeIs('waka.siswa*') ? 'active' : '' }}">
                <span class="scp-menu-icon"><i class="fa-solid fa-users"></i></span>
                <span>Data Siswa</span>
            </a>

            <a href="{{ route('waka.kelas') }}"
               class="{{ request()->routeIs('waka.kelas*') ? 'active' : '' }}">
                <span class="scp-menu-icon"><i class="fa-solid fa-school"></i></span>
                <span>Data Kelas</span>
            </a>

            <a href="{{ route('waka.jadwal') }}"
               class="{{ request()->routeIs('waka.jadwal*') ? 'active' : '' }}">
                <span class="scp-menu-icon"><i class="fa-regular fa-calendar-days"></i></span>
                <span>Jadwal</span>
            </a>

            <a href="{{ route('waka.jurnal') }}"
               class="{{ request()->routeIs('waka.jurnal*') ? 'active' : '' }}">
                <span class="scp-menu-icon"><i class="fa-solid fa-book-open"></i></span>
                <span>Jurnal Mengajar</span>
            </a>

            <a href="{{ route('waka.rekap-presensi') }}"
               class="{{ request()->routeIs('waka.rekap-presensi*') ? 'active' : '' }}">
                <span class="scp-menu-icon"><i class="fa-solid fa-clipboard-list"></i></span>
                <span>Rekap Presensi</span>
            </a>

            <a href="{{ route('profil') }}"
               class="{{ request()->routeIs('profil*') ? 'active' : '' }}">
                <span class="scp-menu-icon"><i class="fa-regular fa-user"></i></span>
                <span>Profil</span>
            </a>

        </nav>

    {{-- MENU ORANG TUA --}}
    @elseif($role === 'orangtua')

        <div class="scp-menu-title">Menu Orang Tua</div>

        <nav class="scp-menu">

            <a href="{{ route('orangtua.dashboard') }}"
               class="{{ request()->routeIs('orangtua.dashboard') ? 'active' : '' }}">
                <span class="scp-menu-icon"><i class="fa-solid fa-house"></i></span>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('orangtua.jadwal') }}"
               class="{{ request()->routeIs('orangtua.jadwal*') ? 'active' : '' }}">
                <span class="scp-menu-icon"><i class="fa-regular fa-calendar-days"></i></span>
                <span>Jadwal Anak</span>
            </a>

            <a href="{{ route('orangtua.presensi') }}"
               class="{{ request()->routeIs('orangtua.presensi*') ? 'active' : '' }}">
                <span class="scp-menu-icon"><i class="fa-solid fa-clipboard-check"></i></span>
                <span>Presensi Anak</span>
            </a>

            <a href="{{ route('izin') }}"
               class="{{ request()->routeIs('izin*') ? 'active' : '' }}">
                <span class="scp-menu-icon"><i class="fa-solid fa-file-circle-check"></i></span>
                <span>Perizinan</span>
            </a>

            <a href="{{ route('orangtua.profil') }}"
               class="{{ request()->routeIs('orangtua.profil*') ? 'active' : '' }}">
                <span class="scp-menu-icon"><i class="fa-regular fa-user"></i></span>
                <span>Profil</span>
            </a>

        </nav>

    {{-- MENU GURU --}}
    @elseif($role === 'guru')

        <div class="scp-menu-title">Menu Guru</div>

        <nav class="scp-menu">

            <a href="{{ route('guru.dashboard') }}"
               class="{{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
                <span class="scp-menu-icon"><i class="fa-solid fa-house"></i></span>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('guru.jadwal') }}"
               class="{{ request()->routeIs('guru.jadwal*') ? 'active' : '' }}">
                <span class="scp-menu-icon"><i class="fa-regular fa-calendar-days"></i></span>
                <span>Jadwal Mengajar</span>
            </a>

            <a href="{{ route('presensi') }}"
               class="{{ request()->routeIs('presensi*') ? 'active' : '' }}">
                <span class="scp-menu-icon"><i class="fa-solid fa-clipboard-check"></i></span>
                <span>Presensi</span>
            </a>

            <a href="{{ route('jurnal.guru') }}"
               class="{{ request()->routeIs('jurnal.guru*') ? 'active' : '' }}">
                <span class="scp-menu-icon"><i class="fa-solid fa-book-open"></i></span>
                <span>Jurnal Mengajar</span>
            </a>

            @if(Route::has('profil'))
                <a href="{{ route('profil') }}"
                   class="{{ request()->routeIs('profil*') ? 'active' : '' }}">
                    <span class="scp-menu-icon"><i class="fa-regular fa-user"></i></span>
                    <span>Profil</span>
                </a>
            @endif

        </nav>

    {{-- MENU BK --}}
    @elseif($role === 'bk')

        <div class="scp-menu-title">Menu BK</div>

        <nav class="scp-menu">

            <a href="{{ route('bk.dashboard') }}"
               class="{{ request()->routeIs('bk.dashboard') ? 'active' : '' }}">
                <span class="scp-menu-icon"><i class="fa-solid fa-house"></i></span>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('bk.kelas') }}"
               class="{{ request()->routeIs('bk.kelas*') ? 'active' : '' }}">
                <span class="scp-menu-icon"><i class="fa-solid fa-school"></i></span>
                <span>Kelas Binaan</span>
            </a>

            <a href="{{ route('bk.siswa') }}"
               class="{{ request()->routeIs('bk.siswa*') ? 'active' : '' }}">
                <span class="scp-menu-icon"><i class="fa-solid fa-users"></i></span>
                <span>Data Siswa</span>
            </a>

            <a href="{{ route('bk.presensi') }}"
               class="{{ request()->routeIs('bk.presensi') ? 'active' : '' }}">
                <span class="scp-menu-icon"><i class="fa-solid fa-clipboard-check"></i></span>
                <span>Presensi</span>
            </a>

            <a href="{{ route('bk.butuh-penanganan') }}"
               class="{{ request()->routeIs('bk.butuh-penanganan') ? 'active' : '' }}">
                <span class="scp-menu-icon"><i class="fa-solid fa-user-clock"></i></span>
                <span>Butuh Penanganan</span>
            </a>

            <a href="{{ route('bk.izin') }}"
               class="{{ request()->routeIs('bk.izin') ? 'active' : '' }}">
                <span class="scp-menu-icon"><i class="fa-solid fa-file-circle-check"></i></span>
                <span>Perizinan</span>
            </a>

            @if(Route::has('profil'))
                <a href="{{ route('profil') }}"
                   class="{{ request()->routeIs('profil*') ? 'active' : '' }}">
                    <span class="scp-menu-icon"><i class="fa-regular fa-user"></i></span>
                    <span>Profil</span>
                </a>
            @endif

        </nav>

    {{-- MENU AKUN KELAS --}}
    @elseif($role === 'kelas')

        <div class="scp-menu-title">Menu Akun Kelas</div>

        <nav class="scp-menu">

            <a
                href="{{ route('kelas.dashboard') }}"
                class="{{ request()->routeIs('kelas.dashboard') && request('tab', 'siswa') === 'siswa' ? 'active' : '' }}"
            >
                <span class="scp-menu-icon">
                    <i class="fa-solid fa-house"></i>
                </span>
                <span>Dashboard</span>
            </a>

            <a
                href="{{ route('kelas.jadwal') }}"
                class="{{ request()->routeIs('kelas.jadwal') || (request()->routeIs('kelas.dashboard') && request('tab') === 'jadwal') ? 'active' : '' }}"
            >
                <span class="scp-menu-icon">
                    <i class="fa-regular fa-calendar-days"></i>
                </span>
                <span>Jadwal Kelas</span>
            </a>

            <a
                href="{{ route('kelas.perizinan') }}"
                class="{{ request()->routeIs('kelas.perizinan') || (request()->routeIs('kelas.dashboard') && request('tab') === 'perizinan') ? 'active' : '' }}"
            >
                <span class="scp-menu-icon">
                    <i class="fa-solid fa-file-circle-check"></i>
                </span>
                <span>Izin Masuk</span>
            </a>

            @if(Route::has('profil'))
                <a
                    href="{{ route('profil') }}"
                    class="{{ request()->routeIs('profil*') ? 'active' : '' }}"
                >
                    <span class="scp-menu-icon">
                        <i class="fa-regular fa-user"></i>
                    </span>
                    <span>Profil</span>
                </a>
            @endif

        </nav>

    {{-- MENU SISWA --}}
    @elseif($role === 'siswa')

        <div class="scp-menu-title">Menu Siswa</div>

        <nav class="scp-menu">

            @if(Route::has('dashboard'))
                <a href="{{ route('dashboard') }}"
                   class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="scp-menu-icon"><i class="fa-solid fa-house"></i></span>
                    <span>Dashboard</span>
                </a>
            @endif

            <a href="{{ route('jadwal.siswa') }}"
               class="{{ request()->routeIs('jadwal.siswa*') ? 'active' : '' }}">
                <span class="scp-menu-icon"><i class="fa-regular fa-calendar-days"></i></span>
                <span>Jadwal</span>
            </a>

            <a href="{{ route('presensi.siswa') }}"
               class="{{ request()->routeIs('presensi.siswa*') ? 'active' : '' }}">
                <span class="scp-menu-icon"><i class="fa-solid fa-clipboard-check"></i></span>
                <span>Presensi</span>
            </a>

            @if(Route::has('izin'))
                <a href="{{ route('izin') }}"
                   class="{{ request()->routeIs('izin*') ? 'active' : '' }}">
                    <span class="scp-menu-icon"><i class="fa-solid fa-file-circle-check"></i></span>
                    <span>Perizinan</span>
                </a>
            @endif

            @if(Route::has('profil'))
                <a href="{{ route('profil') }}"
                   class="{{ request()->routeIs('profil*') ? 'active' : '' }}">
                    <span class="scp-menu-icon"><i class="fa-regular fa-user"></i></span>
                    <span>Profil</span>
                </a>
            @endif

        </nav>

    {{-- MENU DEFAULT --}}
    @else

        <div class="scp-menu-title">Menu</div>

        <nav class="scp-menu">

            @if(Route::has('dashboard'))
                <a href="{{ route('dashboard') }}"
                   class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="scp-menu-icon"><i class="fa-solid fa-house"></i></span>
                    <span>Dashboard</span>
                </a>
            @endif

            @if(Route::has('profil'))
                <a href="{{ route('profil') }}"
                   class="{{ request()->routeIs('profil*') ? 'active' : '' }}">
                    <span class="scp-menu-icon"><i class="fa-regular fa-user"></i></span>
                    <span>Profil</span>
                </a>
            @endif

        </nav>

    @endif

    {{-- LOGOUT --}}
    <div class="scp-sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="scp-logout">
                <span class="scp-menu-icon">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </span>

                <span>Keluar</span>
            </button>
        </form>
    </div>

</aside>
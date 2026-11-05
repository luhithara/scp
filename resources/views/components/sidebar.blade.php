{{-- =========================================================
     SCP - SIDEBAR
     File:
     resources/views/components/sidebar.blade.php

     Catatan:
     - Waka TIDAK memiliki menu Data Siswa
     - Waka TIDAK memiliki menu Kelola Data
     - Data siswa Waka diakses melalui Data Kelas
     - Tidak menggunakan emoji
     - Menggunakan Font Awesome
========================================================= --}}

@php

    $user = auth()->user();

    $role = strtolower(
        trim(
            $user->role ?? ''
        )
    );

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

        overflow-y: auto;

        background: linear-gradient(
            180deg,
            #0f4c81 0%,
            #123f68 100%
        );

        color: #ffffff;

        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.08);
    }

    .scp-sidebar-header {
        padding: 24px 20px;

        border-bottom: 1px solid rgba(255, 255, 255, 0.12);
    }

    .scp-brand {
        display: flex;
        align-items: center;

        gap: 12px;

        color: #ffffff;

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

        background: #ffffff;

        color: #0f4c81;

        font-size: 13px;
        font-weight: 800;

        letter-spacing: 0.5px;

        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .scp-brand-title {
        font-size: 18px;
        font-weight: 800;

        line-height: 1.2;
    }

    .scp-brand-subtitle {
        margin-top: 4px;

        font-size: 10px;

        opacity: 0.70;

        letter-spacing: 0.2px;
    }

    .scp-sidebar-user {
        margin: 18px 16px;

        padding: 14px;

        border-radius: 13px;

        background: rgba(255, 255, 255, 0.08);

        border: 1px solid rgba(255, 255, 255, 0.06);
    }

    .scp-user-name {
        margin-bottom: 5px;

        overflow: hidden;

        color: #ffffff;

        font-size: 14px;
        font-weight: 700;

        white-space: nowrap;

        text-overflow: ellipsis;
    }

    .scp-user-role {
        color: #ffffff;

        font-size: 10px;

        opacity: 0.65;

        text-transform: uppercase;

        letter-spacing: 1px;
    }

    .scp-menu-title {
        padding: 8px 20px;

        color: #ffffff;

        font-size: 9px;
        font-weight: 700;

        opacity: 0.55;

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

        color: rgba(255, 255, 255, 0.78);

        font-size: 13px;
        font-weight: 600;

        text-decoration: none;

        transition: all 0.2s ease;
    }

    .scp-menu a:hover {
        background: rgba(255, 255, 255, 0.09);

        color: #ffffff;

        transform: translateX(2px);
    }

    .scp-menu a.active {
        background: #ffffff;

        color: #0f4c81;

        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.10);
    }

    .scp-menu-icon {
        width: 32px;
        height: 32px;

        min-width: 32px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 9px;

        background: rgba(255, 255, 255, 0.08);

        color: rgba(255, 255, 255, 0.85);

        font-size: 13px;

        transition: all 0.2s ease;
    }

    .scp-menu a:hover .scp-menu-icon {
        background: rgba(255, 255, 255, 0.14);

        color: #ffffff;
    }

    .scp-menu a.active .scp-menu-icon {
        background: #eaf3fb;

        color: #0f4c81;
    }

    .scp-sidebar-footer {
        padding: 15px 12px 22px;

        border-top: 1px solid rgba(255, 255, 255, 0.10);
    }

    .scp-logout {
        width: 100%;

        display: flex;
        align-items: center;

        gap: 12px;

        padding: 11px 13px;

        border: none;

        border-radius: 11px;

        background: rgba(255, 255, 255, 0.07);

        color: rgba(255, 255, 255, 0.82);

        cursor: pointer;

        font-family: inherit;

        font-size: 13px;
        font-weight: 600;

        transition: all 0.2s ease;
    }

    .scp-logout:hover {
        background: rgba(220, 38, 38, 0.90);

        color: #ffffff;
    }

    .scp-logout:hover .scp-menu-icon {
        background: rgba(255, 255, 255, 0.15);

        color: #ffffff;
    }

    .scp-sidebar::-webkit-scrollbar {
        width: 5px;
    }

    .scp-sidebar::-webkit-scrollbar-track {
        background: transparent;
    }

    .scp-sidebar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.20);

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


    {{-- =====================================================
         HEADER SIDEBAR
    ====================================================== --}}

    <div class="scp-sidebar-header">

        @if($role === 'waka')

            <a
                href="{{ route('waka.dashboard') }}"
                class="scp-brand"
            >

        @elseif($role === 'guru')

            <a
                href="{{ route('guru.dashboard') }}"
                class="scp-brand"
            >

        @elseif($role === 'bk')

            <a
                href="{{ route('bk.dashboard') }}"
                class="scp-brand"
            >

        @elseif($role === 'orangtua')

            <a
                href="{{ route('orangtua.dashboard') }}"
                class="scp-brand"
            >

        @else

            <a
                href="{{ route('dashboard') }}"
                class="scp-brand"
            >

        @endif


            <div class="scp-brand-icon">
                SCP
            </div>


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


    {{-- =====================================================
         USER SIDEBAR
    ====================================================== --}}

    <div class="scp-sidebar-user">

        <div class="scp-user-name">
            {{ $user->name ?? 'Pengguna' }}
        </div>

        <div class="scp-user-role">
            {{ ucfirst($user->role ?? 'Pengguna') }}
        </div>

    </div>


    {{-- =====================================================
         MENU WAKA
    ====================================================== --}}

    @if($role === 'waka')

        <div class="scp-menu-title">
            Menu Waka
        </div>


        <nav class="scp-menu">


            {{-- Dashboard --}}

            <a
                href="{{ route('waka.dashboard') }}"
                class="{{ request()->routeIs('waka.dashboard') ? 'active' : '' }}"
            >

                <span class="scp-menu-icon">
                    <i class="fa-solid fa-house"></i>
                </span>

                <span>
                    Dashboard
                </span>

            </a>


            {{-- Data Guru --}}

            <a
                href="{{ route('waka.guru') }}"
                class="{{ request()->routeIs('waka.guru*') ? 'active' : '' }}"
            >

                <span class="scp-menu-icon">
                    <i class="fa-solid fa-user-tie"></i>
                </span>

                <span>
                    Data Guru
                </span>

            </a>


            {{-- Data Kelas --}}

            <a
                href="{{ route('waka.kelas') }}"
                class="{{ request()->routeIs('waka.kelas*') ? 'active' : '' }}"
            >

                <span class="scp-menu-icon">
                    <i class="fa-solid fa-school"></i>
                </span>

                <span>
                    Data Kelas
                </span>

            </a>


            {{-- Jadwal --}}

            <a
                href="{{ route('waka.jadwal') }}"
                class="{{ request()->routeIs('waka.jadwal*') ? 'active' : '' }}"
            >

                <span class="scp-menu-icon">
                    <i class="fa-regular fa-calendar-days"></i>
                </span>

                <span>
                    Jadwal
                </span>

            </a>


            {{-- Jurnal Mengajar --}}

            <a
                href="{{ route('waka.jurnal') }}"
                class="{{ request()->routeIs('waka.jurnal*') ? 'active' : '' }}"
            >

                <span class="scp-menu-icon">
                    <i class="fa-solid fa-book-open"></i>
                </span>

                <span>
                    Jurnal Mengajar
                </span>

            </a>


            {{-- Profil --}}

            <a
                href="{{ route('profil') }}"
                class="{{ request()->routeIs('profil*') ? 'active' : '' }}"
            >

                <span class="scp-menu-icon">
                    <i class="fa-regular fa-user"></i>
                </span>

                <span>
                    Profil
                </span>

            </a>


        </nav>


    {{-- =====================================================
         MENU ORANGTUA
    ====================================================== --}}

    @elseif($role === 'orangtua')

        <div class="scp-menu-title">
            Menu Orangtua
        </div>

        <nav class="scp-menu">


            <a
                href="{{ route('orangtua.dashboard') }}"
                class="{{ request()->routeIs('orangtua.dashboard') ? 'active' : '' }}"
            >

                <span class="scp-menu-icon">
                    <i class="fa-solid fa-house"></i>
                </span>

                <span>
                    Dashboard
                </span>

            </a>


            <a
                href="{{ route('orangtua.jadwal') }}"
                class="{{ request()->routeIs('orangtua.jadwal*') ? 'active' : '' }}"
            >

                <span class="scp-menu-icon">
                    <i class="fa-regular fa-calendar-days"></i>
                </span>

                <span>
                    Jadwal Anak
                </span>

            </a>


            <a
                href="{{ route('orangtua.presensi') }}"
                class="{{ request()->routeIs('orangtua.presensi*') ? 'active' : '' }}"
            >

                <span class="scp-menu-icon">
                    <i class="fa-solid fa-clipboard-check"></i>
                </span>

                <span>
                    Presensi Anak
                </span>

            </a>


            <a
                href="{{ route('izin') }}"
                class="{{ request()->routeIs('izin*') ? 'active' : '' }}"
            >

                <span class="scp-menu-icon">
                    <i class="fa-solid fa-file-circle-check"></i>
                </span>

                <span>
                    Perizinan
                </span>

            </a>


            <a
                href="{{ route('orangtua.profil') }}"
                class="{{ request()->routeIs('orangtua.profil*') ? 'active' : '' }}"
            >

                <span class="scp-menu-icon">
                    <i class="fa-regular fa-user"></i>
                </span>

                <span>
                    Profil
                </span>

            </a>


        </nav>


    {{-- =====================================================
         MENU GURU
    ====================================================== --}}

    @elseif($role === 'guru')

        <div class="scp-menu-title">
            Menu Guru
        </div>

        <nav class="scp-menu">


            <a
                href="{{ route('guru.dashboard') }}"
                class="{{ request()->routeIs('guru.dashboard') ? 'active' : '' }}"
            >

                <span class="scp-menu-icon">
                    <i class="fa-solid fa-house"></i>
                </span>

                <span>
                    Dashboard
                </span>

            </a>


            <a
                href="{{ route('guru.jadwal') }}"
                class="{{ request()->routeIs('guru.jadwal*') ? 'active' : '' }}"
            >

                <span class="scp-menu-icon">
                    <i class="fa-regular fa-calendar-days"></i>
                </span>

                <span>
                    Jadwal Mengajar
                </span>

            </a>


            <a
                href="{{ route('presensi') }}"
                class="{{ request()->routeIs('presensi*') ? 'active' : '' }}"
            >

                <span class="scp-menu-icon">
                    <i class="fa-solid fa-clipboard-check"></i>
                </span>

                <span>
                    Presensi
                </span>

            </a>


            <a
                href="{{ route('jurnal.guru') }}"
                class="{{ request()->routeIs('jurnal.guru*') ? 'active' : '' }}"
            >

                <span class="scp-menu-icon">
                    <i class="fa-solid fa-book-open"></i>
                </span>

                <span>
                    Jurnal Mengajar
                </span>

            </a>


            <a
                href="{{ route('guru.izin') }}"
                class="{{ request()->routeIs('guru.izin*') ? 'active' : '' }}"
            >

                <span class="scp-menu-icon">
                    <i class="fa-solid fa-file-circle-check"></i>
                </span>

                <span>
                    Perizinan Siswa
                </span>

            </a>


            @if(Route::has('profil'))

                <a
                    href="{{ route('profil') }}"
                    class="{{ request()->routeIs('profil*') ? 'active' : '' }}"
                >

                    <span class="scp-menu-icon">
                        <i class="fa-regular fa-user"></i>
                    </span>

                    <span>
                        Profil
                    </span>

                </a>

            @endif


        </nav>


    {{-- =====================================================
         MENU BK
    ====================================================== --}}

    @elseif($role === 'bk')

        <div class="scp-menu-title">
            Menu BK
        </div>

        <nav class="scp-menu">


            <a
                href="{{ route('bk.dashboard') }}"
                class="{{ request()->routeIs('bk.dashboard') ? 'active' : '' }}"
            >

                <span class="scp-menu-icon">
                    <i class="fa-solid fa-house"></i>
                </span>

                <span>
                    Dashboard
                </span>

            </a>


            <a
                href="{{ route('bk.kelas') }}"
                class="{{ request()->routeIs('bk.kelas*') ? 'active' : '' }}"
            >

                <span class="scp-menu-icon">
                    <i class="fa-solid fa-school"></i>
                </span>

                <span>
                    Kelas Binaan
                </span>

            </a>


            <a
                href="{{ route('bk.siswa') }}"
                class="{{ request()->routeIs('bk.siswa*') ? 'active' : '' }}"
            >

                <span class="scp-menu-icon">
                    <i class="fa-solid fa-users"></i>
                </span>

                <span>
                    Data Siswa
                </span>

            </a>


            <a
                href="{{ route('bk.presensi') }}"
                class="{{ request()->routeIs('bk.presensi') ? 'active' : '' }}"
            >

                <span class="scp-menu-icon">
                    <i class="fa-solid fa-clipboard-check"></i>
                </span>

                <span>
                    Presensi
                </span>

            </a>


            <a
                href="{{ route('bk.butuh-penanganan') }}"
                class="{{ request()->routeIs('bk.butuh-penanganan') ? 'active' : '' }}"
            >

                <span class="scp-menu-icon">
                    <i class="fa-solid fa-user-clock"></i>
                </span>

                <span>
                    Butuh Penanganan
                </span>

            </a>


            <a
                href="{{ route('bk.izin') }}"
                class="{{ request()->routeIs('bk.izin') ? 'active' : '' }}"
            >

                <span class="scp-menu-icon">
                    <i class="fa-solid fa-file-circle-check"></i>
                </span>

                <span>
                    Perizinan
                </span>

            </a>


            @if(Route::has('profil'))

                <a
                    href="{{ route('profil') }}"
                    class="{{ request()->routeIs('profil*') ? 'active' : '' }}"
                >

                    <span class="scp-menu-icon">
                        <i class="fa-regular fa-user"></i>
                    </span>

                    <span>
                        Profil
                    </span>

                </a>

            @endif


        </nav>


    {{-- =====================================================
         MENU SISWA
    ====================================================== --}}

    @elseif($role === 'siswa')

        <div class="scp-menu-title">
            Menu Siswa
        </div>

        <nav class="scp-menu">


            @if(Route::has('dashboard'))

                <a
                    href="{{ route('dashboard') }}"
                    class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"
                >

                    <span class="scp-menu-icon">
                        <i class="fa-solid fa-house"></i>
                    </span>

                    <span>
                        Dashboard
                    </span>

                </a>

            @endif


            <a
                href="{{ route('jadwal.siswa') }}"
                class="{{ request()->routeIs('jadwal.siswa*') ? 'active' : '' }}"
            >

                <span class="scp-menu-icon">
                    <i class="fa-regular fa-calendar-days"></i>
                </span>

                <span>
                    Jadwal
                </span>

            </a>


            <a
                href="{{ route('presensi.siswa') }}"
                class="{{ request()->routeIs('presensi.siswa*') ? 'active' : '' }}"
            >

                <span class="scp-menu-icon">
                    <i class="fa-solid fa-clipboard-check"></i>
                </span>

                <span>
                    Presensi
                </span>

            </a>


            @if(Route::has('izin'))

                <a
                    href="{{ route('izin') }}"
                    class="{{ request()->routeIs('izin*') ? 'active' : '' }}"
                >

                    <span class="scp-menu-icon">
                        <i class="fa-solid fa-file-circle-check"></i>
                    </span>

                    <span>
                        Perizinan
                    </span>

                </a>

            @endif


            @if(Route::has('profil'))

                <a
                    href="{{ route('profil') }}"
                    class="{{ request()->routeIs('profil*') ? 'active' : '' }}"
                >

                    <span class="scp-menu-icon">
                        <i class="fa-regular fa-user"></i>
                    </span>

                    <span>
                        Profil
                    </span>

                </a>

            @endif


        </nav>


    {{-- =====================================================
         MENU DEFAULT
    ====================================================== --}}

    @else

        <div class="scp-menu-title">
            Menu
        </div>

        <nav class="scp-menu">


            @if(Route::has('dashboard'))

                <a
                    href="{{ route('dashboard') }}"
                    class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"
                >

                    <span class="scp-menu-icon">
                        <i class="fa-solid fa-house"></i>
                    </span>

                    <span>
                        Dashboard
                    </span>

                </a>

            @endif


            @if(Route::has('profil'))

                <a
                    href="{{ route('profil') }}"
                    class="{{ request()->routeIs('profil*') ? 'active' : '' }}"
                >

                    <span class="scp-menu-icon">
                        <i class="fa-regular fa-user"></i>
                    </span>

                    <span>
                        Profil
                    </span>

                </a>

            @endif


        </nav>

    @endif


    {{-- =====================================================
         LOGOUT
    ====================================================== --}}

    <div class="scp-sidebar-footer">

        <form
            method="POST"
            action="{{ route('logout') }}"
        >

            @csrf

            <button
                type="submit"
                class="scp-logout"
            >

                <span class="scp-menu-icon">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </span>

                <span>
                    Keluar
                </span>

            </button>

        </form>

    </div>


</aside>
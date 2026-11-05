{{-- SCP NAVBAR --}}

@php
    $navbarUser = auth()->user();
    $navbarRole = strtolower(trim($navbarUser->role ?? ''));

    $unreadNotifications = \Illuminate\Support\Facades\DB::table('notifikasi')
        ->where('userid', $navbarUser->id)
        ->where('sudahdibaca', false)
        ->count();
@endphp

<header class="topbar">

    <div class="topbar-left">

        <button
            type="button"
            class="circlebtn mobiletoggle"
            aria-label="Buka menu"
        >
            <i class="fa-solid fa-bars"></i>
        </button>

        <div class="topbar-title">
            <span>School Class Progress</span>

            <strong>
                @yield('page-title', 'Sistem Informasi Sekolah')
            </strong>
        </div>

    </div>

    <div class="topright">

        <div class="datechip">
            <span class="date-icon">
                <i class="fa-regular fa-calendar"></i>
            </span>

            <span>
                {{ now()->translatedFormat('l, d F Y') }}
            </span>
        </div>

        {{-- NOTIFIKASI --}}
        <a
            href="{{ route('notifikasi.index') }}"
            class="circlebtn"
            aria-label="Notifikasi"
            title="Notifikasi"
            style="position:relative;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;"
        >
            <i class="fa-regular fa-bell"></i>

            @if($unreadNotifications > 0)
                <span style="
                    position:absolute;
                    top:-4px;
                    right:-4px;
                    min-width:17px;
                    height:17px;
                    padding:0 4px;
                    border-radius:20px;
                    background:#ef4444;
                    color:#fff;
                    font-size:10px;
                    font-weight:700;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                ">
                    {{ $unreadNotifications > 99 ? '99+' : $unreadNotifications }}
                </span>
            @endif
        </a>

        {{-- AVATAR --}}
        @if($navbarRole === 'orangtua')

            <a
                href="{{ route('orangtua.profil') }}"
                class="top-avatar"
                title="Profil"
            >
                {{ strtoupper(substr($navbarUser->name ?? 'U', 0, 1)) }}
            </a>

        @else

            <a
                href="{{ route('profil') }}"
                class="top-avatar"
                title="Profil"
            >
                {{ strtoupper(substr($navbarUser->name ?? 'U', 0, 1)) }}
            </a>

        @endif

    </div>

</header>
{{-- =========================================================
     SCP - LAYOUT UTAMA
     File:
     resources/views/layouts/app.blade.php

     Fungsi:
     - Kerangka utama seluruh halaman setelah login
     - Sidebar
     - Header
     - Alert
     - Konten halaman
     - CSS dan JS halaman
========================================================= --}}

<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('title', 'SCP - School Class Progress')
    </title>


    {{-- =====================================================
         FONT
    ====================================================== --}}

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap"
        rel="stylesheet"
    >


    {{-- =====================================================
         FONT AWESOME
    ====================================================== --}}

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >


    {{-- =====================================================
         CSS UTAMA SCP
    ====================================================== --}}

    <link
        rel="stylesheet"
        href="{{ asset('scp-ui.css') }}"
    >


    {{-- =====================================================
         CSS TAMBAHAN HALAMAN
    ====================================================== --}}

    @stack('styles')

</head>


<body>


<div class="app">


    {{-- =====================================================
         SIDEBAR
    ====================================================== --}}

    @include('components.sidebar')


    {{-- =====================================================
         AREA UTAMA
    ====================================================== --}}

    <main class="main">


        {{-- =================================================
             NAVBAR / HEADER
        ================================================== --}}

        @include('components.navbar')


        {{-- =================================================
             KONTEN
        ================================================== --}}

        <section class="page-content">


            {{-- =================================================
                 ALERT BERHASIL
            ================================================== --}}

            @if(session('success'))

                <div class="alert alert-success">

                    <div class="alert-icon">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>

                    <div class="alert-content">

                        <strong>
                            Berhasil
                        </strong>

                        <span>
                            {{ session('success') }}
                        </span>

                    </div>

                    <button
                        type="button"
                        class="alert-close"
                        onclick="this.parentElement.remove()"
                    >
                        <i class="fa-solid fa-xmark"></i>
                    </button>

                </div>

            @endif


            {{-- =================================================
                 ALERT ERROR
            ================================================== --}}

            @if(session('error'))

                <div class="alert alert-danger">

                    <div class="alert-icon">
                        <i class="fa-solid fa-circle-exclamation"></i>
                    </div>

                    <div class="alert-content">

                        <strong>
                            Terjadi Kesalahan
                        </strong>

                        <span>
                            {{ session('error') }}
                        </span>

                    </div>

                    <button
                        type="button"
                        class="alert-close"
                        onclick="this.parentElement.remove()"
                    >
                        <i class="fa-solid fa-xmark"></i>
                    </button>

                </div>

            @endif


            {{-- =================================================
                 VALIDASI
            ================================================== --}}

            @if($errors->any())

                <div class="alert alert-danger">

                    <div class="alert-icon">
                        <i class="fa-solid fa-circle-exclamation"></i>
                    </div>

                    <div class="alert-content">

                        <strong>
                            Periksa kembali data
                        </strong>

                        <ul>

                            @foreach($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                    <button
                        type="button"
                        class="alert-close"
                        onclick="this.parentElement.remove()"
                    >
                        <i class="fa-solid fa-xmark"></i>
                    </button>

                </div>

            @endif


            {{-- =================================================
                 ISI HALAMAN
            ================================================== --}}

            @yield('content')


        </section>


    </main>


</div>


{{-- =========================================================
     OVERLAY MOBILE
========================================================= --}}

<div
    class="sidebar-overlay"
    id="sidebarOverlay"
></div>


{{-- =========================================================
     JAVASCRIPT
========================================================= --}}

@stack('scripts')


<script>

document.addEventListener('DOMContentLoaded', function () {


    /*
    |--------------------------------------------------------------------------
    | SIDEBAR MOBILE
    |--------------------------------------------------------------------------
    */

    const sidebar =
        document.querySelector('.scp-sidebar');

    const mobileToggle =
        document.querySelector('.mobiletoggle');

    const sidebarOverlay =
        document.getElementById('sidebarOverlay');


    if (mobileToggle && sidebar) {

        mobileToggle.addEventListener(
            'click',
            function () {

                sidebar.classList.toggle('show');

                if (sidebarOverlay) {

                    sidebarOverlay.classList.toggle('show');

                }

            }
        );

    }


    if (sidebarOverlay && sidebar) {

        sidebarOverlay.addEventListener(
            'click',
            function () {

                sidebar.classList.remove('show');

                sidebarOverlay.classList.remove('show');

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | AUTO HIDE ALERT
    |--------------------------------------------------------------------------
    */

    setTimeout(function () {

        document
            .querySelectorAll('.alert')
            .forEach(function (alert) {

                alert.classList.add('fade-out');

                setTimeout(function () {

                    if (alert) {
                        alert.remove();
                    }

                }, 350);

            });

    }, 5000);


});

</script>


</body>

</html>
@extends('layouts.app')

@section('title', 'Kelas Tanggung Jawab - BK | SCP')

@section('content')

<style>
    .bk-kelas-page {
        width: 100%;
    }

    /* =========================================================
       HEADER
    ========================================================= */

    .bk-kelas-header {
        margin-bottom: 20px;
    }

    .bk-kelas-header h1 {
        margin: 0;
        color: #172033;
        font-size: 26px;
        font-weight: 800;
        letter-spacing: -.4px;
    }

    .bk-kelas-header p {
        margin: 7px 0 0;
        color: #718096;
        font-size: 14px;
        line-height: 1.6;
    }


    /* =========================================================
       ALERT
    ========================================================= */

    .bk-alert {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        padding: 13px 15px;
        margin-bottom: 15px;
        border-radius: 12px;
        font-size: 13px;
        line-height: 1.5;
    }

    .bk-alert-icon {
        width: 30px;
        height: 30px;
        flex: 0 0 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        margin-top: -2px;
    }

    .bk-alert-success {
        background: #ecfdf3;
        border: 1px solid #d1fadf;
        color: #087443;
    }

    .bk-alert-success .bk-alert-icon {
        background: #d1fadf;
        color: #087443;
    }

    .bk-alert-error {
        background: #fef3f2;
        border: 1px solid #fecdca;
        color: #b42318;
    }

    .bk-alert-error .bk-alert-icon {
        background: #fecdca;
        color: #b42318;
    }

    .bk-alert ul {
        margin: 7px 0 0 18px;
        padding: 0;
    }


    /* =========================================================
       INFO
    ========================================================= */

    .bk-info {
        display: flex;
        align-items: flex-start;
        gap: 13px;
        padding: 16px 18px;
        margin-bottom: 16px;
        border-radius: 14px;
        background: #eff6ff;
        border: 1px solid #dbeafe;
        color: #175cd3;
    }

    .bk-info-icon {
        width: 38px;
        height: 38px;
        flex: 0 0 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        background: #dbeafe;
        color: #2563eb;
    }

    .bk-info-content strong {
        display: block;
        margin-bottom: 3px;
        color: #194185;
        font-size: 13px;
        font-weight: 800;
    }

    .bk-info-content span {
        color: #3565ae;
        font-size: 12px;
        line-height: 1.6;
    }


    /* =========================================================
       CARD
    ========================================================= */

    .bk-card {
        background: #ffffff;
        border: 1px solid #e6ebf2;
        border-radius: 16px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, .04);
    }

    .bk-card-header {
        padding: 18px 20px;
        border-bottom: 1px solid #edf1f5;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .bk-card-header-left h3 {
        margin: 0;
        color: #172033;
        font-size: 16px;
        font-weight: 800;
    }

    .bk-card-header-left p {
        margin: 5px 0 0;
        color: #8a94a6;
        font-size: 12px;
        line-height: 1.5;
    }

    .bk-card-body {
        padding: 20px;
    }


    /* =========================================================
       TOOLBAR
    ========================================================= */

    .bk-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 18px;
    }

    .bk-toolbar-info strong {
        display: block;
        color: #172033;
        font-size: 14px;
        font-weight: 800;
    }

    .bk-toolbar-info span {
        display: block;
        margin-top: 4px;
        color: #8a94a6;
        font-size: 11px;
    }

    .bk-select-all {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 12px;
        border-radius: 10px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #344054;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        user-select: none;
        white-space: nowrap;
    }

    .bk-select-all:hover {
        background: #f1f5f9;
    }

    .bk-select-all input {
        width: 16px;
        height: 16px;
        margin: 0;
        cursor: pointer;
        accent-color: #2563eb;
    }


    /* =========================================================
       KELAS GRID
    ========================================================= */

    .bk-kelas-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }

    .bk-kelas-card {
        position: relative;
        min-width: 0;
        padding: 18px;
        border: 1px solid #e2e8f0;
        border-radius: 15px;
        background: #ffffff;
        cursor: pointer;
        transition: .2s ease;
    }

    .bk-kelas-card:hover {
        border-color: #bfdbfe;
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(37, 99, 235, .07);
    }

    .bk-kelas-card.dipilih {
        border-color: #60a5fa;
        background: #f8fbff;
        box-shadow: 0 8px 22px rgba(37, 99, 235, .08);
    }

    .bk-kelas-check {
        position: absolute;
        top: 16px;
        right: 16px;
        z-index: 2;
    }

    .bk-kelas-check input {
        width: 18px;
        height: 18px;
        margin: 0;
        cursor: pointer;
        accent-color: #2563eb;
    }

    .bk-kelas-icon {
        width: 46px;
        height: 46px;
        margin-bottom: 15px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eff6ff;
        color: #2563eb;
        font-size: 16px;
        font-weight: 800;
    }

    .bk-kelas-card.dipilih .bk-kelas-icon {
        background: #dbeafe;
    }

    .bk-kelas-name {
        padding-right: 30px;
        color: #172033;
        font-size: 16px;
        font-weight: 800;
    }

    .bk-kelas-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 7px;
        margin-top: 7px;
        color: #8a94a6;
        font-size: 11px;
    }

    .bk-kelas-meta-item {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .bk-kelas-dot {
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: #aab5c5;
    }


    /* =========================================================
       FOOTER
    ========================================================= */

    .bk-kelas-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-top: 20px;
        padding-top: 18px;
        border-top: 1px solid #edf1f5;
    }

    .bk-selected-count {
        color: #8a94a6;
        font-size: 12px;
    }

    .bk-selected-count strong {
        color: #2563eb;
        font-size: 14px;
    }

    .bk-save-btn {
        min-height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 16px;
        border: 0;
        border-radius: 10px;
        background: #2563eb;
        color: #ffffff;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: .2s ease;
    }

    .bk-save-btn:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
    }


    /* =========================================================
       EMPTY
    ========================================================= */

    .bk-empty {
        grid-column: 1 / -1;
        padding: 55px 20px;
        border: 1px dashed #d8e0ea;
        border-radius: 15px;
        text-align: center;
        color: #98a2b3;
    }

    .bk-empty-icon {
        width: 52px;
        height: 52px;
        margin: 0 auto 13px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eff6ff;
        color: #2563eb;
        font-size: 20px;
    }

    .bk-empty strong {
        display: block;
        margin-bottom: 5px;
        color: #344054;
        font-size: 14px;
    }

    .bk-empty span {
        font-size: 12px;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1000px) {
        .bk-kelas-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 700px) {
        .bk-toolbar {
            align-items: stretch;
            flex-direction: column;
        }

        .bk-select-all {
            justify-content: center;
        }

        .bk-kelas-grid {
            grid-template-columns: 1fr;
        }

        .bk-kelas-footer {
            align-items: stretch;
            flex-direction: column;
        }

        .bk-save-btn {
            width: 100%;
        }
    }

    @media (max-width: 500px) {
        .bk-card-header,
        .bk-card-body {
            padding: 15px;
        }

        .bk-kelas-header h1 {
            font-size: 22px;
        }
    }
</style>


<div class="bk-kelas-page">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="bk-kelas-header">

        <h1>Kelas Tanggung Jawab</h1>

        <p>
            Pilih kelas yang menjadi tanggung jawab Anda sebagai BK.
        </p>

    </div>


    {{-- =========================================================
         SUCCESS
    ========================================================== --}}

    @if(session('success'))

        <div class="bk-alert bk-alert-success">

            <div class="bk-alert-icon">
                <i class="fa-solid fa-check"></i>
            </div>

            <div>
                {{ session('success') }}
            </div>

        </div>

    @endif


    {{-- =========================================================
         ERROR
    ========================================================== --}}

    @if(session('error'))

        <div class="bk-alert bk-alert-error">

            <div class="bk-alert-icon">
                <i class="fa-solid fa-xmark"></i>
            </div>

            <div>
                {{ session('error') }}
            </div>

        </div>

    @endif


    {{-- =========================================================
         VALIDATION ERROR
    ========================================================== --}}

    @if($errors->any())

        <div class="bk-alert bk-alert-error">

            <div class="bk-alert-icon">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>

            <div>

                <strong>
                    Terjadi kesalahan:
                </strong>

                <ul>

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        </div>

    @endif


    {{-- =========================================================
         INFO
    ========================================================== --}}

    <div class="bk-info">

        <div class="bk-info-icon">
            <i class="fa-solid fa-circle-info"></i>
        </div>

        <div class="bk-info-content">

            <strong>
                Pengaturan Kelas BK
            </strong>

            <span>
                Kelas yang Anda pilih akan digunakan untuk membatasi
                data siswa, presensi, perizinan, dan data lainnya
                yang dapat Anda pantau.
            </span>

        </div>

    </div>


    {{-- =========================================================
         FORM
    ========================================================== --}}

    <form
        action="{{ route('bk.kelas.simpan') }}"
        method="POST"
    >

        @csrf


        <div class="bk-card">

            <div class="bk-card-header">

                <div class="bk-card-header-left">

                    <h3>
                        Daftar Kelas
                    </h3>

                    <p>
                        Pilih kelas yang menjadi tanggung jawab Anda.
                    </p>

                </div>

            </div>


            <div class="bk-card-body">

                {{-- =================================================
                     DATA KELAS
                ================================================== --}}

                @php

                    /*
                    |--------------------------------------------------------------------------
                    | AMBIL DATA KELAS
                    |--------------------------------------------------------------------------
                    |
                    | Controller saat ini mengirim $semuaKelas.
                    | Fallback $kelas tetap dipertahankan agar view
                    | tidak mudah error jika data berasal dari nama
                    | variabel yang berbeda.
                    |
                    */

                    $daftarKelas = $semuaKelas ?? $kelas ?? collect();


                    /*
                    |--------------------------------------------------------------------------
                    | KELAS YANG SUDAH DIPILIH
                    |--------------------------------------------------------------------------
                    */

                    $kelasTerpilih =
                        $selectedKelas
                        ?? $kelasDipilih
                        ?? collect();


                    /*
                    |--------------------------------------------------------------------------
                    | UBAH MENJADI ARRAY ID
                    |--------------------------------------------------------------------------
                    */

                    $kelasTerpilihIds = collect($kelasTerpilih)
                        ->map(function ($item) {

                            if (is_object($item)) {

                                return $item->kelasid
                                    ?? $item->id
                                    ?? null;

                            }

                            return $item;

                        })
                        ->filter()
                        ->map(fn ($id) => (int) $id)
                        ->values()
                        ->toArray();

                @endphp


                <div class="bk-toolbar">

                    <div class="bk-toolbar-info">

                        <strong>
                            Pilih kelas
                        </strong>

                        <span>
                            Centang kelas yang menjadi tanggung jawab Anda.
                        </span>

                    </div>


                    <label class="bk-select-all">

                        <input
                            type="checkbox"
                            id="pilihSemua"
                        >

                        <span>
                            Pilih Semua
                        </span>

                    </label>

                </div>


                {{-- =================================================
                     GRID KELAS
                ================================================== --}}

                <div class="bk-kelas-grid">

                    @forelse($daftarKelas as $item)

                        @php

                            $kelasId =
                                $item->id
                                ?? $item->kelasid;


                            $namaKelas =
                                $item->namakelas
                                ?? 'Kelas';


                            $tingkat =
                                $item->tingkat
                                ?? '-';


                            $namaJurusan =
                                $item->namajurusan
                                ?? $item->jurusan_nama
                                ?? optional($item->jurusan)->namajurusan
                                ?? '-';


                            $kodeJurusan =
                                $item->kodejurusan
                                ?? $item->jurusan_kode
                                ?? '';


                            $dipilih = in_array(
                                (int) $kelasId,
                                $kelasTerpilihIds,
                                true
                            );

                        @endphp


                        <label
                            class="bk-kelas-card {{ $dipilih ? 'dipilih' : '' }}"
                            data-kelas-card
                        >

                            <div class="bk-kelas-check">

                                <input
                                    type="checkbox"
                                    name="kelasid[]"
                                    value="{{ $kelasId }}"
                                    class="bk-kelas-checkbox"
                                    {{ $dipilih ? 'checked' : '' }}
                                >

                            </div>


                            <div class="bk-kelas-icon">

                                <i class="fa-solid fa-school"></i>

                            </div>


                            <div class="bk-kelas-name">

                                {{ $namaKelas }}

                            </div>


                            <div class="bk-kelas-meta">

                                <span class="bk-kelas-meta-item">

                                    <i class="fa-solid fa-layer-group"></i>

                                    Tingkat {{ $tingkat }}

                                </span>


                                @if($kodeJurusan || $namaJurusan !== '-')

                                    <span class="bk-kelas-dot"></span>

                                    <span class="bk-kelas-meta-item">

                                        <i class="fa-solid fa-graduation-cap"></i>

                                        {{ $kodeJurusan ?: $namaJurusan }}

                                    </span>

                                @endif

                            </div>

                        </label>


                    @empty

                        <div class="bk-empty">

                            <div class="bk-empty-icon">
                                <i class="fa-solid fa-school"></i>
                            </div>

                            <strong>
                                Belum ada data kelas
                            </strong>

                            <span>
                                Data kelas belum tersedia di sistem.
                            </span>

                        </div>

                    @endforelse

                </div>


                {{-- =================================================
                     FOOTER
                ================================================== --}}

                @if($daftarKelas->count() > 0)

                    <div class="bk-kelas-footer">

                        <div class="bk-selected-count">

                            <strong id="jumlahDipilih">
                                {{ count($kelasTerpilihIds) }}
                            </strong>

                            kelas dipilih

                        </div>


                        <button
                            type="submit"
                            class="bk-save-btn"
                        >

                            <i class="fa-solid fa-floppy-disk"></i>

                            Simpan Kelas Tanggung Jawab

                        </button>

                    </div>

                @endif

            </div>

        </div>

    </form>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const pilihSemua =
        document.getElementById('pilihSemua');

    const checkboxKelas =
        document.querySelectorAll('.bk-kelas-checkbox');

    const cards =
        document.querySelectorAll('[data-kelas-card]');

    const jumlahDipilih =
        document.getElementById('jumlahDipilih');


    /*
    |--------------------------------------------------------------------------
    | UPDATE JUMLAH
    |--------------------------------------------------------------------------
    */

    function updateJumlah() {

        const jumlah =
            document.querySelectorAll(
                '.bk-kelas-checkbox:checked'
            ).length;


        if (jumlahDipilih) {
            jumlahDipilih.textContent = jumlah;
        }

    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE CARD
    |--------------------------------------------------------------------------
    */

    function updateCard(card, checkbox) {

        if (!card || !checkbox) {
            return;
        }

        if (checkbox.checked) {

            card.classList.add('dipilih');

        } else {

            card.classList.remove('dipilih');

        }

    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE PILIH SEMUA
    |--------------------------------------------------------------------------
    */

    function updatePilihSemua() {

        if (!pilihSemua) {
            return;
        }

        if (checkboxKelas.length === 0) {

            pilihSemua.checked = false;

            return;
        }

        const jumlahChecked =
            document.querySelectorAll(
                '.bk-kelas-checkbox:checked'
            ).length;


        pilihSemua.checked =
            jumlahChecked === checkboxKelas.length;

    }


    /*
    |--------------------------------------------------------------------------
    | CHECKBOX KELAS
    |--------------------------------------------------------------------------
    */

    checkboxKelas.forEach(function (checkbox) {

        checkbox.addEventListener(
            'change',
            function () {

                const card =
                    checkbox.closest('[data-kelas-card]');


                updateCard(
                    card,
                    checkbox
                );


                updateJumlah();

                updatePilihSemua();

            }
        );

    });


    /*
    |--------------------------------------------------------------------------
    | PILIH SEMUA
    |--------------------------------------------------------------------------
    */

    if (pilihSemua) {

        pilihSemua.addEventListener(
            'change',
            function () {

                checkboxKelas.forEach(
                    function (checkbox) {

                        checkbox.checked =
                            pilihSemua.checked;


                        const card =
                            checkbox.closest(
                                '[data-kelas-card]'
                            );


                        updateCard(
                            card,
                            checkbox
                        );

                    }
                );


                updateJumlah();

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | KLIK CARD
    |--------------------------------------------------------------------------
    */

    cards.forEach(function (card) {

        card.addEventListener(
            'click',
            function (event) {

                /*
                | Kalau yang diklik langsung checkbox,
                | browser sudah menangani perubahan statusnya.
                */

                if (
                    event.target.closest(
                        '.bk-kelas-check'
                    )
                ) {
                    return;
                }


                const checkbox =
                    card.querySelector(
                        '.bk-kelas-checkbox'
                    );


                if (!checkbox) {
                    return;
                }


                checkbox.checked =
                    !checkbox.checked;


                updateCard(
                    card,
                    checkbox
                );


                updateJumlah();

                updatePilihSemua();

            }
        );

    });


    /*
    |--------------------------------------------------------------------------
    | KONDISI AWAL
    |--------------------------------------------------------------------------
    */

    checkboxKelas.forEach(
        function (checkbox) {

            const card =
                checkbox.closest(
                    '[data-kelas-card]'
                );


            updateCard(
                card,
                checkbox
            );

        }
    );


    updateJumlah();

    updatePilihSemua();

});
</script>

@endsection
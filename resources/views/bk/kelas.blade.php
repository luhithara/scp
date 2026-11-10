@extends('layouts.app')

@section('title', 'Kelas Tanggung Jawab - BK | SCP')

@section('content')

@php
    $daftarKelas = $semuaKelas ?? collect();

    $kelasTerpilihIds = collect(
        $selectedKelas ?? $kelasIds ?? []
    )->map(function ($item) {
        if (is_object($item)) {
            return (int) ($item->kelasid ?? $item->id ?? 0);
        }

        return (int) $item;
    })->filter()->values()->toArray();

    $kelasTerpakaiIds = collect($kelasTerpakai ?? [])
        ->map(fn($id) => (int) $id)
        ->toArray();

    // Jangan sampai pilihan kelas yang sudah tersimpan hilang
    // ketika halaman dikembalikan karena validasi.
    $pilihanLama = old('kelasid');

    if ($pilihanLama !== null) {
        $kelasTerpilihIds = collect($pilihanLama)
            ->map(fn($id) => (int) $id)
            ->toArray();
    }
@endphp

<style>
    .bk-kelas-page {
        width: 100%;
    }

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

    /* ALERT */

    .bk-alert {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        padding: 13px 15px;
        margin-bottom: 15px;
        border-radius: 12px;
        font-size: 13px;
        line-height: 1.6;
    }

    .bk-alert-icon {
        width: 30px;
        height: 30px;
        flex: 0 0 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
    }

    .bk-alert-success {
        background: #ecfdf3;
        border: 1px solid #d1fadf;
        color: #087443;
    }

    .bk-alert-success .bk-alert-icon {
        background: #d1fadf;
    }

    .bk-alert-error {
        background: #fef3f2;
        border: 1px solid #fecdca;
        color: #b42318;
    }

    .bk-alert-error .bk-alert-icon {
        background: #fecdca;
    }

    .bk-alert ul {
        margin: 7px 0 0 18px;
        padding: 0;
    }

    /* INFO */

    .bk-info {
        display: flex;
        align-items: flex-start;
        gap: 13px;
        padding: 16px 18px;
        margin-bottom: 16px;
        border-radius: 14px;
        background: #eff6ff;
        border: 1px solid #dbeafe;
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

    /* CARD */

    .bk-card {
        background: #fff;
        border: 1px solid #e6ebf2;
        border-radius: 16px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, .04);
    }

    .bk-card-header {
        padding: 18px 20px;
        border-bottom: 1px solid #edf1f5;
    }

    .bk-card-header h3 {
        margin: 0;
        color: #172033;
        font-size: 16px;
        font-weight: 800;
    }

    .bk-card-header p {
        margin: 5px 0 0;
        color: #8a94a6;
        font-size: 12px;
    }

    .bk-card-body {
        padding: 20px;
    }

    /* TOOLBAR */

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
    }

    .bk-toolbar-info span {
        display: block;
        margin-top: 4px;
        color: #8a94a6;
        font-size: 11px;
    }

    .bk-toolbar-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
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
    }

    .bk-select-all input {
        width: 16px;
        height: 16px;
        margin: 0;
        accent-color: #2563eb;
    }

    /* GRID */

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
        background: #fff;
        cursor: pointer;
        transition: .2s ease;
    }

    .bk-kelas-card:hover {
        border-color: #93c5fd;
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(37, 99, 235, .07);
    }

    .bk-kelas-card.dipilih {
        border-color: #60a5fa;
        background: #f8fbff;
        box-shadow: 0 8px 22px rgba(37, 99, 235, .08);
    }

    /* KELAS TERKUNCI */

    .bk-kelas-card.terkunci {
        background: #f8fafc;
        border-color: #e2e8f0;
        cursor: not-allowed;
        opacity: .75;
        transform: none;
        box-shadow: none;
    }

    .bk-kelas-card.terkunci:hover {
        border-color: #e2e8f0;
        transform: none;
        box-shadow: none;
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

    .bk-kelas-card.terkunci input {
        cursor: not-allowed;
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
    }

    .bk-kelas-card.dipilih .bk-kelas-icon {
        background: #dbeafe;
    }

    .bk-kelas-card.terkunci .bk-kelas-icon {
        background: #e2e8f0;
        color: #64748b;
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

    .bk-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin-top: 14px;
        padding: 5px 9px;
        border-radius: 7px;
        font-size: 10px;
        font-weight: 800;
    }

    .bk-status-dipilih {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .bk-status-tersedia {
        background: #ecfdf3;
        color: #087443;
    }

    .bk-status-terkunci {
        background: #f1f5f9;
        color: #64748b;
    }

    /* FOOTER */

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
        font-size: 15px;
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
        color: white;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: .2s;
    }

    .bk-save-btn:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
    }

    /* EMPTY */

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

        .bk-toolbar-actions {
            justify-content: flex-start;
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

    {{-- HEADER --}}

    <div class="bk-kelas-header">
        <h1>Kelas Tanggung Jawab</h1>
        <p>
            Pilih kelas yang menjadi tanggung jawab Anda sebagai BK.
        </p>
    </div>


    {{-- SUCCESS --}}

    @if(session('success'))
        <div class="bk-alert bk-alert-success">
            <div class="bk-alert-icon">
                <i class="fa-solid fa-check"></i>
            </div>
            <div>{{ session('success') }}</div>
        </div>
    @endif


    {{-- ERROR --}}

    @if(session('error'))
        <div class="bk-alert bk-alert-error">
            <div class="bk-alert-icon">
                <i class="fa-solid fa-xmark"></i>
            </div>
            <div>{{ session('error') }}</div>
        </div>
    @endif


    {{-- VALIDATION ERROR --}}

    @if($errors->any())
        <div class="bk-alert bk-alert-error">
            <div class="bk-alert-icon">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>

            <div>
                <strong>Terjadi kesalahan:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif


    {{-- INFO --}}

    <div class="bk-info">
        <div class="bk-info-icon">
            <i class="fa-solid fa-circle-info"></i>
        </div>

        <div class="bk-info-content">
            <strong>Pengaturan Kelas BK</strong>
            <span>
                Pilih kelas yang menjadi tanggung jawab Anda.
                Kelas yang sudah dipilih BK lain akan terkunci
                dan tidak dapat dipilih kembali.
            </span>
        </div>
    </div>


    {{-- FORM --}}

    <form action="{{ route('bk.kelas.simpan') }}" method="POST">
        @csrf

        <div class="bk-card">

            <div class="bk-card-header">
                <h3>Daftar Kelas</h3>
                <p>
                    Kelas tersedia dapat dipilih dan diubah kapan saja.
                </p>
            </div>

            <div class="bk-card-body">

                {{-- TOOLBAR --}}

                <div class="bk-toolbar">

                    <div class="bk-toolbar-info">
                        <strong>Pilih kelas</strong>
                        <span>
                            Kelas yang terkunci sudah menjadi tanggung jawab BK lain.
                        </span>
                    </div>

                    <div class="bk-toolbar-actions">

                        <label class="bk-select-all">
                            <input
                                type="checkbox"
                                id="pilihSemua"
                            >
                            <span>Pilih Semua yang Tersedia</span>
                        </label>

                    </div>

                </div>


                {{-- GRID KELAS --}}

                <div class="bk-kelas-grid">

                    @forelse($daftarKelas as $item)

                        @php
                            $kelasId = (int) $item->id;

                            $namaKelas = $item->namakelas ?? 'Kelas';

                            $tingkat = $item->tingkat ?? '-';

                            $namaJurusan = $item->namajurusan ?? '-';

                            $kodeJurusan = $item->kodejurusan ?? '';

                            $dipilih = in_array(
                                $kelasId,
                                $kelasTerpilihIds,
                                true
                            );

                            $terkunci = in_array(
                                $kelasId,
                                $kelasTerpakaiIds,
                                true
                            );
                        @endphp

                        <label
                            class="
                                bk-kelas-card
                                {{ $dipilih ? 'dipilih' : '' }}
                                {{ $terkunci ? 'terkunci' : '' }}
                            "
                            data-kelas-card
                            data-terkunci="{{ $terkunci ? '1' : '0' }}"
                        >

                            <div class="bk-kelas-check">

                                <input
                                    type="checkbox"
                                    name="kelasid[]"
                                    value="{{ $kelasId }}"
                                    class="bk-kelas-checkbox"
                                    {{ $dipilih ? 'checked' : '' }}
                                    {{ $terkunci ? 'disabled' : '' }}
                                    aria-label="Pilih {{ $namaKelas }}"
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


                            {{-- STATUS KELAS --}}

                            @if($terkunci)

                                <span class="bk-status bk-status-terkunci">
                                    <i class="fa-solid fa-lock"></i>
                                    Sudah dipilih BK lain
                                </span>

                            @elseif($dipilih)

                                <span class="bk-status bk-status-dipilih">
                                    <i class="fa-solid fa-check-circle"></i>
                                    Kelas Anda
                                </span>

                            @else

                                <span class="bk-status bk-status-tersedia">
                                    <i class="fa-solid fa-circle-check"></i>
                                    Tersedia
                                </span>

                            @endif

                        </label>

                    @empty

                        <div class="bk-empty">
                            <div class="bk-empty-icon">
                                <i class="fa-solid fa-school"></i>
                            </div>

                            <strong>Belum ada data kelas</strong>

                            <span>
                                Data kelas belum tersedia di sistem.
                            </span>
                        </div>

                    @endforelse

                </div>


                {{-- FOOTER --}}

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

    const pilihSemua = document.getElementById('pilihSemua');

    const checkboxKelas = document.querySelectorAll(
        '.bk-kelas-checkbox'
    );

    const cards = document.querySelectorAll(
        '[data-kelas-card]'
    );

    const jumlahDipilih = document.getElementById(
        'jumlahDipilih'
    );


    /*
    |--------------------------------------------------------------------------
    | UPDATE JUMLAH KELAS
    |--------------------------------------------------------------------------
    */

    function updateJumlah() {

        const jumlah = document.querySelectorAll(
            '.bk-kelas-checkbox:checked'
        ).length;

        if (jumlahDipilih) {
            jumlahDipilih.textContent = jumlah;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE TAMPILAN CARD
    |--------------------------------------------------------------------------
    */

    function updateCard(card, checkbox) {

        if (!card || !checkbox) return;

        card.classList.toggle(
            'dipilih',
            checkbox.checked
        );

        const status = card.querySelector('.bk-status');

        if (!status) return;

        if (checkbox.checked) {

            status.className = 'bk-status bk-status-dipilih';

            status.innerHTML =
                '<i class="fa-solid fa-check-circle"></i> Kelas Anda';

        } else {

            status.className = 'bk-status bk-status-tersedia';

            status.innerHTML =
                '<i class="fa-solid fa-circle-check"></i> Tersedia';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE PILIH SEMUA
    |--------------------------------------------------------------------------
    */

    function updatePilihSemua() {

        if (!pilihSemua) return;

        const tersedia = Array.from(checkboxKelas)
            .filter(checkbox => !checkbox.disabled);

        const terpilih = tersedia.filter(
            checkbox => checkbox.checked
        );

        pilihSemua.checked =
            tersedia.length > 0 &&
            terpilih.length === tersedia.length;

        pilihSemua.indeterminate =
            terpilih.length > 0 &&
            terpilih.length < tersedia.length;
    }


    /*
    |--------------------------------------------------------------------------
    | PERUBAHAN CHECKBOX
    |--------------------------------------------------------------------------
    */

    checkboxKelas.forEach(function (checkbox) {

        checkbox.addEventListener('change', function () {

            const card = checkbox.closest('[data-kelas-card]');

            updateCard(card, checkbox);

            updateJumlah();

            updatePilihSemua();

        });

    });


    /*
    |--------------------------------------------------------------------------
    | PILIH SEMUA KELAS YANG TERSEDIA
    |--------------------------------------------------------------------------
    */

    if (pilihSemua) {

        pilihSemua.addEventListener('change', function () {

            checkboxKelas.forEach(function (checkbox) {

                // Kelas BK lain tidak boleh diubah
                if (checkbox.disabled) return;

                checkbox.checked = pilihSemua.checked;

                const card = checkbox.closest(
                    '[data-kelas-card]'
                );

                updateCard(card, checkbox);

            });

            updateJumlah();

            updatePilihSemua();

        });

    }


    /*
    |--------------------------------------------------------------------------
    | KLIK CARD
    |--------------------------------------------------------------------------
    */

    cards.forEach(function (card) {

        card.addEventListener('click', function (event) {

            const checkbox = card.querySelector(
                '.bk-kelas-checkbox'
            );

            if (!checkbox || checkbox.disabled) {

                event.preventDefault();

                return;
            }

            // Klik checkbox ditangani browser secara normal
            if (event.target.closest('.bk-kelas-check')) {
                return;
            }

            checkbox.checked = !checkbox.checked;

            updateCard(card, checkbox);

            updateJumlah();

            updatePilihSemua();

        });

    });


    /*
    |--------------------------------------------------------------------------
    | KONDISI AWAL
    |--------------------------------------------------------------------------
    */

    checkboxKelas.forEach(function (checkbox) {

        const card = checkbox.closest(
            '[data-kelas-card]'
        );

        if (checkbox.disabled) {
            card.classList.add('terkunci');
        }

        updateCard(card, checkbox);

    });

    updateJumlah();

    updatePilihSemua();

});
</script>

@endsection
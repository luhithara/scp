@extends('layouts.app')

@section('title', 'Jadwal Kelas - SCP')

@section('content')

<div class="page-header">

    <div>

        <div class="breadcrumb">

            <a href="{{ route('waka.jadwal') }}">
                Jadwal
            </a>

            <span>/</span>

            <a href="{{ route('waka.jadwal.angkatan', $tingkat) }}">
                Kelas {{ $tingkat }}
            </a>

            <span>/</span>

            <span>
                {{ $jurusan->namajurusan ?? '-' }}
            </span>

        </div>

        <h1>
            {{ $jurusan->namajurusan ?? 'Jurusan' }}
        </h1>

        <p>
            Pilih kelas untuk melihat jadwal pembelajaran.
        </p>

    </div>

    <a
        href="{{ route('waka.jadwal.angkatan', $tingkat) }}"
        class="btn-back"
    >
        ← Kembali
    </a>

</div>


<div class="info-card">

    <div class="info-icon">
        <i class="fa-solid fa-building"></i>
    </div>

    <div class="info-content">

        <span class="info-label">
            Jurusan
        </span>

        <strong>
            {{ $jurusan->namajurusan ?? '-' }}
        </strong>

    </div>

    <div class="info-content">

        <span class="info-label">
            Tingkat
        </span>

        <strong>
            Kelas {{ $tingkat }}
        </strong>

    </div>

    <div class="info-content">

        <span class="info-label">
            Jumlah Kelas
        </span>

        <strong>
            {{ $dataKelas->count() }} Kelas
        </strong>

    </div>

</div>


<div class="section-header">

    <div>

        <h2>
            Daftar Kelas
        </h2>

        <p>
            Pilih kelas untuk melihat jadwal lengkap.
        </p>

    </div>

</div>


<div class="class-grid">

    @forelse ($dataKelas as $kelas)

        <a
            href="{{ route('waka.jadwal.kelas', [
                'tingkat' => $tingkat,
                'jurusanId' => $jurusan->id,
                'kelasId' => $kelas->id
            ]) }}"
            class="class-card"
        >

            <div class="class-icon">
                <i class="fa-solid fa-door-open"></i>
            </div>


            <div class="class-info">

                <h3>
                    {{ $kelas->namakelas ?? 'Kelas' }}
                </h3>

                <p>
                    {{ $kelas->jumlah_jadwal ?? 0 }} jadwal pembelajaran
                </p>

            </div>


            <div class="class-arrow">

                <i class="fa-solid fa-chevron-right"></i>

            </div>

        </a>

    @empty

        <div class="empty-card">

            <div class="empty-icon">
                <i class="fa-solid fa-folder-open"></i>
            </div>

            <h3>
                Belum Ada Kelas
            </h3>

            <p>
                Belum terdapat data kelas untuk
                {{ $jurusan->namajurusan ?? 'jurusan ini' }}
                pada tingkat {{ $tingkat }}.
            </p>

        </div>

    @endforelse

</div>


<style>

/* =========================================================
   HEADER
   ========================================================= */

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 20px;
    margin-bottom: 25px;
}

.breadcrumb {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
    font-size: 13px;
    color: #8a98aa;
}

.breadcrumb a {
    color: #4b72a8;
    text-decoration: none;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.page-header h1 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
    color: #173b72;
}

.page-header p {
    margin: 7px 0 0;
    color: #718096;
    font-size: 14px;
}


/* =========================================================
   BUTTON BACK
   ========================================================= */

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    border-radius: 10px;
    background: #ffffff;
    border: 1px solid #dbe5f1;
    color: #2459a6;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    transition: .2s ease;
}

.btn-back:hover {
    background: #f3f7fc;
    border-color: #bfd0e6;
}


/* =========================================================
   INFO CARD
   ========================================================= */

.info-card {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 28px;
    padding: 20px 22px;
    background: #ffffff;
    border: 1px solid #e2eaf3;
    border-radius: 16px;
    box-shadow: 0 5px 18px rgba(30, 70, 110, .05);
}

.info-icon {
    width: 50px;
    height: 50px;
    min-width: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: #edf4ff;
    color: #2864b2;
    font-size: 19px;
}

.info-content {
    display: flex;
    flex-direction: column;
    gap: 5px;
    min-width: 150px;
}

.info-label {
    color: #8795a8;
    font-size: 12px;
}

.info-content strong {
    color: #284b78;
    font-size: 15px;
}


/* =========================================================
   SECTION HEADER
   ========================================================= */

.section-header {
    margin-bottom: 16px;
}

.section-header h2 {
    margin: 0;
    color: #1f3f6d;
    font-size: 20px;
}

.section-header p {
    margin: 5px 0 0;
    color: #7b8798;
    font-size: 13px;
}


/* =========================================================
   CLASS GRID
   ========================================================= */

.class-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
}


/* =========================================================
   CLASS CARD
   ========================================================= */

.class-card {
    display: flex;
    align-items: center;
    gap: 15px;
    min-height: 105px;
    padding: 21px;
    background: #ffffff;
    border: 1px solid #e2eaf3;
    border-radius: 16px;
    text-decoration: none;
    box-shadow: 0 5px 18px rgba(30, 70, 110, .05);
    transition: .2s ease;
}

.class-card:hover {
    transform: translateY(-2px);
    border-color: #b9cde6;
    box-shadow: 0 9px 25px rgba(30, 70, 110, .10);
}

.class-icon {
    width: 48px;
    height: 48px;
    min-width: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: #edf4ff;
    color: #2864b2;
    font-size: 18px;
}

.class-info {
    flex: 1;
}

.class-info h3 {
    margin: 0 0 6px;
    color: #1f3f6d;
    font-size: 17px;
    font-weight: 700;
}

.class-info p {
    margin: 0;
    color: #7b8798;
    font-size: 13px;
}

.class-arrow {
    color: #9aaabd;
    font-size: 13px;
}


/* =========================================================
   EMPTY
   ========================================================= */

.empty-card {
    grid-column: 1 / -1;
    padding: 60px 25px;
    text-align: center;
    background: #ffffff;
    border: 1px solid #e2eaf3;
    border-radius: 16px;
}

.empty-icon {
    width: 58px;
    height: 58px;
    margin: 0 auto 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 14px;
    background: #f2f5f9;
    color: #8795a8;
    font-size: 22px;
}

.empty-card h3 {
    margin: 0 0 8px;
    color: #34495e;
    font-size: 18px;
}

.empty-card p {
    max-width: 500px;
    margin: 0 auto;
    color: #8795a8;
    font-size: 14px;
}


/* =========================================================
   RESPONSIVE
   ========================================================= */

@media (max-width: 900px) {

    .class-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .info-card {
        flex-wrap: wrap;
    }

}


@media (max-width: 600px) {

    .page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .page-header h1 {
        font-size: 23px;
    }

    .class-grid {
        grid-template-columns: 1fr;
    }

    .info-card {
        align-items: flex-start;
        flex-direction: column;
    }

    .info-content {
        min-width: 0;
    }

}

</style>

@endsection
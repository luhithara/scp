@extends('layouts.app')

@section('title', 'Jadwal Berdasarkan Jurusan - SCP')

@section('content')

<div class="page-header">

    <div>
        <div class="breadcrumb">
            <a href="{{ route('waka.jadwal') }}">
                Jadwal
            </a>

            <span>/</span>

            <span>
                Kelas {{ $tingkat }}
            </span>
        </div>

        <h1>Jadwal Kelas {{ $tingkat }}</h1>

        <p>
            Pilih jurusan untuk melihat daftar kelas dan jadwal mengajar.
        </p>
    </div>

    <a href="{{ route('waka.jadwal') }}" class="btn-back">
        ← Kembali
    </a>

</div>


<div class="card-grid">

    @forelse ($daftarJurusan as $jurusan)

        <a
            href="{{ route('waka.jadwal.jurusan', [
                'tingkat' => $tingkat,
                'jurusanId' => $jurusan->id
            ]) }}"
            class="menu-card"
        >

            <div class="menu-icon">
                <i class="fa-solid fa-building"></i>
            </div>

            <div class="menu-content">

                <h3>
                    {{ $jurusan->namajurusan }}
                </h3>

                <p>
                    {{ $jurusan->jumlah_kelas ?? 0 }} kelas
                </p>

            </div>

            <div class="menu-arrow">
                <i class="fa-solid fa-chevron-right"></i>
            </div>

        </a>

    @empty

        <div class="empty-card">

            <div class="empty-icon">
                <i class="fa-solid fa-folder-open"></i>
            </div>

            <h3>Belum Ada Jurusan</h3>

            <p>
                Belum terdapat data jurusan untuk kelas {{ $tingkat }}.
            </p>

        </div>

    @endforelse

</div>


<style>

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 28px;
}

.breadcrumb {
    display: flex;
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

.card-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
}

.menu-card {
    position: relative;
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 22px;
    min-height: 105px;
    background: #ffffff;
    border: 1px solid #e2eaf3;
    border-radius: 16px;
    text-decoration: none;
    box-shadow: 0 5px 18px rgba(30, 70, 110, .05);
    transition: .2s ease;
}

.menu-card:hover {
    transform: translateY(-2px);
    border-color: #b9cde6;
    box-shadow: 0 9px 25px rgba(30, 70, 110, .10);
}

.menu-icon {
    width: 48px;
    height: 48px;
    min-width: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #edf4ff;
    color: #2864b2;
    font-size: 19px;
}

.menu-content {
    flex: 1;
}

.menu-content h3 {
    margin: 0 0 6px;
    color: #1f3f6d;
    font-size: 17px;
    font-weight: 700;
}

.menu-content p {
    margin: 0;
    color: #7b8798;
    font-size: 13px;
}

.menu-arrow {
    color: #9aaabd;
    font-size: 13px;
}

.empty-card {
    grid-column: 1 / -1;
    padding: 55px 25px;
    text-align: center;
    background: #ffffff;
    border: 1px solid #e2eaf3;
    border-radius: 16px;
}

.empty-icon {
    width: 55px;
    height: 55px;
    margin: 0 auto 15px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f2f5f9;
    color: #8795a8;
    font-size: 21px;
}

.empty-card h3 {
    margin: 0 0 7px;
    color: #34495e;
}

.empty-card p {
    margin: 0;
    color: #8795a8;
    font-size: 14px;
}

@media (max-width: 900px) {

    .card-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

}

@media (max-width: 600px) {

    .page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .card-grid {
        grid-template-columns: 1fr;
    }

    .page-header h1 {
        font-size: 23px;
    }

}

</style>

@endsection
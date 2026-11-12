import sys
import json
import re

import pdfplumber


DURASI_JAM = 45

HARI = [
    "senin",
    "selasa",
    "rabu",
    "kamis",
    "jumat",
]


def bersihkan(teks):
    if not teks:
        return ""

    return re.sub(r"\s+", " ", str(teks)).strip()


def waktu_menit(teks):
    teks = bersihkan(teks)

    try:
        jam, menit = map(int, teks.split(":"))
        return jam * 60 + menit
    except Exception:
        return None


def format_waktu(menit):
    jam = menit // 60
    sisa = menit % 60
    return f"{jam:02d}:{sisa:02d}"


def parse_sel(teks):
    teks = (teks or "").strip()

    if not teks or teks.lower() in ["-x-", "x"]:
        return None

    bagian = re.split(r'["“]', teks, maxsplit=1)

    if len(bagian) < 2:
        return None

    mapel = bersihkan(bagian[0])
    bagian_guru = bagian[1].replace('"', "").replace("”", "")

    baris = [
        bersihkan(x)
        for x in bagian_guru.splitlines()
        if bersihkan(x)
    ]

    if not mapel or not baris:
        return None

    baris[0] = re.sub(
        r"^\s*\d+\s*[\.\)]\s*",
        "",
        baris[0]
    )

    ruangan = None

    if len(baris) >= 2:
        calon_ruangan = baris[-1].upper()

        if re.match(
            r"^(OR|LAB|R)\s*[A-Z0-9 -]*$",
            calon_ruangan
        ):
            ruangan = calon_ruangan
            baris = baris[:-1]

    guru = bersihkan(" ".join(baris))

    if not guru:
        return None

    return {
        "namamapel": mapel,
        "namaguru": guru,
        "ruangan": ruangan,
    }


def proses_pdf(lokasi):
    hasil = []

    with pdfplumber.open(lokasi) as pdf:

        if len(pdf.pages) < 2:
            raise RuntimeError(
                "PDF tidak memiliki halaman jadwal kelas."
            )

        for nomor_halaman, halaman in enumerate(
            pdf.pages[1:],
            start=2
        ):

            tabel_ditemukan = halaman.find_tables()

            if not tabel_ditemukan:
                raise RuntimeError(
                    f"Tabel tidak ditemukan pada halaman {nomor_halaman}."
                )

            tabel = tabel_ditemukan[0]
            isi = tabel.extract()
            baris_tabel = tabel.rows

            if len(isi) < 12 or len(isi[0]) < 6:
                raise RuntimeError(
                    f"Struktur tabel halaman {nomor_halaman} tidak sesuai."
                )

            nama_kelas = bersihkan(isi[0][1])

            if not nama_kelas:
                raise RuntimeError(
                    f"Nama kelas kosong pada halaman {nomor_halaman}."
                )

            waktu_slot = []

            for indeks in range(2, 12):
                teks_waktu = bersihkan(isi[indeks][0])
                menit = waktu_menit(teks_waktu)

                if menit is None:
                    raise RuntimeError(
                        f"Waktu tidak valid pada halaman "
                        f"{nomor_halaman}, baris {indeks + 1}."
                    )

                # PERBAIKAN: menggunakan variabel menit.
                waktu_slot.append(menit)

            data_kelas = {
                "halaman": nomor_halaman,
                "kelas": nama_kelas,
                "jadwal": [],
            }

            for indeks_baris in range(2, 12):

                indeks_slot = indeks_baris - 2

                for indeks_hari in range(5):

                    kolom = indeks_hari + 1
                    teks = isi[indeks_baris][kolom]

                    if not teks:
                        continue

                    data_sel = parse_sel(teks)

                    if not data_sel:
                        continue

                    bbox = baris_tabel[indeks_baris].cells[kolom]

                    if not bbox:
                        raise RuntimeError(
                            f"Posisi sel tidak terbaca pada halaman "
                            f"{nomor_halaman}."
                        )

                    batas_bawah = bbox[3]
                    slot_akhir = indeks_slot

                    for kandidat in range(indeks_slot, 10):
                        bbox_waktu = baris_tabel[kandidat + 2].cells[0]

                        if not bbox_waktu:
                            continue

                        if abs(bbox_waktu[3] - batas_bawah) < 2:
                            slot_akhir = kandidat
                            break

                    mulai = waktu_slot[indeks_slot]
                    selesai = waktu_slot[slot_akhir] + DURASI_JAM

                    data_kelas["jadwal"].append({
                        "hari": HARI[indeks_hari],
                        "jamke": indeks_slot + 1,
                        "jamMulai": format_waktu(mulai),
                        "jamSelesai": format_waktu(selesai),
                        **data_sel,
                    })

            hasil.append(data_kelas)

    return hasil


if __name__ == "__main__":
    try:
        if len(sys.argv) < 2:
            raise RuntimeError(
                "Lokasi file PDF belum diberikan."
            )

        data = proses_pdf(sys.argv[1])

        print(json.dumps(
            {
                "berhasil": True,
                "jumlah_kelas": len(data),
                "data": data,
            },
            ensure_ascii=False
        ))

    except Exception as error:
        print(json.dumps(
            {
                "berhasil": False,
                "pesan": str(error),
            },
            ensure_ascii=False
        ))

        sys.exit(1)
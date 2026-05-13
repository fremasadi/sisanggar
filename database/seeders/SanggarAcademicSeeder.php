<?php

namespace Database\Seeders;

use App\Models\HasilUjianKelompok;
use App\Models\JadwalKelompok;
use App\Models\Kelompok;
use App\Models\KelompokPeserta;
use App\Models\Pelatih;
use App\Models\UjianKelompok;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SanggarAcademicSeeder extends Seeder
{
    public function run(): void
    {
        $pelatihA = User::updateOrCreate(
            ['email' => 'pelatih1@sisanggar.test'],
            [
                'name' => 'Pelatih Pemula',
                'no_hp' => '081111111111',
                'role' => 'pelatih',
                'status_aktif' => true,
                'password' => Hash::make('password123'),
            ]
        );

        $pelatihB = User::updateOrCreate(
            ['email' => 'pelatih2@sisanggar.test'],
            [
                'name' => 'Pelatih Lanjutan',
                'no_hp' => '082222222222',
                'role' => 'pelatih',
                'status_aktif' => true,
                'password' => Hash::make('password123'),
            ]
        );

        Pelatih::updateOrCreate(['id_pelatih' => $pelatihA->id], []);
        Pelatih::updateOrCreate(['id_pelatih' => $pelatihB->id], []);

        $peserta1 = $this->makePeserta('peserta1@sisanggar.test', 'Ayu Pemula', '083111111111');
        $peserta2 = $this->makePeserta('peserta2@sisanggar.test', 'Bima Pemula', '083222222222');
        $peserta3 = $this->makePeserta('peserta3@sisanggar.test', 'Citra Madya', '083333333333');
        $peserta4 = $this->makePeserta('peserta4@sisanggar.test', 'Dina Madya', '083444444444');
        $peserta5 = $this->makePeserta('peserta5@sisanggar.test', 'Eka Lanjut', '083555555555');

        $pemula = Kelompok::updateOrCreate(
            ['nama_kelompok' => 'Kelompok Pemula A'],
            [
                'level_urutan' => 1,
                'pelatih_id' => $pelatihA->id,
                'deskripsi' => 'Kelas dasar untuk peserta baru.',
                'status_aktif' => true,
            ]
        );

        $madya = Kelompok::updateOrCreate(
            ['nama_kelompok' => 'Kelompok Madya A'],
            [
                'level_urutan' => 2,
                'pelatih_id' => $pelatihA->id,
                'deskripsi' => 'Kelas menengah untuk peserta berkembang.',
                'status_aktif' => true,
            ]
        );

        $lanjut = Kelompok::updateOrCreate(
            ['nama_kelompok' => 'Kelompok Lanjut A'],
            [
                'level_urutan' => 3,
                'pelatih_id' => $pelatihB->id,
                'deskripsi' => 'Kelas lanjutan untuk peserta senior.',
                'status_aktif' => true,
            ]
        );

        $this->attachPeserta($pemula, $peserta1->id);
        $this->attachPeserta($pemula, $peserta2->id);
        $this->attachPeserta($madya, $peserta3->id);
        $this->attachPeserta($madya, $peserta4->id);
        $this->attachPeserta($lanjut, $peserta5->id);

        $this->makeJadwal($pemula->id, 'Senin', '15:00', '17:00', 'Studio 1');
        $this->makeJadwal($pemula->id, 'Rabu', '15:00', '17:00', 'Studio 1');
        $this->makeJadwal($madya->id, 'Selasa', '16:00', '18:00', 'Studio 2');
        $this->makeJadwal($lanjut->id, 'Jumat', '16:00', '18:00', 'Studio Utama');

        $ujian = UjianKelompok::updateOrCreate(
            [
                'kelompok_id' => $pemula->id,
                'nama_ujian' => 'Ujian Kenaikan Pemula',
            ],
            [
                'kelompok_tujuan_id' => $madya->id,
                'tanggal_ujian' => now()->addWeeks(2)->toDateString(),
                'jam_mulai' => '09:00',
                'lokasi' => 'Studio 1',
                'status' => 'dibuka',
                'keterangan' => 'Ujian kenaikan tingkat dari pemula ke madya.',
            ]
        );

        HasilUjianKelompok::updateOrCreate(
            ['ujian_kelompok_id' => $ujian->id, 'peserta_id' => $peserta1->id],
            ['hasil' => 'lulus', 'nilai' => 88, 'catatan' => 'Gerakan stabil.']
        );

        HasilUjianKelompok::updateOrCreate(
            ['ujian_kelompok_id' => $ujian->id, 'peserta_id' => $peserta2->id],
            ['hasil' => 'menunggu', 'nilai' => null, 'catatan' => 'Menunggu penilaian akhir.']
        );
    }

    private function makePeserta(string $email, string $name, string $noHp): User
    {
        return User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'no_hp' => $noHp,
                'role' => 'peserta',
                'status_aktif' => true,
                'password' => Hash::make('password123'),
            ]
        );
    }

    private function attachPeserta(Kelompok $kelompok, int $pesertaId): void
    {
        KelompokPeserta::updateOrCreate(
            [
                'kelompok_id' => $kelompok->id,
                'peserta_id' => $pesertaId,
            ],
            [
                'tanggal_masuk' => now()->subMonth()->toDateString(),
                'status' => 'aktif',
                'catatan' => 'Data demo peserta kelompok.',
            ]
        );
    }

    private function makeJadwal(int $kelompokId, string $hari, string $mulai, string $selesai, string $lokasi): void
    {
        JadwalKelompok::updateOrCreate(
            [
                'kelompok_id' => $kelompokId,
                'hari' => $hari,
                'jam_mulai' => $mulai,
            ],
            [
                'jam_selesai' => $selesai,
                'lokasi' => $lokasi,
                'catatan' => 'Jadwal latihan rutin.',
            ]
        );
    }
}

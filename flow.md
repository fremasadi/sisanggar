# Flow Aplikasi SIM-STARKSI

Dokumen ini menjelaskan alur utama aplikasi Sanggar Tari Kembang Sore berdasarkan role pengguna dan fitur yang tersedia.

## 1. Flow Umum

1. Pengunjung membuka halaman utama `/`.
2. Halaman utama menampilkan:
   - Hero dan informasi sanggar.
   - Galeri kegiatan.
   - Koleksi kostum yang tersedia.
3. Pengunjung dapat memasukkan kostum ke keranjang.
4. Pengunjung dapat melakukan booking kostum.
5. Pengunjung dapat mengecek riwayat booking melalui fitur pencarian riwayat booking.
6. Pengguna yang sudah login diarahkan ke dashboard sesuai role:
   - Admin.
   - Pelatih.
   - Peserta.

## 2. Flow Autentikasi

1. Pengguna login melalui halaman login.
2. Sistem memeriksa akun dan role pengguna.
3. Setelah login, pengguna masuk ke dashboard.
4. Akses fitur dibatasi menggunakan middleware role:
   - `admin` hanya dapat mengakses prefix `/admin`.
   - `pelatih` hanya dapat mengakses prefix `/pelatih`.
   - `peserta` hanya dapat mengakses prefix `/peserta`.

## 3. Flow Admin

Admin adalah role utama untuk mengelola master data dan operasional sanggar.

### 3.1 Manajemen Pengguna

1. Admin membuka menu `Pengguna`.
2. Admin dapat menambah, melihat, mengubah, dan menghapus data pengguna.
3. Pengguna dapat diberi role seperti admin, pelatih, atau peserta.

### 3.2 Manajemen Pelatih

1. Admin membuka menu `Pelatih`.
2. Admin mengelola data pelatih.
3. Data pelatih dapat dihubungkan ke kelompok.

### 3.3 Manajemen Kostum

1. Admin membuka menu `Kostum`.
2. Admin menambah data kostum beserta gambar.
3. Admin mengatur ukuran, harga sewa, stok, dan status ketersediaan.
4. Kostum dengan status `tersedia` tampil di halaman utama.
5. Kostum dapat dipesan oleh pengunjung melalui keranjang dan booking.

### 3.4 Manajemen Galeri

1. Admin membuka menu `Galeri`.
2. Admin dapat upload banyak gambar sekaligus.
3. Admin dapat mengatur:
   - Judul gambar.
   - Urutan tampil.
   - Status tampil atau disembunyikan.
4. Galeri yang aktif tampil di halaman utama sebelum section kostum.

### 3.5 Manajemen Booking

1. Admin membuka menu `Booking`.
2. Admin melihat daftar booking kostum.
3. Admin membuka detail booking.
4. Admin dapat mengubah status booking.
5. Admin dapat mengubah status verifikasi booking.
6. Sistem pembayaran menerima callback dari Midtrans untuk memperbarui status pembayaran.

### 3.6 Manajemen Kelompok

1. Admin membuka menu `Kelompok`.
2. Admin membuat kelompok dan mengatur:
   - Nama kelompok.
   - Jalur tingkatan.
   - Nomor tingkat.
   - Level urutan.
   - Pelatih.
   - Status aktif.
3. Admin membuka detail kelompok.
4. Pada detail kelompok, admin hanya mengelola anggota kelompok.
5. Admin dapat menambahkan satu atau banyak peserta ke kelompok.
6. Admin dapat mengubah status anggota:
   - Aktif.
   - Lulus.
   - Pindah.
   - Keluar.

### 3.7 Manajemen Jadwal Kelompok

1. Admin membuka menu `Jadwal Kelompok`.
2. Admin memilih kelompok.
3. Admin mengisi hari, jam mulai, jam selesai, lokasi, dan catatan.
4. Jadwal tersimpan sebagai jadwal latihan kelompok.
5. Admin dapat melihat daftar jadwal seluruh kelompok.

### 3.8 Manajemen Presensi

1. Admin membuka menu `Presensi`.
2. Admin memilih kelompok.
3. Admin membuat sesi presensi dengan tanggal, judul pertemuan, materi, dan catatan.
4. Sistem otomatis membuat detail presensi untuk semua anggota aktif kelompok.
5. Admin membuka detail presensi.
6. Admin dapat mengubah data presensi dan status kehadiran peserta.

### 3.9 Manajemen Ujian Kelompok

1. Admin membuka menu `Ujian Kelompok`.
2. Admin memilih kelompok.
3. Admin membuat ujian kelompok dengan:
   - Nama ujian.
   - Tanggal ujian.
   - Jam mulai.
   - Lokasi.
   - Status.
   - Kelompok tujuan.
   - Keterangan.
4. Sistem otomatis membuat data hasil ujian untuk anggota aktif kelompok.
5. Admin membuka detail ujian.
6. Admin mengubah hasil ujian peserta.
7. Jika peserta lulus, admin dapat menjalankan proses kenaikan tingkat.
8. Sistem memindahkan peserta lulus ke kelompok tujuan dan menandai status anggota lama sebagai lulus.

### 3.10 Manajemen SPP

1. Admin membuka menu `SPP`.
2. Admin dapat membuat atau generate tagihan SPP.
3. Tagihan SPP muncul di akun peserta.
4. Peserta dapat melakukan pembayaran tagihan.

### 3.11 Manajemen Sertifikat

1. Admin membuka menu `Sertifikat`.
2. Admin memilih peserta.
3. Admin mengisi nama sertifikat, tanggal terbit, catatan, dan upload file gambar sertifikat.
4. Sertifikat tersimpan untuk peserta yang dipilih.
5. Admin dapat melihat, memfilter, download, dan menghapus sertifikat.
6. Peserta yang dipilih dapat melihat sertifikat tersebut di menu `Sertifikat Saya`.

## 4. Flow Pelatih

Pelatih hanya mengelola data yang berkaitan dengan kelompok binaannya.

### 4.1 Peserta Binaan

1. Pelatih membuka menu `Peserta Binaan`.
2. Sistem menampilkan kelompok yang pelatih tersebut tangani.
3. Pelatih membuka detail kelompok.
4. Detail kelompok menampilkan daftar peserta yang dilatih.

### 4.2 Presensi Peserta

1. Pelatih membuka menu `Presensi Peserta`.
2. Pelatih memilih kelompok binaannya.
3. Pelatih membuat sesi presensi.
4. Sistem membuat detail presensi untuk anggota aktif kelompok tersebut.
5. Pelatih membuka detail presensi.
6. Pelatih mengubah status kehadiran peserta.
7. Pelatih hanya bisa mengakses presensi dari kelompok yang dia latih.

### 4.3 Nilai Ujian

1. Pelatih membuka menu `Nilai Ujian`.
2. Sistem menampilkan ujian dari kelompok yang dia latih.
3. Pelatih membuka detail ujian.
4. Pelatih menginput atau mengubah hasil ujian peserta.
5. Pelatih hanya dapat mengakses ujian dari kelompok binaannya.

## 5. Flow Peserta

Peserta hanya melihat data miliknya sendiri.

### 5.1 Tagihan SPP

1. Peserta membuka menu `Tagihan SPP`.
2. Sistem menampilkan daftar tagihan SPP milik peserta.
3. Peserta membuka detail tagihan.
4. Peserta melakukan pembayaran.
5. Sistem pembayaran memproses checkout dan status pembayaran.

### 5.2 Kelompok Saya

1. Peserta membuka menu `Kelompok Saya`.
2. Sistem menampilkan kelompok aktif peserta.
3. Peserta dapat melihat informasi kelompok, pelatih, jadwal, presensi, dan ujian yang relevan sesuai halaman peserta.

### 5.3 Presensi Saya

1. Peserta membuka menu `Presensi Saya`.
2. Sistem menampilkan riwayat presensi peserta.
3. Peserta dapat melihat status kehadiran pada setiap sesi presensi.

### 5.4 Ujian Saya

1. Peserta membuka menu `Ujian Saya`.
2. Sistem menampilkan daftar ujian yang terkait dengan peserta.
3. Peserta dapat melihat hasil ujian dan status kelulusannya.

### 5.5 Sertifikat Saya

1. Peserta membuka menu `Sertifikat Saya`.
2. Sistem menampilkan daftar sertifikat yang diupload admin untuk peserta tersebut.
3. Peserta dapat download file sertifikat.
4. Peserta tidak dapat melihat sertifikat peserta lain.

## 6. Flow Booking Kostum Pengunjung

1. Pengunjung membuka halaman utama.
2. Pengunjung memilih kostum yang tersedia.
3. Pengunjung menambahkan kostum ke keranjang.
4. Pengunjung membuka keranjang.
5. Pengunjung mengubah jumlah atau menghapus item jika diperlukan.
6. Pengunjung melakukan booking.
7. Sistem membuat data booking.
8. Pengunjung dapat melanjutkan pembayaran.
9. Midtrans mengirim callback pembayaran.
10. Sistem memperbarui status pembayaran.
11. Admin memverifikasi dan mengelola status booking.

## 7. Flow Kenaikan Tingkat

1. Admin membuat kelompok dengan jalur tingkatan dan nomor tingkat.
2. Sistem dapat mengenali kelompok berikutnya dalam jalur yang sama.
3. Admin membuat ujian kelompok.
4. Jika kelompok punya tingkatan berikutnya, tujuan ujian diarahkan ke kelompok tingkat berikutnya.
5. Admin atau pelatih mengisi hasil ujian peserta.
6. Admin menjalankan proses promosi.
7. Peserta yang lulus:
   - Status di kelompok lama menjadi `lulus`.
   - Dibuatkan anggota baru di kelompok tujuan dengan status `aktif`.
8. Peserta yang tidak lulus tetap berada di kelompok lama.

## 8. Ringkasan Hak Akses

| Role | Akses Utama |
| --- | --- |
| Guest | Melihat halaman utama, galeri, kostum, keranjang, booking, riwayat booking |
| Admin | Mengelola semua master data dan operasional |
| Pelatih | Mengelola presensi dan nilai ujian kelompok binaan |
| Peserta | Melihat kelompok, presensi, ujian, SPP, dan sertifikat miliknya |

## 9. Catatan Implementasi

1. File kostum dan galeri disimpan di folder publik agar dapat tampil langsung di frontend.
2. File sertifikat disimpan di storage private dan hanya dapat diunduh melalui route yang memeriksa hak akses.
3. Halaman detail kelompok admin dan pelatih dibuat fokus pada anggota atau peserta, sedangkan fitur jadwal, presensi, dan ujian dipisahkan ke menu masing-masing.
4. Setiap fitur utama memakai route berdasarkan role agar akses lebih jelas dan terpisah.
